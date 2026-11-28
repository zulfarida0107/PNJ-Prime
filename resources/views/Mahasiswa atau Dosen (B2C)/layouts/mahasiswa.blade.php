<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog Utama') — RTPU Student | PNJ Prime</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        pnj: {
                            50: '#F0FDFB',
                            100: '#CCFBF1',
                            200: '#99F6E4',
                            500: '#0D9488',
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
            background-color: #F8FAFC;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
        #b2c-sidebar.is-collapsed {
            width: 4.5rem;
        }
        #b2c-sidebar.is-collapsed .sidebar-label {
            display: none;
        }
        #b2c-sidebar.is-collapsed .sidebar-nav-item {
            justify-content: center;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        @media print {
            aside, header, .no-print {
                display: none !important;
            }
            main {
                padding: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] font-sans text-slate-800 antialiased">
    @php
        $resolvedUser = $portalUser ?? null;
        if (! $resolvedUser) {
            $authUser = auth()->user();
            if ($authUser) {
                $resolvedUser = \App\Models\PortalUser::where('email', $authUser->email)->first();
            }
            if (! $resolvedUser) {
                $resolvedUser = \App\Models\PortalUser::where('email', 'bagas@pnj.ac.id')->first();
            }
        }

        $namaLengkap = $resolvedUser->name ?? 'Bagas Pratama';
        $namaDepan = explode(' ', trim($namaLengkap))[0] ?: 'Mahasiswa';
        $nimNip = $resolvedUser->nim_nip ?? '2107411058';
        $prodiUnit = $resolvedUser->prodi_unit ?? 'Teknik Elektro - Smt 7';
        $roleBadge = ($resolvedUser->role ?? 'mahasiswa') === 'dosen' ? 'Dosen PNJ' : 'Mahasiswa Aktif';
        $idLabel = ($resolvedUser->role ?? 'mahasiswa') === 'dosen' ? 'NIP' : 'NIM';
        $inisial = collect(explode(' ', $namaLengkap))
            ->filter()
            ->take(2)
            ->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))
            ->implode('');

        $resolvedUnreadNotif = $unreadNotifCount ?? 0;
        if (! isset($unreadNotifCount) && $resolvedUser) {
            $aUser = auth()->user() ?? \App\Models\User::where('email', $resolvedUser->email)->first();
            if ($aUser) {
                $resolvedUnreadNotif = \App\Models\Notifikasi::where('id_penerima', $aUser->id_pengguna)->where('is_read', false)->count();
            }
        }

        $menuAktif = trim($__env->yieldContent('nav', 'katalog'));
        $kelasAktif = 'border-l-[3px] border-[#00877A] bg-[#00877A]/10 text-[#00877A] font-semibold';
        $kelasBiasa = 'border-l-[3px] border-transparent text-slate-600 hover:bg-slate-100/80 hover:text-[#00877A]';
    @endphp

    <div class="flex min-h-screen">
        {{-- Mobile Sidebar Overlay --}}
        <div id="b2c-sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-[1px] lg:hidden"></div>

        {{-- Sidebar Kiri (Sticky & Collapsible) --}}
        <aside id="b2c-sidebar"
               class="fixed inset-y-0 left-0 z-50 flex h-screen w-56 -translate-x-full shrink-0 flex-col border-r border-slate-200/80 bg-white transition-all duration-200 lg:sticky lg:top-0 lg:translate-x-0">
            {{-- Brand --}}
            <div class="flex items-center justify-between gap-2 border-b border-slate-100 px-3.5 py-3.5">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="flex min-w-0 items-center gap-2.5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#00877A] text-white shadow-sm">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10 12 5 2 10l10 5 10-5z" />
                            <path d="M6 12v5c3 3 9 3 12 0v-5" />
                        </svg>
                    </span>
                    <div class="sidebar-label min-w-0 leading-tight">
                        <p class="truncate text-sm font-extrabold text-[#006E63]">RTPU Student</p>
                        <p class="truncate text-[10px] font-medium text-slate-400">Portal Akademik &amp; Riset</p>
                    </div>
                </a>
                <button type="button" id="b2c-collapse-btn"
                        class="hidden h-7 w-7 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700 active:scale-95 lg:inline-flex"
                        title="Ciutkan / Perluas Sidebar" aria-label="Ciutkan Sidebar">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M9 3v18" />
                    </svg>
                </button>
            </div>

            {{-- Menu Atas (3 Menu Final B2C) --}}
            <nav class="mt-3 flex-1 space-y-1 px-2.5">
                <a href="{{ route('b2c.katalog') }}"
                   class="sidebar-nav-item flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-all duration-200 {{ $menuAktif === 'katalog' ? $kelasAktif : $kelasBiasa }}"
                   title="Katalog Utama">
                    <svg width="16" height="16" class="shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z" />
                    </svg>
                    <span class="sidebar-label truncate">Katalog Utama</span>
                </a>

                <a href="{{ route('b2c.pesanan') }}"
                   class="sidebar-nav-item flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-all duration-200 {{ $menuAktif === 'pesanan' ? $kelasAktif : $kelasBiasa }}"
                   title="Pesanan Saya">
                    <svg width="16" height="16" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span class="sidebar-label truncate">Pesanan Saya</span>
                </a>

                <a href="{{ route('b2c.pembayaran') }}"
                   class="sidebar-nav-item flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-all duration-200 {{ $menuAktif === 'pembayaran' ? $kelasAktif : $kelasBiasa }}"
                   title="Pembayaran">
                    <svg width="16" height="16" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2.5" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                    <span class="sidebar-label truncate">Pembayaran</span>
                </a>
            </nav>

            {{-- Menu Bawah (2 Menu Final B2C: Pengaturan & Keluar) --}}
            <div class="space-y-1 border-t border-slate-100 px-2.5 py-3">
                <a href="{{ route('b2c.pengaturan') }}"
                   class="sidebar-nav-item flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-all duration-200 {{ $menuAktif === 'pengaturan' ? $kelasAktif : $kelasBiasa }}"
                   title="Pengaturan">
                    <svg width="16" height="16" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                    <span class="sidebar-label truncate">Pengaturan</span>
                </a>

                <button type="button" id="b2c-logout-trigger"
                        class="sidebar-nav-item flex w-full items-center gap-2.5 rounded-lg border-l-[3px] border-transparent px-3 py-2 text-left text-[13px] font-medium text-rose-600 transition-all duration-200 hover:bg-rose-50 hover:text-rose-700 active:scale-95"
                        title="Keluar">
                    <svg width="16" height="16" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <path d="m16 17 5-5-5-5" />
                        <path d="M21 12H9" />
                    </svg>
                    <span class="sidebar-label truncate">Keluar</span>
                </button>
            </div>
        </aside>

        {{-- Area Utama --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col bg-[#F8FAFC]">
            {{-- Top Navbar Compact + Live Search --}}
            <header class="sticky top-0 z-30 flex items-center justify-between gap-3 border-b border-slate-200/80 bg-white/95 px-4 py-2.5 backdrop-blur lg:px-6">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" id="b2c-mobile-menu-btn"
                            class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50 active:scale-95 lg:hidden"
                            aria-label="Buka menu navigasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h1 class="truncate text-base font-bold tracking-tight text-slate-900">Halo, {{ $namaDepan }}</h1>
                            <span class="hidden shrink-0 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2 py-0.5 text-[10px] font-semibold text-[#00877A] sm:inline-flex">{{ $roleBadge }}</span>
                        </div>
                        <p class="truncate text-[11px] text-slate-500">{{ $prodiUnit }} &bull; Politeknik Negeri Jakarta</p>
                    </div>
                </div>

                {{-- Live Search Top Navbar Fungsional --}}
                <div class="relative hidden max-w-sm flex-1 md:block" id="b2c-live-search-wrap">
                    <form action="{{ route('b2c.katalog') }}" method="GET" class="relative">
                        <svg width="15" height="15" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        <input type="search"
                               name="q"
                               id="b2c-live-search-input"
                               value="{{ request('q') }}"
                               autocomplete="off"
                               placeholder="Cari produk riset, kode, atau fasilitas lab..."
                               class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50/80 pr-3 pl-9 text-xs text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-[#00877A] focus:bg-white focus:ring-2 focus:ring-[#00877A]/15">
                    </form>
                    <div id="b2c-live-search-dropdown"
                         class="absolute right-0 left-0 z-50 mt-1.5 hidden overflow-hidden rounded-lg border border-slate-200/80 bg-white shadow-xl">
                        <div id="b2c-live-search-results" class="divide-y divide-slate-100"></div>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-3">
                    {{-- Tombol Notifikasi --}}
                    <a href="{{ route('b2c.notifikasi') }}"
                       class="relative flex h-8 w-8 items-center justify-center rounded-lg border border-transparent text-slate-500 transition-all duration-200 hover:border-slate-200 hover:bg-slate-50 hover:text-[#00877A] active:scale-95 {{ $menuAktif === 'notifikasi' ? 'bg-[#00877A]/10 text-[#00877A]' : '' }}"
                       aria-label="Pusat Notifikasi" title="Pusat Notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                        </svg>
                        @if ($resolvedUnreadNotif > 0)
                            <span class="absolute top-1 right-1 flex h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        @endif
                    </a>

                    {{-- Profil Ringkas --}}
                    <a href="{{ route('b2c.pengaturan') }}"
                       class="flex items-center gap-2.5 border-l border-slate-200 pl-3 transition-opacity hover:opacity-85">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#00877A]/15 text-xs font-bold text-[#00877A] ring-2 ring-[#00877A]/15">
                            {{ $inisial }}
                        </span>
                        <div class="hidden leading-tight sm:block">
                            <p class="text-xs font-semibold text-slate-800">{{ $namaLengkap }}</p>
                            <p class="tabular-nums text-[11px] text-slate-500">{{ $idLabel }} {{ $nimNip }}</p>
                        </div>
                    </a>
                </div>
            </header>

            {{-- Flash Message --}}
            @if (session('success') || session('error') || $errors->any())
                <div class="no-print px-4 pt-3 lg:px-6">
                    @if (session('success'))
                        <div class="flex items-center justify-between gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-xs font-medium text-emerald-800">
                            <div class="flex items-center gap-2">
                                <svg width="16" height="16" class="shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="flex items-center justify-between gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs font-medium text-rose-800">
                            <span>{{ session('error') }}</span>
                            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800">&times;</button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs text-rose-800">
                            <p class="font-semibold">Mohon periksa kembali isian formulir:</p>
                            <ul class="mt-1 list-disc space-y-0.5 pl-4">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Konten Utama Padat --}}
            <main class="flex-1 p-4 lg:p-5">
                @yield('content')
            </main>

            {{-- Footer Compact --}}
            <footer class="no-print border-t border-slate-200/80 bg-white/70 px-4 py-3 text-[11px] text-slate-500 lg:px-6">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p>&copy; 2026 UPA-RTPU Politeknik Negeri Jakarta &bull; Unit Layanan Riset Terapan &amp; Pengabdian Mahasiswa</p>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center gap-1.5 font-medium text-[#00877A]">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="10" rx="2" />
                                <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                            </svg>
                            Verifikasi SSO PNJ Aktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 font-medium text-[#00877A]">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            Pelunasan BLU 100%
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Modal 12: Konfirmasi Keluar --}}
    <div id="b2c-logout-modal"
         class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-[2px]"
         role="dialog" aria-modal="true" aria-labelledby="b2c-logout-title">
        <div class="w-full max-w-sm rounded-xl border border-slate-200/80 bg-white p-5 shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <path d="m16 17 5-5-5-5" />
                        <path d="M21 12H9" />
                    </svg>
                </div>
                <button type="button" data-close-logout
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Tutup modal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <h2 id="b2c-logout-title" class="mt-3.5 text-base font-bold text-slate-900">Keluar dari Portal RTPU Student?</h2>
            <p class="mt-1 text-xs leading-relaxed text-slate-500">
                Sesi login SSO PNJ atas nama <span class="font-semibold text-slate-700">{{ $namaLengkap }}</span> akan diakhiri. Anda perlu masuk kembali untuk mengakses pesanan dan QR Pass laboratorium.
            </p>

            <div class="mt-5 flex items-center justify-end gap-2.5">
                <button type="button" data-close-logout
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition-all hover:bg-slate-50 active:scale-95">
                    Batal
                </button>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-rose-700 active:scale-95">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="m16 17 5-5-5-5" />
                            <path d="M21 12H9" />
                        </svg>
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            // 1. Sidebar Collapse (Desktop) & Drawer (Mobile)
            const sidebar = document.getElementById('b2c-sidebar');
            const overlay = document.getElementById('b2c-sidebar-overlay');
            const mobileBtn = document.getElementById('b2c-mobile-menu-btn');
            const collapseBtn = document.getElementById('b2c-collapse-btn');

            if (localStorage.getItem('pnj_b2c_sidebar_collapsed') === '1' && window.innerWidth >= 1024) {
                sidebar?.classList.add('is-collapsed');
            }

            collapseBtn?.addEventListener('click', () => {
                sidebar?.classList.toggle('is-collapsed');
                localStorage.setItem('pnj_b2c_sidebar_collapsed', sidebar?.classList.contains('is-collapsed') ? '1' : '0');
            });

            const closeMobile = () => {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
            };

            mobileBtn?.addEventListener('click', () => {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
            });

            overlay?.addEventListener('click', closeMobile);

            // 2. Modal Konfirmasi Keluar (12)
            const logoutModal = document.getElementById('b2c-logout-modal');
            const logoutTrigger = document.getElementById('b2c-logout-trigger');
            const openLogout = () => {
                logoutModal?.classList.remove('hidden');
                logoutModal?.classList.add('flex');
            };
            const closeLogout = () => {
                logoutModal?.classList.add('hidden');
                logoutModal?.classList.remove('flex');
            };

            logoutTrigger?.addEventListener('click', openLogout);
            document.querySelectorAll('[data-close-logout]').forEach((el) => el.addEventListener('click', closeLogout));
            logoutModal?.addEventListener('click', (e) => {
                if (e.target === logoutModal) closeLogout();
            });

            // 3. Live Search Top Navbar
            const searchInput = document.getElementById('b2c-live-search-input');
            const searchDropdown = document.getElementById('b2c-live-search-dropdown');
            const searchResults = document.getElementById('b2c-live-search-results');
            const searchWrap = document.getElementById('b2c-live-search-wrap');
            let searchTimer = null;

            const typeLabel = (type) => {
                if (type === 'lab_reservation') return 'Fasilitas Lab';
                if (type === 'pre_order') return 'Pre-Order';
                return 'Ready Stock';
            };

            searchInput?.addEventListener('input', () => {
                clearTimeout(searchTimer);
                const q = searchInput.value.trim();
                if (q.length < 2) {
                    searchDropdown?.classList.add('hidden');
                    return;
                }
                searchTimer = setTimeout(async () => {
                    try {
                        const res = await fetch(`{{ route('b2c.katalog.search') }}?q=${encodeURIComponent(q)}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        if (!res.ok) return;
                        const data = await res.json();
                        const items = data.items || [];
                        if (items.length === 0) {
                            searchResults.innerHTML = `<div class="px-3.5 py-3 text-xs text-slate-500">Tidak ditemukan produk untuk "<strong>${q.replace(/</g, '&lt;')}</strong>".</div>`;
                        } else {
                            searchResults.innerHTML = items.map((item) => `
                                <a href="${item.url}" class="flex items-center justify-between gap-3 px-3.5 py-2.5 transition-colors hover:bg-slate-50">
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-semibold text-slate-800">${item.name}</p>
                                        <p class="truncate text-[11px] text-slate-500">${item.unit} &bull; ${typeLabel(item.order_type)}</p>
                                    </div>
                                    <span class="shrink-0 tabular-nums text-xs font-bold text-[#00877A]">${item.price_formatted}</span>
                                </a>
                            `).join('');
                        }
                        searchDropdown?.classList.remove('hidden');
                    } catch (_) {}
                }, 220);
            });

            document.addEventListener('click', (e) => {
                if (searchWrap && !searchWrap.contains(e.target)) {
                    searchDropdown?.classList.add('hidden');
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
