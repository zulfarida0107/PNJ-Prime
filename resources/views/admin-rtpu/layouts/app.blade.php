<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Utama RTPU') — PNJ Prime RTPU</title>

    {{-- Restore state sidebar sebelum first paint agar bebas flicker --}}
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
                        pnjteal: {
                            50: '#F0FDFA',
                            100: '#CCFBF1',
                            200: '#99F6E4',
                            500: '#00A88F',
                            600: '#00877A',
                            700: '#006E63',
                            800: '#00564D',
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

        /* Dark Collapsible Sidebar Transitions */
        #rtpuSidebar {
            width: 16rem; /* w-64 */
            transition: width 220ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        html.sidebar-collapsed #rtpuSidebar {
            width: 72px;
        }
        html.sidebar-collapsed #rtpuSidebar .sidebar-label,
        html.sidebar-collapsed #rtpuSidebar .sidebar-badge-full,
        html.sidebar-collapsed #rtpuSidebar .sidebar-submenu,
        html.sidebar-collapsed #rtpuSidebar .sidebar-chevron {
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
        #rtpuSidebar ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
    @stack('styles')
</head>
<body class="antialiased text-slate-800 bg-[#F8FAFC]">
    <div class="min-h-screen flex">
        {{-- Sticky Dark Collapsible Sidebar (#0F172A) Persis Mockup --}}
        <aside id="rtpuSidebar" class="sticky top-0 h-screen shrink-0 bg-[#0F172A] text-slate-300 border-r border-slate-800/90 flex flex-col justify-between z-30 select-none">
            <div class="flex flex-col min-h-0 flex-1">
                {{-- Header Brand + Toggle Collapsible --}}
                <div class="sidebar-header-inner h-[64px] px-4 flex items-center justify-between border-b border-slate-800/80 shrink-0">
                    <a href="{{ route('admin.rtpu.dashboard') }}" class="sidebar-brand-link flex items-center gap-3 group min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-[#00A88F] text-white flex items-center justify-center shrink-0 shadow-sm transition-transform duration-200 group-hover:scale-105">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        </div>
                        <div class="sidebar-label leading-tight truncate">
                            <span class="block text-[14.5px] font-extrabold tracking-tight text-white">PNJ Prime RTPU</span>
                            <span class="block text-[10.5px] text-slate-400 font-medium truncate mt-0.5">Admin Operations Portal</span>
                        </div>
                    </a>

                    <button type="button" id="sidebarToggleBtn" onclick="toggleSidebar()"
                            title="Buka / Tutup Sidebar"
                            aria-label="Buka atau tutup sidebar"
                            class="w-7 h-7 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center shrink-0 transition-all active:scale-95">
                        <svg class="sidebar-toggle-icon w-3.5 h-3.5 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                </div>

                {{-- Susunan 9 Menu Utama Persis Mockup --}}
                @php
                    $isDashboard = request()->routeIs('admin.rtpu.dashboard', 'admin.rtpu.index');
                    $isAntrean = request()->routeIs('admin.rtpu.antrean');
                    $isValidasiB2c = request()->routeIs('admin.rtpu.validasi-b2c', 'admin.rtpu.validasi-b2c.index');
                    $isValidasiB2b = request()->routeIs('admin.rtpu.validasi-b2b', 'admin.rtpu.validasi-b2b.index');
                    $isValidasiGroup = $isValidasiB2c || $isValidasiB2b;
                    $isDashboardOrAntrean = $isDashboard || $isAntrean;
                @endphp

                <nav class="px-3 pt-4 pb-3 space-y-1 text-[13px] overflow-y-auto flex-1">
                    {{-- 1. Dashboard --}}
                    <a href="{{ route('admin.rtpu.dashboard') }}"
                       class="sidebar-nav-item has-tooltip relative flex items-center justify-between px-3 py-2.5 rounded-lg font-semibold transition-all duration-150 active:scale-[0.98] {{ $isDashboardOrAntrean ? 'bg-[#00A88F] text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 {{ $isDashboardOrAntrean ? 'text-white' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                            <span class="sidebar-label truncate">Dashboard</span>
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Dashboard
                        </span>
                    </a>

                    {{-- 2. Validasi Pesanan (Accordion B2C & B2B) --}}
                    <div>
                        <button type="button" id="validasiAccordionBtn" onclick="toggleValidasiMenu()"
                                class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition-all duration-150 {{ $isValidasiGroup ? 'text-white bg-slate-800/60' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                            <span class="flex items-center gap-3 min-w-0">
                                <svg class="w-4 h-4 shrink-0 {{ $isValidasiGroup ? 'text-[#00A88F]' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <span class="sidebar-label truncate">Validasi Pesanan</span>
                            </span>
                            <span class="sidebar-badge-full flex items-center gap-1.5">
                                @if (! $isValidasiGroup && ($pendingOrdersCount ?? 0) > 0)
                                    <span class="tabular-nums min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10.5px] font-bold inline-flex items-center justify-center">
                                        {{ $pendingOrdersCount }}
                                    </span>
                                @endif
                                <svg id="validasiChevron" class="sidebar-chevron w-3.5 h-3.5 text-slate-400 transition-transform duration-200 {{ $isValidasiGroup ? 'rotate-180' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                            <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                                Validasi Pesanan ({{ $pendingOrdersCount ?? 0 }})
                            </span>
                        </button>

                        {{-- Submenu Accordion: Mahasiswa (B2C) & Mitra Industri (B2B) --}}
                        <div id="validasiSubmenu" class="sidebar-submenu mt-1 pl-2 space-y-1 {{ $isValidasiGroup ? '' : 'hidden' }}">
                            <a href="{{ route('admin.rtpu.validasi-b2c.index') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-[12.5px] font-semibold transition-all active:scale-[0.98] {{ $isValidasiB2c ? 'bg-[#00A88F] text-white shadow-sm' : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-100' }}">
                                <span class="flex items-center gap-2.5 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isValidasiB2c ? 'bg-white' : 'bg-slate-500' }}"></span>
                                    <span class="truncate">Mahasiswa (B2C)</span>
                                </span>
                                @if ($isValidasiB2c || ($pendingB2cCount ?? 0) > 0)
                                    <span class="tabular-nums min-w-[20px] h-5 px-1.5 rounded-full text-[10.5px] font-bold inline-flex items-center justify-center {{ $isValidasiB2c ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-300' }}">
                                        {{ $pendingB2cCount ?? 0 }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('admin.rtpu.validasi-b2b.index') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-[12.5px] font-semibold transition-all active:scale-[0.98] {{ $isValidasiB2b ? 'bg-[#00A88F] text-white shadow-sm' : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-100' }}">
                                <span class="flex items-center gap-2.5 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isValidasiB2b ? 'bg-white' : 'bg-slate-500' }}"></span>
                                    <span class="truncate">Mitra Industri (B2B)</span>
                                </span>
                                @if ($isValidasiB2b || ($pendingB2bCount ?? 0) > 0)
                                    <span class="tabular-nums min-w-[20px] h-5 px-1.5 rounded-full text-[10.5px] font-bold inline-flex items-center justify-center {{ $isValidasiB2b ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-300' }}">
                                        {{ $pendingB2bCount ?? 0 }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>

                    {{-- 3. Kurasi Produk (Batch 2) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Kurasi Produk', 'Modul kurasi teknis usulan produk unggulan dari Admin P3M beserta penetapan rekomendasi tarif SK dijadwalkan pada rilis Batch 2. Saat ini terdapat {{ $pendingCurationCount ?? 0 }} produk menunggu kurasi RTPU.')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                            <span class="sidebar-label truncate">Kurasi Produk</span>
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Kurasi Produk (Batch 2)
                        </span>
                    </button>

                    {{-- 4. Manajemen Katalog (Batch 2) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Manajemen Katalog', 'Pengelolaan etalase produk aktif, ketersediaan stok, dan penjadwalan slot laboratorium dijadwalkan pada rilis Batch 2.')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span class="sidebar-label truncate">Manajemen Katalog</span>
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Manajemen Katalog (Batch 2)
                        </span>
                    </button>

                    {{-- 5. Laporan Keuangan (Batch 2 + Shortcut Cetak Laporan PDF) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Laporan Keuangan', 'Dashboard rekonsiliasi keuangan BLU PNJ dan distribusi bagi hasil unit dijadwalkan pada Batch 2. Anda dapat mencetak Laporan Operasional (.PDF) saat ini melalui tombol di bawah.', '{{ route('admin.rtpu.print.laporan') }}')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                <line x1="2" y1="10" x2="22" y2="10"></line>
                            </svg>
                            <span class="sidebar-label truncate">Laporan Keuangan</span>
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Laporan Keuangan (Batch 2)
                        </span>
                    </button>

                    {{-- 6. Validasi Mitra (KYC) (Batch 2 + Badge Merah) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Validasi Mitra (KYC)', 'Modul verifikasi legalitas badan usaha Mitra Industri B2B (KTP PIC, NPWP, dan NIB) dijadwalkan pada rilis Batch 2.')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                <path d="M9 12l2 2 4-4"></path>
                            </svg>
                            <span class="sidebar-label truncate">Validasi Mitra (KYC)</span>
                        </span>
                        <span class="sidebar-badge-full tabular-nums min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10.5px] font-bold inline-flex items-center justify-center">
                            {{ max((int) ($pendingKycCount ?? 0), 0) }}
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Validasi Mitra KYC (Batch 2)
                        </span>
                    </button>

                    {{-- 7. Pusat Pesan (Batch 2 + Badge Merah) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Pusat Pesan', 'Fitur percakapan langsung antara Admin RTPU, sivitas, dan PIC Mitra Industri B2B dijadwalkan pada rilis Batch 2.')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span class="sidebar-label truncate">Pusat Pesan</span>
                        </span>
                        @if (($pendingOrdersCount ?? 0) > 0)
                            <span class="sidebar-badge-full tabular-nums min-w-[20px] h-5 px-1.5 rounded-full bg-rose-500 text-white text-[10.5px] font-bold inline-flex items-center justify-center">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Pusat Pesan (Batch 2)
                        </span>
                    </button>

                    {{-- 8. Master Data (Batch 2) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Master Data', 'Pengelolaan Master Data P08 (Unit Pengelola, Kategori Baku, dan Sub-Kategori) dijadwalkan pada rilis Batch 2.')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <path d="M3 9h18M9 21V9"></path>
                            </svg>
                            <span class="sidebar-label truncate">Master Data</span>
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Master Data (Batch 2)
                        </span>
                    </button>

                    {{-- 9. Pengaturan Admin (Batch 2) --}}
                    <button type="button"
                            onclick="openBatch2Modal('Pengaturan Admin', 'Konfigurasi parameter pajak BLU, rekening penerimaan VA Mandiri, serta manajemen hak akses admin dijadwalkan pada rilis Batch 2.')"
                            class="sidebar-nav-item has-tooltip relative w-full flex items-center justify-between px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all active:scale-[0.98]">
                        <span class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span class="sidebar-label truncate">Pengaturan Admin</span>
                        </span>
                        <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                            Pengaturan Admin (Batch 2)
                        </span>
                    </button>
                </nav>
            </div>

            {{-- Footer Sidebar Persis Mockup --}}
            <div class="border-t border-slate-800 p-3 space-y-1 text-[12.5px] shrink-0">
                <button type="button" onclick="openGuideModal()"
                        class="sidebar-footer-btn has-tooltip relative w-full flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800/80 hover:text-white transition-colors active:scale-[0.98]">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span class="sidebar-label truncate">Bantuan &amp; Panduan</span>
                    <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                        Bantuan &amp; Panduan
                    </span>
                </button>

                <button type="button" onclick="openLogoutModal()"
                        class="sidebar-footer-btn has-tooltip relative w-full flex items-center gap-3 px-3 py-2 rounded-lg text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors active:scale-[0.98]">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="sidebar-label truncate font-semibold">Keluar / Logout</span>
                    <span class="sidebar-tooltip absolute left-[calc(100%+12px)] top-1/2 -translate-y-1/2 px-2.5 py-1.5 rounded-md bg-slate-900 border border-slate-700 text-white text-[11px] font-semibold whitespace-nowrap shadow-lg z-50">
                        Keluar / Logout
                    </span>
                </button>
            </div>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="flex-1 min-w-0 flex flex-col">
            {{-- Top Navbar Putih (#FFFFFF, border-b border-slate-200) Persis Mockup --}}
            <header class="sticky top-0 z-20 h-[64px] bg-white border-b border-slate-200 px-6 flex items-center justify-between gap-4">
                {{-- Kiri: Search Bar 100% Fungsional --}}
                <div class="flex items-center gap-3 flex-1 max-w-md">
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
                        @if (request()->filled('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
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

                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input id="globalSearchInput" type="text" name="q" value="{{ request('q') }}" autocomplete="off"
                               placeholder="Cari pesanan, mahasiswa, atau aset lab..."
                               class="w-full pl-10 pr-8 py-2 text-[12.5px] bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00877A]/15 focus:border-[#00877A] focus:bg-white transition-all">
                        <button type="button" id="globalSearchClear" onclick="clearGlobalSearch()"
                                title="Hapus pencarian"
                                class="{{ request()->filled('q') ? '' : 'hidden' }} absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-200/70 flex items-center justify-center text-sm leading-none transition-colors">
                            &times;
                        </button>
                    </form>
                </div>

                {{-- Kanan Navbar: Lonceng Notifikasi & Profil Admin Kepala RTPU --}}
                <div class="flex items-center gap-3.5">
                    <a href="{{ route('admin.rtpu.antrean', ['status' => 'perlu_validasi']) }}"
                       title="Antrean Menunggu Persetujuan"
                       class="relative p-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors active:scale-[0.98]">
                        <svg width="18" height="18" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if (($pendingOrdersCount ?? 0) > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        @endif
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>

                    {{-- Profil Admin Kepala RTPU --}}
                    <div class="relative" id="profileMenuWrapper">
                        <button type="button" onclick="toggleProfileMenu()"
                                class="flex items-center gap-2.5 py-1 px-1.5 rounded-lg hover:bg-slate-50 transition-colors active:scale-[0.98]">
                            @php
                                $displayName = $portalUser->name ?? Auth::user()->nama_lengkap ?? 'Admin Kepala RTPU';
                                $initials = collect(explode(' ', preg_replace('/[^A-Za-z ]/', '', $displayName)))
                                    ->filter()->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('');
                            @endphp
                            <div class="text-right leading-tight hidden sm:block">
                                <p class="text-[12.5px] font-bold text-slate-900">Admin Kepala RTPU</p>
                                <p class="text-[11px] text-slate-500">Superadmin Unit RTPU</p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-slate-800 text-white text-[12px] font-bold flex items-center justify-center ring-2 ring-slate-100">
                                {{ $initials ?: 'RT' }}
                            </div>
                        </button>

                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-slate-200/90 py-1.5 z-40">
                            <div class="px-3.5 py-2.5 border-b border-slate-100">
                                <p class="text-[12.5px] font-bold text-slate-900 truncate">{{ $displayName }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ $portalUser->email ?? Auth::user()->email }}</p>
                            </div>
                            <button type="button" onclick="toggleProfileMenu(); openAccessModal();"
                                    class="w-full text-left px-3.5 py-2 text-[12px] text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <span>Info Akun &amp; Hak Akses</span>
                            </button>
                            <div class="border-t border-slate-100 mt-1 pt-1">
                                <button type="button" onclick="toggleProfileMenu(); openLogoutModal();"
                                        class="w-full text-left px-3.5 py-2 text-[12px] font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    <span>Keluar / Logout</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash & Validation Alerts --}}
            @if (session('success') || session('error') || session('info') || $errors->any())
                <div class="px-6 pt-4 max-w-[1440px] w-full mx-auto space-y-2">
                    @if (session('success'))
                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[12.5px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold px-1">&times;</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[12.5px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold px-1">&times;</button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-[12.5px]">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <span class="font-medium">{{ session('info') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="text-sky-600 hover:text-sky-900 font-bold px-1">&times;</button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="flex items-start justify-between gap-3 px-4 py-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[12.5px]">
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

            {{-- Main Content Full-Width Proporsional 1366x768 --}}
            <main class="flex-1 px-6 py-5 max-w-[1440px] w-full mx-auto">
                @yield('content')
            </main>

            {{-- Footer Halaman Persis Mockup --}}
            <footer class="px-6 py-3.5 border-t border-slate-200/80 bg-[#F8FAFC] text-[11.5px] text-slate-500">
                <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        <span>Sistem Reservasi &amp; API Lab Terhubung Normal</span>
                    </div>
                    <div>
                        &copy; 2026 Unit RTPU Politeknik Negeri Jakarta. Seluruh hak cipta dilindungi.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Semua Modal Admin RTPU (Termasuk Modal 03c Invoice B2B) --}}
    @include('admin-rtpu.partials.modals')

    <script>
        function toggleSidebar() {
            const root = document.documentElement;
            const isCollapsed = root.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem('pnj_rtpu_sidebar_collapsed', isCollapsed ? '1' : '0');
            } catch (e) {}
        }

        function toggleValidasiMenu() {
            // Jika sidebar sedang collapsed, langsung arahkan ke Validasi B2C
            if (document.documentElement.classList.contains('sidebar-collapsed')) {
                window.location.href = "{{ route('admin.rtpu.validasi-b2c.index') }}";
                return;
            }
            const sub = document.getElementById('validasiSubmenu');
            const chev = document.getElementById('validasiChevron');
            if (sub) sub.classList.toggle('hidden');
            if (chev) chev.classList.toggle('rotate-180');
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
           LIVE SEARCH TOP NAVBAR & LOCAL INPUT (Real-Time + Server Query Sync)
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
            const localInput = document.getElementById('localSearchInput');
            if (input) input.value = '';
            if (localInput) localInput.value = '';
            applyLiveSearch('');

            const url = new URL(window.location.href);
            if (url.searchParams.has('q') && url.searchParams.get('q') !== '') {
                url.searchParams.delete('q');
                window.location.href = url.toString();
            } else if (input) {
                input.focus();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('globalSearchInput');
            const localInput = document.getElementById('localSearchInput');

            if (input) {
                input.addEventListener('input', function () {
                    if (localInput) localInput.value = this.value;
                    applyLiveSearch(this.value);
                });
                if (input.value.trim().length > 0) {
                    applyLiveSearch(input.value);
                }
            }

            if (localInput) {
                localInput.addEventListener('input', function () {
                    if (input) input.value = this.value;
                    applyLiveSearch(this.value);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
