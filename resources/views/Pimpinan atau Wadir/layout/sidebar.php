        <!-- ========================================================================= -->
        <!-- MOBILE SIDEBAR BACKDROP OVERLAY                                           -->
        <!-- ========================================================================= -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm lg:hidden hidden transition-opacity duration-300"></div>

        <!-- ========================================================================= -->
        <!-- ASIDE: SIDEBAR NAVIGATION (Width: 256px) - Responsive Drawer               -->
        <!-- ========================================================================= -->
        <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col justify-between border-r border-[#BCC9C6]/30 bg-white transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 h-screen overflow-y-auto">
            
            <!-- Top Section: Branding, Navigasi Utama & Unduh Dokumen -->
            <div class="flex flex-col p-4">
                
                <!-- Institutional Logo Anchor: PNJ Prime DIREKSI & Close Button on Mobile -->
                <div class="mb-5 border-b border-[#BCC9C6]/20 px-2 pt-2 pb-4 flex items-center justify-between">
                    <a href="#" class="flex items-center gap-3">
                        <!-- Classical Building / University Pillars Icon in Teal Square -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#00998A] text-white shadow-sm">
                            <svg width="20" height="20" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18"/>
                                <path d="M3 10h18"/>
                                <path d="M5 10v11"/>
                                <path d="M9 10v11"/>
                                <path d="M13 10v11"/>
                                <path d="M17 10v11"/>
                                <path d="M12 2L2 7h20L12 2z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="font-heading text-[15px] font-bold tracking-tight text-[#00998A]">PNJ Prime DIREKSI</span>
                            <span class="text-xs text-[#6D7A77] mt-0.5">Kantor Pimpinan - Direksi</span>
                        </div>
                    </a>

                    <!-- Close button for mobile drawer -->
                    <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-md text-[#6D7A77] hover:bg-slate-100 transition" aria-label="Tutup Menu">
                        <svg width="20" height="20" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                <!-- Section Title: NAVIGASI UTAMA -->
                <div class="px-2 pb-2.5 text-[11px] font-semibold tracking-wider text-[#6D7A77] uppercase">
                    NAVIGASI UTAMA
                </div>

                <!-- Nav - Menu Tunggal: Ringkasan Eksekutif (ACTIVE) -->
                <nav class="space-y-4">
                    <a href="#" class="relative flex items-center justify-between rounded-lg bg-[#EFF4FF] px-3.5 py-3 text-sm font-semibold text-[#00998A] transition shadow-sm">
                        <!-- Left indicator bar -->
                        <span class="absolute left-0 inset-y-1.5 w-1 rounded-r-full bg-[#00998A]"></span>

                        <div class="flex items-center gap-3 pl-1">
                            <!-- 4 Squares / Grid Icon -->
                            <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0 text-[#00998A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1"/>
                                <rect width="7" height="7" x="14" y="3" rx="1"/>
                                <rect width="7" height="7" x="14" y="14" rx="1"/>
                                <rect width="7" height="7" x="3" y="14" rx="1"/>
                            </svg>
                            <span class="font-heading text-sm font-semibold tracking-tight">Ringkasan Eksekutif</span>
                        </div>
                        <!-- Solid Teal Circle Dot on the right -->
                        <span class="h-2 w-2 rounded-full bg-[#00998A] mr-1 shrink-0"></span>
                    </a>

                    <!-- Separator line -->
                    <div class="border-b border-[#BCC9C6]/20 pt-1"></div>

                    <!-- Action Box: Unduh Dokumen Eksekutif -->
                    <button
                        type="button"
                        class="flex w-full items-center justify-center gap-3 rounded-lg border border-[#00998A]/30 bg-[#F4FBF9] p-4 text-[#00998A] hover:bg-[#E6F7F4] hover:border-[#00998A]/50 transition shadow-sm active:scale-[0.98] cursor-pointer"
                    >
                        <svg width="20" height="20" class="w-5 h-5 shrink-0 text-[#00998A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" x2="8" y1="13" y2="13"/>
                            <line x1="16" x2="8" y1="17" y2="17"/>
                            <line x1="10" x2="8" y1="9" y2="9"/>
                        </svg>
                        <div class="flex flex-col text-left font-heading text-xs font-semibold leading-snug">
                            <span>Unduh Dokumen</span>
                            <span>Eksekutif</span>
                        </div>
                    </button>
                </nav>
            </div>

            <!-- Bottom Section: Pengaturan Portal, Bantuan & Panduan, Footnote -->
            <div class="border-t border-[#BCC9C6]/30 p-4 space-y-1 bg-white">
                
                <!-- Pengaturan Portal -->
                <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-[#3D4947] hover:bg-slate-50 hover:text-[#0B1C30] transition">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0 text-[#6D7A77]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <span>Pengaturan Portal</span>
                </a>

                <!-- Bantuan & Panduan -->
                <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-[#3D4947] hover:bg-slate-50 hover:text-[#0B1C30] transition">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0 text-[#6D7A77]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" x2="12.01" y1="17" y2="17"/>
                    </svg>
                    <span>Bantuan &amp; Panduan</span>
                </a>

                <!-- Institutional Footnote -->
                <div class="px-3 pt-3 text-[11px] leading-tight text-[#6D7A77]">
                    <p class="font-semibold text-[#3D4947]">BLU Politeknik Negeri Jakarta</p>
                    <p class="text-[10px] text-[#94A3B8] mt-0.5">Sistem Otoritas v2.4 (Terverifikasi)</p>
                </div>
            </div>
        </aside>
