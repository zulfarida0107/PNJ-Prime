@extends('public.layouts.catalog')

@section('title', $product->name . ' — ' . ($detail['is_lab'] ? 'Sewa Fasilitas Lab' : 'Katalog Riset Publik'))
@section('public_nav', $detail['is_lab'] ? 'lab' : 'katalog')
@section('sidebar_default_collapsed', '1')

@php
    $authUser = auth()->user();
    $isMitra = $authUser && $authUser->role === 'mitra';
    $imageUrl = $product->resolved_image_url;
    $imageExists = $imageUrl !== null;
    $displayPrice = (float) ($product->price_b2b > 0 ? $product->price_b2b : $product->price_b2c);
    $showPrice = (bool) ($product->show_price ?? true);
    $unitSuffix = $detail['is_lab'] ? 'Jam' : 'Unit';

    // Gabungkan poster & galeri untuk navigasi Lightbox interaktif tanpa broken image
    $lightboxItems = [];
    $posterLightboxIndex = null;
    if (! empty($detail['poster']['url'])) {
        $posterLightboxIndex = count($lightboxItems);
        $lightboxItems[] = [
            'url' => $detail['poster']['url'],
            'title' => $detail['poster']['has_dedicated_poster']
                ? 'Poster Produk & Blueprint — ' . $product->name
                : 'Poster Ringkas — ' . $product->name,
            'caption' => $detail['poster']['caption'],
            'tag' => $detail['poster']['badge'],
        ];
    }

    $galleryStartIndex = count($lightboxItems);
    foreach ($detail['gallery'] as $gItem) {
        $lightboxItems[] = [
            'url' => $gItem['url'],
            'title' => $gItem['title'],
            'caption' => $gItem['caption'],
            'tag' => $gItem['tag'],
        ];
    }

    $guestGateTitle = $detail['is_lab']
        ? 'Masuk atau Daftar untuk Reservasi Fasilitas Lab'
        : 'Masuk atau Daftar untuk Melanjutkan Pemesanan';
    $guestGateDesc = $detail['is_lab']
        ? 'Silakan masuk ke akun PNJ Prime atau daftarkan akun Mitra Industri baru untuk memilih jadwal sesi laboratorium dan mendapatkan tagihan resmi BLU (100% Lunas).'
        : 'Silakan masuk ke akun PNJ Prime atau daftarkan akun Mitra Industri baru untuk mengajukan pemesanan dan mendapatkan dokumen penawaran & invoice resmi BLU (100% Lunas).';
@endphp

@section('content')
<div class="space-y-5 print:space-y-3" data-detail-print-root>
    {{-- ===================================================================== --}}
    {{-- KOP BROSUR RESMI KHUSUS CETAK PDF (HIDDEN DI WEB, TAMPIL SAAT PRINT)  --}}
    {{-- ===================================================================== --}}
    <div class="hidden print:flex items-center justify-between border-b-2 border-[#00877A] pb-2.5" data-detail-print-section>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-[#00877A]">
                PNJ Prime &bull; UPA-RTPU Politeknik Negeri Jakarta
            </p>
            <h2 class="text-sm font-extrabold text-[#0F172A]">
                Brosur Spesifikasi {{ $detail['is_lab'] ? 'Fasilitas Laboratorium' : 'Produk Inovasi Riset' }}
            </h2>
            <p class="text-[10px] text-slate-500">
                Kampus Baru UI, Jl. Prof. Dr. G.A. Siwabessy, Depok &bull; rtpu@pnj.ac.id
            </p>
        </div>
        <div class="text-right text-[10px] text-slate-600 tabular-nums">
            <p class="font-bold text-[#0F172A]">Kode: {{ $product->code }}</p>
            <p>{{ $meta['trl_label'] }} &bull; {{ $meta['order_label'] }} &bull; 100% Lunas</p>
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- BREADCRUMB & TOMBOL KEMBALI KE KATALOG (WEB ONLY)                     --}}
    {{-- ===================================================================== --}}
    <div class="print:hidden no-print flex flex-wrap items-center justify-between gap-3">
        <nav class="flex items-center gap-1.5 text-[11px] font-semibold tracking-wider uppercase text-slate-400" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-[#00877A] transition-colors">BERANDA</a>
            <svg width="12" height="12" class="h-3 w-3 text-slate-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
            <a href="{{ $detail['back_url'] }}" class="hover:text-[#00877A] transition-colors">{{ $detail['breadcrumb_section_label'] }}</a>
            <svg width="12" height="12" class="h-3 w-3 text-slate-300 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
            <span class="tabular-nums text-[#00877A] truncate max-w-[220px]">{{ $product->code }}</span>
        </nav>

        <a href="{{ $detail['back_url'] }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95 transition-all">
            <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            <span>Kembali ke Katalog</span>
        </a>
    </div>

    {{-- ===================================================================== --}}
    {{-- 1. VIDEO YOUTUBE FULL-WIDTH PALING ATAS (OPSI B — REFERENSI RTPU PNJ) --}}
    {{-- ===================================================================== --}}
    <section id="section-video-produk"
             class="print:hidden no-print scroll-mt-24 overflow-hidden rounded-xl border border-slate-200/80 bg-slate-950 shadow-sm">
        {{-- Strip Header Tipis (Tanpa Duplikasi Judul Produk) --}}
        <div class="print:hidden flex flex-wrap items-center justify-between gap-2 border-b border-slate-800 bg-slate-900 px-4 py-2.5">
            <div class="flex flex-wrap items-center gap-2">
                <span class="print:hidden inline-flex items-center gap-1.5 rounded-full bg-[#00877A] px-2.5 py-0.5 text-[11px] font-bold text-white">
                    <svg width="11" height="11" class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <span>Video Demonstrasi Produk</span>
                </span>
                <span class="inline-flex items-center gap-1 rounded-full border border-white/15 bg-white/10 px-2.5 py-0.5 text-[11px] font-semibold text-slate-100">
                    {{ $detail['hero']['program_badge'] }}
                </span>
                <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-medium text-teal-300">
                    <svg width="13" height="13" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ $detail['hero']['verified_label'] }}</span>
                </span>
            </div>

            <a href="{{ $product->resolved_watch_video_url }}"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 rounded-md border border-slate-700 bg-slate-800 px-2.5 py-1 text-[11px] font-semibold text-slate-200 hover:border-teal-400 hover:text-teal-300 transition-colors">
                <svg width="12" height="12" class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
                <span>Buka di YouTube</span>
            </a>
        </div>

        {{-- Pemutar YouTube 16:9 Full-Width Proporsional 1366x768 --}}
        <div class="print:hidden aspect-video w-full max-h-[460px] bg-slate-950">
            <iframe
                src="{{ $product->resolved_embed_video_url }}"
                title="Video Demo {{ $product->name }}"
                class="h-full w-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
                loading="eager"></iframe>
        </div>
    </section>

    {{-- Foto Produk Utama Khusus Cetak PDF (Langsung di Bawah Kop Brosur, Tanpa Ruang Kosong) --}}
    @if ($imageExists)
        <div class="hidden print:block overflow-hidden rounded-lg border border-slate-200" data-detail-print-section>
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-44 w-full object-cover object-center">
        </div>
    @endif

    {{-- ===================================================================== --}}
    {{-- 2. CARD RINGKASAN PRODUK, HARGA (show_price), TRL & AKSI UTAMA        --}}
    {{-- ===================================================================== --}}
    <section class="rounded-lg border border-slate-200/80 bg-white p-5 sm:p-6 print:p-3.5" data-detail-print-section>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 print:grid-cols-12 print:gap-4">
            {{-- Kiri (7 Kolom): Judul Tunggal, Identitas, Kuota, Estimasi, TRL & Catatan Kurasi --}}
            <div class="flex flex-col justify-between space-y-4 lg:col-span-7 print:col-span-7 print:space-y-2.5">
                <div class="space-y-3 print:space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-bold {{ $meta['order_pill'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $meta['order_dot'] }}"></span>
                            <span>{{ $meta['order_label'] }}</span>
                        </span>
                        <span class="tabular-nums rounded-md bg-[#00877A] px-2.5 py-0.5 text-[11px] font-bold text-white">
                            {{ $meta['trl_label'] }}
                        </span>
                        <span class="tabular-nums rounded-md border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600">
                            Kode: {{ $product->code }}
                        </span>
                        <span class="tabular-nums rounded-md border border-teal-200 bg-teal-50/70 px-2.5 py-0.5 text-[11px] font-semibold text-[#006E63]">
                            {{ $detail['hero']['cert_badge'] }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#00877A]">
                            {{ $detail['hero']['eyebrow'] }}
                        </p>
                        <h1 class="mt-1 text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A] leading-snug print:text-lg">
                            {{ $product->name }}
                        </h1>
                        <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600">
                            <span class="inline-flex items-center gap-1.5 font-semibold text-[#0F172A]">
                                <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V7l8-4v18"></path>
                                    <path d="M19 21V11l-6-4"></path>
                                </svg>
                                <span>Unit Pengelola: {{ $product->unit?->name ?? 'UPA-RTPU PNJ' }}</span>
                            </span>
                            <span class="text-slate-300" aria-hidden="true">&bull;</span>
                            <span>{{ $product->category?->name }} / {{ $product->subCategory?->name }}</span>
                        </div>
                    </div>

                    {{-- Strip Info Compact: Kuota Produksi / Kapasitas & Estimasi Waktu --}}
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 print:grid-cols-3 rounded-lg border border-slate-200/80 bg-[#F8FAFC] p-3.5 print:p-2.5 text-xs">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">{{ $meta['stok_label'] }}</p>
                            <p class="mt-1 tabular-nums font-bold text-emerald-700">{{ $product->stock }} {{ $meta['satuan'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Estimasi Waktu</p>
                            <p class="mt-1 tabular-nums font-bold text-[#0F172A]">{{ $meta['estimasi'] }}</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1 print:col-span-1">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Skema Transaksi</p>
                            <p class="mt-1 font-bold text-[#00877A]">100% Lunas BLU</p>
                        </div>
                    </div>

                    {{-- Progress Bar Tingkat Kesiapan Teknologi (TRL) --}}
                    <div class="rounded-lg border border-slate-200/80 bg-white p-3.5 print:p-2.5 space-y-2 print:space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-600">
                                Tingkat Kesiapan Teknologi (<span class="tabular-nums">{{ $meta['trl_label'] }}</span>)
                            </span>
                            <span class="tabular-nums font-bold text-[#00877A]">
                                {{ $meta['readiness_pct'] }}% Siap Hilirisasi
                            </span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-[#00877A]" style="width: {{ $meta['readiness_pct'] }}%;"></div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Kurasi Teknis UPA-RTPU PNJ --}}
                @if ($product->curation_notes)
                    <div class="flex items-start gap-3 rounded-lg border border-[#00877A]/20 bg-[#E6F4F2]/60 p-3.5 print:p-2.5 text-xs">
                        <svg width="16" height="16" class="mt-0.5 h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <div>
                            <p class="font-bold text-[#006E63]">Catatan Kurasi Teknis UPA-RTPU PNJ</p>
                            <p class="mt-0.5 leading-relaxed text-slate-600">{{ $product->curation_notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Kanan (5 Kolom): Panel Tarif/Harga (show_price) & Tombol Aksi --}}
            <div class="lg:col-span-5 print:col-span-5">
                <div class="flex h-full flex-col justify-between rounded-lg border border-slate-200/80 bg-slate-50 p-5 print:p-3.5 space-y-4 print:space-y-2.5">
                    <div class="space-y-4 print:space-y-2.5">
                        <div class="border-b border-slate-200/80 pb-4 print:pb-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    {{ $detail['is_lab'] ? 'Tarif Sewa Resmi BLU' : 'Estimasi Harga Resmi BLU' }}
                                </span>
                                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">
                                    100% Lunas
                                </span>
                            </div>

                            @if ($showPrice)
                                <div class="mt-2.5 flex items-baseline gap-1.5">
                                    <span class="tabular-nums text-2xl lg:text-3xl print:text-xl font-bold tracking-tight text-slate-900">
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-500">/ {{ $unitSuffix }}</span>
                                </div>
                                <p class="mt-1 text-[11px] text-slate-500">
                                    Tarif resmi komersial BLU Politeknik Negeri Jakarta (belum termasuk PPN 11%).
                                </p>
                            @else
                                <div class="mt-3 rounded-lg border border-[#00877A]/25 bg-[#E6F4F2]/80 p-3.5 print:p-2.5">
                                    <span class="inline-flex items-center rounded-md bg-[#00877A] px-2 py-0.5 text-[10px] font-bold text-white">
                                        Penawaran Resmi BLU
                                    </span>
                                    <p class="mt-2 text-sm font-extrabold text-[#0F172A]">
                                        Hubungi UPA-RTPU untuk Penawaran Resmi
                                    </p>
                                    <p class="mt-1 text-[11px] leading-relaxed text-slate-600">
                                        Nominal penawaran ditetapkan resmi oleh Admin UPA-RTPU PNJ sesuai spesifikasi teknis, kuantitas, dan kelengkapan administrasi BLU.
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- 3 Poin Jaminan Pengadaan Resmi --}}
                        <ul class="space-y-2 print:space-y-1 text-xs text-slate-600">
                            <li class="flex items-start gap-2">
                                <svg width="15" height="15" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Pembayaran <strong class="text-[#0F172A]">100% Lunas</strong> via VA Mandiri BLU / QRIS</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg width="15" height="15" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong class="text-[#0F172A]">PPN 11%</strong>, e-Meterai &amp; <strong class="text-[#0F172A]">BAST</strong> resmi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg width="15" height="15" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Terverifikasi LPPM &amp; UPA-RTPU PNJ</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Tombol Aksi Utama & Sekunder (WEB ONLY) --}}
                    <div class="space-y-2.5 pt-1 print:hidden no-print">
                        @if ($isMitra && Route::has('b2b.detail'))
                            <a href="{{ route('b2b.detail', $product->id) }}"
                               class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-3 text-xs font-bold text-white active:scale-95 transition-all">
                                <svg width="15" height="15" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>{{ $meta['cta_label'] }} (Portal Mitra)</span>
                            </a>
                        @elseif ($authUser)
                            <a href="{{ $dashboardUrl }}"
                               class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-3 text-xs font-bold text-white active:scale-95 transition-all">
                                <svg width="15" height="15" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                </svg>
                                <span>Buka Dashboard untuk Melanjutkan</span>
                            </a>
                        @else
                            <button type="button"
                                    data-guest-gate
                                    data-gate-title="{{ $guestGateTitle }}"
                                    data-gate-product="{{ $product->name }}"
                                    data-gate-desc="{{ $guestGateDesc }}"
                                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-3 text-xs font-bold text-white active:scale-95 transition-all">
                                <svg width="15" height="15" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>{{ $meta['cta_label'] }}</span>
                            </button>
                        @endif

                        {{-- Baris Tombol Sekunder: Brosur PDF & Salin Link --}}
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button"
                                    onclick="window.print()"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95 transition-all">
                                <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <span>Brosur PDF</span>
                            </button>

                            <button type="button"
                                    id="btn-copy-product-link"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95 transition-all">
                                <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                </svg>
                                <span id="copy-link-label">Salin Link</span>
                            </button>
                        </div>

                        @guest
                            <p class="pt-0.5 text-center text-[11px] leading-relaxed text-slate-500">
                                Pengunjung umum wajib masuk atau mendaftar sebagai Mitra sebelum melanjutkan transaksi.
                            </p>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- BARIS INFO VIDEO RINGKAS KHUSUS CETAK PDF (1 BARIS RAPI)              --}}
    {{-- ===================================================================== --}}
    <div class="hidden print:flex items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs text-slate-700" data-detail-print-section>
        <span class="font-bold text-[#0F172A] shrink-0">Tautan Video Demonstrasi:</span>
        <a href="{{ $product->resolved_watch_video_url }}" class="text-[#00877A] underline break-all text-right">{{ $product->resolved_watch_video_url }}</a>
    </div>

    {{-- ===================================================================== --}}
    {{-- 3. BLOK 2 KOLOM: POSTER PRODUK & BLUEPRINT + GALERI & HASIL UJI       --}}
    {{-- ===================================================================== --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-12 print:hidden no-print">
        {{-- Kolom Kiri (5 Kolom): Poster Produk & Blueprint --}}
        <div class="flex flex-col justify-between rounded-lg border border-slate-200/80 bg-white p-5 lg:col-span-5">
            <div>
                <div class="mb-4 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#0F172A]">
                            Poster Produk &amp; Blueprint
                        </h2>
                    </div>
                    <span class="rounded-full bg-[#E6F4F2] px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">
                        {{ $detail['poster']['badge'] }}
                    </span>
                </div>

                @if ($detail['poster']['has_dedicated_poster'] && ! empty($detail['poster']['url']))
                    {{-- Poster Resolusi HD Dedicated (Khusus Mesin CNC 5-Axis) --}}
                    <button type="button"
                            data-lightbox-open="{{ $posterLightboxIndex }}"
                            class="group relative block w-full overflow-hidden rounded-lg border border-slate-200 bg-slate-950 text-left focus:outline-none focus:ring-2 focus:ring-[#00877A]">
                        <img src="{{ $detail['poster']['url'] }}"
                             alt="{{ $detail['poster']['caption'] }}"
                             loading="lazy"
                             class="mx-auto max-h-[410px] w-full object-contain transition-transform duration-300 group-hover:scale-[1.02]">
                        <span class="absolute right-3 bottom-3 inline-flex items-center gap-1.5 rounded-lg bg-slate-900/85 px-2.5 py-1.5 text-[11px] font-semibold text-white">
                            <svg width="13" height="13" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                            <span>Klik untuk Zoom</span>
                        </span>
                    </button>
                @else
                    {{-- Fallback Poster Visual Ringkas untuk 11 Produk & Lab Non-CNC --}}
                    <button type="button"
                            @if ($posterLightboxIndex !== null) data-lightbox-open="{{ $posterLightboxIndex }}" @endif
                            class="group tech-grid-bg relative flex w-full flex-col justify-between overflow-hidden rounded-lg border border-slate-800 p-4 text-left text-white focus:outline-none focus:ring-2 focus:ring-[#00877A]">
                        <div class="flex items-center justify-between gap-2 text-[10px] font-bold uppercase tracking-wider">
                            <span class="text-teal-300">PNJ PRIME &bull; <span class="tabular-nums">{{ $product->code }}</span></span>
                            <span class="tabular-nums rounded bg-[#00877A] px-2 py-0.5 text-white">{{ $meta['trl_label'] }}</span>
                        </div>

                        <h3 class="mt-2 text-sm font-extrabold leading-snug text-white">
                            {{ $product->name }}
                        </h3>

                        @if (! empty($detail['poster']['url']))
                            <div class="relative mt-3 h-48 w-full overflow-hidden rounded-md border border-white/10 bg-slate-950">
                                <img src="{{ $detail['poster']['url'] }}"
                                     alt="{{ $detail['poster']['caption'] }}"
                                     loading="lazy"
                                     class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
                            </div>
                        @endif

                        <div class="mt-3 grid grid-cols-2 gap-2.5 text-[11px]">
                            <div class="rounded-md border border-white/10 bg-white/5 p-2.5">
                                <p class="text-[10px] text-slate-400">{{ $detail['poster']['metric_1_label'] }}</p>
                                <p class="mt-0.5 tabular-nums font-bold text-white">{{ $detail['poster']['metric_1_value'] }}</p>
                            </div>
                            <div class="rounded-md border border-white/10 bg-white/5 p-2.5">
                                <p class="text-[10px] text-slate-400">{{ $detail['poster']['metric_2_label'] }}</p>
                                <p class="mt-0.5 tabular-nums font-bold text-teal-300">{{ $detail['poster']['metric_2_value'] }}</p>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-between border-t border-white/10 pt-2.5 text-[10px] text-slate-300">
                            <span class="truncate">{{ $product->unit?->name ?? 'UPA-RTPU PNJ' }}</span>
                            <span class="shrink-0 font-semibold text-teal-300 group-hover:underline">Perbesar &rarr;</span>
                        </div>
                    </button>
                @endif
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2.5">
                <button type="button"
                        @if ($posterLightboxIndex !== null) data-lightbox-open="{{ $posterLightboxIndex }}" @endif
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95 transition-all">
                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <polyline points="9 21 3 21 3 15"></polyline>
                        <line x1="21" y1="3" x2="14" y2="10"></line>
                        <line x1="3" y1="21" x2="10" y2="14"></line>
                    </svg>
                    <span>Perbesar Poster</span>
                </button>

                @if (! empty($detail['poster']['download_url']))
                    <a href="{{ $detail['poster']['download_url'] }}"
                       download="{{ $detail['poster']['download_name'] }}"
                       class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-[#00877A]/30 bg-[#E6F4F2]/70 px-3 py-2 text-xs font-semibold text-[#00877A] hover:bg-[#00877A] hover:text-white active:scale-95 transition-all">
                        <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Unduh Poster</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan (7 Kolom): Galeri Produk & Hasil Uji Komponen --}}
        <div class="flex flex-col justify-between rounded-lg border border-slate-200/80 bg-white p-5 lg:col-span-7">
            <div>
                <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-[#0F172A]">
                            Galeri Produk &amp; Hasil Uji Komponen
                        </h2>
                    </div>
                    <span class="tabular-nums text-[11px] font-medium text-slate-500">
                        {{ count($detail['gallery']) }} Dokumentasi &bull; Klik foto untuk memperbesar
                    </span>
                </div>

                @if (count($detail['gallery']) > 0)
                    @php $primaryPhoto = $detail['gallery'][0]; @endphp
                    <div class="space-y-3.5">
                        {{-- Foto Sorotan Utama (Baris Atas) --}}
                        <button type="button"
                                data-lightbox-open="{{ $galleryStartIndex }}"
                                class="group relative block h-60 sm:h-64 w-full overflow-hidden rounded-lg border border-slate-200 bg-slate-900 text-left focus:outline-none focus:ring-2 focus:ring-[#00877A]">
                            <img src="{{ $primaryPhoto['url'] }}"
                                 alt="{{ $primaryPhoto['title'] }}"
                                 loading="lazy"
                                 class="h-full w-full object-cover {{ $primaryPhoto['object_position'] }} transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-x-0 bottom-0 bg-slate-950/75 px-4 py-3 text-white">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <span class="inline-block rounded bg-[#00877A] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">
                                            {{ $primaryPhoto['tag'] }}
                                        </span>
                                        <p class="mt-1 text-xs sm:text-sm font-bold text-white leading-snug">
                                            {{ $primaryPhoto['title'] }}
                                        </p>
                                    </div>
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/15 text-white group-hover:bg-[#00877A] transition-colors">
                                        <svg width="15" height="15" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            <line x1="11" y1="8" x2="11" y2="14"></line>
                                            <line x1="8" y1="11" x2="14" y2="11"></line>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </button>

                        {{-- Grid Foto Pendukung / Hasil Uji (Baris Bawah) --}}
                        @if (count($detail['gallery']) > 1)
                            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                                @foreach (array_slice($detail['gallery'], 1) as $idx => $gPhoto)
                                    <button type="button"
                                            data-lightbox-open="{{ $galleryStartIndex + $idx + 1 }}"
                                            class="group relative block h-40 w-full overflow-hidden rounded-lg border border-slate-200 bg-slate-900 text-left focus:outline-none focus:ring-2 focus:ring-[#00877A]">
                                        <img src="{{ $gPhoto['url'] }}"
                                             alt="{{ $gPhoto['title'] }}"
                                             loading="lazy"
                                             class="h-full w-full object-cover {{ $gPhoto['object_position'] }} transition-transform duration-300 group-hover:scale-105">
                                        <div class="absolute inset-x-0 bottom-0 bg-slate-950/75 px-3 py-2.5 text-white">
                                            <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-teal-300">
                                                {{ $gPhoto['tag'] }}
                                            </span>
                                            <p class="mt-0.5 text-xs font-bold text-white line-clamp-1">
                                                {{ $gPhoto['title'] }}
                                            </p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="mt-4 flex items-center justify-between rounded-lg border border-slate-200/80 bg-[#F8FAFC] px-3.5 py-2.5 text-[11px] text-slate-600">
                <span>Seluruh dokumentasi foto telah diverifikasi oleh unit pengelola {{ $product->unit?->name }}.</span>
                <span class="shrink-0 font-semibold text-[#00877A]">Dokumentasi Resmi PNJ</span>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 4. FITUR UNGGULAN INOVASI / KEUNGGULAN FASILITAS LAB (GRID 4 CARD)    --}}
    {{-- ===================================================================== --}}
    <section class="space-y-3.5 print:space-y-2" data-detail-print-section>
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#00877A]">
                    {{ $detail['features_eyebrow'] }}
                </p>
                <h2 class="mt-0.5 text-lg font-extrabold tracking-tight text-[#0F172A] print:text-sm">
                    {{ $detail['features_heading'] }}
                </h2>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#E6F4F2] px-3 py-1 text-[11px] font-bold text-[#00877A]">
                <svg width="13" height="13" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ $detail['features_badge'] }}</span>
            </span>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 print:grid-cols-2 print:gap-2.5">
            @foreach ($detail['features'] as $feature)
                <article class="flex flex-col justify-between rounded-lg border border-slate-200/80 bg-white p-5 print:p-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div>
                        <span class="flex h-10 w-10 print:h-7 print:w-7 items-center justify-center rounded-lg bg-[#E6F4F2] text-[#00877A]">
                            @switch($feature['icon'])
                                @case('target')
                                    <svg width="20" height="20" class="h-5 w-5 print:h-4 print:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <circle cx="12" cy="12" r="6"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                    </svg>
                                    @break
                                @case('activity')
                                    <svg width="20" height="20" class="h-5 w-5 print:h-4 print:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                    @break
                                @case('zap')
                                    <svg width="20" height="20" class="h-5 w-5 print:h-4 print:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                    </svg>
                                    @break
                                @default
                                    <svg width="20" height="20" class="h-5 w-5 print:h-4 print:w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        <polyline points="9 12 11 14 15 10"></polyline>
                                    </svg>
                            @endswitch
                        </span>
                        <h3 class="mt-3.5 print:mt-2 text-sm print:text-xs font-bold text-[#0F172A] leading-snug">
                            {{ $feature['title'] }}
                        </h3>
                        <p class="mt-1.5 print:mt-1 text-xs print:text-[11px] leading-relaxed text-slate-600">
                            {{ $feature['description'] }}
                        </p>
                    </div>
                    <div class="mt-3.5 print:mt-2 border-t border-slate-100 pt-2.5 print:pt-1.5 text-[11px] font-bold text-[#00877A]">
                        {{ $feature['tag'] }}
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 5. BLOK 2 KOLOM: DESKRIPSI LENGKAP (8) + KETUA TIM & LEGALITAS (4)    --}}
    {{-- ===================================================================== --}}
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-12 print:grid-cols-12 print:gap-3.5">
        {{-- Kiri (8 Kolom): Deskripsi Lengkap Produk & Konteks Industri --}}
        <div class="rounded-lg border border-slate-200/80 bg-white p-5 sm:p-6 print:p-3.5 lg:col-span-8 print:col-span-7 space-y-5 print:space-y-3">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3.5 print:pb-2">
                <span class="flex h-8 w-8 print:h-6 print:w-6 shrink-0 items-center justify-center rounded-lg bg-[#E6F4F2] text-[#00877A]">
                    <svg width="16" height="16" class="h-4 w-4 print:h-3.5 print:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </span>
                <h2 class="text-base print:text-sm font-extrabold text-[#0F172A]">
                    {{ $detail['description_heading'] }}
                </h2>
            </div>

            <div class="space-y-3 print:space-y-2 text-sm print:text-xs leading-relaxed text-slate-600">
                <p>{{ $product->description }}</p>
                <p>{{ $detail['industry_context'] }}</p>
            </div>

            {{-- Grid 2x2 Kotak Sorotan Manfaat / Aplikasi --}}
            <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 print:grid-cols-2 print:gap-2">
                @foreach ($detail['highlights'] as $hl)
                    <div class="rounded-lg border border-slate-200/80 bg-slate-50 p-3.5 print:p-2.5" data-detail-print-section>
                        <div class="flex items-start gap-2.5">
                            <span class="mt-0.5 flex h-7 w-7 print:h-6 print:w-6 shrink-0 items-center justify-center rounded-md bg-white border border-slate-200 text-[#00877A]">
                                @switch($hl['icon'])
                                    @case('factory')
                                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                                        </svg>
                                        @break
                                    @case('heart')
                                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        </svg>
                                        @break
                                    @case('cog')
                                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                        </svg>
                                        @break
                                    @default
                                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                        </svg>
                                @endswitch
                            </span>
                            <div>
                                <h3 class="text-xs font-bold text-[#0F172A]">{{ $hl['title'] }}</h3>
                                <p class="mt-1 text-xs print:text-[11px] leading-relaxed text-slate-600">{{ $hl['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Banner Info Teal Lembut (Super Tax Deduction / Standar Operasional Lab) --}}
            <div class="flex items-start gap-3 rounded-lg border border-teal-200/80 bg-teal-50/70 p-4 print:p-2.5" data-detail-print-section>
                <span class="mt-0.5 flex h-7 w-7 print:h-6 print:w-6 shrink-0 items-center justify-center rounded-md bg-[#00877A] text-white">
                    <svg width="15" height="15" class="h-4 w-4 print:h-3.5 print:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <polyline points="9 12 11 14 15 10"></polyline>
                    </svg>
                </span>
                <div>
                    <h3 class="text-xs font-extrabold text-[#006E63]">{{ $detail['info_banner']['title'] }}</h3>
                    <p class="mt-1 text-xs print:text-[11px] leading-relaxed text-slate-700">{{ $detail['info_banner']['body'] }}</p>
                </div>
            </div>
        </div>

        {{-- Kanan (4 Kolom): Ketua Tim Riset & Legalitas Dokumen (2 Card Bertumpuk) --}}
        <div class="space-y-5 print:space-y-3 lg:col-span-4 print:col-span-5">
            {{-- Card 1: Ketua Tim Riset / Penanggung Jawab Lab --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 print:p-3.5 space-y-4 print:space-y-2.5" data-detail-print-section>
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        {{ $detail['researcher']['role_label'] }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-[#E6F4F2] px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">
                        <svg width="11" height="11" class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>{{ $detail['researcher']['badge'] }}</span>
                    </span>
                </div>

                <div class="flex items-start gap-3">
                    <div class="flex h-12 w-12 print:h-10 print:w-10 shrink-0 items-center justify-center rounded-lg bg-[#0F172A] text-sm print:text-xs font-extrabold text-white">
                        {{ $detail['researcher']['initials'] }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm print:text-xs font-extrabold text-[#0F172A] leading-snug">
                            {{ $detail['researcher']['name'] }}
                        </h3>
                        <p class="mt-0.5 text-[11px] leading-snug text-slate-500">
                            {{ $detail['researcher']['title'] }}
                        </p>
                        <p class="mt-1.5 inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-700">
                            {{ $detail['researcher']['credentials'] }}
                        </p>
                    </div>
                </div>

                <blockquote class="rounded-lg border border-slate-200/80 bg-slate-50 p-3 print:p-2 text-xs print:text-[11px] italic leading-relaxed text-slate-600">
                    &ldquo;{{ $detail['researcher']['quote'] }}&rdquo;
                </blockquote>

                <div class="space-y-2 print:space-y-1 border-t border-slate-100 pt-3 print:pt-2 text-xs print:text-[11px] text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span class="truncate font-medium text-[#0F172A]">{{ $detail['researcher']['email'] }}</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg width="14" height="14" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="leading-snug">{{ $detail['researcher']['location'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Legalitas & Dokumen Riset (Statis Bersih saat Cetak PDF) --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 print:p-3.5 space-y-3.5 print:space-y-2" data-detail-print-section>
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#0F172A]">
                        Legalitas &amp; Dokumen Riset
                    </h3>
                    <svg width="15" height="15" class="h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>

                <div class="space-y-2.5 print:space-y-1.5">
                    @foreach ($detail['legal_documents'] as $doc)
                        @if ($doc['action_type'] === 'print')
                            {{-- Baris Brosur PDF disembunyikan saat print agar tidak tercetak di dalam brosur --}}
                            <button type="button"
                                    onclick="window.print()"
                                    class="print:hidden no-print flex w-full items-center justify-between gap-3 rounded-lg border border-slate-200/80 bg-slate-50 px-3.5 py-2.5 text-left hover:border-[#00877A] hover:bg-[#E6F4F2]/40 active:scale-[0.99] transition-all">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white border border-slate-200 text-[#00877A]">
                                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-bold text-[#0F172A]">{{ $doc['title'] }}</p>
                                        <p class="truncate text-[11px] text-slate-500">{{ $doc['subtitle'] }}</p>
                                    </div>
                                </div>
                                <svg width="14" height="14" class="print:hidden h-3.5 w-3.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                            </button>
                        @else
                            {{-- Item Legalitas: Tombol interaktif di web, daftar statis terverifikasi saat print --}}
                            <button type="button"
                                    data-doc-preview
                                    data-doc-title="{{ $doc['title'] }}"
                                    data-doc-number="{{ $doc['doc_number'] }}"
                                    data-doc-issuer="{{ $doc['issuer'] }}"
                                    data-doc-status="{{ $doc['status'] }}"
                                    data-doc-summary="{{ $doc['summary'] }}"
                                    class="flex w-full items-center justify-between gap-3 rounded-lg border border-slate-200/80 bg-slate-50 px-3.5 py-2.5 text-left hover:border-[#00877A] hover:bg-[#E6F4F2]/40 active:scale-[0.99] transition-all print:border-slate-200 print:bg-white print:px-2.5 print:py-2 print:pointer-events-none">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="flex h-8 w-8 print:h-6 print:w-6 shrink-0 items-center justify-center rounded-md bg-white border border-slate-200 text-[#00877A]">
                                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            <polyline points="9 12 11 14 15 10"></polyline>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-xs print:text-[11px] font-bold text-[#0F172A]">{{ $doc['title'] }}</p>
                                        <p class="truncate tabular-nums text-[11px] print:text-[10px] text-slate-500">{{ $doc['subtitle'] }}</p>
                                    </div>
                                </div>

                                {{-- Ikon Mata Interaktif (Hanya Web — Disembunyikan saat Print) --}}
                                <svg width="14" height="14" class="print:hidden no-print h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>

                                {{-- Badge Statis Terverifikasi (Hanya Tampil saat Print PDF) --}}
                                <span class="hidden print:inline-flex items-center gap-1 rounded-full bg-[#E6F4F2] px-2 py-0.5 text-[10px] font-bold text-[#00877A] shrink-0">
                                    <svg width="10" height="10" class="h-2.5 w-2.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Terverifikasi</span>
                                </span>
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="rounded-lg border border-[#00877A]/20 bg-[#E6F4F2]/50 p-3 print:p-2 text-[11px] print:text-[10px] leading-relaxed text-slate-600">
                    Seluruh dokumen legalitas dan sertifikasi terarsip resmi pada sistem UPA-RTPU &amp; LPPM Politeknik Negeri Jakarta.
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 6. SPESIFIKASI TEKNIS LENGKAP (TABEL RAPI & PADAT SAAT PRINT)         --}}
    {{-- ===================================================================== --}}
    <section class="overflow-hidden rounded-lg border border-slate-200/80 bg-white" data-detail-print-table>
        <div class="flex flex-wrap items-center justify-between gap-2 px-5 py-4 sm:px-6 print:px-3.5 print:py-2.5">
            <div class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 print:h-6 print:w-6 shrink-0 items-center justify-center rounded-lg bg-[#E6F4F2] text-[#00877A]">
                    <svg width="16" height="16" class="h-4 w-4 print:h-3.5 print:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </span>
                <h2 class="text-base print:text-sm font-extrabold text-[#0F172A]">Spesifikasi Teknis Lengkap</h2>
            </div>
            <span class="tabular-nums text-[11px] font-semibold text-slate-500">
                Standar Validasi Laboratorium PNJ &bull; 2026
            </span>
        </div>

        <div class="overflow-x-auto lg:overflow-visible">
            <table class="w-full border-collapse text-left text-xs print:text-[11px]">
                <thead>
                    <tr class="border-y border-slate-200/80 bg-slate-50 text-[11px] print:text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <th scope="col" class="w-[28%] px-5 py-3 sm:px-6 print:px-3.5 print:py-2">Parameter Teknis</th>
                        <th scope="col" class="w-[38%] px-4 py-3 print:px-3 print:py-2">Nilai / Spesifikasi</th>
                        <th scope="col" class="w-[34%] px-5 py-3 sm:px-6 print:px-3.5 print:py-2">Metode / Standar Pengujian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($detail['specifications'] as $spec)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5 sm:px-6 print:px-3.5 print:py-2 font-bold text-[#0F172A] align-top">
                                {{ $spec['parameter'] }}
                            </td>
                            <td class="px-4 py-3.5 print:px-3 print:py-2 tabular-nums font-semibold text-[#00877A] align-top">
                                {{ $spec['value'] }}
                            </td>
                            <td class="px-5 py-3.5 sm:px-6 print:px-3.5 print:py-2 tabular-nums text-slate-600 align-top">
                                {{ $spec['method'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 7. BANNER CTA BAWAH & PRODUK / FASILITAS TERKAIT (WEB ONLY)           --}}
    {{-- ===================================================================== --}}
    <section class="print:hidden no-print rounded-xl bg-[#00877A] p-6 lg:p-8 text-white">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-bold uppercase tracking-wider text-teal-100">
                    {{ $detail['cta_banner']['eyebrow'] }}
                </p>
                <h2 class="mt-1.5 text-xl sm:text-2xl font-extrabold tracking-tight text-white leading-snug">
                    {{ $detail['cta_banner']['title'] }}
                </h2>
                <p class="mt-2 text-xs sm:text-sm leading-relaxed text-teal-50/95">
                    {{ $detail['cta_banner']['subtitle'] }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if ($isMitra && Route::has('b2b.detail'))
                    <a href="{{ route('b2b.detail', $product->id) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-xs font-bold text-[#00877A] hover:bg-slate-50 active:scale-95 transition-all">
                        <svg width="15" height="15" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span>{{ $meta['cta_label'] }}</span>
                    </a>
                @elseif ($authUser)
                    <a href="{{ $dashboardUrl }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-xs font-bold text-[#00877A] hover:bg-slate-50 active:scale-95 transition-all">
                        <span>Buka Dashboard</span>
                    </a>
                @else
                    <button type="button"
                            data-guest-gate
                            data-gate-title="{{ $guestGateTitle }}"
                            data-gate-product="{{ $product->name }}"
                            data-gate-desc="{{ $guestGateDesc }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-3 text-xs font-bold text-[#00877A] hover:bg-slate-50 active:scale-95 transition-all">
                        <svg width="15" height="15" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span>{{ $meta['cta_label'] }}</span>
                    </button>
                @endif

                <a href="{{ $detail['back_url'] }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-white/35 bg-white/10 hover:bg-white/20 px-5 py-3 text-xs font-semibold text-white active:scale-95 transition-all">
                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    <span>Kembali ke Katalog</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Produk / Fasilitas Terkait (3 Kolom) --}}
    @if ($relatedProducts->isNotEmpty())
        <section class="print:hidden no-print space-y-4 pt-1">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-base font-extrabold text-[#0F172A]">
                    {{ $detail['is_lab'] ? 'Fasilitas Laboratorium Terkait Lainnya' : 'Produk Riset Terkait Lainnya' }}
                </h2>
                <a href="{{ $detail['back_url'] }}" class="text-xs font-semibold text-[#00877A] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach ($relatedProducts as $rel)
                    @php
                        $relMeta = $relatedMetaMap[$rel->id];
                        $relPrice = (float) ($rel->price_b2b > 0 ? $rel->price_b2b : $rel->price_b2c);
                        $relShowPrice = (bool) ($rel->show_price ?? true);
                        $relImg = $rel->resolved_image_url;
                        $relUnitSuffix = $rel->order_type === 'lab_reservation' ? 'Jam' : 'Unit';
                    @endphp
                    <a href="{{ route('public.katalog.detail', $rel->id) }}"
                       class="group flex flex-col justify-between overflow-hidden rounded-lg border border-slate-200/80 bg-white transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/50 hover:shadow-lg">
                        <div>
                            <div class="relative h-40 w-full overflow-hidden border-b border-slate-100 bg-slate-900">
                                @if ($relImg)
                                    <img src="{{ $relImg }}"
                                         alt="{{ $rel->name }}"
                                         loading="lazy"
                                         class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="tech-grid-bg flex h-full w-full items-center justify-center p-4 text-center text-xs font-bold text-white">
                                        {{ $rel->code }}
                                    </div>
                                @endif
                                <span class="absolute top-3 right-3 inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-[10px] font-bold shadow-sm {{ $relMeta['order_pill'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $relMeta['order_dot'] }}"></span>
                                    <span>{{ $relMeta['order_label'] }}</span>
                                </span>
                                <span class="tabular-nums absolute bottom-3 left-3 rounded bg-slate-900/85 px-2 py-0.5 text-[10px] font-bold text-white">
                                    {{ $relMeta['trl_label'] }}
                                </span>
                            </div>

                            <div class="p-4">
                                <p class="truncate text-[11px] font-semibold text-[#00877A]">
                                    {{ $rel->unit?->name }}
                                </p>
                                <h3 class="mt-1 text-sm font-bold text-[#0F172A] group-hover:text-[#00877A] transition-colors line-clamp-2">
                                    {{ $rel->name }}
                                </h3>
                            </div>
                        </div>

                        <div class="mx-4 mb-4 flex items-center justify-between border-t border-slate-100 pt-3">
                            @if ($relShowPrice)
                                <span class="tabular-nums text-sm font-extrabold text-[#0F172A]">
                                    Rp {{ number_format($relPrice, 0, ',', '.') }}
                                    <span class="text-[10px] font-medium text-slate-400">/ {{ $relUnitSuffix }}</span>
                                </span>
                            @else
                                <span class="text-xs font-bold text-[#00877A]">
                                    Hubungi UPA-RTPU
                                </span>
                            @endif
                            <span class="shrink-0 text-xs font-bold text-[#00877A]">Lihat Detail &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- ========================================================================= --}}
{{-- MODAL LIGHTBOX INTERAKTIF (POSTER & GALERI — VANILLA JS)                  --}}
{{-- ========================================================================= --}}
<div id="detail-lightbox-modal"
     class="print:hidden no-print fixed inset-0 z-50 hidden flex-col items-center justify-center bg-slate-950/90 p-4 backdrop-blur-sm"
     role="dialog"
     aria-modal="true"
     aria-labelledby="lightbox-title">
    <div class="flex w-full max-w-5xl flex-col gap-3">
        {{-- Top Bar --}}
        <div class="flex items-center justify-between gap-3 text-white">
            <div class="flex items-center gap-2.5 min-w-0">
                <span id="lightbox-tag" class="rounded bg-[#00877A] px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white"></span>
                <span id="lightbox-counter" class="tabular-nums text-xs font-semibold text-slate-300"></span>
            </div>
            <button type="button"
                    id="lightbox-close"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/15 bg-white/10 text-white hover:bg-white/20 active:scale-95 transition-all"
                    aria-label="Tutup pratinjau gambar">
                <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Image Stage --}}
        <div class="relative flex items-center justify-center overflow-hidden rounded-xl border border-white/10 bg-slate-900 p-2 sm:p-4">
            <img id="lightbox-image"
                 src=""
                 alt=""
                 class="max-h-[70vh] w-auto max-w-full rounded-lg object-contain">
        </div>

        {{-- Caption & Controls --}}
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-white/10 bg-slate-900/90 px-4 py-3 text-white">
            <div class="min-w-0 flex-1">
                <h3 id="lightbox-title" class="text-sm font-bold text-white"></h3>
                <p id="lightbox-caption" class="mt-0.5 text-xs text-slate-300"></p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button"
                        id="lightbox-prev"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-xs font-semibold text-white hover:bg-white/20 active:scale-95 transition-all">
                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                    <span>Sebelumnya</span>
                </button>
                <button type="button"
                        id="lightbox-next"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-3 py-2 text-xs font-semibold text-white active:scale-95 transition-all">
                    <span>Berikutnya</span>
                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL PRATINJAU INFO DOKUMEN LEGALITAS (TANPA 404)                        --}}
{{-- ========================================================================= --}}
<div id="detail-doc-modal"
     class="print:hidden no-print fixed inset-0 z-50 hidden items-center justify-center bg-[#0F172A]/55 p-4 backdrop-blur-[2px]"
     role="dialog"
     aria-modal="true"
     aria-labelledby="doc-modal-title">
    <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
        <div class="h-1 w-full bg-[#00877A]"></div>
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#00877A]">Verifikasi Dokumen PNJ Prime</span>
                    <h3 id="doc-modal-title" class="mt-0.5 text-base font-extrabold text-[#0F172A]"></h3>
                </div>
                <button type="button"
                        id="doc-modal-close"
                        class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Tutup informasi dokumen">
                    <svg width="16" height="16" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <dl class="space-y-2 rounded-lg border border-slate-200/80 bg-slate-50 p-3.5 text-xs">
                <div class="flex justify-between gap-3">
                    <dt class="text-slate-500">Nomor Referensi</dt>
                    <dd id="doc-modal-number" class="tabular-nums font-bold text-[#0F172A] text-right"></dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-slate-500">Penerbit</dt>
                    <dd id="doc-modal-issuer" class="font-semibold text-[#0F172A] text-right"></dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-slate-500">Status</dt>
                    <dd id="doc-modal-status" class="font-bold text-[#00877A] text-right"></dd>
                </div>
            </dl>

            <p id="doc-modal-summary" class="text-xs leading-relaxed text-slate-600"></p>

            <div class="flex items-center justify-end gap-2.5 border-t border-slate-100 pt-3">
                <button type="button"
                        id="doc-modal-print"
                        class="rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 py-2 text-xs font-bold text-white active:scale-95 transition-all">
                    Cetak Brosur PDF
                </button>
                <button type="button"
                        id="doc-modal-dismiss"
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 active:scale-95 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        // 1. Salin Link Halaman Produk dengan Feedback "Tersalin!"
        const copyBtn = document.getElementById('btn-copy-product-link');
        const copyLabel = document.getElementById('copy-link-label');
        let copyTimer = null;

        function markCopied() {
            if (!copyLabel) return;
            copyLabel.textContent = 'Tersalin!';
            if (copyBtn) {
                copyBtn.classList.add('border-[#00877A]', 'text-[#00877A]', 'bg-[#E6F4F2]/60');
            }
            if (copyTimer) clearTimeout(copyTimer);
            copyTimer = setTimeout(function () {
                copyLabel.textContent = 'Salin Link';
                if (copyBtn) {
                    copyBtn.classList.remove('border-[#00877A]', 'text-[#00877A]', 'bg-[#E6F4F2]/60');
                }
            }, 2400);
        }

        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                const url = window.location.href;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(markCopied).catch(function () {
                        fallbackCopy(url);
                    });
                } else {
                    fallbackCopy(url);
                }
            });
        }

        function fallbackCopy(text) {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            try {
                document.execCommand('copy');
                markCopied();
            } catch (e) {}
            document.body.removeChild(ta);
        }

        // 2. Lightbox Modal Interaktif (Poster & Galeri)
        const lightboxItems = @json($lightboxItems);
        const lbModal = document.getElementById('detail-lightbox-modal');
        const lbImg = document.getElementById('lightbox-image');
        const lbTitle = document.getElementById('lightbox-title');
        const lbCaption = document.getElementById('lightbox-caption');
        const lbTag = document.getElementById('lightbox-tag');
        const lbCounter = document.getElementById('lightbox-counter');
        const lbPrev = document.getElementById('lightbox-prev');
        const lbNext = document.getElementById('lightbox-next');
        const lbClose = document.getElementById('lightbox-close');
        let currentIndex = 0;

        function renderLightbox(index) {
            if (!lightboxItems.length || !lbModal) return;
            currentIndex = (index + lightboxItems.length) % lightboxItems.length;
            const item = lightboxItems[currentIndex];
            if (lbImg) {
                lbImg.src = item.url;
                lbImg.alt = item.title || '';
            }
            if (lbTitle) lbTitle.textContent = item.title || '';
            if (lbCaption) lbCaption.textContent = item.caption || '';
            if (lbTag) lbTag.textContent = item.tag || 'GALERI';
            if (lbCounter) lbCounter.textContent = (currentIndex + 1) + ' / ' + lightboxItems.length;
        }

        function openLightbox(index) {
            if (!lightboxItems.length || !lbModal) return;
            renderLightbox(index);
            lbModal.classList.remove('hidden');
            lbModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeLightbox() {
            if (!lbModal) return;
            lbModal.classList.add('hidden');
            lbModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.querySelectorAll('[data-lightbox-open]').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                const idx = parseInt(trigger.getAttribute('data-lightbox-open'), 10);
                openLightbox(isNaN(idx) ? 0 : idx);
            });
        });

        if (lbPrev) lbPrev.addEventListener('click', function () { renderLightbox(currentIndex - 1); });
        if (lbNext) lbNext.addEventListener('click', function () { renderLightbox(currentIndex + 1); });
        if (lbClose) lbClose.addEventListener('click', closeLightbox);
        if (lbModal) {
            lbModal.addEventListener('click', function (e) {
                if (e.target === lbModal) closeLightbox();
            });
        }

        // 3. Modal Pratinjau Informasi Dokumen Legalitas
        const docModal = document.getElementById('detail-doc-modal');
        const docTitle = document.getElementById('doc-modal-title');
        const docNumber = document.getElementById('doc-modal-number');
        const docIssuer = document.getElementById('doc-modal-issuer');
        const docStatus = document.getElementById('doc-modal-status');
        const docSummary = document.getElementById('doc-modal-summary');
        const docClose = document.getElementById('doc-modal-close');
        const docDismiss = document.getElementById('doc-modal-dismiss');
        const docPrint = document.getElementById('doc-modal-print');

        function closeDocModal() {
            if (!docModal) return;
            docModal.classList.add('hidden');
            docModal.classList.remove('flex');
        }

        document.querySelectorAll('[data-doc-preview]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!docModal) return;
                if (docTitle) docTitle.textContent = btn.getAttribute('data-doc-title') || '';
                if (docNumber) docNumber.textContent = btn.getAttribute('data-doc-number') || '';
                if (docIssuer) docIssuer.textContent = btn.getAttribute('data-doc-issuer') || '';
                if (docStatus) docStatus.textContent = btn.getAttribute('data-doc-status') || '';
                if (docSummary) docSummary.textContent = btn.getAttribute('data-doc-summary') || '';
                docModal.classList.remove('hidden');
                docModal.classList.add('flex');
            });
        });

        if (docClose) docClose.addEventListener('click', closeDocModal);
        if (docDismiss) docDismiss.addEventListener('click', closeDocModal);
        if (docPrint) {
            docPrint.addEventListener('click', function () {
                closeDocModal();
                window.print();
            });
        }
        if (docModal) {
            docModal.addEventListener('click', function (e) {
                if (e.target === docModal) closeDocModal();
            });
        }

        // Keyboard Shortcuts untuk Lightbox & Modal Dokumen
        document.addEventListener('keydown', function (e) {
            if (lbModal && !lbModal.classList.contains('hidden')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') renderLightbox(currentIndex - 1);
                if (e.key === 'ArrowRight') renderLightbox(currentIndex + 1);
            }
            if (docModal && !docModal.classList.contains('hidden') && e.key === 'Escape') {
                closeDocModal();
            }
        });
    })();
</script>
@endpush
