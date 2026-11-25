@extends('admin-rtpu.layouts.app')

@section('title', 'Daftar Antrean Pesanan B2C & B2B')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.rtpu.dashboard') }}" class="hover:text-[#003366]">Dashboard RTPU</a>
                <span>/</span>
                <span class="text-slate-800">Antrean Pesanan B2C &amp; B2B</span>
            </nav>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Daftar Antrean Pesanan Terpadu
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Kelola seluruh permohonan pesanan sivitas (B2C) dan mitra industri (B2B), filter per unit/status, serta ekspor rekap operasional.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.rtpu.export.csv', array_filter(['segment' => $segment !== 'semua' ? $segment : null, 'status' => $status !== 'semua' ? $status : null, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Ekspor .CSV</span>
            </a>
            <a href="{{ route('admin.rtpu.print.laporan', array_filter(['segment' => $segment !== 'semua' ? $segment : null, 'status' => $status !== 'semua' ? $status : null, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak Rekap (.PDF)</span>
            </a>
        </div>
    </div>

    {{-- Filter Bar Interaktif --}}
    <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
            {{-- Pill Segment B2C / B2B --}}
            <div class="inline-flex items-center p-1 rounded-lg bg-slate-100 border border-slate-200/70 text-[12px] tabular-nums">
                @php
                    $segmentPills = [
                        'semua' => ['Semua Kanal', $counts['semua']],
                        'b2c' => ['Kanal B2C', $counts['b2c']],
                        'b2b' => ['Kanal B2B', $counts['b2b']],
                    ];
                @endphp
                @foreach ($segmentPills as $segKey => [$segLabel, $segCount])
                    <a href="{{ route('admin.rtpu.antrean', array_filter(['segment' => $segKey !== 'semua' ? $segKey : null, 'status' => $status !== 'semua' ? $status : null, 'unit_id' => $selectedUnitId, 'order_type' => $orderType !== 'semua' ? $orderType : null, 'q' => $search])) }}"
                       class="px-3 py-1 rounded-md font-semibold transition-all inline-flex items-center gap-1.5 {{ $segment === $segKey ? 'bg-white text-[#003366] shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>{{ $segLabel }}</span>
                        <span class="text-[10.5px] px-1.5 py-0.2 rounded {{ $segment === $segKey ? 'bg-[#EFF6FF] text-[#003366]' : 'bg-slate-200/70 text-slate-600' }}">{{ $segCount }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Form Filter Dropdown Status, Unit, Tipe Order --}}
            <form method="GET" action="{{ route('admin.rtpu.antrean') }}" class="flex flex-wrap items-center gap-2 text-[12px]">
                @if ($segment !== 'semua')
                    <input type="hidden" name="segment" value="{{ $segment }}">
                @endif
                @if ($search !== '')
                    <input type="hidden" name="q" value="{{ $search }}">
                @endif

                <select name="status" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 rounded-lg bg-slate-50 border border-slate-200/90 text-slate-700 font-medium focus:outline-none focus:border-[#003366]">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="perlu_validasi" {{ $status === 'perlu_validasi' ? 'selected' : '' }}>Perlu Validasi / Pelunasan</option>
                    <option value="menunggu_verifikasi" {{ $status === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="menunggu_pembayaran" {{ $status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="diproses" {{ $status === 'diproses' ? 'selected' : '' }}>Diproses Unit</option>
                    <option value="siap_diambil" {{ $status === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <select name="unit_id" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 rounded-lg bg-slate-50 border border-slate-200/90 text-slate-700 font-medium focus:outline-none focus:border-[#003366]">
                    <option value="">Semua Unit Pengelola</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}" {{ (string) $selectedUnitId === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>

                <select name="order_type" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 rounded-lg bg-slate-50 border border-slate-200/90 text-slate-700 font-medium focus:outline-none focus:border-[#003366]">
                    <option value="semua" {{ $orderType === 'semua' ? 'selected' : '' }}>Semua Tipe Order</option>
                    <option value="ready_stock" {{ $orderType === 'ready_stock' ? 'selected' : '' }}>Ready Stock</option>
                    <option value="pre_order" {{ $orderType === 'pre_order' ? 'selected' : '' }}>Pre-Order</option>
                    <option value="lab_reservation" {{ $orderType === 'lab_reservation' ? 'selected' : '' }}>Reservasi Lab</option>
                </select>

                @if ($segment !== 'semua' || $status !== 'semua' || $selectedUnitId || $orderType !== 'semua' || $search !== '')
                    <a href="{{ route('admin.rtpu.antrean') }}"
                       class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition-colors">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabel Antrean Operasional Terpadu --}}
    <div class="bg-white rounded-lg border border-slate-200/80 overflow-hidden">
        {{-- Indikator Live Search --}}
        <div id="liveSearchBanner" class="hidden px-4 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
            <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
            <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#003366] hover:underline">Reset Pencarian</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[12px] border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-3 text-left">Kode Pesanan</th>
                        <th class="px-3 py-3 text-left">Pemesan / Instansi</th>
                        <th class="px-3 py-3 text-left">Kanal</th>
                        <th class="px-3 py-3 text-left">Unit Pengelola &amp; Layanan</th>
                        <th class="px-3 py-3 text-left">Tipe Order</th>
                        <th class="px-3 py-3 text-right">Nominal Total (Rp)</th>
                        <th class="px-3 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $ord)
                        @php
                            $firstItem = $ord->items->first();
                            $unitName = $firstItem?->product?->unit?->name ?? '-';
                            $productName = $firstItem?->product?->name ?? '-';
                            $extraItems = max(0, $ord->items->count() - 1);
                            $totalQty = (int) $ord->items->sum('quantity');
                            $customerName = $ord->segment === 'b2b'
                                ? ($ord->user?->company_name ?: ($ord->user?->name ?? '-'))
                                : ($ord->user?->name ?? '-');
                            $customerMeta = $ord->segment === 'b2b'
                                ? ('PIC: ' . ($ord->user?->name ?? '-') . ($ord->user?->npwp ? ' • NPWP Terdaftar' : ''))
                                : (($ord->user?->nim_nip ? 'NIM/NIP ' . $ord->user->nim_nip . ' • ' : '') . ($ord->user?->prodi_unit ?? 'Sivitas PNJ'));
                            $orderTotal = $ord->invoice
                                ? (float) $ord->invoice->grand_total
                                : round((float) $ord->items->sum('subtotal') * 1.11, 2);

                            $typeLabel = match ($ord->order_type) {
                                'ready_stock' => 'Ready Stock',
                                'pre_order' => 'Pre-Order',
                                'lab_reservation' => 'Reservasi Lab',
                                default => $ord->order_type,
                            };

                            $statusBadge = match ($ord->status) {
                                'menunggu_verifikasi' => ['Menunggu Verifikasi', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                                'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-sky-50 text-sky-700 ring-sky-600/20'],
                                'diproses' => ['Diproses Unit', 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
                                'siap_diambil' => ['Siap Diambil', 'bg-teal-50 text-teal-700 ring-teal-600/20'],
                                'selesai' => ['Selesai', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                                'dibatalkan' => ['Dibatalkan', 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                                default => [$ord->status, 'bg-slate-100 text-slate-700 ring-slate-600/20'],
                            };

                            $detailUrl = $ord->segment === 'b2b'
                                ? route('admin.rtpu.validasi-b2b', $ord)
                                : route('admin.rtpu.validasi-b2c', $ord);

                            $searchBlob = strtolower(implode(' ', [
                                $ord->order_code,
                                $customerName,
                                $customerMeta,
                                $ord->segment,
                                $unitName,
                                $productName,
                                $typeLabel,
                                $ord->invoice?->invoice_number ?? '',
                                $statusBadge[0],
                            ]));
                        @endphp
                        <tr data-searchable="true" data-search-text="{{ $searchBlob }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 text-left align-top tabular-nums">
                                <div class="font-mono font-bold text-[#003366] text-[11.5px]">{{ $ord->order_code }}</div>
                                <div class="text-[10.5px] text-slate-400 mt-0.5">{{ optional($ord->created_at)->format('d/m/Y H:i') }} WIB</div>
                                @if ($ord->invoice)
                                    <div class="font-mono text-[10px] text-slate-500 mt-0.5">{{ $ord->invoice->invoice_number }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-left align-top">
                                <div class="font-bold text-slate-900">{{ $customerName }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $customerMeta }}</div>
                            </td>
                            <td class="px-3 py-3 text-left align-top">
                                @if ($ord->segment === 'b2b')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20 text-[10.5px] font-bold">B2B</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-600/20 text-[10.5px] font-bold">B2C</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-left align-top">
                                <div class="font-semibold text-slate-800">
                                    {{ $productName }}
                                    @if ($extraItems > 0)
                                        <span class="text-[10.5px] text-slate-500">(+{{ $extraItems }} item)</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $unitName }} &bull; <span class="tabular-nums">{{ $totalQty }} qty</span></div>
                            </td>
                            <td class="px-3 py-3 text-left align-top">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-300/70 text-[10.5px] font-semibold whitespace-nowrap">
                                    {{ $typeLabel }}
                                </span>
                                @if ($ord->booking_date)
                                    <div class="text-[10.5px] text-slate-500 mt-1 tabular-nums">{{ $ord->booking_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-right align-top tabular-nums">
                                <div class="font-extrabold text-slate-900 whitespace-nowrap">{{ number_format($orderTotal, 0, ',', '.') }}</div>
                                @if ($ord->invoice)
                                    <div class="text-[10.5px] mt-0.5 font-semibold {{ $ord->invoice->payment_status === 'paid' ? 'text-emerald-700' : ($ord->invoice->payment_status === 'expired' ? 'text-rose-600' : 'text-amber-700') }}">
                                        {{ $ord->invoice->payment_status === 'paid' ? 'Lunas 100%' : ($ord->invoice->payment_status === 'expired' ? 'Kedaluwarsa' : 'Menunggu Bayar') }}
                                    </div>
                                @else
                                    <div class="text-[10.5px] text-slate-400 mt-0.5">Belum Invoice</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-left align-top">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-bold ring-1 ring-inset whitespace-nowrap {{ $statusBadge[1] }}">
                                    {{ $statusBadge[0] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right align-top">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if ($ord->invoice)
                                        <a href="{{ route('admin.rtpu.print.invoice', $ord) }}" target="_blank" rel="noopener noreferrer"
                                           title="Cetak Invoice Resmi (.PDF)"
                                           class="p-1.5 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 transition-all active:scale-95">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ $detailUrl }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[11.5px] font-semibold transition-all active:scale-95 whitespace-nowrap">
                                        <span>{{ $ord->segment === 'b2b' ? 'Validasi B2B' : 'Validasi B2C' }}</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <p class="text-[13.5px] font-bold text-slate-800">Tidak ada pesanan ditemukan</p>
                                <p class="text-[12px] text-slate-500 mt-1">Ubah filter kanal, status, unit pengelola, atau kata kunci pencarian Anda.</p>
                                <a href="{{ route('admin.rtpu.antrean') }}"
                                   class="mt-3 inline-flex px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700">
                                    Tampilkan Semua Pesanan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Empty State Live Search --}}
        <div id="liveSearchEmptyState" class="hidden p-10 text-center border-t border-slate-100">
            <p class="text-[13.5px] font-bold text-slate-800">Tidak ada hasil untuk "<span id="liveSearchEmptyQuery"></span>"</p>
            <p class="text-[12px] text-slate-500 mt-1">Tekan Enter untuk pencarian penuh di server, atau klik reset.</p>
            <button type="button" onclick="clearGlobalSearch()"
                    class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                Reset Pencarian
            </button>
        </div>

        {{-- Footer Pagination --}}
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-[11.5px] text-slate-500 tabular-nums">
            <span>Menampilkan {{ $orders->count() }} dari {{ $orders->total() }} pesanan</span>
            <div>{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
