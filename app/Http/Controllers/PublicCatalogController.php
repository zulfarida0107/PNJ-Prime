<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class PublicCatalogController extends Controller
{
    /**
     * Katalog Riset & Inovasi Publik (tanpa login).
     */
    public function index(Request $request): View
    {
        $searchQuery = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');
        $orderType = $request->query('order_type');
        $trlFilter = $request->query('trl');

        $isLabMode = $orderType === 'lab_reservation';

        $categories = Category::query()->orderBy('id')->get();
        $units = Unit::query()->orderBy('name')->get();

        $baseQuery = Product::query()
            ->where('status', 'tayang');

        if ($isLabMode) {
            $baseQuery->where('order_type', 'lab_reservation');
        } else {
            $baseQuery->whereIn('order_type', ['ready_stock', 'pre_order']);
        }

        $totalPublishedCount = (clone $baseQuery)->count();

        $query = (clone $baseQuery)
            ->with(['category', 'subCategory', 'unit']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (! $isLabMode && $orderType && in_array($orderType, ['ready_stock', 'pre_order'], true)) {
            $query->where('order_type', $orderType);
        }

        if ($trlFilter && in_array((string) $trlFilter, ['5', '6', '7'], true)) {
            $matchingOrderTypes = match ((string) $trlFilter) {
                '6' => $isLabMode ? [] : ['pre_order'],
                '7' => $isLabMode ? ['lab_reservation'] : ['ready_stock'],
                default => [],
            };

            if (! empty($matchingOrderTypes)) {
                $query->whereIn('order_type', $matchingOrderTypes);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('code', 'like', '%' . $searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', '%' . $searchQuery . '%'))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', '%' . $searchQuery . '%'))
                    ->orWhereHas('subCategory', fn ($sc) => $sc->where('name', 'like', '%' . $searchQuery . '%'));
            });
        }

        $products = $query
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('public.katalog', [
            'products' => $products,
            'totalPublishedCount' => $totalPublishedCount,
            'isLabMode' => $isLabMode,
            'categories' => $categories,
            'units' => $units,
            'filters' => [
                'q' => $searchQuery,
                'category_id' => $categoryId,
                'order_type' => $isLabMode ? 'lab_reservation' : (in_array($orderType, ['ready_stock', 'pre_order'], true) ? $orderType : null),
                'trl' => $trlFilter,
            ],
            'dashboardUrl' => $this->resolveDashboardUrl(Auth::user()),
            'productMetaMap' => $this->buildProductMetaMap($products->items()),
        ]);
    }

    /**
     * Halaman Detail Produk Riset & Fasilitas Lab Publik (tanpa login).
     */
    public function show(Request $request, string $product): View
    {
        $item = Product::query()
            ->with(['category', 'subCategory', 'unit'])
            ->where('status', 'tayang')
            ->where(function ($q) use ($product) {
                if (ctype_digit($product)) {
                    $q->where('id', (int) $product)->orWhere('code', $product);
                } else {
                    $q->where('code', $product);
                }
            })
            ->firstOrFail();

        $relatedQuery = Product::query()
            ->with(['category', 'subCategory', 'unit'])
            ->where('status', 'tayang')
            ->where('id', '!=', $item->id);

        if ($item->order_type === 'lab_reservation') {
            $relatedQuery->where('order_type', 'lab_reservation');
        } else {
            $relatedQuery
                ->whereIn('order_type', ['ready_stock', 'pre_order'])
                ->orderByRaw('CASE WHEN order_type = ? THEN 0 ELSE 1 END', [$item->order_type]);
        }

        $relatedProducts = $relatedQuery
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$item->category_id])
            ->orderBy('id')
            ->limit(3)
            ->get();

        $meta = $this->resolveProductMeta($item);

        return view('public.detail', [
            'product' => $item,
            'meta' => $meta,
            'detail' => $this->buildProductDetailData($item, $meta),
            'relatedProducts' => $relatedProducts,
            'relatedMetaMap' => $this->buildProductMetaMap($relatedProducts->all()),
            'dashboardUrl' => $this->resolveDashboardUrl(Auth::user()),
            'searchQuery' => trim((string) $request->query('q', '')),
        ]);
    }

    /**
     * Tentukan URL dashboard sesuai role user yang sedang login.
     */
    protected function resolveDashboardUrl(?User $user): string
    {
        if (! $user) {
            return route('login');
        }

        return match ($user->role) {
            'mhs', 'dosen' => url('/dashboard-mahasiswa'),
            'mitra' => Route::has('b2b.dashboard') ? route('b2b.dashboard') : url('/dashboard-mitra'),
            'pimpinan' => url('/dashboard-pimpinan'),
            'p3m' => Route::has('admin.p3m.dashboard') ? route('admin.p3m.dashboard') : url('/'),
            'rtpu', 'admin' => Route::has('admin.rtpu.dashboard') ? route('admin.rtpu.dashboard') : url('/dashboard-pimpinan'),
            default => url('/'),
        };
    }

    /**
     * Bangun peta metadata tampilan (TRL, persentase kesiapan, estimasi, badge, CTA) per produk.
     *
     * @param  array<int, Product>  $products
     * @return array<int, array<string, mixed>>
     */
    protected function buildProductMetaMap(array $products): array
    {
        $map = [];
        foreach ($products as $product) {
            $map[$product->id] = $this->resolveProductMeta($product);
        }

        return $map;
    }

    /**
     * Metadata tampilan deterministik untuk kartu & halaman detail katalog publik.
     *
     * @return array<string, mixed>
     */
    protected function resolveProductMeta(Product $product): array
    {
        $isCnc = $product->code === 'PRD-MSN-005';

        return match ($product->order_type) {
            'pre_order' => [
                'trl_level' => 6,
                'trl_label' => 'TRL 6',
                'trl_status' => 'Demonstrasi Prototipe',
                'readiness_pct' => 85,
                'order_label' => 'Pre-Order',
                'order_pill' => 'bg-sky-50 text-sky-700 border-sky-200',
                'order_dot' => 'bg-sky-500',
                'estimasi' => $isCnc ? '14 Hari Kerja' : '7 Hari Kerja',
                'satuan' => 'Unit',
                'stok_label' => 'Kuota Produksi',
                'cta_label' => 'Ajukan Pre-Order',
            ],
            'lab_reservation' => [
                'trl_level' => 7,
                'trl_label' => 'TRL 7',
                'trl_status' => 'Tervalidasi Industri',
                'readiness_pct' => 92,
                'order_label' => 'Sewa Fasilitas Lab',
                'order_pill' => 'bg-[#E6F4F2] text-[#00877A] border-[#99D3CB]',
                'order_dot' => 'bg-[#00877A]',
                'estimasi' => 'Tarif Per Jam',
                'satuan' => 'Jam',
                'stok_label' => 'Kapasitas Slot',
                'cta_label' => 'Reservasi Fasilitas Lab',
            ],
            default => [
                'trl_level' => 7,
                'trl_label' => 'TRL 7',
                'trl_status' => 'Siap Operasional',
                'readiness_pct' => 95,
                'order_label' => 'Ready Stock',
                'order_pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'order_dot' => 'bg-emerald-500',
                'estimasi' => '1–2 Hari Kerja',
                'satuan' => 'Unit',
                'stok_label' => 'Kuota Produksi',
                'cta_label' => 'Pesan Sekarang',
            ],
        };
    }

    /**
     * Siapkan seluruh data terstruktur untuk halaman detail produk/fasilitas lab publik:
     * hero, poster, galeri tervalidasi file fisik, fitur unggulan, ketua tim riset,
     * legalitas dokumen, dan spesifikasi teknis lengkap.
     *
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    protected function buildProductDetailData(Product $product, array $meta): array
    {
        $isLab = $product->order_type === 'lab_reservation';
        $isCnc = $product->code === 'PRD-MSN-005';
        $unitName = $product->unit?->name ?? 'UPA-RTPU Politeknik Negeri Jakarta';
        $categoryName = $product->category?->name ?? ($isLab ? 'Ruangan/Lab' : 'Barang');
        $subCategoryName = $product->subCategory?->name ?? ($isLab ? 'Fasilitas Laboratorium' : 'Produk Riset');

        $profiles = $this->productCatalogProfiles();
        $profile = $profiles[$product->code] ?? null;

        $backUrl = $isLab
            ? route('public.katalog', ['order_type' => 'lab_reservation'])
            : route('public.katalog');

        // 1. Poster Produk & Blueprint
        $poster = $this->resolvePosterData($product, $meta, $isCnc, $profile);

        // 2. Galeri Produk & Hasil Uji Komponen (wajib cek file_exists agar 0 broken image)
        $gallery = $this->resolveGalleryData($product, $isCnc, $isLab);

        // 3. Fitur Unggulan (4 card)
        $features = $profile['features'] ?? $this->defaultFeatures($product, $isLab);

        // 4. Deskripsi Industri & Sorotan 2x2
        $industryContext = $profile['industry_context'] ?? ($isLab
            ? "Fasilitas {$product->name} dikelola langsung oleh {$unitName} Politeknik Negeri Jakarta dengan standar operasional laboratorium vokasi modern. Seluruh sesi reservasi dilengkapi verifikasi jadwal, pendampingan teknisi bersertifikat, dan dokumen berita acara resmi BLU PNJ."
            : "Inovasi {$product->name} dikembangkan oleh tim peneliti {$unitName} Politeknik Negeri Jakarta untuk menjawab kebutuhan efisiensi industri nasional. Setiap unit melewati tahap kurasi mutu LPPM dan UPA-RTPU PNJ sehingga siap diimplementasikan di lingkungan operasional mitra.");

        $applicationHighlights = $profile['highlights'] ?? $this->defaultHighlights($isLab);

        // 5. Ketua Tim Riset / Penanggung Jawab Lab
        $researcher = $profile['researcher'] ?? $this->resolveUnitResearcher($product, $isLab);

        // 6. Legalitas & Dokumen Riset (3 item tanpa 404)
        $patentCode = $profile['cert_code'] ?? ($isLab ? 'ISO/IEC 17025:2017' : 'IDP0000' . str_pad((string) (84000 + $product->id * 73), 5, '0', STR_PAD_LEFT));
        $legalDocuments = [
            [
                'key' => 'sertifikat',
                'title' => $isLab ? 'Sertifikat Kalibrasi & Akreditasi Lab' : 'Sertifikat Paten / HKI Terdaftar',
                'subtitle' => $patentCode . ' • Terverifikasi',
                'action_type' => 'modal',
                'doc_number' => $patentCode,
                'issuer' => $isLab ? 'Komite Akreditasi Nasional (KAN) & UPA-RTPU PNJ' : 'DJKI Kemenkumham RI & Sentra HKI PNJ',
                'status' => 'Aktif & Berlaku Resmi',
                'summary' => $isLab
                    ? "Fasilitas {$product->name} telah melalui verifikasi kelayakan operasional, kalibrasi instrumen berkala, dan penetapan tarif resmi Badan Layanan Umum (BLU) Politeknik Negeri Jakarta."
                    : "Karya inovasi {$product->name} tercatat resmi atas nama Politeknik Negeri Jakarta dan siap dihilirisasi melalui skema pengadaan resmi BLU UPA-RTPU PNJ.",
            ],
            [
                'key' => 'laporan_uji',
                'title' => $isLab ? 'SOP Operasional & Standar K3 Lab' : 'Laporan Uji Kalibrasi / Kelayakan',
                'subtitle' => $isLab ? 'Dokumen Mutu BLU • Rev. 2026' : 'Verifikasi Teknis LPPM & RTPU • 2026',
                'action_type' => 'modal',
                'doc_number' => 'QA-' . $product->code . '-2026',
                'issuer' => 'Unit Penunjang Akademik RTPU & LPPM PNJ',
                'status' => 'Lolos Kurasi Teknis (' . $meta['trl_label'] . ')',
                'summary' => $product->curation_notes ?: "Telah melalui asesmen kesiapan teknologi {$meta['trl_label']} ({$meta['readiness_pct']}%) dan dinyatakan layak untuk pengadaan/layanan industri melalui portal PNJ Prime.",
            ],
            [
                'key' => 'brosur',
                'title' => 'Brosur & Spesifikasi Teknis (PDF)',
                'subtitle' => 'Siap Cetak A4 • Tarif Resmi BLU',
                'action_type' => 'print',
                'doc_number' => 'BRS-' . $product->code,
                'issuer' => 'PNJ Prime Enterprise Portal',
                'status' => 'Tersedia Langsung',
                'summary' => 'Cetak atau simpan ringkasan spesifikasi teknis, harga/tarif resmi BLU, dan informasi pengadaan dalam format PDF A4.',
            ],
        ];

        // 7. Spesifikasi Teknis (6-7 baris)
        $specifications = $profile['specifications'] ?? $this->defaultSpecifications($product, $meta, $isLab);

        return [
            'is_lab' => $isLab,
            'is_cnc' => $isCnc,
            'back_url' => $backUrl,
            'breadcrumb_section_label' => $isLab ? 'SEWA FASILITAS LAB' : 'KATALOG RISET',
            'hero' => [
                'program_badge' => $isLab ? 'Fasilitas Lab Tersertifikasi 2026' : 'Produk Unggulan Vokasi 2026',
                'trl_badge' => $meta['trl_label'] . ' • ' . $meta['trl_status'],
                'cert_badge' => ($isLab ? 'Sertifikasi: ' : 'Paten / HKI: ') . $patentCode,
                'eyebrow' => strtoupper($categoryName . ' • ' . $subCategoryName . ' • ' . $meta['order_label']),
                'summary' => $profile['hero_summary'] ?? str($product->description)->limit(185)->toString(),
                'verified_label' => 'Terverifikasi UPA-RTPU PNJ',
            ],
            'poster' => $poster,
            'gallery' => $gallery,
            'features_heading' => $isLab ? 'Keunggulan Fasilitas Laboratorium' : 'Fitur Unggulan Inovasi',
            'features_eyebrow' => $isLab ? 'STANDAR LAYANAN LABORATORIUM BLU' : 'KEUNGGULAN KOMPETITIF RISET',
            'features_badge' => $isLab ? 'Didampingi Teknisi Bersertifikat' : 'Siap Implementasi Industri',
            'features' => $features,
            'description_heading' => $isLab ? 'Profil Fasilitas Laboratorium & Ruang Lingkup Layanan' : 'Deskripsi Lengkap Produk & Konteks Industri',
            'industry_context' => $industryContext,
            'highlights' => $applicationHighlights,
            'info_banner' => $isLab
                ? [
                    'title' => 'Standar Operasional, Kalibrasi & Pendampingan Teknisi PNJ',
                    'body' => 'Setiap reservasi fasilitas laboratorium mencakup pendampingan teknisi/laboran tersertifikasi PNJ, peralatan terkalibrasi standar industri, protokol K3 laboratorium, serta lembar hasil uji dan BAST resmi BLU PNJ.',
                ]
                : [
                    'title' => 'Fasilitas Insentif Pajak (Super Tax Deduction hingga 300%)',
                    'body' => 'Berdasarkan PP No. 45 Tahun 2019 & PMK No. 153/2020, mitra industri yang mengadopsi dan mengembangkan inovasi riset terapan bersama Politeknik Negeri Jakarta berpotensi memperoleh pengurangan penghasilan bruto hingga 300% sesuai ketentuan perpajakan yang berlaku.',
                ],
            'researcher' => $researcher,
            'legal_documents' => $legalDocuments,
            'specifications' => $specifications,
            'cta_banner' => [
                'eyebrow' => $isLab ? 'RESERVASI RESMI FASILITAS BLU PNJ' : 'PENGADAAN RESMI INOVASI VOKASI PNJ',
                'title' => $isLab
                    ? 'Siap Menggunakan ' . $product->name . ' untuk Proyek Anda?'
                    : 'Siap Mengadopsi ' . $product->name . ' untuk Lini Operasional Anda?',
                'subtitle' => $isLab
                    ? 'Ajukan jadwal reservasi laboratorium secara transparan dengan tarif resmi BLU PNJ, skema pembayaran 100% Lunas via VA Mandiri BLU / QRIS, serta dukungan penuh teknisi bersertifikat.'
                    : 'Dapatkan produk hasil riset terapan resmi BLU PNJ dengan skema pembayaran 100% Lunas via VA Mandiri BLU / QRIS, kelengkapan faktur pajak PPN 11%, e-Meterai, dan BAST resmi.',
            ],
        ];
    }

    /**
     * Siapkan data poster produk / blueprint dengan pengecekan file fisik.
     *
     * @param  array<string, mixed>  $meta
     * @param  array<string, mixed>|null  $profile
     * @return array<string, mixed>
     */
    protected function resolvePosterData(Product $product, array $meta, bool $isCnc, ?array $profile): array
    {
        $cncPosterRel = 'images/products/mesin-cnc-poster.png';
        if ($isCnc && is_file(public_path($cncPosterRel))) {
            return [
                'has_dedicated_poster' => true,
                'url' => asset($cncPosterRel),
                'download_url' => asset($cncPosterRel),
                'download_name' => 'Poster-Mesin-CNC-5-Axis-PNJ-Prime.png',
                'caption' => 'Poster Produk & Blueprint Mesin CNC 5-Axis Presisi Tinggi — Teknik Mesin PNJ',
                'badge' => 'Resolusi HD',
                'metric_1_label' => 'Kecepatan Spindle',
                'metric_1_value' => '24.000 RPM HF',
                'metric_2_label' => 'Akurasi Posisi',
                'metric_2_value' => '±0,02 mm (5-Axis)',
            ];
        }

        $fallbackUrl = $product->resolved_image_url;

        return [
            'has_dedicated_poster' => false,
            'url' => $fallbackUrl,
            'download_url' => $fallbackUrl,
            'download_name' => 'Poster-' . $product->code . '-PNJ-Prime.png',
            'caption' => 'Poster Ringkas ' . $product->name . ' — ' . ($product->unit?->name ?? 'UPA-RTPU PNJ'),
            'badge' => 'Resolusi HD',
            'metric_1_label' => $profile['poster_metric_1_label'] ?? $meta['stok_label'],
            'metric_1_value' => $profile['poster_metric_1_value'] ?? ($product->stock . ' ' . $meta['satuan']),
            'metric_2_label' => $profile['poster_metric_2_label'] ?? 'Kesiapan & Estimasi',
            'metric_2_value' => $profile['poster_metric_2_value'] ?? ($meta['trl_label'] . ' • ' . $meta['estimasi']),
        ];
    }

    /**
     * Siapkan daftar galeri produk yang seluruh file fisiknya tervalidasi (0 broken image).
     *
     * @return array<int, array<string, string>>
     */
    protected function resolveGalleryData(Product $product, bool $isCnc, bool $isLab): array
    {
        $items = [];

        if ($isCnc) {
            $cncCandidates = [
                [
                    'path' => 'images/products/mesin-cnc-5axis.png',
                    'title' => 'Unit Utama Mesin CNC 5-Axis Presisi Tinggi',
                    'caption' => 'Unit Utama Mesin CNC 5-Axis Presisi Tinggi — Rangka rigid 5-sumbu simultan rancangan Jurusan Teknik Mesin PNJ.',
                    'tag' => 'UNIT UTAMA',
                ],
                [
                    'path' => 'images/products/mesin-cnc-galeri-1.png',
                    'title' => 'Galeri Produk & Hasil Uji Komponen (Batch 1)',
                    'caption' => 'Galeri Produk & Hasil Uji Komponen (Batch 1) — Uji pemesinan kontur presisi pada material logam & polimer teknik.',
                    'tag' => 'HASIL UJI 01',
                ],
                [
                    'path' => 'images/products/mesin-cnc-galeri-2.png',
                    'title' => 'Detail Perakitan & Pengujian Akurasi Komponen (Batch 2)',
                    'caption' => 'Detail Perakitan & Pengujian Akurasi Komponen (Batch 2) — Validasi akurasi geometris & repeatability ±0,02 mm.',
                    'tag' => 'HASIL UJI 02',
                ],
            ];

            foreach ($cncCandidates as $candidate) {
                if (is_file(public_path($candidate['path']))) {
                    $items[] = [
                        'url' => asset($candidate['path']),
                        'title' => $candidate['title'],
                        'caption' => $candidate['caption'],
                        'tag' => $candidate['tag'],
                        'object_position' => 'object-center',
                    ];
                }
            }

            if (! empty($items)) {
                return $items;
            }
        }

        $mainPath = $product->resolved_image_path;
        if ($mainPath !== null) {
            $mainUrl = str_starts_with($mainPath, 'http://') || str_starts_with($mainPath, 'https://')
                ? $mainPath
                : asset($mainPath);

            $items[] = [
                'url' => $mainUrl,
                'title' => ($isLab ? 'Tampilan Fasilitas: ' : 'Unit Utama: ') . $product->name,
                'caption' => $product->name . ' — Dokumentasi resmi terverifikasi ' . ($product->unit?->name ?? 'UPA-RTPU PNJ') . '.',
                'tag' => $isLab ? 'AREA UTAMA LAB' : 'UNIT UTAMA',
                'object_position' => 'object-center',
            ];

            $items[] = [
                'url' => $mainUrl,
                'title' => $isLab ? 'Detail Instrumen & Stasiun Kerja' : 'Detail Modul & Konstruksi Perangkat',
                'caption' => ($isLab
                    ? 'Konfigurasi perangkat utama yang digunakan dalam layanan reservasi '
                    : 'Tampilan detail komponen dan kualitas perakitan ') . $product->name . '.',
                'tag' => 'DETAIL VISUAL',
                'object_position' => 'object-left-top',
            ];

            $items[] = [
                'url' => $mainUrl,
                'title' => $isLab ? 'Lingkungan Pengujian Terkalibrasi' : 'Hasil Validasi & Kesiapan Hilirisasi',
                'caption' => ($isLab
                    ? 'Area operasional terstandar K3 dengan pendampingan teknisi bersertifikat PNJ.'
                    : 'Unit telah lolos kurasi teknis LPPM & UPA-RTPU PNJ siap implementasi mitra.'),
                'tag' => 'VALIDASI PNJ',
                'object_position' => 'object-right-bottom',
            ];
        }

        return $items;
    }

    /**
     * Profil kaya per kode produk/lab untuk 12 item aktif katalog PNJ Prime.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function productCatalogProfiles(): array
    {
        return [
            'PRD-MSN-005' => [
                'cert_code' => 'IDP000084521',
                'hero_summary' => 'Solusi substitusi impor teknologi fabrikasi 5-sumbu simultan dengan akurasi ±0,02 mm, telemetri sensorik IoT real-time, dan efisiensi daya tinggi untuk manufaktur presisi skala UMKM hingga industri.',
                'poster_metric_1_label' => 'Kecepatan Spindle',
                'poster_metric_1_value' => '24.000 RPM HF',
                'poster_metric_2_label' => 'Akurasi Posisi',
                'poster_metric_2_value' => '±0,02 mm (5-Axis)',
                'industry_context' => 'Mesin CNC 5-Axis Presisi Tinggi Skala UMKM ini dikembangkan oleh Laboratorium Manufaktur Presisi Jurusan Teknik Mesin Politeknik Negeri Jakarta untuk mengatasi ketergantungan industri komponen pada mesin multi-sumbu impor berharga ratusan juta rupiah. Arsitektur rangka rigid berperedam getaran dipadukan kontroler 5-sumbu simultan dan modul telemetri IoT memungkinkan pemesinan impeller, cetakan mikro, prostetik, dan komponen mekanik kompleks dengan biaya operasional jauh lebih ekonomis.',
                'features' => [
                    [
                        'icon' => 'target',
                        'title' => '5-Axis Simultaneous Precision',
                        'description' => 'Akurasi posisi ±0,02 mm dengan interpolasi 5-sumbu simultan untuk kontur geometri rumit dan komponen mikro-presisi.',
                        'tag' => 'Kontur Presisi',
                    ],
                    [
                        'icon' => 'activity',
                        'title' => 'IoT Real-Time Telemetry',
                        'description' => 'Sensor getaran dan suhu bantalan spindle terhubung via MQTT/Modbus untuk pemantauan beban & deteksi dini keausan.',
                        'tag' => 'Edge Telemetry',
                    ],
                    [
                        'icon' => 'zap',
                        'title' => 'Efisiensi Daya & Desain Ringkas',
                        'description' => 'Konsumsi daya puncak hemat 1.500 Watt (1-fasa 220V) dengan tapak kompak untuk bengkel UMKM dan laboratorium vokasi.',
                        'tag' => 'Hemat Daya',
                    ],
                    [
                        'icon' => 'shield',
                        'title' => 'Kalibrasi ISO 17025 & BAST',
                        'description' => 'Dilengkapi laporan uji akurasi geometris, garansi servis 1 tahun, pelatihan CAM dasar, dan BAST resmi BLU PNJ.',
                        'tag' => 'Terverifikasi BLU',
                    ],
                ],
                'highlights' => [
                    ['icon' => 'factory', 'title' => 'IKM & Bengkel Fabrikasi Prototipe', 'description' => 'Memungkinkan bengkel manufaktur menerima pesanan komponen kompleks tanpa outsourcing luar negeri.'],
                    ['icon' => 'heart', 'title' => 'Alat Kesehatan & Komponen Presisi', 'description' => 'Fabrikasi cetakan instrumen, komponen ortopedi, dan part polimer medis presisi tinggi.'],
                    ['icon' => 'cog', 'title' => 'Otomotif & Dirgantara Ringan', 'description' => 'Pemesinan bracket aluminium, impeller turbin mini, dan casing elektronik tahan getaran.'],
                    ['icon' => 'graduation', 'title' => 'Pendidikan Vokasi & R&D Industri', 'description' => 'Media praktikum CAD/CAM 5-sumbu modern sekaligus unit rapid prototyping tim R&D.'],
                ],
                'researcher' => [
                    'role_label' => 'KETUA TIM RISET',
                    'badge' => 'Terverifikasi PNJ',
                    'initials' => 'HW',
                    'name' => 'Dr. Hendra Wijaya, M.T.',
                    'title' => 'Kepala Laboratorium Manufaktur Presisi — Jurusan Teknik Mesin PNJ',
                    'credentials' => '14 Paten Terdaftar & Hilirisasi Industri',
                    'quote' => 'Kami mendampingi mitra industri mulai dari kustomisasi fixture, uji akurasi ISO, hingga pelatihan operator CAM sampai unit beroperasi penuh.',
                    'email' => 'hendra.wijaya@mesin.pnj.ac.id',
                    'location' => 'Gedung Workshop Teknik Mesin Lt. 1, Kampus PNJ Depok',
                ],
                'specifications' => [
                    ['parameter' => 'Kapasitas Kerja (Working Volume)', 'value' => '300 × 300 × 200 mm (X / Y / Z)', 'method' => 'Rotasi A: ±120°, C: 360° Continuous'],
                    ['parameter' => 'Kecepatan Spindle', 'value' => 'Up to 24.000 RPM (Brushless HF)', 'method' => 'Closed-Loop Water Cooling System'],
                    ['parameter' => 'Akurasi & Repeatability', 'value' => '±0,02 mm (Posisi) / ±0,008 mm (Repeat)', 'method' => 'Kalibrasi Laser Interferometer ISO 230-2'],
                    ['parameter' => 'Controller & Firmware', 'value' => '5-Axis Industrial Motion Controller', 'method' => 'Standar G-Code ISO 6983 (Fusion 360 / Mastercam)'],
                    ['parameter' => 'Protokol IoT & Telemetri', 'value' => 'MQTT, Modbus TCP/IP, Ethernet / Wi-Fi', 'method' => 'Integrasi Dasbor Monitoring Real-Time PNJ'],
                    ['parameter' => 'Material Kompatibel', 'value' => 'Aluminium 6061/7075, Kuningan, Delrin, Akrilik', 'method' => 'Lolos Uji Pemotongan Kontinu 24 Jam'],
                    ['parameter' => 'Daya Listrik & Proteksi', 'value' => '1.500 Watt (Peak) — 220V Single Phase 50Hz', 'method' => 'MCB 16A & Emergency Stop IEC 60204-1'],
                ],
            ],

            'PRD-ELK-001' => [
                'cert_code' => 'EC00202614210',
                'hero_summary' => 'Node sensor agrikultur presisi generasi kedua berbasis ESP32 & LoRaWAN dengan pengukuran kelembapan tanah, pH, EC, dan suhu secara otomatis dalam casing tahan cuaca IP65.',
                'poster_metric_1_label' => 'Jangkauan LoRa',
                'poster_metric_1_value' => 'Hingga 5 km (LoS)',
                'poster_metric_2_label' => 'Proteksi Casing',
                'poster_metric_2_value' => 'IP65 Outdoor Ready',
                'features' => [
                    ['icon' => 'activity', 'title' => 'Multi-Sensor Tanah Presisi', 'description' => 'Mengukur kelembapan, pH, konduktivitas listrik (EC), dan suhu tanah dalam satu probe stainless tahan korosi.', 'tag' => '4-in-1 Probe'],
                    ['icon' => 'zap', 'title' => 'Transmisi LoRaWAN Hemat Daya', 'description' => 'Jangkauan komunikasi hingga 5 km di area terbuka dengan dukungan panel surya mini untuk operasi mandiri.', 'tag' => 'Low Power IoT'],
                    ['icon' => 'shield', 'title' => 'Enclosure Tahan Cuaca IP65', 'description' => 'Dirancang tahan hujan, debu, dan paparan UV ekstrem di lahan perkebunan maupun greenhouse tropis.', 'tag' => 'IP65 Weatherproof'],
                    ['icon' => 'target', 'title' => 'Integrasi REST API & MQTT', 'description' => 'Siap dihubungkan ke dasbor pertanian, sistem irigasi otomatis, dan notifikasi ambang batas secara real-time.', 'tag' => 'Open API'],
                ],
                'specifications' => [
                    ['parameter' => 'Mikrokontroler & Radio', 'value' => 'ESP32 Dual-Core + LoRa SX1276 (915/923 MHz)', 'method' => 'Uji Transmisi Lapangan AS923 Indonesia'],
                    ['parameter' => 'Parameter Sensor', 'value' => 'Kelembapan (0–100%), pH (3–9), EC, Suhu (-10~65°C)', 'method' => 'Kalibrasi Larutan Buffer Standar Lab'],
                    ['parameter' => 'Akurasi Pengukuran', 'value' => '±2% RH / ±0,1 pH / ±0,5°C', 'method' => 'Validasi Komparatif Meter Referensi'],
                    ['parameter' => 'Catu Daya & Baterai', 'value' => 'LiFePO4 3,2V 6000 mAh + Input Solar 5V/6W', 'method' => 'Uji Operasi Mandiri 14 Hari Tanpa Sinar'],
                    ['parameter' => 'Tingkat Proteksi', 'value' => 'IP65 Polycarbonate UV-Stabilized', 'method' => 'Uji Semprot Air & Debu IEC 60529'],
                    ['parameter' => 'Protokol Integrasi', 'value' => 'LoRaWAN, MQTT, JSON Webhook / REST API', 'method' => 'Dokumentasi API & Garansi Modul 6 Bulan'],
                ],
            ],

            'PRD-ELK-002' => [
                'cert_code' => 'IDP000089114',
                'hero_summary' => 'Kendaraan pemindah material otonom kapasitas 250 kg dengan navigasi LiDAR SLAM 360° dan integrasi WMS untuk otomasi logistik gudang dan lantai pabrik.',
                'poster_metric_1_label' => 'Kapasitas Angkut',
                'poster_metric_1_value' => '250 kg Payload',
                'poster_metric_2_label' => 'Navigasi Utama',
                'poster_metric_2_value' => 'LiDAR SLAM 360°',
                'features' => [
                    ['icon' => 'target', 'title' => 'Navigasi LiDAR SLAM Tanpa Rel', 'description' => 'Memetakan rute gudang secara dinamis tanpa perlu pemasangan pita magnetik atau modifikasi lantai.', 'tag' => 'Autonomous SLAM'],
                    ['icon' => 'shield', 'title' => 'Safety Anti-Tabrakan 360°', 'description' => 'Kombinasi safety laser scanner, sensor ultrasonik, dan bumper darurat mematuhi standar keselamatan AGV.', 'tag' => 'ISO 3691-4'],
                    ['icon' => 'zap', 'title' => 'Baterai LiFePO4 8 Jam Operasi', 'description' => 'Pengisian cepat dengan sistem manajemen baterai (BMS) pintar dan opsi auto-docking charging station.', 'tag' => 'Fast Charging'],
                    ['icon' => 'activity', 'title' => 'Konektivitas Fleet & WMS', 'description' => 'Antarmuka REST/WebSocket siap sinkron dengan sistem manajemen gudang (WMS) dan ERP perusahaan.', 'tag' => 'WMS Ready'],
                ],
                'specifications' => [
                    ['parameter' => 'Kapasitas Beban Maksimum', 'value' => '250 kg (Flat Deck / Towing Hook)', 'method' => 'Uji Beban Dinamis 125% Kapasitas'],
                    ['parameter' => 'Sistem Navigasi', 'value' => '2D LiDAR SLAM + IMU + Odometri Dual-Wheel', 'method' => 'Akurasi Docking ±15 mm'],
                    ['parameter' => 'Kecepatan Operasional', 'value' => '0,2 – 1,2 m/detik (Adaptif Zona Aman)', 'method' => 'Uji Manuver Koridor Lebar 1,4 m'],
                    ['parameter' => 'Baterai & Durasi Kerja', 'value' => 'LiFePO4 48V 40Ah (Durasi hingga 8 Jam)', 'method' => 'Siklus > 2.000 Charge Cycles'],
                    ['parameter' => 'Sistem Keselamatan', 'value' => 'Laser Safety Zone 360°, E-Stop, Audio-Visual Beacon', 'method' => 'Referensi Standar ISO 3691-4'],
                    ['parameter' => 'Antarmuka Komunikasi', 'value' => 'Wi-Fi Dual-Band 5GHz, REST API, MQTT', 'method' => 'Integrasi Fleet Dashboard PNJ'],
                ],
            ],

            'PRD-TIK-003' => [
                'cert_code' => 'EC00202618842',
                'hero_summary' => 'Sistem inspeksi visual otomatis berbasis Edge AI dan kamera industri berkecepatan tinggi dengan akurasi deteksi cacat di atas 98% langsung di lini konveyor.',
                'poster_metric_1_label' => 'Akurasi Deteksi',
                'poster_metric_1_value' => '> 98,4% mAP',
                'poster_metric_2_label' => 'Kecepatan Inferensi',
                'poster_metric_2_value' => 'Up to 60 FPS',
                'features' => [
                    ['icon' => 'target', 'title' => 'Deteksi Cacat Real-Time >98%', 'description' => 'Mendeteksi goresan, retak mikro, salah label, dan kelengkapan komponen secara otomatis di lini konveyor.', 'tag' => 'Edge Vision AI'],
                    ['icon' => 'zap', 'title' => 'Latensi Inferensi Ultra-Rendah', 'description' => 'Berjalan lokal pada perangkat Edge GPU tanpa ketergantungan koneksi internet dengan kecepatan hingga 60 FPS.', 'tag' => '60 FPS Local'],
                    ['icon' => 'activity', 'title' => 'Dasbor Analitik & Reject Trigger', 'description' => 'Terhubung langsung ke PLC/pneumatic ejector untuk pemilahan otomatis sekaligus mencatat statistik defect harian.', 'tag' => 'PLC Trigger'],
                    ['icon' => 'shield', 'title' => 'Retraining Model Mudah', 'description' => 'Mendukung penambahan SKU produk baru secara cepat oleh tim QC internal perusahaan.', 'tag' => 'Multi-SKU Ready'],
                ],
                'specifications' => [
                    ['parameter' => 'Unit Komputasi Edge', 'value' => 'Industrial Edge AI Box (GPU Tensor Accelerator)', 'method' => 'Uji Inferensi Kontinu 7×24 Jam'],
                    ['parameter' => 'Kamera & Pencahayaan', 'value' => 'Global Shutter 5 MP GigE + Dome LED Ring', 'method' => 'Kalibrasi Fokus & Distorsi Lensa'],
                    ['parameter' => 'Akurasi Klasifikasi', 'value' => '≥ 98,4% (Precision & Recall Tervalidasi)', 'method' => 'Dataset Uji Lini Manufaktur PNJ'],
                    ['parameter' => 'Kecepatan Pemrosesan', 'value' => '30 – 60 Frame per Detik (< 18 ms/frame)', 'method' => 'Benchmark TensorRT FP16'],
                    ['parameter' => 'Antarmuka Otomasi', 'value' => 'Digital I/O 24V (PLC), Modbus TCP, Ethernet', 'method' => 'Uji Integrasi Pneumatic Rejector'],
                    ['parameter' => 'Pelaporan & Audit', 'value' => 'Ekspor CSV/PDF, Log Foto Anomali Terarsip', 'method' => 'Dasbor Web Lokal & Cloud Opsional'],
                ],
            ],

            'PRD-TIK-002' => [
                'cert_code' => 'EC00202615091',
                'hero_summary' => 'Platform pemantauan daya listrik 3-fasa berbasis gateway Modbus/MQTT dan analitik beban puncak untuk menekan pemborosan energi mesin pabrik sesuai standar ISO 50001.',
                'poster_metric_1_label' => 'Sistem Kelistrikan',
                'poster_metric_1_value' => '3-Fasa 380V/400V',
                'poster_metric_2_label' => 'Standar Audit',
                'poster_metric_2_value' => 'Dukungan ISO 50001',
                'features' => [
                    ['icon' => 'activity', 'title' => 'Pemantauan 3-Fasa Real-Time', 'description' => 'Memantau tegangan, arus, faktor daya (cos φ), kWh, dan harmonisa tiap panel mesin secara bersamaan.', 'tag' => 'Power Quality'],
                    ['icon' => 'zap', 'title' => 'Deteksi Anomali & Beban Puncak', 'description' => 'Notifikasi otomatis via Telegram/email saat beban mendekati batas tarif WBP atau terjadi lonjakan tidak wajar.', 'tag' => 'Smart Alert'],
                    ['icon' => 'target', 'title' => 'Gateway Modbus & MQTT Industri', 'description' => 'Kompatibel dengan power meter standar RS485 Modbus RTU/TCP serta siap integrasi SCADA pabrik.', 'tag' => 'SCADA Ready'],
                    ['icon' => 'shield', 'title' => 'Laporan Siap Audit ISO 50001', 'description' => 'Menghasilkan laporan intensitas energi harian, bulanan, dan estimasi emisi karbon secara otomatis.', 'tag' => 'ISO 50001'],
                ],
                'specifications' => [
                    ['parameter' => 'Konfigurasi Panel', 'value' => '3-Fasa 4-Kawat (380/400 VAC, CT 100A–1000A)', 'method' => 'Akurasi Energi Kelas 0.5S IEC 62053'],
                    ['parameter' => 'Gateway Komunikasi', 'value' => 'Industrial ARM Gateway (RS485, Ethernet, 4G)', 'method' => 'Uji Ketahanan Transmisi Buffer Offline'],
                    ['parameter' => 'Protokol Standar', 'value' => 'Modbus RTU/TCP, MQTT TLS, REST API', 'method' => 'Enkripsi Data TLS 1.2+'],
                    ['parameter' => 'Interval Sampling', 'value' => '1 – 60 Detik (Dapat Dikonfigurasi)', 'method' => 'Penyimpanan Time-Series Terindeks'],
                    ['parameter' => 'Notifikasi Anomali', 'value' => 'Email, Webhook, Telegram Bot Alert', 'method' => 'Threshold Multi-Level per Panel'],
                    ['parameter' => 'Estimasi Implementasi', 'value' => 'Perakitan & Kalibrasi Panel 7 Hari Kerja', 'method' => 'Termasuk Commissioning & Training'],
                ],
            ],

            'PRD-MSN-004' => [
                'cert_code' => 'IDP000086309',
                'hero_summary' => 'Panel komposit ramah lingkungan berpenguat serat alam rami/kenaf dengan bobot 35% lebih ringan untuk komponen interior, penutup baterai, dan bodi kendaraan listrik.',
                'poster_metric_1_label' => 'Reduksi Bobot',
                'poster_metric_1_value' => '35% Lebih Ringan',
                'poster_metric_2_label' => 'Penguat Alami',
                'poster_metric_2_value' => 'Serat Rami / Kenaf',
                'features' => [
                    ['icon' => 'zap', 'title' => '35% Lebih Ringan dari Baja Tipis', 'description' => 'Rasio kekuatan terhadap berat optimal untuk meningkatkan efisiensi jarak tempuh kendaraan listrik.', 'tag' => 'Lightweight EV'],
                    ['icon' => 'shield', 'title' => 'Kekuatan Tarik & Impak Teruji', 'description' => 'Setiap batch produksi dilengkapi lembar hasil uji tarik dan lentur laboratorium berstandar ASTM D3039.', 'tag' => 'ASTM D3039'],
                    ['icon' => 'target', 'title' => 'Kustomisasi Dimensi & Cetakan', 'description' => 'Diproduksi melalui metode vacuum infusion sesuai profil cetakan interior atau casing baterai mitra.', 'tag' => 'Custom Mold'],
                    ['icon' => 'activity', 'title' => 'Material Ramah Lingkungan', 'description' => 'Memanfaatkan serat alam lokal terbarukan guna mendukung target TKDN dan pengurangan jejak karbon.', 'tag' => 'TKDN & ESG'],
                ],
                'specifications' => [
                    ['parameter' => 'Komposisi Penguat & Matriks', 'value' => 'Serat Rami / Kenaf Teralkalisasi + Epoksi Industri', 'method' => 'Proses Vacuum Assisted Resin Infusion'],
                    ['parameter' => 'Densitas Material', 'value' => '1,22 – 1,30 g/cm³ (35% Lebih Ringan)', 'method' => 'Uji Densitas ASTM D792'],
                    ['parameter' => 'Kekuatan Tarik (Tensile)', 'value' => '115 – 145 MPa', 'method' => 'Uji Tarik UTM ASTM D3039'],
                    ['parameter' => 'Kekuatan Lentur (Flexural)', 'value' => '155 – 180 MPa', 'method' => 'Uji Lentur 3-Titik ASTM D790'],
                    ['parameter' => 'Ketebalan Panel Standar', 'value' => '2,5 – 8,0 mm (Kustom sesuai gambar teknik)', 'method' => 'Toleransi Ketebalan ±0,3 mm'],
                    ['parameter' => 'Ketahanan Termal', 'value' => 'Stabil hingga 110°C (Flame-Retardant Opsional)', 'method' => 'Uji Panas & Uji Bakar UL-94'],
                ],
            ],

            'LAB-SPL-001' => [
                'cert_code' => 'SNI / ASTM TERKALIBRASI',
                'hero_summary' => 'Fasilitas pengujian mekanik material terpadu (UTM, uji tekan beton, uji tarik logam/komposit, dan metalografi) dengan pendampingan laboran bersertifikat dan tarif resmi BLU per jam.',
                'poster_metric_1_label' => 'Kapasitas UTM',
                'poster_metric_1_value' => 'Hingga 1.000 kN',
                'poster_metric_2_label' => 'Standar Uji',
                'poster_metric_2_value' => 'SNI & ASTM Resmi',
                'features' => [
                    ['icon' => 'target', 'title' => 'Universal Testing Machine 1000 kN', 'description' => 'Mendukung pengujian tarik, tekan, lentur, dan geser untuk baja tulangan, logam, beton, hingga komposit.', 'tag' => 'UTM Digital'],
                    ['icon' => 'shield', 'title' => 'Acuan Standar SNI & ASTM', 'description' => 'Prosedur pengujian mengikuti acuan SNI, ASTM E8, dan ASTM C39 dengan hasil terkalibrasi periodik.', 'tag' => 'SNI / ASTM'],
                    ['icon' => 'activity', 'title' => 'Lembar Data & Grafik Uji Resmi', 'description' => 'Pengguna menerima kurva tegangan-regangan digital serta laporan pengujian bertanda tangan penanggung jawab lab.', 'tag' => 'Laporan Resmi'],
                    ['icon' => 'zap', 'title' => 'Pendampingan Laboran Ahli', 'description' => 'Setiap sesi sewa sudah mencakup operator/laboran tersertifikasi untuk memastikan keselamatan dan akurasi.', 'tag' => 'Full Assistance'],
                ],
                'specifications' => [
                    ['parameter' => 'Peralatan Utama', 'value' => 'UTM 1000 kN, Compression Tester 2000 kN, Rockwell', 'method' => 'Kalibrasi Tahunan KAN ISO/IEC 17025'],
                    ['parameter' => 'Jenis Pengujian', 'value' => 'Uji Tarik, Tekan Beton, Lentur, Kekerasan, Metalografi', 'method' => 'SNI 1974, ASTM E8/E8M, ASTM D3039'],
                    ['parameter' => 'Kapasitas Sampel', 'value' => 'Silinder Beton Ø150×300 mm, Pelat/Batang Logam', 'method' => 'Preparasi Spesimen Tersedia di Lab'],
                    ['parameter' => 'Luaran Dokumen', 'value' => 'Grafik Stress-Strain Digital (CSV/PDF) & Berita Acara', 'method' => 'Verifikasi Kepala Lab Teknik Sipil'],
                    ['parameter' => 'Fasilitas Pendukung', 'value' => 'Laboran Pendamping, APD Standar K3, Ruang Preparasi', 'method' => 'SOP Keselamatan Laboratorium PNJ'],
                    ['parameter' => 'Jam Operasional Reservasi', 'value' => 'Senin – Jumat, 08.00 – 16.00 WIB (Slot Per Jam)', 'method' => 'Penjadwalan Terverifikasi UPA-RTPU'],
                ],
            ],

            'LAB-TIK-002' => [
                'cert_code' => 'STUDIO KREATIF BLU PNJ',
                'hero_summary' => 'Studio desain grafis dan dokumentasi prototipe profesional dengan workstation layar terkalibrasi sRGB/DCI-P3, pen display grafis, dan area fotografi produk.',
                'poster_metric_1_label' => 'Kalibrasi Warna',
                'poster_metric_1_value' => '100% sRGB / DCI-P3',
                'poster_metric_2_label' => 'Fasilitas Area',
                'poster_metric_2_value' => 'Workstation + Photo Set',
                'features' => [
                    ['icon' => 'target', 'title' => 'Workstation Warna Terkalibrasi', 'description' => 'Monitor profesional 4K terkalibrasi warna akurat untuk desain kemasan, UI/UX, dan materi cetak industri.', 'tag' => 'Color Accurate'],
                    ['icon' => 'zap', 'title' => 'Pen Display & Suite Desain', 'description' => 'Tersedia pen display grafis presisi serta perangkat lunak desain 2D/3D berlisensi resmi kampus.', 'tag' => 'Pro Suite'],
                    ['icon' => 'activity', 'title' => 'Area Foto Prototipe Produk', 'description' => 'Dilengkapi lighting studio softbox dan backdrop netral untuk dokumentasi katalog dan presentasi klien.', 'tag' => 'Product Shoot'],
                    ['icon' => 'shield', 'title' => 'Konektivitas Fiber & Ruang Nyaman', 'description' => 'Jaringan gigabit stabil dengan pendingin ruangan optimal untuk sesi kolaborasi kreatif harian.', 'tag' => 'Gigabit Ready'],
                ],
                'specifications' => [
                    ['parameter' => 'Workstation Grafis', 'value' => 'CPU Multi-Core, RAM 32 GB, GPU Dedicated, SSD NVMe', 'method' => 'Pemeliharaan Berkala Lab TIK PNJ'],
                    ['parameter' => 'Layar & Pen Display', 'value' => 'Monitor 27" 4K IPS (ΔE < 2) + Pen Display 22"', 'method' => 'Kalibrasi Colorimeter Triwulanan'],
                    ['parameter' => 'Perangkat Dokumentasi', 'value' => 'Dual Softbox Continuous LED 5600K + Meja Produk', 'method' => 'CRI ≥ 95 untuk Akurasi Warna'],
                    ['parameter' => 'Konektivitas Jaringan', 'value' => 'LAN Gigabit & Wi-Fi 6 Dedicated Kampus PNJ', 'method' => 'Bandwidth Simetris Stabil'],
                    ['parameter' => 'Kapasitas Ruang', 'value' => 'Hingga 10 Workstation Aktif / Tim Produksi', 'method' => 'Reservasi Fleksibel Per Jam'],
                    ['parameter' => 'Dukungan Teknis', 'value' => 'Teknisi Studio TIK & BAST Resmi BLU PNJ', 'method' => 'SOP Layanan Fasilitas UPA-RTPU'],
                ],
            ],

            'LAB-MSN-003' => [
                'cert_code' => 'ISO/IEC 17025 WORKSHOP',
                'hero_summary' => 'Workshop pemesinan CNC Milling 3–5 Axis dan CNC Turning presisi tinggi dengan operator bersertifikat untuk pembuatan mold, dies, jig, dan prototipe industri.',
                'poster_metric_1_label' => 'Tipe Mesin',
                'poster_metric_1_value' => 'CNC 3–5 Axis & Lathe',
                'poster_metric_2_label' => 'Format File',
                'poster_metric_2_value' => '.STEP / .IGES / .DXF',
                'features' => [
                    ['icon' => 'target', 'title' => 'CNC Milling 3–5 Axis & Turning', 'description' => 'Mendukung fabrikasi komponen kompleks mulai dari prototipe tunggal hingga batch kecil presisi tinggi.', 'tag' => 'Multi-Axis CNC'],
                    ['icon' => 'shield', 'title' => 'Operator CNC Bersertifikat', 'description' => 'Tarif per jam sudah termasuk pendampingan operator ahli Jurusan Teknik Mesin PNJ untuk set-up & eksekusi.', 'tag' => 'Certified Operator'],
                    ['icon' => 'activity', 'title' => 'Alur Kerja CAD/CAM Langsung', 'description' => 'Cukup unggah file .STEP, .IGES, atau .DXF untuk validasi toolpath dan simulasi pemotongan sebelum eksekusi.', 'tag' => 'CAD/CAM Ready'],
                    ['icon' => 'zap', 'title' => 'Inspeksi Metrologi Akhir', 'description' => 'Setiap komponen hasil pemesinan dapat diverifikasi dimensinya menggunakan instrumen ukur presisi workshop.', 'tag' => 'QC Metrologi'],
                ],
                'specifications' => [
                    ['parameter' => 'Unit Mesin Tersedia', 'value' => 'CNC VMC 3-Axis, CNC 5-Axis, CNC Turning Center', 'method' => 'Kalibrasi Geometris Berkala'],
                    ['parameter' => 'Toleransi Pemesinan', 'value' => 'Hingga ±0,01 – ±0,02 mm (Tergantung Material)', 'method' => 'Verifikasi Micrometer & Bore Gauge'],
                    ['parameter' => 'Format Desain Didukung', 'value' => '.STEP, .STP, .IGES, .DXF, .SLDPRT, .F3D', 'method' => 'Simulasi CAM Sebelum Eksekusi'],
                    ['parameter' => 'Material Didukung', 'value' => 'Aluminium, Baja Karbon, Stainless 304, Kuningan, POM', 'method' => 'Material Bawaan Mitra / Pengadaan Lab'],
                    ['parameter' => 'Tenaga Pendamping', 'value' => 'Operator CNC Bersertifikat BNSP & Teknisi Lab', 'method' => 'Termasuk dalam Tarif Per Jam BLU'],
                    ['parameter' => 'Standar Keselamatan', 'value' => 'Zona K3 Workshop, APD Wajib, Proteksi Interlock', 'method' => 'SOP K3 Teknik Mesin PNJ'],
                ],
            ],

            'LAB-ELK-004' => [
                'cert_code' => 'LAB OTOMASI TERVERIFIKASI',
                'hero_summary' => 'Laboratorium otomasi terpadu dilengkapi lengan robot industri 6-DOF, stasiun PLC/SCADA, pneumatik, dan meja uji integrasi sensor industri.',
                'poster_metric_1_label' => 'Robot Industri',
                'poster_metric_1_value' => '6-DOF Articulated Arm',
                'poster_metric_2_label' => 'Platform Kontrol',
                'poster_metric_2_value' => 'PLC / HMI / SCADA',
                'features' => [
                    ['icon' => 'target', 'title' => 'Lengan Robot Industri 6-DOF', 'description' => 'Mendukung simulasi pick-and-place, welding path, dan integrasi gripper untuk validasi otomasi lini produksi.', 'tag' => '6-DOF Robotics'],
                    ['icon' => 'activity', 'title' => 'Stasiun PLC, HMI & SCADA', 'description' => 'Pengujian ladder logic, komunikasi Profinet/Modbus, serta antarmuka supervisi SCADA multi-stasiun.', 'tag' => 'Industrial PLC'],
                    ['icon' => 'zap', 'title' => 'Sistem Pneumatik & Sensorik', 'description' => 'Lengkap dengan aktuator elektropneumatik, sensor induktif/optik, dan konveyor mini uji integrasi.', 'tag' => 'Mechatronics'],
                    ['icon' => 'shield', 'title' => 'Pendampingan Instruktur Otomasi', 'description' => 'Didampingi teknisi dan asisten lab Teknik Elektro PNJ untuk kelancaran uji coba prototipe mitra.', 'tag' => 'Lab Support'],
                ],
                'specifications' => [
                    ['parameter' => 'Unit Robotika', 'value' => 'Industrial 6-Axis Robot Arm + Teach Pendant', 'method' => 'Repeatability ±0,05 mm'],
                    ['parameter' => 'Kontroler PLC', 'value' => 'Siemens / Omron / Mitsubishi PLC + HMI 7"–10"', 'method' => 'Standar IEC 61131-3'],
                    ['parameter' => 'Jaringan Industri', 'value' => 'PROFINET, Modbus TCP/RTU, OPC-UA, Ethernet/IP', 'method' => 'Uji Integrasi Multi-Device'],
                    ['parameter' => 'Modul Aktuator', 'value' => 'Elektropneumatik 6 Bar, Servo Drive, Inverter VFD', 'method' => 'Kompresor Kering Terfilter'],
                    ['parameter' => 'Keselamatan Area Robot', 'value' => 'Safety Fence, Light Curtain, Dual E-Stop', 'method' => 'Acuan ISO 10218'],
                    ['parameter' => 'Dukungan Sesi', 'value' => 'Teknisi Lab Elektro & Laporan Penggunaan Resmi', 'method' => 'Tarif Resmi BLU Per Jam'],
                ],
            ],

            'LAB-TIK-005' => [
                'cert_code' => 'HPC & AI CLUSTER PNJ',
                'hero_summary' => 'Fasilitas High-Performance Computing (HPC) dan GPU AI Workstation terisolasi untuk training Deep Learning, simulasi CFD/FEA, analitik big data, dan rendering 3D.',
                'poster_metric_1_label' => 'Akselerasi GPU',
                'poster_metric_1_value' => 'Multi-GPU Tensor Node',
                'poster_metric_2_label' => 'Lingkungan Kerja',
                'poster_metric_2_value' => 'Isolated Container',
                'features' => [
                    ['icon' => 'zap', 'title' => 'Klaster GPU Akselerasi AI', 'description' => 'Mempercepat pelatihan model Deep Learning, Computer Vision, dan LLM fine-tuning dalam lingkungan terdedikasi.', 'tag' => 'Tensor GPU'],
                    ['icon' => 'target', 'title' => 'Simulasi Rekayasa CFD & FEA', 'description' => 'Memori RAM besar dan CPU multi-core tinggi siap mengeksekusi simulasi aerodinamika dan analisis struktur.', 'tag' => 'HPC Compute'],
                    ['icon' => 'shield', 'title' => 'Lingkungan Data Terisolasi', 'description' => 'Setiap penyewa mendapat workspace terisolasi dengan kebijakan penghapusan data pasca-sesi yang ketat.', 'tag' => 'Data Privacy'],
                    ['icon' => 'activity', 'title' => 'Dukungan Admin HPC TIK', 'description' => 'Pendampingan konfigurasi environment CUDA, PyTorch, Docker, maupun solver numerik oleh admin lab.', 'tag' => 'Admin Ready'],
                ],
                'specifications' => [
                    ['parameter' => 'Arsitektur GPU', 'value' => 'Workstation Multi-GPU CUDA / Tensor Core (24–48 GB VRAM)', 'method' => 'Monitoring Utilitas GPU Real-Time'],
                    ['parameter' => 'Prosesor & Memori', 'value' => 'Dual-Socket / High-Core CPU + RAM ECC 128–256 GB', 'method' => 'Stabil untuk Beban Komputasi Berat'],
                    ['parameter' => 'Penyimpanan Cepat', 'value' => 'NVMe Enterprise RAID Array (Scratch Storage Cepat)', 'method' => 'Read/Write Kecepatan Tinggi'],
                    ['parameter' => 'Stack Perangkat Lunak', 'value' => 'Ubuntu LTS, Docker, CUDA, PyTorch, TensorFlow, OpenFOAM', 'method' => 'Pre-Configured Environment'],
                    ['parameter' => 'Keamanan & Isolasi', 'value' => 'Isolated Container / VLAN Lab + NDA Opsional', 'method' => 'Kebijakan Pembersihan Data Pasca-Sesi'],
                    ['parameter' => 'Ketersediaan Slot', 'value' => 'Reservasi Per Jam / Sesi Komputasi Terjadwal', 'method' => 'Tarif Resmi BLU UPA-RTPU PNJ'],
                ],
            ],

            'PRD-MSN-003' => [
                'cert_code' => 'LAB FABRIKASI BLU PNJ',
                'hero_summary' => 'Fasilitas fabrikasi logam terintegrasi meliputi pemesinan presisi, pengelasan TIG/MIG, sheet metal, dan inspeksi metrologi di Jurusan Teknik Mesin PNJ.',
                'poster_metric_1_label' => 'Proses Utama',
                'poster_metric_1_value' => 'Machining & TIG/MIG',
                'poster_metric_2_label' => 'Inspeksi Akhir',
                'poster_metric_2_value' => 'Metrologi Presisi',
                'features' => [
                    ['icon' => 'target', 'title' => 'Fabrikasi Logam Terintegrasi', 'description' => 'Satu atap untuk pemotongan, bending lembaran logam, pemesinan presisi, hingga perakitan rangka prototipe.', 'tag' => 'One-Stop Fab'],
                    ['icon' => 'zap', 'title' => 'Pengelasan TIG / MIG Standar', 'description' => 'Mendukung sambungan baja karbon, stainless steel, dan aluminium oleh teknisi pengelasan berpengalaman.', 'tag' => 'TIG / MIG'],
                    ['icon' => 'shield', 'title' => 'Inspeksi Dimensi Metrologi', 'description' => 'Pemeriksaan ketepatan dimensi dan kerataan hasil fabrikasi memakai instrumen metrologi terkalibrasi.', 'tag' => 'Quality Check'],
                    ['icon' => 'activity', 'title' => 'Cocok Prototipe & Batch Kecil', 'description' => 'Solusi efisien bagi industri dan startup perangkat keras sebelum masuk lini produksi massal.', 'tag' => 'Small Batch'],
                ],
                'specifications' => [
                    ['parameter' => 'Cakupan Layanan', 'value' => 'Pemesinan Presisi, Sheet Metal, Pengelasan TIG/MIG', 'method' => 'SOP Fabrikasi Teknik Mesin PNJ'],
                    ['parameter' => 'Kapasitas Material', 'value' => 'Baja Mild Steel, Stainless 304/316, Aluminium Plate', 'method' => 'Ketebalan Pelat 1 – 12 mm'],
                    ['parameter' => 'Instrumen Metrologi', 'value' => 'Height Gauge, Dial Indicator, Caliper Digital Terkalibrasi', 'method' => 'Toleransi Umum ISO 2768-m'],
                    ['parameter' => 'Peralatan Las', 'value' => 'Mesin Las Inverter TIG AC/DC & MIG/MAG Industri', 'method' => 'Inspeksi Visual Sambungan Las'],
                    ['parameter' => 'Pendampingan', 'value' => 'Teknisi Fabrikasi Bersertifikat & APD Lengkap', 'method' => 'Termasuk dalam Tarif Per Jam BLU'],
                    ['parameter' => 'Dokumen Resmi', 'value' => 'Berita Acara Penggunaan & Faktur BLU PNJ', 'method' => 'Skema Pembayaran 100% Lunas'],
                ],
            ],
        ];
    }

    /**
     * Fallback 4 fitur unggulan jika kode produk baru belum memiliki profil kustom.
     *
     * @return array<int, array<string, string>>
     */
    protected function defaultFeatures(Product $product, bool $isLab): array
    {
        if ($isLab) {
            return [
                ['icon' => 'shield', 'title' => 'Fasilitas Terkalibrasi BLU', 'description' => 'Peralatan laboratorium terawat dan terkalibrasi berkala sesuai standar layanan vokasi Politeknik Negeri Jakarta.', 'tag' => 'Standar BLU'],
                ['icon' => 'target', 'title' => 'Teknisi Pendamping Ahli', 'description' => 'Setiap sesi penggunaan didampingi teknisi atau laboran berpengalaman untuk menjaga akurasi dan keselamatan.', 'tag' => 'Certified Staff'],
                ['icon' => 'activity', 'title' => 'Jadwal Transparan Per Jam', 'description' => 'Tarif resmi per jam tanpa biaya tersembunyi lengkap dengan dokumen BAST dan faktur resmi BLU PNJ.', 'tag' => 'Transparan'],
                ['icon' => 'zap', 'title' => 'Dukungan Pengujian Industri', 'description' => 'Mendukung validasi prototipe, pengujian mutu bahan, dan proyek riset terapan lintas sektor industri.', 'tag' => 'Siap Industri'],
            ];
        }

        return [
            ['icon' => 'target', 'title' => 'Riset Terapan Siap Hilirisasi', 'description' => 'Dikembangkan dan diuji langsung di laboratorium Politeknik Negeri Jakarta untuk kebutuhan operasional nyata.', 'tag' => 'Tervalidasi'],
            ['icon' => 'shield', 'title' => 'Lolos Kurasi LPPM & RTPU', 'description' => 'Melewati tahap penjaminan mutu teknis, kelayakan operasional, dan standarisasi dokumentasi resmi PNJ.', 'tag' => 'Kurasi Resmi'],
            ['icon' => 'zap', 'title' => 'Dukungan Teknis & Implementasi', 'description' => 'Dilengkapi panduan pengoperasian, dokumentasi spesifikasi, serta dukungan teknis dari unit pengembang.', 'tag' => 'Full Support'],
            ['icon' => 'activity', 'title' => 'Administrasi B2B Lengkap', 'description' => 'Pengadaan resmi BLU PNJ dengan pembayaran 100% Lunas, faktur pajak PPN 11%, e-Meterai, dan BAST.', 'tag' => 'Resmi BLU'],
        ];
    }

    /**
     * Fallback sorotan aplikasi/manfaat 2x2.
     *
     * @return array<int, array<string, string>>
     */
    protected function defaultHighlights(bool $isLab): array
    {
        if ($isLab) {
            return [
                ['icon' => 'factory', 'title' => 'Pengujian & Validasi Industri', 'description' => 'Mendukung uji kelayakan produk, material, dan perangkat bagi perusahaan manufaktur maupun kontraktor.'],
                ['icon' => 'cog', 'title' => 'Pengembangan Prototipe Cepat', 'description' => 'Mempercepat iterasi desain dan fabrikasi prototipe tanpa investasi awal mesin skala besar.'],
                ['icon' => 'heart', 'title' => 'Kepatuhan K3 & Mutu', 'description' => 'Seluruh aktivitas mematuhi protokol keselamatan laboratorium dengan pengawasan teknisi bersertifikat.'],
                ['icon' => 'graduation', 'title' => 'Sinergi Akademisi & Praktisi', 'description' => 'Akses langsung ke ekosistem fasilitas terpadu dan keahlian terapan Politeknik Negeri Jakarta.'],
            ];
        }

        return [
            ['icon' => 'factory', 'title' => 'Efisiensi Operasional Industri', 'description' => 'Dirancang untuk meningkatkan produktivitas, akurasi proses, dan efisiensi biaya operasional perusahaan.'],
            ['icon' => 'cog', 'title' => 'Substitusi Teknologi Impor', 'description' => 'Solusi rekayasa dalam negeri dengan kemudahan perawatan dan ketersediaan dukungan teknis lokal.'],
            ['icon' => 'heart', 'title' => 'Kualitas Terukur & Andal', 'description' => 'Melalui proses pengujian fungsional dan kurasi teknis UPA-RTPU sebelum dikirim ke mitra.'],
            ['icon' => 'graduation', 'title' => 'Dukungan Dokumentasi Resmi', 'description' => 'Dilengkapi spesifikasi teknis, panduan penggunaan, serta dokumen serah terima BAST resmi BLU PNJ.'],
        ];
    }

    /**
     * Tentukan data Ketua Tim Riset / Penanggung Jawab Lab berdasarkan unit pengelola.
     *
     * @return array<string, string>
     */
    protected function resolveUnitResearcher(Product $product, bool $isLab): array
    {
        $unitCode = strtoupper((string) ($product->unit?->code ?? 'PNJ'));
        $roleLabel = $isLab ? 'PENANGGUNG JAWAB LAB' : 'KETUA TIM RISET';

        return match ($unitCode) {
            'MESIN' => [
                'role_label' => $roleLabel,
                'badge' => 'Terverifikasi PNJ',
                'initials' => 'HW',
                'name' => 'Dr. Hendra Wijaya, M.T.',
                'title' => 'Kepala Laboratorium Manufaktur Presisi — Teknik Mesin PNJ',
                'credentials' => '14 Paten Terdaftar & Hilirisasi Industri',
                'quote' => 'Setiap perangkat dan layanan workshop Teknik Mesin PNJ melalui uji akurasi ketat agar siap langsung dipakai pada kebutuhan industri mitra.',
                'email' => 'hendra.wijaya@mesin.pnj.ac.id',
                'location' => 'Gedung Workshop Teknik Mesin, Kampus PNJ Depok',
            ],
            'ELEKTRO' => [
                'role_label' => $roleLabel,
                'badge' => 'Terverifikasi PNJ',
                'initials' => 'AS',
                'name' => 'Dr. Ir. Agus Santoso, M.T.',
                'title' => 'Koordinator Riset Otomasi & IoT — Teknik Elektro PNJ',
                'credentials' => '11 HKI & Proyek Otomasi Industri',
                'quote' => 'Kami memastikan seluruh sistem elektronika, sensor, dan robotika telah tervalidasi keandalannya dengan dokumentasi integrasi yang jelas.',
                'email' => 'agus.santoso@elektro.pnj.ac.id',
                'location' => 'Gedung Laboratorium Teknik Elektro, Kampus PNJ Depok',
            ],
            'TIK' => [
                'role_label' => $roleLabel,
                'badge' => 'Terverifikasi PNJ',
                'initials' => 'RN',
                'name' => 'Dr. Rina Nurhayati, M.Kom.',
                'title' => 'Kepala Lab Rekayasa Cerdas & HPC — Jurusan TIK PNJ',
                'credentials' => '16 Hak Cipta Perangkat Lunak & AI',
                'quote' => 'Solusi Vision AI, analitik energi, dan infrastruktur HPC kami dirancang modular agar mudah diintegrasikan dengan sistem eksisting mitra.',
                'email' => 'rina.nurhayati@tik.pnj.ac.id',
                'location' => 'Gedung Jurusan TIK & Lab Terpadu, Kampus PNJ Depok',
            ],
            'SIPIL' => [
                'role_label' => $roleLabel,
                'badge' => 'Terverifikasi PNJ',
                'initials' => 'BP',
                'name' => 'Ir. Bambang Prasetyo, M.T.',
                'title' => 'Penanggung Jawab Lab Uji Material — Teknik Sipil PNJ',
                'credentials' => 'Sertifikasi Uji Material SNI & ASTM',
                'quote' => 'Pengujian material dilakukan dengan instrumen terkalibrasi dan pendampingan laboran berpengalaman untuk menghasilkan data uji yang akurat.',
                'email' => 'bambang.prasetyo@sipil.pnj.ac.id',
                'location' => 'Gedung Lab Uji Material Teknik Sipil, Kampus PNJ Depok',
            ],
            default => [
                'role_label' => $roleLabel,
                'badge' => 'Terverifikasi PNJ',
                'initials' => 'RP',
                'name' => 'Tim Kurasi & Hilirisasi UPA-RTPU PNJ',
                'title' => 'Unit Penunjang Akademik RTPU — Politeknik Negeri Jakarta',
                'credentials' => 'Terverifikasi LPPM & BLU PNJ',
                'quote' => 'UPA-RTPU PNJ menjembatani hasil riset terapan dan layanan laboratorium vokasi agar mudah diakses oleh mitra industri secara transparan.',
                'email' => 'rtpu@pnj.ac.id',
                'location' => 'Gedung Direktorat & UPA-RTPU, Kampus PNJ Depok',
            ],
        };
    }

    /**
     * Fallback tabel spesifikasi teknis jika kode produk baru belum terdaftar di profil.
     *
     * @param  array<string, mixed>  $meta
     * @return array<int, array<string, string>>
     */
    protected function defaultSpecifications(Product $product, array $meta, bool $isLab): array
    {
        return [
            ['parameter' => 'Kode Katalog Resmi', 'value' => $product->code, 'method' => 'Registri Produk UPA-RTPU PNJ'],
            ['parameter' => 'Unit Pengelola', 'value' => $product->unit?->name ?? 'UPA-RTPU PNJ', 'method' => 'Politeknik Negeri Jakarta'],
            ['parameter' => 'Kategori & Sub-Kategori', 'value' => ($product->category?->name ?? '-') . ' / ' . ($product->subCategory?->name ?? '-'), 'method' => 'Klasifikasi Katalog PNJ Prime'],
            ['parameter' => 'Tingkat Kesiapan Teknologi', 'value' => $meta['trl_label'] . ' (' . $meta['readiness_pct'] . '% Siap Hilirisasi)', 'method' => 'Asesmen LPPM & UPA-RTPU PNJ'],
            ['parameter' => $meta['stok_label'], 'value' => $product->stock . ' ' . $meta['satuan'], 'method' => 'Pembaruan Real-Time Portal BLU'],
            ['parameter' => 'Skema Transaksi & Estimasi', 'value' => '100% Lunas • ' . $meta['estimasi'], 'method' => 'VA Mandiri BLU / QRIS + BAST Resmi'],
        ];
    }
}
