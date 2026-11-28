@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Detail Pre-Order #' . $order->order_code)
@section('nav', 'pesanan')

@section('content')
@php
    // Mapping 5 State Pre-Order dalam 1 file Blade (State A–E)
    $state = match ($effectiveStatus) {
        'menunggu_verifikasi' => 'A',
        'menunggu_pembayaran' => 'B',
        'diproses' => 'C',
        'siap_diambil' => 'D',
        'selesai' => 'E',
        default => 'A',
    };

    $stepLevel = match ($state) {
        'A' => 2,
        'B' => 3,
        'C', 'D' => 4,
        'E' => 5,
    };

    $invNumber = $invoice?->invoice_number ?? ('INV/' . now()->format('Y') . '/BLU-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT));
    $pickupCode = 'PICKUP-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT);
    $resiNumber = 'JNE-PNJ-' . now()->format('Y') . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT);
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
                <span class="font-semibold text-slate-700">Detail Pre-Order #{{ $order->order_code }}</span>
            </nav>
            <div class="mt-1 flex items-center gap-2.5">
                <a href="{{ route('b2c.pesanan') }}"
                   class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-[#00877A] hover:text-[#00877A] active:scale-95"
                   aria-label="Kembali">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Detail Pre-Order</h1>
                    <p class="text-xs text-slate-500">Lacak status verifikasi lab, e-Invoice, perakitan, hingga serah terima perangkat Anda.</p>
                </div>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-3 py-1 text-[11px] font-semibold text-[#00877A]">
            <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
            Sinkronisasi Layanan Lab RTPU Aktif
        </span>
    </div>

    {{-- ===================================================================== --}}
    {{-- STATUS HERO CARD & 4-STEP TRACKER (KONDISI @if STATE A - E)           --}}
    {{-- ===================================================================== --}}
    @if ($state === 'A')
        {{-- STATE A (04c.2): Menunggu Verifikasi Admin RTPU --}}
        <section class="rounded-xl border-2 border-amber-300/90 bg-white p-5 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-0.5 text-[11px] font-bold text-amber-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        Menunggu Verifikasi Admin RTPU
                    </span>
                    <h2 class="mt-2 text-lg font-extrabold text-slate-900">Pengajuan Pre-Order Sedang Dicek Lab</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Admin RTPU &amp; teknisi {{ $product?->unit?->name ?? 'Laboratorium PNJ' }} sedang mengecek ketersediaan antrean produksi serta menghitung ongkos kirim final (maks. 1&times;24 jam kerja).</p>
                </div>
                <div class="text-right">
                    <span class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 tabular-nums text-xs font-extrabold text-slate-800">#{{ $order->order_code }}</span>
                    <p class="mt-1 tabular-nums text-[11px] text-slate-400">Diajukan: {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                </div>
            </div>
    @elseif ($state === 'B')
        {{-- STATE B (04c.3): e-Invoice Terbit / Menunggu Pembayaran --}}
        <section class="rounded-xl border border-[#00877A]/40 bg-white p-5 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-3 py-0.5 text-[11px] font-bold text-[#00877A]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#00877A]"></span>
                        Disetujui &bull; Menunggu Pembayaran (100% Lunas)
                    </span>
                    <h2 class="mt-2 text-lg font-extrabold text-slate-900">e-Invoice Resmi Telah Terbit!</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Kapasitas produksi disetujui oleh {{ $product?->unit?->name ?? 'Lab PNJ' }}. Silakan periksa e-Invoice resmi dan lakukan pelunasan 100% sebelum batas waktu agar perakitan segera dimulai.</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 tabular-nums text-[10px] font-bold text-amber-700">
                        Batas Bayar: {{ ($invoice?->due_at ?? now()->addDay())->translatedFormat('d M Y, 23:59') }} WIB
                    </span>
                    <p class="mt-1.5 tabular-nums text-[11px] font-bold text-slate-700">#{{ $invNumber }} &bull; #{{ $order->order_code }}</p>
                </div>
            </div>
    @elseif ($state === 'C')
        {{-- STATE C (04c.8): Pembayaran Lunas — Sedang Dalam Perakitan / Produksi --}}
        <section class="rounded-xl border border-[#00877A]/40 bg-white p-5 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-3 py-0.5 text-[11px] font-bold text-[#00877A]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#00877A]"></span>
                        Lunas &bull; Dalam Proses Perakitan Lab
                    </span>
                    <h2 class="mt-2 text-lg font-extrabold text-slate-900">Perangkat Riset Sedang Dirakit &amp; Dikalibrasi</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Pelunasan Anda telah terverifikasi oleh Kas BLU PNJ. Teknisi {{ $product?->unit?->name ?? 'Laboratorium PNJ' }} sedang mengerjakan perakitan dan uji akurasi perangkat.</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">&bull; LUNAS (100%)</span>
                    <p class="mt-1.5 tabular-nums text-[11px] font-bold text-slate-700">#{{ $invNumber }} &bull; #{{ $order->order_code }}</p>
                </div>
            </div>
    @elseif ($state === 'D')
        {{-- STATE D (04c.9): Selesai Dirakit / Siap Diambil --}}
        <section class="rounded-xl border border-[#00877A]/40 bg-white p-5 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-3 py-0.5 text-[11px] font-bold text-sky-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                        Lolos QC Lab &bull; Dalam Pengiriman / Siap Diambil
                    </span>
                    <h2 class="mt-2 text-lg font-extrabold text-slate-900">Perangkat Selesai Dirakit &amp; Siap Serah Terima!</h2>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $product?->name ?? 'Perangkat riset' }} telah lulus uji kalibrasi dan siap diambil di loket serah terima atau dikirim via ekspedisi.</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">&bull; LUNAS (100%)</span>
                    <p class="mt-1.5 tabular-nums text-[11px] font-bold text-slate-700">#{{ $invNumber }} &bull; #{{ $order->order_code }}</p>
                </div>
            </div>
    @else
        {{-- STATE E (04c.10): Pesanan Selesai --}}
        <section class="rounded-xl border border-emerald-300 bg-white p-5 shadow-xs">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#00877A] px-3 py-0.5 text-[11px] font-bold text-white">
                        &#10003; Pesanan Selesai
                    </span>
                    <h2 class="mt-2 text-lg font-extrabold text-slate-900">Perangkat Riset Telah Diterima dengan Baik</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Terima kasih telah menggunakan layanan inovasi RTPU Student PNJ. Semoga perangkat ini mendukung kelancaran praktikum dan riset Tugas Akhir Anda.</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 tabular-nums text-[10px] font-bold text-emerald-700">
                        &#10003; Selesai: {{ $order->updated_at->translatedFormat('d M Y, H:i') }} WIB
                    </span>
                    <p class="mt-1.5 tabular-nums text-[11px] font-bold text-slate-700">#{{ $invNumber }} &bull; #{{ $order->order_code }}</p>
                </div>
            </div>
    @endif

            {{-- 4-Step Progress Bar --}}
            <div class="mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4 sm:grid-cols-4">
                @php
                    $steps = [
                        1 => ['title' => 'Pengajuan Dikirim', 'sub' => $order->created_at->translatedFormat('d M Y, H:i')],
                        2 => ['title' => $state === 'A' ? 'Verifikasi Admin RTPU' : 'Diverifikasi Admin RTPU', 'sub' => $state === 'A' ? 'Sedang Berlangsung' : 'Disetujui Lab'],
                        3 => ['title' => $stepLevel >= 4 ? 'Pembayaran Lunas' : 'e-Invoice & Pembayaran', 'sub' => $state === 'A' ? 'Menunggu Verifikasi' : ($state === 'B' ? 'Menunggu Pelunasan' : 'Terverifikasi 100%')],
                        4 => ['title' => $state === 'E' ? 'Diterima & Selesai' : 'Perakitan & Pengiriman', 'sub' => match ($state) {
                            'C' => 'Tahap Perakitan Lab',
                            'D' => 'Siap Diambil / Dikirim',
                            'E' => $order->updated_at->translatedFormat('d M Y, H:i'),
                            default => 'Tahap Akhir',
                        }],
                    ];
                @endphp

                @foreach ($steps as $idx => $s)
                    @php
                        $isDone = $stepLevel > $idx || ($state === 'E');
                        $isCurrent = ! $isDone && $stepLevel === $idx;
                    @endphp
                    <div class="flex flex-col items-center text-center">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold {{ $isDone ? 'bg-[#00877A] text-white' : ($isCurrent ? ($state === 'A' ? 'bg-amber-500 text-white' : 'bg-[#00877A] text-white ring-4 ring-[#00877A]/15') : 'border border-slate-200 bg-slate-100 text-slate-400') }}">
                            @if ($isDone)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7" /></svg>
                            @else
                                {{ $idx }}
                            @endif
                        </span>
                        <p class="mt-2 text-xs font-bold {{ $isDone || $isCurrent ? 'text-slate-900' : 'text-slate-400' }}">{{ $s['title'] }}</p>
                        <p class="mt-0.5 text-[10px] font-medium {{ $isCurrent && $state === 'A' ? 'text-amber-600' : ($isDone || $isCurrent ? 'text-[#00877A]' : 'text-slate-400') }}">{{ $s['sub'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

    {{-- ===================================================================== --}}
    {{-- KONTEN 2 KOLOM (KIRI 8 COL + KANAN 4 COL)                             --}}
    {{-- ===================================================================== --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- KOLOM KIRI (8 COLS) --}}
        <div class="space-y-4 lg:col-span-8">

            {{-- PANEL KHUSUS STATE B: Dokumen e-Invoice Resmi BLU PNJ --}}
            @if ($state === 'B')
                <section class="rounded-lg border border-[#00877A]/30 bg-[#00877A]/[0.04] p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#00877A] text-white">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                </svg>
                            </span>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-sm font-extrabold text-slate-900">Dokumen e-Invoice Resmi BLU PNJ</h3>
                                    <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-[#00877A]">Terbit Sebelum Pembayaran</span>
                                </div>
                                <p class="mt-0.5 tabular-nums text-[11px] text-slate-600">No. <strong>{{ $invNumber }}</strong> &bull; Disetujui Admin RTPU</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" id="open-einvoice-modal-btn"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Lihat e-Invoice
                            </button>
                            <button type="button" onclick="window.print()"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-3 py-2 text-xs font-bold text-white hover:bg-[#006E63] active:scale-95">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
                                </svg>
                                Cetak PDF
                            </button>
                        </div>
                    </div>
                </section>
            @endif

            {{-- PANEL KHUSUS STATE C: Progres Perakitan Lab PNJ --}}
            @if ($state === 'C')
                <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Progres Perakitan {{ $product?->unit?->name ?? 'Lab PNJ' }}</h3>
                            <p class="text-[11px] text-slate-500">Sistem Monitoring Status Produksi &amp; Uji Presisi</p>
                        </div>
                        <span class="rounded-full bg-[#00877A]/10 px-3 py-1 tabular-nums text-xs font-extrabold text-[#00877A]">65% Selesai</span>
                    </div>

                    <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full w-[65%] rounded-full bg-[#00877A]"></div>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Tahap 2 dari 3: Perakitan Rangkaian &amp; Integrasi</span>
                        <span class="tabular-nums font-semibold text-slate-700">Estimasi Selesai: {{ now()->addDays(4)->translatedFormat('d M Y') }}</span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2.5 sm:grid-cols-3">
                        <div class="rounded-lg border border-emerald-200 bg-emerald-50/60 p-3">
                            <p class="text-[10px] font-bold text-[#00877A]">&#10003; Tahap 1</p>
                            <p class="mt-1 text-xs font-extrabold text-slate-900">Persiapan Komponen &amp; Casing</p>
                            <p class="mt-1 text-[10px] font-semibold text-emerald-700">Selesai Diverifikasi</p>
                        </div>
                        <div class="rounded-lg border-2 border-[#00877A] bg-[#00877A]/[0.04] p-3">
                            <p class="text-[10px] font-bold text-[#00877A]">&bull; Tahap 2 (Aktif)</p>
                            <p class="mt-1 text-xs font-extrabold text-slate-900">Perakitan Rangkaian &amp; Integrasi</p>
                            <p class="mt-1 text-[10px] font-semibold text-[#00877A]">Sedang Dikerjakan Teknisi</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50/70 p-3">
                            <p class="text-[10px] font-bold text-slate-400">Tahap 3</p>
                            <p class="mt-1 text-xs font-extrabold text-slate-500">Kalibrasi Presisi &amp; QC Akhir</p>
                            <p class="mt-1 text-[10px] text-slate-400">Menunggu Tahap 2</p>
                        </div>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                        <span>PIC Teknisi Lab: <strong class="text-slate-800">Bambang Sujarwo, S.T.</strong> ({{ $product?->unit?->name ?? 'Lab PNJ' }})</span>
                        <span class="rounded bg-slate-100 px-2 py-0.5 tabular-nums font-semibold text-slate-600">Workstation #LAB-EL04</span>
                    </div>
                </section>
            @endif

            {{-- PANEL KHUSUS STATE D: Informasi Pengiriman & Serah Terima Mahasiswa --}}
            @if ($state === 'D')
                <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Informasi Pengiriman &amp; Serah Terima Mahasiswa</h3>
                            <p class="text-[11px] text-slate-500">Tracking paket ekspedisi &amp; opsi pengambilan fisik di laboratorium</p>
                        </div>
                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">&#10003; Lolos Kalibrasi Lab</span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Ekspedisi Kurir</span>
                                <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Aktif</span>
                            </div>
                            <p class="mt-1 text-xs font-extrabold text-slate-900">JNE Reguler (Jabodetabek)</p>
                            <div class="mt-2 flex items-center justify-between rounded-md border border-slate-200 bg-white px-2.5 py-1.5">
                                <div>
                                    <p class="text-[9px] text-slate-400">Nomor Resi Pengiriman</p>
                                    <p class="tabular-nums text-xs font-extrabold text-slate-900">{{ $resiNumber }}</p>
                                </div>
                                <button type="button" data-copy-text="{{ $resiNumber }}"
                                        class="rounded border border-slate-200 px-2 py-1 text-[10px] font-bold text-[#00877A] hover:bg-[#00877A]/5 active:scale-95">
                                    Salin
                                </button>
                            </div>
                        </div>

                        <div class="rounded-lg border border-[#00877A]/30 bg-[#00877A]/[0.04] p-3.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold tracking-wider text-[#00877A] uppercase">Opsi Ambil Mandiri di Kampus</span>
                                <span class="rounded bg-white px-1.5 py-0.5 text-[10px] font-bold text-[#00877A]">Tersedia</span>
                            </div>
                            <p class="mt-2 text-[10px] font-semibold text-slate-500">KODE PENGAMBILAN RESMI</p>
                            <p class="mt-0.5 tabular-nums text-lg font-extrabold text-[#00877A]">#{{ $pickupCode }}</p>
                            <p class="mt-1 text-[11px] text-slate-600">Lokasi: Loket Lab Terpadu Gedung C Lt. 1 (Tunjukkan KTM/NIM asli)</p>
                        </div>
                    </div>
                </section>
            @endif

            {{-- PANEL KHUSUS STATE E: Banner Ulasan Layanan Laboratorium --}}
            @if ($state === 'E')
                <section class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-[#00877A]/30 bg-[#00877A]/[0.05] p-4 shadow-xs">
                    <div>
                        <span class="text-[10px] font-bold tracking-wider text-[#00877A] uppercase">Ulasan Layanan Laboratorium</span>
                        <h3 class="mt-0.5 text-sm font-extrabold text-slate-900">
                            {{ $hasReviewed ? 'Terima Kasih atas Ulasan Anda!' : 'Bagaimana Kualitas Perangkat & Layanan Lab?' }}
                        </h3>
                        <p class="mt-0.5 text-xs text-slate-600">
                            {{ $hasReviewed ? 'Penilaian Anda telah tercatat untuk peningkatan kualitas riset & layanan lab PNJ.' : 'Bagikan pengalamanmu agar membantu mahasiswa dan dosen lain di lingkungan Politeknik Negeri Jakarta.' }}
                        </p>
                    </div>

                    @if (! $hasReviewed)
                        <button type="button" id="open-review-modal-btn"
                                class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            Tulis Ulasan &amp; Penilaian
                        </button>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                            &#10003; Ulasan Terkirim
                        </span>
                    @endif
                </section>
            @endif

            {{-- CARD BERSAMA: Rincian Produk Inovasi (Make-to-Order) --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-extrabold text-slate-900">Rincian Produk Inovasi (Make-to-Order)</h3>
                    <span class="rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">
                        {{ $state === 'E' ? 'Diterima • Garansi Kalibrasi Lab 30 Hari' : 'Pre-Order • Make-to-Order' }}
                    </span>
                </div>

                <div class="mt-4 flex flex-col gap-4 sm:flex-row">
                    {{-- Foto Produk (resolved_image_url) --}}
                    <div class="relative h-28 w-full shrink-0 overflow-hidden rounded-lg border border-slate-200/80 bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/70 sm:w-32">
                        @if ($product?->resolved_image_url)
                            <img src="{{ $product->resolved_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#00877A]/70">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 3h6M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <h4 class="text-base font-extrabold text-slate-900">{{ $product?->name ?? 'Produk Inovasi Pre-Order PNJ' }}</h4>
                        <p class="mt-1 text-xs leading-relaxed text-slate-600">
                            {{ $product?->description ?: 'Modul riset terapan hasil inovasi laboratorium Politeknik Negeri Jakarta.' }}
                        </p>
                        <div class="mt-2.5 flex flex-wrap items-center gap-2 text-[10px] font-bold">
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-slate-700">Jumlah: {{ $firstItem?->quantity ?? 1 }} Unit</span>
                            <span class="rounded bg-[#00877A]/10 px-2 py-0.5 text-[#00877A]">Estimasi: 5&ndash;7 Hari Kerja</span>
                            <span class="rounded bg-sky-50 px-2 py-0.5 text-sky-700">{{ $product?->unit?->name ?? 'Lab PNJ' }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CARD BERSAMA: Detail Pengiriman & Pemesan --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <h3 class="border-b border-slate-100 pb-3 text-sm font-extrabold text-slate-900">Detail Pengiriman &amp; Pemesan</h3>

                <div class="mt-3.5 grid grid-cols-1 gap-4 text-xs sm:grid-cols-2">
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Nama Pemesan</p>
                        <p class="mt-0.5 font-extrabold text-slate-900">{{ $portalUser->name }}</p>
                        <p class="tabular-nums text-[11px] text-slate-500">NIM/NIP: {{ $portalUser->nim_nip ?? '-' }} &bull; {{ $portalUser->prodi_unit ?? 'PNJ' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Kontak Mahasiswa / Dosen</p>
                        <p class="mt-0.5 tabular-nums font-extrabold text-slate-900">{{ $portalUser->phone ?? '-' }}</p>
                        <p class="text-[11px] text-slate-500">{{ $portalUser->email }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Alamat Tujuan Pengambilan / Kirim</p>
                        <p class="mt-0.5 text-xs font-medium text-slate-800">{{ $order->shipping_address ?: ($portalUser->address ?? 'Kampus Politeknik Negeri Jakarta, Depok') }}</p>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="mt-3.5 rounded-lg border border-amber-200/80 bg-amber-50/70 px-3.5 py-2.5 text-xs text-amber-900">
                        <p class="text-[10px] font-bold uppercase text-amber-700">Catatan &amp; Spesifikasi Pemesan</p>
                        <p class="mt-0.5 leading-relaxed">{{ $order->notes }}</p>
                    </div>
                @endif

                @if ($state === 'E')
                    <div class="mt-3.5 flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50/70 px-3.5 py-2.5 text-xs text-emerald-900">
                        <div>
                            <p class="text-[10px] font-bold uppercase text-emerald-700">&#10003; Bukti Serah Terima Resmi</p>
                            <p class="mt-0.5">Diterima oleh <strong>{{ $portalUser->name }}</strong> ({{ $order->updated_at->translatedFormat('d M Y, H:i') }} WIB) &bull; Resi: {{ $resiNumber }}</p>
                        </div>
                        <span class="rounded bg-white px-2 py-0.5 text-[10px] font-bold text-[#00877A]">Terverifikasi</span>
                    </div>
                @endif
            </section>
        </div>

        {{-- KOLOM KANAN (4 COLS): RINGKASAN BIAYA & AKSI SESUAI STATE A-E --}}
        <aside class="lg:col-span-4">
            <div class="sticky top-20 space-y-3.5">
                <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-extrabold text-slate-900">
                            @if ($state === 'A')
                                Ringkasan Estimasi Biaya
                            @elseif ($state === 'B')
                                Rincian Tagihan Final (100%)
                            @else
                                Ringkasan Pembayaran
                            @endif
                        </h3>
                        <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $state === 'A' ? 'bg-slate-100 text-slate-600' : 'bg-[#00877A]/10 text-[#00877A]' }}">
                            {{ $state === 'A' ? 'TAHAP PRE-ORDER' : ($state === 'B' ? 'HARGA FINAL' : 'LUNAS 100%') }}
                        </span>
                    </div>

                    <div class="mt-3.5 space-y-2 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Subtotal Pre-Order ({{ $firstItem?->quantity ?? 1 }} Unit)</span>
                            <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Ongkos Kirim Logistik</span>
                            @if ($state === 'A')
                                <span class="rounded bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700">Dihitung Admin</span>
                            @else
                                <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>PPN 11% (Resmi BLU PNJ)</span>
                            <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-3.5 flex items-end justify-between border-t border-slate-100 pt-3.5">
                        <div>
                            <p class="text-xs font-bold text-slate-700">
                                {{ $state === 'A' ? 'Total Estimasi Sementara' : ($state === 'B' ? 'Total Tagihan' : 'Total Dibayar') }}
                            </p>
                            <p class="text-[10px] text-slate-400">
                                {{ $state === 'A' ? '(Belum termasuk ongkir)' : '(Termasuk PPN & Ongkir)' }}
                            </p>
                        </div>
                        <p class="tabular-nums text-2xl font-extrabold text-[#00877A]">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- CTA & STATUS BOX BERDASARKAN STATE --}}
                    <div class="mt-4 space-y-2.5">
                        @if ($state === 'A')
                            <div class="rounded-lg border border-sky-200 bg-sky-50/70 p-3 text-[11px] text-sky-900">
                                <p class="font-bold">Belum Ada Tagihan Aktif</p>
                                <p class="mt-0.5 leading-relaxed text-sky-800">Pembayaran via Mandiri Virtual Account atau QRIS BLU PNJ baru dibuka setelah formulir &amp; stok dikonfirmasi oleh Admin RTPU.</p>
                            </div>
                            <form action="{{ route('b2c.pesanan.batal', $order) }}" method="POST"
                                  onsubmit="return confirm('Batalkan pengajuan Pre-Order #{{ $order->order_code }}?')">
                                @csrf
                                <button type="submit"
                                        class="w-full rounded-lg border border-rose-200 bg-white py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 active:scale-95">
                                    Batalkan Pengajuan Pre-Order
                                </button>
                            </form>
                        @elseif ($state === 'B')
                            <a href="{{ route('b2c.preorder.pembayaran', $order) }}"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] py-3 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] hover:shadow-md active:scale-95">
                                <span>Bayar Sekarang (QRIS / Mandiri VA)</span>
                                <span>&rarr;</span>
                            </a>
                            <button type="button" id="open-einvoice-modal-btn-side"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                Lihat Preview e-Invoice (PDF)
                            </button>
                            <form action="{{ route('b2c.pesanan.batal', $order) }}" method="POST"
                                  onsubmit="return confirm('Batalkan pengajuan Pre-Order #{{ $order->order_code }}?')">
                                @csrf
                                <button type="submit" class="w-full py-1 text-center text-[11px] font-bold text-rose-600 hover:underline">
                                    Batalkan Pengajuan Pre-Order
                                </button>
                            </form>
                        @elseif ($state === 'C')
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50/70 p-3 text-[11px] text-emerald-800">
                                <p class="font-bold">&#10003; Pembayaran Berhasil (100% Lunas)</p>
                                <p class="mt-0.5 tabular-nums">Dibayar via {{ $invoice?->payment_method === 'qris' ? 'QRIS BLU PNJ' : 'Mandiri VA BLU' }} &bull; {{ ($invoice?->paid_at ?? $order->updated_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                            </div>
                            <button type="button" onclick="window.print()"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                Unduh e-Nota / Invoice Lunas (PDF)
                            </button>
                        @elseif ($state === 'D')
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50/70 p-3 text-[11px] text-emerald-800">
                                <p class="font-bold">&#10003; Pembayaran Berhasil (100% Lunas)</p>
                                <p class="mt-0.5 tabular-nums">Terverifikasi Kas BLU PNJ</p>
                            </div>
                            <form action="{{ route('b2c.preorder.terima', $order) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] py-3 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] hover:shadow-md active:scale-95">
                                    &#10003; Konfirmasi Pesanan Diterima
                                </button>
                            </form>
                            <button type="button" onclick="window.print()"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-2 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                Unduh e-Nota Lunas (PDF)
                            </button>
                        @else
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50/70 p-3 text-[11px] text-emerald-800">
                                <p class="font-bold">&#10003; Pembayaran Lunas &amp; Pesanan Selesai</p>
                                <p class="mt-0.5 tabular-nums">Garansi Kalibrasi Lab 30 Hari Aktif</p>
                            </div>
                            @if (! $hasReviewed)
                                <button type="button" id="open-review-modal-btn-side"
                                        class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                                    Tulis Ulasan &amp; Penilaian
                                </button>
                            @endif
                            <button type="button" onclick="window.print()"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white py-2 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                Unduh e-Nota &amp; Hasil Kalibrasi (PDF)
                            </button>
                            @if ($product)
                                <a href="{{ route('b2c.preorder.checkout', $product) }}"
                                   class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-[#00877A]/30 bg-[#00877A]/5 py-2 text-xs font-bold text-[#00877A] hover:bg-[#00877A]/10 active:scale-95">
                                    Pesan Ulang Produk Ini
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- MODAL 04c.4: e-Invoice Tagihan Resmi (Khusus State B)                 --}}
{{-- ===================================================================== --}}
@if ($state === 'B')
<div id="po-einvoice-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="po-einvoice-title">
    <div class="w-full max-w-lg overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <div class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </span>
                <div>
                    <h3 id="po-einvoice-title" class="text-sm font-extrabold text-slate-900">e-Invoice Tagihan Resmi</h3>
                    <p class="text-[11px] text-slate-500">RTPU Student &bull; BLU Politeknik Negeri Jakarta</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">MENUNGGU PEMBAYARAN</span>
                <button type="button" data-close-einvoice class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div class="space-y-4 p-5 text-xs">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-[#00877A]/25 bg-[#00877A]/[0.04] p-3.5">
                <div>
                    <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Total Tagihan Final</p>
                    <p class="tabular-nums text-2xl font-extrabold text-[#00877A]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-slate-500">Termasuk PPN 11% &amp; Ongkir Final (Lunas 100%)</p>
                </div>
                <div class="text-right text-[11px]">
                    <p class="text-slate-500">No. Invoice: <strong class="tabular-nums text-slate-900">{{ $invNumber }}</strong></p>
                    <p class="text-slate-500">Ref Tiket: <strong class="tabular-nums text-slate-800">#{{ $order->order_code }}</strong></p>
                    <p class="text-amber-700">Jatuh Tempo: <strong class="tabular-nums">{{ ($invoice?->due_at ?? now()->addDay())->translatedFormat('d M Y, 23:59') }} WIB</strong></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3 text-[11px]">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Ditagihkan Kepada</p>
                    <p class="mt-0.5 font-bold text-slate-900">{{ $portalUser->name }}</p>
                    <p class="tabular-nums text-slate-500">NIM/NIP {{ $portalUser->nim_nip ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Rekening Penerima</p>
                    <p class="mt-0.5 font-bold text-slate-900">Rekening Operasional BLU PNJ (Bank Mandiri)</p>
                    <p class="text-slate-500">Metode: QRIS / Mandiri VA</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 rounded-lg border border-slate-200/80 px-3.5">
                <div class="flex items-center justify-between py-2.5">
                    <span>{{ $product?->name ?? 'Produk Pre-Order' }} ({{ $firstItem?->quantity ?? 1 }} Unit)</span>
                    <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span>Ongkos Kirim Final (Admin RTPU)</span>
                    <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span>PPN 11% (Resmi BLU PNJ)</span>
                    <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-2.5 text-sm font-extrabold text-[#00877A]">
                    <span>Total Tagihan Final</span>
                    <span class="tabular-nums">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button type="button" onclick="window.print()"
                        class="rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    Unduh e-Invoice (PDF)
                </button>
                <a href="{{ route('b2c.preorder.pembayaran', $order) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    <span>Lanjut ke Pembayaran</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ===================================================================== --}}
{{-- MODAL 04c.11: Tulis Ulasan & Penilaian PO (Khusus State E)            --}}
{{-- ===================================================================== --}}
@if ($state === 'E')
<div id="po-review-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="po-review-title">
    <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 id="po-review-title" class="text-base font-extrabold text-slate-900">Tulis Ulasan &amp; Penilaian</h3>
                <p class="mt-0.5 text-[11px] text-slate-500">Masukanmu membantu meningkatkan kualitas riset &amp; layanan lab PNJ.</p>
            </div>
            <button type="button" data-close-review class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('b2c.preorder.ulasan', $order) }}" method="POST" class="space-y-4 p-5">
            @csrf
            <div class="flex items-center gap-3 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3">
                <div class="h-11 w-11 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-white">
                    @if ($product?->resolved_image_url)
                        <img src="{{ $product->resolved_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-[#00877A]/10 text-[#00877A]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="6" width="12" height="12" rx="1.5" /></svg>
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="truncate text-xs font-extrabold text-slate-900">{{ $product?->name ?? 'Produk Pre-Order' }}</p>
                    <p class="truncate text-[10px] text-slate-500">{{ $product?->unit?->name ?? 'Lab PNJ' }} &bull; #{{ $order->order_code }}</p>
                </div>
            </div>

            {{-- Interactive Star Rating --}}
            <div class="text-center">
                <p class="text-xs font-bold text-slate-700">Seberapa puas dengan hasil perakitan &amp; akurasi alat?</p>
                <input type="hidden" name="rating" id="po-rating-input" value="5">
                <div class="mt-2 flex items-center justify-center gap-1.5" id="po-star-group">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" data-star="{{ $i }}" class="po-star-btn text-amber-400 transition-transform hover:scale-110 active:scale-95" aria-label="{{ $i }} Bintang">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                        </button>
                    @endfor
                </div>
                <span id="po-rating-label" class="mt-2 inline-block rounded-full bg-amber-50 px-3 py-0.5 text-[11px] font-bold text-amber-700">5.0 &mdash; Sangat Memuaskan!</span>
            </div>

            {{-- Quick Tags --}}
            <div>
                <p class="text-[11px] font-bold text-slate-700">Apa yang paling kamu suka? (Opsional)</p>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach (['Akurasi Sensor Presisi', 'Selesai Tepat Waktu', 'Cocok untuk Riset / TA', 'Teknisi Lab Ramah', 'Casing 3D Rapi'] as $tag)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag }}" class="peer sr-only" {{ in_array($tag, ['Akurasi Sensor Presisi', 'Selesai Tepat Waktu'], true) ? 'checked' : '' }}>
                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 transition-all peer-checked:border-[#00877A] peer-checked:bg-[#00877A]/10 peer-checked:text-[#00877A]">
                                {{ $tag }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="po-review-comment" class="block text-[11px] font-bold text-slate-700">Ceritakan pengalamanmu <span class="text-rose-500">*</span></label>
                <textarea id="po-review-comment" name="comment" rows="3" required
                          placeholder="Hasil kalibrasi sangat akurat saat dites untuk data tugas akhir. Teknisi lab sangat membantu saat konsultasi penggunaan."
                          class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button type="button" data-close-review
                        class="rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 active:scale-95">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    Kirim Ulasan
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        // Salin Resi
        document.querySelectorAll('[data-copy-text]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(btn.dataset.copyText);
                    const prev = btn.textContent;
                    btn.textContent = 'Tersalin!';
                    setTimeout(() => { btn.textContent = prev; }, 1500);
                } catch (_) {}
            });
        });

        // Modal e-Invoice (04c.4)
        const invModal = document.getElementById('po-einvoice-modal');
        const openInv = () => { invModal?.classList.remove('hidden'); invModal?.classList.add('flex'); };
        const closeInv = () => { invModal?.classList.add('hidden'); invModal?.classList.remove('flex'); };
        document.getElementById('open-einvoice-modal-btn')?.addEventListener('click', openInv);
        document.getElementById('open-einvoice-modal-btn-side')?.addEventListener('click', openInv);
        document.querySelectorAll('[data-close-einvoice]').forEach((el) => el.addEventListener('click', closeInv));
        invModal?.addEventListener('click', (e) => { if (e.target === invModal) closeInv(); });

        // Modal Tulis Ulasan (04c.11)
        const revModal = document.getElementById('po-review-modal');
        const openRev = () => { revModal?.classList.remove('hidden'); revModal?.classList.add('flex'); };
        const closeRev = () => { revModal?.classList.add('hidden'); revModal?.classList.remove('flex'); };
        document.getElementById('open-review-modal-btn')?.addEventListener('click', openRev);
        document.getElementById('open-review-modal-btn-side')?.addEventListener('click', openRev);
        document.querySelectorAll('[data-close-review]').forEach((el) => el.addEventListener('click', closeRev));
        revModal?.addEventListener('click', (e) => { if (e.target === revModal) closeRev(); });

        // Star rating interactive
        const ratingInput = document.getElementById('po-rating-input');
        const ratingLabel = document.getElementById('po-rating-label');
        const starBtns = document.querySelectorAll('.po-star-btn');
        const labels = {
            1: '1.0 — Kurang Memuaskan',
            2: '2.0 — Cukup Baik',
            3: '3.0 — Baik',
            4: '4.0 — Memuaskan',
            5: '5.0 — Sangat Memuaskan!',
        };

        starBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const val = parseInt(btn.dataset.star, 10);
                if (ratingInput) ratingInput.value = val;
                if (ratingLabel) ratingLabel.textContent = labels[val] || '';
                starBtns.forEach((b) => {
                    const s = parseInt(b.dataset.star, 10);
                    b.classList.toggle('text-amber-400', s <= val);
                    b.classList.toggle('text-slate-200', s > val);
                });
            });
        });
    })();
</script>
@endpush
