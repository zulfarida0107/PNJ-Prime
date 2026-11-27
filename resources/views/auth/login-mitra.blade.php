@extends('auth.layouts.auth')

@section('title', 'Portal Mitra Industri B2B')

@section('content')
{{-- Top Accent Strip --}}
<div class="h-1 w-full bg-[#00877A] shrink-0"></div>

{{-- Header Card --}}
<div class="px-6 pt-3 pb-2.5 text-center shrink-0">
    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#E6F4F2] border border-[#00877A]/20 text-[#00877A] text-[10px] font-bold tracking-wide uppercase mb-1">
        <span class="w-1.5 h-1.5 rounded-full bg-[#00877A]"></span>
        <span>Portal Kemitraan B2B</span>
    </div>
    <h1 class="text-base font-bold text-[#0F172A] tracking-tight">Portal Mitra Industri B2B</h1>
    <p class="mt-0.5 text-[11px] text-slate-500 leading-snug">
        Masuk dengan akun perusahaan terverifikasi untuk pengadaan &amp; reservasi lab PNJ.
    </p>
</div>

{{-- Tab Navigasi Login / Register --}}
<div class="grid grid-cols-2 border-y border-slate-200 text-xs shrink-0">
    <a href="{{ route('login.mitra') }}"
       class="flex items-center justify-center gap-1.5 py-1.5 font-bold text-[#00877A] border-b-2 border-[#00877A] bg-white">
        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
            <polyline points="10 17 15 12 10 7"></polyline>
            <line x1="15" y1="12" x2="3" y2="12"></line>
        </svg>
        <span>Login Mitra</span>
    </a>
    <a href="{{ route('register.mitra') }}"
       class="flex items-center justify-center gap-1.5 py-1.5 font-semibold text-slate-500 hover:text-[#0F172A] hover:bg-slate-50 transition-colors">
        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <line x1="20" y1="8" x2="20" y2="14"></line>
            <line x1="23" y1="11" x2="17" y2="11"></line>
        </svg>
        <span>Register Mitra</span>
    </a>
</div>

{{-- Body Card --}}
<div class="flex-1 px-6 py-4 flex flex-col justify-between gap-2.5">
    <div class="space-y-2.5">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="flex items-start gap-2 px-2.5 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] leading-snug">
                <svg width="14" height="14" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-2 px-2.5 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-[11px] leading-snug">
                <svg width="14" height="14" class="w-3.5 h-3.5 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Info Kepatuhan B2B Ringkas --}}
        <div class="flex items-start gap-2 px-3 py-2 rounded-lg bg-[#E6F4F2]/70 border border-[#00877A]/20">
            <svg width="16" height="16" class="w-4 h-4 text-[#00877A] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 21h18"></path>
                <path d="M5 21V7l7-4 7 4v14"></path>
                <path d="M9 21v-6h6v6"></path>
            </svg>
            <p class="text-[11px] text-slate-600 leading-snug">
                Pengadaan korporat resmi BLU PNJ dengan skema <strong class="text-[#0F172A]">100% Lunas</strong> serta faktur pajak PPN 11%, PPh 23, e-Meterai &amp; BAST.
            </p>
        </div>

        {{-- Form Login Mitra Non-SSO --}}
        <form action="{{ route('login.process') }}" method="POST" class="space-y-2">
            @csrf
            <input type="hidden" name="login_context" value="mitra">

            {{-- Email --}}
            <div>
                <label for="mitra_email" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                    Email Korporat / Akun Kemitraan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <input type="email"
                           id="mitra_email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="nama@perusahaan.co.id"
                           class="w-full h-9 pl-9 pr-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00877A] focus:ring-[#00877A]/20' }} rounded-lg focus:outline-none focus:ring-2 transition-colors">
                </div>
                @error('email')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kata Sandi --}}
            <div>
                <div class="flex items-center justify-between mb-0.5">
                    <label for="mitra_password" class="block text-[11px] font-semibold text-slate-700">
                        Kata Sandi
                    </label>
                    <a href="{{ route('login') }}" class="text-[10px] font-semibold text-[#00877A] hover:text-[#006E63] transition-colors">
                        Login Sivitas / Admin &rarr;
                    </a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password"
                           id="mitra_password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan kata sandi"
                           class="w-full h-9 pl-9 pr-9 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00877A] focus:ring-[#00877A]/20' }} rounded-lg focus:outline-none focus:ring-2 transition-colors">
                    <button type="button"
                            data-toggle-password="mitra_password"
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg data-eye-open width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-eye-closed width="14" height="14" class="w-3.5 h-3.5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           {{ old('remember') ? 'checked' : '' }}
                           class="w-3.5 h-3.5 rounded border-slate-300 text-[#00877A] focus:ring-[#00877A]/30 accent-[#00877A]">
                    <span class="text-[11px] font-medium text-slate-600">Ingat perangkat ini</span>
                </label>
                <a href="{{ route('login.sso') }}" class="text-[11px] font-medium text-slate-500 hover:text-[#00877A] transition-colors">
                    Gateway SSO PNJ
                </a>
            </div>

            {{-- Tombol Utama --}}
            <button type="submit"
                    class="w-full h-9 inline-flex items-center justify-center gap-2 bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-bold px-4 rounded-xl transition-colors">
                <span>Masuk Portal Mitra B2B</span>
                <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>
    </div>

    {{-- Panel Akun Demo Mitra 1-Klik --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-2.5">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold tracking-wide text-slate-600 uppercase">Akun Demo Mitra B2B (1-Klik)</span>
            <span class="tabular-nums text-[10px] text-slate-400">Pass: password123</span>
        </div>
        <div class="grid grid-cols-2 gap-1.5">
            <button type="button" data-demo-fill data-target-email="mitra_email" data-target-password="mitra_password"
                    data-demo-email="budi@inovasiteknologi.co.id" data-demo-password="password123"
                    class="px-2.5 py-1 rounded-md border border-slate-200 bg-slate-50 hover:border-[#00877A] hover:bg-[#E6F4F2] hover:text-[#00877A] text-[10px] font-semibold text-slate-600 transition-colors truncate"
                    title="budi@inovasiteknologi.co.id">
                PT Inovasi Teknologi
            </button>
            <button type="button" data-demo-fill data-target-email="mitra_email" data-target-password="mitra_password"
                    data-demo-email="admin.rtpu@pnj.ac.id" data-demo-password="password123"
                    class="px-2.5 py-1 rounded-md border border-slate-200 bg-slate-50 hover:border-[#00877A] hover:bg-[#E6F4F2] hover:text-[#00877A] text-[10px] font-semibold text-slate-600 transition-colors truncate"
                    title="admin.rtpu@pnj.ac.id">
                Admin RTPU (Preview)
            </button>
        </div>
    </div>
</div>

{{-- Footer Card --}}
<div class="bg-slate-50 border-t border-slate-100 px-6 py-2 text-center text-[11px] text-slate-500 shrink-0">
    Belum terdaftar sebagai mitra?
    <a href="{{ route('register.mitra') }}" class="font-semibold text-[#00877A] hover:text-[#006E63] transition-colors">
        Daftar &amp; Verifikasi KYC
    </a>
</div>
@endsection
