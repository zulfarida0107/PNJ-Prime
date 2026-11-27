<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Autentikasi') — PNJ Prime</title>

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
                    colors: {
                        pnj: {
                            50: '#E6F4F2',
                            100: '#CCE9E5',
                            200: '#99D3CB',
                            500: '#009B8C',
                            600: '#00877A',
                            700: '#006E63',
                            800: '#00564D',
                        },
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

    @stack('styles')
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
    {{-- Full-Screen Vertically & Horizontally Centered Auth Canvas (100% Fit 1 Layar Laptop Tanpa Scroll) --}}
    <main class="min-h-screen lg:h-screen lg:overflow-hidden flex flex-col justify-center items-center py-3 px-4">
        <div class="w-full max-w-[520px]">
            {{-- Minimalist Top Utility Bar (Tanpa Header Navigasi Publik) --}}
            <div class="mb-2 flex items-center justify-between gap-2 px-0.5">
                <a href="{{ Route::has('public.katalog') ? route('public.katalog') : url('/katalog') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-[#00877A] transition-colors">
                    <svg width="14" height="14" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M19 12H5"></path>
                        <path d="m12 19-7-7 7-7"></path>
                    </svg>
                    <span>Kembali ke Katalog Publik</span>
                </a>

                <a href="{{ Route::has('public.katalog') ? route('public.katalog') : url('/') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0F172A]">
                    <span class="flex h-5 w-5 items-center justify-center rounded-md bg-[#00877A] text-white">
                        <svg width="12" height="12" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </span>
                    <span>PNJ Prime</span>
                </a>
            </div>

            {{-- Desktop-Proportioned Outer Card Shell (Seragam max-w-[520px], Tanpa Scroll Internal Desktop) --}}
            <div class="w-full lg:min-h-[510px] bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                @yield('content')
            </div>

            {{-- Ultra-Compact Institutional Attribution --}}
            <p class="mt-2 text-center text-[10px] text-slate-400">
                &copy; 2026 UPA-RTPU Politeknik Negeri Jakarta &bull; Sistem Komersialisasi Riset Terverifikasi
            </p>
        </div>
    </main>

    <script>
        (function () {
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

            document.querySelectorAll('[data-demo-fill]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetEmailId = btn.getAttribute('data-target-email') || 'email';
                    const targetPassId = btn.getAttribute('data-target-password') || 'password';
                    const emailVal = btn.getAttribute('data-demo-email') || '';
                    const passVal = btn.getAttribute('data-demo-password') || 'password123';

                    const emailInput = document.getElementById(targetEmailId);
                    const passInput = document.getElementById(targetPassId);
                    if (emailInput) {
                        emailInput.value = emailVal;
                        emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    if (passInput) {
                        passInput.value = passVal;
                        passInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    document.querySelectorAll('[data-demo-fill]').forEach(function (chip) {
                        chip.classList.remove('border-[#00877A]', 'bg-[#E6F4F2]', 'text-[#00877A]');
                        chip.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-600');
                    });
                    btn.classList.remove('border-slate-200', 'bg-slate-50', 'text-slate-600');
                    btn.classList.add('border-[#00877A]', 'bg-[#E6F4F2]', 'text-[#00877A]');
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
