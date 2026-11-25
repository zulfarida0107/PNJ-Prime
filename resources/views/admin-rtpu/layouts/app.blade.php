<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin RTPU') — PNJ Prime</title>

    {{-- Restore state sidebar sebelum first paint agar nol flicker --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('pnj_rtpu_sidebar_collapsed') === '1') {
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
                        navybrand: {
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            200: '#BFDBFE',
                            600: '#1E3A8A',
                            700: '#003366',
                            800: '#00264D',
                            900: '#001A33',
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
        #rtpuSidebar {
            width: 16rem; /* w-64 */
            transition: width 220ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        html.sidebar-collapsed #rtpuSidebar {
            width: 72px;
        }
        html.sidebar-collapsed #rtpuSidebar .sidebar-label,
        html.sidebar-collapsed #rtpuSidebar .sidebar-badge-full,
        html.sidebar-collapsed #rtpuSidebar .sidebar-section-title,
        html.sidebar-collapsed #rtpuSidebar .sidebar-footer-meta {
            display: none !important;
        }
        html.sidebar-collapsed #rtpuSidebar .sidebar-header-inner {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        html.sidebar-collapsed #rtpuSidebar .sidebar-brand-link {
            display: none;
        }
        html.sidebar-collapsed #rtpuSidebar .sidebar-nav-item,
        html.sidebar-collapsed #rtpuSidebar .sidebar-footer-btn {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        html.sidebar-collapsed #rtpuSidebar .sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        /* Tooltip saat sidebar collapsed */
        .sidebar-tooltip {
            pointer-events: none;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity 140ms ease, transform 140ms ease;
        }
        html.sidebar-collapsed #rtpuSidebar .has-tooltip:hover .sidebar-tooltip {
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
        {{-- Sticky Collapsible Sidebar Khusus Admin RTPU (Seragam Enterprise dengan Admin P3M) --}}
        <aside id="rtpuSidebar" class="sticky top-0 h-screen shrink-0 bg-white border-r border-slate-200/80 flex flex-col justify-between z-30 select-none">
            <div class="flex flex-col min-h-0">
                {{-- Header Brand + Toggle Collapsible --}}
                <div class="sidebar-header-inner h-14 px-4 flex items-center justify-between border-b border-slate-100 shrink-0">
                    <a href="{{ route('admin.rtpu.dashboard') }}" class="sidebar-brand-link flex items-center gap-2.5 group min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-[#003366] text-white flex items-center justify-center shrink-0 transition-transform duration-200 group-hover:scale-105">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <div class="sidebar-label leading-tight truncate">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[14px] font-extrabold tracking-tight text-slate-900">PNJ Prime</span>
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded bg-[#EFF6FF] text-[#003366] border border-[#BFDBFE]">RTPU</span>
                            </div>
                            <p class="text-[10.5px] text-slate-500 font-medium truncate">Operasional &amp; Keuangan BLU</p>
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

                {{-- Menu Utama Admin RTPU --}}
                @php
                    $isDashboard = request()->routeIs('admin.rtpu.dashboard', 'admin.rtpu.index');
                    $isAntrean = request()->routeIs('admin.rtpu.antrean');
                    $isValidasiB2c = request()->routeIs('admin.rtpu.validasi-b2c', 'admin.rtpu.validasi-b2c.index');
                    $isValidasiB2b = request()->routeIs('admin.rtpu.validasi-b2b', 'admin.rtpu.validasi-b2b.index');
                @endphp

                <nav class="px-2.5 pt-3 pb-2 space-y-1 text-[13px] overflow-y-auto">
                    <p class="sidebar-section-title px-3 pt-1 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Operasional Pesanan
                    </p>

                    {{-- 1. Dashboard --}}
                    <a href="{{ route('admin.rtpu.dashboard') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isDashboard ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isDashboard ? 'text-[#003366]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                            <span class="sidebar-label truncate">Dashboard</span>
                        </span>
                        @if ($isDashboard)
                            <span class="sidebar-badge-full w-1.5 h-1.5 rounded-full bg-[#003366]"></span>
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#003366]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Dashboard Eksekutif
                        </span>
                    </a>

                    {{-- 2. Daftar Antrean Pesanan --}}
                    <a href="{{ route('admin.rtpu.antrean') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isAntrean ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isAntrean ? 'text-[#003366]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <span class="sidebar-label truncate">Antrean Pesanan</span>
                        </span>
                        @if (($pendingOrdersCount ?? 0) > 0)
                            <span class="sidebar-badge-full tabular-nums px-1.5 py-0.5 text-[10px] font-bold rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                        @if ($isAntrean)
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#003366]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Antrean Pesanan ({{ $pendingOrdersCount ?? 0 }})
                        </span>
                    </a>

                    {{-- 3. Validasi Pesanan B2C --}}
                    <a href="{{ route('admin.rtpu.validasi-b2c.index') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isValidasiB2c ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isValidasiB2c ? 'text-[#003366]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="sidebar-label truncate">Validasi B2C</span>
                        </span>
                        @if (($pendingB2cCount ?? 0) > 0)
                            <span class="sidebar-badge-full tabular-nums px-1.5 py-0.5 text-[10px] font-bold rounded bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                {{ $pendingB2cCount }}
                            </span>
                        @endif
                        @if ($isValidasiB2c)
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#003366]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Validasi Pesanan B2C ({{ $pendingB2cCount ?? 0 }})
                        </span>
                    </a>

                    {{-- 4. Validasi Pesanan B2B --}}
                    <a href="{{ route('admin.rtpu.validasi-b2b.index') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium transition-all duration-200 active:scale-95 {{ $isValidasiB2b ? 'bg-slate-100/90 text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <span class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isValidasiB2b ? 'text-[#003366]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                            <span class="sidebar-label truncate">Validasi B2B</span>
                        </span>
                        @if (($pendingB2bCount ?? 0) > 0)
                            <span class="sidebar-badge-full tabular-nums px-1.5 py-0.5 text-[10px] font-bold rounded bg-[#EFF6FF] text-[#1E3A8A] ring-1 ring-inset ring-[#1E3A8A]/20">
                                {{ $pendingB2bCount }}
                            </span>
                        @endif
                        @if ($isValidasiB2b)
                            <span class="absolute right-0 top-1.5 bottom-1.5 w-[3px] rounded-l bg-[#003366]"></span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                            Validasi Pesanan B2B ({{ $pendingB2bCount ?? 0 }})
                        </span>
                    </a>

                    <div class="pt-3 mt-2 border-t border-slate-100">
                        <p class="sidebar-section-title px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Tata Kelola &amp; Laporan
                        </p>

                        {{-- Laporan Operasional PDF (Langsung Berfungsi) --}}
                        <a href="{{ route('admin.rtpu.print.laporan') }}" target="_blank" rel="noopener noreferrer"
                           class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 active:scale-95">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                <span class="sidebar-label truncate">Laporan Operasional</span>
                            </span>
                            <span class="sidebar-badge-full text-[9.5px] font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">PDF</span>
                            <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                                Cetak Laporan Operasional (.PDF)
                            </span>
                        </a>

                        {{-- Batch 2 Safe Links (Tidak 404, tampilkan modal info Batch 2) --}}
                        <button type="button"
                                onclick="openBatch2Modal('Kurasi Produk P3M', 'Modul kurasi teknis usulan produk unggulan dari Admin P3M beserta penetapan rekomendasi tarif SK Pimpinan dijadwalkan pada rilis Batch 2. Saat ini terdapat {{ $pendingCurationCount ?? 0 }} produk menunggu kurasi RTPU.')"
                                class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2 rounded-lg font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all duration-200 active:scale-95">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                <span class="sidebar-label truncate">Kurasi Produk P3M</span>
                            </span>
                            <span class="sidebar-badge-full px-1.5 py-0.5 text-[9.5px] font-semibold rounded bg-slate-100 text-slate-500">Segera</span>
                            <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                                Kurasi Produk P3M (Batch 2)
                            </span>
                        </button>

                        <button type="button"
                                onclick="openBatch2Modal('Verifikasi Mitra (KYC)', 'Modul telaah berkas legalitas perusahaan Mitra B2B (NIB, NPWP, dan KTP PIC) dijadwalkan pada pengembangan Batch 2.')"
                                class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2 rounded-lg font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all duration-200 active:scale-95">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    <path d="M9 12l2 2 4-4"></path>
                                </svg>
                                <span class="sidebar-label truncate">Verifikasi Mitra (KYC)</span>
                            </span>
                            <span class="sidebar-badge-full px-1.5 py-0.5 text-[9.5px] font-semibold rounded bg-slate-100 text-slate-500">Segera</span>
                            <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                                Verifikasi Mitra KYC (Batch 2)
                            </span>
                        </button>

                        <button type="button"
                                onclick="openBatch2Modal('Master Data P08', 'Pengelolaan referensi baku Unit Pengelola, 3 Kategori Baku, Sub-Kategori, serta katalog layanan dijadwalkan pada rilis Batch 2.')"
                                class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2 rounded-lg font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-all duration-200 active:scale-95">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                                <span class="sidebar-label truncate">Master Data P08</span>
                            </span>
                            <span class="sidebar-badge-full px-1.5 py-0.5 text-[9.5px] font-semibold rounded bg-slate-100 text-slate-500">Segera</span>
                            <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                                Master Data P08 (Batch 2)
                            </span>
                        </button>
                    </div>
                </nav>
            </div>

            {{-- Footer Sidebar --}}
            <div class="border-t border-slate-100 p-2.5 space-y-1 text-[12px] shrink-0">
                <button type="button" onclick="openGuideModal()"
                        class="sidebar-footer-btn has-tooltip relative w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors active:scale-95">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span class="sidebar-label truncate">Panduan Operasional</span>
                    <span class="sidebar-tooltip absolute left-[calc(100%+10px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 text-white text-[11px] font-semibold whitespace-nowrap shadow-md z-50">
                        Panduan Operasional RTPU
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
                        Info Akun &amp; Hak Akses
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
                    <span class="text-slate-400 font-semibold">Otoritas</span>
                    <span class="text-[#003366] font-extrabold">Admin RTPU</span>
                </div>
            </div>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="flex-1 min-w-0 flex flex-col">
            {{-- Top Navbar Compact --}}
            <header class="sticky top-0 z-20 h-14 bg-white/95 backdrop-blur border-b border-slate-200/80 px-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 max-w-xl">
                    @php
                        $searchAction = request()->routeIs('admin.rtpu.antrean')
                            ? route('admin.rtpu.antrean')
                            : (request()->routeIs('admin.rtpu.validasi-b2c', 'admin.rtpu.validasi-b2c.index')
                                ? route('admin.rtpu.validasi-b2c.index')
                                : (request()->routeIs('admin.rtpu.validasi-b2b', 'admin.rtpu.validasi-b2b.index')
                                    ? route('admin.rtpu.validasi-b2b.index')
                                    : route('admin.rtpu.dashboard')));
                    @endphp
                    <form id="globalSearchForm" method="GET" action="{{ $searchAction }}" class="relative flex-1">
                        @if (request()->filled('tab'))
                            <input type="hidden" name="tab" value="{{ request('tab') }}">
                        @endif
                        @if (request()->filled('segment'))
                            <input type="hidden" name="segment" value="{{ request('segment') }}">
                        @endif
                        @if (request()->filled('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if (request()->filled('unit_id'))
                            <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
                        @endif
                        @if (request()->filled('order_type'))
                            <input type="hidden" name="order_type" value="{{ request('order_type') }}">
                        @endif

                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input id="globalSearchInput" type="text" name="q" value="{{ request('q') }}" autocomplete="off"
                               placeholder="Cari kode pesanan, pemesan/instansi, invoice, atau unit..."
                               class="w-full pl-9 pr-8 py-1.5 text-[12.5px] bg-slate-50 border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366] focus:bg-white transition-all">
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

                {{-- Kanan Navbar: Notifikasi Antrean & Profil --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.rtpu.antrean', ['status' => 'perlu_validasi']) }}"
                       title="Antrean Pesanan Menunggu Validasi"
                       class="relative p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors active:scale-95">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if (($pendingOrdersCount ?? 0) > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-amber-500 ring-2 ring-white"></span>
                        @endif
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    {{-- Profil Admin RTPU --}}
                    <div class="relative" id="profileMenuWrapper">
                        <button type="button" onclick="toggleProfileMenu()"
                                class="flex items-center gap-2.5 py-1 px-1.5 rounded-lg hover:bg-slate-50 transition-colors active:scale-95">
                            @php
                                $displayName = $portalUser->name ?? Auth::user()->nama_lengkap ?? 'Admin RTPU';
                                $displayUnit = $portalUser->prodi_unit ?? 'UPA-RTPU PNJ';
                                $initials = collect(explode(' ', preg_replace('/[^A-Za-z ]/', '', $displayName)))
                                    ->filter()->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('');
                            @endphp
                            <div class="w-8 h-8 rounded-full bg-[#003366] text-white text-[11px] font-bold flex items-center justify-center">
                                {{ $initials ?: 'RT' }}
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

            {{-- Flash / Toast Alerts --}}
            @if (session('success') || session('error') || session('info') || $errors->any())
                <div class="px-5 pt-3 space-y-2">
                    @if (session('success'))
                        <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[12px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold px-1">&times;</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-[12px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold px-1">&times;</button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-sky-50 border border-sky-200 text-sky-800 text-[12px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <span class="font-medium">{{ session('info') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-sky-600 hover:text-sky-900 font-bold px-1">&times;</button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="flex items-start justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-[12px]">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <div>
                                    <p class="font-bold">Periksa kembali input Anda:</p>
                                    <ul class="list-disc list-inside mt-0.5 space-y-0.5">
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold px-1">&times;</button>
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

    {{-- Semua Modal Admin RTPU --}}
    @include('admin-rtpu.partials.modals')

    <script>
        function toggleSidebar() {
            const root = document.documentElement;
            const isCollapsed = root.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem('pnj_rtpu_sidebar_collapsed', isCollapsed ? '1' : '0');
            } catch (e) {}
        }

        function toggleProfileMenu() {
            const el = document.getElementById('profileDropdown');
            if (el) el.classList.toggle('hidden');
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

            if (input.value.trim().length > 0) {
                applyLiveSearch(input.value);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
