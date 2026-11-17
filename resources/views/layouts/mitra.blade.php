<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PNJ Prime B2B</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: { tosca: { DEFAULT: '#00998a', dark: '#007d71', soft: '#e6f7f5' } },
                },
            },
        };
    </script>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    @php
        $menuAktif = trim($__env->yieldContent('nav', 'dashboard'));
        $aktif = 'border-l-4 border-tosca bg-tosca-soft font-semibold text-tosca';
        $biasa = 'border-l-4 border-transparent font-medium text-slate-600 hover:bg-slate-50 hover:text-tosca';
    @endphp

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="sticky top-0 flex h-screen w-64 shrink-0 flex-col border-r border-slate-200 bg-white">
            <div class="flex items-start gap-3 px-5 pt-6 pb-4">
                <span class="mt-0.5 h-10 w-10 shrink-0 rounded-xl bg-tosca"></span>
                <div class="leading-tight">
                    <p class="text-base font-bold text-tosca">PNJ Prime</p>
                    <p class="mt-0.5 text-[10px] font-medium tracking-wide text-slate-500 uppercase">PT Inovasi Teknologi Nusa</p>
                </div>
            </div>

            <div class="px-4">
                <a href="#"
                   class="mb-5 flex w-full items-center justify-center gap-2 rounded-lg bg-tosca px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-tosca-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14" /></svg>
                    Buat Pengajuan
                </a>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3">
                <a href="{{ url('/dashboard-mitra') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'dashboard' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z" /></svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'katalog' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z" /><path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z" /></svg>
                    Katalog Riset
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'pengajuan' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17 2 2a1 1 0 0 0 3-3" /><path d="m14 14 2.5 2.5a1 1 0 0 0 3-3l-3.9-3.9a3 3 0 0 0-4.2 0l-.9.9a1 1 0 0 1-1.4 0l-.6-.6a1 1 0 0 1 0-1.4L10 6.5" /><path d="m21 3 1 11h-2" /><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" /><path d="M3 4h8" /></svg>
                    Pengajuan Kerja Sama
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'validasi' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="m8.5 12 2.5 2.5 4.5-5" /></svg>
                    Status Validasi
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'sewa' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6" /><path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" /><path d="M7.5 14h9" /></svg>
                    Sewa Fasilitas Lab
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'inbox' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" /><path d="m22 7-10 6L2 7" /></svg>
                    Inbox / Pesan
                </a>
                <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ $menuAktif === 'pengaturan' ? $aktif : $biasa }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" /></svg>
                    Pengaturan Akun
                </a>
            </nav>

            <div class="border-t border-slate-100 px-5 py-4 text-xs text-slate-500">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>System Online</span>
                    <span>v2.4 Enterprise</span>
                </div>
                <p class="mt-2 text-[11px] text-slate-400">PNJ Prime Enterprise Portal</p>
            </div>
        </aside>

        {{-- Area utama --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex items-center gap-4 border-b border-slate-200 bg-white px-8 py-3">
                <div class="flex flex-1 justify-center">
                    <label class="relative w-full max-w-md">
                        <svg class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" /></svg>
                        <input type="search" placeholder="Cari dokumen pengajuan, riset, atau riwayat..."
                               class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 pr-20 pl-10 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:border-tosca focus:bg-white focus:ring-2 focus:ring-tosca/20">
                        <kbd class="absolute top-1/2 right-2 -translate-y-1/2 rounded border border-slate-200 bg-white px-1.5 py-0.5 text-[10px] font-medium text-slate-500">Ctrl+K</kbd>
                    </label>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" class="text-slate-500 hover:text-slate-700" aria-label="Bantuan">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" /><path d="M12 17h.01" /></svg>
                    </button>
                    <button type="button" class="relative text-slate-500 hover:text-slate-700" aria-label="Notifikasi">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" /><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" /></svg>
                        <span class="absolute -top-0.5 -right-0.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-tosca">BS</span>
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-slate-800">Budi Santoso</p>
                            <p class="text-[11px] text-slate-500">Admin PT Inovasi Teknologi Nusa</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-500" aria-label="Keluar" title="Keluar">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><path d="m16 17 5-5-5-5" /><path d="M21 12H9" /></svg>
                        </button>
                    </form>
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
