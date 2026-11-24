@extends('admin-p3m.layouts.app')

@section('title', 'Pemantauan Status Pengajuan Produk')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Portal P3M &bull; Pemantauan Produk Unggulan
            </p>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug mt-0.5">
                Pemantauan Status Pengajuan Produk
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Pantau status kurasi, penelaahan teknis, dan verifikasi SK tarif produk riset unggulan oleh RTPU PNJ.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.p3m.export', ['scope' => 'all']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/80 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Ekspor Data (.csv)</span>
            </a>
            <a href="{{ route('admin.p3m.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#0F766E] hover:bg-[#115E59] text-white text-[12px] font-semibold transition-all active:scale-95">
                <span>+ Ajukan Produk Baru</span>
            </a>
        </div>
    </div>

    {{-- 4 KPI Ringkas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Total Usulan</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $counts['semua'] }} <span class="text-[11.5px] font-medium text-slate-400">Produk</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/70 text-slate-500 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Dalam Antrean Review</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $counts['diajukan'] }} <span class="text-[11.5px] font-medium text-slate-400">Usulan</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-[#F0FDFA] border border-[#CCFBF1] text-[#0F766E] flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Perlu Revisi</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $counts['revisi_only'] }} <span class="text-[11.5px] font-medium text-slate-400">Catatan</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-200/70 text-rose-600 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 p-3.5 flex items-center justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <p class="text-[11px] font-semibold text-slate-500">Disetujui &amp; Tayang</p>
                <p class="mt-1 text-[22px] font-extrabold text-slate-900 leading-none tabular-nums">{{ $counts['tayang'] }} <span class="text-[11.5px] font-medium text-slate-400">Katalog</span></p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200/70 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
    </div>

    {{-- Panel Tabel Pemantauan --}}
    <div class="bg-white rounded-lg border border-slate-200/80 overflow-hidden">
        {{-- Filter Bar --}}
        <div class="px-4 py-3 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 flex-wrap text-[12px]">
                @php
                    $statusTabs = [
                        'semua' => ['Semua', $counts['semua'], 'bg-slate-100 text-slate-700'],
                        'diajukan' => ['Diajukan', $counts['diajukan'], 'bg-[#F0FDFA] text-[#0F766E]'],
                        'revisi' => ['Ditolak / Revisi', $counts['revisi'], 'bg-rose-600 text-white'],
                        'tayang' => ['Disetujui & Tayang', $counts['tayang'], 'bg-emerald-50 text-emerald-700'],
                        'draft' => ['Draf', $counts['draft'], 'bg-slate-100 text-slate-600'],
                    ];
                @endphp
                @foreach ($statusTabs as $key => [$label, $cnt, $badgeCls])
                    <a href="{{ route('admin.p3m.pemantauan', array_filter(['status' => $key, 'unit_id' => $selectedUnitId, 'q' => $search])) }}"
                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg font-semibold transition-all active:scale-95 {{ $statusFilter === $key ? 'bg-slate-100 text-slate-900 border border-slate-200' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>{{ $label }}</span>
                        <span class="tabular-nums px-1.5 py-0.5 rounded text-[10px] font-bold {{ $badgeCls }}">{{ $cnt }}</span>
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.p3m.pemantauan') }}" class="flex items-center gap-2 text-[12px]">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                @if ($search)
                    <input type="hidden" name="q" value="{{ $search }}">
                @endif
                <label for="unit_id" class="text-slate-500 font-medium">Unit:</label>
                <select id="unit_id" name="unit_id" onchange="this.form.submit()"
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

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/70 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2.5">ID Pengajuan</th>
                        <th class="px-4 py-2.5">Nama Produk &amp; Unit</th>
                        <th class="px-4 py-2.5">Harga &amp; Tipe Order</th>
                        <th class="px-4 py-2.5">Tanggal Diajukan</th>
                        <th class="px-4 py-2.5">Status RTPU</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[12px]">
                    @forelse ($products as $product)
                        @php
                            $productPayload = [
                                'id' => $product->id,
                                'code' => $product->code,
                                'name' => $product->name,
                                'description' => $product->description,
                                'unit' => $product->unit->name ?? '-',
                                'category' => $product->category->name ?? '-',
                                'sub_category' => $product->subCategory->name ?? '-',
                                'submitter' => $product->submitter->name ?? ($portalUser->name ?? 'Admin P3M'),
                                'order_type' => $product->order_type,
                                'hpp' => (float) $product->hpp,
                                'price_b2c' => (float) $product->price_b2c,
                                'price_b2b' => (float) $product->price_b2b,
                                'stock' => (int) $product->stock,
                                'status' => $product->status,
                                'curation_notes' => $product->curation_notes,
                                'updated_at' => optional($product->updated_at)->translatedFormat('d M Y, H:i') . ' WIB',
                                'image_url' => $product->image_path && file_exists(public_path('storage/' . $product->image_path)) ? asset('storage/' . $product->image_path) : null,
                                'edit_url' => route('admin.p3m.revisi', $product),
                            ];
                            $searchBlob = strtolower(implode(' ', [
                                $product->code,
                                $product->name,
                                $product->unit->name ?? '',
                                $product->category->name ?? '',
                                $product->subCategory->name ?? '',
                            ]));
                        @endphp
                        <tr data-searchable="true" data-search-id="prod-{{ $product->id }}" data-search-text="{{ $searchBlob }}"
                            class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-slate-700 whitespace-nowrap tabular-nums">#{{ $product->code }}</td>
                            <td class="px-4 py-3 max-w-[290px]">
                                <p class="font-bold text-slate-900 leading-snug">{{ $product->name }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $product->unit->name ?? '-' }} &bull; {{ $product->category->name ?? '-' }}
                                </p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap tabular-nums">
                                <p class="font-bold text-slate-900">Rp {{ number_format((float) $product->price_b2c, 0, ',', '.') }}</p>
                                <p class="text-[10.5px] text-slate-500">
                                    HPP: Rp {{ number_format((float) $product->hpp, 0, ',', '.') }} &bull;
                                    {{ $product->order_type === 'ready_stock' ? 'Ready Stock' : ($product->order_type === 'pre_order' ? 'Pre-Order' : 'Reservasi Lab') }}
                                </p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap tabular-nums">
                                <p class="font-semibold text-slate-700">{{ optional($product->created_at)->format('d M Y') }}</p>
                                <p class="text-[10.5px] text-slate-400">{{ optional($product->updated_at)->diffForHumans() }}</p>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($product->status === 'revisi')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold">Perlu Revisi RTPU</span>
                                @elseif ($product->status === 'menunggu_kurasi_rtpu')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#F0FDFA] text-[#0F766E] border border-[#99F6E4] text-[11px] font-bold">Menunggu Review RTPU</span>
                                @elseif ($product->status === 'menunggu_sk_pimpinan')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200 text-[11px] font-bold">Menunggu ACC Pimpinan</span>
                                @elseif ($product->status === 'tayang')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">&check; Disetujui &amp; Tayang</span>
                                @elseif ($product->status === 'draft')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-bold">Draf</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-rose-700 border border-rose-200 text-[11px] font-bold">Ditolak / Batal</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if ($product->status === 'revisi')
                                        <a href="{{ route('admin.p3m.revisi', $product) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#0F766E] hover:bg-[#115E59] text-white text-[11px] font-semibold transition-all active:scale-95">
                                            <span>Revisi &amp; Ajukan Ulang</span>
                                        </a>
                                    @elseif (in_array($product->status, ['menunggu_kurasi_rtpu', 'menunggu_sk_pimpinan', 'draft'], true))
                                        <button type="button" onclick='openProductModal(@json($productPayload))'
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-semibold active:scale-95">
                                            Detail
                                        </button>
                                        <a href="{{ route('admin.p3m.revisi', $product) }}"
                                           class="px-2.5 py-1.5 rounded-lg text-[#0F766E] hover:bg-[#F0FDFA] text-[11px] font-semibold active:scale-95">
                                            Edit
                                        </a>
                                        <button type="button" onclick='openCancelModal(@json(route("admin.p3m.cancel", $product)), @json($product->name))'
                                                class="px-2.5 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 text-[11px] font-semibold active:scale-95">
                                            Batalkan
                                        </button>
                                    @else
                                        <button type="button" onclick='openProductModal(@json($productPayload))'
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-semibold transition-all active:scale-95">
                                            <span>{{ $product->status === 'tayang' ? 'Lihat Detail SK' : 'Detail Arsip' }}</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Baris Catatan Revisi Khusus --}}
                        @if ($product->status === 'revisi' && $product->curation_notes)
                            <tr data-search-parent-id="prod-{{ $product->id }}" class="bg-rose-50/70">
                                <td colspan="6" class="px-4 py-2 text-[11.5px] text-rose-800 border-t border-rose-100">
                                    <span class="font-extrabold">&#9888; Catatan Admin / Pimpinan RTPU:</span>
                                    <span>{{ $product->curation_notes }}</span>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">
                                <p class="text-[13px] font-bold text-slate-800">Tidak ada data pengajuan produk yang sesuai filter.</p>
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
                <p class="text-[13.5px] font-bold text-slate-800">Tidak ada usulan yang cocok dengan "<span id="liveSearchEmptyQuery"></span>"</p>
                <p class="text-[12px] text-slate-500 mt-1">Tekan Enter untuk mencari di seluruh halaman, atau reset kata kunci pencarian.</p>
                <button type="button" onclick="clearGlobalSearch()"
                        class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                    Reset Pencarian
                </button>
            </div>
        </div>

        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-[11.5px] text-slate-500 tabular-nums">
            <span>Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} usulan produk</span>
            <div>{{ $products->links() }}</div>
        </div>
    </div>

    {{-- Banner Ketentuan Validasi Kurasi RTPU --}}
    <div class="bg-white border border-slate-200/80 rounded-lg px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-start gap-2.5 text-[12px]">
            <div class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5 font-bold text-[11px]">i</div>
            <div>
                <p class="font-bold text-slate-900">Ketentuan Validasi Kurasi RTPU PNJ</p>
                <p class="text-slate-600 text-[11.5px]">Sesuai Pedoman Operasional RTPU No. 042/PNJ/P3M/2026, usulan berstatus revisi memiliki batas waktu perbaikan 14 hari kalender kerja sebelum dibatalkan otomatis.</p>
            </div>
        </div>
        <button type="button" onclick="openGuideModal()" class="text-[12px] font-bold text-[#0F766E] hover:underline shrink-0">
            Lihat Alur Kurasi &amp; Penelaahan &rarr;
        </button>
    </div>
</div>
@endsection
