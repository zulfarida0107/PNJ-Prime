<?php
$pageTitle = '30. Pimpinan - Ringkasan Eksekutif — PNJ Prime BLU';
$activeMenu = 'ringkasan';

// Memasukkan layout header (otomatis mencakup sidebar & top navigation bar)
include_once __DIR__ . '/layout/header.php';
?>

<!-- ========================================================================= -->
<!-- 1. EXECUTIVE CONTENT HEADER & CRITICAL PRIMARY ACTION CLUSTER            -->
<!-- ========================================================================= -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between border-b border-[#BCC9C6]/20 pb-5 gap-4">
    
    <div class="flex flex-col gap-1">
        <!-- Badges and Subtitle -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-[#00685F]/10 px-2.5 py-0.5 text-[11px] font-semibold text-[#00998A]">
                RINGKASAN EKSEKUTIF
            </span>
            <span class="text-[11px] font-semibold tracking-wide text-[#6D7A77]">
                Otoritas Pimpinan &amp; Dewan Pengawas BLU
            </span>
        </div>

        <!-- Heading 2 -->
        <h2 class="font-heading text-xl sm:text-2xl font-bold tracking-tight text-[#0B1C30] mt-0.5">
            Kinerja Hilirisasi &amp; Pendapatan BLU
        </h2>

        <!-- Subtext Description -->
        <p class="text-xs sm:text-sm text-[#3D4947] max-w-2xl leading-relaxed">
            Pantau performa realisasi PNBP, komersialisasi produk riset terapan, dan kemitraan strategis industri PNJ secara berkala.
        </p>
    </div>

    <!-- High-Priority Critical Export Action and Period Filter -->
    <div class="flex flex-wrap items-center gap-3">
        
        <!-- Calendar Period Dropdown -->
        <div class="relative w-full sm:w-auto">
            <div class="flex h-[42px] items-center justify-between sm:justify-start gap-2 rounded border border-[#BCC9C6]/60 bg-white px-3.5 py-2 shadow-sm text-xs sm:text-sm font-heading font-semibold text-[#0B1C30] cursor-pointer hover:border-[#00998A] transition">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" class="w-4 h-4 shrink-0 text-[#6D7A77]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                    <span>Tahun Fiskal 2026 (YTD)</span>
                </div>
                <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0 text-[#6D7A77] ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>
        </div>

        <!-- Button - CRITICAL USE CASE FEATURE: Ekspor Laporan Pendapatan (PDF/Excel) in Solid Dark Teal -->
        <button type="button" class="flex h-[42px] w-full sm:w-auto items-center justify-center gap-2 rounded bg-[#00998A] px-5 py-2 text-xs sm:text-sm font-heading font-semibold text-white shadow-sm hover:bg-[#006A63] transition active:scale-[0.98] cursor-pointer whitespace-nowrap">
            <svg width="16" height="16" class="w-4 h-4 shrink-0 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" x2="12" y1="15" y2="3"/>
            </svg>
            <span>Ekspor Laporan Pendapatan (PDF/Excel)</span>
        </button>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. SECTION - INDIKATOR KINERJA UTAMA (4 KPI Cards Grid - Fully Responsive)-->
<!-- ========================================================================= -->
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
    
    <!-- Card 1: Total Pendapatan Hilirisasi -->
    <div class="relative flex flex-col justify-between rounded-lg border border-[#BCC9C6]/40 bg-white p-5 shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="pointer-events-none absolute top-0 right-0 h-24 w-24 rounded-bl-xl bg-[#00685F]/5"></div>

        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wide text-[#3D4947]">
                    TOTAL PENDAPATAN HILIRISASI
                </span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#00998A]/10 text-[#00998A]">
                    <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="12" x2="12" y1="2" y2="22"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="font-heading text-2xl lg:text-3xl font-bold tracking-tight text-[#0B1C30]">
                    Rp 6.84 Miliar
                </div>
            </div>
        </div>

        <div class="mt-5 border-t border-[#BCC9C6]/20 pt-3">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1 rounded bg-[#10B981]/10 border border-[#10B981]/20 px-2 py-0.5 text-[11px] font-bold text-[#047857]">
                    <svg width="12" height="12" class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                    +18.4% YoY
                </span>
            </div>
            <p class="mt-1.5 text-xs text-[#6D7A77]">
                Akumulasi realisasi kas BLU masuk
            </p>
        </div>
    </div>

    <!-- Card 2: Total Transaksi B2B & Kemitraan -->
    <div class="relative flex flex-col justify-between rounded-lg border border-[#BCC9C6]/40 bg-white p-5 shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="pointer-events-none absolute top-0 right-0 h-24 w-24 rounded-bl-xl bg-[#006A63]/5"></div>

        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wide text-[#3D4947]">
                    TRANSAKSI B2B &amp; KEMITRAAN
                </span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#006A63]/10 text-[#006A63]">
                    <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="font-heading text-2xl lg:text-3xl font-bold tracking-tight text-[#0B1C30]">
                    142 Transaksi
                </div>
            </div>
        </div>

        <div class="mt-5 border-t border-[#BCC9C6]/20 pt-3">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1 rounded bg-[#00998A]/10 border border-[#00998A]/20 px-2 py-0.5 text-[11px] font-bold text-[#00998A]">
                    <svg width="12" height="12" class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    +24 Kontrak Baru
                </span>
            </div>
            <p class="mt-1.5 text-xs text-[#6D7A77]">
                Kontrak lisensi, sewa lab, &amp; matching fund
            </p>
        </div>
    </div>

    <!-- Card 3: Realisasi Target PNBP BLU -->
    <div class="relative flex flex-col justify-between rounded-lg border border-[#BCC9C6]/40 bg-white p-5 shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="pointer-events-none absolute top-0 right-0 h-24 w-24 rounded-bl-xl bg-[#006947]/5"></div>

        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wide text-[#3D4947]">
                    TARGET PNBP BLU
                </span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#006947]/10 text-[#006947]">
                    <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="6"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="font-heading text-2xl lg:text-3xl font-bold tracking-tight text-[#00998A]">
                    112.5%
                </div>
            </div>
        </div>

        <div class="mt-5 border-t border-[#BCC9C6]/20 pt-3">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1 rounded bg-[#10B981]/10 border border-[#10B981]/20 px-2 py-0.5 text-[11px] font-bold text-[#047857]">
                    <svg width="12" height="12" class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Melampaui Target
                </span>
            </div>
            <p class="mt-1.5 text-xs text-[#6D7A77]">
                Target Rp 6.08 Miliar telah terpenuhi
            </p>
        </div>
    </div>

    <!-- Card 4: Mitra Industri Aktif & Loyal -->
    <div class="relative flex flex-col justify-between rounded-lg border border-[#BCC9C6]/40 bg-white p-5 shadow-sm overflow-hidden hover:shadow-md transition">
        <div class="pointer-events-none absolute top-0 right-0 h-24 w-24 rounded-bl-xl bg-[#00685F]/5"></div>

        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold tracking-wide text-[#3D4947]">
                    MITRA INDUSTRI AKTIF
                </span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-[#E5EEFF] text-[#3D4947]">
                    <svg width="16" height="16" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                        <path d="M9 22v-4h6v4"/>
                        <path d="M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/>
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="font-heading text-2xl lg:text-3xl font-bold tracking-tight text-[#0B1C30]">
                    54 Perusahaan
                </div>
            </div>
        </div>

        <div class="mt-5 border-t border-[#BCC9C6]/20 pt-3">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center gap-1 rounded bg-[#006A63]/10 border border-[#006A63]/20 px-2 py-0.5 text-[11px] font-bold text-[#006A63]">
                    <svg width="12" height="12" class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                    </svg>
                    87% Retensi Mitra
                </span>
            </div>
            <p class="mt-1.5 text-xs text-[#6D7A77]">
                Kolaborasi riset &amp; serapan lulusan
            </p>
        </div>
    </div>

</section>

<!-- ========================================================================= -->
<!-- 3. SECTION - TRAJECTORY & CHART (Tren Realisasi Bulanan - Responsive SVG)-->
<!-- ========================================================================= -->
<section class="rounded-lg border border-[#BCC9C6]/40 bg-white p-4 sm:p-6 shadow-sm overflow-hidden">
    
    <!-- Chart Header & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#BCC9C6]/20 pb-4">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="font-heading text-base sm:text-lg font-bold tracking-tight text-[#0B1C30]">
                    Tren Realisasi Pendapatan Komersialisasi &amp; Hilirisasi
                </h3>
                <span class="rounded-full bg-[#00998A]/10 px-2.5 py-0.5 text-[11px] font-semibold text-[#00998A]">
                    TAHUN 2026
                </span>
            </div>
            <p class="mt-1 text-xs text-[#6D7A77]">
                Perbandingan realisasi kas BLU bulanan terhadap target trajectory 2026
            </p>
        </div>

        <!-- Filter Toggle Tabs -->
        <div class="flex items-center gap-1 overflow-x-auto pb-1 sm:pb-0 bg-[#EFF4FF] p-1 rounded-lg border border-[#BCC9C6]/40 text-xs">
            <button type="button" class="rounded bg-white px-3 py-1.5 font-heading font-semibold text-[#00998A] shadow-sm whitespace-nowrap">
                Tahun Ini (2026)
            </button>
            <button type="button" class="rounded px-3 py-1.5 font-medium text-[#3D4947] hover:text-[#0B1C30] transition whitespace-nowrap">
                Tahun Lalu (2025)
            </button>
            <button type="button" class="rounded px-3 py-1.5 font-medium text-[#3D4947] hover:text-[#0B1C30] transition whitespace-nowrap">
                Kuartal IV
            </button>
            <button type="button" class="rounded px-3 py-1.5 font-medium text-[#3D4947] hover:text-[#0B1C30] transition whitespace-nowrap">
                Kuartal III
            </button>
        </div>
    </div>

    <!-- Legend & Quick Metrics Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 py-3 text-xs">
        <div class="flex flex-wrap items-center gap-4 sm:gap-6">
            <!-- Realisasi Legend -->
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-[#00998A] shrink-0"></span>
                <span class="font-heading font-semibold text-[#0B1C30]">Realisasi Aktual (Kas Masuk)</span>
            </div>
            <!-- Target Legend -->
            <div class="flex items-center gap-2">
                <span class="w-4 border-t-2 border-dashed border-[#6D7A77] shrink-0"></span>
                <span class="text-[#3D4947]">Target Trajectory RKAT</span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[#6D7A77]">
            <span>Baseline RKAT: Rp 500 Jt/Bulan</span>
            <span class="hidden sm:inline-block h-3 w-[1px] bg-[#BCC9C6]/50"></span>
            <span class="font-medium text-[#0B1C30]">Rerata Bulanan: <strong class="text-[#00998A]">Rp 570 Juta</strong></span>
        </div>
    </div>

    <!-- SVG Line / Area Graph Component with Horizontal Scroll container on small devices -->
    <div class="relative w-full overflow-x-auto pt-2 pb-4">
        
        <!-- Min-width prevents graph squashing on mobile screens -->
        <div class="relative min-w-[650px] xl:min-w-0">
            
            <!-- Dynamic Peak Callout / Tooltip positioned above October -->
            <div class="absolute right-[17%] top-1 z-10 flex flex-col rounded border border-[#00998A]/30 bg-white p-2 shadow-md">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-[#00998A] shrink-0"></span>
                    <span class="text-[11px] font-bold text-[#0B1C30] whitespace-nowrap">Puncak Realisasi: Rp 920 Juta</span>
                </div>
                <span class="text-[10px] font-medium text-[#00998A] mt-0.5 whitespace-nowrap">
                    Bulan Oktober (Pasca Hilirisasi Batch 2)
                </span>
            </div>

            <!-- SVG Vector Graph (Full Width & Responsive) -->
            <svg viewBox="0 0 910 260" class="w-full h-auto overflow-visible" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="tealAreaGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#00998A" stop-opacity="0.30"/>
                        <stop offset="85%" stop-color="#00998A" stop-opacity="0.04"/>
                        <stop offset="100%" stop-color="#00998A" stop-opacity="0.00"/>
                    </linearGradient>
                </defs>

                <!-- Horizontal Dashed Grid Lines -->
                <g stroke="#E5E7EB" stroke-width="1" stroke-dasharray="3 3">
                    <line x1="50" y1="20" x2="890" y2="20" />
                    <line x1="50" y1="65" x2="890" y2="65" />
                    <line x1="50" y1="110" x2="890" y2="110" />
                    <line x1="50" y1="155" x2="890" y2="155" />
                    <line x1="50" y1="200" x2="890" y2="200" stroke-dasharray="0" stroke="#CBD5E1" />
                </g>

                <!-- Y-Axis Labels -->
                <g font-family="Inter" font-size="11" fill="#94A3B8" text-anchor="end">
                    <text x="42" y="24">1.0 M</text>
                    <text x="42" y="69">800 Jt</text>
                    <text x="42" y="114">600 Jt</text>
                    <text x="42" y="159">400 Jt</text>
                    <text x="42" y="204">0</text>
                </g>

                <!-- Target Trajectory Dashed Line (Target RKAT) -->
                <path
                    d="M 60 145 L 135 140 L 210 135 L 285 130 L 360 120 L 435 115 L 510 105 L 585 100 L 660 90 L 735 85 L 810 80 L 885 75"
                    stroke="#94A3B8"
                    stroke-width="2"
                    stroke-dasharray="4 4"
                    fill="none"
                />

                <!-- Actual Realization Area Fill -->
                <path
                    d="M 60 170 
                       L 135 145 
                       L 210 125 
                       L 285 132 
                       L 360 108 
                       L 435 95 
                       L 510 75 
                       L 585 85 
                       L 660 55 
                       L 735 32 
                       L 810 58 
                       L 885 45 
                       L 885 200 
                       L 60 200 Z"
                    fill="url(#tealAreaGradient)"
                />

                <!-- Actual Realization Stroke Line -->
                <path
                    d="M 60 170 
                       L 135 145 
                       L 210 125 
                       L 285 132 
                       L 360 108 
                       L 435 95 
                       L 510 75 
                       L 585 85 
                       L 660 55 
                       L 735 32 
                       L 810 58 
                       L 885 45"
                    stroke="#00998A"
                    stroke-width="3.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    fill="none"
                />

                <!-- Data Points (Circle Vectors) -->
                <g fill="#FFFFFF" stroke="#00998A" stroke-width="2">
                    <circle cx="60" cy="170" r="4.5" />
                    <circle cx="135" cy="145" r="4.5" />
                    <circle cx="210" cy="125" r="4.5" />
                    <circle cx="285" cy="132" r="4.5" />
                    <circle cx="360" cy="108" r="4.5" />
                    <circle cx="435" cy="95" r="4.5" />
                    <circle cx="510" cy="75" r="4.5" />
                    <circle cx="585" cy="85" r="4.5" />
                    <circle cx="660" cy="55" r="4.5" />
                    
                    <!-- October Peak Point (Enlarged with double ring) -->
                    <circle cx="735" cy="32" r="8" fill="#00998A" stroke="#FFFFFF" stroke-width="2.5" />
                    <circle cx="735" cy="32" r="13" fill="none" stroke="#00998A" stroke-width="1.5" stroke-opacity="0.4" />

                    <circle cx="810" cy="58" r="4.5" />
                    <circle cx="885" cy="45" r="4.5" />
                </g>

                <!-- X-Axis Labels (Months) -->
                <g font-family="Inter" font-size="12" fill="#64748B" text-anchor="middle">
                    <text x="60" y="225">Jan</text>
                    <text x="135" y="225">Feb</text>
                    <text x="210" y="225">Mar</text>
                    <text x="285" y="225">Apr</text>
                    <text x="360" y="225">Mei</text>
                    <text x="435" y="225">Jun</text>
                    <text x="510" y="225">Jul</text>
                    <text x="585" y="225">Agu</text>
                    <text x="660" y="225">Sep</text>
                    <text x="735" y="225" font-weight="700" fill="#00998A">Okt</text>
                    <text x="810" y="225">Nov</text>
                    <text x="885" y="225">Des</text>
                </g>
            </svg>
        </div>
    </div>

    <!-- Graph Subtext Summary Footer -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-t border-[#BCC9C6]/20 pt-3 text-xs text-[#6D7A77]">
        <div class="flex items-center gap-2">
            <svg width="16" height="16" class="w-4 h-4 shrink-0 text-[#00998A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" x2="12" y1="16" y2="12"/>
                <line x1="12" x2="12.01" y1="8" y2="8"/>
            </svg>
            <span>Realisasi tertinggi terjadi pada Q3 &amp; Q4 didorong penyelesaian kontrak lisensi dan pengujian lab industri.</span>
        </div>
        <a href="#" class="font-medium text-[#00998A] hover:underline whitespace-nowrap">
            Lihat Analisis Varians &rarr;
        </a>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. BOTTOM TWO-COLUMN SECTION (60% / 40% - Responsive Grid)               -->
<!-- ========================================================================= -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
    
    <!-- LEFT COLUMN (60% ~ 7 cols): Distribusi Sumber Pendapatan Komersialisasi Terbesar -->
    <div class="lg:col-span-7 flex flex-col justify-between rounded-lg border border-[#BCC9C6]/40 bg-white p-5 sm:p-6 shadow-sm">
        
        <div>
            <!-- Header -->
            <div class="flex flex-wrap items-center justify-between border-b border-[#BCC9C6]/20 pb-4 gap-2">
                <div>
                    <h3 class="font-heading text-base font-bold tracking-tight text-[#0B1C30]">
                        Distribusi Sumber Pendapatan Komersialisasi
                    </h3>
                    <p class="mt-0.5 text-xs text-[#6D7A77]">
                        Breakdown kontribusi per unit usaha &amp; hilirisasi riset
                    </p>
                </div>
                <span class="rounded bg-[#EFF4FF] px-2.5 py-1 text-[11px] font-semibold text-[#3D4947]">
                    4 KATEGORI UTAMA
                </span>
            </div>

            <!-- Detailed Progress Bars & Metric Line Items -->
            <div class="mt-5 space-y-5">
                
                <!-- 1. Modul IoT & Hardware Edukasi -->
                <div>
                    <div class="flex flex-wrap items-center justify-between text-sm gap-1">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-sm bg-[#00998A] shrink-0"></span>
                            <span class="font-heading font-semibold text-[#0B1C30]">
                                1. Modul IoT &amp; Hardware Edukasi
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-bold text-[#0B1C30]">Rp 2.46 Miliar</span>
                            <span class="text-xs font-semibold text-[#6D7A77]">(36%)</span>
                        </div>
                    </div>
                    <div class="mt-2 h-2.5 w-full rounded-full bg-[#E5EEFF] overflow-hidden">
                        <div class="h-full rounded-full bg-[#00998A]" style="width: 36%;"></div>
                    </div>
                    <div class="mt-1 flex flex-wrap items-center justify-between text-[11px] font-medium text-[#6D7A77] gap-1">
                        <span>Batch 1 &amp; 2 D4 Teknik Elektronika &amp; Telekomunikasi</span>
                        <span class="font-semibold text-[#047857]">+28% YoY</span>
                    </div>
                </div>

                <!-- 2. Jasa Pengujian, Sertifikasi & Sewa CNC RTPU -->
                <div>
                    <div class="flex flex-wrap items-center justify-between text-sm gap-1">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-sm bg-[#006A63] shrink-0"></span>
                            <span class="font-heading font-semibold text-[#0B1C30]">
                                2. Jasa Pengujian, Sertifikasi &amp; Sewa CNC RTPU
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-bold text-[#0B1C30]">Rp 1.91 Miliar</span>
                            <span class="text-xs font-semibold text-[#6D7A77]">(28%)</span>
                        </div>
                    </div>
                    <div class="mt-2 h-2.5 w-full rounded-full bg-[#E5EEFF] overflow-hidden">
                        <div class="h-full rounded-full bg-[#006A63]" style="width: 28%;"></div>
                    </div>
                    <div class="mt-1 flex flex-wrap items-center justify-between text-[11px] font-medium text-[#6D7A77] gap-1">
                        <span>Layanan pengujian material &amp; lab presisi industri</span>
                        <span class="font-semibold text-[#047857]">+14% YoY</span>
                    </div>
                </div>

                <!-- 3. Lisensi Paten & Konsorsium Matching Fund -->
                <div>
                    <div class="flex flex-wrap items-center justify-between text-sm gap-1">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-sm bg-[#006947] shrink-0"></span>
                            <span class="font-heading font-semibold text-[#0B1C30]">
                                3. Lisensi Paten &amp; Konsorsium Matching Fund
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-bold text-[#0B1C30]">Rp 1.57 Miliar</span>
                            <span class="text-xs font-semibold text-[#6D7A77]">(23%)</span>
                        </div>
                    </div>
                    <div class="mt-2 h-2.5 w-full rounded-full bg-[#E5EEFF] overflow-hidden">
                        <div class="h-full rounded-full bg-[#006947]" style="width: 23%;"></div>
                    </div>
                    <div class="mt-1 flex flex-wrap items-center justify-between text-[11px] font-medium text-[#6D7A77] gap-1">
                        <span>3 Paten terlisensi industri otomotif &amp; energi</span>
                        <span class="font-semibold text-[#047857]">+32% YoY</span>
                    </div>
                </div>

                <!-- 4. Konsultansi Teknis & Fabrikasi Industri -->
                <div>
                    <div class="flex flex-wrap items-center justify-between text-sm gap-1">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-sm bg-[#6D7A77] shrink-0"></span>
                            <span class="font-heading font-semibold text-[#0B1C30]">
                                4. Konsultansi Teknis &amp; Fabrikasi Industri
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-bold text-[#0B1C30]">Rp 890 Juta</span>
                            <span class="text-xs font-semibold text-[#6D7A77]">(13%)</span>
                        </div>
                    </div>
                    <div class="mt-2 h-2.5 w-full rounded-full bg-[#E5EEFF] overflow-hidden">
                        <div class="h-full rounded-full bg-[#6D7A77]" style="width: 13%;"></div>
                    </div>
                    <div class="mt-1 flex flex-wrap items-center justify-between text-[11px] font-medium text-[#6D7A77] gap-1">
                        <span>Rancang bangun purwarupa &amp; pendampingan UMKM mitra</span>
                        <span class="font-semibold text-[#3D4947]">Stabil (&plusmn;0%)</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Metric summary callout -->
        <div class="mt-6 flex flex-wrap items-center justify-between rounded border-t border-[#BCC9C6]/20 bg-[#EFF4FF]/50 px-3.5 py-3 gap-2">
            <div class="flex items-center gap-2">
                <svg width="16" height="16" class="w-4 h-4 shrink-0 text-[#00998A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <span class="text-xs font-semibold text-[#0B1C30]">
                    Total Kontribusi BLU Terealisasi
                </span>
            </div>
            <span class="font-heading text-sm sm:text-base font-bold text-[#00998A]">
                Rp 6.84 Miliar (100%)
            </span>
        </div>
    </div>

    <!-- RIGHT COLUMN (40% ~ 5 cols): Status Disposisi & Usulan Pimpinan Terkini -->
    <div class="lg:col-span-5 flex flex-col justify-between rounded-lg border border-[#BCC9C6]/40 bg-white p-5 sm:p-6 shadow-sm">
        
        <div>
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-[#BCC9C6]/20 pb-4">
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0 text-[#00998A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" x2="8" y1="13" y2="13"/>
                        <line x1="16" x2="8" y1="17" y2="17"/>
                    </svg>
                    <h3 class="font-heading text-base font-bold tracking-tight text-[#0B1C30]">
                        Disposisi &amp; Usulan
                    </h3>
                </div>
                <span class="rounded bg-[#BA1A1A]/10 border border-[#BA1A1A]/20 px-2 py-0.5 text-[11px] font-bold text-[#BA1A1A]">
                    3 PERLU TINDAKAN
                </span>
            </div>

            <!-- List of 3 Key Executive Dispositions -->
            <div class="mt-4 space-y-3.5">
                
                <!-- Item 1: Modul IoT ESP32-S3 (High Priority with Trigger Button) -->
                <div class="rounded border border-[#00685F]/30 bg-[#00685F]/5 p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="rounded bg-[#BA1A1A] px-1.5 py-0.5 text-[10px] font-bold tracking-wide text-white uppercase">
                            URGENT
                        </span>
                        <span class="text-[11px] font-medium text-[#6D7A77]">Diajukan 2 jam yang lalu</span>
                    </div>
                    <h4 class="font-heading text-sm font-bold text-[#0B1C30]">
                        Persetujuan Komersialisasi Batch 3
                    </h4>
                    <p class="text-xs text-[#3D4947] leading-relaxed">
                        Usulan kontrak 500 unit modul IoT dengan PT Nusantara Elektronik senilai Rp 450 Juta.
                    </p>
                    <div class="flex flex-wrap items-center justify-between border-t border-[#00685F]/15 pt-2.5 gap-2">
                        <span class="text-[11px] font-medium text-[#6D7A77]">Pengusul: Tim Riset IoT RTPU</span>
                        
                        <!-- BUTTON PEMICU POPUP: "Tinjau Sekarang" -->
                        <button
                            type="button"
                            id="btn-tinjau-sekarang"
                            onclick="openApprovalModal()"
                            class="flex items-center gap-1.5 rounded bg-[#00998A] px-3 py-1.5 text-xs font-heading font-semibold text-white hover:bg-[#006A63] transition shadow-sm active:scale-95 cursor-pointer"
                        >
                            <span>Tinjau Sekarang</span>
                            <svg width="12" height="12" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Item 2: Perjanjian Konsorsium EV Battery Lab PNJ -->
                <div class="rounded border border-[#BCC9C6]/40 bg-[#F8F9FF] p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="rounded bg-[#10B981]/15 px-1.5 py-0.5 text-[10px] font-bold text-[#047857] uppercase">
                            KONSORSIUM
                        </span>
                        <span class="text-[11px] font-medium text-[#6D7A77]">Kemarin, 14:20</span>
                    </div>
                    <h4 class="font-heading text-sm font-bold text-[#0B1C30]">
                        MoU Matching Fund EV Battery
                    </h4>
                    <p class="text-xs text-[#3D4947] leading-relaxed">
                        Finalisasi draft kemitraan pendanaan bersama industri baterai nasional senilai Rp 1.2 Miliar.
                    </p>
                    <div class="flex items-center justify-between border-t border-[#BCC9C6]/20 pt-2 text-xs">
                        <span class="text-[11px] font-medium text-[#6D7A77]">Mitra: Konsorsium Energi</span>
                        <a href="#" class="flex items-center gap-1 font-heading font-semibold text-[#00998A] hover:underline">
                            <span>Lihat Draft MoU</span>
                            <svg width="12" height="12" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Item 3: Sertifikasi Kalibrasi Alat Ukur Industri -->
                <div class="rounded border border-[#BCC9C6]/40 bg-[#F8F9FF] p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="rounded bg-[#E5EEFF] px-1.5 py-0.5 text-[10px] font-bold text-[#3D4947] uppercase">
                            LAYANAN LAB
                        </span>
                        <span class="text-[11px] font-medium text-[#6D7A77]">3 hari lalu</span>
                    </div>
                    <h4 class="font-heading text-sm font-bold text-[#0B1C30]">
                        Penetapan Tarif Layanan Kalibrasi 2027
                    </h4>
                    <p class="text-xs text-[#3D4947] leading-relaxed">
                        Penyesuaian SK Direktur tentang tarif pengujian akreditasi KAN untuk lab metrologi.
                    </p>
                    <div class="flex items-center justify-between border-t border-[#BCC9C6]/20 pt-2 text-xs">
                        <span class="text-[11px] font-medium text-[#6D7A77]">Unit: Lab Metrologi RTPU</span>
                        <a href="#" class="flex items-center gap-1 font-heading font-semibold text-[#00998A] hover:underline">
                            <span>Detail Dokumen</span>
                            <svg width="12" height="12" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom View All Action -->
        <div class="mt-4 border-t border-[#BCC9C6]/20 pt-3">
            <button type="button" class="flex w-full items-center justify-center gap-1.5 rounded py-2 text-sm font-heading font-semibold text-[#00998A] hover:bg-[#EFF4FF] transition cursor-pointer">
                <span>Lihat Semua Disposisi Masuk (8)</span>
                <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </div>

</section>

<!-- ========================================================================= -->
<!-- MODAL POPUP: 31_Pimpinan_Modal_Approval_Pesanan                          -->
<!-- Ditampilkan saat tombol "Tinjau Sekarang" diklik                         -->
<!-- ========================================================================= -->
<div
    id="modal-approval-pesanan"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-3 sm:p-5 overflow-y-auto hidden"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Modal Card Container: Sesuai Mockup Gambar, Kompak, Tanpa Scrollbar Internal -->
    <div class="relative w-full max-w-lg sm:max-w-2xl md:max-w-3xl lg:max-w-[760px] bg-white border border-[#BCC9C6]/40 rounded-xl shadow-[0px_25px_60px_-12px_rgba(0,0,0,0.35)] overflow-hidden my-auto flex flex-col animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Top Decorative Institutional Accent Bar (5px) -->
        <div class="h-[5px] w-full shrink-0 bg-gradient-to-r from-[#00685F] via-[#008378] to-[#006A63]"></div>

        <!-- Modal Header -->
        <div class="flex items-start justify-between border-b border-[#BCC9C6]/20 bg-white px-5 py-3.5 sm:px-7 sm:py-4 shrink-0">
            <div class="flex flex-col gap-1 pr-4 max-w-2xl">
                <!-- Institutional Security Seal Badge -->
                <div class="inline-flex items-center gap-1.5 rounded-full border border-[#006A63]/20 bg-[rgba(153,239,229,0.4)] px-2.5 py-0.5 w-fit">
                    <svg width="11" height="13" class="w-3 h-3 shrink-0 text-[#006A63]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                    <span class="text-[10px] sm:text-[11px] font-bold tracking-wider text-[#006A63] uppercase">
                        PORTAL EKSEKUTIF DIREKSI &bull; BLU PNJ
                    </span>
                </div>

                <!-- Heading 2 -->
                <h2 id="modal-title" class="font-heading text-lg sm:text-2xl font-bold tracking-tight text-[#0B1C30] mt-0.5">
                    Otorisasi Persetujuan SK Tarif &amp; HPP
                </h2>

                <!-- Subtitle / Context -->
                <p class="text-xs text-[#3D4947] leading-relaxed">
                    Persetujuan resmi tingkat pimpinan untuk penetapan Harga Pokok Penjualan (HPP) dan pengesahan Surat Keputusan (SK) Tarif produk inovasi ke dalam etalase katalog resmi PNJ Prime.
                </p>
            </div>

            <!-- Button - Tutup Dialog -->
            <button
                type="button"
                onclick="closeApprovalModal()"
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-[#6D7A77] hover:bg-slate-100 hover:text-[#0B1C30] transition cursor-pointer"
                aria-label="Tutup Dialog"
            >
                <svg width="18" height="18" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body Content: Tanpa Scrollbar Internal -->
        <div class="flex flex-col p-4 sm:p-5 gap-3.5 flex-1">
            
            <!-- 1. High-Value Transaction Summary Card -->
            <div class="rounded-xl border border-[#BCC9C6]/30 bg-[#EFF4FF]/30 p-3.5 sm:p-4 space-y-3">
                
                <!-- Card Header -->
                <div class="flex items-center justify-between border-b border-[#BCC9C6]/20 pb-2.5">
                    <div class="flex items-center gap-2">
                        <svg width="16" height="16" class="w-4 h-4 shrink-0 text-[#00685F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                        <span class="text-[11px] sm:text-xs font-bold tracking-wider text-[#6D7A77] uppercase">
                            SPESIFIKASI PENETAPAN SK TARIF &amp; HPP
                        </span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-md border border-[#006947]/20 bg-[#006947]/10 px-2.5 py-0.5 text-[11px] font-bold text-[#006947]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#006947]"></span>
                        Siap Disahkan
                    </span>
                </div>

                <!-- Product Name and Unit Details (Grid Diperlebar Proporsional) -->
                <div class="space-y-2">
                    <div>
                        <span class="text-[11px] font-medium text-[#6D7A77]">
                            Nama Produk / Inovasi Layanan
                        </span>
                        <div class="mt-0.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                            <h3 class="font-heading text-sm sm:text-base font-bold text-[#0B1C30] leading-snug">
                                Modul IoT Sensor Praktikum ESP32&ndash;S3 Dual Core (Rev 2.4)
                            </h3>
                            <div class="inline-flex items-center gap-1.5 rounded-lg border border-[#00685F]/20 bg-[#00685F]/10 px-2.5 py-1 text-[#00685F] shrink-0">
                                <svg width="14" height="14" class="w-3.5 h-3.5 text-[#00685F] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <span class="text-[11px] font-bold whitespace-nowrap">Inovasi Terdaftar P3M</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] font-medium text-[#6D7A77]">
                            Unit Pengusul / Jurusan
                        </span>
                        <p class="mt-0.5 text-xs text-[#0B1C30] leading-relaxed">
                            Lab Otomasi Industri &amp; IoT Jurusan Teknik Elektro PNJ &bull; <span class="text-[#006A63] font-bold">Skor Kelayakan P3M: 94/100 (Sangat Layak)</span>
                        </p>
                    </div>
                </div>

                <!-- Structured Cost of Goods Sold (HPP) Breakdown -->
                <div class="space-y-2 pt-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold tracking-wider text-[#6D7A77] uppercase text-[10px] sm:text-[11px]">
                            RINCIAN HARGA POKOK PENJUALAN (HPP) PER UNIT
                        </span>
                        <span class="font-medium text-[#3D4947] text-xs">
                            Total HPP: <strong class="text-[#0B1C30] font-bold">Rp 185.000</strong>
                        </span>
                    </div>

                    <!-- 3 HPP Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3">
                        <!-- Komponen & PCB -->
                        <div class="rounded-lg border border-[#BCC9C6]/30 bg-white p-3 shadow-xs">
                            <span class="text-[11px] font-semibold text-[#6D7A77]">1. Komponen &amp; PCB</span>
                            <div class="font-heading text-sm sm:text-base font-bold text-[#0B1C30] mt-0.5">Rp 120.000</div>
                            <span class="text-[11px] text-[#3D4947] mt-0.5 block">Bahan baku &amp; IC ESP32-S3</span>
                        </div>

                        <!-- Fabrikasi & SMT Lab -->
                        <div class="rounded-lg border border-[#BCC9C6]/30 bg-white p-3 shadow-xs">
                            <span class="text-[11px] font-semibold text-[#6D7A77]">2. Fabrikasi &amp; SMT Lab</span>
                            <div class="font-heading text-sm sm:text-base font-bold text-[#0B1C30] mt-0.5">Rp 45.000</div>
                            <span class="text-[11px] text-[#3D4947] mt-0.5 block">Jasa perakitan mesin Lab PNJ</span>
                        </div>

                        <!-- QC, Kalibrasi & Case -->
                        <div class="rounded-lg border border-[#BCC9C6]/30 bg-white p-3 shadow-xs">
                            <span class="text-[11px] font-semibold text-[#6D7A77]">3. QC, Kalibrasi &amp; Case</span>
                            <div class="font-heading text-sm sm:text-base font-bold text-[#0B1C30] mt-0.5">Rp 20.000</div>
                            <span class="text-[11px] text-[#3D4947] mt-0.5 block">Uji beban &amp; enclosure 3D</span>
                        </div>
                    </div>
                </div>

                <!-- Highlight Box: Usulan Harga Katalog / Etalase B2B (Margin Atas & Bebas Retribusi Bawah) -->
                <div class="rounded-xl border border-[#00685F]/30 bg-white p-3.5 sm:p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-0.5 max-w-md">
                        <span class="text-[10px] sm:text-[11px] font-semibold tracking-wider text-[#6D7A77] uppercase">
                            USULAN HARGA KATALOG / ETALASE B2B
                        </span>
                        <div class="flex items-baseline gap-1">
                            <span class="font-heading text-2xl sm:text-3xl font-extrabold tracking-tight text-[#00685F] leading-none">
                                Rp 250.000
                            </span>
                            <span class="font-heading text-xs sm:text-sm font-semibold text-[#3D4947]">
                                /Unit
                            </span>
                        </div>
                        <p class="text-[11px] text-[#3D4947] leading-tight pt-0.5">
                            Skema Royalti: 50% Inventor Dosen/Mhs, 30% Kas BLU PNJ, 20% Lab Riset Pengembang (SK Direktur No. 128/2024)
                        </p>
                    </div>

                    <!-- 2 Badges Stacked: Margin Atas, Bebas Retribusi Bawah -->
                    <div class="flex flex-col items-start sm:items-end gap-1.5 shrink-0">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-[#006947]/20 bg-[rgba(78,222,163,0.3)] px-3 py-1 text-xs font-bold text-[#006947] whitespace-nowrap">
                            <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                                <polyline points="16 7 22 7 22 13"/>
                            </svg>
                            Margin: +35.1% (+Rp 65.000)
                        </span>
                        <span class="inline-flex items-center rounded-lg border border-[#BCC9C6]/30 bg-[#E5EEFF] px-3 py-1 text-xs font-semibold text-[#00685F] whitespace-nowrap">
                            Bebas Retribusi Ganda PP 49/2022
                        </span>
                    </div>
                </div>

            </div>

            <!-- 2. Full-Width Confirmation Notice Box (Grid Diperlebar Penuh Selebar Kartu) -->
            <div class="w-full rounded-xl border border-[#006A63]/30 bg-[rgba(153,239,229,0.2)] p-3 sm:p-3.5 flex items-start gap-3">
                <svg width="18" height="18" class="w-4 h-4 text-[#006A63] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m14.5 12.5-8 8a2.119 2.119 0 1 1-3-3l8-8"/>
                    <path d="m16 16 6-6"/>
                    <path d="m8 8 6-6"/>
                    <path d="m9 7 8 8"/>
                    <path d="m21 11-8-8"/>
                    <line x1="2" x2="10" y1="22" y2="22"/>
                </svg>
                <div class="space-y-0.5 text-xs leading-relaxed">
                    <p class="font-bold text-[#0B1C30]">
                        Tindakan ini akan mengesahkan harga secara final dan menerbitkan SK Tarif resmi untuk produk ini ke dalam katalog PNJ Prime.
                    </p>
                    <p class="text-[#3D4947]">
                        Dokumen SK Tarif elektronik bertanda tangan digital Direktur PNJ akan otomatis diarsipkan dalam sistem E-Office dan diintegrasikan ke etalase publik.
                    </p>
                </div>
            </div>

            <!-- 3. Full-Width Security PIN & Digital Authentication Box (Grid Diperlebar Penuh Selebar Kartu) -->
            <div class="w-full rounded-xl border border-[#BCC9C6]/30 bg-white p-3 sm:p-3.5 flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-[#00685F]/20 bg-[#00685F]/10 text-[#00685F]">
                        <svg width="18" height="18" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4"/>
                            <path d="M14 13.12c0 2.38 0 6.38-1 8.88"/>
                            <path d="M17.29 21.02c.12-.6.43-2.3.5-3.02"/>
                            <path d="M2 12a10 10 0 0 1 18-6"/>
                            <path d="M2 16h.01"/>
                            <path d="M21.8 16c.2-2 .131-5.354 0-6"/>
                            <path d="M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2"/>
                            <path d="M8.65 22c.21-.66.45-1.32.57-2"/>
                            <path d="M9 6.8a6 6 0 0 1 9 5.2v2"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-heading text-xs sm:text-[13px] font-bold text-[#0B1C30]">
                            Otorisasi Digital: Akun Direktur Terautentikasi
                        </h4>
                        <p class="text-[11px] font-medium text-[#6D7A77]">
                            Prof. Dr. Sc. H. Zainal Nur Arifin, M.T. (NIP. 196803271994031001)
                        </p>
                    </div>
                </div>

                <div class="inline-flex items-center gap-1.5 rounded-lg border border-[#00685F]/20 bg-[#EFF4FF] px-2.5 py-1 text-xs font-bold text-[#00685F] shrink-0">
                    <svg width="12" height="12" class="w-3 h-3 text-[#00685F]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    <span>Kredensial Aktif</span>
                </div>
            </div>

        </div>

        <!-- Modal Footer & Action Buttons -->
        <div class="flex flex-row items-center justify-between border-t border-[#BCC9C6]/20 bg-[#EFF4FF]/40 px-5 py-3 sm:px-7 sm:py-3.5 shrink-0">
            <!-- Footnote / Status -->
            <div class="flex items-center gap-2 text-xs font-medium text-[#6D7A77]">
                <svg width="14" height="14" class="w-3.5 h-3.5 text-[#6D7A77] shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
                <span class="text-[11px] sm:text-xs">Protokol Enkripsi TLS 1.3 Terlindungi</span>
            </div>

            <!-- Action Button Group -->
            <div class="flex items-center gap-2.5">
                <button
                    type="button"
                    onclick="closeApprovalModal()"
                    class="rounded-lg border border-[#BCC9C6]/40 bg-white px-4 sm:px-5 py-2 text-xs font-heading font-semibold text-[#3D4947] hover:bg-slate-50 transition cursor-pointer text-center shadow-xs"
                >
                    Batal
                </button>
                <button
                    type="button"
                    onclick="alert('SK Tarif & HPP Berhasil Disetujui dan Disahkan!'); closeApprovalModal();"
                    class="flex items-center justify-center gap-2 rounded-lg bg-[#00685F] px-5 sm:px-6 py-2 text-xs font-heading font-semibold text-white shadow-sm hover:bg-[#00554e] transition active:scale-[0.98] cursor-pointer whitespace-nowrap"
                >
                    <svg width="15" height="15" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23 12l-2.44-2.79.34-3.69-3.61-.82-1.89-3.2L12 2.96 8.6 1.5 6.71 4.69 3.1 5.5l.34 3.7L1 12l2.44 2.79-.34 3.7 3.61.82L8.6 22.5l3.4-1.47 3.4 1.46 1.89-3.19 3.61-.82-.34-3.69L23 12zm-12.91 4.72l-3.8-3.81 1.48-1.48 2.32 2.33 5.85-5.87 1.48 1.48-7.33 7.35z"/>
                    </svg>
                    <span>Setujui &amp; Sahkan SK Tarif</span>
                </button>
            </div>
        </div>

    </div>
</div>

<?php
// Memasukkan footer layout
include __DIR__ . '/layout/footer.php';
?>
