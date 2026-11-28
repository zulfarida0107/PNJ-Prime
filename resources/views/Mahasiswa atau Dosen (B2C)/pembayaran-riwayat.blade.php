@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Riwayat Pembayaran')
@section('nav', 'pembayaran')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <a href="{{ route('b2c.pembayaran') }}" class="hover:text-[#00877A]">Pembayaran &amp; Tagihan</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Riwayat Pembayaran</span>
            </nav>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Riwayat Pembayaran</h1>
            <p class="text-xs text-slate-500">Daftar riwayat transaksi lunas &amp; status tagihan BLU PNJ Anda.</p>
        </div>

        <div class="rounded-lg border border-slate-200/80 bg-white px-3.5 py-2 text-right shadow-xs">
            <p class="text-[10px] font-semibold text-slate-400 uppercase">Total Transaksi Lunas</p>
            <p class="tabular-nums text-sm font-extrabold text-[#00877A]">Rp {{ number_format((float) $totalPaidAmount, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tab Bar + Search & Filter Metode --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('b2c.pembayaran') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                <span>Tagihan Aktif</span>
                <span class="rounded-full bg-slate-100 px-1.5 py-0.5 tabular-nums text-[10px] font-bold text-slate-600">{{ $activeCount }}</span>
            </a>
            <a href="{{ route('b2c.pembayaran.riwayat') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-3.5 py-2 text-xs font-bold text-white shadow-xs transition-all active:scale-95">
                <span>Riwayat Selesai</span>
                <span class="rounded-full bg-white/20 px-1.5 py-0.5 tabular-nums text-[10px] font-bold">{{ $invoices->total() }}</span>
            </a>
        </div>

        <form action="{{ route('b2c.pembayaran.riwayat') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="method" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-600 outline-none focus:border-[#00877A]">
                <option value="">Semua Metode</option>
                <option value="va_mandiri_blu" {{ $method === 'va_mandiri_blu' ? 'selected' : '' }}>VA Mandiri BLU</option>
                <option value="qris" {{ $method === 'qris' ? 'selected' : '' }}>QRIS BLU PNJ</option>
            </select>
            <div class="relative w-56">
                <svg width="14" height="14" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari invoice / transaksi..."
                       class="h-9 w-full rounded-lg border border-slate-200 bg-white pr-3 pl-9 text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
            </div>
        </form>
    </div>

    {{-- Tabel Riwayat Transaksi (tabular-nums) --}}
    <div class="overflow-hidden rounded-lg border border-slate-200/80 bg-white shadow-xs">
        @if ($invoices->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="text-sm font-bold text-slate-800">Belum Ada Riwayat Pembayaran</p>
                <p class="mt-1 text-xs text-slate-500">Transaksi yang telah Anda lunasi akan otomatis tercatat pada tabel riwayat ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200/80 bg-slate-50/80 text-[11px] font-bold tracking-wide text-slate-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">Nomor Invoice &amp; Tanggal</th>
                            <th class="px-4 py-3">Produk / Fasilitas Riset</th>
                            <th class="px-4 py-3">Metode Pembayaran</th>
                            <th class="px-4 py-3 text-right">Nominal (100%)</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($invoices as $inv)
                            @php
                                $ord = $inv->order;
                                $prod = $ord?->items?->first()?->product;
                                $isPaid = $inv->payment_status === 'paid';
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/70">
                                <td class="px-4 py-3.5">
                                    <p class="tabular-nums font-extrabold text-slate-900">{{ $inv->invoice_number }}</p>
                                    <p class="mt-0.5 tabular-nums text-[11px] text-slate-500">
                                        {{ ($inv->paid_at ?? $inv->created_at)->translatedFormat('d M Y, H:i') }} WIB
                                    </p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="font-bold text-slate-800">{{ $prod?->name ?? 'Pesanan Riset PNJ' }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">
                                        {{ $prod?->unit?->name ?? 'UPA-RTPU PNJ' }} &bull; {{ $ord?->order_code }}
                                    </p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">
                                        {{ $inv->payment_method === 'qris' ? 'QRIS Dinamis BLU' : 'VA Mandiri BLU' }}
                                    </span>
                                    @if ($inv->qr_pass_code)
                                        <p class="mt-0.5 tabular-nums text-[10px] text-slate-400">Pass: {{ $inv->qr_pass_code }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right tabular-nums font-extrabold text-slate-900">
                                    Rp {{ number_format((float) $inv->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if ($isPaid)
                                        <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">
                                            &#10003; Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600">
                                            Kedaluwarsa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    @if ($ord)
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('b2c.pesanan.show', $ord) }}"
                                               class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('b2c.pesanan.show', $ord) }}"
                                               class="inline-flex items-center gap-1 rounded-lg bg-[#00877A]/10 px-2.5 py-1.5 text-[11px] font-bold text-[#00877A] transition-all hover:bg-[#00877A] hover:text-white active:scale-95">
                                                E-Nota
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 px-4 py-2.5 text-xs text-slate-500">
                <span class="tabular-nums">Menampilkan {{ $invoices->firstItem() }}–{{ $invoices->lastItem() }} dari {{ $invoices->total() }} riwayat transaksi</span>
                <div>{{ $invoices->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
