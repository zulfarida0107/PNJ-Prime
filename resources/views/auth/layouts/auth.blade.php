<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Autentikasi') — PNJ Prime</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#E0F2F1',
                            100: '#B2DFDB',
                            200: '#80CBC4',
                            500: '#009688',
                            600: '#00897B',
                            700: '#00796B',
                            800: '#00695C',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
        }
        .auth-bg-glow {
            background-image: radial-gradient(circle at 50% 35%, rgba(0, 137, 123, 0.08) 0%, rgba(248, 250, 252, 0) 60%);
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col justify-between bg-[#F8FAFC] text-slate-800 antialiased">

    {{-- Header / Navbar Atas (Ramping & Non-Sticky) --}}
    <header class="bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-4">
            {{-- Brand Kiri --}}
            <div class="flex items-center gap-2.5">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-[#E0F2F1] border border-[#00897B]/20 flex items-center justify-center shrink-0 group-hover:bg-[#00897B]/15 transition-colors">
                        <svg class="w-4 h-4 text-[#00897B]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2.25c-.41 0-.82.09-1.2.26L4.6 5.26A1.75 1.75 0 0 0 3.5 6.87v4.78c0 5.05 3.41 9.53 8.12 10.97a1.3 1.3 0 0 0 .76 0c4.71-1.44 8.12-5.92 8.12-10.97V6.87a1.75 1.75 0 0 0-1.1-1.61l-6.2-2.75a2.95 2.95 0 0 0-1.2-.26Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <span class="block text-sm font-bold text-slate-900 tracking-tight">PNJ Prime</span>
                        <span class="block text-[10px] font-medium text-slate-500">Politeknik Negeri Jakarta</span>
                    </div>
                </a>
                <span class="hidden lg:inline-block h-5 w-px bg-slate-200 ml-1.5" aria-hidden="true"></span>
            </div>

            {{-- Navigasi Tengah --}}
            <nav class="hidden md:flex items-center gap-6" aria-label="Navigasi Utama">
                <a href="{{ url('/') }}" class="text-xs font-medium text-slate-600 hover:text-[#00897B] transition-colors">Beranda</a>
                <a href="{{ url('/#katalog') }}" class="text-xs font-medium text-slate-600 hover:text-[#00897B] transition-colors">Katalog Produk</a>
                <a href="{{ url('/#fasilitas') }}" class="text-xs font-medium text-slate-600 hover:text-[#00897B] transition-colors">Fasilitas Lab</a>
                <a href="{{ url('/#bantuan') }}" class="text-xs font-medium text-slate-600 hover:text-[#00897B] transition-colors">Bantuan</a>
            </nav>

            {{-- Tombol Akses SSO Kanan --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('login.sso') }}"
                   class="inline-flex items-center gap-1.5 bg-[#00897B] hover:bg-[#00796B] active:bg-[#00695C] text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg shadow-sm transition-colors">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Akses SSO</span>
                </a>

                {{-- Mobile Menu Button --}}
                <button type="button" id="mobile-nav-toggle"
                        class="md:hidden inline-flex items-center justify-center p-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                        aria-label="Buka menu navigasi">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Nav Drawer --}}
        <div id="mobile-nav-menu" class="hidden md:hidden border-t border-slate-100 bg-white px-4 py-2 space-y-1">
            <a href="{{ url('/') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00897B]">Beranda</a>
            <a href="{{ url('/#katalog') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00897B]">Katalog Produk</a>
            <a href="{{ url('/#fasilitas') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00897B]">Fasilitas Lab</a>
            <a href="{{ url('/#bantuan') }}" class="block px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00897B]">Bantuan</a>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 auth-bg-glow flex items-center justify-center py-4 px-4">
        @yield('content')
    </main>

    {{-- Footer Bawah (1 Baris Ramping) --}}
    <footer class="bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-500">
            <p>&copy; 2026 UPA-RTPU Politeknik Negeri Jakarta. Hak cipta dilindungi.</p>
            <div class="flex items-center gap-5">
                <a href="#" class="hover:text-[#00897B] transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#00897B] transition-colors">Syarat &amp; Ketentuan</a>
                <a href="#" class="hover:text-[#00897B] transition-colors">Hubungi Helpdesk</a>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            const toggleBtn = document.getElementById('mobile-nav-toggle');
            const mobileMenu = document.getElementById('mobile-nav-menu');
            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = btn.getAttribute('data-toggle-password');
                    const input = document.getElementById(targetId);
                    if (!input) return;

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';

                    const eyeOpen = btn.querySelector('[data-eye-open]');
                    const eyeClosed = btn.querySelector('[data-eye-closed]');
                    if (eyeOpen && eyeClosed) {
                        eyeOpen.classList.toggle('hidden', isPassword);
                        eyeClosed.classList.toggle('hidden', !isPassword);
                    }
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
