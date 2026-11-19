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
        $kelasAktif = 'border-l-4 border-teal-500 bg-teal-50 text-teal-700';
        $kelasBiasa = 'border-l-4 border-transparent text-gray-600 hover:bg-slate-50 hover:text-teal-700';
    @endphp

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="sticky top-0 flex h-screen w-64 shrink-0 flex-col border-r border-gray-200 bg-white">
            <div class="flex items-center gap-3 px-5 py-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500 text-white">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10 12 5 2 10l10 5 10-5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                </span>
                <div class="leading-tight">
                    <p class="font-semibold text-teal-800">RTPU Student</p>
                    <p class="text-xs text-gray-400">Portal Akademik &amp; Riset</p>
                </div>
            </div>

            <nav class="mt-2 flex-1 space-y-1.5 px-3">
                <a href="{{ url('/dashboard-mahasiswa') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $menuAktif === 'katalog' ? $kelasAktif : $kelasBiasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z" />
                    </svg>
                    Katalog Utama
                </a>
                <a href="{{ url('/pesanan-saya') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $menuAktif === 'pesanan' ? $kelasAktif : $kelasBiasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    Pesanan Saya
                </a>
                <a href="{{ url('/pembayaran') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $menuAktif === 'pembayaran' ? $kelasAktif : $kelasBiasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2.5" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                    Pembayaran
                </a>
            </nav>

            <div class="space-y-1.5 border-t border-gray-100 px-3 py-5">
                <a href="{{ url('/pengaturan') }}"
                   class="flex items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-slate-50 hover:text-teal-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                    Pengaturan
                </a>
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg border-l-4 border-transparent px-3 py-2.5 text-left text-sm font-medium text-red-500 transition hover:bg-red-50">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="m16 17 5-5-5-5" />
                            <path d="M21 12H9" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Area utama --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col bg-slate-50">
            <header class="flex items-center justify-between border-b border-gray-200 bg-white px-8 py-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-800">Halo, Bagas</h1>
                        <span class="rounded-full border border-teal-200 bg-teal-50 px-2 py-0.5 text-xs font-medium text-teal-600">Mahasiswa Aktif</span>
                    </div>
                    <p class="mt-0.5 text-xs text-gray-500">Teknik Elektro - Smt 7 &bull; Politeknik Negeri Jakarta</p>
                </div>

                <div class="flex items-center gap-5">
                    <button type="button" class="relative text-slate-500 hover:text-slate-700" aria-label="Notifikasi">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                        </svg>
                        <span class="absolute -top-0.5 -right-0.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    <div class="flex items-center gap-3 border-l border-gray-200 pl-5">
                        <img src="https://i.pravatar.cc/80?img=12" alt="Bagas Pratama"
                             class="h-10 w-10 rounded-full object-cover ring-2 ring-teal-100">
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-slate-800">Bagas Pratama</p>
                            <p class="text-xs text-gray-500">NIM 2107411058</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
