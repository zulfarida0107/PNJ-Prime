@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Katalog Utama')
@section('nav', 'katalog')

@section('content')
@php
    if (! isset($featuredProducts)) {
        $resolvedUser = $portalUser ?? \App\Models\PortalUser::where('email', auth()->user()?->email ?? 'bagas@pnj.ac.id')->first();
        $waitingPaymentCount = $resolvedUser
            ? \App\Models\Order::where('user_id', $resolvedUser->id)->where('segment', 'b2c')->where('status', 'menunggu_pembayaran')->count()
            : 0;
        $processingCount = $resolvedUser
            ? \App\Models\Order::where('user_id', $resolvedUser->id)->where('segment', 'b2c')->whereIn('status', ['menunggu_verifikasi', 'diproses', 'siap_diambil'])->count()
            : 0;
        $completedCount = $resolvedUser
            ? \App\Models\Order::where('user_id', $resolvedUser->id)->where('segment', 'b2c')->where('status', 'selesai')->count()
            : 0;
        $featuredProducts = \App\Models\Product::with(['unit', 'category', 'subCategory'])
            ->where('status', 'tayang')
            ->latest('id')
            ->take(4)
            ->get();
        $units = \App\Models\Unit::orderBy('name')->get();
    }
@endphp

<div class="space-y-4">
    {{-- Statistik (3 Stat Card Compact) --}}
    <div class="grid grid-cols-1 gap-3.5 md:grid-cols-3">
        <a href="{{ route('b2c.pesanan', ['status' => 'menunggu_pembayaran']) }}"
           class="group flex items-center justify-between rounded-lg border border-slate-200/80 bg-white p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/50 hover:shadow-lg">
            <div>
                <p class="text-xs font-medium text-slate-500">Menunggu Pembayaran</p>
                <p class="mt-0.5 tabular-nums text-2xl font-extrabold text-slate-900">{{ $waitingPaymentCount }}</p>
                <p class="mt-0.5 text-[11px] text-slate-500">Segera konfirmasi invoice lunas 100%</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00877A]/10 text-[#00877A] transition-transform duration-300 group-hover:scale-110">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 2 2 2-2 2 2 3-2V8z" />
                    <path d="M14 2v6h6" />
                    <path d="M8 13h6M8 17h4" />
                </svg>
            </span>
        </a>

        <a href="{{ route('b2c.pesanan', ['status' => 'diproses']) }}"
           class="group flex items-center justify-between rounded-lg border border-slate-200/80 bg-white p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/50 hover:shadow-lg">
            <div>
                <p class="text-xs font-medium text-slate-500">Diproses RTPU</p>
                <p class="mt-0.5 tabular-nums text-2xl font-extrabold text-slate-900">{{ $processingCount }}</p>
                <p class="mt-0.5 text-[11px] text-slate-500">Dalam preparasi &amp; jadwal lab riset</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00877A]/10 text-[#00877A] transition-transform duration-300 group-hover:scale-110">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 3h6" />
                    <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                    <path d="M7.5 14h9" />
                </svg>
            </span>
        </a>

        <a href="{{ route('b2c.pesanan', ['status' => 'selesai']) }}"
           class="group flex items-center justify-between rounded-lg border border-slate-200/80 bg-white p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/50 hover:shadow-lg">
            <div>
                <p class="text-xs font-medium text-slate-500">Selesai</p>
                <p class="mt-0.5 tabular-nums text-2xl font-extrabold text-slate-900">{{ $completedCount }}</p>
                <p class="mt-0.5 text-[11px] text-slate-500">Instrumen &amp; sesi lab telah diterima</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00877A]/10 text-[#00877A] transition-transform duration-300 group-hover:scale-110">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m8 12 3 3 5-6" />
                </svg>
            </span>
        </a>
    </div>

    {{-- Hero Banner Ramping --}}
    <section class="relative overflow-hidden rounded-xl bg-gradient-to-r from-[#006E63] via-[#00877A] to-teal-500 p-5 text-white shadow-sm">
        <div class="pointer-events-none absolute -right-16 -bottom-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute top-0 right-1/3 h-36 w-36 rounded-full bg-white/5 blur-2xl"></div>

        <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-2.5 py-0.5 text-[11px] font-semibold">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m8 12 3 3 5-6" />
                    </svg>
                    Riset Terapan PNJ &bull; Tarif Sivitas Akademika
                </span>
                <h2 class="mt-1.5 text-xl font-extrabold tracking-tight">Eksplorasi Produk Riset &amp; Fasilitas Lab</h2>
                <p class="mt-0.5 text-xs text-white/90">Inovasi teknologi, purwarupa, dan reservasi laboratorium riset terapan kampus langsung untuk mahasiswa &amp; dosen.</p>
            </div>

            <a href="{{ route('b2c.katalog') }}"
               class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-white px-4 py-2 text-xs font-bold text-[#00877A] shadow-sm transition-all duration-200 hover:bg-teal-50 hover:shadow-md active:scale-95">
                <span>Jelajahi Katalog Lengkap</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </section>

    {{-- Katalog Produk Riset Pilihan --}}
    <section id="katalog">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold leading-tight text-slate-900">Katalog Produk Riset Pilihan</h3>
                <p class="text-xs text-slate-500">Menampilkan {{ $featuredProducts->count() }} instrumen &amp; fasilitas terverifikasi RTPU</p>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('b2c.katalog') }}" method="GET" class="relative">
                    <svg width="14" height="14" class="pointer-events-none absolute top-1/2 left-2.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 6h16M7 12h10M10 18h4" />
                    </svg>
                    <select name="unit_id" onchange="this.form.submit()"
                            class="h-8 appearance-none rounded-lg border border-slate-200 bg-white pr-7 pl-8 text-xs font-medium text-slate-600 outline-none transition-all hover:border-[#00877A] focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <option value="">Semua Departemen Riset</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('b2c.katalog') }}"
                   class="inline-flex h-8 items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-[#00877A] transition-all hover:border-[#00877A] hover:bg-[#00877A]/5 active:scale-95">
                    Lihat Semua
                </a>
            </div>
        </div>

        <div class="mt-3 grid grid-cols-1 gap-3.5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($featuredProducts as $item)
                @php
                    $badgeMeta = match ($item->order_type) {
                        'lab_reservation' => ['label' => 'Fasilitas Lab', 'cls' => 'bg-sky-50 text-sky-700 border-sky-200'],
                        'pre_order' => ['label' => 'Pre-Order', 'cls' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        default => ['label' => 'Ready Stock', 'cls' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                    };
                @endphp
                <article class="group flex flex-col rounded-lg border border-slate-200/80 bg-white p-3.5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:border-[#00877A]/40 hover:shadow-lg">
                    <div class="relative aspect-[16/10] overflow-hidden rounded-lg bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/70">
                        @if ($item->resolved_image_url)
                            <img src="{{ $item->resolved_image_url }}"
                                 alt="{{ $item->name }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#00877A]/70 transition-transform duration-300 group-hover:scale-105">
                                @if ($item->order_type === 'lab_reservation')
                                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="14" rx="2" />
                                        <path d="M7 21h10M12 17v4M8 9h8M8 13h4" />
                                    </svg>
                                @elseif ($item->order_type === 'pre_order')
                                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 3h6" />
                                        <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                                        <path d="M7.5 14h9" />
                                    </svg>
                                @else
                                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                        <rect x="9.5" y="9.5" width="5" height="5" />
                                        <path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4" />
                                    </svg>
                                @endif
                            </div>
                        @endif
                        <span class="absolute top-2 right-2 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $badgeMeta['cls'] }}">
                            &bull; {{ $badgeMeta['label'] }}
                        </span>
                    </div>

                    <div class="mt-3 flex flex-1 flex-col">
                        <span class="w-fit rounded-md bg-[#00877A]/10 px-2 py-0.5 text-[10px] font-semibold text-[#00877A]">
                            {{ $item->category?->name ?? 'Riset' }} &bull; {{ $item->unit?->code ?? 'PNJ' }}
                        </span>
                        <h4 class="mt-1.5 line-clamp-2 min-h-[2.5rem] text-sm font-bold leading-snug text-slate-900 transition-colors group-hover:text-[#00877A]">
                            {{ $item->name }}
                        </h4>

                        <div class="mt-auto border-t border-slate-100 pt-2.5">
                            <p class="text-[10px] font-medium text-slate-400">
                                {{ $item->order_type === 'lab_reservation' ? 'Tarif Reservasi Sivitas' : 'Harga Sivitas Akademika' }}
                            </p>
                            <p class="tabular-nums text-sm font-extrabold text-slate-900">
                                Rp {{ number_format((float) $item->price_b2c, 0, ',', '.') }}
                                @if ($item->order_type === 'lab_reservation')
                                    <span class="text-[11px] font-medium text-slate-500">/ Sesi</span>
                                @endif
                            </p>
                        </div>

                        <a href="{{ route('b2c.produk.show', $item) }}"
                           class="mt-2.5 inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2 text-center text-xs font-semibold text-white transition-all duration-200 hover:bg-[#006E63] hover:shadow-md active:scale-95">
                            <span>Lihat Detail</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
