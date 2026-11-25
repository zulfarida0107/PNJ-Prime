@extends('admin-rtpu.layouts.app')

@section('title', 'Dashboard Eksekutif Admin RTPU')

@section('content')
<div class="space-y-4">
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                UPA-RTPU Politeknik Negeri Jakarta &bull; Manajemen Operasional &amp; Keuangan
            </p>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug mt-0.5">
                Dashboard Eksekutif Admin RTPU
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Pantau transaksi masuk B2C &amp; B2B, validasi operasional unit, serta penerbitan tagihan resmi BLU PNJ secara terpusat.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.rtpu.antrean') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <span>Antrean Lengkap</span>
            </a>
            <a href="{{ route('admin.rtpu.print.laporan', array_filter(['q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak Laporan Operasional (.PDF)</span>
            </a>
        </div>
    </div>

    {{-- 4 KPI Cards Berbasis Data Aktual Database --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        {{-- KPI 1: Total Pesanan Masuk --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Total Pesanan Masuk</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $totalOrders }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Pesanan</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/70 text-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2.5 text-[11px] font-semibold text-slate-600 tabular-nums">
                <span>{{ $b2cOrdersCount }} B2C</span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-[#003366]">{{ $b2bOrdersCount }} B2B</span>
            </div>
        </div>

        {{-- KPI 2: Pesanan Menunggu Validasi --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Pesanan Menunggu Validasi</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $waitingValidationOrders }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Antrean</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200/70 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 text-[10.5px] font-bold">
                    Perlu Verifikasi &amp; Tagihan
                </span>
                <a href="{{ route('admin.rtpu.antrean', ['status' => 'perlu_validasi']) }}" class="text-[11px] font-bold text-[#003366] hover:underline">Tinjau &rarr;</a>
            </div>
        </div>

        {{-- KPI 3: Produk Menunggu Kurasi --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Produk Menunggu Kurasi</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $waitingCurationProducts }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Usulan P3M</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-[#EFF6FF] border border-[#BFDBFE] text-[#003366] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] font-semibold text-slate-600 tabular-nums">
                {{ $activeCatalogCount }} aktif tayang dari {{ $totalCatalogCount }} katalog
            </p>
        </div>

        {{-- KPI 4: Total Pendapatan Tervalidasi --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11.5px] font-semibold text-slate-500">Pendapatan Tervalidasi (Lunas)</p>
                    <div class="mt-1.5 tabular-nums">
                        <span class="text-[21px] font-extrabold text-slate-900 leading-none block truncate">
                            Rp {{ number_format($validatedRevenue, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200/70 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] font-semibold text-slate-500 tabular-nums truncate">
                Tagihan berjalan: <strong class="text-slate-800">Rp {{ number_format($unpaidInvoicedTotal, 0, ',', '.') }}</strong>
            </p>
        </div>
    </div>

    {{-- Grid Utama 2 Kolom (Kiri 8, Kanan 4) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        {{-- Panel Kiri: Daftar Pesanan & Aktivitas Terbaru --}}
        <div class="lg:col-span-8 bg-white rounded-lg border border-slate-200/80 overflow-hidden">
            <div class="px-4 pt-4 pb-3 border-b border-slate-100 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-[14.5px] font-extrabold text-slate-900">Daftar Pesanan &amp; Aktivitas Operasional Terbaru</h2>
                    <p class="text-[12px] text-slate-500 mt-0.5">
                        Akses langsung validasi pesanan kanal B2C dan penerbitan invoice komersial B2B.
                    </p>
                </div>
                <a href="{{ route('admin.rtpu.antrean') }}"
                   class="text-[12px] font-bold text-[#003366] hover:text-[#1E3A8A] shrink-0 inline-flex items-center gap-1 transition-colors">
                    <span>Lihat Semua</span>
                    <span>&rarr;</span>
                </a>
            </div>

            {{-- Filter Tab Cepat --}}
            <div class="px-4 pt-2.5 border-b border-slate-100 flex items-center gap-5 text-[12px] overflow-x-auto">
                @php
                    $tabs = [
                        'semua' => ['label' => 'Semua', 'count' => $tabCounts['semua']],
                        'b2c' => ['label' => 'B2C', 'count' => $tabCounts['b2c']],
                        'b2b' => ['label' => 'B2B', 'count' => $tabCounts['b2b']],
                        'perlu_validasi' => ['label' => 'Perlu Validasi', 'count' => $tabCounts['perlu_validasi']],
                    ];
                @endphp
                @foreach ($tabs as $key => $info)
                    <a href="{{ route('admin.rtpu.dashboard', array_filter(['tab' => $key, 'q' => $search])) }}"
                       class="pb-2.5 font-semibold whitespace-nowrap border-b-2 transition-colors inline-flex items-center gap-1.5 tabular-nums {{ $activeTab === $key ? 'border-[#003366] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        <span>{{ $info['label'] }}</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $activeTab === $key ? 'bg-[#EFF6FF] text-[#003366]' : 'bg-slate-100 text-slate-500' }}">
                            {{ $info['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Indikator Hasil Live Search --}}
            <div id="liveSearchBanner" class="hidden px-4 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
                <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
                <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#003366] hover:underline">Reset Pencarian</button>
            </div>

            {{-- Tabel Pesanan Keuangan Presisi --}}
            <div class="overflow-x-auto">
                <table class="w-full text-[12px] border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="px-4 py-2.5 text-left">Kode &amp; Pemesan</th>
                            <th class="px-3 py-2.5 text-left">Kanal</th>
                            <th class="px-3 py-2.5 text-left">Layanan &amp; Unit</th>
                            <th class="px-3 py-2.5 text-right">Nominal (Rp)</th>
                            <th class="px-3 py-2.5 text-left">Status</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentOrders as $ord)
                            @php
                                $firstItem = $ord->items->first();
                                $unitName = $firstItem?->product?->unit?->name ?? '-';
                                $productName = $firstItem?->product?->name ?? 'Item Pesanan';
                                $extraItems = max(0, $ord->items->count() - 1);
                                $customerTitle = $ord->segment === 'b2b'
                                    ? ($ord->user?->company_name ?: ($ord->user?->name ?? '-'))
                                    : ($ord->user?->name ?? '-');
                                $customerSub = $ord->segment === 'b2b'
                                    ? ('PIC: ' . ($ord->user?->name ?? '-'))
                                    : ($ord->user?->prodi_unit ?: ($ord->user?->email ?? '-'));
                                $orderTotal = $ord->invoice
                                    ? (float) $ord->invoice->grand_total
                                    : round((float) $ord->items->sum('subtotal') * 1.11, 2);
                                $validateRoute = $ord->segment === 'b2b'
                                    ? route('admin.rtpu.validasi-b2b', $ord)
                                    : route('admin.rtpu.validasi-b2c', $ord);

                                $statusBadge = match ($ord->status) {
                                    'menunggu_verifikasi' => ['Menunggu Verifikasi', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                                    'menunggu_pembayaran' => ['Menunggu Pelunasan', 'bg-sky-50 text-sky-700 ring-sky-600/20'],
                                    'diproses' => ['Diproses Unit', 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
                                    'siap_diambil' => ['Siap Diambil', 'bg-teal-50 text-teal-700 ring-teal-600/20'],
                                    'selesai' => ['Selesai', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                                    'dibatalkan' => ['Dibatalkan', 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                                    default => [$ord->status, 'bg-slate-100 text-slate-700 ring-slate-600/20'],
                                };

                                $searchBlob = strtolower(implode(' ', [
                                    $ord->order_code,
                                    $customerTitle,
                                    $customerSub,
                                    $ord->segment,
                                    $ord->order_type,
                                    $productName,
                                    $unitName,
                                    $ord->invoice?->invoice_number ?? '',
                                    $statusBadge[0],
                                ]));
                            @endphp
                            <tr data-searchable="true" data-search-text="{{ $searchBlob }}" class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3 text-left align-top">
                                    <div class="font-mono font-bold text-[#003366] text-[11.5px] tabular-nums">{{ $ord->order_code }}</div>
                                    <div class="font-bold text-slate-900 text-[12.5px] mt-0.5 leading-snug">{{ $customerTitle }}</div>
                                    <div class="text-[11px] text-slate-500 truncate max-w-[200px]">{{ $customerSub }}</div>
                                </td>
                                <td class="px-3 py-3 text-left align-top">
                                    @if ($ord->segment === 'b2b')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20 text-[10.5px] font-bold">
                                            B2B
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-600/20 text-[10.5px] font-bold">
                                            B2C
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-left align-top">
                                    <div class="font-semibold text-slate-800 leading-snug">
                                        {{ $productName }}
                                        @if ($extraItems > 0)
                                            <span class="text-[10.5px] text-slate-500 font-medium">(+{{ $extraItems }} item)</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $unitName }}</div>
                                </td>
                                <td class="px-3 py-3 text-right align-top tabular-nums">
                                    <div class="font-extrabold text-slate-900 whitespace-nowrap">
                                        {{ number_format($orderTotal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10.5px] mt-0.5 {{ $ord->invoice?->payment_status === 'paid' ? 'text-emerald-700 font-semibold' : 'text-slate-400' }}">
                                        {{ $ord->invoice?->payment_status === 'paid' ? 'Lunas 100%' : ($ord->invoice ? 'Belum Lunas' : 'Estimasi') }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-left align-top">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-bold ring-1 ring-inset whitespace-nowrap {{ $statusBadge[1] }}">
                                        {{ $statusBadge[0] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right align-top">
                                    <a href="{{ $validateRoute }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white hover:bg-[#003366] border border-slate-200/90 hover:border-[#003366] text-slate-700 hover:text-white text-[11.5px] font-semibold transition-all active:scale-95 whitespace-nowrap">
                                        <span>Validasi</span>
                                        <span>&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center">
                                    <p class="text-[13px] font-bold text-slate-800">
                                        {{ $search !== '' ? 'Tidak ada pesanan yang cocok dengan "' . $search . '"' : 'Belum ada data pesanan pada tab ini' }}
                                    </p>
                                    <p class="text-[12px] text-slate-500 mt-1">
                                        {{ $search !== '' ? 'Gunakan kata kunci kode pesanan, instansi, atau unit lainnya.' : 'Pesanan baru dari portal B2C maupun B2B akan muncul secara otomatis di sini.' }}
                                    </p>
                                    @if ($search !== '')
                                        <button type="button" onclick="clearGlobalSearch()"
                                                class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                                            Reset Pencarian
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Empty State Live Search --}}
            <div id="liveSearchEmptyState" class="hidden p-10 text-center border-t border-slate-100">
                <p class="text-[13.5px] font-bold text-slate-800">Tidak ada hasil untuk "<span id="liveSearchEmptyQuery"></span>"</p>
                <p class="text-[12px] text-slate-500 mt-1">Tekan Enter untuk mencari di seluruh database, atau reset kata kunci pencarian.</p>
                <button type="button" onclick="clearGlobalSearch()"
                        class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                    Reset Pencarian
                </button>
            </div>

            {{-- Footer Pagination --}}
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-[11.5px] text-slate-500 tabular-nums">
                <span>Menampilkan {{ $recentOrders->count() }} dari {{ $recentOrders->total() }} pesanan tercatat</span>
                <div>{{ $recentOrders->links() }}</div>
            </div>
        </div>

        {{-- Panel Kanan: Komposisi Kanal B2C vs B2B + Kurasi P3M + Kebijakan BLU --}}
        <div class="lg:col-span-4 space-y-4">
            {{-- Ringkasan Komposisi Kanal Transaksi --}}
            <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-3.5">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                    <div>
                        <h2 class="text-[13.5px] font-extrabold text-slate-900">Komposisi Kanal Transaksi</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Perbandingan volume &amp; nilai tagihan B2C vs B2B</p>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold tabular-nums">
                        {{ $totalOrders }} Total
                    </span>
                </div>

                {{-- Progress Bar Gabungan --}}
                <div class="space-y-1.5">
                    <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden flex">
                        <div class="bg-slate-600 h-full transition-all" style="width: {{ $b2cShare }}%"></div>
                        <div class="bg-[#003366] h-full transition-all" style="width: {{ $b2bShare }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-semibold tabular-nums">
                        <span class="text-slate-600">B2C: {{ $b2cShare }}%</span>
                        <span class="text-[#003366]">B2B: {{ $b2bShare }}%</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2.5 tabular-nums">
                    <a href="{{ route('admin.rtpu.validasi-b2c.index') }}"
                       class="p-3 rounded-lg bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200/80 transition-colors flex items-center justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                                <span class="text-[12px] font-bold text-slate-900">Kanal B2C (Sivitas / Umum)</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $b2cOrdersCount }} pesanan tercatat</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[12.5px] font-extrabold text-slate-900">Rp {{ number_format($b2cRevenue, 0, ',', '.') }}</p>
                            <p class="text-[10.5px] text-[#003366] font-semibold">Validasi &rarr;</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.rtpu.validasi-b2b.index') }}"
                       class="p-3 rounded-lg bg-[#EFF6FF]/60 hover:bg-[#EFF6FF] border border-[#BFDBFE]/80 transition-colors flex items-center justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#003366]"></span>
                                <span class="text-[12px] font-bold text-slate-900">Kanal B2B (Mitra Industri)</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $b2bOrdersCount }} pesanan tercatat</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[12.5px] font-extrabold text-[#003366]">Rp {{ number_format($b2bRevenue, 0, ',', '.') }}</p>
                            <p class="text-[10.5px] text-[#003366] font-semibold">Validasi &amp; Invoice &rarr;</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Ringkasan Produk Menunggu Kurasi P3M --}}
            <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-3">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                    <h2 class="text-[13.5px] font-extrabold text-slate-900">Usulan Produk P3M Masuk</h2>
                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 text-[10px] font-bold tabular-nums">
                        {{ $waitingCurationProducts }} Menunggu
                    </span>
                </div>

                <div class="space-y-2">
                    @forelse ($curationPreview as $prod)
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200/70">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-[12px] font-bold text-slate-900 leading-snug">{{ $prod->name }}</p>
                                <span class="font-mono text-[10px] font-bold text-slate-500 shrink-0 tabular-nums">{{ $prod->code }}</span>
                            </div>
                            <div class="mt-1 flex items-center justify-between text-[11px] text-slate-500 tabular-nums">
                                <span>{{ $prod->unit->name ?? '-' }}</span>
                                <strong class="text-slate-800">Rp {{ number_format((float) $prod->price_b2c, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @empty
                        <p class="text-[12px] text-slate-500 text-center py-3">Tidak ada produk menunggu kurasi saat ini.</p>
                    @endforelse
                </div>

                <button type="button"
                        onclick="openBatch2Modal('Kurasi Produk P3M', 'Terdapat {{ $waitingCurationProducts }} produk dari Admin P3M yang menunggu kurasi. Halaman proses kurasi & penetapan tarif SK tersedia pada rilis Batch 2.')"
                        class="w-full py-2 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-[11.5px] font-semibold transition-all active:scale-95">
                    Antrean Kurasi P3M (Batch 2)
                </button>
            </div>

            {{-- Maklumat Tata Kelola Keuangan BLU --}}
            <div class="bg-white rounded-lg border border-slate-200/80 p-4">
                <div class="p-3 rounded-lg bg-slate-50 border-l-4 border-[#003366] space-y-1">
                    <p class="text-[12px] font-bold text-slate-900">Kepatuhan Penerimaan BLU PNJ</p>
                    <p class="text-[11.5px] text-slate-600 leading-relaxed">
                        Seluruh tagihan B2C &amp; B2B wajib menggunakan skema pembayaran <strong>100% Lunas</strong> ke rekening resmi BLU PNJ dengan komponen PPN 11% dan PPh 23 sesuai ketentuan perpajakan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
