@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', $product->name)
@section('nav', 'katalog')

@section('content')
@php
    $isLab = $product->order_type === 'lab_reservation';
    $isPreOrder = $product->order_type === 'pre_order';
    $basePrice = (float) $product->price_b2c;
    $defaultDate = now()->addDays(2)->format('Y-m-d');

    $typeLabel = match ($product->order_type) {
        'lab_reservation' => 'FASILITAS LAB TERPADU',
        'pre_order' => 'PRE-ORDER RISET',
        default => 'READY STOCK RTPU',
    };

    $quickDays = collect(range(1, 5))->map(function ($offset) use ($bookedDates) {
        $d = now()->addDays($offset);
        $iso = $d->format('Y-m-d');
        $count = $bookedDates[$iso] ?? 0;
        return [
            'iso' => $iso,
            'dayName' => $d->translatedFormat('D'),
            'dayNum' => $d->format('d'),
            'fullLabel' => $d->translatedFormat('l, d F Y'),
            'isFull' => $count >= 2,
        ];
    });
@endphp

<div class="space-y-4">
    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-medium text-slate-400">
        <a href="{{ route('b2c.katalog') }}" class="hover:text-[#00877A]">Katalog Utama</a>
        <span>&rsaquo;</span>
        <a href="{{ route('b2c.katalog', ['category_id' => $product->category_id]) }}" class="hover:text-[#00877A]">
            {{ $product->category?->name ?? 'Riset Terapan' }}
        </a>
        <span>&rsaquo;</span>
        <span class="font-semibold text-[#00877A]">{{ $product->name }}</span>
    </nav>

    {{-- Konten Utama 2 Kolom --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Kolom Kiri: Visual, Thumbnail, & Spesifikasi --}}
        <div class="space-y-3.5 lg:col-span-5">
            <div class="overflow-hidden rounded-lg border border-slate-200/80 bg-white p-3 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-gradient-to-br from-slate-100 via-[#00877A]/12 to-teal-100/70">
                    <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-lg bg-white/95 px-2.5 py-1 text-[11px] font-semibold text-slate-700 shadow-xs backdrop-blur">
                        <svg width="13" height="13" class="text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18M5 21V7l7-4 7 4v14" />
                        </svg>
                        {{ $product->unit?->name ?? 'UPA-RTPU Kampus PNJ' }}
                    </span>

                    @if ($product->resolved_image_url)
                        <img src="{{ $product->resolved_image_url }}"
                             alt="{{ $product->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full flex-col items-center justify-center p-6 text-center text-[#00877A]">
                            @if ($isLab)
                                <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="14" rx="2" />
                                    <path d="M7 21h10M12 17v4M8 9h8M8 13h4" />
                                </svg>
                            @elseif ($isPreOrder)
                                <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 3h6" />
                                    <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                                    <path d="M7.5 14h9" />
                                </svg>
                            @else
                                <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                    <rect x="9.5" y="9.5" width="5" height="5" />
                                    <path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4" />
                                </svg>
                            @endif
                            <p class="mt-3 text-xs font-semibold text-slate-600">{{ $product->code }}</p>
                        </div>
                    @endif
                </div>

                {{-- 3 Mini Spec Cards --}}
                <div class="mt-3 grid grid-cols-3 divide-x divide-slate-100 rounded-lg border border-slate-100 bg-slate-50/70 py-2.5 text-center">
                    <div class="px-2">
                        <p class="text-[10px] font-medium text-slate-400">Kode Produk</p>
                        <p class="mt-0.5 truncate tabular-nums text-xs font-bold text-slate-800">{{ $product->code }}</p>
                    </div>
                    <div class="px-2">
                        <p class="text-[10px] font-medium text-slate-400">Sub-Kategori</p>
                        <p class="mt-0.5 truncate text-xs font-bold text-slate-800">{{ $product->subCategory?->name ?? '-' }}</p>
                    </div>
                    <div class="px-2">
                        <p class="text-[10px] font-medium text-slate-400">{{ $isLab ? 'Kuota Slot' : 'Stok Tersedia' }}</p>
                        <p class="mt-0.5 tabular-nums text-xs font-bold text-[#00877A]">
                            {{ $product->stock }} {{ $isLab ? 'Sesi/Hari' : 'Unit' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Deskripsi & Catatan Kurasi --}}
            <div class="rounded-lg border border-slate-200/80 bg-white p-4 shadow-xs">
                <h3 class="text-xs font-bold tracking-wide text-slate-800 uppercase">Spesifikasi &amp; Informasi Teknis</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-600">
                    {{ $product->description ?: 'Produk riset terapan dan layanan laboratorium resmi Politeknik Negeri Jakarta di bawah tata kelola UPA-RTPU.' }}
                </p>
                @if ($product->curation_notes)
                    <div class="mt-3 rounded-lg border border-[#00877A]/20 bg-[#00877A]/5 p-3">
                        <p class="text-[11px] font-bold text-[#00877A]">Catatan Operasional RTPU</p>
                        <p class="mt-0.5 text-xs text-slate-600">{{ $product->curation_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Info Harga B2C & Form Aksi Sesuai order_type --}}
        <div class="lg:col-span-7">
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <span class="inline-flex items-center gap-1.5 rounded-md border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-1 text-[10px] font-bold tracking-wider text-[#00877A]">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 3h6M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                    </svg>
                    {{ $typeLabel }}
                </span>

                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">{{ $product->name }}</h1>

                <div class="mt-2 flex flex-wrap items-baseline gap-2">
                    <p class="tabular-nums text-2xl font-extrabold text-[#00877A]">
                        Rp {{ number_format($basePrice, 0, ',', '.') }}
                    </p>
                    <span class="text-xs font-medium text-slate-500">
                        {{ $isLab ? '/ Sesi (4 Jam)' : '/ Unit' }} &bull; Tarif Sivitas Akademika (100% Lunas)
                    </span>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[11px] font-semibold text-sky-700">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                        {{ $isLab ? 'Kapasitas: Maks. ' . max(1, $product->stock) . ' Mahasiswa/Sesi' : 'Tersedia: ' . $product->stock . ' Unit' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        {{ $product->unit?->name ?? 'Gedung UPA-RTPU Kampus PNJ' }}
                    </span>
                </div>

                {{-- Form Aksi sesuai order_type (Lab -> 05, Ready Stock -> 04b, Pre-Order -> 04c) --}}
                @php
                    $checkoutAction = match ($product->order_type) {
                        'ready_stock' => route('b2c.ready-stock.checkout', $product),
                        'pre_order' => route('b2c.preorder.checkout', $product),
                        default => route('b2c.pemesanan.create', $product),
                    };
                @endphp
                <form action="{{ $checkoutAction }}" method="GET" class="mt-5 space-y-4">
                    @if ($isLab)
                        {{-- Panel Jadwal & Ketersediaan Slot (Khusus Lab Reservation) --}}
                        <div class="rounded-lg border border-slate-200/80 bg-slate-50/50 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <svg width="16" height="16" class="text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                    <h3 class="text-xs font-bold text-slate-800">Jadwal &amp; Ketersediaan Slot</h3>
                                </div>

                                <button type="button" id="open-calendar-modal-btn"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-[#00877A]/30 bg-white px-2.5 py-1 text-[11px] font-semibold text-[#00877A] transition-all hover:bg-[#00877A]/5 active:scale-95">
                                    <span id="selected-month-label">{{ now()->translatedFormat('F Y') }}</span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                            </div>

                            <input type="hidden" name="booking_date" id="booking-date-input" value="{{ $defaultDate }}">

                            {{-- 5-Day Quick Strip --}}
                            <div class="mt-3 grid grid-cols-5 gap-2">
                                @foreach ($quickDays as $day)
                                    <button type="button"
                                            data-quick-date="{{ $day['iso'] }}"
                                            data-quick-label="{{ $day['fullLabel'] }}"
                                            {{ $day['isFull'] ? 'disabled' : '' }}
                                            class="quick-day-btn flex flex-col items-center rounded-lg border px-2 py-2 text-center transition-all active:scale-95 {{ $day['isFull'] ? 'cursor-not-allowed border-slate-200 bg-slate-100/70 opacity-60' : ($day['iso'] === $defaultDate ? 'border-[#00877A] bg-[#00877A] text-white shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-[#00877A]') }}">
                                        <span class="text-[10px] font-medium opacity-80">{{ $day['dayName'] }}</span>
                                        <span class="mt-0.5 tabular-nums text-sm font-extrabold">{{ $day['dayNum'] }}</span>
                                        <span class="mt-0.5 text-[9px] font-semibold {{ $day['isFull'] ? 'text-rose-500' : '' }}">
                                            &bull; {{ $day['isFull'] ? 'Penuh' : ($day['iso'] === $defaultDate ? 'Dipilih' : 'Tersedia') }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            <p class="mt-2.5 text-[11px] text-slate-500">
                                Tanggal dipilih: <strong id="selected-date-text" class="text-slate-800">{{ \Illuminate\Support\Carbon::parse($defaultDate)->translatedFormat('l, d F Y') }}</strong>
                            </p>

                            {{-- Pilihan Sesi --}}
                            <div class="mt-3">
                                <p class="text-[11px] font-semibold text-slate-600">Pilih Sesi Praktikum / Riset:</p>
                                <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="booking_slot" value="08:00 - 12:00 WIB" class="peer sr-only">
                                        <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-2.5 transition-all peer-checked:border-[#00877A] peer-checked:bg-[#00877A]/5">
                                            <div>
                                                <p class="tabular-nums text-xs font-bold text-slate-900">08:00 - 12:00 WIB</p>
                                                <p class="text-[11px] text-slate-500">Sesi Pagi (4 Jam)</p>
                                            </div>
                                            <span class="h-4 w-4 rounded-full border-2 border-slate-300 peer-checked:border-[#00877A]"></span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="booking_slot" value="13:00 - 17:00 WIB" class="peer sr-only" checked>
                                        <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-2.5 transition-all peer-checked:border-[#00877A] peer-checked:bg-[#00877A]/5">
                                            <div>
                                                <p class="tabular-nums text-xs font-bold text-[#00877A]">13:00 - 17:00 WIB</p>
                                                <p class="text-[11px] text-slate-500">Sesi Siang (4 Jam)</p>
                                            </div>
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A] text-white">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m5 12 5 5L20 7" />
                                                </svg>
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi Pendampingan Teknisi Lab --}}
                        <label class="flex cursor-pointer items-start justify-between gap-3 rounded-lg border border-slate-200/80 bg-white p-3.5 transition-colors hover:border-[#00877A]/50">
                            <div class="flex items-start gap-2.5">
                                <input type="checkbox" id="with-operator-checkbox" name="with_operator" value="1" checked
                                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#00877A] focus:ring-[#00877A]">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Termasuk Pendampingan Operator Teknisi Lab</p>
                                    <p class="text-[11px] text-slate-500">Bantuan teknis setting G-code, clamping alat, kalibrasi, dan keamanan operasional.</p>
                                </div>
                            </div>
                            <span class="shrink-0 tabular-nums text-xs font-bold text-[#00877A]">+Rp {{ number_format($operatorFee, 0, ',', '.') }}</span>
                        </label>
                    @else
                        {{-- Panel Kuantitas (Ready Stock & Pre-Order) --}}
                        <div class="rounded-lg border border-slate-200/80 bg-slate-50/50 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Jumlah Pesanan</p>
                                    <p class="text-[11px] text-slate-500">
                                        {{ $isPreOrder ? 'Estimasi perakitan Pre-Order ± 7 hari kerja' : 'Siap diambil / dikirim dalam 1 hari kerja' }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="qty-minus"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:border-[#00877A] active:scale-95">
                                        &minus;
                                    </button>
                                    <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}"
                                           class="h-8 w-14 rounded-lg border border-slate-200 bg-white text-center tabular-nums text-xs font-bold text-slate-900 outline-none focus:border-[#00877A]">
                                    <button type="button" id="qty-plus"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:border-[#00877A] active:scale-95">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Estimasi Total & CTA --}}
                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-slate-500">Estimasi Total Biaya (Pelunasan 100%)</p>
                                <p class="text-[11px] text-slate-400">Tanpa DP 50% &bull; Pembayaran via VA Mandiri BLU / QRIS</p>
                            </div>
                            <p id="estimated-total-display" class="tabular-nums text-xl font-extrabold text-slate-900">
                                Rp {{ number_format($isLab ? $basePrice + $operatorFee : $basePrice, 0, ',', '.') }}
                            </p>
                        </div>

                        <button type="submit"
                                class="mt-3.5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-5 py-3 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-[#006E63] hover:shadow-md active:scale-95">
                            <span>{{ $isLab ? 'Buat Reservasi Lab' : ($isPreOrder ? 'Ajukan Pre-Order' : 'Buat Pesanan Sekarang') }}</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>

                        <p class="mt-2.5 flex items-center justify-center gap-1.5 text-[11px] text-slate-500">
                            <svg width="13" height="13" class="text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            Pemesanan langsung terintegrasi dengan UPA-RTPU Politeknik Negeri Jakarta
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Produk Lainnya --}}
    @if ($relatedProducts->isNotEmpty())
        <section class="pt-2">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Produk &amp; Fasilitas Riset Lainnya</h3>
                <a href="{{ route('b2c.katalog') }}" class="text-xs font-semibold text-[#00877A] hover:underline">Lihat Katalog &rarr;</a>
            </div>
            <div class="mt-2.5 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($relatedProducts as $rel)
                    <a href="{{ route('b2c.produk.show', $rel) }}"
                       class="group flex items-center justify-between gap-3 rounded-lg border border-slate-200/80 bg-white p-3 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/40 hover:shadow-lg">
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold text-[#00877A]">{{ $rel->category?->name ?? 'Riset' }}</p>
                            <p class="truncate text-xs font-bold text-slate-800 group-hover:text-[#00877A]">{{ $rel->name }}</p>
                            <p class="mt-0.5 tabular-nums text-xs font-extrabold text-slate-900">
                                Rp {{ number_format((float) $rel->price_b2c, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400 group-hover:bg-[#00877A]/10 group-hover:text-[#00877A]">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- Modal 04a: Pilih Tanggal Reservasi Lab (Kalender Interaktif) --}}
@if ($isLab)
<div id="b2c-calendar-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="calendar-modal-title">
    <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </span>
                <div>
                    <h3 id="calendar-modal-title" class="text-sm font-bold text-slate-900">Pilih Tanggal Reservasi</h3>
                    <p class="text-[11px] text-slate-500">Pilih tanggal praktikum atau riset mandiri laboratorium</p>
                </div>
            </div>
            <button type="button" data-close-calendar
                    class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Tutup kalender">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h4 id="cal-month-title" class="text-sm font-extrabold text-slate-900"></h4>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Kalender Akademik</span>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" id="cal-prev-month"
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                        &lsaquo;
                    </button>
                    <button type="button" id="cal-next-month"
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                        &rsaquo;
                    </button>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-7 text-center text-[11px] font-semibold text-slate-400">
                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
            </div>
            <div id="cal-days-grid" class="mt-1.5 grid grid-cols-7 gap-1 text-center"></div>

            {{-- Legend --}}
            <div class="mt-3 flex items-center justify-center gap-4 text-[10px] font-medium text-slate-500">
                <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-[#00877A]"></span> Slot Tersedia</span>
                <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Slot Penuh</span>
                <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full border-2 border-[#00877A]"></span> Dipilih</span>
            </div>

            {{-- Selected Date Preview --}}
            <div class="mt-4 flex items-center justify-between rounded-lg border border-[#00877A]/20 bg-[#00877A]/5 px-3.5 py-2.5">
                <div>
                    <p class="text-[10px] font-medium text-slate-500">Tanggal Reservasi Terpilih</p>
                    <p id="cal-selected-preview" class="text-xs font-bold text-slate-900">-</p>
                </div>
                <span class="rounded-md border border-[#00877A]/25 bg-white px-2 py-0.5 text-[10px] font-bold text-[#00877A]">
                    2 Sesi Tersedia
                </span>
            </div>

            <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" data-close-calendar
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 active:scale-95">
                    Batal
                </button>
                <button type="button" id="cal-confirm-btn"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    <span>Konfirmasi Tanggal</span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        const isLab = @json($isLab);
        const basePrice = @json($basePrice);
        const operatorFee = @json($operatorFee);
        const bookedDates = @json($bookedDates);
        const totalDisplay = document.getElementById('estimated-total-display');

        const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

        if (!isLab) {
            const qtyInput = document.getElementById('qty-input');
            const minusBtn = document.getElementById('qty-minus');
            const plusBtn = document.getElementById('qty-plus');
            const maxQty = parseInt(qtyInput?.getAttribute('max') || '50', 10);

            const updateQtyTotal = () => {
                let v = parseInt(qtyInput?.value || '1', 10);
                if (isNaN(v) || v < 1) v = 1;
                if (v > maxQty) v = maxQty;
                if (qtyInput) qtyInput.value = v;
                if (totalDisplay) totalDisplay.textContent = formatRp(basePrice * v);
            };

            minusBtn?.addEventListener('click', () => {
                if (qtyInput) qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
                updateQtyTotal();
            });
            plusBtn?.addEventListener('click', () => {
                if (qtyInput) qtyInput.value = Math.min(maxQty, parseInt(qtyInput.value || '1', 10) + 1);
                updateQtyTotal();
            });
            qtyInput?.addEventListener('input', updateQtyTotal);
            return;
        }

        // Lab Reservation: Operator fee toggle + Quick date strip + Calendar Modal (04a)
        const opCheckbox = document.getElementById('with-operator-checkbox');
        const updateLabTotal = () => {
            const total = basePrice + (opCheckbox?.checked ? operatorFee : 0);
            if (totalDisplay) totalDisplay.textContent = formatRp(total);
        };
        opCheckbox?.addEventListener('change', updateLabTotal);

        const dateInput = document.getElementById('booking-date-input');
        const dateText = document.getElementById('selected-date-text');
        const quickBtns = document.querySelectorAll('.quick-day-btn');

        const monthsID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const daysID = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        const formatDateID = (iso) => {
            const [y, m, d] = iso.split('-').map(Number);
            const dt = new Date(y, m - 1, d);
            return `${daysID[dt.getDay()]}, ${d} ${monthsID[m - 1]} ${y}`;
        };

        const highlightQuickStrip = (iso) => {
            quickBtns.forEach((b) => {
                if (b.disabled) return;
                const active = b.dataset.quickDate === iso;
                b.className = active
                    ? 'quick-day-btn flex flex-col items-center rounded-lg border border-[#00877A] bg-[#00877A] px-2 py-2 text-center text-white shadow-sm transition-all active:scale-95'
                    : 'quick-day-btn flex flex-col items-center rounded-lg border border-slate-200 bg-white px-2 py-2 text-center text-slate-700 transition-all hover:border-[#00877A] active:scale-95';
            });
        };

        quickBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const iso = btn.dataset.quickDate;
                if (dateInput) dateInput.value = iso;
                if (dateText) dateText.textContent = formatDateID(iso);
                highlightQuickStrip(iso);
                tempSelectedIso = iso;
            });
        });

        // Calendar Modal 04a
        const calModal = document.getElementById('b2c-calendar-modal');
        const openCalBtn = document.getElementById('open-calendar-modal-btn');
        const monthTitle = document.getElementById('cal-month-title');
        const daysGrid = document.getElementById('cal-days-grid');
        const selectedPreview = document.getElementById('cal-selected-preview');
        const prevBtn = document.getElementById('cal-prev-month');
        const nextBtn = document.getElementById('cal-next-month');
        const confirmBtn = document.getElementById('cal-confirm-btn');

        let tempSelectedIso = dateInput?.value || new Date().toISOString().slice(0, 10);
        const initParts = tempSelectedIso.split('-').map(Number);
        let viewYear = initParts[0];
        let viewMonth = initParts[1] - 1;

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const renderCalendar = () => {
            if (!daysGrid || !monthTitle) return;
            monthTitle.textContent = `${monthsID[viewMonth]} ${viewYear}`;
            if (selectedPreview) selectedPreview.textContent = formatDateID(tempSelectedIso);

            const firstDay = new Date(viewYear, viewMonth, 1);
            const startOffset = (firstDay.getDay() + 6) % 7; // Monday = 0
            const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

            let html = '';
            for (let i = 0; i < startOffset; i++) {
                html += `<div class="h-9"></div>`;
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dt = new Date(viewYear, viewMonth, d);
                const iso = `${viewYear}-${String(viewMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const isPast = dt < today;
                const isSunday = dt.getDay() === 0;
                const isFull = (bookedDates[iso] || 0) >= 2;
                const isSelected = iso === tempSelectedIso;
                const disabled = isPast || isSunday || isFull;

                let cls = 'relative flex h-9 flex-col items-center justify-center rounded-lg tabular-nums text-xs font-semibold transition-all ';
                if (isSelected) {
                    cls += 'bg-[#00877A] text-white shadow-sm';
                } else if (disabled) {
                    cls += 'cursor-not-allowed text-slate-300';
                } else {
                    cls += 'text-slate-700 hover:bg-[#00877A]/10 hover:text-[#00877A]';
                }

                const dot = isFull
                    ? '<span class="mt-0.5 h-1 w-1 rounded-full bg-rose-500"></span>'
                    : (!isPast && !isSunday ? `<span class="mt-0.5 h-1 w-1 rounded-full ${isSelected ? 'bg-white' : 'bg-[#00877A]'}"></span>` : '');

                html += `<button type="button" data-cal-iso="${iso}" ${disabled ? 'disabled' : ''} class="${cls}"><span>${d}</span>${dot}</button>`;
            }

            daysGrid.innerHTML = html;
            daysGrid.querySelectorAll('[data-cal-iso]:not([disabled])').forEach((btn) => {
                btn.addEventListener('click', () => {
                    tempSelectedIso = btn.dataset.calIso;
                    renderCalendar();
                });
            });
        };

        const openCal = () => {
            renderCalendar();
            calModal?.classList.remove('hidden');
            calModal?.classList.add('flex');
        };
        const closeCal = () => {
            calModal?.classList.add('hidden');
            calModal?.classList.remove('flex');
        };

        openCalBtn?.addEventListener('click', openCal);
        document.querySelectorAll('[data-close-calendar]').forEach((el) => el.addEventListener('click', closeCal));
        calModal?.addEventListener('click', (e) => {
            if (e.target === calModal) closeCal();
        });

        prevBtn?.addEventListener('click', () => {
            viewMonth--;
            if (viewMonth < 0) { viewMonth = 11; viewYear--; }
            renderCalendar();
        });
        nextBtn?.addEventListener('click', () => {
            viewMonth++;
            if (viewMonth > 11) { viewMonth = 0; viewYear++; }
            renderCalendar();
        });

        confirmBtn?.addEventListener('click', () => {
            if (dateInput) dateInput.value = tempSelectedIso;
            if (dateText) dateText.textContent = formatDateID(tempSelectedIso);
            const monthLabel = document.getElementById('selected-month-label');
            const parts = tempSelectedIso.split('-').map(Number);
            if (monthLabel) monthLabel.textContent = `${monthsID[parts[1] - 1]} ${parts[0]}`;
            highlightQuickStrip(tempSelectedIso);
            closeCal();
        });
    })();
</script>
@endpush
