@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Pesanan Saya')
@section('nav', 'pesanan')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Pesanan Saya</span>
            </nav>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Pesanan Saya</h1>
            <p class="text-xs text-slate-500">Lacak status pesanan produk riset dan jadwal reservasi laboratorium Anda secara real-time.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-3 py-1 text-[11px] font-semibold text-[#00877A]">
            <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
            Sinkronisasi Portal Akademik Aktif
        </span>
    </div>

    {{-- Tab Filter Status + Search --}}
    <div class="flex flex-wrap items-center justify-between gap-2.5 rounded-lg border border-slate-200/80 bg-white p-3 shadow-xs">
        <div class="flex flex-wrap items-center gap-1.5">
            @foreach ([
                'semua' => ['label' => 'Semua', 'count' => $counts['semua']],
                'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'count' => $counts['menunggu_pembayaran']],
                'diproses' => ['label' => 'Diproses RTPU', 'count' => $counts['diproses']],
                'selesai' => ['label' => 'Selesai', 'count' => $counts['selesai']],
                'dibatalkan' => ['label' => 'Dibatalkan', 'count' => $counts['dibatalkan']],
            ] as $key => $meta)
                <a href="{{ route('b2c.pesanan', array_filter(['status' => $key === 'semua' ? null : $key, 'q' => $search])) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-all active:scale-95 {{ $statusTab === $key ? 'bg-[#00877A] text-white shadow-xs' : 'border border-slate-200 bg-white text-slate-600 hover:border-[#00877A] hover:text-[#00877A]' }}">
                    <span>{{ $meta['label'] }}</span>
                    <span class="rounded-full px-1.5 py-0.5 tabular-nums text-[10px] font-bold {{ $statusTab === $key ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                        {{ $meta['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        <form action="{{ route('b2c.pesanan') }}" method="GET" class="relative w-full sm:w-60">
            @if ($statusTab !== 'semua')
                <input type="hidden" name="status" value="{{ $statusTab }}">
            @endif
            <svg width="14" height="14" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari invoice atau produk..."
                   class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50/70 pr-3 pl-9 text-xs text-slate-700 outline-none focus:border-[#00877A] focus:bg-white focus:ring-2 focus:ring-[#00877A]/15">
        </form>
    </div>

    {{-- Daftar Pesanan --}}
    @if ($orders->isEmpty())
        <div class="rounded-lg border border-slate-200/80 bg-white px-6 py-12 text-center shadow-xs">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <path d="M3 6h18M16 10a4 4 0 0 1-8 0" />
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-slate-800">Belum Ada Pesanan pada Filter Ini</h3>
            <p class="mt-1 text-xs text-slate-500">Mulai eksplorasi katalog produk riset atau reservasi fasilitas laboratorium PNJ.</p>
            <a href="{{ route('b2c.katalog') }}"
               class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-4 py-2 text-xs font-bold text-white hover:bg-[#006E63] active:scale-95">
                Jelajahi Katalog Utama
            </a>
        </div>
    @else
        <div class="space-y-3.5">
            @foreach ($orders as $order)
                @php
                    $invoice = $order->invoice;
                    $firstItem = $order->items->first();
                    $product = $firstItem?->product;
                    $statusBadge = match ($order->status) {
                        'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'cls' => 'border-amber-200 bg-amber-50 text-amber-700'],
                        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'cls' => 'border-sky-200 bg-sky-50 text-sky-700'],
                        'diproses', 'siap_diambil' => ['label' => 'Diproses RTPU', 'cls' => 'border-[#00877A]/25 bg-[#00877A]/10 text-[#00877A]'],
                        'selesai' => ['label' => 'Selesai', 'cls' => 'border-emerald-200 bg-emerald-50 text-emerald-700'],
                        default => ['label' => 'Dibatalkan', 'cls' => 'border-rose-200 bg-rose-50 text-rose-700'],
                    };
                @endphp
                <article class="rounded-lg border border-slate-200/80 bg-white p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/40 hover:shadow-lg">
                    {{-- Header Card --}}
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="tabular-nums font-extrabold text-slate-900">{{ $invoice?->invoice_number ?? $order->order_code }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="tabular-nums text-[11px] text-slate-500">{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="rounded-full border px-2.5 py-0.5 text-[11px] font-bold {{ $statusBadge['cls'] }}">
                                &bull; {{ $statusBadge['label'] }}
                            </span>
                            @if ($order->booking_date && in_array($order->status, ['diproses', 'siap_diambil'], true))
                                <span class="rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-[10px] font-bold text-sky-700">
                                    Jadwal Terkonfirmasi
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Deadline Alert jika Menunggu Pembayaran --}}
                    @if ($order->status === 'menunggu_pembayaran' && $invoice?->due_at)
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 rounded-lg border border-amber-200/80 bg-amber-50/70 px-3 py-2 text-[11px] font-medium text-amber-800">
                            <span class="inline-flex items-center gap-1.5">
                                <svg width="14" height="14" class="shrink-0 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                                Selesaikan pembayaran 100% Lunas sebelum: <strong class="tabular-nums">{{ $invoice->due_at->translatedFormat('d M Y, H:i') }} WIB</strong>
                            </span>
                            <span class="rounded bg-white px-2 py-0.5 text-[10px] font-bold text-amber-700">Tanpa DP 50%</span>
                        </div>
                    @endif

                    {{-- Body Item --}}
                    <div class="mt-3.5 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200/70 bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/70 text-[#00877A]">
                                @if ($product?->resolved_image_url)
                                    <img src="{{ $product->resolved_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                @elseif ($order->order_type === 'lab_reservation')
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="14" rx="2" />
                                        <path d="M7 21h10M12 17v4" />
                                    </svg>
                                @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                        <path d="M9 2v4M15 2v4M9 18v4M15 18v4" />
                                    </svg>
                                @endif
                            </span>
                            <div>
                                <span class="rounded bg-sky-50 px-2 py-0.5 text-[10px] font-bold text-sky-700">
                                    {{ $order->order_type === 'lab_reservation' ? 'Reservasi Laboratorium' : ($order->order_type === 'pre_order' ? 'Pre-Order Riset' : 'Komponen Riset Ready Stock') }}
                                </span>
                                <h3 class="mt-1 text-sm font-extrabold text-slate-900">{{ $product?->name ?? 'Pesanan Riset PNJ' }}</h3>
                                <p class="mt-0.5 text-[11px] text-slate-500">
                                    @if ($order->booking_date)
                                        Jadwal: {{ $order->booking_date->translatedFormat('d M Y') }} ({{ $order->booking_slot }}) &bull; {{ $product?->unit?->name }}
                                    @else
                                        Jumlah: {{ $firstItem?->quantity ?? 1 }} Unit &bull; {{ $product?->unit?->name }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-[11px] text-slate-400">Total Tagihan</p>
                            <p class="tabular-nums text-base font-extrabold text-slate-900">
                                Rp {{ number_format((float) ($invoice?->grand_total ?? $order->items->sum('subtotal')), 0, ',', '.') }}
                                @if ($invoice?->payment_status === 'paid')
                                    <span class="ml-1 rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Lunas</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="mt-3.5 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3">
                        <p class="text-[11px] font-medium text-slate-500">
                            @if ($invoice?->payment_status === 'paid')
                                <span class="text-[#00877A]">&#10003; Pembayaran Terverifikasi ({{ $invoice->payment_method === 'qris' ? 'QRIS BLU PNJ' : 'VA Mandiri BLU' }})</span>
                            @else
                                Metode: Virtual Account Mandiri BLU / QRIS
                            @endif
                        </p>

                        @php
                            $detailUrl = $order->order_type === 'pre_order'
                                ? route('b2c.preorder.show', $order)
                                : route('b2c.pesanan.show', $order);
                            $payUrl = match ($order->order_type) {
                                'pre_order' => route('b2c.preorder.pembayaran', $order),
                                'ready_stock' => route('b2c.ready-stock.pembayaran', $order),
                                default => route('b2c.pembayaran'),
                            };
                        @endphp
                        <div class="flex items-center gap-2">
                            @if ($order->status === 'menunggu_pembayaran')
                                <form action="{{ route('b2c.pesanan.batal', $order) }}" method="POST"
                                      onsubmit="return confirm('Batalkan pesanan {{ $order->order_code }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition-all hover:border-rose-300 hover:text-rose-600 active:scale-95">
                                        Batalkan
                                    </button>
                                </form>
                                @if ($order->order_type === 'pre_order')
                                    <a href="{{ $detailUrl }}"
                                       class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                        Lihat e-Invoice
                                    </a>
                                @endif
                                <a href="{{ $payUrl }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition-all hover:bg-[#006E63] active:scale-95">
                                    Bayar Sekarang
                                </a>
                            @else
                                <a href="{{ $detailUrl }}"
                                   class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                    Rincian Pesanan
                                </a>
                                <a href="{{ $detailUrl }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition-all hover:bg-[#006E63] active:scale-95">
                                    {{ $order->order_type === 'pre_order' ? 'Lacak Pre-Order' : ($order->status === 'selesai' ? 'Lihat Nota & QR Pass' : 'Lacak & QR Pass') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="flex items-center justify-between rounded-lg border border-slate-200/80 bg-white px-4 py-2.5 text-xs text-slate-500 shadow-xs">
            <span class="tabular-nums">Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi</span>
            <div>{{ $orders->onEachSide(1)->links() }}</div>
        </div>
    @endif
</div>
@endsection
