@extends('public.layouts.catalog')

@section('title', $product->name . ' — Katalog Riset Publik')
@section('public_nav', $product->order_type === 'lab_reservation' ? 'lab' : 'katalog')

@php
    $authUser = auth()->user();
    $isMitra = $authUser && $authUser->role === 'mitra';
    $imageExists = $product->image_path && file_exists(public_path('storage/' . $product->image_path));
    $displayPrice = (float) ($product->price_b2b > 0 ? $product->price_b2b : $product->price_b2c);
    $showPrice = (bool) ($product->show_price ?? true);
@endphp

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb & Back --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <nav class="flex items-center gap-1.5 text-[11px] font-semibold tracking-wider uppercase text-slate-400" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-[#00877A] transition-colors">BERANDA</a>
            <svg width="12" height="12" class="h-3 w-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
            <a href="{{ route('public.katalog') }}" class="hover:text-[#00877A] transition-colors">KATALOG RISET</a>
            <svg width="12" height="12" class="h-3 w-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
            <span class="text-[#00877A] truncate max-w-[240px]">{{ $product->code }}</span>
        </nav>

        <a href="{{ route('public.katalog') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] transition-colors">
            <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            <span>Kembali ke Katalog</span>
        </a>
    </div>

    {{-- Main Detail Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Left Column (7/12): Visual, Deskripsi & Spesifikasi --}}
        <div class="space-y-5 lg:col-span-7">
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="relative h-60 w-full border-b border-slate-100 bg-[#0F172A]">
                    @if ($imageExists)
                        <img src="{{ asset('storage/' . $product->image_path) }}"
                             alt="{{ $product->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="tech-grid-bg flex h-full w-full flex-col justify-between p-6 text-white">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="tabular-nums rounded-md bg-[#00877A] px-2.5 py-1 text-[11px] font-bold text-white">
                                        {{ $meta['trl_label'] }}
                                    </span>
                                    <span class="rounded-md bg-white/10 border border-white/15 px-2.5 py-1 text-[11px] font-semibold text-white">
                                        {{ $product->category?->name }} &bull; {{ $product->subCategory?->name }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $meta['order_pill'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $meta['order_dot'] }}"></span>
                                    {{ $meta['order_label'] }}
                                </span>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-[#99D3CB]">
                                    {{ $product->unit?->name }} &bull; <span class="tabular-nums">{{ $product->code }}</span>
                                </p>
                                <h1 class="mt-1 text-xl sm:text-2xl font-extrabold tracking-tight text-white">
                                    {{ $product->name }}
                                </h1>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-6 space-y-5">
                    @if ($imageExists)
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="tabular-nums rounded-md bg-[#00877A] px-2.5 py-0.5 text-[11px] font-bold text-white">{{ $meta['trl_label'] }}</span>
                                <span class="rounded-md bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-[#0F172A]">{{ $product->category?->name }}</span>
                                <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $meta['order_pill'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $meta['order_dot'] }}"></span>
                                    {{ $meta['order_label'] }}
                                </span>
                            </div>
                            <h1 class="mt-2.5 text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A]">{{ $product->name }}</h1>
                        </div>
                    @endif

                    {{-- Deskripsi --}}
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Deskripsi Riset &amp; Spesifikasi</h2>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            {{ $product->description }}
                        </p>
                    </div>

                    {{-- Progress Bar Kesiapan --}}
                    <div class="rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-4 space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-600">Tingkat Kesiapan Teknologi ({{ $meta['trl_label'] }})</span>
                            <span class="tabular-nums font-bold text-[#00877A]">{{ $meta['readiness_pct'] }}% Siap Hilirisasi</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-[#00877A]" style="width: {{ $meta['readiness_pct'] }}%;"></div>
                        </div>
                    </div>

                    {{-- Grid Metadata --}}
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 rounded-xl border border-slate-200/80 bg-white p-4 text-xs">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Unit Pengelola</p>
                            <p class="mt-1 font-bold text-[#0F172A]">{{ $product->unit?->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Kategori</p>
                            <p class="mt-1 font-bold text-[#0F172A]">{{ $product->category?->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">{{ $meta['stok_label'] }}</p>
                            <p class="mt-1 tabular-nums font-bold text-emerald-700">{{ $product->stock }} {{ $meta['satuan'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Estimasi Waktu</p>
                            <p class="mt-1 tabular-nums font-bold text-[#0F172A]">{{ $meta['estimasi'] }}</p>
                        </div>
                    </div>

                    @if ($product->curation_notes)
                        <div class="flex items-start gap-3 rounded-xl border border-[#00877A]/20 bg-[#E6F4F2]/60 p-4 text-xs">
                            <svg width="18" height="18" class="mt-0.5 h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <div>
                                <p class="font-bold text-[#006E63]">Catatan Kurasi Teknis UPA-RTPU PNJ</p>
                                <p class="mt-1 text-slate-600 leading-relaxed">{{ $product->curation_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- Right Column (5/12): Pricing Card & Guest Gate CTA --}}
        <div class="lg:col-span-5">
            <section class="sticky top-20 rounded-xl border border-slate-200 bg-white p-6 space-y-5">
                <div class="border-b border-slate-100 pb-4">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-[#00877A]">Informasi Tarif &amp; Pengadaan</span>
                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">
                            100% Lunas
                        </span>
                    </div>

                    @if ($showPrice)
                        <div class="mt-2.5 flex items-baseline gap-1.5">
                            <span class="tabular-nums text-2xl font-extrabold tracking-tight text-[#0F172A]">
                                Rp {{ number_format($displayPrice, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-slate-400">/ {{ $meta['satuan'] }}</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            Tarif resmi komersial BLU Politeknik Negeri Jakarta (belum termasuk PPN 11% &amp; komponen faktur korporat).
                        </p>
                    @else
                        <div class="mt-3 rounded-xl border border-[#00877A]/25 bg-[#E6F4F2]/70 p-3.5">
                            <span class="inline-flex items-center rounded-md bg-[#00877A] px-2 py-0.5 text-[10px] font-bold text-white">
                                Ajukan Penawaran
                            </span>
                            <p class="mt-1.5 text-sm font-bold text-[#0F172A]">
                                Harga disesuaikan saat penerbitan Invoice
                            </p>
                            <p class="mt-1 text-[11px] leading-relaxed text-slate-600">
                                Nominal harga final ditetapkan oleh Admin RTPU sesuai spesifikasi teknis, volume pemesanan, dan kelengkapan pajak resmi BLU PNJ.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Keunggulan Pengadaan Resmi --}}
                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-start gap-2.5">
                        <svg width="16" height="16" class="mt-0.5 h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Pembayaran penuh <strong class="text-[#0F172A]">100% Lunas</strong> melalui Virtual Account Mandiri BLU atau QRIS resmi.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <svg width="16" height="16" class="mt-0.5 h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Kelengkapan dokumen administrasi B2B: <strong class="text-[#0F172A]">PPN 11%</strong>, e-Meterai PERURI, dan <strong class="text-[#0F172A]">BAST</strong> resmi.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <svg width="16" height="16" class="mt-0.5 h-4 w-4 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Terverifikasi kualitas dan kesiapan hilirisasi oleh LPPM &amp; UPA-RTPU PNJ.</span>
                    </div>
                </div>

                {{-- CTA Pemesanan (Guest Gate / Direct B2B) --}}
                <div class="space-y-2.5 pt-1">
                    @if ($isMitra && Route::has('b2b.detail'))
                        <a href="{{ route('b2b.detail', $product->id) }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#00877A] hover:bg-[#006E63] px-4 py-3 text-xs font-bold text-white transition-colors">
                            <svg width="15" height="15" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span>Lanjut Pemesanan di Portal Mitra B2B</span>
                        </a>
                    @elseif ($authUser)
                        <a href="{{ $dashboardUrl }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#00877A] hover:bg-[#006E63] px-4 py-3 text-xs font-bold text-white transition-colors">
                            <span>Buka Dashboard untuk Memesan</span>
                        </a>
                    @else
                        <button type="button"
                                data-guest-gate
                                data-gate-title="Masuk atau Daftar untuk Melanjutkan Pemesanan"
                                data-gate-product="{{ $product->name }}"
                                data-gate-desc="Silakan masuk ke akun PNJ Prime atau daftarkan akun Mitra Industri baru untuk mengajukan pemesanan dan mendapatkan dokumen penawaran resmi."
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#00877A] hover:bg-[#006E63] px-4 py-3 text-xs font-bold text-white transition-colors">
                            <svg width="15" height="15" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span>{{ $meta['cta_label'] }}</span>
                        </button>
                        <p class="text-center text-[11px] text-slate-400">
                            Pengunjung umum wajib masuk atau mendaftar sebagai Mitra sebelum melanjutkan transaksi.
                        </p>
                    @endif
                </div>
            </section>
        </div>
    </div>

    {{-- Produk Riset Terkait --}}
    @if ($relatedProducts->isNotEmpty())
        <section class="space-y-3 pt-2">
            <h2 class="text-sm font-bold text-[#0F172A]">Inovasi Riset Terkait Lainnya</h2>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach ($relatedProducts as $rel)
                    @php
                        $relMeta = $relatedMetaMap[$rel->id];
                        $relPrice = (float) ($rel->price_b2b > 0 ? $rel->price_b2b : $rel->price_b2c);
                        $relShowPrice = (bool) ($rel->show_price ?? true);
                    @endphp
                    <a href="{{ route('public.katalog.detail', $rel->id) }}"
                       class="group flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-4 transition-all hover:-translate-y-0.5 hover:border-[#00877A]/50 hover:shadow-md">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-semibold text-[#00877A] truncate">{{ $rel->unit?->name }}</span>
                                <span class="shrink-0 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $relMeta['order_pill'] }}">{{ $relMeta['order_label'] }}</span>
                            </div>
                            <h3 class="mt-1.5 text-sm font-bold text-[#0F172A] group-hover:text-[#00877A] transition-colors">
                                {{ $rel->name }}
                            </h3>
                        </div>
                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                            @if ($relShowPrice)
                                <span class="tabular-nums text-sm font-extrabold text-[#0F172A]">
                                    Rp {{ number_format($relPrice, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-xs font-semibold text-[#00877A]">
                                    Ajukan Penawaran
                                </span>
                            @endif
                            <span class="text-xs font-semibold text-[#00877A]">Lihat Detail &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
