<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin P3M') — PNJ Prime</title>

    {{-- Restore state sidebar sebelum first paint agar bebas flicker --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('pnj_p3m_sidebar_collapsed') === '1') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        tealbrand: {
                            50: '#F0FDFA',
                            100: '#CCFBF1',
                            200: '#99F6E4',
                            500: '#0D9488',
                            600: '#0F766E',
                            700: '#115E59',
                            800: '#134E4A',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        /* Sidebar Collapsible Transitions */
        #p3mSidebar {
            width: 16rem; /* w-64 */
            transition: width 220ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        html.sidebar-collapsed #p3mSidebar {
            width: 72px;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-label,
        html.sidebar-collapsed #p3mSidebar .sidebar-badge-full,
        html.sidebar-collapsed #p3mSidebar .sidebar-footer-meta {
            display: none !important;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-header-inner {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-brand-link {
            display: none;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-cta-wrap {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-cta-btn {
            padding-left: 0;
            padding-right: 0;
            justify-content: center;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-nav-item,
        html.sidebar-collapsed #p3mSidebar .sidebar-footer-btn {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        html.sidebar-collapsed #p3mSidebar .sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        /* Tooltip saat sidebar collapsed */
        .sidebar-tooltip {
            pointer-events: none;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity 140ms ease, transform 140ms ease;
        }
        html.sidebar-collapsed #p3mSidebar .has-tooltip:hover .sidebar-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        /* Scrollbar halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-slate-800 bg-[#F8FAFC]">
    <div class="min-h-screen flex">
        {{-- Sticky Collapsible Sidebar Khusus Admin P3M (Tanpa Menu Kurasi/Validasi) --}}
        <aside id="p3mSidebar" class="sticky top-0 h-screen shrink-0 bg-white border-r border-slate-200/80 flex flex-col justify-between z-30 select-none">
            <div>
                {{-- Header Brand + Toggle Collapsible --}}
                <div class="sidebar-header-inner h-14 px-4 flex items-center justify-between border-b border-slate-100">
                    <a href="{{ route('admin.p3m.dashboard') }}" class="sidebar-brand-link flex items-center gap-2.5 group min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-[#0F766E] text-white flex items-center justify-center shrink-0 transition-transform duration-200 group-hover:scale-105">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 3h6"></path>
                                <path d="M10 3v6.5L5.2 18.2A2 2 0 0 0 6.9 21h10.2a2 2 0 0 0 1.7-2.8L14 9.5V3"></path>
                                <path d="M8.5 14h7"></path>
                            </svg>
                        </div>
                        <div class="sidebar-label leading-tight truncate">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[14px] font-extrabold tracking-tight text-slate-900">PNJ Prime</span>
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-slate-100 text-slate-700 border border-slate-200">P3M</span>
                            </div>
                            <p class="text-[10.5px] text-slate-500 font-medium truncate">Portal Hilirisasi &amp; RTPU</p>
                        </div>
                    </a>

                    <button type="button" id="sidebarToggleBtn" onclick="toggleSidebar()"
                            title="Buka / Tutup Sidebar"
                            aria-label="Buka atau tutup sidebar"
                            class="w-7 h-7 rounded-lg border border-slate-200/80 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-800 flex items-center justify-center shrink-0 transition-all active:scale-95">
                        <svg class="sidebar-toggle-icon w-3.5 h-3.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                </div>

                {{-- Tombol CTA Ajukan Produk Unggulan --}}
                <div class="sidebar-cta-wrap px-3.5 pt-4 pb-3">
                    <a href="{{ route('admin.p3m.create') }}"
                       class="sidebar-cta-btn has-tooltip relative w-full inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-lg bg-[#0F766E] hover:bg-[#115E59] text-white text-[12.5px] font-semibold transition-all duration-200 active:scale-95">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span class="sidebar-label whitespace-nowrap">Ajukan Produk Unggulan</span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Ajukan Produk Unggulan
                        </span>
                    </a>
                </div>

                {{-- Menu Utama Admin P3M --}}
                <nav class="px-2.5 space-y-1 text-[13px]">
                    @php
                        $isDashboard = request()->routeIs('admin.p3m.dashboard', 'admin.p3m.index');
                        $isPemantauan = request()->routeIs('admin.p3m.pemantauan', 'admin.p3m.create', 'admin.p3m.revisi');
                        $isRiwayat = request()->routeIs('admin.p3m.riwayat');
                    @endphp

                    <a href="{{ route('admin.p3m.dashboard') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isDashboard ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isDashboard ? 'text-[#0F766E]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                            <span class="sidebar-label truncate">Dashboard</span>
                        </span>
                        @if ($isDashboard)
                            <span class="sidebar-badge-full w-1.5 h-1.5 rounded-full bg-[#0F766E]"></span>
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#0F766E]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Dashboard
                        </span>
                    </a>

                    <a href="{{ route('admin.p3m.pemantauan') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isPemantauan ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isPemantauan ? 'text-[#0F766E]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 22h14"></path>
                                <path d="M5 2h14"></path>
                                <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
                                <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
                            </svg>
                            <span class="sidebar-label truncate">Pemantauan Pengajuan</span>
                        </span>
                        @if (($activeQueueCount ?? 0) > 0)
                            <span class="sidebar-badge-full tabular-nums px-1.5 py-0.5 text-[10px] font-bold rounded bg-[#F0FDFA] text-[#0F766E] border border-[#99F6E4]">
                                {{ $activeQueueCount }}
                            </span>
                        @endif
                        @if ($isPemantauan)
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#0F766E]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Pemantauan Pengajuan ({{ $activeQueueCount ?? 0 }})
                        </span>
                    </a>

                    <a href="{{ route('admin.p3m.riwayat') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isRiwayat ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isRiwayat ? 'text-[#0F766E]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                                <path d="M12 7v5l4 2"></path>
                            </svg>
                            <span class="sidebar-label truncate">Riwayat Pengajuan</span>
                        </span>
                        @if ($isRiwayat)
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#0F766E]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Riwayat Pengajuan
                        </span>
                    </a>
                </nav>
            </div>

            {{-- Footer Sidebar --}}
            <div class="border-t border-slate-100 p-2.5 space-y-1 text-[12px]">
                <button type="button" onclick="openGuideModal()"
                        class="sidebar-footer-btn has-tooltip relative w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors active:scale-95">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span class="sidebar-label truncate">Panduan Sistem</span>
                    <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                        Panduan Sistem
                    </span>
                </button>

                <button type="button" onclick="openAccessModal()"
                        class="sidebar-footer-btn has-tooltip relative w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors active:scale-95">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span class="sidebar-label truncate">Pengaturan Akses</span>
                    <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                        Pengaturan Akses
                    </span>
                </button>

                <button type="button" onclick="openLogoutModal()"
                        class="sidebar-footer-btn has-tooltip relative w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-slate-600 hover:bg-rose-50 hover:text-rose-700 transition-colors active:scale-95">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="sidebar-label truncate font-medium">Keluar</span>
                    <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                        Keluar dari PNJ Prime
                    </span>
                </button>

                <div class="sidebar-footer-meta pt-2 mt-1 border-t border-slate-100 px-2 flex items-center justify-between text-[10px] tracking-wider uppercase">
                    <span class="text-slate-400 font-semibold">Unit Pengaju</span>
                    <span class="text-[#0F766E] font-extrabold">Admin P3M</span>
                </div>
            </div>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="flex-1 min-w-0 flex flex-col">
            {{-- Top Navbar Compact --}}
            <header class="sticky top-0 z-20 h-14 bg-white/95 backdrop-blur border-b border-slate-200/80 px-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 max-w-xl">
                    @php
                        $searchAction = request()->routeIs('admin.p3m.riwayat')
                            ? route('admin.p3m.riwayat')
                            : (request()->routeIs('admin.p3m.pemantauan')
                                ? route('admin.p3m.pemantauan')
                                : route('admin.p3m.dashboard'));
                    @endphp
                    <form id="globalSearchForm" method="GET" action="{{ $searchAction }}" class="relative flex-1">
                        @if (request()->filled('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        @if (request()->filled('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if (request()->filled('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        @if (request()->filled('unit_id'))
                            <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
                        @endif

                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input id="globalSearchInput" type="text" name="q" value="{{ request('q') }}" autocomplete="off"
                               placeholder="Cari nama produk, kode pengajuan, unit, atau kategori..."
                               class="w-full pl-9 pr-8 py-1.5 text-[12.5px] bg-slate-50 border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0F766E]/15 focus:border-[#0F766E] focus:bg-white transition-all">
                        <button type="button" id="globalSearchClear" onclick="clearGlobalSearch()"
                                title="Hapus pencarian"
                                class="{{ request()->filled('q') ? '' : 'hidden' }} absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-200/70 flex items-center justify-center text-sm leading-none transition-colors">
                            &times;
                        </button>
                    </form>

                    <div class="hidden lg:inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-50 border border-slate-200/80 text-[11.5px] text-slate-600 shrink-0 tabular-nums">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>TA</span>
                        <strong class="text-slate-800 font-bold">2026/2027</strong>
                    </div>
                </div>

                {{-- Kanan Navbar: Notifikasi & Profil --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.p3m.pemantauan', ['status' => 'revisi']) }}"
                       title="Notifikasi Revisi RTPU"
                       class="relative p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors active:scale-95">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if (($revisionAlertCount ?? 0) > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        @endif
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    {{-- Profil Admin P3M --}}
                    <div class="relative" id="profileMenuWrapper">
                        <button type="button" onclick="toggleProfileMenu()"
                                class="flex items-center gap-2.5 py-1 px-1.5 rounded-lg hover:bg-slate-50 transition-colors active:scale-95">
                            @php
                                $displayName = $portalUser->name ?? Auth::user()->nama_lengkap ?? 'Admin P3M';
                                $displayUnit = $portalUser->prodi_unit ?? 'Unit P3M PNJ';
                                $initials = collect(explode(' ', preg_replace('/[^A-Za-z ]/', '', $displayName)))
                                    ->filter()->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('');
                            @endphp
                            <div class="w-8 h-8 rounded-full bg-slate-800 text-white text-[11px] font-bold flex items-center justify-center">
                                {{ $initials ?: 'P3' }}
                            </div>
                            <div class="text-left leading-tight hidden sm:block">
                                <p class="text-[12px] font-bold text-slate-900">{{ $displayName }}</p>
                                <p class="text-[10.5px] text-slate-500">{{ $displayUnit }}</p>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>

                        <div id="profileDropdown" class="hidden absolute right-0 mt-1.5 w-56 bg-white rounded-lg shadow-lg border border-slate-200/90 py-1.5 z-40">
                            <div class="px-3.5 py-2 border-b border-slate-100">
                                <p class="text-[12px] font-bold text-slate-900 truncate">{{ $displayName }}</p>
                                <p class="text-[10.5px] text-slate-500 truncate">{{ $portalUser->email ?? Auth::user()->email }}</p>
                            </div>
                            <button type="button" onclick="toggleProfileMenu(); openAccessModal();"
                                    class="w-full text-left px-3.5 py-2 text-[12px] text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <span>Info Akun &amp; Hak Akses</span>
                            </button>
                            <div class="border-t border-slate-100 mt-1 pt-1">
                                <button type="button" onclick="toggleProfileMenu(); openLogoutModal();"
                                        class="w-full text-left px-3.5 py-2 text-[12px] font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span>Keluar dari PNJ Prime</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Alerts --}}
            @if (session('success') || session('error') || session('info'))
                <div class="px-5 pt-3">
                    @if (session('success'))
                        <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[12px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-[12px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800">&times;</button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Main Content --}}
            <main class="flex-1 px-5 py-4 max-w-[1360px] w-full mx-auto">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Semua Modal/Pop-up Admin P3M --}}
    @include('admin-p3m.partials.modals')

    <script>
        function toggleSidebar() {
            const root = document.documentElement;
            const isCollapsed = root.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem('pnj_p3m_sidebar_collapsed', isCollapsed ? '1' : '0');
            } catch (e) {}
        }

        function toggleProfileMenu() {
            const el = document.getElementById('profileDropdown');
            el.classList.toggle('hidden');
        }

        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('profileMenuWrapper');
            const dropdown = document.getElementById('profileDropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        /* =====================================================================
           LIVE SEARCH TOP NAVBAR (Real-Time Filter + Server Query Sync + Reset)
           ===================================================================== */
        function applyLiveSearch(rawQuery) {
            const q = (rawQuery || '').trim();
            const qLower = q.toLowerCase();
            const clearBtn = document.getElementById('globalSearchClear');

            if (clearBtn) {
                clearBtn.classList.toggle('hidden', q.length === 0);
            }

            const items = document.querySelectorAll('[data-searchable="true"]');
            const banner = document.getElementById('liveSearchBanner');
            const bannerCount = document.getElementById('liveSearchCount');
            const bannerQuery = document.getElementById('liveSearchQuery');
            const emptyState = document.getElementById('liveSearchEmptyState');
            const emptyQuery = document.getElementById('liveSearchEmptyQuery');

            if (!items.length) return;

            let visibleCount = 0;
            items.forEach(function (el) {
                const haystack = (el.getAttribute('data-search-text') || '').toLowerCase();
                const isMatch = qLower === '' || haystack.includes(qLower);
                el.classList.toggle('hidden', !isMatch);

                const parentId = el.getAttribute('data-search-id');
                if (parentId) {
                    const childRows = document.querySelectorAll('[data-search-parent-id="' + parentId + '"]');
                    childRows.forEach(function (child) {
                        child.classList.toggle('hidden', !isMatch);
                    });
                }

                if (isMatch) visibleCount++;
            });

            if (banner && bannerCount && bannerQuery) {
                if (q.length > 0) {
                    bannerCount.textContent = visibleCount;
                    bannerQuery.textContent = q;
                    banner.classList.remove('hidden');
                } else {
                    banner.classList.add('hidden');
                }
            }

            if (emptyState) {
                if (q.length > 0 && visibleCount === 0) {
                    if (emptyQuery) emptyQuery.textContent = q;
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }
        }

        function clearGlobalSearch() {
            const input = document.getElementById('globalSearchInput');
            if (!input) return;

            input.value = '';
            applyLiveSearch('');

            // Jika URL saat ini memiliki parameter ?q=..., bersihkan dan reload agar data server-side kembali utuh
            const url = new URL(window.location.href);
            if (url.searchParams.has('q') && url.searchParams.get('q') !== '') {
                url.searchParams.delete('q');
                window.location.href = url.toString();
            } else {
                input.focus();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('globalSearchInput');
            if (!input) return;

            input.addEventListener('input', function () {
                applyLiveSearch(this.value);
            });

            // Jika halaman dimuat dengan ?q=... dari server, tampilkan indikator hasil
            if (input.value.trim().length > 0) {
                applyLiveSearch(input.value);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
