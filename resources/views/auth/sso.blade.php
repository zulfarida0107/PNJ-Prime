@extends('auth.layouts.auth')

@section('title', 'Gateway SSO PNJ')

@section('content')
<div class="w-full max-w-[400px] bg-white rounded-2xl shadow-lg shadow-slate-200/60 border border-slate-100 overflow-hidden">
    {{-- Top Emerald Accent Bar --}}
    <div class="h-1 w-full bg-[#00897B]"></div>

    <div class="px-5 sm:px-6 pt-5 pb-5">
        {{-- Shield Icon + Check Badge --}}
        <div class="flex justify-center mb-3">
            <div class="relative w-11 h-11 rounded-xl bg-slate-100/80 border border-slate-200/70 flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-[#00897B] text-white flex items-center justify-center ring-2 ring-white">
                    <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </span>
            </div>
        </div>

        {{-- Heading --}}
        <div class="text-center mb-4">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Autentikasi Akun Tunggal PNJ</h1>
            <p class="mt-1 text-xs text-slate-500 leading-snug max-w-[290px] mx-auto">
                Masukkan NIM, NIP, atau Email Institusi Anda untuk melanjutkan ke <span class="font-semibold text-slate-700">PNJ Prime</span>.
            </p>
            <div class="mt-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50/80 border border-indigo-100 text-slate-700 text-[11px] font-semibold">
                <svg class="w-3 h-3 text-[#00897B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
                <span>Layanan Terpusat UPT TIK PNJ</span>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="mb-3 flex items-start gap-2 p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-[11px] leading-snug">
                <svg class="w-3.5 h-3.5 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-3 flex items-start gap-2 p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] leading-snug">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Form SSO --}}
        <form action="{{ route('login.sso.process') }}" method="POST" class="space-y-3">
            @csrf

            {{-- Email Institusi / NIM / NIP --}}
            <div>
                <label for="identifier" class="block text-xs font-semibold text-slate-700 mb-1">
                    Email Institusi / NIM / NIP
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <input type="text"
                           id="identifier"
                           name="identifier"
                           value="{{ old('identifier') }}"
                           required
                           autocomplete="username"
                           placeholder="nama@pnj.ac.id atau Nomor Induk"
                           class="w-full pl-9 pr-3.5 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('identifier') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-xl focus:outline-none focus:ring-2 transition-colors">
                </div>
                @error('identifier')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kata Sandi / Password --}}
            <div>
                <label for="sso_password" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kata Sandi / Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password"
                           id="sso_password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan kata sandi akun SSO Anda"
                           class="w-full pl-9 pr-10 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-xl focus:outline-none focus:ring-2 transition-colors">
                    <button type="button"
                            data-toggle-password="sso_password"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg data-eye-open class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-eye-closed class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Checkbox Ingat Sesi --}}
            <div>
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           {{ old('remember') ? 'checked' : '' }}
                           class="w-3.5 h-3.5 rounded border-slate-300 text-[#00897B] focus:ring-[#00897B]/30 accent-[#00897B]">
                    <span class="text-xs font-medium text-slate-600">Ingat sesi autentikasi pada perangkat ini</span>
                </label>
            </div>

            {{-- Tombol Aksi --}}
            <div class="space-y-2 pt-0.5">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#00897B] hover:bg-[#00796B] active:bg-[#00695C] text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Lanjutkan Autentikasi</span>
                </button>

                <a href="{{ route('login') }}"
                   class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-sm font-medium py-2 px-4 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Batalkan &amp; Kembali</span>
                </a>
            </div>
        </form>

        {{-- Footer Links --}}
        <div class="mt-4 pt-3.5 border-t border-slate-100 grid grid-cols-2 gap-2.5 text-[11px] leading-snug">
            <a href="#" class="flex items-start justify-center gap-1 text-center font-semibold text-[#00897B] hover:text-[#00796B] transition-colors">
                <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <span>Lupa Kata Sandi Akun PNJ?</span>
            </a>
            <a href="#" class="flex items-start justify-center gap-1 text-center font-medium text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
                <span>Panduan Keamanan SSO &amp; Bantuan TIK</span>
            </a>
        </div>
    </div>
</div>
@endsection
