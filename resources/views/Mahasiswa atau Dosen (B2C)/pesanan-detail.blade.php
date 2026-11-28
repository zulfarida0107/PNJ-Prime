@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Detail Pesanan & Pembayaran — ' . $order->order_code)
@section('nav', 'pesanan')

@section('content')
@php
    $invoice = $order->invoice;
    $firstItem = $order->items->first();
    $product = $firstItem?->product;
    $isPaid = $invoice && $invoice->payment_status === 'paid';
    $isLab = $order->order_type === 'lab_reservation';
    $passCode = $invoice?->qr_pass_code ?: ('PASS-LAB-' . now()->format('Y') . '-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT));
    $vaRaw = $invoice?->va_number ?: '887702107411032';
    $vaFormatted = trim(chunk_split($vaRaw, 4, ' '));
@endphp

<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <a href="{{ route('b2c.pesanan') }}" class="hover:text-[#00877A]">Pesanan Saya</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Detail Pesanan &amp; Pembayaran</span>
            </nav>
            <div class="mt-1 flex items-center gap-2.5">
                <a href="{{ route('b2c.pesanan') }}"
                   class="no-print flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-[#00877A] hover:text-[#00877A] active:scale-95"
                   aria-label="Kembali">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Detail Pesanan &amp; Pembayaran</h1>
                    <p class="text-xs text-slate-500">Rincian komprehensif transaksi pemesanan fasilitas riset laboratorium dan verifikasi pembayaran BLU PNJ.</p>
                </div>
            </div>
        </div>

        <button type="button" onclick="window.print()"
                class="no-print inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-xs transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9" />
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                <rect x="6" y="14" width="12" height="8" />
            </svg>
            <span>Cetak Bukti Transaksi</span>
        </button>
    </div>

    {{-- Top Invoice Banner --}}
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border-l-4 border-[#00877A] border-y border-r border-y-slate-200/80 border-r-slate-200/80 bg-white px-5 py-4 shadow-xs">
        <div class="flex flex-wrap items-center gap-6">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Nomor Invoice</p>
                <p class="mt-0.5 tabular-nums text-base font-extrabold text-slate-900">{{ $invoice?->invoice_number ?? $order->order_code }}</p>
            </div>
            <div class="border-l border-slate-200 pl-6">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Waktu Transaksi</p>
                <p class="mt-0.5 tabular-nums text-xs font-bold text-slate-800">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
            <div class="border-l border-slate-200 pl-6">
                <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Kode Pesanan</p>
                <p class="mt-0.5 tabular-nums text-xs font-bold text-slate-800">{{ $order->order_code }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if ($isPaid)
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    Lunas / Terverifikasi
                </span>
                @if ($invoice?->paid_at)
                    <p class="text-[11px] text-slate-500">Diverifikasi pada<br><strong class="text-slate-700">{{ $invoice->paid_at->translatedFormat('d M Y, H:i') }} WIB</strong></p>
                @endif
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1.5 text-xs font-bold text-amber-700">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    {{ $order->status === 'dibatalkan' ? 'Dibatalkan' : 'Menunggu Pembayaran' }}
                </span>
            @endif
        </div>
    </div>

    {{-- Grid Konten 2 Kolom --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Kolom Kiri (8 cols): Item & Kontak Pemesan --}}
        <div class="space-y-4 lg:col-span-8">
            {{-- Informasi Item & Fasilitas Riset --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="14" rx="2" />
                                <path d="M7 21h10M12 17v4" />
                            </svg>
                        </span>
                        <h2 class="text-sm font-extrabold text-slate-900">Informasi Item &amp; Fasilitas Riset</h2>
                    </div>
                    <span class="rounded-full bg-[#00877A]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">
                        {{ $isLab ? '1 Sesi Terjadwal' : ($firstItem?->quantity ?? 1) . ' Unit Dipesan' }}
                    </span>
                </div>

                @foreach ($order->items as $item)
                    <div class="mt-4 flex flex-col gap-4 sm:flex-row">
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200/70 bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/70 text-[#00877A]">
                            @if ($item->product?->resolved_image_url)
                                <img src="{{ $item->product->resolved_image_url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                            @else
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="14" rx="2" />
                                    <path d="M7 21h10M12 17v4M8 9h8" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="rounded bg-[#00877A]/10 px-2 py-0.5 text-[10px] font-bold text-[#00877A]">
                                    {{ $item->product?->unit?->name ?? 'UPA-RTPU PNJ' }}
                                </span>
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                    {{ $item->product?->category?->name ?? 'Riset Terapan' }}
                                </span>
                            </div>
                            <h3 class="mt-1.5 text-base font-extrabold text-slate-900">{{ $item->product?->name ?? 'Produk Riset' }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                {{ $item->product?->description }}
                            </p>
                        </div>
                    </div>
                @endforeach

                {{-- Grid Detail Operasional --}}
                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-200/70 bg-slate-50/70 p-3">
                        <p class="text-[10px] font-semibold text-slate-400">{{ $isLab ? 'Jadwal Pemakaian Lab' : 'Estimasi Penyelesaian' }}</p>
                        <p class="mt-0.5 text-xs font-bold text-slate-800">
                            @if ($order->booking_date)
                                {{ $order->booking_date->translatedFormat('l, d F Y') }} ({{ $order->booking_slot }})
                            @else
                                {{ $order->estimasi_hari ?? 1 }} Hari Kerja
                            @endif
                        </p>
                    </div>
                    <div class="rounded-lg border border-slate-200/70 bg-slate-50/70 p-3">
                        <p class="text-[10px] font-semibold text-slate-400">Lokasi Laboratorium / Pengiriman</p>
                        <p class="mt-0.5 text-xs font-bold text-slate-800">{{ $order->shipping_address ?: 'Gedung UPA-RTPU Kampus PNJ Depok' }}</p>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="mt-3 rounded-lg border border-sky-200/80 bg-sky-50/60 p-3 text-xs text-slate-700">
                        <span class="font-bold text-[#006E63]">Catatan &amp; Keperluan Riset:</span> {{ $order->notes }}
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <span class="font-medium text-slate-500">Subtotal Item</span>
                    <span class="tabular-nums text-sm font-extrabold text-slate-900">
                        Rp {{ number_format((float) $order->items->sum('subtotal'), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Informasi Kontak Pemesan (Mahasiswa / Dosen) --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                    <h2 class="text-sm font-extrabold text-slate-900">Informasi Kontak Pemesan (Sivitas PNJ)</h2>
                </div>

                <div class="mt-3.5 grid grid-cols-1 gap-4 text-xs sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] text-slate-400">Nama Lengkap &amp; NIM/NIP</p>
                        <p class="mt-0.5 font-bold text-slate-900">{{ $portalUser->name }}</p>
                        <p class="tabular-nums text-[11px] text-slate-500">ID: {{ $portalUser->nim_nip ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400">Program Studi / Unit</p>
                        <p class="mt-0.5 font-bold text-slate-900">{{ $portalUser->prodi_unit ?? 'Politeknik Negeri Jakarta' }}</p>
                        <p class="text-[11px] text-slate-500">Politeknik Negeri Jakarta</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400">Email &amp; WhatsApp</p>
                        <p class="mt-0.5 font-bold text-slate-800">{{ $portalUser->email }}</p>
                        <p class="tabular-nums text-[11px] text-slate-500">{{ $portalUser->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400">Status Otorisasi Akun</p>
                        <p class="mt-0.5 inline-flex items-center gap-1 font-bold text-[#00877A]">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                            Terverifikasi SSO Kampus PNJ
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan (4 cols): Metode Pembayaran & Rincian Biaya + Tombol QR Pass --}}
        <div class="space-y-4 lg:col-span-4">
            {{-- Metode & Status Pembayaran --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2.5" />
                        </svg>
                    </span>
                    <h2 class="text-sm font-extrabold text-slate-900">Metode &amp; Status Pembayaran</h2>
                </div>

                <div class="mt-3.5 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-extrabold text-slate-900">
                            {{ $invoice?->payment_method === 'qris' ? 'QRIS Dinamis BLU PNJ' : 'Bank Mandiri Virtual Account' }}
                        </p>
                        <span class="rounded bg-[#00877A]/10 px-2 py-0.5 text-[10px] font-bold text-[#00877A]">BLU PNJ</span>
                    </div>
                    @if ($invoice?->payment_method !== 'qris')
                        <p class="mt-2 text-[10px] text-slate-400">Nomor Virtual Account (MVA)</p>
                        <p class="mt-0.5 tabular-nums text-sm font-extrabold tracking-wider text-slate-900">{{ $vaFormatted }}</p>
                    @endif
                </div>

                <div class="mt-3.5 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Status Transaksi</span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $isPaid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $isPaid ? 'Lunas 100% (Settlement)' : 'Menunggu Pembayaran' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">ID Transaksi BLU</span>
                        <span class="tabular-nums font-bold text-slate-800">{{ $invoice?->invoice_number ?? '-' }}</span>
                    </div>
                    @if ($invoice?->paid_at)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Waktu Pembayaran</span>
                            <span class="tabular-nums font-semibold text-slate-700">{{ $invoice->paid_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rincian Biaya & Tombol Aksi --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <h2 class="text-sm font-extrabold text-slate-900">Rincian Biaya</h2>

                <div class="mt-3 space-y-2 border-b border-slate-100 pb-3 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Dasar Pengenaan (DPP)</span>
                        <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format((float) ($invoice?->dpp ?? 0), 0, ',', '.') }}</span>
                    </div>
                    @if ((float) ($invoice?->ppn_amount ?? 0) > 0)
                        <div class="flex items-center justify-between text-slate-600">
                            <span>PPN</span>
                            <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format((float) $invoice->ppn_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Biaya Layanan BLU PNJ</span>
                        <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Rp 0</span>
                    </div>
                </div>

                <div class="mt-3">
                    <p class="text-[11px] font-semibold text-slate-500">Total Pembayaran (100% Lunas)</p>
                    <p class="mt-0.5 tabular-nums text-2xl font-extrabold text-[#00877A]">
                        Rp {{ number_format((float) ($invoice?->grand_total ?? 0), 0, ',', '.') }}
                    </p>
                </div>

                <div class="no-print mt-4 space-y-2">
                    @if ($isPaid)
                        <button type="button" id="open-qrpass-modal-btn"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] hover:shadow-md active:scale-95">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                                <path d="M14 14h3v3h-3zM18 18h3v3h-3z" />
                            </svg>
                            <span>Lihat QR Pass Akses Lab</span>
                        </button>
                        <button type="button" onclick="window.print()"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M8 13h8M8 17h5" />
                            </svg>
                            <span>Unduh E-Nota Resmi</span>
                        </button>
                    @elseif ($order->status !== 'dibatalkan')
                        <a href="{{ route('b2c.pembayaran') }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] active:scale-95">
                            <span>Bayar Sekarang (VA / QRIS)</span>
                        </a>
                    @endif
                </div>

                <div class="mt-3.5 rounded-lg border border-slate-200/70 bg-slate-50 p-2.5 text-[10px] leading-relaxed text-slate-500">
                    Transaksi ini sah dan terdaftar resmi pada Sistem BLU Politeknik Negeri Jakarta.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 07a: QR Pass Akses Lab (Tersedia setelah pembayaran terkonfirmasi) --}}
@if ($isPaid)
<div id="b2c-qrpass-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="qrpass-modal-title">
    <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        {{-- Teal Header --}}
        <div class="flex items-start justify-between bg-[#00877A] px-5 py-4 text-white">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-teal-100 uppercase">Pass Digital Akses Laboratorium</p>
                <h3 id="qrpass-modal-title" class="mt-0.5 text-sm font-extrabold">Politeknik Negeri Jakarta &bull; UPA-RTPU</h3>
                <span class="mt-1.5 inline-flex items-center gap-1 rounded-full bg-white/20 px-2.5 py-0.5 text-[10px] font-bold">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 5 5L20 7" />
                    </svg>
                    TERVERIFIKASI &amp; AKTIF
                </span>
            </div>
            <button type="button" data-close-qrpass
                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/10 text-white hover:bg-white/20"
                    aria-label="Tutup QR Pass">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-5">
            {{-- QR Code Box --}}
            <div class="mx-auto flex w-fit flex-col items-center rounded-xl border-2 border-dashed border-[#00877A]/30 bg-slate-50/60 p-4">
                <svg id="qrpass-svg-code" width="168" height="168" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="29" height="29" fill="#FFFFFF" />
                    <path fill="#0F172A" d="M2 2h7v7H2V2zm1 1v5h5V3H3zm1 1h3v3H4V4zm16-2h7v7h-7V2zm1 1v5h5V3h-5zm1 1h3v3h-3V4zM2 20h7v7H2v-7zm1 1v5h5v-5H3zm1 1h3v3H4v-3zM11 2h3v2h-3V2zm5 1h2v3h-2V3zm-5 4h2v2h-2V7zm4 1h3v2h-3V8zm-3 3h2v2h-2v-2zm4 0h3v3h-3v-3zM2 11h2v3H2v-3zm4 1h3v2H6v-2zm-4 4h3v2H2v-2zm5 0h2v3H7v-3zm14-5h6v2h-6v-2zm0 4h3v3h-3v-3zm5 1h2v3h-2v-3zm-15 4h3v2h-3v-2zm5 1h3v2h-3v-2zm-5 3h2v3h-2v-3zm4 1h4v2h-4v-2zm6-4h2v4h-2v-4zm4 1h3v2h-3v-2zm-1 4h4v2h-4v-2z"/>
                    <rect x="13" y="13" width="3" height="3" rx="0.5" fill="#00877A" />
                </svg>
            </div>

            {{-- Pass Code Pill --}}
            <div class="mt-3 flex items-center justify-center gap-2">
                <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1 tabular-nums text-xs font-extrabold text-slate-800">
                    #{{ $passCode }}
                </span>
                <button type="button" id="copy-passcode-btn" data-passcode="{{ $passCode }}"
                        class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-[#00877A] hover:bg-[#00877A]/5 active:scale-95">
                    Salin
                </button>
            </div>

            <div class="mt-3 text-center">
                <p class="text-sm font-extrabold text-slate-900">{{ $product?->name ?? 'Reservasi Fasilitas Lab PNJ' }}</p>
                <p class="mt-0.5 text-[11px] text-slate-500">{{ $product?->unit?->name ?? 'UPA-RTPU Kampus PNJ Depok' }}</p>
            </div>

            {{-- Ticket Grid --}}
            <div class="mt-4 grid grid-cols-2 gap-2.5 border-t border-dashed border-slate-200 pt-4 text-xs">
                <div class="rounded-lg border border-slate-200/70 bg-slate-50/60 p-2.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Jadwal / Tipe</p>
                    <p class="mt-0.5 font-bold text-slate-800">
                        {{ $order->booking_date ? $order->booking_date->translatedFormat('d M Y') : 'Pengambilan RTPU' }}
                    </p>
                    <p class="text-[10px] text-slate-500">{{ $order->booking_slot ?? ($order->order_code) }}</p>
                </div>
                <div class="rounded-lg border border-slate-200/70 bg-slate-50/60 p-2.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Pemegang Tiket</p>
                    <p class="mt-0.5 truncate font-bold text-slate-800">{{ $portalUser->name }}</p>
                    <p class="tabular-nums text-[10px] text-slate-500">ID: {{ $portalUser->nim_nip ?? '-' }}</p>
                </div>
                <div class="rounded-lg border border-slate-200/70 bg-slate-50/60 p-2.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Status Bayar</p>
                    <p class="mt-0.5 tabular-nums font-extrabold text-[#00877A]">Rp {{ number_format((float) $invoice->grand_total, 0, ',', '.') }}</p>
                    <p class="text-[10px] font-bold text-emerald-700">LUNAS 100%</p>
                </div>
                <div class="rounded-lg border border-slate-200/70 bg-slate-50/60 p-2.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Nomor Invoice</p>
                    <p class="mt-0.5 truncate tabular-nums font-bold text-slate-800">{{ $invoice->invoice_number }}</p>
                    <p class="text-[10px] text-slate-500">BLU PNJ Verified</p>
                </div>
            </div>

            {{-- K3 Notice --}}
            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-2.5 text-[11px] leading-relaxed text-amber-800">
                <strong>Protokol K3 &amp; Akses Lab:</strong> Wajib menunjukkan QR Pass ini beserta KTM / kartu pegawai asli kepada laboran di pintu masuk dan mematuhi SOP K3.
            </div>

            {{-- Footer Actions --}}
            <div class="mt-4 grid grid-cols-2 gap-2.5">
                <button type="button" id="download-qrpass-btn"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-2 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    <span>Simpan QR Pass</span>
                </button>
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    <span>Unduh e-Tiket (PDF)</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('b2c-qrpass-modal');
        const openBtn = document.getElementById('open-qrpass-modal-btn');

        const openModal = () => {
            modal?.classList.remove('hidden');
            modal?.classList.add('flex');
        };
        const closeModal = () => {
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        };

        openBtn?.addEventListener('click', openModal);
        document.querySelectorAll('[data-close-qrpass]').forEach((b) => b.addEventListener('click', closeModal));
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        const copyBtn = document.getElementById('copy-passcode-btn');
        copyBtn?.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(copyBtn.dataset.passcode || '');
                copyBtn.textContent = 'Tersalin!';
                setTimeout(() => { copyBtn.textContent = 'Salin'; }, 1500);
            } catch (_) {}
        });

        document.getElementById('download-qrpass-btn')?.addEventListener('click', () => {
            const svg = document.getElementById('qrpass-svg-code');
            if (!svg) return;
            const data = new XMLSerializer().serializeToString(svg);
            const blob = new Blob([data], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `QR-PASS-LAB-PNJ.svg`;
            a.click();
            URL.revokeObjectURL(url);
        });
    })();
</script>
@endpush
