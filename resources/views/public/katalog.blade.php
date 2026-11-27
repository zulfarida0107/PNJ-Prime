@extends('public.layouts.catalog')

@section('title', 'Katalog Riset & Inovasi')
@section('public_nav', ($filters['order_type'] ?? '') === 'lab_reservation' ? 'lab' : 'katalog')

@php
    $authUser = auth()->user();
    $isMitra = $authUser && $authUser->role === 'mitra';
    $hasActiveFilter = ! empty($filters['category_id'])
        || ! empty($filters['trl'])
        || ! empty($filters['order_type'])
        || ! empty($filters['q']);
@endphp

@section('content')
<div class="space-y-6">
    {{-- ===================================================================== --}}
    {{-- BREADCRUMB & PAGE HEADER (PERSIS MOCKUP)                              --}}
    {{-- ===================================================================== --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <nav class="mb-1.5 flex items-center gap-1.5 text-[11px] font-semibold tracking-wider uppercase text-slate-400" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-[#00877A] transition-colors">BERANDA</a>
                <svg width="12" height="12" class="h-3 w-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
                <span class="text-[#00877A]">KATALOG RISET &amp; INOVASI</span>
            </nav>
            <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">Katalog Riset &amp; Inovasi</h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                Eksplorasi hasil riset terapan unggulan Politeknik Negeri Jakarta siap hilirisasi dan kolaborasi industri.
            </p>
        </div>

        <button type="button"
                onclick="window.print()"
                class="no-print inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-[#00877A] hover:border-[#00877A]/40 hover:bg-[#E6F4F2]/50 transition-colors">
            <svg width="15" height="15" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Unduh Brosur Katalog</span>
        </button>
    </div>

    {{-- ===================================================================== --}}
    {{-- FILTER CARD 4 KOLOM (PERSIS MOCKUP)                                   --}}
    {{-- ===================================================================== --}}
    <section class="no-print rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
        <form id="public-catalog-filter-form" method="GET" action="{{ route('public.katalog') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Kolom 1: Kategori --}}
                <div>
                    <label for="public_category_id" class="mb-1.5 block text-[11px] font-semibold text-slate-600">
                        Kategori
                    </label>
                    <select id="public_category_id" name="category_id" data-auto-submit
                            class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-[#0F172A] outline-none transition focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kolom 2: Tingkat Kesiapan (TRL) --}}
                <div>
                    <label for="public_trl" class="mb-1.5 block text-[11px] font-semibold text-slate-600">
                        Tingkat Kesiapan (TRL)
                    </label>
                    <select id="public_trl" name="trl" data-auto-submit
                            class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-[#0F172A] outline-none transition focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <option value="">Semua Tingkat</option>
                        <option value="5" @selected((string) ($filters['trl'] ?? '') === '5')>TRL 5 — Validasi Lingkungan</option>
                        <option value="6" @selected((string) ($filters['trl'] ?? '') === '6')>TRL 6 — Demonstrasi Prototipe</option>
                        <option value="7" @selected((string) ($filters['trl'] ?? '') === '7')>TRL 7 — Siap Operasional</option>
                    </select>
                </div>

                {{-- Kolom 3: Kategori Pesanan --}}
                <div>
                    <label for="public_order_type" class="mb-1.5 block text-[11px] font-semibold text-slate-600">
                        Kategori Pesanan
                    </label>
                    <select id="public_order_type" name="order_type" data-auto-submit
                            class="h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-[#0F172A] outline-none transition focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <option value="">Semua Tipe Pesanan</option>
                        <option value="ready_stock" @selected(($filters['order_type'] ?? '') === 'ready_stock')>Ready Stock</option>
                        <option value="pre_order" @selected(($filters['order_type'] ?? '') === 'pre_order')>Pre-Order</option>
                        <option value="lab_reservation" @selected(($filters['order_type'] ?? '') === 'lab_reservation')>Reservasi Lab</option>
                    </select>
                </div>

                {{-- Kolom 4: Pencarian Cepat --}}
                <div>
                    <label for="public-filter-q" class="mb-1.5 block text-[11px] font-semibold text-slate-600">
                        Pencarian Cepat
                    </label>
                    <div class="relative">
                        <svg width="14" height="14"
                             class="pointer-events-none absolute top-1/2 left-3 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input type="text"
                               id="public-filter-q"
                               name="q"
                               value="{{ $filters['q'] ?? '' }}"
                               placeholder="Cari inovasi, unit, atau kode..."
                               class="h-10 w-full rounded-lg border border-slate-200 bg-white pr-3 pl-9 text-xs font-medium text-[#0F172A] outline-none transition placeholder:text-slate-400 focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    </div>
                </div>
            </div>

            {{-- Status Bar Bawah Filter --}}
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3 text-xs">
                <div class="flex items-center gap-2 text-slate-500">
                    <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
                    <span>
                        Menampilkan <strong id="public-result-count" class="tabular-nums text-[#0F172A]">{{ $products->count() }}</strong> hasil riset unggulan
                        <span class="mx-1.5 text-slate-300">|</span>
                        <span class="text-slate-400">Terverifikasi LPPM &amp; UPA-RTPU Politeknik Negeri Jakarta</span>
                    </span>
                </div>

                <a href="{{ route('public.katalog') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#00877A] hover:text-[#006E63] transition-colors {{ $hasActiveFilter ? '' : 'opacity-80' }}">
                    <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    <span>Reset Filter</span>
                </a>
            </div>
        </form>
    </section>

    {{-- ===================================================================== --}}
    {{-- GRID KARTU PRODUK 3 KOLOM (PERSIS MOCKUP)                             --}}
    {{-- ===================================================================== --}}
    @if ($products->isNotEmpty())
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($products as $product)
                @php
                    $meta = $productMetaMap[$product->id];
                    $imageExists = $product->image_path && file_exists(public_path('storage/' . $product->image_path));
                    $displayPrice = (float) ($product->price_b2b > 0 ? $product->price_b2b : $product->price_b2c);
                    $showPrice = (bool) ($product->show_price ?? true);
                    $searchIndex = strtolower(implode(' ', array_filter([
                        $product->name,
                        $product->code,
                        $product->description,
                        $product->unit?->name,
                        $product->category?->name,
                        $product->subCategory?->name,
                        $meta['trl_label'],
                        $meta['order_label'],
                    ])));
                @endphp

                <article data-public-product-card
                         data-search-text="{{ $searchIndex }}"
                         class="group flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">

                    {{-- Thumbnail Header --}}
                    <div class="relative h-44 w-full overflow-hidden border-b border-slate-100 bg-[#0F172A]">
                        @if ($imageExists)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="tech-grid-bg flex h-full w-full flex-col justify-between p-4 text-white">
                                <div class="h-6"></div>
                                <div class="flex items-center gap-3.5">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/10 border border-white/15 text-[#99D3CB]">
                                        @if ($product->order_type === 'lab_reservation')
                                            <svg width="24" height="24" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M9 3h6"></path>
                                                <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"></path>
                                                <path d="M7.5 14h9"></path>
                                            </svg>
                                        @elseif ($product->order_type === 'pre_order')
                                            <svg width="24" height="24" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                                <polyline points="2 17 12 22 22 17"></polyline>
                                                <polyline points="2 12 12 17 22 12"></polyline>
                                            </svg>
                                        @else
                                            <svg width="24" height="24" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                                                <rect x="9" y="9" width="6" height="6"></rect>
                                                <path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"></path>
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <p class="tabular-nums text-[10px] font-bold tracking-wider text-[#99D3CB] uppercase">{{ $product->code }}</p>
                                        <p class="mt-0.5 line-clamp-2 text-xs font-medium text-slate-200">
                                            {{ $product->subCategory?->name ?? 'Inovasi Riset Terapan PNJ' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-slate-400">
                                    <span>{{ $product->unit?->code ?? 'PNJ' }}</span>
                                    <span>Terverifikasi UPA-RTPU</span>
                                </div>
                            </div>
                        @endif

                        {{-- Floating Badges Pojok Kiri Atas & Kanan Atas (Persis Mockup) --}}
                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="tabular-nums shrink-0 rounded-md bg-[#00877A] px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                    {{ $meta['trl_label'] }}
                                </span>
                                <span class="truncate rounded-md bg-white/95 px-2 py-0.5 text-[10px] font-semibold text-[#0F172A] shadow-xs">
                                    {{ $product->category?->name ?? 'Riset' }}
                                </span>
                            </div>

                            <span class="shrink-0 inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-semibold shadow-xs {{ $meta['order_pill'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $meta['order_dot'] }}"></span>
                                {{ $meta['order_label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="flex flex-1 flex-col p-5">
                        {{-- Unit / Lab PNJ --}}
                        <div class="flex items-center gap-1.5 text-[11px] font-semibold text-[#00877A]">
                            <svg width="13" height="13" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 3h6"></path>
                                <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"></path>
                            </svg>
                            <span class="truncate">{{ $product->unit?->name ?? 'Unit Riset PNJ' }}</span>
                        </div>

                        {{-- Judul Produk --}}
                        <h2 class="mt-1.5 line-clamp-2 text-[15px] font-bold leading-snug text-[#0F172A] group-hover:text-[#00877A] transition-colors">
                            <a href="{{ route('public.katalog.detail', $product->id) }}">{{ $product->name }}</a>
                        </h2>

                        {{-- Deskripsi 2–3 baris --}}
                        <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-slate-500">
                            {{ $product->description }}
                        </p>

                        {{-- Blok Harga Kondisional (Mengikuti $product->show_price) --}}
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            @if ($showPrice)
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Estimasi Tarif</span>
                                    <span class="text-[10px] font-semibold text-[#00877A]">Tarif Resmi BLU</span>
                                </div>
                                <div class="mt-0.5 flex items-baseline gap-1">
                                    <span class="tabular-nums text-base font-extrabold tracking-tight text-[#0F172A]">
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400">/ {{ $meta['satuan'] }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Estimasi Tarif</span>
                                    <span class="inline-flex items-center rounded-full border border-[#00877A]/25 bg-[#E6F4F2] px-2 py-0.5 text-[10px] font-bold text-[#00877A]">
                                        Ajukan Penawaran
                                    </span>
                                </div>
                                <p class="mt-1 text-xs font-semibold text-slate-700">
                                    Harga disesuaikan saat penerbitan Invoice
                                </p>
                            @endif
                        </div>

                        {{-- Kotak Progress Bar Tingkat Kesiapan & Estimasi Waktu (Persis Mockup) --}}
                        <div class="mt-3.5 rounded-lg border border-slate-100 bg-[#F8FAFC] p-3 space-y-2">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-500">Tingkat Kesiapan:</span>
                                <span class="tabular-nums font-bold text-[#00877A]">{{ $meta['readiness_pct'] }}%</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                                <div class="h-full rounded-full bg-[#00877A]" style="width: {{ $meta['readiness_pct'] }}%;"></div>
                            </div>
                            <div class="flex items-center justify-between pt-0.5 text-[11px]">
                                <span class="text-slate-500">Estimasi Waktu:</span>
                                <span class="tabular-nums font-bold text-[#0F172A]">{{ $meta['estimasi'] }}</span>
                            </div>
                        </div>

                        {{-- Action Row (Persis Mockup + Guest Gate) --}}
                        <div class="mt-4 flex items-center gap-2 pt-1">
                            @if ($isMitra && Route::has('b2b.detail'))
                                <a href="{{ route('b2b.detail', $product->id) }}"
                                   class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-2.5 text-xs font-semibold text-white transition-colors">
                                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>{{ $meta['cta_label'] }}</span>
                                </a>
                            @elseif ($authUser)
                                <a href="{{ route('public.katalog.detail', $product->id) }}"
                                   class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-2.5 text-xs font-semibold text-white transition-colors">
                                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>{{ $meta['cta_label'] }}</span>
                                </a>
                            @else
                                <button type="button"
                                        data-guest-gate
                                        data-gate-title="Masuk atau Daftar untuk Melanjutkan Pemesanan"
                                        data-gate-product="{{ $product->name }}"
                                        data-gate-desc="Silakan masuk ke akun PNJ Prime atau daftarkan akun Mitra Industri baru untuk mengajukan {{ strtolower($meta['order_label']) }} produk riset ini."
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-2.5 text-xs font-semibold text-white transition-colors">
                                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>{{ $meta['cta_label'] }}</span>
                                </button>
                            @endif

                            <a href="{{ route('public.katalog.detail', $product->id) }}"
                               title="Lihat Detail Produk"
                               aria-label="Lihat Detail {{ $product->name }}"
                               class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-[#00877A] hover:text-[#00877A] transition-colors">
                                <svg width="16" height="16" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        {{-- Live Search Empty State --}}
        <div id="public-live-empty" class="hidden rounded-xl border border-slate-200 bg-white px-6 py-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                <svg width="22" height="22" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-[#0F172A]">Inovasi riset tidak ditemukan</h3>
            <p class="mt-1 text-xs text-slate-500">Tidak ada produk katalog yang cocok dengan kata kunci pencarian Anda.</p>
            <button type="button"
                    onclick="if (window.pnjApplyPublicSearch) window.pnjApplyPublicSearch('', true);"
                    class="mt-4 inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                Reset Pencarian
            </button>
        </div>
    @else
        {{-- Server-side Empty State --}}
        <section class="rounded-xl border border-slate-200 bg-white px-6 py-14 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                <svg width="22" height="22" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-[#0F172A]">Belum ada produk riset untuk filter tersebut</h3>
            <p class="mt-1 text-xs text-slate-500">Ubah kategori, tingkat kesiapan (TRL), atau tipe pesanan untuk melihat katalog tayang lainnya.</p>
            <a href="{{ route('public.katalog') }}"
               class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-2 text-xs font-semibold text-white transition-colors">
                Tampilkan Semua Katalog
            </a>
        </section>
    @endif

    {{-- ===================================================================== --}}
    {{-- PAGINATION BAR (PERSIS MOCKUP)                                        --}}
    {{-- ===================================================================== --}}
    <section class="no-print flex flex-wrap items-center justify-between gap-3 border-t border-slate-200/80 pt-4 text-xs text-slate-500">
        <p class="tabular-nums">
            @if ($products->total() > 0)
                Menampilkan <strong class="text-[#0F172A]">{{ $products->firstItem() }} - {{ $products->lastItem() }}</strong>
                dari <strong class="text-[#0F172A]">{{ $products->total() }}</strong> inovasi riset terdaftar
            @else
                Menampilkan <strong class="text-[#0F172A]">0</strong> dari <strong class="text-[#0F172A]">{{ $totalPublishedCount }}</strong> inovasi riset terdaftar
            @endif
        </p>

        <div class="flex items-center gap-1.5">
            @if ($products->onFirstPage())
                <span class="inline-flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-400">
                    &lsaquo; Sebelumnya
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}"
                   class="inline-flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] transition-colors">
                    &lsaquo; Sebelumnya
                </a>
            @endif

            @for ($page = 1; $page <= max(1, $products->lastPage()); $page++)
                @if ($page === $products->currentPage())
                    <span class="tabular-nums inline-flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A] text-xs font-bold text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $products->url($page) }}"
                       class="tabular-nums inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}"
                   class="inline-flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] transition-colors">
                    Selanjutnya &rsaquo;
                </a>
            @else
                <span class="inline-flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-400">
                    Selanjutnya &rsaquo;
                </span>
            @endif
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- BANNER KUSTOMISASI / QUOTATION BAWAH (PERSIS MOCKUP)                  --}}
    {{-- ===================================================================== --}}
    <section class="no-print rounded-xl border border-slate-200 bg-white p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#E6F4F2] text-[#00877A]">
                    <svg width="20" height="20" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Butuh Kustomisasi Spesifikasi atau Pesanan Skala Besar?</h3>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Tim PNJ Prime siap melayani penyesuaian spesifikasi produk inovasi (custom order) dan pembelian partai besar untuk kebutuhan industri Anda.
                    </p>
                </div>
            </div>

            @if ($isMitra && Route::has('b2b.katalog'))
                <a href="{{ route('b2b.katalog') }}"
                   class="shrink-0 rounded-lg border border-[#00877A]/35 bg-white hover:bg-[#E6F4F2]/60 px-4 py-2 text-xs font-semibold text-[#00877A] transition-colors">
                    Minta Penawaran (Quotation)
                </a>
            @else
                <button type="button"
                        data-guest-gate
                        data-gate-title="Ajukan Permintaan Penawaran (Quotation)"
                        data-gate-desc="Masuk atau daftar sebagai Mitra Industri B2B untuk mengajukan custom order, penawaran harga resmi, dan penerbitan faktur pajak BLU PNJ."
                        class="shrink-0 rounded-lg border border-[#00877A]/35 bg-white hover:bg-[#E6F4F2]/60 px-4 py-2 text-xs font-semibold text-[#00877A] transition-colors">
                    Minta Penawaran (Quotation)
                </button>
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const filterForm = document.getElementById('public-catalog-filter-form');
        if (!filterForm) return;

        filterForm.querySelectorAll('[data-auto-submit]').forEach(function (selectEl) {
            selectEl.addEventListener('change', function () {
                filterForm.submit();
            });
        });
    })();
</script>
@endpush
