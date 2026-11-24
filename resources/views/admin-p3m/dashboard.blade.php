@extends('admin-p3m.layouts.app')

@section('title', 'Dashboard Pengajuan Produk Unggulan P3M')

@section('content')
<div class="space-y-4">
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                Portal P3M &bull; Pengajuan Produk Unggulan
            </p>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug mt-0.5">
                Dashboard Pengajuan Produk Unggulan P3M
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Kelola pengajuan produk riset, jasa pengujian, dan reservasi lab untuk diteruskan ke kurasi RTPU PNJ.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200/80 text-[12px] font-semibold text-slate-700 tabular-nums">
                <span class="text-slate-400 font-normal">Periode:</span>
                <span>Semester Gasal 2026/2027</span>
            </div>
            <a href="{{ route('admin.p3m.print', array_filter(['scope' => 'ringkasan', 'q' => $search])) }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/80 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak Ringkasan</span>
            </a>
        </div>
    </div>

    {{-- 4 KPI Cards Compact --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        {{-- Card 1 --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Total Produk Diajukan</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $totalProducts }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Produk</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200/70 text-slate-500 flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] font-semibold text-slate-600 flex items-center gap-1 tabular-nums">
                <svg class="w-3.5 h-3.5 text-[#0F766E]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span>+{{ $thisMonthCount }} usulan diajukan bulan ini</span>
            </p>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Menunggu Review RTPU</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $waitingReviewCount }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Produk</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-[#F0FDFA] border border-[#CCFBF1] text-[#0F766E] flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-[#F0FDFA] border border-[#CCFBF1] text-[#0F766E] text-[10.5px] font-bold">
                    Dalam Antrean RTPU
                </span>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Disetujui &amp; Tayang</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $approvedCount }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Produk</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200/70 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] font-semibold text-emerald-700 tabular-nums">
                {{ $approvalRate }}% Lolos Kurasi RTPU
            </p>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500">Ditolak / Perlu Revisi</p>
                    <div class="mt-1.5 flex items-baseline gap-1.5 tabular-nums">
                        <span class="text-[26px] font-extrabold text-slate-900 leading-none">{{ $revisionCount + $rejectedCount }}</span>
                        <span class="text-[12px] font-medium text-slate-500">Produk</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-200/70 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-3 text-[11px] font-semibold tabular-nums">
                <span class="text-rose-600">{{ $revisionCount }} Perlu Revisi</span>
                <span class="text-slate-500">{{ $rejectedCount }} Ditolak / Batal</span>
            </div>
        </div>
    </div>

    {{-- Grid Utama 2 Kolom (Kiri 8, Kanan 4) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        {{-- Kolom Kiri: Status Pengajuan Produk Terbaru --}}
        <div class="lg:col-span-8 bg-white rounded-lg border border-slate-200/80 overflow-hidden">
            <div class="px-4 pt-4 pb-3 border-b border-slate-100 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-[14.5px] font-extrabold text-slate-900">Status Pengajuan Produk Terbaru</h2>
                    <p class="text-[12px] text-slate-500 mt-0.5">Pantau posisi review dan tanggapi catatan revisi dari Tim RTPU secara real-time</p>
                </div>
                <a href="{{ route('admin.p3m.pemantauan') }}"
                   class="text-[12px] font-bold text-[#0F766E] hover:text-[#115E59] shrink-0 inline-flex items-center gap-1 transition-colors">
                    <span>Lihat Semua Antrean</span>
                    <span>&rarr;</span>
                </a>
            </div>

            {{-- Filter Tabs --}}
            <div class="px-4 pt-2.5 border-b border-slate-100 flex items-center gap-5 text-[12px] overflow-x-auto">
                @php
                    $tabs = [
                        'semua' => 'Semua',
                        'diajukan' => 'Diajukan',
                        'revisi' => 'Perlu Revisi',
                        'disetujui' => 'Disetujui',
                    ];
                @endphp
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('admin.p3m.dashboard', array_filter(['tab' => $key, 'q' => $search])) }}"
                       class="pb-2.5 font-semibold whitespace-nowrap border-b-2 transition-colors inline-flex items-center gap-1.5 {{ $activeTab === $key ? 'border-[#0F766E] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        <span>{{ $label }}</span>
                        @if ($key === 'revisi' && $revisionCount > 0)
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Indikator Hasil Live Search --}}
            <div id="liveSearchBanner" class="hidden px-4 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[12px] text-slate-600">
                <span>Menampilkan <strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> hasil untuk "<strong id="liveSearchQuery" class="text-slate-900"></strong>"</span>
                <button type="button" onclick="clearGlobalSearch()" class="text-[11.5px] font-semibold text-[#0F766E] hover:underline">Reset Pencarian</button>
            </div>

            {{-- Daftar Pengajuan Terbaru --}}
            <div class="divide-y divide-slate-100">
                @forelse ($recentProducts as $product)
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
                    <div data-searchable="true" data-search-text="{{ $searchBlob }}"
                         class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/80 transition-colors">
                        <div class="flex items-start gap-3 min-w-0">
                            {{-- Icon / Thumbnail --}}
                            <div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center overflow-hidden border border-slate-200/70 {{ $product->status === 'revisi' ? 'bg-rose-50 text-rose-600' : ($product->status === 'tayang' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-500') }}">
                                @if ($product->image_path && file_exists(public_path('storage/' . $product->image_path)))
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    @if ($product->status === 'revisi')
                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[10.5px] font-bold">Perlu Revisi RTPU</span>
                                    @elseif ($product->status === 'menunggu_kurasi_rtpu')
                                        <span class="px-2 py-0.5 rounded bg-[#F0FDFA] text-[#0F766E] border border-[#99F6E4] text-[10.5px] font-bold">Menunggu Review RTPU</span>
                                    @elseif ($product->status === 'menunggu_sk_pimpinan')
                                        <span class="px-2 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200 text-[10.5px] font-bold">Menunggu SK Pimpinan</span>
                                    @elseif ($product->status === 'tayang')
                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10.5px] font-bold">Disetujui &amp; Tayang</span>
                                    @elseif ($product->status === 'draft')
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200 text-[10.5px] font-bold">Draf</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[10.5px] font-bold">Ditolak / Dibatalkan</span>
                                    @endif

                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10.5px] font-medium">
                                        {{ $product->unit->name ?? '-' }}
                                    </span>
                                    <span class="text-[10.5px] text-slate-400">
                                        &bull; {{ optional($product->updated_at)->diffForHumans() }}
                                    </span>
                                </div>

                                <h3 class="text-[13.5px] font-bold text-slate-900 mt-1 truncate">{{ $product->name }}</h3>
                                <p class="text-[11.5px] text-slate-500 mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 tabular-nums">
                                    <span class="font-mono font-semibold text-slate-700">#{{ $product->code }}</span>
                                    <span>&bull; {{ $product->category->name ?? '-' }} ({{ $product->subCategory->name ?? '-' }})</span>
                                    <span>&bull; <strong class="text-slate-800">Rp {{ number_format((float) $product->price_b2c, 0, ',', '.') }}</strong></span>
                                </p>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-2">
                            @if ($product->status === 'revisi')
                                <a href="{{ route('admin.p3m.revisi', $product) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0F766E] hover:bg-[#115E59] text-white text-[11.5px] font-semibold transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    <span>Revisi &amp; Ajukan Ulang</span>
                                </a>
                            @elseif ($product->status === 'tayang')
                                <button type="button" onclick='openProductModal(@json($productPayload))'
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-slate-700 text-[11.5px] font-semibold transition-all active:scale-95">
                                    <span>Lihat Legalitas</span>
                                </button>
                            @else
                                <button type="button" onclick='openProductModal(@json($productPayload))'
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-slate-700 text-[11.5px] font-semibold transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>Edit / Detail</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-[13.5px] font-bold text-slate-800">
                            @if ($search !== '')
                                Tidak ada pengajuan yang cocok dengan "{{ $search }}"
                            @else
                                Belum ada pengajuan produk pada kategori ini
                            @endif
                        </p>
                        <p class="text-[12px] text-slate-500 mt-1">
                            @if ($search !== '')
                                Coba gunakan kata kunci nama produk, kode pengajuan, unit, atau kategori lainnya.
                            @else
                                Klik tombol "Ajukan Produk Unggulan" di sidebar untuk membuat pengajuan baru.
                            @endif
                        </p>
                        @if ($search !== '')
                            <button type="button" onclick="clearGlobalSearch()"
                                    class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                                Reset Pencarian
                            </button>
                        @endif
                    </div>
                @endforelse

                {{-- Empty State Live Search --}}
                <div id="liveSearchEmptyState" class="hidden p-10 text-center">
                    <div class="w-10 h-10 mx-auto rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mb-2.5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <p class="text-[13.5px] font-bold text-slate-800">Tidak ada hasil untuk "<span id="liveSearchEmptyQuery"></span>"</p>
                    <p class="text-[12px] text-slate-500 mt-1">Tekan Enter untuk mencari di seluruh halaman, atau reset kata kunci pencarian.</p>
                    <button type="button" onclick="clearGlobalSearch()"
                            class="mt-3 px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-[12px] font-semibold text-slate-700 active:scale-95">
                        Reset Pencarian
                    </button>
                </div>
            </div>

            {{-- Footer Pagination --}}
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-[11.5px] text-slate-500 tabular-nums">
                <span>Menampilkan {{ $recentProducts->count() }} dari {{ $recentProducts->total() }} produk terdaftar</span>
                <div>{{ $recentProducts->links() }}</div>
            </div>
        </div>

        {{-- Kolom Kanan: Catatan Revisi dari RTPU + Maklumat Pedoman --}}
        <div class="lg:col-span-4 space-y-4">
            {{-- Card Catatan Revisi RTPU --}}
            <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <h2 class="text-[13.5px] font-extrabold text-slate-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>Catatan Revisi dari RTPU</span>
                    </h2>
                    @if ($revisionCount > 0)
                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold">Perlu Tindakan</span>
                    @endif
                </div>

                <div class="mt-3 space-y-2.5">
                    @forelse ($revisionProducts as $rev)
                        <div class="p-3 rounded-lg bg-slate-50/80 border border-slate-200/80 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-[12.5px] font-bold text-slate-900 leading-snug">{{ $rev->name }}</p>
                                <span class="px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[9.5px] font-bold shrink-0">Mendesak</span>
                            </div>
                            <p class="text-[11.5px] text-slate-600 leading-relaxed line-clamp-3">
                                {{ $rev->curation_notes ?: 'Mohon lengkapi spesifikasi teknis dan rincian HPP sesuai pedoman BLU.' }}
                            </p>
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[10.5px] text-slate-400">Dari: Sentra RTPU</span>
                                <a href="{{ route('admin.p3m.revisi', $rev) }}"
                                   class="px-2.5 py-1 rounded-lg bg-[#0F766E] hover:bg-[#115E59] text-white text-[11px] font-semibold transition-all active:scale-95">
                                    Perbaiki Sekarang
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 rounded-lg bg-slate-50 border border-slate-200/70 text-center">
                            <p class="text-[12px] font-bold text-slate-800">Tidak ada catatan revisi tertunda</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Seluruh pengajuan Anda dalam kondisi baik.</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('admin.p3m.pemantauan', ['status' => 'revisi']) }}"
                   class="mt-3 w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-[11.5px] font-semibold transition-all active:scale-95">
                    <span>Lihat Semua Catatan Review</span>
                </a>
            </div>

            {{-- Card Maklumat & Pedoman --}}
            <div class="bg-white rounded-lg border border-slate-200/80 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg space-y-3">
                <h2 class="text-[13px] font-extrabold text-slate-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a3 3 0 0 0-3-3H5a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8z"/><path d="M18 8l4-2v12l-4-2"/></svg>
                    <span>Maklumat &amp; Pedoman Pengajuan</span>
                </h2>

                <div class="p-3 rounded-lg bg-slate-50 border-l-4 border-[#0F766E] space-y-1.5">
                    <p class="text-[12px] font-bold text-slate-900">Standar Berkas Produk Unggulan 2026</p>
                    <p class="text-[11.5px] text-slate-600 leading-relaxed">
                        Pedoman No. 042/PNJ/P3M/2026 mewajibkan klasifikasi Master Data P08, estimasi HPP, dan margin BLU minimal 15% untuk produk yang diajukan ke katalog RTPU.
                    </p>
                    <button type="button" onclick="openGuideModal()"
                            class="inline-flex items-center gap-1 text-[11.5px] font-bold text-[#0F766E] hover:underline pt-0.5">
                        Lihat Ringkasan Pedoman &rarr;
                    </button>
                </div>

                <div class="p-3 rounded-lg bg-slate-50/70 border border-slate-200/70 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div class="leading-tight">
                            <p class="text-[12px] font-bold text-slate-900">Helpdesk Konsultasi RTPU</p>
                            <p class="text-[11px] text-slate-500 tabular-nums">Senin - Jumat (08:00 - 16:00 WIB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
