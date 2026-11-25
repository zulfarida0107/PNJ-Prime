@extends('admin-rtpu.layouts.app')

@section('title', 'Verifikasi Pesanan Mahasiswa (B2C)')

@section('content')
<div class="space-y-5">
    {{-- Header Halaman Persis Mockup 03a --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Dashboard Admin RTPU &ndash; Verifikasi Pesanan Mahasiswa (B2C)
            </h1>
            <p class="text-[13px] text-slate-500 mt-0.5">
                Daftar permohonan transaksi sivitas dan umum (B2C) yang membutuhkan persetujuan dan penerbitan invoice resmi.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button type="button" onclick="openLogVerifikasiModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12.5px] font-semibold text-slate-700 shadow-[0_1px_2px_rgba(15,23,42,0.03)] transition-all active:scale-[0.98]">
                <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Log Verifikasi</span>
            </button>
            <a href="{{ route('admin.rtpu.print.laporan', array_filter(['segment' => 'b2c', 'category' => $activeCategory !== 'semua' ? $activeCategory : null, 'q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-[0.98]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Ekspor Laporan</span>
            </a>
        </div>
    </div>

    {{-- 4 Kartu KPI Khusus B2C Persis Mockup 03a --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 tabular-nums">
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Pesanan Masuk<br>(Pending)</p>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $b2cStats['pending'] }} Pesanan</p>
                <p class="mt-2.5 text-[11.5px] font-semibold text-amber-600">&bull; Memerlukan verifikasi &amp; VA</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Total Item Dipesan</p>
                <div class="w-9 h-9 rounded-lg bg-[#F0FDFA] text-[#00877A] flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6"/><path d="M10 3v6.5L5.2 18.2A2 2 0 0 0 6.9 21h10.2a2 2 0 0 0 1.7-2.8L14 9.5V3"/><path d="M8.5 14h7"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $b2cStats['total_items'] }} Item</p>
                <p class="mt-2.5 text-[11.5px] font-semibold text-[#00877A]">&nearr; {{ $b2cStats['active_items_processing'] }} item aktif diproses</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Virtual Account Aktif</p>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $b2cStats['active_va'] }} VA</p>
                <p class="mt-2.5 text-[11.5px] font-semibold text-blue-600">&check; Sinkronisasi SIM-Keuangan BLU</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] p-5 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[12.5px] font-semibold text-slate-500">Rata-rata Waktu<br>Verifikasi</p>
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

    {{-- Kartu Tabel Full-Width Antrean B2C Persis Mockup 03a --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <h2 class="text-[16px] font-extrabold text-slate-900 leading-snug">
                        Antrean Verifikasi Permohonan Mahasiswa B2C
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-[#F0FDFA] text-[#00877A] border border-[#99F6E4] text-[11px] font-bold tabular-nums">
                        {{ $b2cStats['total_b2c_orders'] }} Antrean Aktif
                    </span>
                </div>
                <p class="text-[12.5px] text-slate-500 mt-1">
                    Verifikasi kelayakan berkas K3 &amp; identitas pemesan mahasiswa sebelum menerbitkan invoice resmi.
                </p>
            </div>

            <div class="flex items-center gap-1.5 text-xs tabular-nums shrink-0 overflow-x-auto">
                @php
                    $b2cCats = [
                        'semua' => ['Semua Antrean', $categoryCounts['semua']],
                        'BRG' => ['Produk Barang', $categoryCounts['BRG']],
                        'JSA' => ['Produk Jasa', $categoryCounts['JSA']],
                        'LAB' => ['Fasilitas/Ruangan', $categoryCounts['LAB']],
                    ];
                @endphp
                @foreach ($b2cCats as $catKey => [$catLabel, $catCount])
                    <a href="{{ route('admin.rtpu.validasi-b2c.index', array_filter(['category' => $catKey !== 'semua' ? $catKey : null, 'q' => $search])) }}"
                       class="px-3 py-1.5 rounded-full font-semibold whitespace-nowrap transition-all {{ $activeCategory === $catKey ? 'bg-[#00877A] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70 hover:text-slate-900' }}">
                        {{ $catLabel }}{{ $catKey === 'semua' ? ' (' . $catCount . ')' : '' }}
                    </a>
                @endforeach
            </div>
        </div>

        <div id="liveSearchBanner" class="hidden px-6 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
            <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
            <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#00877A] hover:underline">Reset Pencarian</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[12.5px] border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3.5 text-left">ID PESANAN</th>
                        <th class="px-4 py-3.5 text-left">NAMA MAHASISWA / PEMESAN</th>
                        <th class="px-4 py-3.5 text-left">LAYANAN &amp; LABORATORIUM</th>
                        <th class="px-4 py-3.5 text-left">STATUS</th>
                        <th class="px-6 py-3.5 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($b2cOrders as $ord)
                        @php
                            $payload = $orderPayloads[$ord->id];
                            $firstItem = $ord->items->first();
                            $productName = $firstItem?->product?->name ?? '-';
                            $unitName = $firstItem?->product?->unit?->name ?? '-';
                            $totalVal = $payload['financials']['grand_total'];

                            $trackBadge = match ($ord->order_type) {
                                'lab_reservation' => ['Jalur Cepat / Lab', 'bg-[#F0FDFA] text-[#00877A] border-[#99F6E4]'],
                                'pre_order' => ['Pre-Order / Riset', 'bg-purple-50 text-purple-700 border-purple-200'],
                                default => ['Reguler Standard', 'bg-slate-100 text-slate-600 border-slate-200'],
                            };

                            $statusPill = match ($ord->status) {
                                'menunggu_verifikasi' => ['Menunggu Approval', 'bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'],
                                'menunggu_pembayaran' => ['Menunggu Pelunasan', 'bg-amber-50 text-amber-700 border-amber-200', 'bg-amber-500'],
                                'diproses' => ['Diproses Unit', 'bg-sky-50 text-sky-700 border-sky-200', 'bg-sky-500'],
                                'siap_diambil' => ['Siap Diambil', 'bg-teal-50 text-[#00877A] border-teal-200', 'bg-[#00877A]'],
                                'selesai' => ['Selesai / Lunas', 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bg-emerald-500'],
                                'dibatalkan' => ['Ditolak / Dibatalkan', 'bg-rose-50 text-rose-700 border-rose-200', 'bg-rose-500'],
                                default => [$ord->status, 'bg-slate-100 text-slate-700 border-slate-200', 'bg-slate-400'],
                            };

                            $searchBlob = strtolower(implode(' ', [
                                $ord->order_code,
                                $ord->user?->name ?? '',
                                $ord->user?->nim_nip ?? '',
                                $ord->user?->prodi_unit ?? '',
                                $productName,
                                $unitName,
                                $statusPill[0],
                            ]));
                        @endphp
                        <tr data-searchable="true" data-search-text="{{ $searchBlob }}" class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 align-top whitespace-nowrap tabular-nums">
                                <p class="font-mono font-extrabold text-slate-900 text-[12.5px]">{{ $ord->order_code }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ optional($ord->created_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                                <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-1 rounded-md border text-[10.5px] font-bold {{ $trackBadge[1] }}">
                                    {{ $trackBadge[0] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <p class="font-extrabold text-slate-900 text-[13px]">{{ $ord->user?->name ?? '-' }}</p>
                                <p class="text-[11.5px] text-slate-500 mt-0.5 tabular-nums">NIM/NIP: {{ $ord->user?->nim_nip ?? '-' }}</p>
                                <p class="text-[11.5px] text-slate-500">&bull; {{ $ord->user?->prodi_unit ?? 'Sivitas PNJ' }}</p>
                                <p class="mt-1.5 text-[11px] font-bold text-[#00877A] flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <span>Identitas Sivitas Terverifikasi</span>
                                </p>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <p class="font-extrabold text-slate-900 text-[13px] leading-snug">{{ $productName }}</p>
                                <p class="text-[11.5px] text-slate-500 mt-0.5">{{ $unitName }} &bull; {{ $ord->items->sum('quantity') }} Qty</p>
                                <p class="text-[11.5px] font-bold text-slate-700 mt-1.5 tabular-nums">
                                    Tarif B2C: Rp {{ number_format($totalVal, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-4 py-4 align-middle whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-[11.5px] font-semibold {{ $statusPill[1] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusPill[2] }}"></span>
                                    <span>{{ $statusPill[0] }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex flex-col items-center gap-1.5">
                                    @if (in_array($ord->status, ['menunggu_verifikasi', 'menunggu_pembayaran'], true))
                                        <button type="button" onclick='triggerIssueOrApprove(@json($payload))'
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-bold shadow-sm transition-all active:scale-[0.98]">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            <span>{{ $ord->status === 'menunggu_pembayaran' ? 'Konfirmasi Lunas & Proses' : 'Approve & Terbitkan Invoice' }}</span>
                                        </button>
                                    @elseif ($ord->invoice)
                                        <a href="{{ route('admin.rtpu.print.invoice', $ord) }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-bold shadow-sm transition-all active:scale-[0.98]">
                                            <span>Cetak Invoice (.PDF)</span>
                                        </a>
                                    @endif

                                    <button type="button" onclick='openDetailBerkasModal(@json($payload))'
                                            class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-slate-500 hover:text-[#00877A] transition-colors">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span>Detail Berkas</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada permohonan pesanan mahasiswa (B2C) pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="liveSearchEmptyState" class="hidden p-10 text-center border-t border-slate-100">
            <p class="text-[13.5px] font-bold text-slate-800">Tidak ada pesanan B2C cocok "<span id="liveSearchEmptyQuery"></span>"</p>
            <button type="button" onclick="clearGlobalSearch()" class="mt-2 px-3 py-1.5 rounded-lg border border-slate-200 text-[12px] font-semibold text-slate-700">Reset</button>
        </div>

        <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between text-[12px] text-slate-500 tabular-nums">
            <span>Menampilkan <span class="font-bold text-slate-800">{{ $b2cOrders->count() }}</span> dari <span class="font-bold text-slate-800">{{ $b2cOrders->total() }}</span> antrean pesanan B2C</span>
            <div>{{ $b2cOrders->links() }}</div>
        </div>
    </div>

    {{-- Banner SOP Bawah Persis Mockup 03a --}}
    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-white border border-emerald-200 text-[#00877A] flex items-center justify-center shrink-0">
                <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
            </div>
            <div>
                <p class="text-[13px] font-extrabold text-slate-900">Prosedur Pengawasan Mutu &amp; Kepatuhan Akademik (BLU Politeknik Negeri Jakarta)</p>
                <p class="text-[12px] text-slate-600 mt-0.5">
                    Setiap persetujuan transaksi otomatis menerbitkan invoice resmi 100% Lunas terintegrasi dengan SIM-Keuangan PNJ.
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
