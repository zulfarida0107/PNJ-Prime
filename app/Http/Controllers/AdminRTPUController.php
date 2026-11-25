<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\PortalUser;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRTPUController extends Controller
{
    /**
     * Memastikan hanya akun Admin RTPU (atau admin) yang mengakses modul ini,
     * dan mengembalikan profil PortalUser yang sesuai dari tabel `users`.
     */
    protected function resolvePortalUser(): PortalUser
    {
        $authUser = Auth::user();

        if ($authUser && ! in_array($authUser->role, ['rtpu', 'admin'], true)) {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Admin RTPU.');
        }

        $portalUser = null;
        if ($authUser) {
            $portalUser = PortalUser::where('email', $authUser->email)->first();
        }

        if (! $portalUser) {
            $portalUser = PortalUser::where('email', 'admin.rtpu@pnj.ac.id')->firstOrFail();
        }

        return $portalUser;
    }

    /**
     * Data bersama untuk sidebar & navbar Admin RTPU.
     */
    protected function sharedLayoutData(PortalUser $portalUser): array
    {
        $pendingOrdersCount = Order::whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])->count();
        $pendingB2cCount = Order::where('segment', 'b2c')
            ->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])
            ->count();
        $pendingB2bCount = Order::where('segment', 'b2b')
            ->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])
            ->count();
        $pendingCurationCount = Product::where('status', 'menunggu_kurasi_rtpu')->count();
        $pendingKycCount = PortalUser::where('role', 'mitra')->where('kyc_status', 'pending')->count();

        return [
            'portalUser' => $portalUser,
            'pendingOrdersCount' => $pendingOrdersCount,
            'pendingB2cCount' => $pendingB2cCount,
            'pendingB2bCount' => $pendingB2bCount,
            'pendingCurationCount' => $pendingCurationCount,
            'pendingKycCount' => $pendingKycCount,
        ];
    }

    /**
     * Hitung nominal total pesanan (prioritaskan grand_total invoice jika sudah ada).
     */
    protected function computeOrderValue(Order $order): float
    {
        if ($order->invoice) {
            return (float) $order->invoice->grand_total;
        }

        $subtotal = (float) $order->items->sum('subtotal');

        return round($subtotal * 1.11, 2);
    }

    /**
     * Hitung struktur keuangan presisi (Tarif item, Diskon B2B, Ongkir, DPP, PPN 11%, PPh 23 2%, e-Meterai, Total 100% Lunas).
     */
    protected function calculateOrderFinancials(Order $order): array
    {
        $itemsSubtotal = 0.0;
        $hasServiceOrLab = $order->order_type === 'lab_reservation';
        $isB2b = $order->segment === 'b2b';

        foreach ($order->items as $item) {
            $fallbackPrice = $isB2b ? ($item->product?->price_b2b ?? 0) : ($item->product?->price_b2c ?? 0);
            $unitPrice = (float) ($item->unit_price > 0 ? $item->unit_price : $fallbackPrice);
            $lineTotal = (float) ($item->subtotal > 0 ? $item->subtotal : ($unitPrice * $item->quantity));
            $itemsSubtotal += $lineTotal;

            $catCode = $item->product?->category?->code;
            if (in_array($catCode, ['JSA', 'LAB'], true)) {
                $hasServiceOrLab = true;
            }
        }

        if ($order->invoice) {
            $inv = $order->invoice;
            $grossSubtotal = (float) $inv->dpp > 0 ? (float) $inv->dpp : $itemsSubtotal;
            $discount = (float) $inv->discount_b2b;
            $shipping = (float) $inv->shipping_cost;
            $dppNet = max(0, $grossSubtotal - $discount + $shipping);
            $ppn = (float) $inv->ppn_amount;
            $pph23 = (float) $inv->pph23_amount;
            $emeterai = (float) $inv->emeterai_amount;
            $grandTotal = (float) $inv->grand_total;
        } else {
            $grossSubtotal = $itemsSubtotal;
            $discount = 0.0;
            $shipping = 0.0;
            $dppNet = $grossSubtotal;
            $ppn = round($dppNet * 0.11, 2);
            $pph23 = ($isB2b && $hasServiceOrLab) ? round($dppNet * 0.02, 2) : 0.0;
            $emeterai = $isB2b ? 10000.0 : 0.0;
            $grandTotal = $dppNet + $ppn - $pph23 + $emeterai;
        }

        return [
            'gross_subtotal' => $grossSubtotal,
            'discount_b2b' => $discount,
            'shipping_cost' => $shipping,
            'dpp_net' => $dppNet,
            'ppn_amount' => $ppn,
            'pph23_amount' => $pph23,
            'emeterai_amount' => $emeterai,
            'grand_total' => $grandTotal,
            'pph23_applicable' => $hasServiceOrLab,
        ];
    }

    /**
     * Siapkan payload JSON lengkap untuk Modal Invoice 03c & Modal Detail Berkas (B2C / B2B).
     */
    protected function buildOrderPayload(Order $order): array
    {
        $financials = $this->calculateOrderFinancials($order);
        $inv = $order->invoice;
        $user = $order->user;
        $isB2b = $order->segment === 'b2b';
        $firstItem = $order->items->first();
        $companyOrName = $isB2b ? ($user?->company_name ?: ($user?->name ?? '-')) : ($user?->name ?? '-');
        $serviceSummary = $order->items
            ->map(fn ($i) => $i->product?->name ?? 'Item')
            ->filter()
            ->implode(', ');

        $itemsList = $order->items->map(function ($i) use ($isB2b) {
            $fallback = $isB2b ? ($i->product?->price_b2b ?? 0) : ($i->product?->price_b2c ?? 0);
            $unitPrice = (float) ($i->unit_price > 0 ? $i->unit_price : $fallback);

            return [
                'name' => $i->product?->name ?? '-',
                'code' => $i->product?->code ?? '-',
                'unit' => $i->product?->unit?->name ?? '-',
                'category' => $i->product?->category?->name ?? '-',
                'quantity' => (int) $i->quantity,
                'unit_price' => $unitPrice,
                'subtotal' => (float) ($i->subtotal > 0 ? $i->subtotal : ($unitPrice * $i->quantity)),
            ];
        })->values()->all();

        $actionUrl = $isB2b
            ? route('admin.rtpu.validasi-b2b.action', $order)
            : route('admin.rtpu.validasi-b2c.action', $order);

        return [
            'id' => $order->id,
            'order_code' => $order->order_code,
            'segment' => $order->segment,
            'order_type' => $order->order_type,
            'status' => $order->status,
            'created_at' => optional($order->created_at)->translatedFormat('d M Y, H:i') . ' WIB',
            'booking_date' => $order->booking_date ? $order->booking_date->translatedFormat('d M Y') : null,
            'booking_slot' => $order->booking_slot,
            'estimasi_hari' => $order->estimasi_hari ?? 7,
            'shipping_address' => $order->shipping_address ?: ($user?->address ?? '-'),
            'attachment_name' => $order->attachment_path ? basename($order->attachment_path) : null,
            'notes' => $order->notes ?: 'Tidak ada catatan tambahan.',
            'customer_name' => $companyOrName,
            'pic_name' => $user?->name ?? '-',
            'email' => $user?->email ?? '-',
            'phone' => $user?->phone ?? '-',
            'nim_nip' => $user?->nim_nip ?? '-',
            'prodi_unit' => $user?->prodi_unit ?? '-',
            'nib' => $user?->nib ?? '-',
            'npwp' => $user?->npwp ?? '-',
            'kyc_status' => $user?->kyc_status ?? 'pending',
            'primary_unit' => $firstItem?->product?->unit?->name ?? '-',
            'service_summary' => $serviceSummary ?: '-',
            'items' => $itemsList,
            'financials' => $financials,
            'has_invoice' => (bool) $inv,
            'invoice_number' => $inv?->invoice_number ?? $this->generateInvoiceNumber($order),
            'va_number' => $inv?->va_number ?? $this->generateVaNumber($order),
            'payment_method' => $inv?->payment_method ?? ($isB2b ? 'va_mandiri_blu' : 'qris'),
            'payment_status' => $inv?->payment_status ?? 'unpaid',
            'due_at' => optional($inv?->due_at)->format('Y-m-d') ?? now()->addDays(7)->format('Y-m-d'),
            'due_at_label' => $inv?->due_at ? $inv->due_at->translatedFormat('d M Y') : now()->addDays(7)->translatedFormat('d M Y'),
            'paid_at_label' => $inv?->paid_at ? $inv->paid_at->translatedFormat('d M Y, H:i') . ' WIB' : '-',
            'qr_pass_code' => $inv?->qr_pass_code,
            'apply_pph23' => $inv ? ((float) $inv->pph23_amount > 0) : $financials['pph23_applicable'],
            'apply_emeterai' => $inv ? ((float) $inv->emeterai_amount > 0) : $isB2b,
            'invoice_submit_url' => route('admin.rtpu.validasi-b2b.invoice', $order),
            'action_url' => $actionUrl,
            'print_invoice_url' => route('admin.rtpu.print.invoice', $order),
        ];
    }

    /**
     * Terapkan filter pencarian ?q=... pada query Order.
     */
    protected function applyOrderSearch($query, string $search): void
    {
        if ($search === '') {
            return;
        }

        $query->where(function ($q) use ($search) {
            $q->where('order_code', 'like', "%{$search}%")
                ->orWhere('notes', 'like', "%{$search}%")
                ->orWhere('segment', 'like', "%{$search}%")
                ->orWhere('order_type', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nim_nip', 'like', "%{$search}%")
                        ->orWhere('prodi_unit', 'like', "%{$search}%");
                })
                ->orWhereHas('items.product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                })
                ->orWhereHas('invoice', fn ($inv) => $inv->where('invoice_number', 'like', "%{$search}%"));
        });
    }

    /**
     * Terapkan filter kategori (BRG / JSA / LAB) pada query Order.
     */
    protected function applyCategoryFilter($query, string $category): void
    {
        if (in_array($category, ['BRG', 'JSA', 'LAB'], true)) {
            $query->whereHas('items.product.category', fn ($c) => $c->where('code', $category));
        }
    }

    /**
     * Hitung jumlah order per kategori (Semua, BRG, JSA, LAB) untuk segmen tertentu atau semua.
     */
    protected function getCategoryCounts(?string $segment = null): array
    {
        $base = Order::query();
        if (in_array($segment, ['b2c', 'b2b'], true)) {
            $base->where('segment', $segment);
        }

        return [
            'semua' => (clone $base)->count(),
            'BRG' => (clone $base)->whereHas('items.product.category', fn ($c) => $c->where('code', 'BRG'))->count(),
            'JSA' => (clone $base)->whereHas('items.product.category', fn ($c) => $c->where('code', 'JSA'))->count(),
            'LAB' => (clone $base)->whereHas('items.product.category', fn ($c) => $c->where('code', 'LAB'))->count(),
        ];
    }

    /**
     * Ambil daftar log verifikasi terbaru untuk modal Log Verifikasi (B2C / B2B).
     */
    protected function getVerificationLogs(?string $segment = null)
    {
        $q = Order::with(['user', 'invoice', 'items.product.unit'])->latest('updated_at');
        if (in_array($segment, ['b2c', 'b2b'], true)) {
            $q->where('segment', $segment);
        }

        return $q->take(10)->get();
    }

    /**
     * Halaman 1: Dashboard Utama RTPU (Mockup 01).
     */
    public function dashboard(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $category = $request->query('category', 'semua');
        $tab = $request->query('tab', 'semua');
        $search = trim((string) $request->query('q', ''));

        // 4 KPI aktual database
        $totalOrders = Order::count();
        $recentOrdersToday = Order::where('created_at', '>=', now()->subDay())->count();
        $waitingValidationOrders = Order::whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])->count();
        $activeCatalogCount = Product::where('status', 'tayang')->count();
        $totalCatalogCount = Product::count();
        $validatedRevenue = (float) Invoice::where('payment_status', 'paid')->sum('grand_total');
        $totalInvoicedValue = (float) Invoice::sum('grand_total');

        $query = Order::with(['user', 'items.product.unit', 'items.product.category', 'invoice'])
            ->orderByRaw("CASE status
                WHEN 'menunggu_verifikasi' THEN 1
                WHEN 'menunggu_pembayaran' THEN 2
                WHEN 'diproses' THEN 3
                WHEN 'siap_diambil' THEN 4
                WHEN 'selesai' THEN 5
                WHEN 'dibatalkan' THEN 6
                ELSE 7 END")
            ->latest('updated_at');

        if ($tab === 'b2c') {
            $query->where('segment', 'b2c');
        } elseif ($tab === 'b2b') {
            $query->where('segment', 'b2b');
        } elseif ($tab === 'perlu_validasi') {
            $query->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran']);
        }

        $this->applyCategoryFilter($query, $category);
        $this->applyOrderSearch($query, $search);

        $recentOrders = $query->paginate(6)->withQueryString();
        $orderPayloads = [];
        foreach ($recentOrders as $ord) {
            $orderPayloads[$ord->id] = $this->buildOrderPayload($ord);
        }

        return view('admin-rtpu.dashboard', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'totalOrders' => $totalOrders,
                'recentOrdersToday' => $recentOrdersToday,
                'waitingValidationOrders' => $waitingValidationOrders,
                'activeCatalogCount' => $activeCatalogCount,
                'totalCatalogCount' => $totalCatalogCount,
                'validatedRevenue' => $validatedRevenue,
                'totalInvoicedValue' => $totalInvoicedValue,
                'recentOrders' => $recentOrders,
                'orderPayloads' => $orderPayloads,
                'categoryCounts' => $this->getCategoryCounts(),
                'activeCategory' => $category,
                'activeTab' => $tab,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman 2: Semua Antrean Validasi Pesanan & Terbitkan Invoice (Mockup 02).
     */
    public function antrean(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $segment = $request->query('segment', 'semua');
        $status = $request->query('status', 'semua');
        $category = $request->query('category', 'semua');
        $unitId = $request->query('unit_id');
        $orderType = $request->query('order_type', 'semua');
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, [5, 10, 20, 50], true)) {
            $perPage = 10;
        }
        $search = trim((string) $request->query('q', ''));

        $query = Order::with(['user', 'items.product.unit', 'items.product.category', 'invoice'])
            ->orderByRaw("CASE status
                WHEN 'menunggu_verifikasi' THEN 1
                WHEN 'menunggu_pembayaran' THEN 2
                WHEN 'diproses' THEN 3
                WHEN 'siap_diambil' THEN 4
                WHEN 'selesai' THEN 5
                WHEN 'dibatalkan' THEN 6
                ELSE 7 END")
            ->latest('created_at');

        if (in_array($segment, ['b2c', 'b2b'], true)) {
            $query->where('segment', $segment);
        }

        if ($status !== 'semua' && $status !== '') {
            if ($status === 'perlu_validasi') {
                $query->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran']);
            } else {
                $query->where('status', $status);
            }
        }

        if (in_array($orderType, ['ready_stock', 'pre_order', 'lab_reservation'], true)) {
            $query->where('order_type', $orderType);
        }

        if ($unitId) {
            $query->whereHas('items.product', fn ($p) => $p->where('unit_id', $unitId));
        }

        $this->applyCategoryFilter($query, $category);
        $this->applyOrderSearch($query, $search);

        $orders = $query->paginate($perPage)->withQueryString();
        $orderPayloads = [];
        foreach ($orders as $ord) {
            $orderPayloads[$ord->id] = $this->buildOrderPayload($ord);
        }

        $units = Unit::orderBy('name')->get();

        return view('admin-rtpu.antrean', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'orders' => $orders,
                'orderPayloads' => $orderPayloads,
                'units' => $units,
                'categoryCounts' => $this->getCategoryCounts($segment !== 'semua' ? $segment : null),
                'segment' => $segment,
                'status' => $status,
                'activeCategory' => $category,
                'selectedUnitId' => $unitId,
                'orderType' => $orderType,
                'perPage' => $perPage,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman 3a: Verifikasi Pesanan Mahasiswa (B2C) — Full-Width (Mockup 03a).
     */
    public function validasiB2cIndex(Request $request): View
    {
        return $this->renderValidasiB2c($request, null);
    }

    /**
     * Halaman 3b: Verifikasi Pesanan B2C dengan auto-open modal detail untuk {order}.
     */
    public function validasiB2c(Request $request, Order $order): View|RedirectResponse
    {
        if ($order->segment !== 'b2c') {
            return redirect()->route('admin.rtpu.validasi-b2b', $order);
        }

        return $this->renderValidasiB2c($request, $order);
    }

    protected function renderValidasiB2c(Request $request, ?Order $focusOrder): View
    {
        $portalUser = $this->resolvePortalUser();

        $category = $request->query('category', 'semua');
        $search = trim((string) $request->query('q', ''));

        $query = Order::with(['user', 'items.product.unit', 'items.product.category', 'items.product.subCategory', 'invoice'])
            ->where('segment', 'b2c')
            ->orderByRaw("CASE status
                WHEN 'menunggu_verifikasi' THEN 1
                WHEN 'menunggu_pembayaran' THEN 2
                WHEN 'diproses' THEN 3
                WHEN 'siap_diambil' THEN 4
                WHEN 'selesai' THEN 5
                ELSE 6 END")
            ->latest('updated_at');

        $this->applyCategoryFilter($query, $category);
        $this->applyOrderSearch($query, $search);

        $b2cOrders = $query->paginate(8)->withQueryString();
        $orderPayloads = [];
        foreach ($b2cOrders as $ord) {
            $orderPayloads[$ord->id] = $this->buildOrderPayload($ord);
        }

        $autoOpenPayload = null;
        if ($focusOrder) {
            $focusOrder->load(['user', 'items.product.unit', 'items.product.category', 'items.product.subCategory', 'invoice']);
            $autoOpenPayload = $this->buildOrderPayload($focusOrder);
            $orderPayloads[$focusOrder->id] = $autoOpenPayload;
        }

        $b2cStats = [
            'pending' => Order::where('segment', 'b2c')->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])->count(),
            'total_items' => (int) DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.segment', 'b2c')
                ->sum('order_items.quantity'),
            'active_items_processing' => (int) DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.segment', 'b2c')
                ->whereIn('orders.status', ['diproses', 'siap_diambil'])
                ->sum('order_items.quantity'),
            'active_va' => Invoice::whereHas('order', fn ($q) => $q->where('segment', 'b2c'))->count(),
            'total_b2c_orders' => Order::where('segment', 'b2c')->count(),
        ];

        return view('admin-rtpu.validasi-b2c', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'order' => $focusOrder,
                'b2cOrders' => $b2cOrders,
                'orderPayloads' => $orderPayloads,
                'autoOpenPayload' => $autoOpenPayload,
                'b2cStats' => $b2cStats,
                'categoryCounts' => $this->getCategoryCounts('b2c'),
                'activeCategory' => $category,
                'verificationLogs' => $this->getVerificationLogs('b2c'),
                'search' => $search,
            ]
        ));
    }

    /**
     * Eksekusi Aksi Validasi Pesanan B2C (Simpan Nyata ke Database).
     */
    public function actionB2c(Request $request, Order $order): RedirectResponse
    {
        $this->resolvePortalUser();

        if ($order->segment !== 'b2c') {
            return back()->with('error', 'Pesanan ini bukan termasuk kanal B2C.');
        }

        $validated = $request->validate([
            'action' => ['required', Rule::in(['approve', 'mark_paid_process', 'mark_ready', 'mark_done', 'reject'])],
            'rejection_reason' => ['required_if:action,reject', 'nullable', 'string', 'min:10', 'max:500'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi minimal 10 karakter.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter agar jelas bagi pemesan.',
        ]);

        $order->load(['items.product', 'invoice']);
        $action = $validated['action'];

        DB::transaction(function () use ($order, $action, $validated) {
            if ($action === 'approve') {
                if (! $order->invoice) {
                    $dpp = (float) $order->items->sum('subtotal');
                    $ppn = round($dpp * 0.11, 2);
                    $grandTotal = $dpp + $ppn;

                    Invoice::create([
                        'invoice_number' => $this->generateInvoiceNumber($order),
                        'order_id' => $order->id,
                        'dpp' => $dpp,
                        'discount_b2b' => 0,
                        'shipping_cost' => 0,
                        'ppn_amount' => $ppn,
                        'pph23_amount' => 0,
                        'emeterai_amount' => 0,
                        'grand_total' => $grandTotal,
                        'va_number' => $this->generateVaNumber($order),
                        'payment_method' => 'qris',
                        'payment_status' => 'unpaid',
                        'due_at' => now()->addDays(2),
                    ]);
                }

                $nextStatus = ($order->invoice && $order->invoice->payment_status === 'paid')
                    ? 'diproses'
                    : 'menunggu_pembayaran';

                $order->update([
                    'status' => $nextStatus,
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Pesanan B2C telah diverifikasi dan disetujui Admin RTPU.'),
                ]);
            } elseif ($action === 'mark_paid_process') {
                if ($order->invoice) {
                    $order->invoice->update([
                        'payment_status' => 'paid',
                        'paid_at' => $order->invoice->paid_at ?? now(),
                        'qr_pass_code' => $order->invoice->qr_pass_code ?? ('QR-PNJ-PRIME-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT)),
                    ]);
                }

                $order->update([
                    'status' => 'diproses',
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Pembayaran B2C terkonfirmasi lunas 100%. Pesanan diteruskan ke unit pengelola untuk diproses.'),
                ]);
            } elseif ($action === 'mark_ready') {
                $order->update([
                    'status' => 'siap_diambil',
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Pesanan B2C telah selesai diproses unit dan siap diambil/digunakan.'),
                ]);
            } elseif ($action === 'mark_done') {
                if ($order->invoice && $order->invoice->payment_status !== 'paid') {
                    $order->invoice->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }

                $order->update([
                    'status' => 'selesai',
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Pesanan B2C telah diselesaikan dan diserahterimakan.'),
                ]);
            } elseif ($action === 'reject') {
                if ($order->invoice && $order->invoice->payment_status === 'unpaid') {
                    $order->invoice->update(['payment_status' => 'expired']);
                }

                $order->update([
                    'status' => 'dibatalkan',
                    'notes' => $this->appendAdminNote($order->notes, 'DITOLAK ADMIN RTPU: ' . trim((string) $validated['rejection_reason'])),
                ]);
            }
        });

        $messages = [
            'approve' => "Pesanan B2C {$order->order_code} berhasil diverifikasi dan invoice resmi diterbitkan.",
            'mark_paid_process' => "Pembayaran pesanan {$order->order_code} terkonfirmasi lunas 100% dan status diperbarui menjadi Diproses.",
            'mark_ready' => "Pesanan {$order->order_code} ditandai Siap Diambil oleh pemesan.",
            'mark_done' => "Pesanan {$order->order_code} telah ditandai Selesai.",
            'reject' => "Pesanan {$order->order_code} telah ditolak beserta catatan penolakan resmi.",
        ];

        return back()->with('success', $messages[$action]);
    }

    /**
     * Halaman 4a: Verifikasi Pesanan B2B — Full-Width (Mockup 03b).
     */
    public function validasiB2bIndex(Request $request): View
    {
        return $this->renderValidasiB2b($request, null);
    }

    /**
     * Halaman 4b: Verifikasi Pesanan B2B dengan auto-open modal detail untuk {order}.
     */
    public function validasiB2b(Request $request, Order $order): View|RedirectResponse
    {
        if ($order->segment !== 'b2b') {
            return redirect()->route('admin.rtpu.validasi-b2c', $order);
        }

        return $this->renderValidasiB2b($request, $order);
    }

    protected function renderValidasiB2b(Request $request, ?Order $focusOrder): View
    {
        $portalUser = $this->resolvePortalUser();

        $category = $request->query('category', 'semua');
        $search = trim((string) $request->query('q', ''));

        $query = Order::with(['user', 'items.product.unit', 'items.product.category', 'items.product.subCategory', 'invoice'])
            ->where('segment', 'b2b')
            ->orderByRaw("CASE status
                WHEN 'menunggu_verifikasi' THEN 1
                WHEN 'menunggu_pembayaran' THEN 2
                WHEN 'diproses' THEN 3
                WHEN 'siap_diambil' THEN 4
                WHEN 'selesai' THEN 5
                ELSE 6 END")
            ->latest('updated_at');

        $this->applyCategoryFilter($query, $category);
        $this->applyOrderSearch($query, $search);

        $b2bOrders = $query->paginate(8)->withQueryString();
        $orderPayloads = [];
        foreach ($b2bOrders as $ord) {
            $orderPayloads[$ord->id] = $this->buildOrderPayload($ord);
        }

        $autoOpenPayload = null;
        if ($focusOrder) {
            $focusOrder->load(['user', 'items.product.unit', 'items.product.category', 'items.product.subCategory', 'invoice']);
            $autoOpenPayload = $this->buildOrderPayload($focusOrder);
            $orderPayloads[$focusOrder->id] = $autoOpenPayload;
        }

        $b2bStats = [
            'pending' => Order::where('segment', 'b2b')->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])->count(),
            'queue_value' => (float) Invoice::whereHas('order', fn ($q) => $q->where('segment', 'b2b'))->sum('grand_total'),
            'issued_invoices' => Invoice::whereHas('order', fn ($q) => $q->where('segment', 'b2b'))->count(),
            'paid_revenue' => (float) Invoice::where('payment_status', 'paid')
                ->whereHas('order', fn ($q) => $q->where('segment', 'b2b'))
                ->sum('grand_total'),
            'total_b2b_orders' => Order::where('segment', 'b2b')->count(),
        ];

        return view('admin-rtpu.validasi-b2b', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'order' => $focusOrder,
                'b2bOrders' => $b2bOrders,
                'orderPayloads' => $orderPayloads,
                'autoOpenPayload' => $autoOpenPayload,
                'b2bStats' => $b2bStats,
                'categoryCounts' => $this->getCategoryCounts('b2b'),
                'activeCategory' => $category,
                'verificationLogs' => $this->getVerificationLogs('b2b'),
                'search' => $search,
            ]
        ));
    }

    /**
     * Terbitkan atau Perbarui Invoice Resmi B2B (100% Lunas, Tanpa DP).
     */
    public function issueInvoiceB2b(Request $request, Order $order): RedirectResponse
    {
        $this->resolvePortalUser();

        if ($order->segment !== 'b2b') {
            return back()->with('error', 'Penerbitan invoice korporasi hanya berlaku untuk pesanan B2B.');
        }

        $validated = $request->validate([
            'discount_type' => ['required', Rule::in(['nominal', 'percent'])],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'estimasi_hari' => ['nullable', 'integer', 'min:1', 'max:180'],
            'due_at' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(['va_mandiri_blu', 'qris'])],
            'apply_pph23' => ['nullable', 'boolean'],
            'apply_emeterai' => ['nullable', 'boolean'],
            'transfer_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'due_at.required' => 'Tanggal jatuh tempo pembayaran wajib diisi.',
            'due_at.date' => 'Format tanggal jatuh tempo tidak valid.',
            'payment_method.required' => 'Metode penerimaan BLU wajib dipilih.',
        ]);

        $order->load(['items.product.category', 'invoice']);

        $grossSubtotal = 0.0;
        foreach ($order->items as $item) {
            $b2bPrice = (float) ($item->unit_price > 0 ? $item->unit_price : ($item->product?->price_b2b ?? 0));
            $grossSubtotal += (float) ($item->subtotal > 0 ? $item->subtotal : ($b2bPrice * $item->quantity));
        }

        $discountInput = (float) ($validated['discount_value'] ?? 0);
        if ($validated['discount_type'] === 'percent') {
            $clampedPercent = min(max($discountInput, 0), 30);
            $discountAmount = round($grossSubtotal * ($clampedPercent / 100), 2);
        } else {
            $discountAmount = min($discountInput, $grossSubtotal);
        }

        $shippingCost = (float) ($validated['shipping_cost'] ?? 0);
        $dppNet = max(0, $grossSubtotal - $discountAmount + $shippingCost);
        $ppnAmount = round($dppNet * 0.11, 2);
        $pph23Amount = $request->boolean('apply_pph23') ? round($dppNet * 0.02, 2) : 0.0;
        $emeteraiAmount = $request->boolean('apply_emeterai') ? 10000.0 : 0.0;
        $grandTotal = max(0, $dppNet + $ppnAmount - $pph23Amount + $emeteraiAmount);

        $invoiceNumber = $order->invoice?->invoice_number ?? $this->generateInvoiceNumber($order);
        $vaNumber = $order->invoice?->va_number ?? $this->generateVaNumber($order);
        $dueAt = Carbon::parse($validated['due_at'])->endOfDay();

        DB::transaction(function () use (
            $order,
            $invoiceNumber,
            $grossSubtotal,
            $discountAmount,
            $shippingCost,
            $ppnAmount,
            $pph23Amount,
            $emeteraiAmount,
            $grandTotal,
            $vaNumber,
            $validated,
            $dueAt
        ) {
            Invoice::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'invoice_number' => $invoiceNumber,
                    'dpp' => $grossSubtotal,
                    'discount_b2b' => $discountAmount,
                    'shipping_cost' => $shippingCost,
                    'ppn_amount' => $ppnAmount,
                    'pph23_amount' => $pph23Amount,
                    'emeterai_amount' => $emeteraiAmount,
                    'grand_total' => $grandTotal,
                    'va_number' => $vaNumber,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $order->invoice?->payment_status === 'paid' ? 'paid' : 'unpaid',
                    'due_at' => $dueAt,
                ]
            );

            $orderUpdate = [
                'estimasi_hari' => $validated['estimasi_hari'] ?? $order->estimasi_hari ?? 7,
            ];

            if (! in_array($order->status, ['diproses', 'siap_diambil', 'selesai'], true)) {
                $orderUpdate['status'] = 'menunggu_pembayaran';
            }

            if (! empty($validated['transfer_notes'])) {
                $orderUpdate['notes'] = $this->appendAdminNote(
                    $order->notes,
                    'Catatan Invoice (' . $invoiceNumber . '): ' . trim((string) $validated['transfer_notes'])
                );
            }

            $order->update($orderUpdate);
        });

        return back()
            ->with('success', "Invoice resmi BLU {$invoiceNumber} (Skema 100% Lunas) berhasil diterbitkan dan tersimpan untuk pesanan {$order->order_code}.");
    }

    /**
     * Eksekusi Aksi Status Pesanan B2B (Pelunasan 100%, Proses, Siap Diambil, Selesai, Tolak).
     */
    public function actionB2b(Request $request, Order $order): RedirectResponse
    {
        $this->resolvePortalUser();

        if ($order->segment !== 'b2b') {
            return back()->with('error', 'Pesanan ini bukan termasuk kanal B2B.');
        }

        $validated = $request->validate([
            'action' => ['required', Rule::in(['confirm_paid', 'mark_ready', 'mark_done', 'reject'])],
            'rejection_reason' => ['required_if:action,reject', 'nullable', 'string', 'min:10', 'max:500'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi minimal 10 karakter.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $order->load('invoice');
        $action = $validated['action'];

        DB::transaction(function () use ($order, $action, $validated) {
            if ($action === 'confirm_paid') {
                if ($order->invoice) {
                    $order->invoice->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'qr_pass_code' => $order->invoice->qr_pass_code ?? ('QR-PNJ-B2B-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT)),
                    ]);
                }

                $order->update([
                    'status' => 'diproses',
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Pelunasan 100% BLU PNJ terkonfirmasi. Pesanan B2B mulai diproses unit.'),
                ]);
            } elseif ($action === 'mark_ready') {
                $order->update([
                    'status' => 'siap_diambil',
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Hasil layanan/produk B2B siap diserahterimakan kepada mitra.'),
                ]);
            } elseif ($action === 'mark_done') {
                if ($order->invoice && $order->invoice->payment_status !== 'paid') {
                    $order->invoice->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }

                $order->update([
                    'status' => 'selesai',
                    'notes' => $this->appendAdminNote($order->notes, $validated['admin_note'] ?? 'Pesanan B2B telah selesai dan berita acara serah terima tercatat.'),
                ]);
            } elseif ($action === 'reject') {
                if ($order->invoice && $order->invoice->payment_status === 'unpaid') {
                    $order->invoice->update(['payment_status' => 'expired']);
                }

                $order->update([
                    'status' => 'dibatalkan',
                    'notes' => $this->appendAdminNote($order->notes, 'DITOLAK ADMIN RTPU: ' . trim((string) $validated['rejection_reason'])),
                ]);
            }
        });

        $messages = [
            'confirm_paid' => "Pelunasan 100% pesanan B2B {$order->order_code} berhasil dikonfirmasi. Status diubah menjadi Diproses.",
            'mark_ready' => "Pesanan B2B {$order->order_code} ditandai Siap Diambil / Diserahterimakan.",
            'mark_done' => "Pesanan B2B {$order->order_code} telah ditandai Selesai.",
            'reject' => "Pesanan B2B {$order->order_code} telah ditolak beserta catatan penolakan resmi.",
        ];

        return back()->with('success', $messages[$action]);
    }

    /**
     * Ekspor Rekap Antrean Pesanan ke CSV (Kompatibel Excel, UTF-8 BOM).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $this->resolvePortalUser();

        $segment = $request->query('segment', 'semua');
        $status = $request->query('status', 'semua');
        $category = $request->query('category', 'semua');
        $unitId = $request->query('unit_id');
        $search = trim((string) $request->query('q', ''));

        $query = Order::with(['user', 'items.product.unit', 'invoice'])->latest('created_at');

        if (in_array($segment, ['b2c', 'b2b'], true)) {
            $query->where('segment', $segment);
        }

        if ($status !== 'semua' && $status !== '') {
            if ($status === 'perlu_validasi') {
                $query->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($unitId) {
            $query->whereHas('items.product', fn ($p) => $p->where('unit_id', $unitId));
        }

        $this->applyCategoryFilter($query, $category);
        $this->applyOrderSearch($query, $search);

        $orders = $query->get();
        $filename = 'pnj-prime-rtpu-antrean-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Pesanan',
                'Tanggal Pesan',
                'Kanal',
                'Pemesan / Instansi',
                'Unit Pengelola',
                'Tipe Order',
                'Nomor Invoice',
                'Status Pembayaran',
                'Nominal Total (Rp)',
                'Status Pesanan',
            ]);

            foreach ($orders as $order) {
                $primaryUnit = $order->items->first()?->product?->unit?->name ?? '-';
                $customerName = $order->segment === 'b2b'
                    ? ($order->user?->company_name ?: $order->user?->name ?? '-')
                    : ($order->user?->name ?? '-');

                fputcsv($handle, [
                    $order->order_code,
                    optional($order->created_at)->format('Y-m-d H:i'),
                    strtoupper($order->segment),
                    $customerName,
                    $primaryUnit,
                    $order->order_type,
                    $order->invoice?->invoice_number ?? 'Belum Terbit',
                    $order->invoice?->payment_status ?? 'unpaid',
                    $this->computeOrderValue($order),
                    $order->status,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Template Cetak / PDF 1: Laporan Operasional Resmi Admin RTPU (A4 Landscape).
     */
    public function printReport(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $segment = $request->query('segment', 'semua');
        $status = $request->query('status', 'semua');
        $category = $request->query('category', 'semua');
        $unitId = $request->query('unit_id');
        $search = trim((string) $request->query('q', ''));

        $query = Order::with(['user', 'items.product.unit', 'items.product.category', 'invoice'])->latest('created_at');

        if (in_array($segment, ['b2c', 'b2b'], true)) {
            $query->where('segment', $segment);
        }

        if ($status !== 'semua' && $status !== '') {
            if ($status === 'perlu_validasi') {
                $query->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($unitId) {
            $query->whereHas('items.product', fn ($p) => $p->where('unit_id', $unitId));
        }

        $this->applyCategoryFilter($query, $category);
        $this->applyOrderSearch($query, $search);

        $orders = $query->get();

        $summary = [
            'total_orders' => $orders->count(),
            'b2c_count' => $orders->where('segment', 'b2c')->count(),
            'b2b_count' => $orders->where('segment', 'b2b')->count(),
            'pending_count' => $orders->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran'])->count(),
            'pending_curation' => Product::where('status', 'menunggu_kurasi_rtpu')->count(),
            'validated_revenue' => $orders->sum(function (Order $o) {
                return ($o->invoice && $o->invoice->payment_status === 'paid')
                    ? (float) $o->invoice->grand_total
                    : 0.0;
            }),
            'total_gross_value' => $orders->sum(fn (Order $o) => $this->computeOrderValue($o)),
        ];

        $pimpinanRtpu = PortalUser::where('role', 'pimpinan_rtpu')->first();

        $reportTitle = match ($segment) {
            'b2c' => 'LAPORAN OPERASIONAL TRANSAKSI KANAL B2C — UPA-RTPU',
            'b2b' => 'LAPORAN OPERASIONAL TRANSAKSI KANAL B2B — UPA-RTPU',
            default => 'LAPORAN OPERASIONAL & KEUANGAN PESANAN — UPA-RTPU',
        };

        return view('admin-rtpu.pdf.laporan', [
            'portalUser' => $portalUser,
            'pimpinanRtpu' => $pimpinanRtpu,
            'orders' => $orders,
            'summary' => $summary,
            'reportTitle' => $reportTitle,
            'segment' => $segment,
            'status' => $status,
            'printedAt' => now(),
        ]);
    }

    /**
     * Template Cetak / PDF 2: Dokumen Invoice Komersial Resmi PNJ Prime (A4 Portrait).
     */
    public function printInvoice(Order $order): View
    {
        $portalUser = $this->resolvePortalUser();

        $order->load(['user', 'items.product.unit', 'items.product.category', 'invoice']);

        $financials = $this->calculateOrderFinancials($order);
        $pimpinanRtpu = PortalUser::where('role', 'pimpinan_rtpu')->first();

        return view('admin-rtpu.pdf.invoice', [
            'portalUser' => $portalUser,
            'pimpinanRtpu' => $pimpinanRtpu,
            'order' => $order,
            'invoice' => $order->invoice,
            'financials' => $financials,
            'printedAt' => now(),
        ]);
    }

    protected function generateInvoiceNumber(Order $order): string
    {
        $datePart = now()->format('Ymd');
        $seq = (Invoice::max('id') ?? 0) + 1;

        do {
            $candidate = sprintf('INV-PNJ-%s-%04d', $datePart, $seq);
            $seq++;
        } while (Invoice::where('invoice_number', $candidate)->exists());

        return $candidate;
    }

    protected function generateVaNumber(Order $order): string
    {
        return '8801' . now()->format('Ymd') . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT);
    }

    protected function appendAdminNote(?string $existing, string $newNote): string
    {
        $stamp = '[' . now()->format('d/m/Y H:i') . ' WIB] ' . trim($newNote);
        $existing = trim((string) $existing);

        return $existing === '' ? $stamp : $existing . "\n" . $stamp;
    }
}
