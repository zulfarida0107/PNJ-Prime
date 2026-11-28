@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Katalog Lengkap & Pencarian Riset')
@section('nav', 'katalog')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-[#00877A]">Katalog Utama</span>
            </nav>
            <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Katalog Lengkap &amp; Pencarian Riset</h2>
            <p class="text-xs text-slate-500">Temukan instrumen, purwarupa, layanan pengujian, dan fasilitas laboratorium riset terapan PNJ.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/80 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs">
            <svg width="14" height="14" class="text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7" rx="1" />
                <rect x="14" y="3" width="7" height="7" rx="1" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <rect x="14" y="14" width="7" height="7" rx="1" />
            </svg>
            <span class="tabular-nums">Menampilkan {{ $products->total() }} produk riset &amp; fasilitas</span>
        </span>
    </div>

    {{-- Search Bar & Quick Category Pills --}}
    <div class="rounded-lg border border-slate-200/80 bg-white p-3.5 shadow-xs">
        <form action="{{ route('b2c.katalog') }}" method="GET" class="flex flex-col gap-2.5 sm:flex-row">
            @if ($filters['category_id'])
                <input type="hidden" name="category_id" value="{{ $filters['category_id'] }}">
            @endif
            @if ($filters['unit_id'])
                <input type="hidden" name="unit_id" value="{{ $filters['unit_id'] }}">
            @endif
            @if ($filters['sub_category_id'])
                <input type="hidden" name="sub_category_id" value="{{ $filters['sub_category_id'] }}">
            @endif
            @if ($filters['order_type'])
                <input type="hidden" name="order_type" value="{{ $filters['order_type'] }}">
            @endif

            <div class="relative flex-1">
                <svg width="16" height="16" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="search"
                       name="q"
                       value="{{ $filters['q'] }}"
                       placeholder="Cari produk riset, kode, departemen, atau fasilitas lab..."
                       class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50/70 pr-24 pl-9 text-xs text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-[#00877A] focus:bg-white focus:ring-2 focus:ring-[#00877A]/15">
                <button type="submit"
                        class="absolute top-1.5 right-1.5 rounded-md bg-[#00877A] px-3 py-1.5 text-[11px] font-semibold text-white transition-all hover:bg-[#006E63] active:scale-95">
                    Cari
                </button>
            </div>

            <button type="button" id="open-filter-modal-btn"
                    class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg border border-[#00877A] bg-white px-4 text-xs font-semibold text-[#00877A] transition-all hover:bg-[#00877A]/5 active:scale-95">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="6" x2="20" y2="6" />
                    <line x1="7" y1="12" x2="17" y2="12" />
                    <line x1="10" y1="18" x2="14" y2="18" />
                </svg>
                <span>Filter Lanjutan</span>
                @if ($activeFilterCount > 0)
                    <span class="flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-[#00877A] px-1 tabular-nums text-[10px] font-bold text-white">
                        {{ $activeFilterCount }}
                    </span>
                @endif
            </button>
        </form>

        {{-- Quick Category Pills --}}
        <div class="mt-3 flex flex-wrap items-center gap-1.5">
            <a href="{{ route('b2c.katalog', array_filter(['q' => $filters['q']])) }}"
               class="rounded-full px-3 py-1 text-xs font-semibold transition-all active:scale-95 {{ ! $filters['category_id'] ? 'bg-[#00877A] text-white shadow-xs' : 'border border-slate-200 bg-white text-slate-600 hover:border-[#00877A] hover:text-[#00877A]' }}">
                Semua Kategori
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('b2c.katalog', array_filter(['q' => $filters['q'], 'category_id' => $cat->id])) }}"
                   class="rounded-full px-3 py-1 text-xs font-semibold transition-all active:scale-95 {{ (string) $filters['category_id'] === (string) $cat->id ? 'bg-[#00877A] text-white shadow-xs' : 'border border-slate-200 bg-white text-slate-600 hover:border-[#00877A] hover:text-[#00877A]' }}">
                    {{ $cat->name }}
                </a>
            @endforeach

            @if ($activeFilterCount > 0 || $filters['q'] !== '' || $filters['category_id'])
                <a href="{{ route('b2c.katalog') }}"
                   class="ml-auto inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 hover:underline">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                    Reset Semua Filter
                </a>
            @endif
        </div>
    </div>

    {{-- Grid Produk 4 Kolom Compact --}}
    @if ($products->isEmpty())
        <div class="rounded-lg border border-slate-200/80 bg-white px-6 py-12 text-center shadow-xs">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-slate-800">Produk atau Fasilitas Tidak Ditemukan</h3>
            <p class="mt-1 text-xs text-slate-500">Coba ubah kata kunci pencarian atau reset filter lanjutan untuk melihat seluruh katalog tayang.</p>
            <a href="{{ route('b2c.katalog') }}"
               class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-4 py-2 text-xs font-semibold text-white transition-all hover:bg-[#006E63] active:scale-95">
                Tampilkan Semua Katalog
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($products as $item)
                @php
                    $typeBadge = match ($item->order_type) {
                        'lab_reservation' => ['label' => 'Fasilitas Lab', 'cls' => 'bg-sky-50 text-sky-700 border-sky-200', 'priceLabel' => 'Tarif Reservasi Lab'],
                        'pre_order' => ['label' => 'Pre-Order', 'cls' => 'bg-amber-50 text-amber-700 border-amber-200', 'priceLabel' => 'Harga Sivitas (PO)'],
                        default => ['label' => 'Ready Stock', 'cls' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'priceLabel' => 'Harga Sivitas Akademika'],
                    };
                @endphp
                <article class="group flex flex-col rounded-lg border border-slate-200/80 bg-white p-3.5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/40 hover:shadow-lg">
                    <div class="relative aspect-[16/10] overflow-hidden rounded-lg bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/60">
                        @if ($item->resolved_image_url)
                            <img src="{{ $item->resolved_image_url }}"
                                 alt="{{ $item->name }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#00877A]/70 transition-transform duration-300 group-hover:scale-105">
                                @if ($item->order_type === 'lab_reservation')
                                    <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="14" rx="2" />
                                        <path d="M7 21h10M12 17v4M8 9h8M8 13h4" />
                                    </svg>
                                @elseif ($item->order_type === 'pre_order')
                                    <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 3h6" />
                                        <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                                        <path d="M7.5 14h9" />
                                    </svg>
                                @else
                                    <svg width="46" height="46" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                        <rect x="9.5" y="9.5" width="5" height="5" />
                                        <path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4" />
                                    </svg>
                                @endif
                            </div>
                        @endif

                        <span class="absolute top-2 right-2 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $typeBadge['cls'] }}">
                            &bull; {{ $typeBadge['label'] }}
                        </span>
                        <span class="absolute bottom-2 left-2 rounded bg-slate-900/70 px-1.5 py-0.5 tabular-nums text-[9px] font-medium text-white">
                            {{ $item->code }}
                        </span>
                    </div>

                    <div class="mt-3 flex flex-1 flex-col">
                        <div class="flex items-center justify-between gap-1.5">
                            <span class="truncate rounded-md bg-[#00877A]/10 px-2 py-0.5 text-[10px] font-semibold text-[#00877A]">
                                {{ $item->category?->name ?? 'Riset' }}
                            </span>
                            <span class="truncate text-[10px] font-medium text-slate-400">
                                {{ $item->unit?->code ?? 'PNJ' }}
                            </span>
                        </div>

                        <h3 class="mt-1.5 line-clamp-2 min-h-[2.5rem] text-sm font-bold leading-snug text-slate-900 transition-colors group-hover:text-[#00877A]">
                            {{ $item->name }}
                        </h3>

                        <p class="mt-1 line-clamp-1 text-[11px] text-slate-500">
                            {{ $item->subCategory?->name ?? $item->unit?->name }} &bull;
                            <span class="tabular-nums font-medium text-slate-600">
                                {{ $item->order_type === 'lab_reservation' ? $item->stock . ' slot/hari' : 'Stok ' . $item->stock }}
                            </span>
                        </p>

                        <div class="mt-auto border-t border-slate-100 pt-2.5">
                            <p class="text-[10px] font-medium text-slate-400">{{ $typeBadge['priceLabel'] }}</p>
                            <p class="tabular-nums text-sm font-extrabold text-slate-900">
                                Rp {{ number_format((float) $item->price_b2c, 0, ',', '.') }}
                                @if ($item->order_type === 'lab_reservation')
                                    <span class="text-[11px] font-medium text-slate-500">/ Sesi</span>
                                @endif
                            </p>
                        </div>

                        <a href="{{ route('b2c.produk.show', $item) }}"
                           class="mt-2.5 inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2 text-xs font-semibold text-white transition-all duration-200 hover:bg-[#006E63] hover:shadow-md active:scale-95">
                            <span>Lihat Detail</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200/80 bg-white px-4 py-2.5 text-xs text-slate-500 shadow-xs">
            <p class="tabular-nums">
                Menampilkan <span class="font-semibold text-slate-800">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span> dari <span class="font-semibold text-slate-800">{{ $products->total() }}</span> item riset terverifikasi
            </p>
            <div>
                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>

{{-- Modal 03: Filter Lanjutan Dinamis --}}
<div id="b2c-filter-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="filter-modal-title">
    <div class="w-full max-w-lg overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="7" y1="12" x2="17" y2="12" />
                        <line x1="10" y1="18" x2="14" y2="18" />
                    </svg>
                </span>
                <div>
                    <h3 id="filter-modal-title" class="text-sm font-bold text-slate-900">Filter Pencarian Lanjutan</h3>
                    <p class="text-[11px] text-slate-500">Sesuaikan kriteria unit, kategori, sub-kategori, tipe order, dan rentang tarif B2C.</p>
                </div>
            </div>
            <button type="button" data-close-filter
                    class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Tutup modal filter">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('b2c.katalog') }}" method="GET" class="space-y-4 p-5">
            @if ($filters['q'])
                <input type="hidden" name="q" value="{{ $filters['q'] }}">
            @endif

            {{-- Tipe Order --}}
            <div>
                <label class="block text-xs font-bold text-slate-800">Tipe Pemesanan / Layanan</label>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach ([
                        'ready_stock' => 'Ready Stock',
                        'pre_order' => 'Pre-Order',
                        'lab_reservation' => 'Reservasi Lab',
                    ] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="{{ $val }}" class="peer sr-only"
                                   {{ $filters['order_type'] === $val ? 'checked' : '' }}>
                            <span class="flex items-center justify-center rounded-lg border border-slate-200 px-2.5 py-2 text-xs font-semibold text-slate-600 transition-all peer-checked:border-[#00877A] peer-checked:bg-[#00877A] peer-checked:text-white hover:border-[#00877A]/50">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Unit & Kategori Dinamis --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <label for="filter-unit" class="block text-xs font-bold text-slate-800">Unit / Departemen Riset</label>
                    <select id="filter-unit" name="unit_id"
                            class="mt-1.5 h-9 w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <option value="">Semua Unit / Jurusan</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ (string) $filters['unit_id'] === (string) $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter-category" class="block text-xs font-bold text-slate-800">Kategori Baku</label>
                    <select id="filter-category" name="category_id"
                            class="mt-1.5 h-9 w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) $filters['category_id'] === (string) $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Sub-Kategori Dinamis --}}
            <div>
                <label for="filter-subcategory" class="block text-xs font-bold text-slate-800">Sub-Kategori Riset</label>
                <select id="filter-subcategory" name="sub_category_id"
                        class="mt-1.5 h-9 w-full rounded-lg border border-slate-200 bg-white px-2.5 text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    <option value="">Semua Sub-Kategori</option>
                    @foreach ($subCategories as $sub)
                        <option value="{{ $sub->id }}" {{ (string) $filters['sub_category_id'] === (string) $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Rentang Harga B2C --}}
            <div>
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-slate-800">Rentang Harga Sivitas (Rp)</label>
                    <span class="text-[10px] text-slate-400">Tarif B2C Mahasiswa/Dosen</span>
                </div>
                <div class="mt-1.5 grid grid-cols-2 gap-2.5">
                    <div class="relative">
                        <span class="pointer-events-none absolute top-1/2 left-2.5 -translate-y-1/2 text-[11px] font-semibold text-slate-400">Rp</span>
                        <input type="number" id="filter-min-price" name="min_price" value="{{ $filters['min_price'] }}" min="0" step="10000" placeholder="Harga Minimum"
                               class="h-9 w-full rounded-lg border border-slate-200 bg-white pr-2.5 pl-8 tabular-nums text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    </div>
                    <div class="relative">
                        <span class="pointer-events-none absolute top-1/2 left-2.5 -translate-y-1/2 text-[11px] font-semibold text-slate-400">Rp</span>
                        <input type="number" id="filter-max-price" name="max_price" value="{{ $filters['max_price'] }}" min="0" step="10000" placeholder="Harga Maksimum"
                               class="h-9 w-full rounded-lg border border-slate-200 bg-white pr-2.5 pl-8 tabular-nums text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    </div>
                </div>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    <button type="button" data-price-preset="0,500000"
                            class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 transition-colors hover:border-[#00877A] hover:bg-[#00877A]/5 hover:text-[#00877A]">
                        &lt; Rp 500rb
                    </button>
                    <button type="button" data-price-preset="500000,1000000"
                            class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 transition-colors hover:border-[#00877A] hover:bg-[#00877A]/5 hover:text-[#00877A]">
                        Rp 500rb – 1Jt
                    </button>
                    <button type="button" data-price-preset="1000000,"
                            class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 transition-colors hover:border-[#00877A] hover:bg-[#00877A]/5 hover:text-[#00877A]">
                        &gt; Rp 1Jt
                    </button>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="flex items-center justify-between border-t border-slate-100 pt-3.5">
                <a href="{{ route('b2c.katalog') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 transition-colors hover:text-slate-800">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    Reset Filter
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-5 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] active:scale-95">
                    <span>Terapkan Filter</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('b2c-filter-modal');
        const openBtn = document.getElementById('open-filter-modal-btn');
        const closeBtns = document.querySelectorAll('[data-close-filter]');
        const minInput = document.getElementById('filter-min-price');
        const maxInput = document.getElementById('filter-max-price');

        const openModal = () => {
            modal?.classList.remove('hidden');
            modal?.classList.add('flex');
        };
        const closeModal = () => {
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        };

        openBtn?.addEventListener('click', openModal);
        closeBtns.forEach((b) => b.addEventListener('click', closeModal));
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        document.querySelectorAll('[data-price-preset]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const [min, max] = btn.dataset.pricePreset.split(',');
                if (minInput) minInput.value = min || '';
                if (maxInput) maxInput.value = max || '';
            });
        });
    })();
</script>
@endpush
