@extends('admin-rtpu.layouts.app')

@section('title', 'Dashboard Utama RTPU')

@section('content')
<div class="space-y-5">
    {{-- Header Halaman Persis Mockup 01 --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Dashboard Utama RTPU
            </h1>
            <p class="text-[13px] text-slate-500 mt-0.5">
                Ringkasan aktivitas transaksi produk, jasa, dan reservasi fasilitas.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-slate-200/90 text-[12.5px] font-semibold text-slate-700 shadow-[0_1px_2px_rgba(15,23,42,0.03)] tabular-nums">
                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span>Semester Ganjil 2026/2027</span>
            </div>
            <a href="{{ route('admin.rtpu.print.laporan', array_filter(['category' => $activeCategory !== 'semua' ? $activeCategory : null, 'q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-[0.98]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Ekspor Laporan</span>
            </a>
        </div>
    </div>

    {{-- Baris 4 Kartu KPI Persis Mockup 01 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- KPI 1: Pesanan Baru Masuk --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Pesanan Baru Masuk</p>
                <div class="w-9 h-9 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[28px] font-extrabold text-orange-500 leading-none tabular-nums">{{ $totalOrders }}</p>
                <p class="mt-2.5 text-[11.5px] font-medium text-slate-500 flex items-center gap-1 tabular-nums">
                    <svg width="14" height="14" class="w-3.5 h-3.5 text-orange-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    <span><strong class="text-orange-600">+{{ $recentOrdersToday }}</strong> pesanan 24 jam terakhir</span>
                </p>
            </div>
        </div>

        {{-- KPI 2: Menunggu Persetujuan Pesanan --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500 leading-snug">Menunggu Persetujuan Pesanan</p>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[28px] font-extrabold text-blue-600 leading-none tabular-nums">{{ $waitingValidationOrders }}</p>
                <p class="mt-2.5 text-[11.5px] font-medium text-slate-500 flex items-center gap-1.5">
                    <svg width="14" height="14" class="w-3.5 h-3.5 text-blue-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Butuh verifikasi &amp; penerbitan invoice</span>
                </p>
            </div>
        </div>

        {{-- KPI 3: Produk & Layanan Aktif --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Produk &amp; Layanan Aktif</p>
                <div class="w-9 h-9 rounded-lg bg-[#F0FDFA] text-[#00877A] flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6"/><path d="M10 3v6.5L5.2 18.2A2 2 0 0 0 6.9 21h10.2a2 2 0 0 0 1.7-2.8L14 9.5V3"/><path d="M8.5 14h7"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[28px] font-extrabold text-[#00877A] leading-none tabular-nums">{{ $activeCatalogCount }}</p>
                <p class="mt-2.5 text-[11.5px] font-medium text-slate-500 flex items-center gap-1.5 tabular-nums">
                    <svg width="14" height="14" class="w-3.5 h-3.5 text-[#00877A] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Dari total <strong class="text-slate-700">{{ $totalCatalogCount }}</strong> item di katalog</span>
                </p>
            </div>
        </div>

        {{-- KPI 4: Total Pendapatan (Bulan Ini) --}}
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500 leading-snug">Total Pendapatan (Bulan Ini)</p>
                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[22px] font-extrabold text-slate-900 leading-tight tabular-nums truncate">
                    Rp {{ number_format($validatedRevenue, 0, ',', '.') }}
                </p>
                <div class="mt-2 space-y-0.5 text-[11px] font-medium text-slate-500 tabular-nums">
                    <p class="text-[#00877A] font-bold leading-snug">&uarr; 100% Lunas Tervalidasi BLU</p>
                    <p class="leading-snug truncate">Total tagihan: <strong class="text-slate-700">Rp {{ number_format($totalInvoicedValue, 0, ',', '.') }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    {{-- 1 Kartu Tabel Full-Width Persis Mockup 01 --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] overflow-hidden">
        {{-- Header Kartu + Filter Pill Kategori + Lihat Semua --}}
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-[16px] font-extrabold text-slate-900 leading-snug">
                    Menunggu Tindakan (Validasi Pesanan &amp; Terbitkan Invoice)
                </h2>
                <p class="text-[12.5px] text-slate-500 mt-0.5">
                    Daftar permohonan transaksi B2B dan B2C yang membutuhkan persetujuan dan penerbitan invoice resmi.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-[12px] tabular-nums">
                @php
                    $catPills = [
                        'semua' => ['Semua', $categoryCounts['semua']],
                        'BRG' => ['Produk Barang', $categoryCounts['BRG']],
                        'JSA' => ['Produk Jasa', $categoryCounts['JSA']],
                        'LAB' => ['Fasilitas/Ruangan', $categoryCounts['LAB']],
                    ];
                @endphp
                @foreach ($catPills as $catKey => [$catLabel, $catCount])
                    <a href="{{ route('admin.rtpu.dashboard', array_filter(['category' => $catKey !== 'semua' ? $catKey : null, 'q' => $search])) }}"
                       class="px-3 py-1.5 rounded-lg font-semibold transition-all {{ $activeCategory === $catKey ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        {{ $catLabel }} ({{ $catCount }})
                    </a>
                @endforeach

                <a href="{{ route('admin.rtpu.antrean') }}"
                   class="ml-1 px-2 py-1.5 text-[#00877A] hover:text-[#006E63] font-bold inline-flex items-center gap-1 transition-colors">
                    <span>Lihat Semua</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Live Search Banner --}}
        <div id="liveSearchBanner" class="hidden px-6 py-2.5 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
            <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
            <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#00877A] hover:underline">Reset Pencarian</button>
        </div>

        {{-- Tabel Full-Width Proporsional 1366px (Sidebar Terbuka Tanpa Slider Horizontal) --}}
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full lg:table-fixed text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="lg:w-[14%] px-2.5 py-2.5 text-left">ID PESANAN</th>
                        <th class="lg:w-[19%] px-2.5 py-2.5 text-left">NAMA PEMESAN</th>
                        <th class="lg:w-[21%] px-2.5 py-2.5 text-left">ITEM / LAYANAN</th>
                        <th class="lg:w-[13%] px-2.5 py-2.5 text-left">JADWAL</th>
                        <th class="lg:w-[15%] px-2.5 py-2.5 text-left">STATUS</th>
                        <th class="lg:w-[18%] px-2.5 py-2.5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentOrders as $ord)
                        @php
                            $payload = $orderPayloads[$ord->id];
                            $firstItem = $ord->items->first();
                            $unitName = $firstItem?->product?->unit?->name ?? '-';
                            $productName = $firstItem?->product?->name ?? '-';
                            $customerTitle = $ord->segment === 'b2b'
                                ? ($ord->user?->company_name ?: ($ord->user?->name ?? '-'))
                                : ($ord->user?->name ?? '-');
                            $customerSub = $ord->segment === 'b2b'
                                ? ('PIC: ' . ($ord->user?->name ?? '-'))
                                : (($ord->user?->prodi_unit ?? 'Sivitas PNJ') . ($ord->user?->nim_nip ? ' • ' . $ord->user->nim_nip : ''));
                            $scheduleDate = $ord->booking_date
                                ? $ord->booking_date->translatedFormat('d M Y')
                                : optional($ord->created_at)->translatedFormat('d M Y');
                            $scheduleTime = $ord->booking_slot
                                ?: (optional($ord->created_at)->format('H:i') . ' WIB');

                            $statusPill = match ($ord->status) {
                                'menunggu_verifikasi' => ['Menunggu Persetujuan', 'bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'],
                                'menunggu_pembayaran' => ['Menunggu Pelunasan', 'bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'],
                                'diproses' => ['Diproses Unit', 'bg-sky-50 text-sky-700 border-sky-200', 'bg-sky-500'],
                                'siap_diambil' => ['Siap Diambil', 'bg-teal-50 text-[#00877A] border-teal-200', 'bg-[#00877A]'],
                                'selesai' => ['Selesai / Lunas', 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'],
                                'dibatalkan' => ['Ditolak / Batal', 'bg-rose-50 text-rose-700 border-rose-200', 'bg-rose-500'],
                                default => [$ord->status, 'bg-slate-100 text-slate-700 border-slate-200', 'bg-slate-400'],
                            };

                            $searchBlob = strtolower(implode(' ', [
                                $ord->order_code,
                                $customerTitle,
                                $customerSub,
                                $productName,
                                $unitName,
                                $ord->segment,
                                $statusPill[0],
                                $ord->invoice?->invoice_number ?? '',
                            ]));
                        @endphp
                        <tr data-searchable="true" data-search-text="{{ $searchBlob }}" class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-2.5 py-2.5 align-middle">
                                <button type="button" onclick='openDetailBerkasModal(@json($payload))'
                                        class="inline-block max-w-full break-all text-left px-2 py-1 rounded-md bg-slate-100 hover:bg-slate-200/80 text-slate-700 font-mono text-[10.5px] font-bold tabular-nums transition-colors">
                                    #{{ $ord->order_code }}
                                </button>
                            </td>
                            <td class="px-2.5 py-2.5 align-middle">
                                <p class="font-bold text-slate-900 break-words leading-snug line-clamp-2">{{ $customerTitle }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 break-words leading-snug line-clamp-2">{{ $customerSub }}</p>
                            </td>
                            <td class="px-2.5 py-2.5 align-middle">
                                <p class="font-bold text-slate-900 break-words leading-snug line-clamp-2">{{ $productName }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 break-words leading-snug line-clamp-2">{{ $unitName }}</p>
                            </td>
                            <td class="px-2.5 py-2.5 align-middle tabular-nums">
                                <div class="flex items-start gap-1.5">
                                    <svg width="13" height="13" class="w-3.5 h-3.5 text-slate-400 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <div class="min-w-0">
                                        <p class="text-[11.5px] font-semibold text-slate-800 leading-snug">{{ $scheduleDate }}</p>
                                        <p class="text-[10.5px] text-slate-400 break-words leading-snug">{{ $scheduleTime }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2.5 py-2.5 align-middle">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full border text-[10.5px] font-semibold leading-tight {{ $statusPill[1] }}">
                                    <span class="w-1.5 h-1.5 shrink-0 rounded-full {{ $statusPill[2] }}"></span>
                                    <span class="break-words">{{ $statusPill[0] }}</span>
                                </span>
                            </td>
                            <td class="px-2.5 py-2.5 align-middle text-right">
                                <div class="flex flex-wrap items-center justify-end gap-1">
                                    @if (! in_array($ord->status, ['selesai', 'dibatalkan'], true))
                                        <button type="button" onclick='triggerRejectOrder(@json($payload))'
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-[11px] font-semibold transition-all active:scale-[0.98]">
                                            <span>&times;</span>
                                            <span>Tolak</span>
                                        </button>
                                        <button type="button" onclick='triggerIssueOrApprove(@json($payload))'
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[11px] font-semibold shadow-sm transition-all active:scale-[0.98]">
                                            <span>&check;</span>
                                            <span>{{ $ord->segment === 'b2b' ? 'Terbitkan Invoice' : ($ord->status === 'menunggu_pembayaran' ? 'Konfirmasi Lunas' : 'Terbitkan Invoice') }}</span>
                                        </button>
                                    @else
                                        <button type="button" onclick='openDetailBerkasModal(@json($payload))'
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-semibold transition-all active:scale-[0.98]">
                                            <span>Detail</span>
                                        </button>
                                        @if ($ord->invoice)
                                            <a href="{{ route('admin.rtpu.print.invoice', $ord) }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[11px] font-semibold transition-all active:scale-[0.98]">
                                                <span>Cetak Invoice</span>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-[13.5px] font-bold text-slate-800">
                                    {{ $search !== '' ? 'Tidak ada permohonan yang cocok dengan "' . $search . '"' : 'Belum ada data permohonan pada kategori ini' }}
                                </p>
                                @if ($search !== '')
                                    <button type="button" onclick="clearGlobalSearch()"
                                            class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700">
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
            <p class="text-[12px] text-slate-500 mt-1">Tekan Enter untuk pencarian penuh di server atau klik reset.</p>
            <button type="button" onclick="clearGlobalSearch()"
                    class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700">
                Reset Pencarian
            </button>
        </div>

        {{-- Footer Tabel Persis Mockup 01 --}}
        <div class="px-5 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[12px] text-slate-500 tabular-nums">
            <span>Menampilkan <span class="font-bold text-slate-800">{{ $recentOrders->count() }}</span> dari <span class="font-bold text-slate-800">{{ $recentOrders->total() }}</span> permohonan aktif</span>
            <div>{{ $recentOrders->links() }}</div>
        </div>
    </div>
</div>
@endsection
