@extends('mitra.layouts.app')

@section('title', 'Katalog Riset & Layanan B2B')
@section('nav', ($filters['order_type'] ?? '') === 'lab_reservation' ? 'lab' : 'katalog')
@section('search_placeholder', 'Cari nama produk, kode, unit pengelola, atau layanan lab...')

@php
    $orderTypeConfig = [
        'ready_stock' => [
            'label' => 'Ready Stock',
            'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
            'cta' => 'Pesan Sekarang',
            'estimasi' => 'Siap Kirim (1–2 Hari)',
            'satuan' => 'Unit',
            'stok_label' => 'Stok Tersedia',
        ],
        'pre_order' => [
            'label' => 'Pre-Order',
            'badge' => 'bg-sky-50 text-sky-700 border-sky-200',
            'dot' => 'bg-sky-500',
            'cta' => 'Ajukan Pre-Order',
            'estimasi' => 'Estimasi 7 Hari Kerja',
            'satuan' => 'Unit',
            'stok_label' => 'Kuota Produksi',
        ],
        'lab_reservation' => [
            'label' => 'Reservasi Lab',
            'badge' => 'bg-teal-50 text-[#007266] border-teal-200',
            'dot' => 'bg-[#00998a]',
            'cta' => 'Reservasi Slot Lab',
            'estimasi' => 'Per Sesi (4 Jam)',
            'satuan' => 'Sesi',
            'stok_label' => 'Slot Tersedia',
        ],
    ];

    $hasActiveFilter = ! empty($filters['unit_id'])
        || ! empty($filters['category_id'])
        || ! empty($filters['sub_category_id'])
        || ! empty($filters['order_type'])
        || ! empty($filters['q']);
@endphp

@section('content')
<div class="space-y-4">
    {{-- ===================================================================== --}}
    {{-- BREADCRUMB & PAGE HEADER                                              --}}
    {{-- ===================================================================== --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="mb-1 flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#00897b] transition-colors">Beranda</a>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
                <span class="font-semibold text-[#00897b]">Katalog Riset &amp; Inovasi B2B</span>
            </nav>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Katalog Produk &amp; Layanan Riset B2B</h1>
            <p class="mt-0.5 text-xs text-slate-500">
                Tarif komersial korporat resmi BLU Politeknik Negeri Jakarta dengan skema <strong class="text-slate-700">100% Lunas</strong> dan kelengkapan faktur pajak.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/80 bg-white px-3 py-1.5 text-xs font-medium text-slate-600">
                <span class="h-2 w-2 rounded-full bg-[#00998a]"></span>
                Tarif Aktif: <strong class="text-slate-900">Komersial B2B</strong>
            </span>
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- FILTER BAR DINAMIS (UNITS, CATEGORIES, SUB_CATEGORIES, ORDER_TYPE)    --}}
    {{-- ===================================================================== --}}
    <section class="rounded-lg border border-slate-200/80 bg-white p-4">
        <form id="b2b-catalog-filter-form" method="GET" action="{{ route('b2b.katalog') }}" class="space-y-3">
            <input type="hidden" name="q" id="b2b-filter-hidden-q" value="{{ $filters['q'] ?? '' }}">

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Filter Unit Pengelola --}}
                <div>
                    <label for="filter_unit_id" class="mb-1 block text-[11px] font-semibold text-slate-600">
                        Unit Pengelola / Jurusan
                    </label>
                    <select id="filter_unit_id" name="unit_id"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 text-xs font-medium text-slate-700 outline-none transition focus:border-[#00998a] focus:bg-white focus:ring-2 focus:ring-[#00998a]/15">
                        <option value="">Semua Unit Pengelola</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @selected((string) ($filters['unit_id'] ?? '') === (string) $unit->id)>
                                {{ $unit->name }} ({{ $unit->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Kategori --}}
                <div>
                    <label for="filter_category_id" class="mb-1 block text-[11px] font-semibold text-slate-600">
                        Kategori
                    </label>
                    <select id="filter_category_id" name="category_id"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 text-xs font-medium text-slate-700 outline-none transition focus:border-[#00998a] focus:bg-white focus:ring-2 focus:ring-[#00998a]/15">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Sub-Kategori --}}
                <div>
                    <label for="filter_sub_category_id" class="mb-1 block text-[11px] font-semibold text-slate-600">
                        Sub-Kategori
                    </label>
                    <select id="filter_sub_category_id" name="sub_category_id"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 text-xs font-medium text-slate-700 outline-none transition focus:border-[#00998a] focus:bg-white focus:ring-2 focus:ring-[#00998a]/15">
                        <option value="">Semua Sub-Kategori</option>
                        @foreach ($subCategories as $subCat)
                            <option value="{{ $subCat->id }}"
                                    data-category-id="{{ $subCat->category_id }}"
                                    @selected((string) ($filters['sub_category_id'] ?? '') === (string) $subCat->id)>
                                {{ $subCat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tipe Order --}}
                <div>
                    <label for="filter_order_type" class="mb-1 block text-[11px] font-semibold text-slate-600">
                        Tipe Order
                    </label>
                    <select id="filter_order_type" name="order_type"
                            class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 text-xs font-medium text-slate-700 outline-none transition focus:border-[#00998a] focus:bg-white focus:ring-2 focus:ring-[#00998a]/15">
                        <option value="">Semua Tipe Order</option>
                        <option value="ready_stock" @selected(($filters['order_type'] ?? '') === 'ready_stock')>Ready Stock</option>
                        <option value="pre_order" @selected(($filters['order_type'] ?? '') === 'pre_order')>Pre-Order</option>
                        <option value="lab_reservation" @selected(($filters['order_type'] ?? '') === 'lab_reservation')>Reservasi Lab</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-2.5 text-xs">
                <div class="flex items-center gap-2 text-slate-500">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>
                        Menampilkan <strong id="b2b-page-result-count" class="tabular-nums text-slate-800">{{ $products->count() }}</strong>
                        dari <span class="tabular-nums">{{ $totalPublishedCount }}</span> produk &amp; layanan tayang
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @if ($hasActiveFilter)
                        <a href="{{ route('b2b.katalog') }}"
                           class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 active:scale-95">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                            </svg>
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#00998a] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </section>

    {{-- ===================================================================== --}}
    {{-- GRID KATALOG PRODUK & LAYANAN B2B                                     --}}
    {{-- ===================================================================== --}}
    @if ($products->isNotEmpty())
        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($products as $product)
                @php
                    $meta = $orderTypeConfig[$product->order_type] ?? $orderTypeConfig['ready_stock'];
                    $imageExists = $product->image_path && file_exists(public_path('storage/' . $product->image_path));
                    $searchIndex = strtolower(implode(' ', array_filter([
                        $product->name,
                        $product->code,
                        $product->description,
                        $product->unit?->name,
                        $product->unit?->code,
                        $product->category?->name,
                        $product->subCategory?->name,
                        $meta['label'],
                    ])));
                @endphp
                <article data-search-item
                         data-search-text="{{ $searchIndex }}"
                         class="group flex flex-col overflow-hidden rounded-lg border border-slate-200/80 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    {{-- Visual / Thumbnail Header --}}
                    <div class="relative h-40 w-full overflow-hidden border-b border-slate-100 bg-slate-900">
                        @if ($imageExists)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full flex-col justify-between bg-gradient-to-br from-slate-800 via-slate-900 to-[#005a50] p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <span class="tabular-nums rounded bg-white/10 px-2 py-0.5 text-[10px] font-semibold tracking-wide text-teal-200">
                                        {{ $product->code }}
                                    </span>
                                    <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium text-slate-200">
                                        {{ $product->unit?->code ?? 'PNJ' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white/10 text-teal-300 ring-1 ring-white/15">
                                        @if ($product->order_type === 'lab_reservation')
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M9 3h6"/>
                                                <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"/>
                                                <path d="M7.5 14h9"/>
                                            </svg>
                                        @elseif ($product->order_type === 'pre_order')
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M12 2 2 7l10 5 10-5-10-5z"/>
                                                <path d="m2 17 10 5 10-5"/>
                                                <path d="m2 12 10 5 10-5"/>
                                            </svg>
                                        @else
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="4" y="4" width="16" height="16" rx="2"/>
                                                <rect x="9" y="9" width="6" height="6"/>
                                                <path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>
                                            </svg>
                                        @endif
                                    </span>
                                    <p class="line-clamp-2 text-xs font-medium text-slate-200/90">
                                        {{ $product->subCategory?->name ?? 'Inovasi Riset Terapan PNJ' }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Floating Badges --}}
                        <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between gap-2">
                            <span class="rounded-md bg-slate-900/80 px-2 py-0.5 text-[10px] font-semibold text-white backdrop-blur-xs">
                                {{ $product->category?->name ?? 'Produk' }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $meta['badge'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $meta['dot'] }}"></span>
                                {{ $meta['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="flex flex-1 flex-col p-4">
                        <div class="flex items-center justify-between gap-2 text-[11px] font-semibold text-[#00897b]">
                            <span class="truncate">{{ $product->unit?->name ?? 'Unit PNJ' }}</span>
                            <span class="tabular-nums shrink-0 text-[10px] font-medium text-slate-400">{{ $product->code }}</span>
                        </div>

                        <h2 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-slate-900 group-hover:text-[#00897b] transition-colors">
                            <a href="{{ route('b2b.detail', $product->id) }}">{{ $product->name }}</a>
                        </h2>

                        <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-slate-500">
                            {{ $product->description }}
                        </p>

                        {{-- Price B2B (Dinamis mengikuti show_price) --}}
                        <div class="mt-3.5 border-t border-slate-100 pt-3">
                            @if ($product->show_price ?? true)
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Tarif B2B</span>
                                    <span class="text-[10px] font-semibold text-[#00877A]">100% Lunas</span>
                                </div>
                                <div class="mt-0.5 flex items-baseline gap-1">
                                    <span class="tabular-nums text-lg font-extrabold tracking-tight text-slate-900">
                                        Rp {{ number_format($product->price_b2b, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400">/ {{ $meta['satuan'] }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Tarif B2B</span>
                                    <span class="inline-flex items-center rounded-full border border-teal-200 bg-[#E6F4F2] px-2 py-0.5 text-[10px] font-semibold text-[#00877A]">
                                        Ajukan Penawaran
                                    </span>
                                </div>
                                <p class="mt-1 text-xs font-semibold text-slate-700">
                                    Harga disesuaikan saat penerbitan Invoice
                                </p>
                            @endif
                        </div>

                        {{-- Specs Box --}}
                        <div class="mt-3 rounded-lg border border-slate-100 bg-slate-50/80 px-3 py-2 text-[11px] space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">{{ $meta['stok_label'] }}:</span>
                                <span class="tabular-nums font-semibold {{ $product->stock > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $product->stock }} {{ $meta['satuan'] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Estimasi:</span>
                                <span class="tabular-nums font-semibold text-slate-700">{{ $meta['estimasi'] }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="mt-3.5 flex items-center gap-2 pt-1">
                            <a href="{{ route('b2b.detail', $product->id) }}"
                               class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-[#00998a] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                                {{ $meta['cta'] }}
                            </a>
                            <a href="{{ route('b2b.detail', $product->id) }}"
                               title="Lihat Detail & Simulasi Pajak"
                               aria-label="Lihat Detail {{ $product->name }}"
                               class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-[#00897b] hover:text-[#00897b] active:scale-95">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        {{-- Live Search Empty State --}}
        <div id="b2b-live-search-empty" class="hidden rounded-lg border border-slate-200/80 bg-white px-6 py-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-slate-900">Produk atau layanan tidak ditemukan</h3>
            <p class="mt-1 text-xs text-slate-500">Tidak ada item katalog yang cocok dengan kata kunci pencarian Anda.</p>
            <button type="button"
                    onclick="if (window.b2bApplySearch) { document.getElementById('b2b-global-search').value = ''; window.b2bApplySearch('', true); }"
                    class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                Reset Pencarian
            </button>
        </div>
    @else
        {{-- Server-side Filter Empty State --}}
        <section class="rounded-lg border border-slate-200/80 bg-white px-6 py-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-slate-900">Belum ada produk tayang untuk filter tersebut</h3>
            <p class="mt-1 text-xs text-slate-500">Silakan ubah kombinasi unit pengelola, kategori, atau tipe order untuk melihat produk yang tersedia.</p>
            <a href="{{ route('b2b.katalog') }}"
               class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-[#00998a] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                Tampilkan Semua Katalog
            </a>
        </section>
    @endif

    {{-- ===================================================================== --}}
    {{-- INFO BANNER KESIAPAN PAJAK & PENGADAAN KOMERSIAL                      --}}
    {{-- ===================================================================== --}}
    <section class="rounded-lg border border-teal-200/70 bg-[#e6f7f5]/60 px-5 py-3.5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white border border-teal-200 text-[#00897b]">
                    <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-bold text-slate-900">Kepatuhan Pajak &amp; Administrasi Pengadaan Korporat</p>
                    <p class="text-[11px] text-slate-600">
                        Seluruh pengadaan B2B berlaku skema <strong>100% Lunas</strong> serta dilengkapi kesiapan dokumen <strong>PPN 11%</strong>, <strong>PPh 23</strong>, <strong>e-Meterai</strong>, dan <strong>BAST</strong> resmi BLU PNJ.
                    </p>
                </div>
            </div>
            <a href="{{ route('b2b.profil') }}"
               class="shrink-0 rounded-lg border border-[#00897b]/30 bg-white px-3 py-1.5 text-xs font-semibold text-[#00897b] transition hover:bg-white/80 active:scale-95">
                Cek NPWP &amp; NIB Perusahaan
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const categorySelect = document.getElementById('filter_category_id');
        const subCategorySelect = document.getElementById('filter_sub_category_id');
        const unitSelect = document.getElementById('filter_unit_id');
        const orderTypeSelect = document.getElementById('filter_order_type');
        const filterForm = document.getElementById('b2b-catalog-filter-form');
        const hiddenQ = document.getElementById('b2b-filter-hidden-q');
        const topSearch = document.getElementById('b2b-global-search');

        // Dynamic sub-category options based on selected category
        function syncSubCategories() {
            if (!categorySelect || !subCategorySelect) return;
            const selectedCat = categorySelect.value;
            const options = Array.from(subCategorySelect.querySelectorAll('option[data-category-id]'));

            options.forEach(function (opt) {
                const match = !selectedCat || opt.getAttribute('data-category-id') === selectedCat;
                opt.hidden = !match;
                opt.disabled = !match;
                if (!match && opt.selected) {
                    subCategorySelect.value = '';
                }
            });
        }

        if (categorySelect) {
            syncSubCategories();
            categorySelect.addEventListener('change', function () {
                syncSubCategories();
                if (hiddenQ && topSearch) hiddenQ.value = topSearch.value;
                if (filterForm) filterForm.submit();
            });
        }

        [unitSelect, subCategorySelect, orderTypeSelect].forEach(function (el) {
            if (!el) return;
            el.addEventListener('change', function () {
                if (hiddenQ && topSearch) hiddenQ.value = topSearch.value;
                if (filterForm) filterForm.submit();
            });
        });

        window.addEventListener('b2b:search', function (e) {
            if (hiddenQ && e.detail) {
                hiddenQ.value = e.detail.query || '';
            }
        });
    })();
</script>
@endpush
