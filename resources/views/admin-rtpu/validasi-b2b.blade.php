@extends('admin-rtpu.layouts.app')

@section('title', 'Verifikasi Pesanan B2B')

@section('content')
<div class="space-y-5">
    {{-- Header Halaman Persis Mockup 03b --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Dashboard Admin RTPU &ndash; Verifikasi Pesanan B2B
            </h1>
            <p class="text-[13px] text-slate-500 mt-0.5">
                Kelola verifikasi pengajuan kemitraan, telaah spesifikasi teknis laboratorium, dan penerbitan invoice resmi.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button type="button" onclick="openLogVerifikasiModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12.5px] font-semibold text-slate-700 shadow-[0_1px_2px_rgba(15,23,42,0.03)] transition-all active:scale-[0.98]">
                <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Log Verifikasi</span>
            </button>
            <a href="{{ route('admin.rtpu.print.laporan', array_filter(['segment' => 'b2b', 'category' => $activeCategory !== 'semua' ? $activeCategory : null, 'q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-[0.98]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Ekspor Laporan</span>
            </a>
        </div>
    </div>

    {{-- 4 Kartu KPI Khusus B2B Persis Mockup 03b --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 tabular-nums">
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Pesanan Masuk (Pending)</p>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $b2bStats['pending'] }} Pesanan</p>
                <p class="mt-2.5 text-[11.5px] font-semibold text-amber-600">&bull; Memerlukan verifikasi &amp; invoice</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Total Nilai Antrean</p>
                <div class="w-9 h-9 rounded-lg bg-[#F0FDFA] text-[#00877A] flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[22px] font-extrabold text-slate-900 leading-tight truncate">
                    Rp {{ number_format($b2bStats['queue_value'], 0, ',', '.') }}
                </p>
                <p class="mt-2 text-[11.5px] font-semibold text-[#00877A]">&nearr; {{ $b2bStats['total_b2b_orders'] }} dokumen tagihan B2B tercatat</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Invoice Diterbitkan (Bulan Ini)</p>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $b2bStats['issued_invoices'] }} Invoice</p>
                <p class="mt-2.5 text-[11.5px] font-semibold text-blue-600 truncate">
                    &check; Realisasi lunas: Rp {{ number_format($b2bStats['paid_revenue'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Rata-rata Waktu Verifikasi</p>
                <div class="w-9 h-9 rounded-lg bg-[#F0FDFA] text-[#00877A] flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[26px] font-extrabold text-slate-900 leading-none">&lt; 24 Jam</p>
                <p class="mt-2.5 text-[11.5px] font-semibold text-[#00877A]">&check; Sesuai SLA Mutu Pelayanan RTPU</p>
            </div>
        </div>
    </div>

    {{-- Kartu Tabel Full-Width Antrean B2B Persis Mockup 03b --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-[15.5px] font-extrabold text-slate-900 leading-snug">
                        Antrean Verifikasi Permohonan Layanan B2B
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-[#F0FDFA] text-[#00877A] border border-[#99F6E4] text-[11px] font-bold tabular-nums whitespace-nowrap">
                        {{ $b2bStats['total_b2b_orders'] }} Antrean Aktif
                    </span>
                </div>
                <p class="text-[12px] text-slate-500 mt-0.5">
                    Verifikasi kelayakan berkas teknis sebelum menerbitkan faktur tagihan komersial BLU (100% Lunas).
                </p>
            </div>

            {{-- Pill Kategori Kompak 1 Baris Persis Mockup 03b --}}
            <div class="inline-flex flex-nowrap items-center gap-1 p-1 rounded-lg bg-slate-100/90 border border-slate-200/70 text-xs tabular-nums shrink-0 overflow-x-auto">
                @php
                    $b2bTabs = [
                        'semua' => ['Semua Antrean (' . $categoryCounts['semua'] . ')'],
                        'LAB' => ['Uji Laboratorium'],
                        'JSA' => ['Konsultasi Industri'],
                        'BRG' => ['Sewa / Produk'],
                    ];
                @endphp
                @foreach ($b2bTabs as $catKey => [$catLabel])
                    <a href="{{ route('admin.rtpu.validasi-b2b.index', array_filter(['category' => $catKey !== 'semua' ? $catKey : null, 'q' => $search])) }}"
                       class="px-3 py-1.5 rounded-md font-semibold whitespace-nowrap transition-all {{ $activeCategory === $catKey ? 'bg-white text-[#00877A] shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ $catLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        <div id="liveSearchBanner" class="hidden px-5 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
            <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
            <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#00877A] hover:underline">Reset Pencarian</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px] border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-4 py-3 text-left whitespace-nowrap">ID PESANAN</th>
                        <th class="px-3 py-3 text-left">NAMA INSTANSI/PEMESAN</th>
                        <th class="px-3 py-3 text-left">LAYANAN</th>
                        <th class="px-3 py-3 text-left whitespace-nowrap">STATUS</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($b2bOrders as $ord)
                        @php
                            $payload = $orderPayloads[$ord->id];
                            $firstItem = $ord->items->first();
                            $productName = $firstItem?->product?->name ?? '-';
                            $unitName = $firstItem?->product?->unit?->name ?? '-';
                            $companyName = $ord->user?->company_name ?: ($ord->user?->name ?? '-');
                            $totalVal = $payload['financials']['grand_total'];

                            $priorityText = match ($ord->order_type) {
                                'lab_reservation' => 'Jalur Cepat',
                                'pre_order' => 'Prioritas Industri',
                                default => 'Reguler Standard',
                            };

                            $statusPill = match ($ord->status) {
                                'menunggu_verifikasi' => ['Menunggu Approval', 'bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'],
                                'menunggu_pembayaran' => ['Invoice Terbit (Unpaid)', 'bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'],
                                'diproses' => ['Lunas • Diproses Unit', 'bg-sky-50 text-sky-700 border-sky-200', 'bg-sky-500'],
                                'siap_diambil' => ['Siap Diserahterimakan', 'bg-teal-50 text-[#00877A] border-teal-200', 'bg-[#00877A]'],
                                'selesai' => ['Selesai / Lunas', 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'],
                                'dibatalkan' => ['Ditolak / Dibatalkan', 'bg-rose-50 text-rose-700 border-rose-200', 'bg-rose-500'],
                                default => [$ord->status, 'bg-slate-100 text-slate-700 border-slate-200', 'bg-slate-400'],
                            };

                            $searchBlob = strtolower(implode(' ', [
                                $ord->order_code,
                                $companyName,
                                $ord->user?->name ?? '',
                                $ord->user?->npwp ?? '',
                                $productName,
                                $unitName,
                                $ord->invoice?->invoice_number ?? '',
                                $statusPill[0],
                            ]));
                        @endphp
                        <tr data-searchable="true" data-search-text="{{ $searchBlob }}" class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3.5 align-top whitespace-nowrap tabular-nums">
                                <p class="font-mono font-extrabold text-[#00877A] text-[12px]">{{ $ord->order_code }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ optional($ord->created_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                                <p class="mt-1.5 text-[11px] font-bold text-[#00877A] inline-flex items-center gap-1">
                                    <svg width="13" height="13" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                                    <span>{{ $priorityText }}</span>
                                </p>
                            </td>
                            <td class="px-3 py-3.5 align-top">
                                <p class="font-extrabold text-slate-900 text-[13px] leading-snug">{{ $companyName }}</p>
                                <p class="text-[11.5px] text-slate-500 mt-0.5">PIC: {{ $ord->user?->name ?? '-' }}</p>
                                <p class="mt-1.5 text-[11px] font-bold text-[#00877A] inline-flex items-center gap-1">
                                    <svg width="13" height="13" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <span>Kontak terverifikasi</span>
                                </p>
                            </td>
                            <td class="px-3 py-3.5 align-top min-w-[210px]">
                                <p class="font-extrabold text-slate-900 text-[13px] leading-snug">{{ $productName }}</p>
                                <p class="text-[11.5px] text-slate-500 mt-0.5">
                                    {{ $unitName }} &bull; {{ $ord->estimasi_hari ?? 7 }} Hari Kerja
                                </p>
                                <p class="text-[12px] font-extrabold text-slate-900 mt-1.5 tabular-nums whitespace-nowrap">
                                    Estimasi: Rp {{ number_format($totalVal, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-3 py-3.5 align-middle whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[11px] font-semibold {{ $statusPill[1] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusPill[2] }}"></span>
                                    <span>{{ $statusPill[0] }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3.5 align-middle text-right whitespace-nowrap">
                                {{-- Susunan Vertikal Rapi Persis Mockup 03b --}}
                                <div class="inline-flex flex-col items-end gap-1.5">
                                    @if ((! $ord->invoice || $ord->invoice->payment_status !== 'paid') && $ord->status !== 'dibatalkan')
                                        <button type="button" onclick='openInvoiceB2bModal(@json($payload))'
                                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-bold whitespace-nowrap shadow-sm transition-all active:scale-[0.98]">
                                            <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            <span>Approve &amp; Terbitkan Invoice</span>
                                        </button>
                                    @elseif ($ord->invoice)
                                        <a href="{{ route('admin.rtpu.print.invoice', $ord) }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-bold whitespace-nowrap shadow-sm transition-all active:scale-[0.98]">
                                            <span>Cetak Invoice (.PDF)</span>
                                        </a>
                                    @endif

                                    <div class="inline-flex items-center justify-end gap-3 text-[11.5px]">
                                        @if ($ord->invoice && $ord->invoice->payment_status !== 'paid')
                                            <a href="{{ route('admin.rtpu.print.invoice', $ord) }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1 font-semibold text-[#00877A] hover:text-[#006E63] transition-colors whitespace-nowrap">
                                                <svg width="13" height="13" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                                <span>Cetak Invoice (.PDF)</span>
                                            </a>
                                        @endif

                                        <button type="button" onclick='openDetailBerkasModal(@json($payload))'
                                                class="inline-flex items-center gap-1 font-semibold text-slate-500 hover:text-[#00877A] transition-colors whitespace-nowrap">
                                            <svg width="13" height="13" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <span>Detail Berkas</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                Belum ada permohonan layanan B2B pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="liveSearchEmptyState" class="hidden p-10 text-center border-t border-slate-100">
            <p class="text-[13.5px] font-bold text-slate-800">Tidak ada pesanan B2B cocok "<span id="liveSearchEmptyQuery"></span>"</p>
            <button type="button" onclick="clearGlobalSearch()" class="mt-2 px-3 py-1.5 rounded-lg border border-slate-200 text-[12px] font-semibold text-slate-700">Reset</button>
        </div>

        <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between text-[12px] text-slate-500 tabular-nums">
            <span>Menampilkan <span class="font-bold text-slate-800">{{ $b2bOrders->count() }}</span> dari <span class="font-bold text-slate-800">{{ $b2bOrders->total() }}</span> antrean pesanan B2B</span>
            <div>{{ $b2bOrders->links() }}</div>
        </div>
    </div>

    {{-- Banner SOP Bawah Persis Mockup 03b --}}
    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-white border border-emerald-200 text-[#00877A] flex items-center justify-center shrink-0">
                <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
            </div>
            <div>
                <p class="text-[13px] font-extrabold text-slate-900">Prosedur Pengawasan Mutu &amp; Kepatuhan Keuangan (BLU Politeknik Negeri Jakarta)</p>
                <p class="text-[12px] text-slate-600 mt-0.5">
                    Setiap persetujuan transaksi secara otomatis menerbitkan dokumen penagihan berbasis Nomor Induk Kemitraan RTPU dan terintegrasi dengan SIM-Keuangan BLU PNJ.
                </p>
            </div>
        </div>
        <button type="button" onclick="openGuideModal()"
                class="text-[12.5px] font-bold text-[#00877A] hover:text-[#006E63] shrink-0 whitespace-nowrap">
            Panduan SOP RTPU &rarr;
        </button>
    </div>
</div>
@endsection

@if ($autoOpenPayload)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            openDetailBerkasModal(@json($autoOpenPayload));
        });
    </script>
    @endpush
@endif
