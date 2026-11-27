<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog Riset & Inovasi') — PNJ Prime</title>

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
                        pnj: {
                            50: '#E6F4F2',
                            100: '#CCE9E5',
                            200: '#99D3CB',
                            500: '#009B8C',
                            600: '#00877A',
                            700: '#006E63',
                            800: '#00564D',
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
        .tech-grid-bg {
            background-color: #0F172A;
            background-image:
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        @media print {
            aside, header, #public-guest-modal, .no-print {
                display: none !important;
            }
            body {
                background: #FFFFFF !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
@php
    $authUser = auth()->user();
    $isInternalAdmin = $authUser && in_array($authUser->role, ['rtpu', 'admin', 'p3m', 'pimpinan'], true);
    $activeNav = trim($__env->yieldContent('public_nav', 'katalog'));
    $navActiveClass = 'bg-[#E6F4F2] text-[#00877A] font-semibold border-l-[3px] border-[#00877A] rounded-r-lg';
    $navIdleClass = 'text-slate-600 font-medium border-l-[3px] border-transparent hover:bg-slate-50 hover:text-[#00877A] rounded-r-lg';
    $currentSearch = $filters['q'] ?? ($searchQuery ?? request()->query('q', ''));
    $resolvedDashboardUrl = $dashboardUrl ?? route('login');
@endphp

<div class="flex min-h-screen">
    {{-- ===================================================================== --}}
    {{-- MOBILE SIDEBAR BACKDROP                                               --}}
    {{-- ===================================================================== --}}
    <div id="public-sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/40 lg:hidden"></div>

    {{-- ===================================================================== --}}
    {{-- SIDEBAR KIRI BERSIH (#FFFFFF, border-r border-slate-200)              --}}
    {{-- ===================================================================== --}}
    <aside id="public-sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-60 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 shrink-0 select-none">

        {{-- Brand Logo PNJ Prime --}}
        <div class="flex h-16 items-center justify-between px-5 border-b border-slate-100">
            <a href="{{ route('public.katalog') }}" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#00877A] text-white">
                    <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </span>
                <span class="text-base font-bold tracking-tight text-[#00877A]">PNJ Prime</span>
            </a>

            <button type="button" id="public-sidebar-close"
                    class="lg:hidden rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                    aria-label="Tutup navigasi">
                <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Menu Navigasi Utama (Role-Aware) --}}
        <nav class="flex-1 space-y-1 overflow-y-auto py-4 pr-3 pl-2" aria-label="Navigasi Portal PNJ Prime">
            {{-- Dashboard --}}
            @auth
                <a href="{{ $resolvedDashboardUrl }}"
                   class="flex items-center gap-3 px-3 py-2.5 text-[13px] transition-colors {{ $navIdleClass }}">
                    <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                    </svg>
                    <span>{{ $isInternalAdmin ? 'Dashboard Admin' : 'Dashboard' }}</span>
                </a>
            @else
                <button type="button"
                        data-guest-gate
                        data-gate-title="Akses Dashboard Portal PNJ Prime"
                        data-gate-desc="Silakan masuk dengan akun Anda atau daftar sebagai Mitra Industri B2B untuk membuka Dashboard transaksi dan analitik."
                        class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-[13px] transition-colors {{ $navIdleClass }}">
                    <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                    </svg>
                    <span>Dashboard</span>
                </button>
            @endauth

            {{-- Katalog Riset (Aktif Pill Teal Lembut) --}}
            <a href="{{ route('public.katalog') }}"
               class="flex items-center gap-3 px-3 py-2.5 text-[13px] transition-colors {{ $activeNav === 'katalog' ? $navActiveClass : $navIdleClass }}">
                <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <span>Katalog Riset</span>
            </a>

            {{-- Status Pesanan (Hanya untuk Guest & Pembeli, disembunyikan untuk Role Internal/Admin) --}}
            @if (! $isInternalAdmin)
                @auth
                    <a href="{{ $authUser->role === 'mitra' && Route::has('b2b.dashboard') ? route('b2b.dashboard') . '#daftar-pesanan' : $resolvedDashboardUrl }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[13px] transition-colors {{ $navIdleClass }}">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path>
                            <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                            <path d="m9 14 2 2 4-4"></path>
                        </svg>
                        <span>Status Pesanan</span>
                    </a>
                @else
                    <button type="button"
                            data-guest-gate
                            data-gate-title="Pantau Status Pesanan & Invoice"
                            data-gate-desc="Masuk ke akun PNJ Prime Anda untuk melacak progres pesanan, jadwal produksi, dan status pembayaran 100% Lunas."
                            class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-[13px] transition-colors {{ $navIdleClass }}">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path>
                            <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                            <path d="m9 14 2 2 4-4"></path>
                        </svg>
                        <span>Status Pesanan</span>
                    </button>
                @endauth
            @endif

            {{-- Sewa Fasilitas Lab --}}
            <a href="{{ route('public.katalog', ['order_type' => 'lab_reservation']) }}"
               class="flex items-center gap-3 px-3 py-2.5 text-[13px] transition-colors {{ $activeNav === 'lab' ? $navActiveClass : $navIdleClass }}">
                <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 3h6"></path>
                    <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"></path>
                    <path d="M7.5 14h9"></path>
                </svg>
                <span>Sewa Fasilitas Lab</span>
            </a>

            {{-- Inbox / Pesan & Pengaturan Akun (Hanya untuk Guest & Pembeli, disembunyikan untuk Role Internal/Admin) --}}
            @if (! $isInternalAdmin)
                @auth
                    <a href="{{ $resolvedDashboardUrl }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[13px] transition-colors {{ $navIdleClass }}">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>Inbox / Pesan</span>
                    </a>
                @else
                    <button type="button"
                            data-guest-gate
                            data-gate-title="Inbox & Komunikasi Resmi RTPU"
                            data-gate-desc="Silakan masuk atau daftar akun untuk mengakses pesan kurasi teknis, penawaran resmi, dan notifikasi pesanan."
                            class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-[13px] transition-colors {{ $navIdleClass }}">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>Inbox / Pesan</span>
                    </button>
                @endauth

                @auth
                    <a href="{{ $authUser->role === 'mitra' && Route::has('b2b.profil') ? route('b2b.profil') : $resolvedDashboardUrl }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-[13px] transition-colors {{ $navIdleClass }}">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Pengaturan Akun</span>
                    </a>
                @else
                    <button type="button"
                            data-guest-gate
                            data-gate-title="Pengaturan Akun & Profil Legalitas"
                            data-gate-desc="Masuk atau daftar sebagai Mitra Industri B2B untuk mengelola profil perusahaan, NPWP, NIB, dan keamanan akun."
                            class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-[13px] transition-colors {{ $navIdleClass }}">
                        <svg width="16" height="16" class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Pengaturan Akun</span>
                    </button>
                @endauth
            @endif
        </nav>

        {{-- Footer Sidebar Kiri Bawah (Persis Mockup) --}}
        <div class="border-t border-slate-100 px-5 py-3.5 text-[11px] text-slate-500">
            <div class="flex items-center justify-between gap-2">
                <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    System Online
                </span>
                <span class="tabular-nums text-[10px] text-slate-400">v2.4 Enterprise</span>
            </div>
            <p class="mt-1 text-[10px] text-slate-400">PNJ Prime Enterprise Portal</p>
        </div>
    </aside>

    {{-- ===================================================================== --}}
    {{-- MAIN WORKSPACE AREA                                                   --}}
    {{-- ===================================================================== --}}
    <div class="flex min-h-screen min-w-0 flex-1 flex-col">
        {{-- Top Navbar (Persis Mockup) --}}
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                {{-- Mobile Menu Toggle --}}
                <button type="button" id="public-sidebar-open"
                        class="lg:hidden inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50"
                        aria-label="Buka menu navigasi">
                    <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                {{-- Global Search Input (Ctrl+K, Real-Time & Server ?q=) --}}
                <form action="{{ route('public.katalog') }}" method="GET" id="public-top-search-form" class="relative w-full max-w-md">
                    <svg width="16" height="16"
                         class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input type="text"
                           id="public-global-search"
                           name="q"
                           value="{{ $currentSearch }}"
                           autocomplete="off"
                           placeholder="Cari produk inovasi, mesin, atau fasilitas lab..."
                           class="h-9 w-full rounded-lg border border-slate-200 bg-[#F8FAFC] pr-16 pl-9 text-xs font-medium text-[#0F172A] outline-none transition placeholder:text-slate-400 focus:border-[#00877A] focus:bg-white focus:ring-2 focus:ring-[#00877A]/15">

                    <button type="button"
                            id="public-search-clear"
                            aria-label="Hapus pencarian"
                            class="{{ $currentSearch !== '' ? '' : 'hidden' }} absolute top-1/2 right-2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-300">
                        &times;
                    </button>

                    <kbd id="public-search-kbd"
                         class="{{ $currentSearch !== '' ? 'hidden' : '' }} pointer-events-none absolute top-1/2 right-2 -translate-y-1/2 rounded border border-slate-200 bg-white px-1.5 py-0.5 text-[10px] font-semibold text-slate-400">
                        Ctrl+K
                    </kbd>
                </form>
            </div>

            {{-- Right Utilities (Help + Masuk/Daftar atau Buka Dashboard) --}}
            <div class="flex items-center gap-3 shrink-0">
                {{-- Help Button --}}
                <div class="relative">
                    <button type="button"
                            id="public-help-btn"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-[#00877A] transition-colors"
                            aria-label="Bantuan Katalog PNJ Prime">
                        <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </button>
                    <div id="public-help-popover" class="hidden absolute right-0 mt-2 w-72 rounded-xl border border-slate-200 bg-white p-4 shadow-lg z-40">
                        <p class="text-xs font-bold text-[#0F172A]">Pusat Layanan UPA-RTPU PNJ</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                            Katalog publik menampilkan produk hasil riset terapan dan layanan laboratorium terverifikasi BLU Politeknik Negeri Jakarta dengan skema transaksi <strong>100% Lunas</strong>.
                        </p>
                        <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-2.5 text-[11px]">
                            <span class="text-slate-400">rtpu@pnj.ac.id</span>
                            <a href="{{ route('login') }}" class="font-semibold text-[#00877A] hover:underline">Portal Masuk &rarr;</a>
                        </div>
                    </div>
                </div>

                <span class="hidden sm:inline-block h-5 w-px bg-slate-200" aria-hidden="true"></span>

                @auth
                    {{-- Jika User Sudah Login: Nama User + Tombol Buka Dashboard + Tombol Keluar Cepat --}}
                    <div class="hidden md:block text-right leading-tight">
                        <p class="text-xs font-bold text-[#0F172A] max-w-[160px] truncate">{{ $authUser->nama_lengkap }}</p>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-[#00877A]">{{ strtoupper($authUser->role) }}</p>
                    </div>
                    <a href="{{ $resolvedDashboardUrl }}"
                       class="inline-flex h-9 items-center gap-1.5 rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 text-xs font-semibold text-white transition-colors">
                        <svg width="14" height="14" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                        </svg>
                        <span>Buka Dashboard</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex h-9 items-center gap-1.5 rounded-lg border border-rose-200 bg-white px-3.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                            <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    {{-- Guest: 2 Tombol Kanan Tegas Masuk / Login & Daftar --}}
                    <a href="{{ route('login') }}"
                       class="inline-flex h-9 items-center justify-center rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 text-xs font-semibold text-white transition-colors">
                        Masuk / Login
                    </a>
                    <span class="hidden sm:inline-block h-5 w-px bg-slate-200" aria-hidden="true"></span>
                    <a href="{{ route('register.mitra') }}"
                       class="inline-flex h-9 items-center justify-center rounded-lg bg-[#00877A] hover:bg-[#006E63] px-5 text-xs font-semibold text-white transition-colors">
                        Daftar
                    </a>
                @endauth
            </div>
        </header>

        {{-- Page Content --}}
        <main class="mx-auto w-full max-w-[1280px] flex-1 px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL GUEST GATE (AJAKAN LOGIN / DAFTAR SAAT MEMESAN ATAU KLIK MENU)      --}}
{{-- ========================================================================= --}}
<div id="public-guest-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-[#0F172A]/55 p-4 backdrop-blur-[2px]"
     role="dialog"
     aria-modal="true"
     aria-labelledby="guest-gate-title">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-xl">
        <div class="h-1 w-full bg-[#00877A]"></div>

        <div class="p-6">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E6F4F2] border border-[#00877A]/20 text-[#00877A]">
                        <svg width="20" height="20" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <div>
                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-[#00877A]">Akses Terverifikasi PNJ Prime</span>
                        <h3 id="guest-gate-title" class="text-base font-bold text-[#0F172A] leading-snug">
                            Masuk atau Daftar untuk Melanjutkan Pemesanan
                        </h3>
                    </div>
                </div>
                <button type="button" id="guest-gate-close"
                        class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Tutup modal">
                    <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div id="guest-gate-product-box" class="hidden mt-4 rounded-xl border border-slate-200 bg-[#F8FAFC] px-3.5 py-2.5">
                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Produk / Layanan Dipilih</p>
                <p id="guest-gate-product-name" class="mt-0.5 text-xs font-bold text-[#0F172A]"></p>
            </div>

            <p id="guest-gate-desc" class="mt-3.5 text-xs leading-relaxed text-slate-600">
                Untuk mengajukan pesanan, reservasi fasilitas laboratorium, atau menerbitkan penawaran &amp; invoice resmi BLU PNJ (skema 100% Lunas), silakan masuk menggunakan akun PNJ Prime Anda atau daftarkan akun Mitra Industri baru.
            </p>

            <div class="mt-5 space-y-2.5">
                <a href="{{ route('login') }}"
                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#00877A] hover:bg-[#006E63] px-4 py-2.5 text-xs font-bold text-white transition-colors">
                    <svg width="15" height="15" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span>Masuk ke Akun</span>
                </a>

                <a href="{{ route('register.mitra') }}"
                   class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#0F172A] bg-[#0F172A] hover:bg-slate-800 px-4 py-2.5 text-xs font-bold text-white transition-colors">
                    <svg width="15" height="15" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span>Daftar Akun Mitra Baru</span>
                </a>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                <span>Sivitas Akademika PNJ?</span>
                <a href="{{ route('login.sso') }}" class="font-semibold text-[#00877A] hover:text-[#006E63]">
                    Masuk via SSO PNJ &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        // Mobile Sidebar Drawer
        const sidebar = document.getElementById('public-sidebar');
        const backdrop = document.getElementById('public-sidebar-backdrop');
        const openSidebarBtn = document.getElementById('public-sidebar-open');
        const closeSidebarBtn = document.getElementById('public-sidebar-close');

        function toggleSidebar(show) {
            if (!sidebar || !backdrop) return;
            sidebar.classList.toggle('-translate-x-full', !show);
            backdrop.classList.toggle('hidden', !show);
        }

        if (openSidebarBtn) openSidebarBtn.addEventListener('click', function () { toggleSidebar(true); });
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', function () { toggleSidebar(false); });
        if (backdrop) backdrop.addEventListener('click', function () { toggleSidebar(false); });

        // Help Popover
        const helpBtn = document.getElementById('public-help-btn');
        const helpPopover = document.getElementById('public-help-popover');
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

        // Guest Gate Modal
        const modal = document.getElementById('public-guest-modal');
        const closeModalBtn = document.getElementById('guest-gate-close');
        const titleEl = document.getElementById('guest-gate-title');
        const descEl = document.getElementById('guest-gate-desc');
        const productBox = document.getElementById('guest-gate-product-box');
        const productNameEl = document.getElementById('guest-gate-product-name');

        function openGuestModal(opts) {
            if (!modal) return;
            const title = (opts && opts.title) || 'Masuk atau Daftar untuk Melanjutkan Pemesanan';
            const desc = (opts && opts.desc) || 'Untuk mengajukan pesanan, reservasi fasilitas laboratorium, atau menerbitkan penawaran & invoice resmi BLU PNJ (skema 100% Lunas), silakan masuk menggunakan akun PNJ Prime Anda atau daftarkan akun Mitra Industri baru.';
            const productName = (opts && opts.productName) || '';

            if (titleEl) titleEl.textContent = title;
            if (descEl) descEl.textContent = desc;

            if (productBox && productNameEl) {
                if (productName) {
                    productNameEl.textContent = productName;
                    productBox.classList.remove('hidden');
                } else {
                    productBox.classList.add('hidden');
                }
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeGuestModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.pnjOpenGuestGate = openGuestModal;

        document.querySelectorAll('[data-guest-gate]').forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                openGuestModal({
                    title: trigger.getAttribute('data-gate-title'),
                    desc: trigger.getAttribute('data-gate-desc'),
                    productName: trigger.getAttribute('data-gate-product'),
                });
            });
        });

        if (closeModalBtn) closeModalBtn.addEventListener('click', closeGuestModal);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeGuestModal();
            });
        }

        // Top Search (Real-time + Ctrl+K + Server ?q=)
        const topSearch = document.getElementById('public-global-search');
        const searchClear = document.getElementById('public-search-clear');
        const searchKbd = document.getElementById('public-search-kbd');
        const topSearchForm = document.getElementById('public-top-search-form');
        const filterSearch = document.getElementById('public-filter-q');

        function applyPublicSearch(rawValue, syncUrl) {
            const term = (rawValue || '').trim().toLowerCase();

            if (searchClear && searchKbd) {
                searchClear.classList.toggle('hidden', term === '');
                searchKbd.classList.toggle('hidden', term !== '');
            }

            if (filterSearch && filterSearch.value !== rawValue) {
                filterSearch.value = rawValue;
            }
            if (topSearch && topSearch.value !== rawValue) {
                topSearch.value = rawValue;
            }

            const cards = Array.from(document.querySelectorAll('[data-public-product-card]'));
            if (cards.length > 0) {
                let visibleCount = 0;
                cards.forEach(function (card) {
                    const haystack = (card.getAttribute('data-search-text') || '').toLowerCase();
                    const matches = term === '' || haystack.includes(term);
                    card.classList.toggle('hidden', !matches);
                    if (matches) visibleCount++;
                });

                const countEl = document.getElementById('public-result-count');
                if (countEl) countEl.textContent = String(visibleCount);

                const liveEmpty = document.getElementById('public-live-empty');
                if (liveEmpty) liveEmpty.classList.toggle('hidden', !(term !== '' && visibleCount === 0));
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
        }

        window.pnjApplyPublicSearch = applyPublicSearch;

        if (topSearch) {
            topSearch.addEventListener('input', function () {
                applyPublicSearch(topSearch.value, true);
            });
            if (searchClear) {
                searchClear.addEventListener('click', function () {
                    applyPublicSearch('', true);
                    topSearch.focus();
                });
            }
            if (topSearchForm) {
                topSearchForm.addEventListener('submit', function () {
                    // Allow server GET ?q= on Enter to search across paginated pages
                });
            }
        }

        if (filterSearch) {
            filterSearch.addEventListener('input', function () {
                applyPublicSearch(filterSearch.value, true);
            });
        }

        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                if (topSearch) {
                    e.preventDefault();
                    topSearch.focus();
                    topSearch.select();
                }
            }
            if (e.key === 'Escape') {
                closeGuestModal();
                if (helpPopover) helpPopover.classList.add('hidden');
            }
        });
    })();
</script>

@stack('scripts')
</body>
</html>
