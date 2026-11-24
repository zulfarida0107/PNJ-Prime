<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PortalUser;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminP3MController extends Controller
{
    /**
     * Memastikan hanya akun Admin P3M (atau admin) yang mengakses modul ini,
     * dan mengembalikan profil PortalUser yang sesuai dari tabel `users`.
     */
    protected function resolvePortalUser(): PortalUser
    {
        $authUser = Auth::user();

        if ($authUser && ! in_array($authUser->role, ['p3m', 'admin'], true)) {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk Admin P3M.');
        }

        $portalUser = null;
        if ($authUser) {
            $portalUser = PortalUser::where('email', $authUser->email)->first();
        }

        if (! $portalUser) {
            $portalUser = PortalUser::where('email', 'admin.p3m@pnj.ac.id')->firstOrFail();
        }

        return $portalUser;
    }

    /**
     * Data bersama untuk sidebar & navbar Admin P3M.
     */
    protected function sharedLayoutData(PortalUser $portalUser): array
    {
        $activeQueueCount = Product::whereIn('status', [
            'menunggu_kurasi_rtpu',
            'menunggu_sk_pimpinan',
            'revisi',
        ])->count();

        $revisionAlertCount = Product::where('status', 'revisi')->count();

        return [
            'portalUser' => $portalUser,
            'activeQueueCount' => $activeQueueCount,
            'revisionAlertCount' => $revisionAlertCount,
        ];
    }

    /**
     * Halaman 1: Dashboard Pengajuan Produk Unggulan P3M.
     */
    public function dashboard(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $tab = $request->query('tab', 'semua');
        $search = trim((string) $request->query('q', ''));

        $totalProducts = Product::count();
        $thisMonthCount = Product::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $waitingReviewCount = Product::whereIn('status', ['menunggu_kurasi_rtpu', 'menunggu_sk_pimpinan'])->count();
        $approvedCount = Product::where('status', 'tayang')->count();
        $revisionCount = Product::where('status', 'revisi')->count();
        $rejectedCount = Product::where('status', 'ditolak')->count();
        $approvalRate = $totalProducts > 0 ? round(($approvedCount / $totalProducts) * 100, 1) : 0;

        $query = Product::with(['unit', 'category', 'subCategory', 'submitter'])->latest('updated_at');

        if ($tab === 'diajukan') {
            $query->whereIn('status', ['menunggu_kurasi_rtpu', 'menunggu_sk_pimpinan', 'draft']);
        } elseif ($tab === 'revisi') {
            $query->where('status', 'revisi');
        } elseif ($tab === 'disetujui') {
            $query->where('status', 'tayang');
        }

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

        $recentProducts = $query->paginate(5)->withQueryString();

        $revisionProducts = Product::with(['unit', 'category'])
            ->where('status', 'revisi')
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('admin-p3m.dashboard', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'totalProducts' => $totalProducts,
                'thisMonthCount' => $thisMonthCount,
                'waitingReviewCount' => $waitingReviewCount,
                'approvedCount' => $approvedCount,
                'revisionCount' => $revisionCount,
                'rejectedCount' => $rejectedCount,
                'approvalRate' => $approvalRate,
                'recentProducts' => $recentProducts,
                'revisionProducts' => $revisionProducts,
                'activeTab' => $tab,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman 2: Form Pengajuan Produk Baru (2 Langkah, berbasis Master Data P08).
     */
    public function create(): View
    {
        $portalUser = $this->resolvePortalUser();

        $units = Unit::orderBy('name')->get();
        $categories = Category::with('subCategories')->orderBy('id')->get();
        $subCategories = SubCategory::orderBy('name')->get();

        $nextSequence = (Product::max('id') ?? 0) + 1;
        $previewCode = sprintf('PROP-%s-%04d', now()->format('Y'), $nextSequence);

        return view('admin-p3m.create', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'units' => $units,
                'categories' => $categories,
                'subCategories' => $subCategories,
                'previewCode' => $previewCode,
            ]
        ));
    }

    /**
     * Menyimpan Pengajuan Produk Baru ke tabel `products`.
     * Status otomatis diset ke `menunggu_kurasi_rtpu` saat diajukan (atau `draft` jika simpan draf).
     */
    public function store(Request $request): RedirectResponse
    {
        $portalUser = $this->resolvePortalUser();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'unit_id' => ['required', 'exists:units,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => [
                'required',
                Rule::exists('sub_categories', 'id')->where(
                    fn ($q) => $q->where('category_id', $request->input('category_id'))
                ),
            ],
            'order_type' => ['required', Rule::in(['ready_stock', 'pre_order', 'lab_reservation'])],
            'description' => ['required', 'string', 'min:20'],
            'hpp' => ['required', 'numeric', 'min:0'],
            'price_b2c' => ['required', 'numeric', 'min:0'],
            'price_b2b' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'submit_action' => ['nullable', Rule::in(['submit', 'draft'])],
        ], [
            'name.required' => 'Nama produk / inovasi wajib diisi.',
            'unit_id.required' => 'Unit pengelola / jurusan wajib dipilih.',
            'category_id.required' => 'Kategori baku wajib dipilih.',
            'sub_category_id.required' => 'Sub-kategori produk wajib dipilih.',
            'sub_category_id.exists' => 'Sub-kategori tidak sesuai dengan kategori baku yang dipilih.',
            'order_type.required' => 'Tipe order wajib dipilih.',
            'description.required' => 'Deskripsi teknis produk wajib diisi.',
            'description.min' => 'Deskripsi teknis minimal 20 karakter.',
            'hpp.required' => 'Estimasi HPP wajib diisi.',
            'price_b2c.required' => 'Usulan harga jual katalog (B2C) wajib diisi.',
            'stock.required' => 'Stok / kapasitas slot wajib diisi.',
            'image.image' => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
            'image.max' => 'Ukuran foto produk maksimal 1MB.',
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);
        $code = $this->generateProductCode($unit->code);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $isDraft = ($validated['submit_action'] ?? 'submit') === 'draft';
        $status = $isDraft ? 'draft' : 'menunggu_kurasi_rtpu';

        $priceB2b = isset($validated['price_b2b']) && $validated['price_b2b'] !== ''
            ? $validated['price_b2b']
            : round((float) $validated['price_b2c'] * 0.9, 2);

        $product = Product::create([
            'unit_id' => $validated['unit_id'],
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'submitted_by' => $portalUser->id,
            'code' => $code,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'order_type' => $validated['order_type'],
            'hpp' => $validated['hpp'],
            'price_b2c' => $validated['price_b2c'],
            'price_b2b' => $priceB2b,
            'stock' => $validated['stock'],
            'image_path' => $imagePath,
            'status' => $status,
            'curation_notes' => $isDraft
                ? 'Disimpan sebagai draf oleh Admin P3M.'
                : 'Usulan baru diajukan oleh Admin P3M. Menunggu verifikasi Admin Sentra RTPU.',
        ]);

        if ($isDraft) {
            return redirect()
                ->route('admin.p3m.pemantauan', ['status' => 'draft'])
                ->with('success', "Draf produk \"{$product->name}\" ({$product->code}) berhasil disimpan.");
        }

        return redirect()
            ->route('admin.p3m.pemantauan')
            ->with('submitted_product', [
                'code' => $product->code,
                'name' => $product->name,
                'price_b2c' => (float) $product->price_b2c,
                'order_type' => $product->order_type,
                'unit_name' => $unit->name,
                'is_revision' => false,
            ]);
    }

    /**
     * Halaman 3: Pemantauan Status Pengajuan Produk.
     */
    public function pemantauan(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $statusFilter = $request->query('status', 'semua');
        $unitId = $request->query('unit_id');
        $search = trim((string) $request->query('q', ''));

        $counts = [
            'semua' => Product::count(),
            'diajukan' => Product::whereIn('status', ['menunggu_kurasi_rtpu', 'menunggu_sk_pimpinan'])->count(),
            'revisi' => Product::whereIn('status', ['revisi', 'ditolak'])->count(),
            'revisi_only' => Product::where('status', 'revisi')->count(),
            'tayang' => Product::where('status', 'tayang')->count(),
            'draft' => Product::where('status', 'draft')->count(),
        ];

        $query = Product::with(['unit', 'category', 'subCategory', 'submitter'])
            ->orderByRaw("CASE status
                WHEN 'revisi' THEN 1
                WHEN 'menunggu_kurasi_rtpu' THEN 2
                WHEN 'menunggu_sk_pimpinan' THEN 3
                WHEN 'draft' THEN 4
                WHEN 'tayang' THEN 5
                WHEN 'ditolak' THEN 6
                ELSE 7 END")
            ->latest('updated_at');

        if ($statusFilter === 'diajukan') {
            $query->whereIn('status', ['menunggu_kurasi_rtpu', 'menunggu_sk_pimpinan']);
        } elseif ($statusFilter === 'revisi') {
            $query->whereIn('status', ['revisi', 'ditolak']);
        } elseif ($statusFilter === 'tayang') {
            $query->where('status', 'tayang');
        } elseif ($statusFilter === 'draft') {
            $query->where('status', 'draft');
        }

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('subCategory', fn ($sc) => $sc->where('name', 'like', "%{$search}%"));
            });
        }

        $products = $query->paginate(8)->withQueryString();
        $units = Unit::orderBy('name')->get();

        return view('admin-p3m.pemantauan', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'products' => $products,
                'units' => $units,
                'counts' => $counts,
                'statusFilter' => $statusFilter,
                'selectedUnitId' => $unitId,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman 4: Form Perbaikan & Pengajuan Ulang (Revisi / Edit Draf / Edit Antrean).
     */
    public function revisi(Product $product): View
    {
        $portalUser = $this->resolvePortalUser();

        $product->load(['unit', 'category', 'subCategory', 'submitter']);
        $units = Unit::orderBy('name')->get();
        $categories = Category::with('subCategories')->orderBy('id')->get();
        $subCategories = SubCategory::orderBy('name')->get();

        return view('admin-p3m.revisi', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'product' => $product,
                'units' => $units,
                'categories' => $categories,
                'subCategories' => $subCategories,
            ]
        ));
    }

    /**
     * Menyimpan perbaikan revisi & mengajukan ulang produk ke `menunggu_kurasi_rtpu`.
     */
    public function updateRevisi(Request $request, Product $product): RedirectResponse
    {
        $this->resolvePortalUser();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'unit_id' => ['required', 'exists:units,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => [
                'required',
                Rule::exists('sub_categories', 'id')->where(
                    fn ($q) => $q->where('category_id', $request->input('category_id'))
                ),
            ],
            'order_type' => ['required', Rule::in(['ready_stock', 'pre_order', 'lab_reservation'])],
            'description' => ['required', 'string', 'min:20'],
            'hpp' => ['required', 'numeric', 'min:0'],
            'price_b2c' => ['required', 'numeric', 'min:0'],
            'price_b2b' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'revision_response' => ['nullable', 'string', 'max:500'],
            'submit_action' => ['nullable', Rule::in(['submit', 'draft'])],
        ], [
            'name.required' => 'Nama produk / inovasi wajib diisi.',
            'unit_id.required' => 'Unit pengelola wajib dipilih.',
            'category_id.required' => 'Kategori baku wajib dipilih.',
            'sub_category_id.required' => 'Sub-kategori produk wajib dipilih.',
            'sub_category_id.exists' => 'Sub-kategori tidak sesuai dengan kategori baku yang dipilih.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'image.max' => 'Ukuran foto produk maksimal 1MB.',
        ]);

        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $isDraft = ($validated['submit_action'] ?? 'submit') === 'draft';
        $nextStatus = $isDraft ? ($product->status === 'revisi' ? 'revisi' : 'draft') : 'menunggu_kurasi_rtpu';

        $priceB2b = isset($validated['price_b2b']) && $validated['price_b2b'] !== ''
            ? $validated['price_b2b']
            : round((float) $validated['price_b2c'] * 0.9, 2);

        $notes = $product->curation_notes;
        if (! $isDraft) {
            $responseText = trim((string) ($validated['revision_response'] ?? ''));
            $notes = $responseText !== ''
                ? 'Perbaikan diajukan ulang oleh Admin P3M: ' . $responseText
                : 'Perbaikan telah dilengkapi oleh Admin P3M dan diajukan ulang untuk kurasi RTPU.';
        }

        $product->update([
            'unit_id' => $validated['unit_id'],
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'order_type' => $validated['order_type'],
            'hpp' => $validated['hpp'],
            'price_b2c' => $validated['price_b2c'],
            'price_b2b' => $priceB2b,
            'stock' => $validated['stock'],
            'image_path' => $imagePath,
            'status' => $nextStatus,
            'curation_notes' => $notes,
        ]);

        if ($isDraft) {
            return back()->with('success', 'Perubahan draf revisi berhasil disimpan.');
        }

        $product->load('unit');

        return redirect()
            ->route('admin.p3m.pemantauan')
            ->with('submitted_product', [
                'code' => $product->code,
                'name' => $product->name,
                'price_b2c' => (float) $product->price_b2c,
                'order_type' => $product->order_type,
                'unit_name' => $product->unit->name ?? '-',
                'is_revision' => true,
            ]);
    }

    /**
     * Membatalkan pengajuan produk (hanya untuk produk yang belum tayang).
     */
    public function cancel(Product $product): RedirectResponse
    {
        $this->resolvePortalUser();

        if ($product->status === 'tayang') {
            return back()->with('error', 'Produk yang sudah tayang di katalog tidak dapat dibatalkan.');
        }

        $name = $product->name;
        $code = $product->code;

        $product->update([
            'status' => 'ditolak',
            'curation_notes' => 'Pengajuan dibatalkan oleh pengusul (Admin P3M) pada ' . now()->format('d M Y H:i') . ' WIB.',
        ]);

        return redirect()
            ->route('admin.p3m.riwayat')
            ->with('success', "Pengajuan produk \"{$name}\" ({$code}) telah dibatalkan dan dipindahkan ke arsip riwayat.");
    }

    /**
     * Halaman 5: Riwayat & Arsip Pengajuan Produk Unggulan.
     */
    public function riwayat(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $filter = $request->query('filter', 'semua');
        $unitId = $request->query('unit_id');
        $search = trim((string) $request->query('q', ''));

        $baseArchive = Product::whereIn('status', ['tayang', 'ditolak', 'draft']);

        $tayangCount = Product::where('status', 'tayang')->count();
        $ditolakPermanentCount = Product::where('status', 'ditolak')
            ->where(function ($q) {
                $q->whereNull('curation_notes')
                    ->orWhere('curation_notes', 'not like', '%dibatalkan%');
            })->count();
        $dibatalkanCount = Product::where('status', 'ditolak')
            ->where('curation_notes', 'like', '%dibatalkan%')
            ->count() + Product::where('status', 'draft')->count();
        $totalArsip = (clone $baseArchive)->count();

        $query = Product::with(['unit', 'category', 'subCategory', 'submitter'])
            ->whereIn('status', ['tayang', 'ditolak', 'draft'])
            ->latest('updated_at');

        if ($filter === 'tayang') {
            $query->where('status', 'tayang');
        } elseif ($filter === 'ditolak') {
            $query->where('status', 'ditolak')
                ->where(function ($q) {
                    $q->whereNull('curation_notes')
                        ->orWhere('curation_notes', 'not like', '%dibatalkan%');
                });
        } elseif ($filter === 'dibatalkan') {
            $query->where(function ($q) {
                $q->where('status', 'draft')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'ditolak')
                            ->where('curation_notes', 'like', '%dibatalkan%');
                    });
            });
        }

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('subCategory', fn ($sc) => $sc->where('name', 'like', "%{$search}%"));
            });
        }

        $archives = $query->paginate(8)->withQueryString();
        $units = Unit::orderBy('name')->get();

        return view('admin-p3m.riwayat', array_merge(
            $this->sharedLayoutData($portalUser),
            [
                'archives' => $archives,
                'units' => $units,
                'totalArsip' => $totalArsip,
                'tayangCount' => $tayangCount,
                'ditolakPermanentCount' => $ditolakPermanentCount,
                'dibatalkanCount' => $dibatalkanCount,
                'filter' => $filter,
                'selectedUnitId' => $unitId,
                'search' => $search,
            ]
        ));
    }

    /**
     * Halaman Cetak / Ekspor PDF Laporan Resmi P3M (Landscape A4 dengan Kop Surat PNJ).
     */
    public function printReport(Request $request): View
    {
        $portalUser = $this->resolvePortalUser();

        $scope = $request->query('scope', 'ringkasan');
        $unitId = $request->query('unit_id');
        $search = trim((string) $request->query('q', ''));

        $query = Product::with(['unit', 'category', 'subCategory', 'submitter'])->latest('updated_at');

        if ($scope === 'riwayat') {
            $query->whereIn('status', ['tayang', 'ditolak', 'draft']);
        }

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $products = $query->get();

        $summary = [
            'total' => $products->count(),
            'menunggu' => $products->whereIn('status', ['menunggu_kurasi_rtpu', 'menunggu_sk_pimpinan'])->count(),
            'revisi' => $products->where('status', 'revisi')->count(),
            'tayang' => $products->where('status', 'tayang')->count(),
            'ditolak' => $products->where('status', 'ditolak')->count(),
            'draft' => $products->where('status', 'draft')->count(),
        ];

        $reportTitle = $scope === 'riwayat'
            ? 'LAPORAN ARSIP & RIWAYAT PENGAJUAN PRODUK UNGGULAN P3M'
            : 'LAPORAN RINGKASAN PENGAJUAN PRODUK UNGGULAN P3M';

        return view('admin-p3m.pdf.laporan', [
            'portalUser' => $portalUser,
            'products' => $products,
            'summary' => $summary,
            'scope' => $scope,
            'reportTitle' => $reportTitle,
            'printedAt' => now(),
        ]);
    }

    /**
     * Ekspor data pengajuan produk ke CSV (kompatibel Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $this->resolvePortalUser();

        $scope = $request->query('scope', 'all');
        $query = Product::with(['unit', 'category', 'subCategory', 'submitter'])->latest('updated_at');

        if ($scope === 'riwayat') {
            $query->whereIn('status', ['tayang', 'ditolak', 'draft']);
        }

        $products = $query->get();
        $filename = 'pnj-prime-p3m-' . $scope . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM agar Excel membaca karakter dengan benar
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Kode Pengajuan',
                'Nama Produk',
                'Unit Pengelola',
                'Kategori',
                'Sub-Kategori',
                'Tipe Order',
                'HPP (Rp)',
                'Harga B2C (Rp)',
                'Harga B2B (Rp)',
                'Stok',
                'Status',
                'Catatan Kurasi',
                'Terakhir Diperbarui',
            ]);

            foreach ($products as $item) {
                fputcsv($handle, [
                    $item->code,
                    $item->name,
                    $item->unit->name ?? '-',
                    $item->category->name ?? '-',
                    $item->subCategory->name ?? '-',
                    $item->order_type,
                    $item->hpp,
                    $item->price_b2c,
                    $item->price_b2b,
                    $item->stock,
                    $item->status,
                    $item->curation_notes ?? '-',
                    optional($item->updated_at)->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Generate kode produk unik berdasarkan kode unit.
     */
    protected function generateProductCode(string $unitCode): string
    {
        $prefix = 'PRD-' . Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $unitCode), 0, 3));
        $seq = (Product::max('id') ?? 0) + 1;

        do {
            $code = sprintf('%s-%03d', $prefix, $seq);
            $seq++;
        } while (Product::where('code', $code)->exists());

        return $code;
    }
}
