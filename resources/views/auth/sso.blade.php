@extends('auth.layouts.auth')

@section('title', 'Gateway SSO PNJ')

@section('content')
{{-- Top Accent Strip --}}
<div class="h-1 w-full bg-[#00877A] shrink-0"></div>

{{-- Header Card --}}
<div class="px-6 pt-3.5 pb-2.5 text-center shrink-0">
    <div class="flex justify-center mb-1.5">
        <div class="relative w-10 h-10 rounded-xl bg-[#E6F4F2] border border-[#00877A]/20 flex items-center justify-center">
            <svg width="20" height="20" class="w-5 h-5 text-[#0F172A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-[#00877A] text-white flex items-center justify-center ring-2 ring-white">
                <svg width="10" height="10" class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </span>
        </div>
    </div>
    <h1 class="text-base font-bold text-[#0F172A] tracking-tight">Autentikasi Akun Tunggal PNJ</h1>
    <p class="mt-0.5 text-[11px] text-slate-500 leading-snug">
        Masukkan NIM, NIP, atau Email Institusi untuk melanjutkan ke <strong class="text-[#0F172A]">PNJ Prime</strong>.
    </p>
    <div class="mt-1.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-semibold">
        <span class="w-1.5 h-1.5 rounded-full bg-[#00877A]"></span>
        <span>Layanan Terpusat UPT TIK PNJ</span>
    </div>
</div>

{{-- Body Card --}}
<div class="flex-1 px-6 py-3 flex flex-col justify-between gap-2.5">
    <div class="space-y-2.5">
        {{-- Flash Messages --}}
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

        @if (session('success'))
            <div class="flex items-start gap-2 px-2.5 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] leading-snug">
                <svg width="14" height="14" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Form SSO --}}
        <form action="{{ route('login.sso.process') }}" method="POST" class="space-y-2">
            @csrf

            {{-- Identifier --}}
            <div>
                <label for="identifier" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                    Email Institusi / NIM / NIP
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
                           placeholder="nama@pnj.ac.id atau NIM / NIP"
                           class="w-full h-9 pl-9 pr-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('identifier') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00877A] focus:ring-[#00877A]/20' }} rounded-lg focus:outline-none focus:ring-2 transition-colors">
                </div>
                @error('identifier')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="sso_password" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                    Kata Sandi Akun SSO
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password"
                           id="sso_password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan kata sandi SSO PNJ"
                           class="w-full h-9 pl-9 pr-9 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00877A] focus:ring-[#00877A]/20' }} rounded-lg focus:outline-none focus:ring-2 transition-colors">
                    <button type="button"
                            data-toggle-password="sso_password"
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
            <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                <input type="checkbox"
                       name="remember"
                       value="1"
                       {{ old('remember') ? 'checked' : '' }}
                       class="w-3.5 h-3.5 rounded border-slate-300 text-[#00877A] focus:ring-[#00877A]/30 accent-[#00877A]">
                <span class="text-[11px] font-medium text-slate-600">Ingat sesi autentikasi pada perangkat ini</span>
            </label>

            {{-- Tombol Aksi --}}
            <div class="grid grid-cols-2 gap-2 pt-0.5">
                <a href="{{ route('login') }}"
                   class="h-9 inline-flex items-center justify-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold px-3 rounded-xl transition-colors">
                    <svg width="14" height="14" class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali</span>
                </a>

                <button type="submit"
                        class="h-9 inline-flex items-center justify-center gap-1.5 bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-bold px-3 rounded-xl transition-colors">
                    <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Lanjutkan SSO</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Panel Demo SSO 1-Klik --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-2.5">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-bold tracking-wide text-slate-600 uppercase">Demo Akun SSO (1-Klik)</span>
            <span class="tabular-nums text-[10px] text-slate-400">Pass: password123</span>
        </div>
        <div class="grid grid-cols-2 gap-1.5">
            <button type="button" data-demo-fill data-target-email="identifier" data-target-password="sso_password"
                    data-demo-email="admin.rtpu@pnj.ac.id" data-demo-password="password123"
                    class="px-2 py-1 rounded-md border border-slate-200 bg-slate-50 hover:border-[#00877A] hover:bg-[#E6F4F2] hover:text-[#00877A] text-[10px] font-semibold text-slate-600 transition-colors truncate">
                Admin RTPU
            </button>
            <button type="button" data-demo-fill data-target-email="identifier" data-target-password="sso_password"
                    data-demo-email="admin.p3m@pnj.ac.id" data-demo-password="password123"
                    class="px-2 py-1 rounded-md border border-slate-200 bg-slate-50 hover:border-[#00877A] hover:bg-[#E6F4F2] hover:text-[#00877A] text-[10px] font-semibold text-slate-600 transition-colors truncate">
                Admin P3M
            </button>
            <button type="button" data-demo-fill data-target-email="identifier" data-target-password="sso_password"
                    data-demo-email="2107411058" data-demo-password="password123"
                    class="px-2 py-1 rounded-md border border-slate-200 bg-slate-50 hover:border-[#00877A] hover:bg-[#E6F4F2] hover:text-[#00877A] text-[10px] font-semibold text-slate-600 transition-colors truncate">
                NIM Mahasiswa (2107411058)
            </button>
            <button type="button" data-demo-fill data-target-email="identifier" data-target-password="sso_password"
                    data-demo-email="pimpinan.rtpu@pnj.ac.id" data-demo-password="password123"
                    class="px-2 py-1 rounded-md border border-slate-200 bg-slate-50 hover:border-[#00877A] hover:bg-[#E6F4F2] hover:text-[#00877A] text-[10px] font-semibold text-slate-600 transition-colors truncate">
                Pimpinan RTPU
            </button>
        </div>
    </div>
</div>

{{-- Footer Card --}}
<div class="bg-slate-50 border-t border-slate-100 px-6 py-2 text-center text-[11px] text-slate-500 shrink-0">
    Mitra Eksternal tanpa akun PNJ?
    <a href="{{ route('login.mitra') }}" class="font-semibold text-[#00877A] hover:text-[#006E63] transition-colors">
        Masuk Portal Mitra B2B
    </a>
</div>
@endsection
