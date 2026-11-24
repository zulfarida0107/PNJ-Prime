<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog Utama') — RTPU Student</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    @php
        $menuAktif = trim($__env->yieldContent('nav', 'katalog'));
        $kelasAktif = 'border-l-4 border-teal-500 bg-teal-50/90 text-teal-700 font-semibold shadow-xs';
        $kelasBiasa = 'border-l-4 border-transparent text-slate-600 hover:bg-slate-100/80 hover:text-teal-700 hover:translate-x-0.5';
    @endphp

    <div class="flex min-h-screen">
        {{-- Sidebar Kiri (Sticky & Compact) --}}
        <aside class="sticky top-0 flex h-screen w-56 shrink-0 flex-col border-r border-slate-200/80 bg-white">
            <div class="flex items-center gap-2.5 px-4 py-4 border-b border-slate-100">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-teal-600 text-white shadow-sm">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10 12 5 2 10l10 5 10-5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                </span>
                <div class="leading-tight min-w-0">
                    <p class="text-sm font-bold text-teal-800 truncate">RTPU Student</p>
                    <p class="text-[11px] text-slate-400 truncate">Portal Akademik &amp; Riset</p>
                </div>
            </div>

            <nav class="mt-3 flex-1 space-y-1 px-2.5">
                <a href="{{ url('/dashboard-mahasiswa') }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $menuAktif === 'katalog' ? $kelasAktif : $kelasBiasa }}">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z" />
                    </svg>
                    <span>Katalog Utama</span>
                </a>
                <a href="{{ url('/pesanan-saya') }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $menuAktif === 'pesanan' ? $kelasAktif : $kelasBiasa }}">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span>Pesanan Saya</span>
                </a>
                <a href="{{ url('/pembayaran') }}"
                   class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 {{ $menuAktif === 'pembayaran' ? $kelasAktif : $kelasBiasa }}">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2.5" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                    <span>Pembayaran</span>
                </a>
            </nav>

            <div class="space-y-1 border-t border-slate-100 px-2.5 py-3.5">
                <a href="{{ url('/pengaturan') }}"
                   class="flex items-center gap-2.5 rounded-lg border-l-4 border-transparent px-3 py-2 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-slate-100/80 hover:text-teal-700 hover:translate-x-0.5">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                    <span>Pengaturan</span>
                </a>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2.5 rounded-lg border-l-4 border-transparent px-3 py-2 text-left text-sm font-medium text-rose-600 transition-all duration-200 hover:bg-rose-50 hover:text-rose-700 hover:translate-x-0.5 active:scale-95">
                        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="m16 17 5-5-5-5" />
                            <path d="M21 12H9" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Area Utama --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col bg-slate-50">
            {{-- Top Navbar Ramping --}}
            <header class="flex items-center justify-between border-b border-slate-200/80 bg-white px-6 py-2.5">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-lg font-bold text-slate-800 tracking-tight">Halo, Bagas</h1>
                        <span class="rounded-full border border-teal-200 bg-teal-50 px-2 py-0.5 text-[11px] font-semibold text-teal-600">Mahasiswa Aktif</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Teknik Elektro - Smt 7 &bull; Politeknik Negeri Jakarta</p>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button"
                            class="relative flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition-all duration-200 hover:bg-slate-100 hover:text-teal-700 hover:shadow-sm active:scale-95"
                            aria-label="Notifikasi">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>

                    <div class="flex items-center gap-2.5 border-l border-slate-200 pl-4">
                        <img src="https://i.pravatar.cc/80?img=12" alt="Bagas Pratama"
                             class="h-8 w-8 rounded-full object-cover ring-2 ring-teal-100">
                        <div class="leading-tight">
                            <p class="text-xs font-semibold text-slate-800">Bagas Pratama</p>
                            <p class="text-[11px] text-slate-500">NIM 2107411058</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Konten Utama Padat --}}
            <main class="flex-1 p-5">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
