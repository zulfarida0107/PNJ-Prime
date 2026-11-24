@extends('auth.layouts.auth')

@section('title', 'Portal Akses & Kemitraan')

@section('content')
<div class="w-full max-w-[390px] bg-white rounded-2xl shadow-lg shadow-slate-200/60 border border-slate-100 overflow-hidden">
    {{-- Header Card --}}
    <div class="px-4 pt-3 pb-2.5 text-center">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#E0F2F1] border border-[#00897B]/20 text-[#00897B] text-[10px] font-semibold mb-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-[#00897B]"></span>
            <span>Portal Resmi PNJ</span>
        </div>
        <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-snug">Portal Akses &amp; Kemitraan</h1>
        <p class="mt-0.5 text-[11px] text-slate-500 leading-tight max-w-[290px] mx-auto">
            Sistem autentikasi terverifikasi Politeknik Negeri Jakarta untuk Sivitas Akademika &amp; Mitra Industri B2B.
        </p>
    </div>

    {{-- Tab Navigasi Login / Register --}}
    <div class="grid grid-cols-2 border-b border-slate-200/80 text-xs">
        <a href="{{ route('login') }}"
           class="flex items-center justify-center gap-1.5 py-1.5 font-semibold text-[#00897B] border-b-2 border-[#00897B] bg-white">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
            <span>Login</span>
        </a>
        <a href="{{ route('register.mitra') }}"
           class="flex items-center justify-center gap-1.5 py-1.5 font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50/70 transition-colors">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <span>Register</span>
        </a>
    </div>

    {{-- Body Card --}}
    <div class="p-4 space-y-2">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flex items-start gap-2 p-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] leading-snug">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-2 p-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-[11px] leading-snug">
                <svg class="w-3.5 h-3.5 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="flex items-start gap-2 p-2 rounded-lg bg-sky-50 border border-sky-200 text-sky-800 text-[11px] leading-snug">
                <svg class="w-3.5 h-3.5 text-sky-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Kotak Info SSO Terpusat Aktif --}}
        <div class="flex items-start gap-2 p-2 rounded-lg bg-[#E0F2F1]/60 border border-[#00897B]/25">
            <div class="w-5 h-5 rounded-md bg-white border border-[#00897B]/20 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-[#00897B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
            </div>
            <div class="leading-tight">
                <p class="text-[11px] font-bold text-[#00796B]">SSO Terpusat Aktif</p>
                <p class="text-[10px] text-slate-600 mt-0.5">
                    Terintegrasi langsung dengan Layanan Single Sign-On PNJ (UPT TIK)
                </p>
            </div>
        </div>

        {{-- Tombol Utama Sign In with SSO PNJ --}}
        <a href="{{ route('login.sso') }}"
           class="w-full inline-flex items-center justify-center gap-1.5 bg-[#00897B] hover:bg-[#00796B] active:bg-[#00695C] text-white text-xs font-semibold py-2 px-4 rounded-xl shadow-sm transition-colors">
            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span>Sign In with SSO PNJ</span>
        </a>

        {{-- Pemisah --}}
        <div class="relative flex items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-2 text-[9px] font-semibold tracking-wider text-slate-400 uppercase">
                Atau Akses Kredensial Langsung
            </span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        {{-- Form Login Kredensial Langsung --}}
        <form action="{{ route('login.process') }}" method="POST" class="space-y-2">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                    Email Institusi / Akun Kemitraan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="nama@pnj.ac.id atau domain mitra"
                           class="w-full pl-8 pr-3 py-1.5 text-xs text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-lg focus:outline-none focus:ring-2 transition-colors">
                </div>
                @error('email')
                    <p class="mt-0.5 text-[10px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kata Sandi --}}
            <div>
                <div class="flex items-center justify-between mb-0.5">
                    <label for="password" class="block text-[11px] font-semibold text-slate-700">
                        Kata Sandi
                    </label>
                    <a href="#" class="text-[10px] font-semibold text-[#00897B] hover:text-[#00796B] transition-colors">
                        Lupa Kata Sandi?
                    </a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                        </svg>
                    </span>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••••••"
                           class="w-full pl-8 pr-9 py-1.5 text-xs text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-lg focus:outline-none focus:ring-2 transition-colors">
                    <button type="button"
                            data-toggle-password="password"
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg data-eye-open class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-eye-closed class="w-3.5 h-3.5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-0.5 text-[10px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox Ingat Perangkat --}}
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           {{ old('remember') ? 'checked' : '' }}
                           class="w-3.5 h-3.5 rounded border-slate-300 text-[#00897B] focus:ring-[#00897B]/30 accent-[#00897B]">
                    <span class="text-[11px] font-medium text-slate-600">Ingat perangkat ini</span>
                </label>
                <a href="{{ route('login.mitra') }}" class="text-[11px] font-medium text-slate-500 hover:text-[#00897B] transition-colors">
                    Portal Mitra B2B
                </a>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-1.5 bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 hover:border-[#00897B]/60 text-xs font-semibold py-2 px-4 rounded-xl transition-all">
                <span>Masuk Portal Akses</span>
                <svg class="w-3.5 h-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>
    </div>

    {{-- Footer Card --}}
    <div class="bg-slate-50/80 border-t border-slate-100 px-4 py-2 text-center text-[11px] text-slate-500">
        Butuh bantuan akses SSO?
        <a href="#" class="font-semibold text-[#00897B] hover:text-[#00796B] transition-colors">
            Hubungi UPT TIK PNJ Helpdesk
        </a>
    </div>
</div>
@endsection
