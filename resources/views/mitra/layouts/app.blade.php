<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PNJ Prime B2B</title>

    {{-- Restore sidebar state BEFORE first paint to prevent flicker --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('pnj_b2b_sidebar_collapsed') === '1') {
                    document.documentElement.classList.add('b2b-sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        tosca: {
                            50: '#e6f7f5',
                            100: '#ccf0ec',
                            200: '#99e0d9',
                            500: '#00a896',
                            DEFAULT: '#00998a',
                            600: '#00897b',
                            700: '#007266',
                            800: '#005a50',
                        },
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        /* Collapsible Sidebar Styles (Zero Flicker) */
        #b2b-sidebar {
            width: 16rem; /* w-64 */
        }

        html.b2b-sidebar-ready #b2b-sidebar {
            transition: width 220ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.b2b-sidebar-collapsed #b2b-sidebar {
            width: 72px;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-label,
        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-expanded-only {
            display: none !important;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-collapsed-only {
            display: flex !important;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-nav-link,
        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-cta-btn {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-brand-wrap {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-collapse-icon {
            transform: rotate(180deg);
        }

        /* Tooltip on hover when sidebar is collapsed */
        #b2b-sidebar .sidebar-tooltip-trigger {
            position: relative;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-tooltip-trigger:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background-color: #0f172a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.2;
            padding: 6px 10px;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 60;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.18);
            pointer-events: none;
        }

        html.b2b-sidebar-collapsed #b2b-sidebar .sidebar-tooltip-trigger:hover::before {
            content: '';
            position: absolute;
            left: calc(100% + 4px);
            top: 50%;
            transform: translateY(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: transparent #0f172a transparent transparent;
            z-index: 60;
            pointer-events: none;
        }

        /* Subtle scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-[#F8FAFC] font-sans text-slate-800 antialiased">
@php
    $authUser = auth()->user();
    $resolvedMitra = $mitra ?? app(\App\Http\Controllers\MitraB2BController::class)->resolveMitraUser();

    $companyName = $resolvedMitra->company_name ?: ($authUser->nama_lengkap ?? 'PT Inovasi Teknologi Nusantara');
    $companyShort = \Illuminate\Support\Str::limit($companyName, 26);
    $picName = $resolvedMitra->name ?: ($authUser->nama_lengkap ?? 'Budi Santoso');
    $kycStatus = $resolvedMitra->kyc_status ?? 'approved';

    $initialsCompany = collect(preg_split('/\s+/', trim($companyName)))
        ->filter(fn ($w) => ! in_array(strtoupper($w), ['PT', 'CV', 'UD', 'TBK']))
        ->take(3)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
    $initialsCompany = $initialsCompany !== '' ? $initialsCompany : 'ITN';

    $initialsPic = collect(preg_split('/\s+/', trim($picName)))
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
    $initialsPic = $initialsPic !== '' ? $initialsPic : 'BS';

    $menuAktif = trim($__env->yieldContent('nav', 'dashboard'));
    $navAktifClass = 'bg-[#e6f7f5] text-[#00897b] font-semibold border border-[#00897b]/20';
    $navBiasaClass = 'text-slate-600 font-medium border border-transparent hover:bg-slate-50 hover:text-[#00897b]';

    $activeOrdersCount = 0;
    if (isset($kpis['pesanan_aktif'])) {
        $activeOrdersCount = (int) $kpis['pesanan_aktif'];
    } elseif ($resolvedMitra && isset($resolvedMitra->id) && $resolvedMitra->id > 0) {
        $activeOrdersCount = \App\Models\Order::query()
            ->where('user_id', $resolvedMitra->id)
            ->where('segment', 'b2b')
            ->whereIn('status', ['menunggu_verifikasi', 'menunggu_pembayaran', 'diproses', 'siap_diambil'])
            ->count();
    }

    $currentSearch = $searchQuery ?? request()->query('q', '');
    $searchPlaceholder = trim($__env->yieldContent('search_placeholder', 'Cari pesanan, produk, unit, atau invoice...'));
    $searchTargetUrl = Route::currentRouteName() === 'b2b.katalog'
        ? route('b2b.katalog')
        : (Route::currentRouteName() === 'b2b.dashboard' ? route('b2b.dashboard') : route('b2b.katalog'));
@endphp

<div class="flex min-h-screen">
    {{-- ===================================================================== --}}
    {{-- SIDEBAR COLLAPSIBLE MITRA B2B                                         --}}
    {{-- ===================================================================== --}}
    <aside id="b2b-sidebar"
           class="sticky top-0 z-30 flex h-screen shrink-0 flex-col border-r border-slate-200/80 bg-white select-none">

        {{-- Brand Header --}}
        <div class="sidebar-brand-wrap flex items-center justify-between gap-2.5 border-b border-slate-100 px-4 py-3.5">
            <a href="{{ route('b2b.dashboard') }}" class="flex min-w-0 items-center gap-2.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#00998a] text-white shadow-xs">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"/>
                        <path d="M5 21V7l7-4 7 4v14"/>
                        <path d="M9 21v-6h6v6"/>
                    </svg>
                </span>
                <div class="sidebar-label min-w-0 leading-tight">
                    <p class="text-sm font-bold tracking-tight text-[#00897b]">PNJ Prime</p>
                    <p class="truncate text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Portal Mitra B2B</p>
                </div>
            </a>

            <button type="button"
                    id="b2b-sidebar-toggle"
                    aria-label="Perkecil atau Perluas Sidebar"
                    title="Perkecil / Perluas Sidebar"
                    class="sidebar-expanded-only flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-slate-200/80 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-[#00897b] active:scale-95">
                <svg class="sidebar-collapse-icon h-3.5 w-3.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </button>
        </div>

        {{-- Mini Toggle when Collapsed --}}
        <div class="sidebar-collapsed-only hidden justify-center border-b border-slate-100 py-2">
            <button type="button"
                    id="b2b-sidebar-expand"
                    data-tooltip="Perluas Sidebar"
                    aria-label="Perluas Sidebar"
                    class="sidebar-tooltip-trigger flex h-8 w-8 items-center justify-center rounded-md border border-slate-200/80 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-[#00897b] active:scale-95">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </button>
        </div>

        {{-- Company Badge (Expanded) --}}
        <div class="sidebar-expanded-only px-3.5 pt-3.5">
            <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-2.5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white border border-slate-200 text-[11px] font-bold text-[#00897b]">
                        {{ $initialsCompany }}
                    </span>
                    <div class="min-w-0 flex-1 leading-tight">
                        <p class="truncate text-xs font-bold text-slate-800" title="{{ $companyName }}">{{ $companyShort }}</p>
                        <p class="text-[10px] text-slate-500">Mitra Industri Aktif</p>
                    </div>
                </div>
                <div class="mt-2 flex items-center justify-between gap-1.5">
                    @if ($kycStatus === 'approved')
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Verified Partner
                        </span>
                    @elseif ($kycStatus === 'rejected')
                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-200/80 px-2 py-0.5 text-[10px] font-semibold text-rose-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            KYC Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200/80 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Verifikasi KYC
                        </span>
                    @endif
                    <span class="tabular-nums text-[10px] font-medium text-slate-400">100% Lunas</span>
                </div>
            </div>
        </div>

        {{-- CTA Button --}}
        <div class="px-3 pt-3 pb-2">
            <a href="{{ route('b2b.katalog') }}"
               data-tooltip="Buat Pesanan B2B"
               class="sidebar-tooltip-trigger sidebar-cta-btn flex w-full items-center justify-center gap-2 rounded-lg bg-[#00998a] px-3 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-[#007d71] active:scale-95">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span class="sidebar-label">Buat Pesanan</span>
            </a>
        </div>

        {{-- Navigation Menu (Tanpa Menu Kerja Sama / MoU) --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-2" aria-label="Navigasi Mitra B2B">
            <a href="{{ route('b2b.dashboard') }}"
               data-tooltip="Dashboard"
               class="sidebar-tooltip-trigger sidebar-nav-link flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] transition-all duration-150 {{ $menuAktif === 'dashboard' ? $navAktifClass : $navBiasaClass }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                <span class="sidebar-label flex-1 truncate">Dashboard</span>
            </a>

            <a href="{{ route('b2b.katalog') }}"
               data-tooltip="Katalog Riset"
               class="sidebar-tooltip-trigger sidebar-nav-link flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] transition-all duration-150 {{ $menuAktif === 'katalog' ? $navAktifClass : $navBiasaClass }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z"/>
                </svg>
                <span class="sidebar-label flex-1 truncate">Katalog Riset</span>
            </a>

            <a href="{{ route('b2b.katalog', ['order_type' => 'lab_reservation']) }}"
               data-tooltip="Sewa Fasilitas Lab"
               class="sidebar-tooltip-trigger sidebar-nav-link flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] transition-all duration-150 {{ $menuAktif === 'lab' ? $navAktifClass : $navBiasaClass }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 3h6"/>
                    <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"/>
                    <path d="M7.5 14h9"/>
                </svg>
                <span class="sidebar-label flex-1 truncate">Sewa Fasilitas Lab</span>
            </a>

            <a href="{{ route('b2b.dashboard') }}#daftar-pesanan"
               data-tooltip="Status Pesanan"
               class="sidebar-tooltip-trigger sidebar-nav-link flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] transition-all duration-150 {{ $menuAktif === 'pesanan' ? $navAktifClass : $navBiasaClass }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <path d="m9 14 2 2 4-4"/>
                </svg>
                <span class="sidebar-label flex-1 truncate">Status Pesanan</span>
                @if ($activeOrdersCount > 0)
                    <span class="sidebar-label tabular-nums rounded-full bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-700">
                        {{ $activeOrdersCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('b2b.profil') }}"
               data-tooltip="Profil Perusahaan"
               class="sidebar-tooltip-trigger sidebar-nav-link flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] transition-all duration-150 {{ $menuAktif === 'profil' ? $navAktifClass : $navBiasaClass }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="sidebar-label flex-1 truncate">Profil Perusahaan</span>
            </a>
        </nav>

        {{-- Sidebar Footer --}}
        <div class="border-t border-slate-100 px-4 py-3 text-[11px] text-slate-500">
            <div class="sidebar-expanded-only">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        System Online
                    </span>
                    <span class="tabular-nums text-slate-400">v2.4 Enterprise</span>
                </div>
                <p class="mt-1 text-[10px] text-slate-400">PNJ Prime Enterprise B2B</p>
            </div>
            <div class="sidebar-collapsed-only hidden justify-center">
                <span class="sidebar-tooltip-trigger h-2.5 w-2.5 rounded-full bg-emerald-500" data-tooltip="System Online • v2.4"></span>
            </div>
        </div>
    </aside>

    {{-- ===================================================================== --}}
    {{-- MAIN WORKSPACE AREA                                                   --}}
    {{-- ===================================================================== --}}
    <div class="flex min-h-screen min-w-0 flex-1 flex-col">
        {{-- Top Navbar Compact --}}
        <header class="sticky top-0 z-20 flex h-14 items-center justify-between gap-4 border-b border-slate-200/80 bg-white/95 px-6 backdrop-blur-xs">
            {{-- Global Search Bar (100% Functional) --}}
            <form action="{{ $searchTargetUrl }}" method="GET" id="b2b-search-form" class="relative flex-1 max-w-md">
                <svg class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <input type="text"
                       id="b2b-global-search"
                       name="q"
                       value="{{ $currentSearch }}"
                       autocomplete="off"
                       placeholder="{{ $searchPlaceholder }}"
                       class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 pr-16 pl-9 text-xs font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#00998a] focus:bg-white focus:ring-2 focus:ring-[#00998a]/15">

                <button type="button"
                        id="b2b-search-clear"
                        aria-label="Reset pencarian"
                        title="Reset pencarian"
                        class="{{ $currentSearch !== '' ? '' : 'hidden' }} absolute top-1/2 right-2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full bg-slate-200/80 text-xs font-bold text-slate-600 transition hover:bg-slate-300 hover:text-slate-800 active:scale-95">
                    &times;
                </button>

                <kbd id="b2b-search-kbd"
                     class="{{ $currentSearch !== '' ? 'hidden' : '' }} pointer-events-none absolute top-1/2 right-2 -translate-y-1/2 rounded border border-slate-200 bg-white px-1.5 py-0.5 text-[10px] font-semibold text-slate-400">
                    Ctrl+K
                </kbd>
            </form>

            {{-- Right Utilities --}}
            <div class="flex items-center gap-3">
                {{-- Tax Readiness Pill (Desktop) --}}
                <span class="hidden xl:inline-flex items-center gap-1.5 rounded-full border border-teal-200/80 bg-[#e6f7f5] px-2.5 py-1 text-[11px] font-semibold text-[#007266]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    Faktur Pajak &amp; BAST Siap
                </span>

                {{-- Help Button --}}
                <div class="relative">
                    <button type="button"
                            id="b2b-help-btn"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-transparent text-slate-500 transition hover:border-slate-200 hover:bg-slate-50 hover:text-[#00897b] active:scale-95"
                            aria-label="Informasi Layanan B2B">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <path d="M12 17h.01"/>
                        </svg>
                    </button>
                    <div id="b2b-help-popover" class="hidden absolute right-0 mt-2 w-72 rounded-lg border border-slate-200 bg-white p-3.5 shadow-lg z-40">
                        <p class="text-xs font-bold text-slate-800">Desk Layanan Mitra B2B PNJ</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            Seluruh transaksi menggunakan tarif komersial B2B dengan skema pembayaran <strong>100% Lunas</strong> (VA Mandiri BLU / QRIS), dilengkapi faktur PPN 11%, bukti potong PPh 23, e-Meterai, dan BAST resmi.
                        </p>
                        <div class="mt-2.5 flex items-center justify-between border-t border-slate-100 pt-2 text-[11px]">
                            <span class="text-slate-400">kemitraan@pnj.ac.id</span>
                            <a href="{{ route('b2b.profil') }}" class="font-semibold text-[#00897b] hover:underline">Profil Legal &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- User Info --}}
                <a href="{{ route('b2b.profil') }}" class="flex items-center gap-2.5 border-l border-slate-200 pl-3 group">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#e6f7f5] text-xs font-bold text-[#00897b] ring-1 ring-[#00897b]/20">
                        {{ $initialsPic }}
                    </span>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-xs font-semibold text-slate-800 group-hover:text-[#00897b] transition-colors">{{ $picName }}</p>
                        <p class="text-[11px] text-slate-500 truncate max-w-[165px]">{{ $companyShort }}</p>
                    </div>
                </a>

                {{-- Logout Trigger (Opens Confirmation Modal) --}}
                <button type="button"
                        id="b2b-logout-trigger"
                        class="inline-flex h-8 items-center gap-1.5 rounded-lg border border-slate-200/80 bg-white px-2.5 text-xs font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 active:scale-95"
                        aria-label="Keluar">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <path d="m16 17 5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>
                    <span class="hidden md:inline">Keluar</span>
                </button>
            </div>
        </header>

        {{-- Live Search Indicator Banner (shown automatically when search active) --}}
        <div id="b2b-search-status-bar" class="hidden border-b border-teal-200/70 bg-[#e6f7f5]/80 px-6 py-2">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 text-xs">
                <p class="font-medium text-[#007266]">
                    <span id="b2b-search-count-text" class="tabular-nums font-semibold"></span>
                    untuk kata kunci "<span id="b2b-search-term-text" class="font-bold"></span>"
                </p>
                <button type="button"
                        id="b2b-search-bar-reset"
                        class="inline-flex items-center gap-1 rounded-md border border-[#00897b]/30 bg-white px-2 py-0.5 text-[11px] font-semibold text-[#00897b] transition hover:bg-teal-50 active:scale-95">
                    Reset Pencarian &times;
                </button>
            </div>
        </div>

        {{-- Flash Message --}}
        @if (session('success') || session('error') || session('info'))
            <div class="mx-auto w-full max-w-7xl px-6 pt-4">
                @if (session('success'))
                    <div class="flex items-center justify-between gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-medium text-emerald-800">
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="font-bold text-emerald-600 hover:text-emerald-900">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="flex items-center justify-between gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-medium text-rose-800">
                        <span>{{ session('error') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="font-bold text-rose-600 hover:text-rose-900">&times;</button>
                    </div>
                @endif
                @if (session('info'))
                    <div class="flex items-center justify-between gap-3 rounded-lg border border-teal-200 bg-teal-50 px-4 py-2.5 text-xs font-medium text-teal-800">
                        <span>{{ session('info') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="font-bold text-teal-600 hover:text-teal-900">&times;</button>
                    </div>
                @endif
            </div>
        @endif

        {{-- Page Content --}}
        <main class="mx-auto w-full max-w-7xl flex-1 px-6 py-5">
            @yield('content')
        </main>

        {{-- Compact Footer --}}
        <footer class="border-t border-slate-200/80 bg-white px-6 py-3">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400">
                <p><span class="font-semibold text-slate-600">PNJ Prime</span> &bull; Portal Mitra Industri (B2B) Politeknik Negeri Jakarta</p>
                <p class="tabular-nums">Tarif Komersial BLU &bull; Skema 100% Lunas &bull; PPN 11%, PPh 23, e-Meterai &amp; BAST</p>
            </div>
        </footer>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL KONFIRMASI LOGOUT (WAJIB SEBELUM POST /logout)                      --}}
{{-- ========================================================================= --}}
<div id="b2b-logout-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]"
     role="dialog"
     aria-modal="true"
     aria-labelledby="b2b-logout-title">
    <div class="w-full max-w-sm rounded-lg border border-slate-200/80 bg-white p-5 shadow-xl">
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-rose-50 border border-rose-200/70 text-rose-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <path d="m16 17 5-5-5-5"/>
                    <path d="M21 12H9"/>
                </svg>
            </span>
            <div>
                <h3 id="b2b-logout-title" class="text-sm font-bold text-slate-900">Konfirmasi Keluar Akun</h3>
                <p class="mt-1 text-xs leading-relaxed text-slate-500">
                    Apakah Anda yakin ingin keluar dari sesi Portal Mitra Industri <strong class="text-slate-700">{{ $companyShort }}</strong>?
                </p>
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-2.5 border-t border-slate-100 pt-3.5">
            <button type="button"
                    id="b2b-logout-cancel"
                    class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 active:scale-95">
                Batal
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="rounded-lg bg-rose-600 px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-700 active:scale-95">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        // Enable smooth sidebar width transitions only after initial paint
        requestAnimationFrame(function () {
            document.documentElement.classList.add('b2b-sidebar-ready');
        });

        // Sidebar collapse toggle + localStorage persistence
        function setSidebarCollapsed(collapsed) {
            document.documentElement.classList.toggle('b2b-sidebar-collapsed', collapsed);
            try {
                localStorage.setItem('pnj_b2b_sidebar_collapsed', collapsed ? '1' : '0');
            } catch (e) {}
        }

        const toggleBtn = document.getElementById('b2b-sidebar-toggle');
        const expandBtn = document.getElementById('b2b-sidebar-expand');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const isCollapsed = document.documentElement.classList.contains('b2b-sidebar-collapsed');
                setSidebarCollapsed(!isCollapsed);
            });
        }
        if (expandBtn) {
            expandBtn.addEventListener('click', function () {
                setSidebarCollapsed(false);
            });
        }

        // Logout confirmation modal
        const logoutTrigger = document.getElementById('b2b-logout-trigger');
        const logoutModal = document.getElementById('b2b-logout-modal');
        const logoutCancel = document.getElementById('b2b-logout-cancel');

        function closeLogoutModal() {
            if (!logoutModal) return;
            logoutModal.classList.add('hidden');
            logoutModal.classList.remove('flex');
        }

        if (logoutTrigger && logoutModal) {
            logoutTrigger.addEventListener('click', function () {
                logoutModal.classList.remove('hidden');
                logoutModal.classList.add('flex');
            });
        }
        if (logoutCancel) {
            logoutCancel.addEventListener('click', closeLogoutModal);
        }
        if (logoutModal) {
            logoutModal.addEventListener('click', function (e) {
                if (e.target === logoutModal) closeLogoutModal();
            });
        }

        // Help popover
        const helpBtn = document.getElementById('b2b-help-btn');
        const helpPopover = document.getElementById('b2b-help-popover');
        if (helpBtn && helpPopover) {
            helpBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                helpPopover.classList.toggle('hidden');
            });
            document.addEventListener('click', function (e) {
                if (!helpPopover.contains(e.target) && e.target !== helpBtn) {
                    helpPopover.classList.add('hidden');
                }
            });
        }

        // Global Top Navbar Search Bar (Live Filter + ?q=... sync + Ctrl+K + Reset)
        const searchInput = document.getElementById('b2b-global-search');
        const searchClear = document.getElementById('b2b-search-clear');
        const searchKbd = document.getElementById('b2b-search-kbd');
        const statusBar = document.getElementById('b2b-search-status-bar');
        const countText = document.getElementById('b2b-search-count-text');
        const termText = document.getElementById('b2b-search-term-text');
        const barReset = document.getElementById('b2b-search-bar-reset');
        const searchForm = document.getElementById('b2b-search-form');

        function applyGlobalSearch(rawValue, syncUrl) {
            const term = (rawValue || '').trim().toLowerCase();

            if (searchClear && searchKbd) {
                if (term.length > 0) {
                    searchClear.classList.remove('hidden');
                    searchKbd.classList.add('hidden');
                } else {
                    searchClear.classList.add('hidden');
                    searchKbd.classList.remove('hidden');
                }
            }

            const items = Array.from(document.querySelectorAll('[data-search-item]'));
            let visibleCount = 0;

            if (items.length > 0) {
                items.forEach(function (el) {
                    const haystack = (el.getAttribute('data-search-text') || el.textContent || '').toLowerCase();
                    const matchesSearch = term === '' || haystack.includes(term);
                    el.dataset.searchMatch = matchesSearch ? '1' : '0';

                    // Respect secondary page filters (e.g., dashboard status tab) if present
                    const statusMatch = el.dataset.statusMatch !== '0';
                    const shouldShow = matchesSearch && statusMatch;
                    el.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) visibleCount++;
                });

                const liveEmptyState = document.getElementById('b2b-live-search-empty');
                if (liveEmptyState) {
                    liveEmptyState.classList.toggle('hidden', !(term !== '' && visibleCount === 0));
                }

                const resultCounter = document.getElementById('b2b-page-result-count');
                if (resultCounter) {
                    resultCounter.textContent = String(visibleCount);
                }
            }

            if (statusBar && countText && termText) {
                if (term !== '' && items.length > 0) {
                    countText.textContent = 'Menampilkan ' + visibleCount + ' dari ' + items.length + ' hasil';
                    termText.textContent = rawValue.trim();
                    statusBar.classList.remove('hidden');
                } else {
                    statusBar.classList.add('hidden');
                }
            }

            if (syncUrl && window.history && window.history.replaceState) {
                try {
                    const url = new URL(window.location.href);
                    if (term !== '') {
                        url.searchParams.set('q', rawValue.trim());
                    } else {
                        url.searchParams.delete('q');
                    }
                    window.history.replaceState({}, '', url.toString());
                } catch (e) {}
            }

            window.dispatchEvent(new CustomEvent('b2b:search', {
                detail: { query: rawValue || '', term: term, visibleCount: visibleCount, totalCount: items.length }
            }));
        }

        window.b2bApplySearch = applyGlobalSearch;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                applyGlobalSearch(searchInput.value, true);
            });

            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    // On pages that have live searchable items (Dashboard / Katalog), keep instant filter without page jump;
                    // On Detail/Profil without list items, submit to Katalog ?q=...
                    if (document.querySelectorAll('[data-search-item]').length > 0) {
                        e.preventDefault();
                        applyGlobalSearch(searchInput.value, true);
                    }
                });
            }

            const resetSearch = function () {
                searchInput.value = '';
                applyGlobalSearch('', true);
                searchInput.focus();
            };

            if (searchClear) searchClear.addEventListener('click', resetSearch);
            if (barReset) barReset.addEventListener('click', resetSearch);

            // Apply initial ?q=... if present
            if (searchInput.value.trim() !== '') {
                applyGlobalSearch(searchInput.value, false);
            }
        }

        // Global keyboard shortcuts: Ctrl+K focuses search, Escape closes modal/popover
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                if (searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
            }
            if (e.key === 'Escape') {
                closeLogoutModal();
                if (helpPopover) helpPopover.classList.add('hidden');
            }
        });
    })();
</script>

@stack('scripts')
</body>
</html>
