@extends('mitra.layouts.app')

@section('title', $product->name . ' — Detail Katalog B2B')
@section('nav', $product->order_type === 'lab_reservation' ? 'lab' : 'katalog')
@section('search_placeholder', 'Cari produk atau layanan B2B lainnya...')

@php
    $orderTypeMeta = [
        'ready_stock' => [
            'label' => 'Ready Stock',
            'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'cta' => 'Lanjut Pesan Ready Stock',
            'estimasi' => 'Siap Kirim (1–2 Hari Kerja)',
            'satuan' => 'Unit',
            'stok_label' => 'Stok Tersedia',
            'alur' => 'Checkout Langsung & Terbitkan Invoice 100% Lunas',
        ],
        'pre_order' => [
            'label' => 'Pre-Order',
            'badge' => 'bg-sky-50 text-sky-700 border-sky-200',
            'cta' => 'Ajukan Pre-Order B2B',
            'estimasi' => 'Perakitan & QC ± 7 Hari Kerja',
            'satuan' => 'Unit',
            'stok_label' => 'Kuota Produksi',
            'alur' => 'Pengajuan Pre-Order → Validasi RTPU → Pembayaran 100% Lunas',
        ],
        'lab_reservation' => [
            'label' => 'Reservasi Lab',
            'badge' => 'bg-teal-50 text-[#007266] border-teal-200',
            'cta' => 'Ajukan Reservasi Slot Lab',
            'estimasi' => 'Sesi Operasional 4 Jam (Pagi / Siang)',
            'satuan' => 'Sesi',
            'stok_label' => 'Slot Tersedia',
            'alur' => 'Pilih Tanggal/Sesi & Lampirkan CAD/SOP → Validasi → 100% Lunas',
        ],
    ];

    $meta = $orderTypeMeta[$product->order_type] ?? $orderTypeMeta['ready_stock'];
    $imageUrl = $product->resolved_image_url;
    $imageExists = $imageUrl !== null;
@endphp

@section('content')
<div class="space-y-4">
    {{-- ===================================================================== --}}
    {{-- BREADCRUMB & BACK BUTTON                                              --}}
    {{-- ===================================================================== --}}
    <div class="flex flex-wrap items-center justify-between gap-2">
        <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
            <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#00897b]">Beranda</a>
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            <a href="{{ route('b2b.katalog') }}" class="hover:text-[#00897b]">Katalog Riset</a>
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            <span class="font-semibold text-[#00897b] truncate max-w-[260px]">{{ $product->name }}</span>
        </nav>

        <a href="{{ route('b2b.katalog') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/80 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 active:scale-95">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            Kembali ke Katalog
        </a>
    </div>

    {{-- ===================================================================== --}}
    {{-- HERO DETAIL & ACTION BOX (PROPORSIONAL 1366x768)                      --}}
    {{-- ===================================================================== --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
        {{-- Left Column: Product Overview, Description & Specs --}}
        <div class="space-y-4 lg:col-span-7">
            <section class="overflow-hidden rounded-lg border border-slate-200/80 bg-white">
                <div class="relative h-52 w-full border-b border-slate-100 bg-slate-900">
                    @if ($imageExists)
                        <img src="{{ $imageUrl }}"
                             alt="{{ $product->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full flex-col justify-between bg-gradient-to-br from-slate-900 via-slate-800 to-[#005a50] p-5 text-white">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="tabular-nums rounded bg-white/10 px-2.5 py-1 text-[11px] font-bold text-teal-200">
                                        {{ $product->code }}
                                    </span>
                                    <span class="rounded bg-white/10 px-2.5 py-1 text-[11px] font-semibold text-white">
                                        {{ $product->category?->name }} &bull; {{ $product->subCategory?->name }}
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $meta['badge'] }}">
                                    {{ $meta['label'] }}
                                </span>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold tracking-wider text-teal-300 uppercase">
                                    {{ $product->unit?->name }}
                                </p>
                                <h1 class="mt-1 text-xl font-extrabold tracking-tight text-white">
                                    {{ $product->name }}
                                </h1>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-4">
                    @if ($imageExists)
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="tabular-nums rounded bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-700">{{ $product->code }}</span>
                                <span class="rounded border px-2 py-0.5 text-[11px] font-semibold {{ $meta['badge'] }}">{{ $meta['label'] }}</span>
                                <span class="rounded bg-[#e6f7f5] px-2 py-0.5 text-[11px] font-semibold text-[#007266]">{{ $product->category?->name }}</span>
                            </div>
                            <h1 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900">{{ $product->name }}</h1>
                        </div>
                    @endif

                    {{-- Deskripsi Lengkap --}}
                    <div>
                        <h2 class="text-xs font-bold tracking-wide text-slate-800 uppercase">Deskripsi Produk &amp; Layanan</h2>
                        <p class="mt-1.5 text-xs leading-relaxed text-slate-600">
                            {{ $product->description }}
                        </p>
                    </div>

                    {{-- Metadata Grid --}}
                    <div class="grid grid-cols-2 gap-3 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 sm:grid-cols-4 text-xs">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase">Unit Pengelola</p>
                            <p class="mt-0.5 font-semibold text-slate-800">{{ $product->unit?->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase">Kategori</p>
                            <p class="mt-0.5 font-semibold text-slate-800">{{ $product->category?->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase">{{ $meta['stok_label'] }}</p>
                            <p class="mt-0.5 tabular-nums font-bold text-emerald-700">{{ $product->stock }} {{ $meta['satuan'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase">Estimasi Pengerjaan</p>
                            <p class="mt-0.5 tabular-nums font-semibold text-slate-800">{{ $meta['estimasi'] }}</p>
                        </div>
                    </div>

                    @if ($product->curation_notes)
                        <div class="flex items-start gap-2.5 rounded-lg border border-teal-200/70 bg-[#e6f7f5]/60 p-3 text-xs">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#00897b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4M12 8h.01"/>
                            </svg>
                            <div>
                                <p class="font-bold text-[#007266]">Catatan Kurasi Teknis RTPU PNJ</p>
                                <p class="mt-0.5 text-[11px] text-slate-600">{{ $product->curation_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Kesiapan Dokumen Pajak & BAST Komersial --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Kesiapan Dokumen Pajak &amp; Legalitas Komersial</h2>
                        <p class="mt-0.5 text-[11px] text-slate-500">
                            Otomatis disiapkan oleh sistem billing BLU PNJ untuk kebutuhan akuntansi &amp; audit korporat B2B.
                        </p>
                    </div>
                    <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">
                        Siap Diterbitkan
                    </span>
                </div>

                <div class="mt-3.5 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-200/80 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">PPN 11%</span>
                            <span class="tabular-nums rounded bg-teal-50 px-1.5 py-0.5 text-[10px] font-bold text-[#007266]">Tarif 11%</span>
                        </div>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            Faktur Pajak keluaran resmi BLU PNJ dihitung 11% dari Dasar Pengenaan Pajak (DPP).
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200/80 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">PPh Pasal 23</span>
                            <span class="tabular-nums rounded {{ $isServiceOrLab ? 'bg-sky-50 text-sky-700' : 'bg-slate-100 text-slate-600' }} px-1.5 py-0.5 text-[10px] font-bold">
                                {{ $isServiceOrLab ? 'Potong 2%' : 'Opsional Jasa' }}
                            </span>
                        </div>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            @if ($isServiceOrLab)
                                Berlaku pemotongan PPh 23 sebesar 2% atas jasa teknis/sewa lab untuk Wajib Pajak Badan.
                            @else
                                Khusus pengadaan barang fisik tidak dikenakan PPh 23 (hanya berlaku untuk komponen jasa/sewa).
                            @endif
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200/80 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">e-Meterai Resmi</span>
                            <span class="tabular-nums rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-bold text-indigo-700">Rp 10.000</span>
                        </div>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            Pembubuhan meterai elektronik PERURI Rp 10.000 pada dokumen invoice &amp; tanda terima.
                        </p>
                    </div>

                    <div class="rounded-lg border border-slate-200/80 bg-slate-50/50 p-3 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">BAST Komersial</span>
                            <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Tersedia</span>
                        </div>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            Berita Acara Serah Terima barang, hasil fabrikasi, atau laporan uji diterbitkan setelah selesai.
                        </p>
                    </div>
                </div>
            </section>
        </div>

        {{-- Right Column: B2B Pricing, 100% Lunas Rule & Interactive Tax Calculator --}}
        <div class="space-y-4 lg:col-span-5">
            <section class="sticky top-20 rounded-lg border border-slate-200/80 bg-white p-5 space-y-4">
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-3.5">
                    <div>
                        <p class="text-[11px] font-semibold tracking-wider text-[#00877A] uppercase">Tarif Komersial B2B</p>
                        @if ($product->show_price ?? true)
                            <div class="mt-1 flex items-baseline gap-1.5">
                                <span class="tabular-nums text-2xl font-extrabold tracking-tight text-slate-900">
                                    Rp {{ number_format($product->price_b2b, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400">/ {{ $meta['satuan'] }}</span>
                            </div>
                        @else
                            <div class="mt-1.5">
                                <span class="inline-flex items-center rounded-md border border-teal-200 bg-[#E6F4F2] px-2.5 py-1 text-xs font-bold text-[#00877A]">
                                    Ajukan Penawaran
                                </span>
                                <p class="mt-1 text-xs font-medium text-slate-600">
                                    Harga disesuaikan saat penerbitan Invoice
                                </p>
                            </div>
                        @endif
                    </div>
                    <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 shrink-0">
                        100% Lunas
                    </span>
                </div>

                {{-- Aturan Bisnis Pembayaran Penuh --}}
                <div class="rounded-lg border border-slate-200/80 bg-slate-50 px-3.5 py-2.5 text-[11px] text-slate-600">
                    <p class="font-bold text-slate-800">Skema Pembayaran: 100% Lunas</p>
                    <p class="mt-0.5 leading-relaxed">
                        Pembayaran dilakukan penuh melalui Virtual Account Mandiri BLU atau QRIS setelah pengajuan disetujui. Alur: <span class="font-medium text-slate-700">{{ $meta['alur'] }}</span>.
                    </p>
                </div>

                {{-- Quantity & Tax Simulation --}}
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label for="b2b-qty-input" class="text-xs font-bold text-slate-800">
                            Simulasi Jumlah ({{ $meta['satuan'] }})
                        </label>
                        <div class="flex items-center rounded-lg border border-slate-200 bg-white">
                            <button type="button" id="b2b-qty-dec"
                                    class="h-8 w-8 text-sm font-bold text-slate-600 hover:bg-slate-50 active:scale-95">−</button>
                            <input type="number" id="b2b-qty-input" value="1" min="1" max="{{ max(1, $product->stock) }}"
                                   class="tabular-nums h-8 w-12 border-x border-slate-200 text-center text-xs font-bold text-slate-900 outline-none">
                            <button type="button" id="b2b-qty-inc"
                                    class="h-8 w-8 text-sm font-bold text-slate-600 hover:bg-slate-50 active:scale-95">+</button>
                        </div>
                    </div>

                    @if ($product->show_price ?? true)
                        <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 space-y-2 text-xs">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Subtotal / DPP (<span id="calc-qty-label" class="tabular-nums font-semibold">1</span> {{ $meta['satuan'] }})</span>
                                <span id="calc-dpp" class="tabular-nums font-semibold text-slate-900">
                                    Rp {{ number_format($taxBreakdown['dpp'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>PPN 11%</span>
                                <span id="calc-ppn" class="tabular-nums font-semibold text-slate-800">
                                    + Rp {{ number_format($taxBreakdown['ppn_amount'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>PPh 23 (2% {{ $isServiceOrLab ? 'Jasa/Lab' : 'N/A' }})</span>
                                <span id="calc-pph23" class="tabular-nums font-semibold {{ $isServiceOrLab ? 'text-sky-700' : 'text-slate-400' }}">
                                    {{ $isServiceOrLab ? '− Rp ' . number_format($taxBreakdown['pph23_amount'], 0, ',', '.') : 'Rp 0' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>e-Meterai Dokumen</span>
                                <span class="tabular-nums font-semibold text-slate-800">
                                    + Rp {{ number_format($taxBreakdown['emeterai_amount'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-200 pt-2">
                                <span class="font-bold text-slate-900">Estimasi Total (100% Lunas)</span>
                                <span id="calc-grand-total" class="tabular-nums text-base font-extrabold text-[#00877A]">
                                    Rp {{ number_format($taxBreakdown['grand_total'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="rounded-lg border border-teal-200/80 bg-[#E6F4F2]/60 p-3.5 text-xs text-slate-700 space-y-1.5">
                            <p class="font-bold text-[#00877A]">Penetapan Harga Berbasis Penawaran &amp; Invoice</p>
                            <p class="text-[11px] leading-relaxed text-slate-600">
                                Nominal harga untuk <span id="calc-qty-label" class="tabular-nums font-semibold text-slate-800">1</span> {{ $meta['satuan'] }}, termasuk perhitungan DPP, PPN 11%, {{ $isServiceOrLab ? 'PPh 23 (2%), ' : '' }}dan e-Meterai akan diinformasikan secara resmi saat Admin RTPU menerbitkan Invoice.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Primary CTA Button by Order Type --}}
                <div class="space-y-2 pt-1">
                    <button type="button"
                            id="b2b-open-order-preview"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#00998a] px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#007d71] active:scale-95">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                        {{ $meta['cta'] }}
                    </button>
                    <p class="text-center text-[11px] text-slate-400">
                        Menuju formulir pengajuan &amp; checkout B2B (skema 100% Lunas)
                    </p>
                </div>
            </section>
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- PRODUK / LAYANAN B2B TERKAIT                                          --}}
    {{-- ===================================================================== --}}
    @if ($relatedProducts->isNotEmpty())
        <section class="space-y-2.5 pt-2">
            <h2 class="text-sm font-bold text-slate-900">Produk &amp; Layanan Riset Lainnya</h2>
            <div class="grid grid-cols-1 gap-3.5 md:grid-cols-3">
                @foreach ($relatedProducts as $rel)
                    @php
                        $relMeta = $orderTypeMeta[$rel->order_type] ?? $orderTypeMeta['ready_stock'];
                    @endphp
                    <a href="{{ route('b2b.detail', $rel->id) }}"
                       class="group flex flex-col justify-between rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-semibold text-[#00897b]">{{ $rel->unit?->name }}</span>
                                <span class="rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $relMeta['badge'] }}">{{ $relMeta['label'] }}</span>
                            </div>
                            <h3 class="mt-1.5 text-xs font-bold text-slate-900 group-hover:text-[#00897b] transition-colors">
                                {{ $rel->name }}
                            </h3>
                        </div>
                        <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2.5">
                            @if ($rel->show_price ?? true)
                                <span class="tabular-nums text-sm font-extrabold text-slate-900">
                                    Rp {{ number_format($rel->price_b2b, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-xs font-semibold text-[#00877A]">
                                    Ajukan Penawaran
                                </span>
                            @endif
                            <span class="text-[11px] font-semibold text-[#00877A]">Detail &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- ========================================================================= --}}
{{-- MODAL RINGKASAN PENGAJUAN / CHECKOUT PREVIEW (BATCH 1 -> BATCH 2)         --}}
{{-- ========================================================================= --}}
<div id="b2b-order-preview-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]"
     role="dialog"
     aria-modal="true"
     aria-labelledby="b2b-order-preview-title">
    <div class="w-full max-w-md rounded-lg border border-slate-200/80 bg-white p-5 shadow-xl">
        <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3">
            <div>
                <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $meta['badge'] }}">
                    {{ $meta['label'] }} &bull; 100% Lunas
                </span>
                <h3 id="b2b-order-preview-title" class="mt-1.5 text-sm font-bold text-slate-900">
                    Ringkasan Pengajuan B2B
                </h3>
            </div>
            <button type="button" id="b2b-close-order-preview"
                    class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700">&times;</button>
        </div>

        <div class="mt-3.5 space-y-3 text-xs">
            <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3">
                <p class="font-bold text-slate-900">{{ $product->name }}</p>
                <p class="mt-0.5 text-[11px] text-slate-500">{{ $product->unit?->name }} &bull; <span class="tabular-nums">{{ $product->code }}</span></p>
                <div class="mt-2.5 flex items-center justify-between border-t border-slate-200/80 pt-2">
                    <span class="text-slate-600">Jumlah &bull; Estimasi</span>
                    <span class="tabular-nums font-bold text-slate-900">
                        <span id="modal-qty-label">1</span> {{ $meta['satuan'] }} &bull; {{ $meta['estimasi'] }}
                    </span>
                </div>
                <div class="mt-1 flex items-center justify-between">
                    <span class="text-slate-600">Estimasi Tagihan</span>
                    @if ($product->show_price ?? true)
                        <span id="modal-total-label" class="tabular-nums font-extrabold text-[#00877A]">
                            Rp {{ number_format($taxBreakdown['grand_total'], 0, ',', '.') }}
                        </span>
                    @else
                        <span class="font-semibold text-[#00877A]">
                            Disesuaikan saat Invoice
                        </span>
                    @endif
                </div>
            </div>

            <div class="rounded-lg border border-teal-200/70 bg-[#e6f7f5]/60 p-3 text-[11px] text-[#005a50]">
                <p class="font-bold">Dokumen Pajak &amp; Alur Pengajuan Siap:</p>
                <ul class="mt-1 list-disc pl-4 space-y-0.5">
                    <li>Skema pembayaran penuh <strong>100% Lunas</strong> via VA Mandiri BLU / QRIS.</li>
                    <li>Kelengkapan otomatis: <strong>PPN 11%</strong>, <strong>{{ $isServiceOrLab ? 'PPh 23 (2%)' : 'Faktur Komersial' }}</strong>, <strong>e-Meterai</strong>, dan <strong>BAST</strong>.</li>
                    <li>Formulir checkout &amp; jadwal sesi interaktif akan terhubung penuh pada alur transaksi Batch 2.</li>
                </ul>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3.5">
            <button type="button" id="b2b-cancel-order-preview"
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 active:scale-95">
                Tutup
            </button>
            <a href="{{ route('b2b.katalog') }}"
               class="rounded-lg bg-[#00998a] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                Jelajahi Item Lain
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const unitPrice = {{ (float) $product->price_b2b }};
        const ppnRate = {{ (float) $taxBreakdown['ppn_rate'] }};
        const pph23Rate = {{ (float) $taxBreakdown['pph23_rate'] }};
        const emeterai = {{ (float) $taxBreakdown['emeterai_amount'] }};
        const maxStock = {{ max(1, (int) $product->stock) }};

        const qtyInput = document.getElementById('b2b-qty-input');
        const decBtn = document.getElementById('b2b-qty-dec');
        const incBtn = document.getElementById('b2b-qty-inc');

        const qtyLabel = document.getElementById('calc-qty-label');
        const dppEl = document.getElementById('calc-dpp');
        const ppnEl = document.getElementById('calc-ppn');
        const pph23El = document.getElementById('calc-pph23');
        const grandTotalEl = document.getElementById('calc-grand-total');

        const modalQtyLabel = document.getElementById('modal-qty-label');
        const modalTotalLabel = document.getElementById('modal-total-label');

        function formatIdr(val) {
            return 'Rp ' + Math.round(val).toLocaleString('id-ID');
        }

        function recalc() {
            if (!qtyInput) return;
            let qty = parseInt(qtyInput.value, 10);
            if (isNaN(qty) || qty < 1) qty = 1;
            if (qty > maxStock) qty = maxStock;
            qtyInput.value = qty;

            const dpp = unitPrice * qty;
            const ppn = Math.round(dpp * ppnRate);
            const pph23 = Math.round(dpp * pph23Rate);
            const grandTotal = dpp + ppn - pph23 + emeterai;

            if (qtyLabel) qtyLabel.textContent = String(qty);
            if (dppEl) dppEl.textContent = formatIdr(dpp);
            if (ppnEl) ppnEl.textContent = '+ ' + formatIdr(ppn);
            if (pph23El && pph23Rate > 0) pph23El.textContent = '− ' + formatIdr(pph23);
            if (grandTotalEl) grandTotalEl.textContent = formatIdr(grandTotal);

            if (modalQtyLabel) modalQtyLabel.textContent = String(qty);
            if (modalTotalLabel) modalTotalLabel.textContent = formatIdr(grandTotal);
        }

        if (decBtn) {
            decBtn.addEventListener('click', function () {
                qtyInput.value = Math.max(1, (parseInt(qtyInput.value, 10) || 1) - 1);
                recalc();
            });
        }
        if (incBtn) {
            incBtn.addEventListener('click', function () {
                qtyInput.value = Math.min(maxStock, (parseInt(qtyInput.value, 10) || 1) + 1);
                recalc();
            });
        }
        if (qtyInput) {
            qtyInput.addEventListener('input', recalc);
        }

        // Order Preview Modal
        const openBtn = document.getElementById('b2b-open-order-preview');
        const modal = document.getElementById('b2b-order-preview-modal');
        const closeBtn = document.getElementById('b2b-close-order-preview');
        const cancelBtn = document.getElementById('b2b-cancel-order-preview');

        function closeModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        if (openBtn && modal) {
            openBtn.addEventListener('click', function () {
                recalc();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        }
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    })();
</script>
@endpush
