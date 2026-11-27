<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNJ Prime — Platform Komersialisasi Riset &amp; Layanan UPA-RTPU PNJ</title>

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
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased flex flex-col">
    {{-- Top Navigation --}}
    <header class="sticky top-0 z-20 border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#00877A] text-white">
                    <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </span>
                <div class="leading-tight">
                    <span class="block text-sm font-extrabold tracking-tight text-[#0F172A]">PNJ Prime</span>
                    <span class="block text-[10px] font-semibold text-slate-500">UPA-RTPU Politeknik Negeri Jakarta</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-xs font-semibold text-slate-600" aria-label="Navigasi Utama">
                <a href="{{ route('public.katalog') }}" class="hover:text-[#00877A] transition-colors">Katalog Riset</a>
                <a href="{{ route('public.katalog', ['order_type' => 'lab_reservation']) }}" class="hover:text-[#00877A] transition-colors">Fasilitas Lab</a>
                <a href="{{ route('login.sso') }}" class="hover:text-[#00877A] transition-colors">Gateway SSO PNJ</a>
            </nav>

            <div class="flex items-center gap-2.5">
                @auth
                    <a href="{{ route('public.katalog') }}"
                       class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3.5 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] transition-colors">
                        Katalog Publik
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex h-9 items-center rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 text-xs font-semibold text-white transition-colors">
                        Buka Dashboard
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
                    <a href="{{ route('login') }}"
                       class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-4 text-xs font-semibold text-[#0F172A] hover:border-[#00877A] hover:text-[#00877A] transition-colors">
                        Masuk / Login
                    </a>
                    <a href="{{ route('register.mitra') }}"
                       class="inline-flex h-9 items-center rounded-lg bg-[#00877A] hover:bg-[#006E63] px-4 text-xs font-semibold text-white transition-colors">
                        Daftar Mitra
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Hero Section --}}
    <main class="flex-1 flex items-center">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 py-12 lg:py-16">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#00877A]/25 bg-[#E6F4F2] px-3 py-1 text-[11px] font-bold text-[#00877A]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#00877A]"></span>
                        <span>Platform Resmi Hilirisasi Riset &amp; Layanan BLU PNJ</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[#0F172A] leading-tight">
                        Komersialisasi Riset Terapan &amp; Fasilitas Lab Industri Politeknik Negeri Jakarta
                    </h1>

                    <p class="text-sm sm:text-base leading-relaxed text-slate-600 max-w-2xl">
                        Jelajahi produk inovasi siap pakai, pengadaan pre-order manufaktur presisi, dan reservasi laboratorium tersertifikasi PNJ dengan tata kelola transparan, skema <strong class="text-[#0F172A]">100% Lunas</strong>, serta kelengkapan faktur pajak resmi BLU.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('public.katalog') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-[#00877A] hover:bg-[#006E63] px-6 py-3 text-sm font-bold text-white transition-colors">
                            <svg width="16" height="16" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            <span>Jelajahi Katalog Riset Publik</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-[#00877A]">Akses Cepat Layanan</p>
                                <h2 class="text-base font-bold text-[#0F172A]">Ekosistem PNJ Prime</h2>
                            </div>
                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">
                                System Online
                            </span>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('public.katalog') }}"
                               class="flex items-center justify-between rounded-xl border border-slate-200 bg-[#F8FAFC] p-3.5 hover:border-[#00877A] transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E6F4F2] text-[#00877A]">
                                        <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-[#0F172A]">Katalog Riset &amp; Inovasi</p>
                                        <p class="text-[11px] text-slate-500">Akses publik tanpa login</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#00877A]">&rarr;</span>
                            </a>

                            <a href="{{ route('public.katalog', ['order_type' => 'lab_reservation']) }}"
                               class="flex items-center justify-between rounded-xl border border-slate-200 bg-[#F8FAFC] p-3.5 hover:border-[#00877A] transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#E6F4F2] text-[#00877A]">
                                        <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M9 3h6"></path>
                                            <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3"></path>
                                            <path d="M7.5 14h9"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-[#0F172A]">Reservasi Lab &amp; Fabrikasi</p>
                                        <p class="text-[11px] text-slate-500">Workshop &amp; laboratorium tersertifikasi</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#00877A]">&rarr;</span>
                            </a>

                            <a href="{{ route('login') }}"
                               class="flex items-center justify-between rounded-xl border border-slate-200 bg-[#F8FAFC] p-3.5 hover:border-[#00877A] transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0F172A] text-white">
                                        <svg width="18" height="18" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-[#0F172A]">Portal Autentikasi Multi-Role</p>
                                        <p class="text-[11px] text-slate-500">SSO PNJ, Mitra B2B, Admin P3M &amp; RTPU</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-[#00877A]">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 sm:px-6 py-4 text-[11px] text-slate-500">
            <p>&copy; 2026 UPA-RTPU Politeknik Negeri Jakarta &bull; PNJ Prime Enterprise Portal</p>
            <p class="tabular-nums">Skema Pembayaran 100% Lunas &bull; Tarif Resmi BLU</p>
        </div>
    </footer>
</body>
</html>
