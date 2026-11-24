@extends('admin-p3m.layouts.app')

@section('title', 'Riwayat & Arsip Pengajuan Produk')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Portal P3M &bull; Arsip &amp; Riwayat Pengusulan
            </p>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug mt-0.5">
                Riwayat Pengajuan Produk Unggulan
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Arsip riwayat pengajuan produk riset yang telah selesai diproses oleh RTPU PNJ (Disetujui Tayang, Ditolak Permanen, atau Dibatalkan).
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.p3m.print', array_filter(['scope' => 'riwayat', 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/80 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Ekspor Riwayat (.pdf)</span>
            </a>
            <a href="{{ route('admin.p3m.export', ['scope' => 'riwayat']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/80 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Ekspor Data (.csv)</span>
            </a>
        </div>
    </div>

    {{-- 4 KPI Arsip --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Total Arsip Pengajuan</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $totalArsip }} <span class="text-[11.5px] font-medium text-slate-400">Produk</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/70 text-slate-500 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Disetujui &amp; Tayang</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $tayangCount }} <span class="text-[11.5px] font-medium text-slate-400">Katalog</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200/70 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Ditolak Permanen</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $ditolakPermanentCount }} <span class="text-[11.5px] font-medium text-slate-400">Usulan</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-200/70 text-rose-600 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Dibatalkan / Draf</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $dibatalkanCount }} <span class="text-[11.5px] font-medium text-slate-400">Usulan</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/70 text-slate-500 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
            </div>
        </div>
    </div>

    {{-- Panel Tabel Riwayat --}}
    <div class="bg-white rounded-lg border border-slate-200/80 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 flex-wrap text-[12px]">
                @php
                    $archiveTabs = [
                        'semua' => ['Semua Riwayat', $totalArsip],
                        'tayang' => ['Tayang di Katalog', $tayangCount],
                        'ditolak' => ['Ditolak Permanen', $ditolakPermanentCount],
                        'dibatalkan' => ['Dibatalkan / Draf', $dibatalkanCount],
                    ];
                @endphp
                @foreach ($archiveTabs as $key => [$label, $cnt])
                    <a href="{{ route('admin.p3m.riwayat', array_filter(['filter' => $key, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg font-semibold transition-all active:scale-95 {{ $filter === $key ? 'bg-slate-100 text-slate-900 border border-slate-200' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>{{ $label }}</span>
                        <span class="tabular-nums px-1.5 py-0.5 rounded bg-white border border-slate-200/70 text-[10px] font-bold text-slate-700">{{ $cnt }}</span>
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.p3m.riwayat') }}" class="flex items-center gap-2 text-[12px]">
                <input type="hidden" name="filter" value="{{ $filter }}">
                @if ($search)
                    <input type="hidden" name="q" value="{{ $search }}">
                @endif
                <label for="unit_riwayat" class="text-slate-500 font-medium">Unit:</label>
                <select id="unit_riwayat" name="unit_id" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 rounded-lg border border-slate-200/90 bg-white text-[12px] font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#0F766E]/15 focus:border-[#0F766E]">
                    <option value="">Semua Unit / Jurusan</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}" @selected((string) $selectedUnitId === (string) $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Indikator Hasil Live Search --}}
        <div id="liveSearchBanner" class="hidden px-4 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
            <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
            <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#0F766E] hover:underline">Reset Pencarian</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/70 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2.5">ID Pengajuan</th>
                        <th class="px-4 py-2.5">Nama Produk &amp; Unit</th>
                        <th class="px-4 py-2.5">Tanggal Selesai</th>
                        <th class="px-4 py-2.5">Keputusan Akhir RTPU</th>
                        <th class="px-4 py-2.5">Nomor SK Tarif / Katalog</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[12px]">
                    @forelse ($archives as $item)
                        @php
                            $isCancelled = $item->status === 'ditolak' && str_contains(strtolower((string) $item->curation_notes), 'dibatalkan');
                            $payload = [
                                'id' => $item->id,
                                'code' => $item->code,
                                'name' => $item->name,
                                'description' => $item->description,
                                'unit' => $item->unit->name ?? '-',
                                'category' => $item->category->name ?? '-',
                                'sub_category' => $item->subCategory->name ?? '-',
                                'submitter' => $item->submitter->name ?? ($portalUser->name ?? 'Admin P3M'),
                                'order_type' => $item->order_type,
                                'hpp' => (float) $item->hpp,
                                'price_b2c' => (float) $item->price_b2c,
                                'price_b2b' => (float) $item->price_b2b,
                                'stock' => (int) $item->stock,
                                'status' => $item->status,
                                'curation_notes' => $item->curation_notes,
                                'updated_at' => optional($item->updated_at)->translatedFormat('d M Y'),
                                'image_url' => $item->image_path && file_exists(public_path('storage/' . $item->image_path)) ? asset('storage/' . $item->image_path) : null,
                                'edit_url' => $item->status === 'draft' ? route('admin.p3m.revisi', $item) : null,
                            ];
                            $searchBlob = strtolower(implode(' ', [
                                $item->code,
                                $item->name,
                                $item->unit->name ?? '',
                                $item->category->name ?? '',
                                $item->subCategory->name ?? '',
                            ]));
                        @endphp
                        <tr data-searchable="true" data-search-text="{{ $searchBlob }}"
                            class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-slate-700 whitespace-nowrap tabular-nums">#{{ $item->code }}</td>
                            <td class="px-4 py-3 max-w-[300px]">
                                <p class="font-bold text-slate-900 leading-snug">{{ $item->name }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $item->unit->name ?? '-' }} &bull; {{ $item->category->name ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-700 tabular-nums">
                                {{ optional($item->updated_at)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($item->status === 'tayang')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">&check; Tayang di Katalog</span>
                                @elseif ($item->status === 'draft')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-bold">Draf Tersimpan</span>
                                @elseif ($isCancelled)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-bold">Dibatalkan</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold">&times; Ditolak Permanen</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap tabular-nums">
                                @if ($item->status === 'tayang')
                                    <p class="font-mono font-bold text-slate-800 text-[11.5px]">SK-RTPU/PNJ/2026/{{ str_pad((string) $item->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-[10.5px] text-slate-500 font-medium">Katalog: #KAT-2026-{{ str_pad((string) $item->id, 3, '0', STR_PAD_LEFT) }}</p>
                                @elseif ($item->status === 'ditolak' && ! $isCancelled)
                                    <p class="font-semibold text-slate-700 text-[11.5px]">Naskah Telaah Tolak</p>
                                    <p class="font-mono text-[10.5px] text-rose-600 font-bold">#TL-RTPU-{{ str_pad((string) $item->id, 3, '0', STR_PAD_LEFT) }}</p>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">- ({{ $item->status === 'draft' ? 'Belum Diajukan' : 'Dibatalkan Pengusul' }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <button type="button" onclick='openProductModal(@json($payload))'
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-[11.5px] font-semibold transition-all active:scale-95">
                                    <span>Lihat Detail Arsip</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">
                                <p class="text-[13px] font-bold text-slate-800">Belum ada arsip pengajuan produk pada kategori ini.</p>
                                @if ($search !== '')
                                    <button type="button" onclick="clearGlobalSearch()"
                                            class="mt-2.5 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                                        Reset Pencarian
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Empty State Live Search --}}
            <div id="liveSearchEmptyState" class="hidden p-10 text-center border-t border-slate-100">
                <div class="w-10 h-10 mx-auto rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mb-2.5">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <p class="text-[13.5px] font-bold text-slate-800">Tidak ada arsip yang cocok dengan "<span id="liveSearchEmptyQuery"></span>"</p>
                <p class="text-[12px] text-slate-500 mt-1">Tekan Enter untuk mencari di seluruh halaman, atau reset kata kunci pencarian.</p>
                <button type="button" onclick="clearGlobalSearch()"
                        class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                    Reset Pencarian
                </button>
            </div>
        </div>

        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-[11.5px] text-slate-500 tabular-nums">
            <span>Menampilkan {{ $archives->firstItem() ?? 0 }} - {{ $archives->lastItem() ?? 0 }} dari {{ $archives->total() }} riwayat pengajuan</span>
            <div>{{ $archives->links() }}</div>
        </div>
    </div>

    {{-- Banner Legalitas Arsip --}}
    <div class="bg-white border border-slate-200/80 rounded-lg px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-start gap-2.5 text-[12px]">
            <div class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5">&check;</div>
            <div>
                <p class="font-bold text-slate-900">Legalitas Arsip &amp; Surat Keputusan Direktur PNJ</p>
                <p class="text-slate-600 text-[11.5px]">Seluruh riwayat pengajuan yang telah terbit SK Tarif resmi tersimpan permanen dalam Sistem Repositori &amp; Arsip Vokasi Politeknik Negeri Jakarta.</p>
            </div>
        </div>
        <a href="{{ route('admin.p3m.print', ['scope' => 'riwayat']) }}" target="_blank" rel="noopener noreferrer"
           class="text-[12px] font-bold text-[#0F766E] hover:underline shrink-0">
            Cetak Rekap Arsip Tahunan (.pdf) &rarr;
        </a>
    </div>
</div>
@endsection
