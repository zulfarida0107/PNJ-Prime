@extends('admin-rtpu.layouts.app')

@section('title', 'Semua Antrean Validasi Pesanan & Terbitkan Invoice')

@section('content')
<div class="space-y-5">
    {{-- Breadcrumb & Header Persis Mockup 02 --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-[11.5px] font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.rtpu.dashboard') }}" class="inline-flex items-center gap-1 hover:text-[#00877A]">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span>Beranda Dashboard</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-[#00877A]">Tampilan Penuh Antrean Validasi</span>
            </nav>
            <h1 class="text-[22px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Semua Antrean Validasi Pesanan &amp; Terbitkan Invoice
            </h1>
            <p class="text-[13px] text-slate-500 mt-0.5">
                Daftar lengkap permohonan transaksi B2B dan B2C yang membutuhkan persetujuan dan penerbitan invoice resmi.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.rtpu.dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12.5px] font-semibold text-slate-700 shadow-[0_1px_2px_rgba(15,23,42,0.03)] transition-all active:scale-[0.98]">
                <span>&larr;</span>
                <span>Kembali ke Ringkasan Dashboard</span>
            </a>

            {{-- Dropdown Ekspor Data (CSV & PDF) --}}
            <div class="relative" id="exportMenuWrap">
                <button type="button" onclick="document.getElementById('exportMenuDropdown').classList.toggle('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <span>Ekspor Data</span>
                </button>
                <div id="exportMenuDropdown" class="hidden absolute right-0 mt-1.5 w-52 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-30 text-[12.5px]">
                    <a href="{{ route('admin.rtpu.export.csv', array_filter(['segment' => $segment !== 'semua' ? $segment : null, 'status' => $status !== 'semua' ? $status : null, 'category' => $activeCategory !== 'semua' ? $activeCategory : null, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
                       class="flex items-center gap-2 px-3.5 py-2 text-slate-700 hover:bg-slate-50 font-semibold">
                        <span>Ekspor Rekap (.CSV)</span>
                    </a>
                    <a href="{{ route('admin.rtpu.print.laporan', array_filter(['segment' => $segment !== 'semua' ? $segment : null, 'status' => $status !== 'semua' ? $status : null, 'category' => $activeCategory !== 'semua' ? $activeCategory : null, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-2 px-3.5 py-2 text-[#00877A] hover:bg-[#F0FDFA] font-semibold">
                        <span>Cetak Laporan Resmi (.PDF)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar Filter & Tabel Full-Width Persis Mockup 02 --}}
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] overflow-hidden">
        {{-- Baris Toolbar Filter --}}
        <div class="px-5 py-4 border-b border-slate-100 space-y-3">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2.5 flex-1">
                    {{-- Input Pencarian Lokal --}}
                    <form method="GET" action="{{ route('admin.rtpu.antrean') }}" class="relative flex-1 min-w-[220px] max-w-xs">
                        @if ($segment !== 'semua') <input type="hidden" name="segment" value="{{ $segment }}"> @endif
                        @if ($status !== 'semua') <input type="hidden" name="status" value="{{ $status }}"> @endif
                        @if ($activeCategory !== 'semua') <input type="hidden" name="category" value="{{ $activeCategory }}"> @endif
                        @if ($selectedUnitId) <input type="hidden" name="unit_id" value="{{ $selectedUnitId }}"> @endif
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input id="localSearchInput" type="text" name="q" value="{{ $search }}" autocomplete="off"
                               placeholder="Cari nama pemesan atau ID..."
                               class="w-full pl-9 pr-3 py-1.5 text-[12.5px] bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/15 focus:border-[#00877A] focus:bg-white">
                    </form>

                    {{-- Tombol Filter Lanjutan --}}
                    <button type="button" onclick="document.getElementById('advancedFilterBar').classList.toggle('hidden')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12.5px] font-semibold text-slate-700 transition-colors active:scale-[0.98]">
                        <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        <span>Filter</span>
                        @if ($segment !== 'semua' || $status !== 'semua' || $selectedUnitId)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#00877A]"></span>
                        @endif
                    </button>
                </div>

                {{-- Pill Kategori Solid Teal Persis Mockup 02 --}}
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
                        <a href="{{ route('admin.rtpu.antrean', array_filter(['category' => $catKey !== 'semua' ? $catKey : null, 'segment' => $segment !== 'semua' ? $segment : null, 'status' => $status !== 'semua' ? $status : null, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
                           class="px-3.5 py-1.5 rounded-lg font-semibold transition-all {{ $activeCategory === $catKey ? 'bg-[#00877A] text-white shadow-sm' : 'bg-slate-50 border border-slate-200/80 text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            {{ $catLabel }} ({{ $catCount }})
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Panel Filter Kanal, Status & Unit Pengelola (Collapsible) --}}
            <form id="advancedFilterBar" method="GET" action="{{ route('admin.rtpu.antrean') }}"
                  class="{{ ($segment !== 'semua' || $status !== 'semua' || $selectedUnitId) ? '' : 'hidden' }} pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2.5 text-[12px]">
                @if ($activeCategory !== 'semua') <input type="hidden" name="category" value="{{ $activeCategory }}"> @endif
                @if ($search !== '') <input type="hidden" name="q" value="{{ $search }}"> @endif

                <select name="segment" onchange="this.form.submit()"
                        class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 font-semibold focus:outline-none focus:border-[#00877A]">
                    <option value="semua" {{ $segment === 'semua' ? 'selected' : '' }}>Semua Kanal (B2C &amp; B2B)</option>
                    <option value="b2c" {{ $segment === 'b2c' ? 'selected' : '' }}>Mahasiswa / Umum (B2C)</option>
                    <option value="b2b" {{ $segment === 'b2b' ? 'selected' : '' }}>Mitra Industri (B2B)</option>
                </select>

                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 font-semibold focus:outline-none focus:border-[#00877A]">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="perlu_validasi" {{ $status === 'perlu_validasi' ? 'selected' : '' }}>Perlu Persetujuan / Pelunasan</option>
                    <option value="menunggu_verifikasi" {{ $status === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="menunggu_pembayaran" {{ $status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="diproses" {{ $status === 'diproses' ? 'selected' : '' }}>Diproses Unit</option>
                    <option value="siap_diambil" {{ $status === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <select name="unit_id" onchange="this.form.submit()"
                        class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 font-semibold focus:outline-none focus:border-[#00877A]">
                    <option value="">Semua Unit Pengelola</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}" {{ (string) $selectedUnitId === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>

                @if ($segment !== 'semua' || $status !== 'semua' || $selectedUnitId || $activeCategory !== 'semua' || $search !== '')
                    <a href="{{ route('admin.rtpu.antrean') }}" class="px-3 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold">Reset Semua Filter</a>
                @endif
            </form>
        </div>

        {{-- Live Search Banner --}}
        <div id="liveSearchBanner" class="hidden px-6 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
            <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
            <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#00877A] hover:underline">Reset Pencarian</button>
        </div>

        {{-- Tabel Full-Width Proporsional 1366px (Sidebar Terbuka Tanpa Terpotong) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-[13px] border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-4 py-3 text-left whitespace-nowrap">ID PESANAN</th>
                        <th class="px-3 py-3 text-left">NAMA PEMESAN</th>
                        <th class="px-3 py-3 text-left">ITEM / LAYANAN</th>
                        <th class="px-3 py-3 text-left whitespace-nowrap">JADWAL</th>
                        <th class="px-3 py-3 text-left whitespace-nowrap">STATUS</th>
                        <th class="px-4 py-3 text-right whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $ord)
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
                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                <button type="button" onclick='openDetailBerkasModal(@json($payload))'
                                        class="px-2 py-1 rounded-md bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-mono text-[11px] font-bold tabular-nums transition-colors">
                                    #{{ $ord->order_code }}
                                </button>
                            </td>
                            <td class="px-3 py-3 align-middle max-w-[180px]">
                                <p class="font-bold text-slate-900 leading-snug truncate">{{ $customerTitle }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug truncate">{{ $customerSub }}</p>
                            </td>
                            <td class="px-3 py-3 align-middle max-w-[200px]">
                                <p class="font-bold text-slate-900 leading-snug truncate">{{ $productName }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $unitName }}</p>
                            </td>
                            <td class="px-3 py-3 align-middle whitespace-nowrap tabular-nums">
                                <div class="flex items-start gap-1.5">
                                    <svg width="13" height="13" class="w-3.5 h-3.5 text-slate-400 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <div>
                                        <p class="text-[12px] font-bold text-slate-800 leading-snug">{{ $scheduleDate }}</p>
                                        <p class="text-[10.5px] text-slate-400">{{ $scheduleTime }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full border text-[11px] font-semibold {{ $statusPill[1] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusPill[2] }}"></span>
                                    <span>{{ $statusPill[0] }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if (! in_array($ord->status, ['selesai', 'dibatalkan'], true))
                                        <button type="button" onclick='triggerRejectOrder(@json($payload))'
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold whitespace-nowrap transition-all active:scale-[0.98]">
                                            <span>&times;</span>
                                            <span>Tolak</span>
                                        </button>
                                        <button type="button" onclick='triggerIssueOrApprove(@json($payload))'
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-semibold whitespace-nowrap shadow-sm transition-all active:scale-[0.98]">
                                            <span>&check;</span>
                                            <span>{{ $ord->segment === 'b2b' ? 'Terbitkan Invoice' : ($ord->status === 'menunggu_pembayaran' ? 'Konfirmasi Lunas' : 'Terbitkan Invoice') }}</span>
                                        </button>
                                    @else
                                        <button type="button" onclick='openDetailBerkasModal(@json($payload))'
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold whitespace-nowrap transition-all active:scale-[0.98]">
                                            <span>Detail</span>
                                        </button>
                                        @if ($ord->invoice)
                                            <a href="{{ route('admin.rtpu.print.invoice', $ord) }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-semibold whitespace-nowrap transition-all active:scale-[0.98]">
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
                                <p class="text-[13.5px] font-bold text-slate-800">Tidak ada permohonan ditemukan</p>
                                <p class="text-[12px] text-slate-500 mt-1">Ubah filter kategori, kanal, atau kata kunci pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Empty State Live Search --}}
        <div id="liveSearchEmptyState" class="hidden p-10 text-center border-t border-slate-100">
            <p class="text-[13.5px] font-bold text-slate-800">Tidak ada hasil untuk "<span id="liveSearchEmptyQuery"></span>"</p>
            <button type="button" onclick="clearGlobalSearch()"
                    class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700">
                Reset Pencarian
            </button>
        </div>

        {{-- Footer Paginasi & Baris per Halaman Persis Mockup 02 --}}
        <div class="px-5 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[12px] text-slate-500 tabular-nums">
            <span>
                Menampilkan <span class="font-bold text-slate-800">{{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $orders->total() }}</span> permohonan aktif
            </span>

            <div class="flex items-center gap-4">
                <form method="GET" action="{{ route('admin.rtpu.antrean') }}" class="flex items-center gap-2">
                    @if ($segment !== 'semua') <input type="hidden" name="segment" value="{{ $segment }}"> @endif
                    @if ($status !== 'semua') <input type="hidden" name="status" value="{{ $status }}"> @endif
                    @if ($activeCategory !== 'semua') <input type="hidden" name="category" value="{{ $activeCategory }}"> @endif
                    @if ($selectedUnitId) <input type="hidden" name="unit_id" value="{{ $selectedUnitId }}"> @endif
                    @if ($search !== '') <input type="hidden" name="q" value="{{ $search }}"> @endif
                    <span class="text-slate-500">Baris per halaman:</span>
                    <select name="per_page" onchange="this.form.submit()"
                            class="px-2 py-1 rounded-md border border-slate-200 bg-white text-slate-800 font-semibold text-[12px] focus:outline-none focus:border-[#00877A]">
                        @foreach ([5, 10, 20, 50] as $opt)
                            <option value="{{ $opt }}" {{ $perPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </form>
                <div>{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('click', function (e) {
        const wrap = document.getElementById('exportMenuWrap');
        const dd = document.getElementById('exportMenuDropdown');
        if (wrap && dd && !wrap.contains(e.target)) {
            dd.classList.add('hidden');
        }
    });
</script>
@endpush
