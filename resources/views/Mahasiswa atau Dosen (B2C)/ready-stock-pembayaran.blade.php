@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Pembayaran Ready Stock — ' . ($invoice?->invoice_number ?? $order->order_code))
@section('nav', 'pembayaran')

@section('content')
@php
    $firstItem = $order->items->first();
    $product = $firstItem?->product;
    $vaRaw = $invoice?->va_number ?: '887702107411032';
    $vaFormatted = trim(chunk_split($vaRaw, 4, ' '));
    $isPaid = $invoice && $invoice->payment_status === 'paid';
    $paidModalData = session('ready_stock_paid');
@endphp

<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <a href="{{ route('b2c.pembayaran') }}" class="hover:text-[#00877A]">Pembayaran &amp; Tagihan</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Tagihan Ready Stock</span>
            </nav>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Pembayaran &amp; Tagihan</h1>
            <p class="text-xs text-slate-500">Daftar tagihan aktif dan riwayat pembayaran transaksi produk riset Ready Stock BLU PNJ.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-3 py-1 text-[11px] font-semibold text-[#00877A]">
            <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
            Sinkronisasi Rekening BLU PNJ Aktif
        </span>
    </div>

    {{-- Tab Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('b2c.pembayaran') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-3.5 py-2 text-xs font-bold text-white shadow-xs transition-all active:scale-95">
                <span>Tagihan Aktif</span>
                <span class="rounded-full bg-white/20 px-1.5 py-0.5 tabular-nums text-[10px] font-bold">{{ $activeCount }}</span>
            </a>
            <a href="{{ route('b2c.pembayaran.riwayat') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                <span>Riwayat Selesai</span>
                <span class="rounded-full bg-slate-100 px-1.5 py-0.5 tabular-nums text-[10px] font-bold text-slate-600">{{ $paidCount }}</span>
            </a>
        </div>

        <a href="{{ route('b2c.pesanan.show', $order) }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
            Lihat Detail Pesanan &rarr;
        </a>
    </div>

    {{-- Card Utama Tagihan Ready Stock (04b.1) --}}
    <article class="overflow-hidden rounded-lg border border-slate-200/80 bg-white shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="h-1 bg-[#00877A]"></div>
        <div class="p-5">
            {{-- Top Row --}}
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-3.5">
                <div>
                    <p class="tabular-nums text-base font-extrabold text-slate-900">{{ $invoice?->invoice_number ?? $order->order_code }}</p>
                    <p class="mt-0.5 text-[11px] text-slate-500">
                        Diterbitkan {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </p>
                    @if ($invoice?->due_at && ! $isPaid)
                        <p class="mt-1 inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                            Jatuh tempo: {{ $invoice->due_at->translatedFormat('d M Y, H:i') }} WIB
                        </p>
                    @endif
                </div>

                @if ($isPaid)
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Lunas (100% Terverifikasi)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        Menunggu Pembayaran
                    </span>
                @endif
            </div>

            {{-- Item Summary Box --}}
            <div class="mt-4 flex flex-wrap items-center justify-between gap-4 rounded-lg border border-slate-200/70 bg-slate-50/70 p-4">
                <div class="flex items-start gap-3.5">
                    <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-white">
                        @if ($product?->resolved_image_url)
                            <img src="{{ $product->resolved_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-[#00877A]/10 text-[#00877A]">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                    <path d="M9 2v4M15 2v4M9 18v4M15 18v4" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-sm font-extrabold text-slate-900">{{ $product?->name ?? 'Produk Ready Stock PNJ' }}</h2>
                            <span class="rounded bg-slate-200/80 px-2 py-0.5 text-[10px] font-bold text-slate-700">Ready Stock &bull; Barang Fisik</span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-600">
                            Pembelian Langsung ({{ $firstItem?->quantity ?? 1 }} Unit) &bull; {{ (float) ($invoice?->shipping_cost ?? 0) > 0 ? 'Pengiriman JNE Reguler (Jabodetabek)' : 'Ambil Mandiri di Gedung AA Lt. 2' }}
                        </p>
                        <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] font-medium text-slate-500">
                            <span class="tabular-nums">Harga Barang: <strong class="text-slate-700">Rp {{ number_format((float) ($invoice?->dpp ?? 0), 0, ',', '.') }}</strong></span>
                            <span class="tabular-nums">Ongkir: <strong class="text-slate-700">Rp {{ number_format((float) ($invoice?->shipping_cost ?? 0), 0, ',', '.') }}</strong></span>
                            <span class="tabular-nums">PPN 11%: <strong class="text-slate-700">Rp {{ number_format((float) ($invoice?->ppn_amount ?? 0), 0, ',', '.') }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-[11px] font-medium text-slate-500">Total Tagihan (100% Lunas):</p>
                    <p class="tabular-nums text-2xl font-extrabold text-[#00877A]">
                        Rp {{ number_format((float) ($invoice?->grand_total ?? 0), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Virtual Account Mandiri BLU Box --}}
            <div class="mt-4 rounded-lg border border-slate-200/80 bg-white p-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50 text-xs font-extrabold text-sky-700">BM</span>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Bank Mandiri (BLU PNJ)</p>
                            <p class="text-[11px] text-slate-500">Mandiri Virtual Account (MVA) &mdash; Tanpa DP 50%</p>
                        </div>
                    </div>
                    <span class="rounded-md bg-sky-50 px-2.5 py-1 text-[10px] font-semibold text-sky-700">Verifikasi Instan 24 Jam</span>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200/70 bg-slate-50 px-4 py-3">
                    <div>
                        <p class="text-[10px] font-medium text-slate-500">Nomor Virtual Account</p>
                        <p class="mt-0.5 tabular-nums text-xl font-extrabold tracking-wider text-slate-900">{{ $vaFormatted }}</p>
                    </div>
                    <button type="button" data-copy-va="{{ $vaRaw }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2" />
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                        </svg>
                        <span>Salin No. VA</span>
                    </button>
                </div>

                <div class="mt-3 space-y-1.5 text-[11px] text-slate-600">
                    <p class="font-bold text-slate-800">Petunjuk Transfer Singkat Melalui Livin' by Mandiri / ATM:</p>
                    <p><span class="mr-1.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A]/10 text-[10px] font-bold text-[#00877A]">1</span>Buka aplikasi m-Banking Livin' by Mandiri, lalu pilih menu <strong>Bayar / Beli &gt; Multipayment</strong>.</p>
                    <p><span class="mr-1.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A]/10 text-[10px] font-bold text-[#00877A]">2</span>Pilih penyedia jasa dengan memasukkan Kode Perusahaan BLU PNJ: <strong class="tabular-nums">88770</strong>.</p>
                    <p><span class="mr-1.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A]/10 text-[10px] font-bold text-[#00877A]">3</span>Masukkan Nomor VA di atas (<strong class="tabular-nums">{{ $vaFormatted }}</strong>) dan pastikan nama mahasiswa &amp; nominal tepat <strong class="tabular-nums">Rp {{ number_format((float) ($invoice?->grand_total ?? 0), 0, ',', '.') }}</strong>.</p>
                </div>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="mt-4 flex flex-wrap items-center justify-end gap-2.5">
                @if (! $isPaid)
                    <button type="button" id="open-rs-qris-btn"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                            <path d="M14 14h3v3h-3zM18 18h3v3h-3z" />
                        </svg>
                        <span>Ubah Metode Pembayaran (QRIS)</span>
                    </button>

                    <form action="{{ route('b2c.ready-stock.konfirmasi', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="va_mandiri_blu">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] hover:shadow-md active:scale-95">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                                <path d="M21 3v5h-5" />
                            </svg>
                            <span>Cek Status Pembayaran</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('b2c.pesanan.show', $order) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                        Lihat Detail &amp; e-Nota Lunas &rarr;
                    </a>
                @endif
            </div>
        </div>
    </article>
</div>

{{-- Modal 04b.2: Pembayaran QRIS Ready Stock --}}
<div id="rs-qris-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="rs-qris-title">
    <div class="w-full max-w-sm overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <div class="flex items-center gap-2">
                <span class="rounded bg-rose-600 px-2 py-0.5 text-[10px] font-extrabold tracking-wider text-white">QRIS</span>
                <h3 id="rs-qris-title" class="text-sm font-bold text-slate-900">Pembayaran QRIS</h3>
            </div>
            <button type="button" data-close-rs-qris
                    class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Tutup modal QRIS">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-3 p-5">
            <div class="flex items-center justify-between rounded-lg border border-slate-200/80 bg-slate-50/70 px-3.5 py-2.5">
                <div>
                    <p class="text-[10px] font-medium text-slate-500">Total Tagihan</p>
                    <p class="tabular-nums text-xl font-extrabold text-[#00877A]">Rp {{ number_format((float) ($invoice?->grand_total ?? 0), 0, ',', '.') }}</p>
                </div>
                <span class="rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">&bull; BLU PNJ</span>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-3.5 text-center shadow-xs">
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                    <span class="text-slate-900">QRIS</span>
                    <span>STANDAR NASIONAL</span>
                </div>
                <div class="my-2.5 flex justify-center">
                    <svg id="rs-qris-svg" width="152" height="152" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg" class="rounded bg-white">
                        <rect width="29" height="29" fill="#FFFFFF" />
                        <path fill="#0F172A" d="M2 2h7v7H2V2zm1 1v5h5V3H3zm1 1h3v3H4V4zm16-2h7v7h-7V2zm1 1v5h5V3h-5zm1 1h3v3h-3V4zM2 20h7v7H2v-7zm1 1v5h5v-5H3zm1 1h3v3H4v-3zM11 2h2v2h-2V2zm4 0h3v2h-3V2zm-4 4h4v2h-4V6zm6 0h1v3h-1V6zm-6 4h2v3h-2v-3zm4 1h3v2h-3v-2zM2 11h3v2H2v-2zm5 0h2v2H7v-2zm-5 4h2v3H2v-3zm4 1h3v2H6v-2zm5-1h3v3h-3v-3zm5-4h3v3h-3v-3zm5 0h6v2h-6v-2zm-1 4h2v3h-2v-3zm4 0h3v2h-3v-2zm-12 5h3v2h-3v-2zm5 0h2v4h-2v-4zm-4 4h3v3h-3v-3zm5 1h3v2h-3v-2zm4-5h3v3h-3v-3zm4 2h2v5h-2v-5zm-4 3h2v2h-2v-2z"/>
                        <rect x="13" y="13" width="3" height="3" rx="0.5" fill="#00877A" />
                    </svg>
                </div>
                <p class="tabular-nums text-[10px] font-medium text-slate-400">NMID: ID1020084920251</p>
            </div>

            <div class="text-center">
                <button type="button" id="rs-download-qris-btn"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Unduh Gambar QRIS
                </button>
            </div>

            <div class="flex items-center justify-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 py-1.5 text-xs font-bold text-amber-700">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                </svg>
                <span>Sisa waktu: <span id="rs-qris-timer" class="tabular-nums">14:59</span></span>
            </div>

            <p class="text-center text-[10px] text-slate-500">Buka aplikasi m-banking atau e-wallet Anda, lalu scan QR Code di atas.</p>

            {{-- Polesan 1: Logo Brand Berwarna 8 Kanal QRIS --}}
            @include('Mahasiswa atau Dosen (B2C).partials.qris-logos')

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button type="button" data-close-rs-qris
                        class="rounded-lg border border-[#00877A] bg-white py-2.5 text-xs font-bold text-[#00877A] hover:bg-[#00877A]/5 active:scale-95">
                    Batal
                </button>
                <form action="{{ route('b2c.ready-stock.konfirmasi', $order) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="qris">
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                            <path d="M21 3v5h-5" />
                        </svg>
                        <span>Cek Status Pembayaran</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal 04b.3: Sukses Pembayaran Ready Stock --}}
@if ($paidModalData)
<div id="rs-success-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="rs-success-title">
    <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="p-5">
            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('rs-success-modal')?.remove()"
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Tutup">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-[#00877A] text-white shadow-md">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <span class="mt-2.5 inline-flex items-center gap-1 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">
                    ESCROW BLU PNJ &bull; TRANSAKSI TERVERIFIKASI REAL-TIME
                </span>
                <h3 id="rs-success-title" class="mt-2 text-lg font-extrabold text-slate-900">Pembayaran Berhasil Diverifikasi!</h3>
                <p class="mt-1 text-xs text-slate-500">
                    Pelunasan Ready Stock (<strong>{{ $paidModalData['order_code'] }}</strong>) telah diverifikasi otomatis oleh Kas BLU PNJ. Pesanan Anda segera disiapkan oleh Gudang RTPU.
                </p>
            </div>

            <div class="mt-4 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 text-xs">
                <div class="flex items-center justify-between border-b border-slate-200/70 pb-2.5">
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase">Total Dana Terbayar</p>
                        <p class="tabular-nums text-xl font-extrabold text-slate-900">Rp {{ number_format($paidModalData['grand_total'], 0, ',', '.') }}</p>
                    </div>
                    <span class="rounded-full bg-[#00877A] px-2.5 py-1 text-[10px] font-bold text-white">&#10003; LUNAS (100%)</span>
                </div>

                <div class="mt-2.5 grid grid-cols-2 gap-2 text-[11px]">
                    <div>
                        <p class="text-slate-400">No. Invoice</p>
                        <p class="tabular-nums font-bold text-[#00877A]">{{ $paidModalData['invoice_number'] }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Waktu Pembayaran</p>
                        <p class="tabular-nums font-bold text-slate-800">{{ $paidModalData['paid_at'] }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Metode Pembayaran</p>
                        <p class="font-bold text-slate-800">{{ $paidModalData['payment_method'] }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">ID Transaksi</p>
                        <p class="tabular-nums font-bold text-slate-800">{{ $paidModalData['trx_id'] }}</p>
                    </div>
                </div>

                <div class="mt-2.5 flex items-center justify-between rounded-md border border-slate-200 bg-white px-3 py-2 text-[11px]">
                    <div>
                        <p class="font-bold text-slate-800">{{ $paidModalData['product_name'] }} ({{ $paidModalData['quantity'] }} Unit)</p>
                        <p class="text-[10px] text-slate-400">SKU: {{ $paidModalData['product_code'] }} &bull; Ready Stock</p>
                    </div>
                    <span class="tabular-nums font-extrabold text-slate-900">Rp {{ number_format($paidModalData['grand_total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2.5">
                <a href="{{ route('b2c.pesanan.show', $order) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    Lihat e-Nota Lunas
                </a>
                <a href="{{ route('b2c.pesanan') }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    <span>Pantau di Pesanan Saya</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="mt-2.5 text-center">
                <a href="{{ route('b2c.katalog') }}" class="text-[11px] font-semibold text-slate-500 hover:text-[#00877A]">Kembali ke Katalog Utama</a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        // Salin VA
        document.querySelectorAll('[data-copy-va]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(btn.dataset.copyVa);
                    const span = btn.querySelector('span');
                    if (span) {
                        const prev = span.textContent;
                        span.textContent = 'Tersalin!';
                        setTimeout(() => { span.textContent = prev; }, 1600);
                    }
                } catch (_) {}
            });
        });

        // Modal QRIS Ready Stock (04b.2)
        const modal = document.getElementById('rs-qris-modal');
        const openBtn = document.getElementById('open-rs-qris-btn');
        const timerEl = document.getElementById('rs-qris-timer');
        let timerId = null;

        const openQris = () => {
            clearInterval(timerId);
            let secs = 14 * 60 + 59;
            const tick = () => {
                const m = String(Math.floor(secs / 60)).padStart(2, '0');
                const s = String(secs % 60).padStart(2, '0');
                if (timerEl) timerEl.textContent = `${m}:${s}`;
                if (secs > 0) secs--;
            };
            tick();
            timerId = setInterval(tick, 1000);
            modal?.classList.remove('hidden');
            modal?.classList.add('flex');
        };
        const closeQris = () => {
            clearInterval(timerId);
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        };

        openBtn?.addEventListener('click', openQris);
        document.querySelectorAll('[data-close-rs-qris]').forEach((el) => el.addEventListener('click', closeQris));
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeQris();
        });

        document.getElementById('rs-download-qris-btn')?.addEventListener('click', () => {
            const svg = document.getElementById('rs-qris-svg');
            if (!svg) return;
            const blob = new Blob([new XMLSerializer().serializeToString(svg)], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'QRIS-ReadyStock-BLU-PNJ.svg';
            a.click();
            URL.revokeObjectURL(url);
        });
    })();
</script>
@endpush
