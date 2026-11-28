<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Notifikasi;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PortalUser;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MahasiswaB2CController extends Controller
{
    /**
     * Biaya pendampingan operator teknisi lab (opsional untuk reservasi lab).
     */
    public const OPERATOR_FEE = 50000;

    /**
     * Resolve profil PortalUser untuk pengguna B2C (Mahasiswa / Dosen).
     */
    protected function resolvePortalUser(): PortalUser
    {
        $authUser = Auth::user();

        if ($authUser && ! in_array($authUser->role, ['mhs', 'dosen', 'admin'], true)) {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Mahasiswa & Dosen PNJ (B2C).');
        }

        $portalUser = null;
        if ($authUser) {
            $portalUser = PortalUser::where('email', $authUser->email)->first();
        }

        if (! $portalUser) {
            $portalUser = PortalUser::where('email', 'bagas@pnj.ac.id')->first()
                ?? PortalUser::whereIn('role', ['mahasiswa', 'dosen'])->firstOrFail();
        }

        return $portalUser;
    }

    /**
     * Resolve model User (tabel `user`) untuk sinkronisasi notifikasi.
     */
    protected function resolveAuthUser(PortalUser $portalUser): ?User
    {
        $authUser = Auth::user();
        if ($authUser instanceof User) {
            return $authUser;
        }

        return User::where('email', $portalUser->email)->first();
    }

    /**
     * Data bersama untuk sidebar & top navbar layout B2C.
     */
    protected function sharedLayoutData(PortalUser $portalUser): array
    {
        $authUser = $this->resolveAuthUser($portalUser);

        $unreadNotifCount = $authUser
            ? Notifikasi::where('id_penerima', $authUser->id_pengguna)->where('is_read', false)->count()
            : 0;

        $unpaidCount = Order::where('user_id', $portalUser->id)
            ->where('segment', 'b2c')
            ->where('status', 'menunggu_pembayaran')
            ->count();

        return [
            'portalUser' => $portalUser,
            'unreadNotifCount' => $unreadNotifCount,
            'unpaidCount' => $unpaidCount,
        ];
    }

    /**
     * Halaman 01: Dashboard B2C Mahasiswa / Dosen.
     */
    public function dashboard(): View
    {
        $portalUser = $this->resolvePortalUser();

        $waitingPaymentCount = Order::where('user_id', $portalUser->id)
            ->where('segment', 'b2c')
            ->where('status', 'menunggu_pembayaran')
            ->count();

        $processingCount = Order::where('user_id', $portalUser->id)
            ->where('segment', 'b2c')
            ->whereIn('status', ['menunggu_verifikasi', 'diproses', 'siap_diambil'])
            ->count();

        $completedCount = Order::where('user_id', $portalUser->id)
            ->where('segment', 'b2c')
            ->where('status', 'selesai')
            ->count();

        $featuredProducts = Product::with(['unit', 'category', 'subCategory'])
            ->where('status', 'tayang')
            ->latest('id')
            ->take(4)
            ->get();

        $units = Unit::orderBy('name')->get();

        return view('Mahasiswa atau Dosen (B2C).dashboard', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'waitingPaymentCount' => $waitingPaymentCount,
                'processingCount' => $processingCount,
                'completedCount' => $completedCount,
                'featuredProducts' => $featuredProducts,
                'units' => $units,
            ]
        ));
    }

    /**
     * Halaman 02 & Modal 03: Katalog Lengkap & Pencarian + Filter Lanjutan.
     */
    public function katalog(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $search = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');
        $subCategoryId = $request->query('sub_category_id');
        $unitId = $request->query('unit_id');
        $orderType = $request->query('order_type');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        $query = Product::with(['unit', 'category', 'subCategory'])
            ->where('status', 'tayang');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('subCategory', fn ($sc) => $sc->where('name', 'like', "%{$search}%"));
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($orderType && in_array($orderType, ['ready_stock', 'pre_order', 'lab_reservation'], true)) {
            $query->where('order_type', $orderType);
        }

        if ($minPrice !== null && $minPrice !== '' && is_numeric($minPrice)) {
            $query->where('price_b2c', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '' && is_numeric($maxPrice)) {
            $query->where('price_b2c', '<=', (float) $maxPrice);
        }

        $products = $query->latest('id')->paginate(8)->withQueryString();

        $units = Unit::orderBy('name')->get();
        $categories = Category::with('subCategories')->orderBy('id')->get();
        $subCategories = SubCategory::orderBy('name')->get();

        $activeFilterCount = collect([$unitId, $subCategoryId, $orderType, $minPrice, $maxPrice])
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->count();

        return view('Mahasiswa atau Dosen (B2C).katalog', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'products' => $products,
                'units' => $units,
                'categories' => $categories,
                'subCategories' => $subCategories,
                'filters' => [
                    'q' => $search,
                    'category_id' => $categoryId,
                    'sub_category_id' => $subCategoryId,
                    'unit_id' => $unitId,
                    'order_type' => $orderType,
                    'min_price' => $minPrice,
                    'max_price' => $maxPrice,
                ],
                'activeFilterCount' => $activeFilterCount,
            ]
        ));
    }

    /**
     * Endpoint JSON untuk Live Search Top Navbar.
     */
    public function liveSearch(Request $request): JsonResponse
    {
        $this->resolvePortalUser();

        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 1) {
            return response()->json(['items' => []]);
        }

        $items = Product::with(['unit', 'category'])
            ->where('status', 'tayang')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$q}%"));
            })
            ->limit(6)
            ->get()
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'code' => $p->code,
                'unit' => $p->unit?->name ?? 'UPA-RTPU PNJ',
                'category' => $p->category?->name ?? 'Riset',
                'order_type' => $p->order_type,
                'price_b2c' => (float) $p->price_b2c,
                'price_formatted' => 'Rp ' . number_format((float) $p->price_b2c, 0, ',', '.'),
                'url' => route('b2c.produk.show', $p),
            ]);

        return response()->json(['items' => $items]);
    }

    /**
     * Halaman 04 & Modal 04a: Detail Produk & Jadwal Reservasi Lab.
     */
    public function showProduct(Product $product): View
    {
        $portalUser = $this->resolvePortalUser();

        $product->load(['unit', 'category', 'subCategory', 'submitter']);

        // Ambil tanggal yang sudah memiliki booking untuk produk lab ini
        $bookedDates = Order::where('order_type', 'lab_reservation')
            ->whereNotIn('status', ['dibatalkan'])
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->whereNotNull('booking_date')
            ->get()
            ->groupBy(fn (Order $o) => $o->booking_date->format('Y-m-d'))
            ->map(fn ($group) => $group->count())
            ->toArray();

        $relatedProducts = Product::with(['unit', 'category'])
            ->where('status', 'tayang')
            ->where('id', '!=', $product->id)
            ->latest('id')
            ->take(4)
            ->get();

        return view('Mahasiswa atau Dosen (B2C).produk-detail', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'product' => $product,
                'bookedDates' => $bookedDates,
                'operatorFee' => self::OPERATOR_FEE,
                'relatedProducts' => $relatedProducts,
            ]
        ));
    }

    /**
     * Halaman 05: Formulir Pemesanan / Reservasi Lab (100% Lunas tanpa DP).
     */
    public function createOrder(Request $request, Product $product): View
    {
        $portalUser = $this->resolvePortalUser();
        $product->load(['unit', 'category', 'subCategory']);

        $defaultDate = $request->query('booking_date', Carbon::now()->addDays(2)->format('Y-m-d'));
        $defaultSlot = $request->query('booking_slot', '13:00 - 17:00 WIB');
        $defaultQty = max(1, (int) $request->query('quantity', 1));
        $withOperator = $request->boolean('with_operator', $product->order_type === 'lab_reservation');

        return view('Mahasiswa atau Dosen (B2C).pemesanan', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'product' => $product,
                'defaultDate' => $defaultDate,
                'defaultSlot' => $defaultSlot,
                'defaultQty' => $defaultQty,
                'withOperator' => $withOperator,
                'operatorFee' => self::OPERATOR_FEE,
            ]
        ));
    }

    /**
     * Simpan Pemesanan / Reservasi Lab ke tabel `orders`, `order_items`, dan `invoices` (100% Lunas).
     */
    public function storeOrder(Request $request, Product $product): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();
        $isLab = $product->order_type === 'lab_reservation';

        $rules = [
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'research_purpose' => ['required', 'string', 'max:200'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'shipping_address' => [$isLab ? 'nullable' : 'required', 'string', 'max:500'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,step,stp,iges,igs,zip,jpg,jpeg,png', 'max:10240'],
            'with_operator' => ['nullable', 'boolean'],
        ];

        if ($isLab) {
            $rules['booking_date'] = ['required', 'date', 'after_or_equal:today'];
            $rules['booking_slot'] = ['required', 'string', 'max:60'];
        }

        $validated = $request->validate($rules, [
            'booking_date.required' => 'Tanggal reservasi wajib dipilih.',
            'booking_date.after_or_equal' => 'Tanggal reservasi minimal hari ini.',
            'booking_slot.required' => 'Sesi praktikum / riset wajib dipilih.',
            'research_purpose.required' => 'Keperluan riset / judul tugas akhir wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman / lokasi pengambilan wajib diisi.',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('b2c-orders', 'public');
        }

        $quantity = $isLab ? 1 : max(1, (int) ($validated['quantity'] ?? 1));
        $unitPrice = (float) $product->price_b2c;
        $subtotal = $unitPrice * $quantity;
        $withOperator = $isLab && $request->boolean('with_operator');
        $operatorFee = $withOperator ? self::OPERATOR_FEE : 0;

        // Skema B2C: 100% Lunas tanpa DP 50%, tanpa diskon B2B, tanpa PPh23/e-Meterai
        $dpp = $subtotal + $operatorFee;
        $grandTotal = $dpp;

        $combinedNotes = 'Keperluan: ' . $validated['research_purpose'];
        if ($withOperator) {
            $combinedNotes .= ' | Pendampingan Teknisi Lab (+Rp ' . number_format(self::OPERATOR_FEE, 0, ',', '.') . ')';
        }
        if (! empty($validated['notes'])) {
            $combinedNotes .= ' | Catatan: ' . $validated['notes'];
        }

        $order = DB::transaction(function () use (
            $portalUser,
            $product,
            $isLab,
            $validated,
            $attachmentPath,
            $quantity,
            $unitPrice,
            $subtotal,
            $dpp,
            $grandTotal,
            $combinedNotes
        ) {
            $orderCode = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            $invoiceNumber = 'INV/' . now()->format('Y') . '/BLU-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
            $vaNumber = '88770' . str_pad(preg_replace('/\D/', '', (string) ($portalUser->nim_nip ?? '2107411058')), 8, '0', STR_PAD_LEFT) . random_int(10, 99);

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $portalUser->id,
                'segment' => 'b2c',
                'order_type' => $product->order_type,
                'status' => 'menunggu_pembayaran',
                'booking_date' => $isLab ? $validated['booking_date'] : null,
                'booking_slot' => $isLab ? $validated['booking_slot'] : null,
                'attachment_path' => $attachmentPath,
                'notes' => $combinedNotes,
                'shipping_address' => $validated['shipping_address'] ?? $portalUser->address ?? 'Gedung UPA-RTPU Kampus PNJ Depok',
                'estimasi_hari' => match ($product->order_type) {
                    'ready_stock' => 1,
                    'pre_order' => 7,
                    'lab_reservation' => 1,
                    default => 2,
                },
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'order_id' => $order->id,
                'dpp' => $dpp,
                'discount_b2b' => 0,
                'shipping_cost' => 0,
                'ppn_amount' => 0,
                'pph23_amount' => 0,
                'emeterai_amount' => 0,
                'grand_total' => $grandTotal,
                'va_number' => $vaNumber,
                'payment_method' => 'va_mandiri_blu',
                'payment_status' => 'unpaid',
                'due_at' => now()->addHours(24),
                'paid_at' => null,
                'qr_pass_code' => null,
            ]);

            return $order;
        });

        // Catat notifikasi untuk pengguna
        $authUser = $this->resolveAuthUser($portalUser);
        if ($authUser) {
            Notifikasi::create([
                'id_penerima' => $authUser->id_pengguna,
                'jenis_notif' => 'pesanan',
                'judul' => $isLab ? 'Pengajuan Reservasi Lab Terkirim' : 'Pesanan Baru Berhasil Dibuat',
                'pesan' => 'Pesanan ' . $product->name . ' (' . $order->order_code . ') telah tercatat dengan total Rp ' . number_format($grandTotal, 0, ',', '.') . ' (Pelunasan 100%).',
                'url_tujuan' => route('b2c.pesanan.show', $order),
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()
            ->route('b2c.pemesanan.create', $product)
            ->with('order_submitted', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'product_name' => $product->name,
                'order_type' => $product->order_type,
                'booking_date' => $order->booking_date ? $order->booking_date->translatedFormat('d F Y') : null,
                'booking_slot' => $order->booking_slot,
                'quantity' => $quantity,
                'grand_total' => $grandTotal,
            ]);
    }

    /**
     * Halaman 08: Pesanan Saya.
     */
    public function orders(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();
        $statusTab = $request->query('status', 'semua');
        $search = trim((string) $request->query('q', ''));

        $baseQuery = Order::where('user_id', $portalUser->id)->where('segment', 'b2c');

        $counts = [
            'semua' => (clone $baseQuery)->count(),
            'menunggu_pembayaran' => (clone $baseQuery)->where('status', 'menunggu_pembayaran')->count(),
            'diproses' => (clone $baseQuery)->whereIn('status', ['menunggu_verifikasi', 'diproses', 'siap_diambil'])->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'dibatalkan' => (clone $baseQuery)->where('status', 'dibatalkan')->count(),
        ];

        $query = Order::with(['items.product.unit', 'items.product.category', 'invoice'])
            ->where('user_id', $portalUser->id)
            ->where('segment', 'b2c');

        if ($statusTab === 'menunggu_pembayaran') {
            $query->where('status', 'menunggu_pembayaran');
        } elseif ($statusTab === 'diproses') {
            $query->whereIn('status', ['menunggu_verifikasi', 'diproses', 'siap_diambil']);
        } elseif ($statusTab === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($statusTab === 'dibatalkan') {
            $query->where('status', 'dibatalkan');
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhereHas('invoice', fn ($i) => $i->where('invoice_number', 'like', "%{$search}%"))
                    ->orWhereHas('items.product', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest('created_at')->paginate(6)->withQueryString();

        return view('Mahasiswa atau Dosen (B2C).pesanan', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'orders' => $orders,
                'counts' => $counts,
                'statusTab' => $statusTab,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman 07 & Modal 07a: Detail Pesanan & Pembayaran + QR Pass Akses Lab.
     */
    public function showOrder(Order $order): View
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['items.product.unit', 'items.product.category', 'invoice', 'user']);

        return view('Mahasiswa atau Dosen (B2C).pesanan-detail', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'order' => $order,
            ]
        ));
    }

    /**
     * Batalkan pesanan yang masih menunggu pembayaran.
     */
    public function cancelOrder(Order $order): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403);
        }

        if (! in_array($order->status, ['menunggu_verifikasi', 'menunggu_pembayaran'], true)) {
            return back()->with('error', 'Pesanan yang sudah diproses tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'dibatalkan']);
            if ($order->invoice) {
                $order->invoice->update(['payment_status' => 'expired']);
            }
        });

        return back()->with('success', 'Pesanan ' . $order->order_code . ' berhasil dibatalkan.');
    }

    /**
     * Halaman 06 & Modal 06a: Pembayaran & Tagihan Aktif (100% Lunas VA Mandiri BLU / QRIS).
     */
    public function payments(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();
        $search = trim((string) $request->query('q', ''));

        $activeQuery = Invoice::with(['order.items.product.unit', 'order.items.product.category'])
            ->where('payment_status', 'unpaid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c')->where('status', '!=', 'dibatalkan'));

        if ($search !== '') {
            $activeQuery->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('va_number', 'like', "%{$search}%")
                    ->orWhereHas('order.items.product', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $activeInvoices = $activeQuery->latest('created_at')->get();

        $paidCount = Invoice::where('payment_status', 'paid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c'))
            ->count();

        return view('Mahasiswa atau Dosen (B2C).pembayaran', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'activeInvoices' => $activeInvoices,
                'paidCount' => $paidCount,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman 09: Riwayat Pembayaran (Tabel Transaksi & Status Tagihan).
     */
    public function paymentHistory(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();
        $search = trim((string) $request->query('q', ''));
        $method = $request->query('method', '');

        $query = Invoice::with(['order.items.product.unit', 'order.items.product.category'])
            ->whereIn('payment_status', ['paid', 'expired'])
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c'));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('va_number', 'like', "%{$search}%")
                    ->orWhere('qr_pass_code', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($o) => $o->where('order_code', 'like', "%{$search}%"))
                    ->orWhereHas('order.items.product', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        if (in_array($method, ['va_mandiri_blu', 'qris'], true)) {
            $query->where('payment_method', $method);
        }

        $invoices = $query->latest('paid_at')->latest('created_at')->paginate(8)->withQueryString();

        $activeCount = Invoice::where('payment_status', 'unpaid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c')->where('status', '!=', 'dibatalkan'))
            ->count();

        $totalPaidAmount = Invoice::where('payment_status', 'paid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c'))
            ->sum('grand_total');

        return view('Mahasiswa atau Dosen (B2C).pembayaran-riwayat', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'invoices' => $invoices,
                'activeCount' => $activeCount,
                'totalPaidAmount' => $totalPaidAmount,
                'search' => $search,
                'method' => $method,
            ]
        ));
    }

    /**
     * Konfirmasi Pembayaran 100% Lunas (VA Mandiri BLU atau QRIS) -> terbitkan QR Pass.
     */
    public function confirmPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();
        $invoice->load('order.items.product');

        if (! $invoice->order || $invoice->order->user_id !== $portalUser->id || $invoice->order->segment !== 'b2c') {
            abort(403);
        }

        if ($invoice->payment_status === 'paid') {
            return redirect()
                ->route('b2c.pesanan.show', $invoice->order)
                ->with('success', 'Tagihan ini sudah berstatus Lunas & Terverifikasi.');
        }

        $method = $request->input('payment_method', $invoice->payment_method ?? 'va_mandiri_blu');
        if (! in_array($method, ['va_mandiri_blu', 'qris'], true)) {
            $method = 'va_mandiri_blu';
        }

        DB::transaction(function () use ($invoice, $method) {
            $qrCode = 'PASS-LAB-' . now()->format('Y') . '-' . str_pad((string) $invoice->order_id, 4, '0', STR_PAD_LEFT);

            $invoice->update([
                'payment_method' => $method,
                'payment_status' => 'paid',
                'paid_at' => now(),
                'qr_pass_code' => $qrCode,
            ]);

            $invoice->order->update([
                'status' => 'diproses',
            ]);
        });

        $authUser = $this->resolveAuthUser($portalUser);
        if ($authUser) {
            Notifikasi::create([
                'id_penerima' => $authUser->id_pengguna,
                'jenis_notif' => 'bayar',
                'judul' => 'Pembayaran Berhasil Divalidasi (100% Lunas)',
                'pesan' => 'Pembayaran untuk invoice ' . $invoice->invoice_number . ' sebesar Rp ' . number_format((float) $invoice->grand_total, 0, ',', '.') . ' via ' . ($method === 'qris' ? 'QRIS BLU PNJ' : 'Virtual Account Mandiri BLU') . ' telah terverifikasi otomatis. QR Pass Akses Lab Anda kini aktif.',
                'url_tujuan' => route('b2c.pesanan.show', $invoice->order),
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()
            ->route('b2c.pesanan.show', $invoice->order)
            ->with('success', 'Pembayaran 100% Lunas terverifikasi! QR Pass Akses Lab & E-Nota resmi Anda sudah aktif.');
    }

    /**
     * Halaman 10: Pusat Notifikasi.
     */
    public function notifications(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();
        $authUser = $this->resolveAuthUser($portalUser);
        $tab = $request->query('tab', 'semua');

        // Seed notifikasi awal otomatis jika masih kosong untuk user ini agar UI informatif
        if ($authUser && Notifikasi::where('id_penerima', $authUser->id_pengguna)->count() === 0) {
            $latestOrder = Order::where('user_id', $portalUser->id)->where('segment', 'b2c')->latest()->first();
            $orderUrl = $latestOrder ? route('b2c.pesanan.show', $latestOrder) : route('b2c.pesanan');

            Notifikasi::insert([
                [
                    'id_penerima' => $authUser->id_pengguna,
                    'jenis_notif' => 'pesanan',
                    'judul' => 'Pesanan Dikonfirmasi RTPU',
                    'pesan' => 'Pesanan instrumen riset Anda telah diverifikasi oleh Unit UPA-RTPU PNJ dan sedang disiapkan oleh teknisi laboratorium.',
                    'url_tujuan' => $orderUrl,
                    'is_read' => false,
                    'created_at' => now()->subHours(2),
                ],
                [
                    'id_penerima' => $authUser->id_pengguna,
                    'jenis_notif' => 'bayar',
                    'judul' => 'Pembayaran Berhasil Divalidasi',
                    'pesan' => 'Pembayaran lunas 100% BLU PNJ Anda telah terverifikasi otomatis. QR Pass dan E-Nota dapat diunduh pada halaman Detail Pesanan.',
                    'url_tujuan' => route('b2c.pembayaran.riwayat'),
                    'is_read' => true,
                    'created_at' => now()->subDay(),
                ],
                [
                    'id_penerima' => $authUser->id_pengguna,
                    'jenis_notif' => 'sistem',
                    'judul' => 'Pengingat Protokol Keselamatan Kerja (K3)',
                    'pesan' => 'Harap menunjukkan QR Pass Digital dan KTM asli kepada laboran sebelum memulai sesi praktikum atau pengambilan instrumen di bengkel riset.',
                    'url_tujuan' => route('b2c.katalog'),
                    'is_read' => true,
                    'created_at' => now()->subDays(2),
                ],
            ]);
        }

        $baseQuery = $authUser
            ? Notifikasi::where('id_penerima', $authUser->id_pengguna)
            : Notifikasi::whereRaw('1 = 0');

        $counts = [
            'semua' => (clone $baseQuery)->count(),
            'transaksi' => (clone $baseQuery)->whereIn('jenis_notif', ['pesanan', 'bayar'])->count(),
            'sistem' => (clone $baseQuery)->whereIn('jenis_notif', ['sistem', 'kurasi', 'verifikasi', 'sla'])->count(),
        ];

        $query = clone $baseQuery;
        if ($tab === 'transaksi') {
            $query->whereIn('jenis_notif', ['pesanan', 'bayar']);
        } elseif ($tab === 'sistem') {
            $query->whereIn('jenis_notif', ['sistem', 'kurasi', 'verifikasi', 'sla']);
        }

        $notifications = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('Mahasiswa atau Dosen (B2C).notifikasi', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'notifications' => $notifications,
                'counts' => $counts,
                'tab' => $tab,
            ]
        ));
    }

    /**
     * Tandai satu notifikasi sudah dibaca.
     */
    public function markNotificationRead(int $id): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();
        $authUser = $this->resolveAuthUser($portalUser);

        if ($authUser) {
            Notifikasi::where('id_notif', $id)
                ->where('id_penerima', $authUser->id_pengguna)
                ->update(['is_read' => true]);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Tandai semua notifikasi sudah dibaca.
     */
    public function markAllNotificationsRead(): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();
        $authUser = $this->resolveAuthUser($portalUser);

        if ($authUser) {
            Notifikasi::where('id_penerima', $authUser->id_pengguna)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    /**
     * Halaman 11: Pengaturan Akun SSO Mahasiswa / Dosen.
     */
    public function settings(): View
    {
        $portalUser = $this->resolvePortalUser();

        return view('Mahasiswa atau Dosen (B2C).pengaturan', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'portalUser' => $portalUser,
            ]
        ));
    }

    /**
     * Simpan perubahan kontak & alamat pengiriman (identitas SSO tetap terkunci).
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
        ], [
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'address.required' => 'Alamat pengiriman wajib diisi.',
        ]);

        $portalUser->update([
            'phone' => trim($validated['phone']),
            'address' => trim($validated['address']),
        ]);

        // Sinkronisasi no_telepon ke tabel `user`
        $authUser = $this->resolveAuthUser($portalUser);
        if ($authUser) {
            $authUser->update([
                'no_telepon' => Str::limit(preg_replace('/[^0-9+]/', '', $validated['phone']), 20, ''),
            ]);
        }

        return back()->with('settings_saved', true);
    }

    /*
    |--------------------------------------------------------------------------
    | BATCH 2B: ALUR READY STOCK B2C (04b, 04b.1, 04b.2, 04b.3)
    |--------------------------------------------------------------------------
    */

    /**
     * Halaman 04b: Checkout Pembelian Langsung (Ready Stock B2C — 100% Lunas).
     */
    public function checkoutReadyStock(Request $request, Product $product): View
    {
        $portalUser = $this->resolvePortalUser();
        $product->load(['unit', 'category', 'subCategory']);

        $defaultQty = max(1, min(max(1, (int) $product->stock), (int) $request->query('quantity', 1)));

        return view('Mahasiswa atau Dosen (B2C).ready-stock-checkout', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'product' => $product,
                'defaultQty' => $defaultQty,
            ]
        ));
    }

    /**
     * Proses Submit Checkout Ready Stock -> buat Order, OrderItem, & Invoice (100% Lunas).
     */
    public function storeReadyStock(Request $request, Product $product): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        $maxStock = max(1, (int) $product->stock);
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $maxStock],
            'shipping_option' => ['required', 'in:jne_reguler,ambil_mandiri'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:25'],
            'notes' => ['nullable', 'string', 'max:250'],
        ], [
            'quantity.required' => 'Jumlah unit wajib diisi.',
            'quantity.max' => 'Jumlah melebihi stok tersedia (' . $maxStock . ' unit).',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'phone.required' => 'Nomor WhatsApp penerima wajib diisi.',
        ]);

        $quantity = (int) $validated['quantity'];
        $unitPrice = (float) $product->price_b2c;
        $dpp = $unitPrice * $quantity;
        $shippingCost = $validated['shipping_option'] === 'jne_reguler' ? 15000.0 : 0.0;
        $ppnAmount = round($dpp * 0.11);
        $grandTotal = $dpp + $shippingCost + $ppnAmount;

        $shippingLabel = $validated['shipping_option'] === 'jne_reguler'
            ? 'Pengiriman JNE Reguler (Jabodetabek)'
            : 'Ambil Mandiri di Gedung AA Lt. 2 (Unit RTPU)';

        $combinedNotes = 'Ready Stock | ' . $shippingLabel . ' | WA: ' . trim($validated['phone']);
        if (! empty($validated['notes'])) {
            $combinedNotes .= ' | Catatan: ' . trim($validated['notes']);
        }

        $order = DB::transaction(function () use (
            $portalUser,
            $product,
            $validated,
            $quantity,
            $unitPrice,
            $dpp,
            $shippingCost,
            $ppnAmount,
            $grandTotal,
            $combinedNotes
        ) {
            $orderCode = 'ORD-RS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            $invoiceNumber = 'INV/' . now()->format('Y') . '/BLU-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
            $vaNumber = '88770' . str_pad(preg_replace('/\D/', '', (string) ($portalUser->nim_nip ?? '2107411058')), 8, '0', STR_PAD_LEFT) . random_int(10, 99);

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $portalUser->id,
                'segment' => 'b2c',
                'order_type' => 'ready_stock',
                'status' => 'menunggu_pembayaran',
                'notes' => $combinedNotes,
                'shipping_address' => trim($validated['shipping_address']),
                'estimasi_hari' => $validated['shipping_option'] === 'jne_reguler' ? 2 : 1,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $dpp,
            ]);

            Invoice::create([
                'invoice_number' => $invoiceNumber,
                'order_id' => $order->id,
                'dpp' => $dpp,
                'discount_b2b' => 0,
                'shipping_cost' => $shippingCost,
                'ppn_amount' => $ppnAmount,
                'pph23_amount' => 0,
                'emeterai_amount' => 0,
                'grand_total' => $grandTotal,
                'va_number' => $vaNumber,
                'payment_method' => 'va_mandiri_blu',
                'payment_status' => 'unpaid',
                'due_at' => now()->endOfDay(),
                'paid_at' => null,
                'qr_pass_code' => null,
            ]);

            return $order;
        });

        $authUser = $this->resolveAuthUser($portalUser);
        if ($authUser) {
            Notifikasi::create([
                'id_penerima' => $authUser->id_pengguna,
                'jenis_notif' => 'pesanan',
                'judul' => 'Tagihan Ready Stock Terbit (100% Lunas)',
                'pesan' => 'Pesanan Ready Stock ' . $product->name . ' (' . $order->order_code . ') sebesar Rp ' . number_format($grandTotal, 0, ',', '.') . ' siap dibayar via VA Mandiri BLU atau QRIS.',
                'url_tujuan' => route('b2c.ready-stock.pembayaran', $order),
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('b2c.ready-stock.pembayaran', $order);
    }

    /**
     * Halaman 04b.1 + Modal 04b.2 (QRIS) + Modal 04b.3 (Sukses): Pembayaran & Tagihan Ready Stock.
     */
    public function showReadyStockPayment(Order $order): View
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        $order->load(['items.product.unit', 'items.product.category', 'invoice']);

        $activeCount = Invoice::where('payment_status', 'unpaid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c')->where('status', '!=', 'dibatalkan'))
            ->count();

        $paidCount = Invoice::where('payment_status', 'paid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c'))
            ->count();

        return view('Mahasiswa atau Dosen (B2C).ready-stock-pembayaran', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'order' => $order,
                'invoice' => $order->invoice,
                'activeCount' => $activeCount,
                'paidCount' => $paidCount,
            ]
        ));
    }

    /**
     * Konfirmasi Pembayaran 100% Lunas Ready Stock (VA Mandiri BLU / QRIS) -> tampilkan Modal 04b.3.
     */
    public function confirmReadyStockPayment(Request $request, Order $order): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403);
        }

        $order->load(['items.product', 'invoice']);
        $invoice = $order->invoice;

        if (! $invoice) {
            return redirect()->route('b2c.pesanan.show', $order)->with('error', 'Tagihan tidak ditemukan.');
        }

        $method = $request->input('payment_method', 'va_mandiri_blu');
        if (! in_array($method, ['va_mandiri_blu', 'qris'], true)) {
            $method = 'va_mandiri_blu';
        }

        if ($invoice->payment_status !== 'paid') {
            DB::transaction(function () use ($order, $invoice, $method) {
                $invoice->update([
                    'payment_method' => $method,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'qr_pass_code' => 'PASS-RS-' . now()->format('Y') . '-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                ]);

                $order->update(['status' => 'diproses']);
            });

            $authUser = $this->resolveAuthUser($portalUser);
            if ($authUser) {
                Notifikasi::create([
                    'id_penerima' => $authUser->id_pengguna,
                    'jenis_notif' => 'bayar',
                    'judul' => 'Pembayaran Ready Stock Lunas (100%)',
                    'pesan' => 'Pelunasan invoice ' . $invoice->invoice_number . ' sebesar Rp ' . number_format((float) $invoice->grand_total, 0, ',', '.') . ' telah terverifikasi otomatis oleh Kas BLU PNJ.',
                    'url_tujuan' => route('b2c.pesanan.show', $order),
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }
        }

        $firstProduct = $order->items->first()?->product;

        return redirect()
            ->route('b2c.ready-stock.pembayaran', $order)
            ->with('ready_stock_paid', [
                'invoice_number' => $invoice->invoice_number,
                'order_code' => $order->order_code,
                'product_name' => $firstProduct?->name ?? 'Produk Ready Stock PNJ',
                'product_code' => $firstProduct?->code ?? 'PNJ-RS',
                'quantity' => $order->items->first()?->quantity ?? 1,
                'grand_total' => (float) $invoice->grand_total,
                'payment_method' => $method === 'qris' ? 'QRIS BLU PNJ' : 'Mandiri VA (' . trim(chunk_split((string) $invoice->va_number, 4, ' ')) . ')',
                'trx_id' => 'TRX-RS-' . now()->format('Ymd') . '-' . random_int(100000, 999999),
                'paid_at' => now()->translatedFormat('d M Y, H:i:s') . ' WIB',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BATCH 2B: ALUR PRE-ORDER B2C (04c, 04c.1, State A-E: 04c.2 - 04c.11)
    |--------------------------------------------------------------------------
    */

    /**
     * Halaman 04c + Modal 04c.1: Checkout Pre-Order B2C.
     */
    public function checkoutPreOrder(Request $request, Product $product): View
    {
        $portalUser = $this->resolvePortalUser();
        $product->load(['unit', 'category', 'subCategory']);

        $defaultQty = max(1, min(20, (int) $request->query('quantity', 1)));

        return view('Mahasiswa atau Dosen (B2C).preorder-checkout', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'product' => $product,
                'defaultQty' => $defaultQty,
            ]
        ));
    }

    /**
     * Submit Pengajuan Pre-Order -> buat Order berstatus `menunggu_verifikasi` (State A) + Modal 04c.1.
     */
    public function storePreOrder(Request $request, Product $product): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'custom_spec' => ['nullable', 'string', 'max:300'],
            'research_purpose' => ['required', 'string', 'max:200'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:25'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'quantity.required' => 'Kuantitas Pre-Order wajib diisi.',
            'research_purpose.required' => 'Keperluan riset / praktikum wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman / pengambilan wajib diisi.',
            'phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
        ]);

        $quantity = (int) $validated['quantity'];
        $unitPrice = (float) $product->price_b2c;
        $subtotal = $unitPrice * $quantity;

        $combinedNotes = 'Keperluan: ' . trim($validated['research_purpose']);
        if (! empty($validated['custom_spec'])) {
            $combinedNotes .= ' | Spesifikasi: ' . trim($validated['custom_spec']);
        }
        $combinedNotes .= ' | WA: ' . trim($validated['phone']);
        if (! empty($validated['notes'])) {
            $combinedNotes .= ' | Catatan: ' . trim($validated['notes']);
        }

        $order = DB::transaction(function () use (
            $portalUser,
            $product,
            $validated,
            $quantity,
            $unitPrice,
            $subtotal,
            $combinedNotes
        ) {
            $orderCode = 'REQ-PO-' . now()->format('Y') . '-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $portalUser->id,
                'segment' => 'b2c',
                'order_type' => 'pre_order',
                'status' => 'menunggu_verifikasi', // State A (04c.2)
                'notes' => $combinedNotes,
                'shipping_address' => trim($validated['shipping_address']),
                'estimasi_hari' => 7,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            return $order;
        });

        $authUser = $this->resolveAuthUser($portalUser);
        if ($authUser) {
            Notifikasi::create([
                'id_penerima' => $authUser->id_pengguna,
                'jenis_notif' => 'pesanan',
                'judul' => 'Pengajuan Pre-Order Berhasil Dikirim',
                'pesan' => 'Pengajuan Pre-Order ' . $product->name . ' (#' . $order->order_code . ') masuk antrean verifikasi Admin RTPU (maks. 1x24 jam kerja).',
                'url_tujuan' => route('b2c.preorder.show', $order),
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()
            ->route('b2c.preorder.checkout', $product)
            ->with('preorder_submitted', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'detail_url' => route('b2c.preorder.show', $order),
            ]);
    }

    /**
     * Halaman Detail Pelacakan Pre-Order State A-E (1 File Blade dengan kondisi @if status).
     * State A (04c.2): menunggu_verifikasi
     * State B (04c.3 + Modal 04c.4): menunggu_pembayaran
     * State C (04c.8): diproses
     * State D (04c.9): siap_diambil
     * State E (04c.10 + Modal 04c.11): selesai
     */
    public function showPreOrder(Request $request, Order $order): View
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403, 'Anda tidak memiliki akses ke pesanan Pre-Order ini.');
        }

        $order->load(['items.product.unit', 'items.product.category', 'invoice', 'user']);

        // Resolusi status aktif (mengikuti status DB secara default)
        $effectiveStatus = $order->status;
        $preview = strtoupper((string) $request->query('state', ''));
        if (in_array($preview, ['A', 'B', 'C', 'D', 'E'], true)) {
            $effectiveStatus = match ($preview) {
                'A' => 'menunggu_verifikasi',
                'B' => 'menunggu_pembayaran',
                'C' => 'diproses',
                'D' => 'siap_diambil',
                'E' => 'selesai',
            };
        }

        $firstItem = $order->items->first();
        $subtotal = (float) $order->items->sum('subtotal');
        $ppn = $order->invoice ? (float) $order->invoice->ppn_amount : round($subtotal * 0.11);
        $shipping = $order->invoice ? (float) $order->invoice->shipping_cost : ($effectiveStatus === 'menunggu_verifikasi' ? 0.0 : 20000.0);
        $grandTotal = $order->invoice ? (float) $order->invoice->grand_total : ($subtotal + $ppn + $shipping);

        $hasReviewed = str_contains((string) $order->notes, '[ULASAN:') || session('review_submitted');

        return view('Mahasiswa atau Dosen (B2C).preorder-detail', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'order' => $order,
                'firstItem' => $firstItem,
                'product' => $firstItem?->product,
                'invoice' => $order->invoice,
                'effectiveStatus' => $effectiveStatus,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'shipping' => $shipping,
                'grandTotal' => $grandTotal,
                'hasReviewed' => $hasReviewed,
            ]
        ));
    }

    /**
     * Halaman 04c.5 + Modal 04c.6 (QRIS PO) + Modal 04c.7 (Sukses PO): Pembayaran & Tagihan Pre-Order.
     */
    public function showPreOrderPayment(Order $order): View
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403, 'Anda tidak memiliki akses ke pembayaran Pre-Order ini.');
        }

        $order->load(['items.product.unit', 'items.product.category', 'invoice']);

        // Jika invoice belum dibuat saat Admin menyetujui, pastikan ter-generate otomatis (100% Lunas)
        if (! $order->invoice) {
            $dpp = (float) $order->items->sum('subtotal');
            $shippingCost = 20000.0;
            $ppnAmount = round($dpp * 0.11);
            $grandTotal = $dpp + $shippingCost + $ppnAmount;

            $invoice = Invoice::create([
                'invoice_number' => 'INV/' . now()->format('Y') . '/BLU-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT),
                'order_id' => $order->id,
                'dpp' => $dpp,
                'discount_b2b' => 0,
                'shipping_cost' => $shippingCost,
                'ppn_amount' => $ppnAmount,
                'pph23_amount' => 0,
                'emeterai_amount' => 0,
                'grand_total' => $grandTotal,
                'va_number' => '88770' . str_pad(preg_replace('/\D/', '', (string) ($portalUser->nim_nip ?? '2107411058')), 8, '0', STR_PAD_LEFT) . random_int(10, 99),
                'payment_method' => 'va_mandiri_blu',
                'payment_status' => 'unpaid',
                'due_at' => now()->addDay()->endOfDay(),
            ]);

            $order->setRelation('invoice', $invoice);
        }

        $activeCount = Invoice::where('payment_status', 'unpaid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c')->where('status', '!=', 'dibatalkan'))
            ->count();

        $paidCount = Invoice::where('payment_status', 'paid')
            ->whereHas('order', fn ($q) => $q->where('user_id', $portalUser->id)->where('segment', 'b2c'))
            ->count();

        return view('Mahasiswa atau Dosen (B2C).preorder-pembayaran', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'order' => $order,
                'invoice' => $order->invoice,
                'activeCount' => $activeCount,
                'paidCount' => $paidCount,
            ]
        ));
    }

    /**
     * Konfirmasi Pembayaran 100% Lunas Pre-Order (VA / QRIS) -> status jadi `diproses` (State C) + Modal 04c.7.
     */
    public function confirmPreOrderPayment(Request $request, Order $order): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403);
        }

        $order->load(['items.product', 'invoice']);
        $invoice = $order->invoice;

        if (! $invoice) {
            return redirect()->route('b2c.preorder.pembayaran', $order);
        }

        $method = $request->input('payment_method', 'va_mandiri_blu');
        if (! in_array($method, ['va_mandiri_blu', 'qris'], true)) {
            $method = 'va_mandiri_blu';
        }

        if ($invoice->payment_status !== 'paid') {
            DB::transaction(function () use ($order, $invoice, $method) {
                $invoice->update([
                    'payment_method' => $method,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'qr_pass_code' => 'PASS-PO-' . now()->format('Y') . '-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                ]);

                $order->update(['status' => 'diproses']); // Masuk ke State C
            });

            $authUser = $this->resolveAuthUser($portalUser);
            if ($authUser) {
                Notifikasi::create([
                    'id_penerima' => $authUser->id_pengguna,
                    'jenis_notif' => 'bayar',
                    'judul' => 'Pelunasan Pre-Order Terverifikasi (100% Lunas)',
                    'pesan' => 'Pelunasan Pre-Order #' . $order->order_code . ' sebesar Rp ' . number_format((float) $invoice->grand_total, 0, ',', '.') . ' telah diverifikasi. Pesanan masuk antrean perakitan Lab PNJ.',
                    'url_tujuan' => route('b2c.preorder.show', $order),
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }
        }

        $firstProduct = $order->items->first()?->product;

        return redirect()
            ->route('b2c.preorder.pembayaran', $order)
            ->with('preorder_paid', [
                'invoice_number' => $invoice->invoice_number,
                'order_code' => $order->order_code,
                'product_name' => $firstProduct?->name ?? 'Produk Pre-Order PNJ',
                'product_code' => $firstProduct?->code ?? 'PNJ-PO',
                'quantity' => $order->items->first()?->quantity ?? 1,
                'grand_total' => (float) $invoice->grand_total,
                'payment_method' => $method === 'qris' ? 'QRIS BLU PNJ' : 'Mandiri VA (' . trim(chunk_split((string) $invoice->va_number, 4, ' ')) . ')',
                'trx_id' => 'TRX-MDR-' . now()->format('Ymd') . '-' . random_int(100000, 999999),
                'paid_at' => now()->translatedFormat('d M Y, H:i:s') . ' WIB',
                'detail_url' => route('b2c.preorder.show', $order),
            ]);
    }

    /**
     * State D -> State E: Konfirmasi Pesanan Pre-Order telah diterima oleh mahasiswa/dosen.
     */
    public function confirmPreOrderReceived(Order $order): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403);
        }

        if ($order->status === 'siap_diambil') {
            $order->update(['status' => 'selesai']);
        }

        return redirect()
            ->route('b2c.preorder.show', $order)
            ->with('success', 'Pesanan Pre-Order telah dikonfirmasi diterima. Anda kini dapat menulis ulasan layanan laboratorium.');
    }

    /**
     * State E (Modal 04c.11): Simpan Ulasan & Penilaian Layanan Lab Pre-Order.
     */
    public function storePreOrderReview(Request $request, Order $order): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        if ($order->user_id !== $portalUser->id || $order->segment !== 'b2c') {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:60'],
            'comment' => ['required', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Rating bintang wajib dipilih.',
            'comment.required' => 'Mohon ceritakan pengalaman Anda menggunakan perangkat / layanan lab.',
        ]);

        $tagText = ! empty($validated['tags']) ? implode(', ', $validated['tags']) : '-';
        $reviewNote = ' | [ULASAN: ' . $validated['rating'] . '/5 | Tag: ' . $tagText . ' | ' . trim($validated['comment']) . ']';

        if (! str_contains((string) $order->notes, '[ULASAN:')) {
            $order->update([
                'notes' => trim((string) $order->notes) . $reviewNote,
            ]);
        }

        return redirect()
            ->route('b2c.preorder.show', $order)
            ->with('review_submitted', [
                'rating' => (int) $validated['rating'],
                'comment' => trim($validated['comment']),
            ])
            ->with('success', 'Terima kasih! Ulasan & penilaian Anda untuk layanan laboratorium PNJ telah tersimpan.');
    }
}
