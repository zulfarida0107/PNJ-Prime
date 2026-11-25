@extends('mitra.layouts.app')

@section('title', 'Dashboard Mitra Industri')
@section('nav', 'dashboard')
@section('search_placeholder', 'Cari kode pesanan, produk, lab, atau nomor invoice...')

@php
    if (! isset($orders) || ! isset($kpis) || ! isset($mitra)) {
        $dashboardData = app(\App\Http\Controllers\MitraB2BController::class)->getDashboardData(request());
        $mitra = $dashboardData['mitra'];
        $orders = $dashboardData['orders'];
        $kpis = $dashboardData['kpis'];
        $searchQuery = $dashboardData['searchQuery'];
    }

    $hasTransactions = $orders->isNotEmpty();
    $companyName = $mitra->company_name ?: ($mitra->name ?: 'PT Inovasi Teknologi Nusantara');
    $kycStatus = $mitra->kyc_status ?? 'approved';
    $tanggalHariIni = \Illuminate\Support\Carbon::now()->locale('id')->translatedFormat('l, d F Y');

    $orderTypeMeta = [
        'ready_stock' => ['label' => 'Ready Stock', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'pre_order' => ['label' => 'Pre-Order', 'badge' => 'bg-sky-50 text-sky-700 border-sky-200'],
        'lab_reservation' => ['label' => 'Reservasi Lab', 'badge' => 'bg-teal-50 text-[#007266] border-teal-200'],
    ];

    $orderStatusMeta = [
        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'dot' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'group' => 'menunggu'],
        'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'dot' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'group' => 'menunggu'],
        'diproses' => ['label' => 'Diproses RTPU', 'dot' => 'bg-sky-500', 'badge' => 'bg-sky-50 text-sky-700 border-sky-200', 'group' => 'diproses'],
        'siap_diambil' => ['label' => 'Siap Diambil', 'dot' => 'bg-indigo-500', 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'group' => 'diproses'],
        'selesai' => ['label' => 'Selesai', 'dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'group' => 'selesai'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'dot' => 'bg-rose-500', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200', 'group' => 'dibatalkan'],
    ];
@endphp

@section('content')
<div class="space-y-4">
    {{-- ===================================================================== --}}
    {{-- HEADER BANNER COMPACT                                                 --}}
    {{-- ===================================================================== --}}
    <section class="rounded-lg border border-slate-200/80 bg-white px-5 py-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                    <span class="tracking-wider text-[#00897b] uppercase">Ikhtisar Transaksi B2B</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="tabular-nums text-slate-500 font-medium">{{ $tanggalHariIni }}</span>
                </div>
                <h1 class="mt-1 text-lg font-bold tracking-tight text-slate-900">
                    Selamat datang, <span class="text-[#00897b]">{{ $companyName }}</span>
                </h1>
                <p class="mt-0.5 text-xs text-slate-500">
                    @if ($hasTransactions)
                        Pantau ringkasan pesanan riset, reservasi laboratorium PNJ, dan status tagihan komersial 100% Lunas.
                    @else
                        Portal pengadaan produk riset terapan dan penyewaan fasilitas laboratorium Politeknik Negeri Jakarta.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($kycStatus === 'approved')
                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        KYC Terverifikasi
                    </span>
                @elseif ($kycStatus === 'rejected')
                    <a href="{{ route('b2b.profil') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        Perbaiki Dokumen KYC
                    </a>
                @else
                    <a href="{{ route('b2b.profil') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        Menunggu Verifikasi KYC
                    </a>
                @endif

                <a href="{{ route('b2b.katalog') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-[#00998a] px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Jelajahi Katalog B2B
                </a>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- KPI CARDS (4 KOLOM PROPORSIONAL 1366x768)                             --}}
    {{-- ===================================================================== --}}
    <section class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- KPI 1: Pesanan Aktif --}}
        <div class="group rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-medium text-slate-500">Pesanan Aktif</p>
                    <p class="mt-1.5 tabular-nums text-2xl font-extrabold tracking-tight text-slate-900">
                        {{ $kpis['pesanan_aktif'] }}
                    </p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#e6f7f5] text-[#00897b] transition-transform duration-300 group-hover:scale-105">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                </span>
            </div>
            <p class="mt-2 text-[11px] text-slate-500">
                @if ($kpis['pesanan_aktif'] > 0)
                    Dalam proses verifikasi / pengerjaan
                @else
                    Belum ada pesanan berjalan
                @endif
            </p>
        </div>

        {{-- KPI 2: Total Transaksi B2B --}}
        <div class="group rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">Total Transaksi B2B</p>
                    <p class="mt-1.5 tabular-nums text-xl font-extrabold tracking-tight text-slate-900 truncate"
                       title="Rp {{ number_format($kpis['total_transaksi_nilai'], 0, ',', '.') }}">
                        Rp {{ number_format($kpis['total_transaksi_nilai'], 0, ',', '.') }}
                    </p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-transform duration-300 group-hover:scale-105">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
            </div>
            <p class="mt-2 tabular-nums text-[11px] text-slate-500">
                Dari <span class="font-semibold text-slate-700">{{ $kpis['total_transaksi_count'] }}</span> transaksi komersial
            </p>
        </div>

        {{-- KPI 3: Tagihan Berjalan --}}
        <div class="group rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">Tagihan Berjalan</p>
                    <p class="mt-1.5 tabular-nums text-xl font-extrabold tracking-tight {{ $kpis['tagihan_berjalan_count'] > 0 ? 'text-amber-600' : 'text-slate-900' }} truncate">
                        Rp {{ number_format($kpis['tagihan_berjalan_nilai'], 0, ',', '.') }}
                    </p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition-transform duration-300 group-hover:scale-105">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 2 2 2-2 2 2 3-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M8 13h6M8 17h4"/>
                    </svg>
                </span>
            </div>
            <p class="mt-2 tabular-nums text-[11px] text-slate-500">
                @if ($kpis['tagihan_berjalan_count'] > 0)
                    <span class="font-semibold text-amber-700">{{ $kpis['tagihan_berjalan_count'] }} invoice</span> belum lunas (100% Lunas)
                @else
                    Tidak ada tagihan tertunggak
                @endif
            </p>
        </div>

        {{-- KPI 4: Fasilitas Lab Tersewa --}}
        <div class="group rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs font-medium text-slate-500">Fasilitas Lab Tersewa</p>
                    <p class="mt-1.5 tabular-nums text-2xl font-extrabold tracking-tight text-slate-900">
                        {{ $kpis['lab_tersewa'] }}
                    </p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600 transition-transform duration-300 group-hover:scale-105">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 3h6"/>
                        <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"/>
                        <path d="M7.5 14h9"/>
                    </svg>
                </span>
            </div>
            <p class="mt-2 text-[11px] text-slate-500">
                Reservasi lab &amp; workshop BLU PNJ
            </p>
        </div>
    </section>

    @if ($hasTransactions)
        {{-- ================================================================= --}}
        {{-- ACTIVE STATE: DAFTAR PESANAN & INVOICE TERBARU                    --}}
        {{-- ================================================================= --}}
        <section id="daftar-pesanan" class="rounded-lg border border-slate-200/80 bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-3.5">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-slate-900">Status Pesanan &amp; Tagihan B2B Terbaru</h2>
                        <span class="tabular-nums rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">
                            <span id="b2b-page-result-count">{{ $orders->count() }}</span> data
                        </span>
                    </div>
                    <p class="mt-0.5 text-[11px] text-slate-500">
                        Bersumber langsung dari transaksi B2B Anda (Skema 100% Lunas tanpa DP).
                    </p>
                </div>

                {{-- Filter Tab Status --}}
                <div class="flex flex-wrap items-center gap-1 rounded-lg border border-slate-200/80 bg-slate-50 p-1 text-xs">
                    <button type="button" data-status-filter="all"
                            class="b2b-status-tab rounded-md bg-white px-2.5 py-1 font-semibold text-[#00897b] shadow-2xs transition active:scale-95">
                        Semua
                    </button>
                    <button type="button" data-status-filter="menunggu"
                            class="b2b-status-tab rounded-md px-2.5 py-1 font-medium text-slate-600 transition hover:text-slate-900 active:scale-95">
                        Menunggu Bayar
                    </button>
                    <button type="button" data-status-filter="diproses"
                            class="b2b-status-tab rounded-md px-2.5 py-1 font-medium text-slate-600 transition hover:text-slate-900 active:scale-95">
                        Diproses
                    </button>
                    <button type="button" data-status-filter="selesai"
                            class="b2b-status-tab rounded-md px-2.5 py-1 font-medium text-slate-600 transition hover:text-slate-900 active:scale-95">
                        Selesai
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 text-left text-[11px] font-semibold tracking-wide text-slate-500 uppercase">
                            <th class="px-5 py-2.5">Kode &amp; Tanggal</th>
                            <th class="px-4 py-2.5">Produk / Layanan Riset</th>
                            <th class="px-4 py-2.5">Tipe &amp; Jadwal</th>
                            <th class="px-4 py-2.5">Tagihan &amp; Pajak B2B</th>
                            <th class="px-4 py-2.5">Status</th>
                            <th class="px-5 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($orders as $order)
                            @php
                                $firstItem = $order->items->first();
                                $product = $firstItem?->product;
                                $invoice = $order->invoice;
                                $typeInfo = $orderTypeMeta[$order->order_type] ?? ['label' => ucfirst($order->order_type), 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'];
                                $statusInfo = $orderStatusMeta[$order->status] ?? ['label' => $order->status, 'dot' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'group' => 'all'];
                                $searchIndex = strtolower(implode(' ', array_filter([
                                    $order->order_code,
                                    $invoice?->invoice_number,
                                    $invoice?->va_number,
                                    $product?->name,
                                    $product?->code,
                                    $product?->unit?->name,
                                    $product?->category?->name,
                                    $typeInfo['label'],
                                    $statusInfo['label'],
                                    $order->notes,
                                ])));
                            @endphp
                            <tr data-search-item
                                data-search-text="{{ $searchIndex }}"
                                data-status-group="{{ $statusInfo['group'] }}"
                                data-status-match="1"
                                class="transition-colors hover:bg-slate-50/70">
                                <td class="px-5 py-3.5 align-top whitespace-nowrap">
                                    <p class="tabular-nums font-bold text-slate-900">{{ $order->order_code }}</p>
                                    <p class="mt-0.5 tabular-nums text-[11px] text-slate-400">
                                        {{ $order->created_at?->locale('id')->translatedFormat('d M Y') }}
                                    </p>
                                </td>

                                <td class="px-4 py-3.5 align-top max-w-[260px]">
                                    <p class="font-semibold text-slate-800 leading-snug">
                                        {{ $product?->name ?? 'Pesanan Katalog B2B' }}
                                        @if ($order->items->count() > 1)
                                            <span class="tabular-nums text-[11px] font-medium text-slate-400">
                                                (+{{ $order->items->count() - 1 }} item)
                                            </span>
                                        @endif
                                    </p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">
                                        {{ $product?->unit?->name ?? 'Unit Pengelola PNJ' }}
                                        @if ($firstItem)
                                            &bull; <span class="tabular-nums font-medium">{{ $firstItem->quantity }}x</span>
                                        @endif
                                    </p>
                                </td>

                                <td class="px-4 py-3.5 align-top whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[11px] font-semibold {{ $typeInfo['badge'] }}">
                                        {{ $typeInfo['label'] }}
                                    </span>
                                    @if ($order->booking_date)
                                        <p class="mt-1 tabular-nums text-[11px] text-slate-600">
                                            {{ $order->booking_date->locale('id')->translatedFormat('d M Y') }}
                                            @if ($order->booking_slot)
                                                &bull; {{ $order->booking_slot }}
                                            @endif
                                        </p>
                                    @elseif ($order->estimasi_hari)
                                        <p class="mt-1 tabular-nums text-[11px] text-slate-500">
                                            Estimasi {{ $order->estimasi_hari }} hari kerja
                                        </p>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5 align-top whitespace-nowrap">
                                    @if ($invoice)
                                        <p class="tabular-nums font-bold text-slate-900">
                                            Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                                        </p>
                                        <p class="mt-0.5 tabular-nums text-[11px] text-slate-500">
                                            {{ $invoice->invoice_number }}
                                            @if ($invoice->va_number)
                                                &bull; VA {{ $invoice->va_number }}
                                            @endif
                                        </p>
                                        <div class="mt-1 flex flex-wrap items-center gap-1 text-[10px]">
                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 font-medium text-slate-600">100% Lunas</span>
                                            @if ((float) $invoice->ppn_amount > 0)
                                                <span class="tabular-nums rounded bg-teal-50 px-1.5 py-0.5 font-medium text-[#007266]">PPN 11%</span>
                                            @endif
                                            @if ((float) $invoice->pph23_amount > 0)
                                                <span class="tabular-nums rounded bg-sky-50 px-1.5 py-0.5 font-medium text-sky-700">PPh 23</span>
                                            @endif
                                            @if ((float) $invoice->emeterai_amount > 0)
                                                <span class="rounded bg-indigo-50 px-1.5 py-0.5 font-medium text-indigo-700">e-Meterai</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">Menunggu Invoice</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5 align-top whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $statusInfo['badge'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusInfo['dot'] }}"></span>
                                        {{ $statusInfo['label'] }}
                                    </span>
                                    @if ($invoice)
                                        <p class="mt-1 text-[11px] font-medium {{ $invoice->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                            Pembayaran: {{ $invoice->payment_status === 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5 align-top text-right whitespace-nowrap">
                                    @if ($product)
                                        <a href="{{ route('b2b.detail', $product->id) }}"
                                           class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition hover:border-[#00897b] hover:text-[#00897b] active:scale-95">
                                            Lihat Detail
                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                <path d="m9 18 6-6-6-6"/>
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Live Search / Filter Empty State --}}
            <div id="b2b-live-search-empty" class="hidden border-t border-slate-100 px-6 py-10 text-center">
                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                </div>
                <p class="mt-3 text-sm font-bold text-slate-800">Tidak ada pesanan atau invoice yang cocok</p>
                <p class="mt-1 text-xs text-slate-500">Coba kata kunci lain atau reset filter pencarian Anda.</p>
                <button type="button"
                        onclick="if (window.b2bApplySearch) { document.getElementById('b2b-global-search').value = ''; window.b2bApplySearch('', true); } document.querySelector('[data-status-filter=all]')?.click();"
                        class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
                    Reset Filter &amp; Pencarian
                </button>
            </div>
        </section>

        {{-- ================================================================= --}}
        {{-- KESIAPAN DOKUMEN PAJAK & BANTUAN KOMERSIAL                        --}}
        {{-- ================================================================= --}}
        <section class="grid grid-cols-1 gap-3.5 md:grid-cols-2">
            <div class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#e6f7f5] text-[#00897b]">
                        <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <path d="m9 15 2 2 4-4"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900">Kesiapan Dokumen Pajak &amp; Audit Korporat</h3>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            Setiap invoice B2B PNJ Prime diterbitkan dengan skema <strong class="text-slate-700">100% Lunas</strong> dilengkapi komponen <strong class="text-slate-700">PPN 11%</strong>, pemotongan <strong class="text-slate-700">PPh 23 (2%)</strong> untuk jasa/lab, <strong class="text-slate-700">e-Meterai Rp 10.000</strong>, serta <strong class="text-slate-700">BAST</strong> resmi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                            <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <path d="M12 17h.01"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-xs font-bold text-slate-900">Butuh Pengadaan Produk atau Slot Lab Baru?</h3>
                            <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                                Telusuri katalog produk riset unggulan &amp; fasilitas CNC 5-Axis PNJ dengan tarif B2B resmi.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('b2b.katalog') }}"
                       class="shrink-0 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-[#00897b] transition hover:border-[#00897b] hover:bg-[#e6f7f5] active:scale-95">
                        Buka Katalog
                    </a>
                </div>
            </div>
        </section>
    @else
        {{-- ================================================================= --}}
        {{-- EMPTY STATE OTOMATIS (MITRA BELUM MEMILIKI TRANSAKSI)             --}}
        {{-- ================================================================= --}}
        <section class="rounded-lg border border-slate-200/80 bg-white px-6 py-10 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-[#e6f7f5] text-[#00897b]">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                    <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                    <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                    <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                </svg>
            </div>
            <h2 class="mt-4 text-base font-bold text-slate-900">Belum ada aktivitas transaksi</h2>
            <p class="mx-auto mt-1.5 max-w-md text-xs leading-relaxed text-slate-500">
                Jelajahi katalog riset &amp; inovasi PNJ untuk melakukan pemesanan produk (Ready Stock / Pre-Order) atau reservasi fasilitas laboratorium berstandar industri.
            </p>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-2.5">
                <a href="{{ route('b2b.katalog') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-[#00897b] bg-white px-4 py-2 text-xs font-semibold text-[#00897b] transition hover:bg-[#e6f7f5] active:scale-95">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    Jelajahi Katalog Riset
                </a>
                <a href="{{ route('b2b.katalog', ['order_type' => 'lab_reservation']) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-[#00998a] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 3h6"/>
                        <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"/>
                    </svg>
                    Reservasi Fasilitas Lab
                </a>
            </div>
            <p class="mt-4 text-[11px] text-slate-400">
                Sistem transaksi BLU PNJ &bull; Tarif Komersial B2B &bull; Skema 100% Lunas
            </p>
        </section>

        {{-- Panduan Cepat Memulai Pengadaan B2B --}}
        <section class="space-y-2.5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Panduan Cepat Memulai Pengadaan B2B</h3>
                    <p class="text-[11px] text-slate-500">Ikuti 3 langkah ringkas untuk pengadaan produk riset &amp; penyewaan lab PNJ.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-3.5 md:grid-cols-3">
                <div class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="tabular-nums flex h-6 w-6 items-center justify-center rounded-md bg-[#e6f7f5] text-xs font-bold text-[#00897b]">1</span>
                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">Legalitas B2B</span>
                    </div>
                    <h4 class="mt-2.5 text-xs font-bold text-slate-900">1. Cek Legalitas Perusahaan</h4>
                    <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                        Pastikan NIB, NPWP 16 digit, kontak PIC, dan alamat penagihan perusahaan telah terverifikasi KYC.
                    </p>
                    <a href="{{ route('b2b.profil') }}" class="mt-3 inline-flex items-center text-[11px] font-semibold text-[#00897b] hover:underline">
                        Periksa Profil Perusahaan &rarr;
                    </a>
                </div>

                <div class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="tabular-nums flex h-6 w-6 items-center justify-center rounded-md bg-[#e6f7f5] text-xs font-bold text-[#00897b]">2</span>
                        <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[10px] font-semibold text-sky-700">Tarif B2B</span>
                    </div>
                    <h4 class="mt-2.5 text-xs font-bold text-slate-900">2. Pilih Produk atau Lab Riset</h4>
                    <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                        Filter berdasarkan unit jurusan, kategori Barang/Jasa/Lab, dan tipe order Ready Stock, Pre-Order, atau Reservasi Lab.
                    </p>
                    <a href="{{ route('b2b.katalog') }}" class="mt-3 inline-flex items-center text-[11px] font-semibold text-[#00897b] hover:underline">
                        Buka Katalog Riset &rarr;
                    </a>
                </div>

                <div class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="tabular-nums flex h-6 w-6 items-center justify-center rounded-md bg-[#e6f7f5] text-xs font-bold text-[#00897b]">3</span>
                        <span class="rounded-full bg-teal-50 px-2 py-0.5 text-[10px] font-semibold text-[#007266]">100% Lunas</span>
                    </div>
                    <h4 class="mt-2.5 text-xs font-bold text-slate-900">3. Pembayaran &amp; Dokumen Pajak</h4>
                    <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                        Pembayaran 100% Lunas via VA Mandiri BLU lengkap dengan PPN 11%, PPh 23, e-Meterai, dan BAST resmi.
                    </p>
                    <a href="{{ route('b2b.katalog') }}" class="mt-3 inline-flex items-center text-[11px] font-semibold text-[#00897b] hover:underline">
                        Lihat Simulasi Pajak &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const tabs = document.querySelectorAll('.b2b-status-tab');
        if (!tabs.length) return;

        let activeGroup = 'all';

        function updateRows() {
            const rows = document.querySelectorAll('[data-search-item]');
            let visible = 0;

            rows.forEach(function (row) {
                const group = row.getAttribute('data-status-group') || 'all';
                const statusMatch = activeGroup === 'all' || group === activeGroup;
                row.dataset.statusMatch = statusMatch ? '1' : '0';

                const searchMatch = row.dataset.searchMatch !== '0';
                const show = statusMatch && searchMatch;
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            const counter = document.getElementById('b2b-page-result-count');
            if (counter) counter.textContent = String(visible);

            const emptyEl = document.getElementById('b2b-live-search-empty');
            if (emptyEl) emptyEl.classList.toggle('hidden', visible > 0);
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activeGroup = tab.getAttribute('data-status-filter') || 'all';

                tabs.forEach(function (t) {
                    t.classList.remove('bg-white', 'font-semibold', 'text-[#00897b]', 'shadow-2xs');
                    t.classList.add('font-medium', 'text-slate-600');
                });
                tab.classList.add('bg-white', 'font-semibold', 'text-[#00897b]', 'shadow-2xs');
                tab.classList.remove('font-medium', 'text-slate-600');

                updateRows();
            });
        });

        window.addEventListener('b2b:search', function () {
            updateRows();
        });
    })();
</script>
@endpush
