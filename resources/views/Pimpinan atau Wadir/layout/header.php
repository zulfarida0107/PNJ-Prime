<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8F9FF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= function_exists('csrf_token') ? csrf_token() : '' ?>">

    <title><?= $pageTitle ?? '30. Pimpinan - Ringkasan Eksekutif — PNJ Prime BLU' ?></title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            teal: '#00998A',
                            'teal-dark': '#006A63',
                            'teal-deep': '#006947',
                            'teal-forest': '#047857',
                            navy: '#0B1C30',
                            slate: '#3D4947',
                            muted: '#6D7A77',
                            border: '#BCC9C6',
                            red: '#BA1A1A',
                            bg: '#F8F9FF',
                            soft: '#EFF4FF',
                            accent: '#E5EEFF'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(0deg, #F8F9FF, #F8F9FF), #FFFFFF;
            color: #3D4947;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#00998A]/20 selection:text-[#006A63]">

    <!-- MAIN WRAPPER (Auto layout: flex row, 256px sidebar + container) -->
    <div class="relative flex min-h-screen w-full bg-[#F8F9FF]">
        
        <!-- SIDEBAR NAVIGATION (Aside 256px) -->
        <?php include_once __DIR__ . '/sidebar.php'; ?>

        <!-- ========================================================================= -->
        <!-- MAIN CONTENT CONTAINER (Responsive lg:ml-64, w-full, fluid)               -->
        <!-- ========================================================================= -->
        <div class="flex-1 lg:ml-64 flex flex-col min-w-0 w-full">

            <!-- --------------------------------------------------------------------- -->
            <!-- HEADER - TOP NAVIGATION BAR                                           -->
            <!-- --------------------------------------------------------------------- -->
            <header class="sticky top-0 z-40 flex min-h-[72px] lg:h-[84px] w-full items-center gap-3 sm:gap-4 border-b border-[#BCC9C6]/30 bg-white px-4 sm:px-6 lg:px-8 shadow-xs">
                
                <!-- Left: Hamburger (Mobile) + Shield Portal Context + Interactive Fiscal Year Calendar -->
                <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                    
                    <!-- Mobile Hamburger Menu Button -->
                    <button
                        type="button"
                        onclick="toggleSidebar()"
                        class="lg:hidden flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-[#3D4947] hover:bg-slate-100 transition focus:outline-none"
                        aria-label="Buka Navigasi"
                    >
                        <svg width="24" height="24" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="20" y1="12" y2="12"/>
                            <line x1="4" x2="20" y1="6" y2="6"/>
                            <line x1="4" x2="20" y1="18" y2="18"/>
                        </svg>
                    </button>

                    <!-- Shield Icon & Portal Eksekutif Direksi Title -->
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center text-[#00998A]">
                            <svg width="24" height="24" class="w-6 h-6 shrink-0 text-[#00998A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="font-heading text-sm sm:text-[15px] font-bold tracking-tight text-[#0B1C30]">Portal Eksekutif</span>
                            <span class="font-heading text-sm sm:text-[15px] font-bold tracking-tight text-[#0B1C30]">Direksi</span>
                        </div>
                    </div>

                    <!-- Interactive Academic Fiscal Year Indicator Pill + Calendar Popover -->
                    <div class="relative hidden sm:block" id="fiscal-calendar-wrapper">
                        <button
                            type="button"
                            onclick="toggleFiscalCalendar(event)"
                            id="fiscal-calendar-trigger"
                            class="flex items-center gap-2.5 rounded-lg border border-[#BCC9C6]/50 bg-[#EFF4FF] hover:bg-[#E2ECFF] hover:border-[#00998A]/50 px-3.5 py-2 shadow-xs transition cursor-pointer group"
                            title="Klik untuk memilih Tahun Anggaran & Kalender"
                        >
                            <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0 text-[#00998A] group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                <line x1="16" x2="16" y1="2" y2="6"/>
                                <line x1="8" x2="8" y1="2" y2="6"/>
                                <line x1="3" x2="21" y1="10" y2="10"/>
                            </svg>
                            <div class="flex flex-col text-left leading-tight">
                                <span class="text-[11px] font-semibold text-[#00998A]">Tahun Anggaran</span>
                                <span id="fiscal-year-display" class="text-xs font-bold text-[#00998A]">2024/2025 (Genap)</span>
                            </div>
                            <svg width="14" height="14" id="fiscal-chevron" class="w-3.5 h-3.5 text-[#00998A] ml-1 transition-transform duration-200 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>

                        <!-- Popover Kalender & Pilihan Tahun Anggaran -->
                        <div
                            id="fiscal-calendar-popover"
                            class="hidden absolute left-0 mt-2 w-[320px] sm:w-[340px] rounded-xl border border-[#BCC9C6]/50 bg-white shadow-xl z-50 overflow-hidden"
                        >
                            <!-- Popover Header -->
                            <div class="bg-gradient-to-r from-[#00685F] to-[#00998A] px-4 py-3 text-white flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg width="16" height="16" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                        <line x1="16" x2="16" y1="2" y2="6"/>
                                        <line x1="8" x2="8" y1="2" y2="6"/>
                                        <line x1="3" x2="21" y1="10" y2="10"/>
                                    </svg>
                                    <span class="font-heading text-xs font-bold tracking-wide uppercase">Kalender Tahun Anggaran</span>
                                </div>
                                <span class="rounded bg-white/20 px-2 py-0.5 text-[10px] font-semibold">Aktif</span>
                            </div>

                            <div class="p-4 space-y-4">
                                <!-- Quick Select Academic Year & Semester -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-[#6D7A77] uppercase tracking-wider mb-2">
                                        Pilih Periode Semester
                                    </label>
                                    <div class="grid grid-cols-1 gap-1.5 max-h-40 overflow-y-auto pr-1" id="fiscal-period-list">
                                        <button type="button" onclick="selectFiscalYear('2026/2027 (Ganjil)', 2026, 8)" class="fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-[#3D4947] hover:bg-[#EFF4FF] hover:text-[#00998A] transition">
                                            <span>T.A. 2026/2027 (Ganjil)</span>
                                            <span class="text-[10px] text-[#6D7A77]">Sep 2026 - Feb 2027</span>
                                        </button>
                                        <button type="button" onclick="selectFiscalYear('2025/2026 (Genap)', 2025, 2)" class="fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-[#3D4947] hover:bg-[#EFF4FF] hover:text-[#00998A] transition">
                                            <span>T.A. 2025/2026 (Genap)</span>
                                            <span class="text-[10px] text-[#6D7A77]">Mar 2025 - Agu 2025</span>
                                        </button>
                                        <button type="button" onclick="selectFiscalYear('2024/2025 (Genap)', 2025, 1)" class="fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-bold bg-[#EFF4FF] text-[#00998A] border border-[#00998A]/30 transition">
                                            <span>2024/2025 (Genap)</span>
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#00998A]">
                                                <svg width="12" height="12" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                                Aktif
                                            </span>
                                        </button>
                                        <button type="button" onclick="selectFiscalYear('2024/2025 (Ganjil)', 2024, 8)" class="fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-[#3D4947] hover:bg-[#EFF4FF] hover:text-[#00998A] transition">
                                            <span>2024/2025 (Ganjil)</span>
                                            <span class="text-[10px] text-[#6D7A77]">Sep 2024 - Feb 2025</span>
                                        </button>
                                        <button type="button" onclick="selectFiscalYear('2023/2024 (Genap)', 2024, 2)" class="fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-[#3D4947] hover:bg-[#EFF4FF] hover:text-[#00998A] transition">
                                            <span>2023/2024 (Genap)</span>
                                            <span class="text-[10px] text-[#6D7A77]">Mar 2024 - Agu 2024</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Interactive Mini Calendar Widget -->
                                <div class="border-t border-[#BCC9C6]/30 pt-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <button type="button" onclick="changeCalendarMonth(-1)" class="p-1 rounded hover:bg-slate-100 text-[#3D4947] transition" title="Bulan Sebelumnya">
                                            <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                                        </button>
                                        <span id="calendar-month-year" class="font-heading text-xs font-bold text-[#0B1C30]">
                                            Februari 2025
                                        </span>
                                        <button type="button" onclick="changeCalendarMonth(1)" class="p-1 rounded hover:bg-slate-100 text-[#3D4947] transition" title="Bulan Berikutnya">
                                            <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                        </button>
                                    </div>

                                    <!-- Days of Week Header -->
                                    <div class="grid grid-cols-7 text-center text-[10px] font-semibold text-[#6D7A77] mb-1">
                                        <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                                    </div>

                                    <!-- Days Grid (Populated via JS) -->
                                    <div id="calendar-days-grid" class="grid grid-cols-7 gap-1 text-center text-xs"></div>
                                </div>

                                <!-- Specific Date Picker Filter -->
                                <div class="border-t border-[#BCC9C6]/30 pt-3 flex items-center justify-between gap-2">
                                    <input
                                        type="date"
                                        id="fiscal-custom-date"
                                        value="2025-02-17"
                                        onchange="applyCustomFiscalDate(this.value)"
                                        class="w-full rounded-lg border border-[#BCC9C6]/60 bg-[#F8F9FF] px-2.5 py-1.5 text-xs text-[#0B1C30] focus:border-[#00998A] focus:outline-none"
                                    >
                                    <button
                                        type="button"
                                        onclick="closeFiscalCalendar()"
                                        class="shrink-0 rounded-lg bg-[#00998A] px-3 py-1.5 text-xs font-heading font-semibold text-white hover:bg-[#006A63] transition cursor-pointer"
                                    >
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Center: Full-Width Search Bar (Melebar penuh dari Tahun Anggaran ke Notifikasi tanpa ruang kosong) -->
                <div class="relative hidden sm:block flex-1 min-w-[140px]">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6D7A77]">
                        <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        placeholder="Cari kontrak, lisensi, atau mitra..."
                        class="h-[40px] w-full rounded-lg border border-[#BCC9C6]/50 bg-white pr-4 pl-10 text-xs sm:text-sm text-[#0B1C30] placeholder-[#6D7A77] focus:border-[#00998A] focus:ring-1 focus:ring-[#00998A] focus:outline-none transition shadow-2xs"
                    >
                </div>

                <!-- Right: Notifications + Sliders + Divider + Profile -->
                <div class="flex items-center justify-end gap-2 sm:gap-3 shrink-0">

                    <!-- Notification Bell with Red Dot -->
                    <button type="button" class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-lg hover:bg-slate-100 text-[#3D4947] transition cursor-pointer" aria-label="Notifikasi">
                        <svg width="20" height="20" class="w-5 h-5 text-[#3D4947]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <span class="absolute top-2 right-2 h-2.5 w-2.5 rounded-full bg-[#BA1A1A] ring-2 ring-white"></span>
                    </button>

                    <!-- Filter / Sliders Adjustment Icon -->
                    <button type="button" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg hover:bg-slate-100 text-[#3D4947] transition cursor-pointer" aria-label="Filter">
                        <svg width="20" height="20" class="w-5 h-5 text-[#3D4947]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" x2="4" y1="21" y2="14"/>
                            <line x1="4" x2="4" y1="10" y2="3"/>
                            <line x1="12" x2="12" y1="21" y2="12"/>
                            <line x1="12" x2="12" y1="8" y2="3"/>
                            <line x1="20" x2="20" y1="21" y2="16"/>
                            <line x1="20" x2="20" y1="12" y2="3"/>
                            <line x1="1" x2="7" y1="14" y2="14"/>
                            <line x1="9" x2="15" y1="8" y2="8"/>
                            <line x1="17" x2="23" y1="16" y2="16"/>
                        </svg>
                    </button>

                    <!-- Vertical Divider -->
                    <div class="hidden sm:block h-8 w-[1px] bg-[#BCC9C6]/40"></div>

                    <!-- Profile Persona: Prof. Dr. Sc. H. Zainal Nur Arifin, M.T. -->
                    <div class="flex items-center gap-3 pl-1 cursor-pointer group shrink-0">
                        <!-- Photo Avatar with graceful fallback -->
                        <div class="relative h-10 w-10 sm:h-11 sm:w-11 shrink-0 overflow-hidden rounded-lg border border-[#00998A]/30 shadow-xs bg-slate-100">
                            <img
                                src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&h=120&q=80"
                                alt="Prof. Dr. Sc. H. Zainal Nur Arifin, M.T."
                                class="h-full w-full object-cover"
                                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 44 44\'><rect width=\'44\' height=\'44\' fill=\'%23E5EEFF\'/><text x=\'22\' y=\'27\' font-family=\'sans-serif\' font-size=\'14\' font-weight=\'bold\' fill=\'%2300998A\' text-anchor=\'middle\'>ZN</text></svg>';"
                            >
                        </div>

                        <!-- Name and Executive Title -->
                        <div class="hidden sm:flex flex-col text-left leading-tight">
                            <span class="font-heading text-xs sm:text-sm font-bold text-[#0B1C30] group-hover:text-[#00998A] transition whitespace-nowrap">
                                Prof. Dr. Sc. H. Zainal Nur Arifin, M.T.
                            </span>
                            <span class="text-[11px] font-medium text-[#00998A] mt-0.5 whitespace-nowrap">
                                Direktur PNJ - Otoritas Pengambil Keputusan
                            </span>
                        </div>

                        <!-- Dropdown Chevron Icon -->
                        <svg width="16" height="16" class="w-4 h-4 text-[#6D7A77] group-hover:text-[#00998A] transition shrink-0 ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>

                </div>
            </header>

            <!-- --------------------------------------------------------------------- -->
            <!-- MAIN VIEW BODY (Fluid & Responsive, Full Width Tanpa Jarak Kiri Kanan)-->
            <!-- --------------------------------------------------------------------- -->
            <main class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-5 sm:py-6 space-y-5 sm:space-y-6">
