@extends('auth.layouts.auth')

@section('title', 'Pendaftaran Mitra Industri & KYC')

@php
    $step1Fields = ['nama_perusahaan', 'nik', 'npwp_perusahaan', 'dokumen_ktp', 'dokumen_npwp', 'dokumen_nib', 'dokumen_profil'];
    $step2Fields = ['email', 'no_telepon', 'password', 'agree_privacy'];

    $hasStep1Error = false;
    $hasStep2Error = false;
    if ($errors->any()) {
        foreach ($step1Fields as $f) {
            if ($errors->has($f)) {
                $hasStep1Error = true;
                break;
            }
        }
        foreach ($step2Fields as $f) {
            if ($errors->has($f)) {
                $hasStep2Error = true;
                break;
            }
        }
    }

    $initialStep = (! $hasStep1Error && $hasStep2Error) ? 2 : 1;
@endphp

@section('content')
{{-- Top Accent Strip --}}
<div class="h-1 w-full bg-[#00877A] shrink-0"></div>

{{-- Header Card (Identik dengan Login) --}}
<div class="px-6 pt-3 pb-2.5 text-center shrink-0">
    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#E6F4F2] border border-[#00877A]/20 text-[#00877A] text-[10px] font-bold tracking-wide uppercase mb-1">
        <span class="w-1.5 h-1.5 rounded-full bg-[#00877A]"></span>
        <span>Registrasi Mitra Industri B2B</span>
    </div>
    <h1 class="text-base font-bold text-[#0F172A] tracking-tight">Daftar Akun Mitra PNJ Prime</h1>
    <p class="mt-0.5 text-[11px] text-slate-500 leading-snug">
        Lengkapi 2 langkah verifikasi perusahaan &amp; akun untuk mengakses layanan B2B.
    </p>
</div>

{{-- Tab Navigasi Login / Register (Identik dengan Login) --}}
<div class="grid grid-cols-2 border-y border-slate-200 text-xs shrink-0">
    <a href="{{ route('login') }}"
       class="flex items-center justify-center gap-1.5 py-1.5 font-semibold text-slate-500 hover:text-[#0F172A] hover:bg-slate-50 transition-colors">
        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
            <polyline points="10 17 15 12 10 7"></polyline>
            <line x1="15" y1="12" x2="3" y2="12"></line>
        </svg>
        <span>Login</span>
    </a>
    <a href="{{ route('register.mitra') }}"
       class="flex items-center justify-center gap-1.5 py-1.5 font-bold text-[#00877A] border-b-2 border-[#00877A] bg-white">
        <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <line x1="20" y1="8" x2="20" y2="14"></line>
            <line x1="23" y1="11" x2="17" y2="11"></line>
        </svg>
        <span>Register Mitra</span>
    </a>
</div>

{{-- Stepper 2 Langkah --}}
<div class="px-6 py-2 border-b border-slate-100 bg-slate-50/50 shrink-0">
    <div class="grid grid-cols-2 gap-2">
        <button type="button" id="step-tab-1" data-goto-step="1"
                class="flex items-center gap-2 rounded-lg border px-2.5 py-1 text-left transition-colors">
            <span id="step-badge-1" class="tabular-nums flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold">1</span>
            <span class="min-w-0 truncate text-[11px] font-bold">1. Data Perusahaan</span>
        </button>
        <button type="button" id="step-tab-2" data-goto-step="2"
                class="flex items-center gap-2 rounded-lg border px-2.5 py-1 text-left transition-colors">
            <span id="step-badge-2" class="tabular-nums flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold">2</span>
            <span class="min-w-0 truncate text-[11px] font-bold">2. Akun &amp; Keamanan</span>
        </button>
    </div>
</div>

{{-- Single Existing Form (Multi-Step JS murni tanpa mengubah backend) --}}
<form id="register-mitra-form"
      action="{{ route('register.mitra.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="flex-1 px-6 py-3 flex flex-col justify-between">
    @csrf

    <div class="space-y-2">
        @if (session('error'))
            <div class="px-2.5 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-[11px] leading-snug">
                {{ session('error') }}
            </div>
        @endif

        <div id="step-client-alert" class="hidden px-2.5 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-medium leading-snug"></div>

        {{-- ================================================================= --}}
        {{-- LANGKAH 1: DATA PERUSAHAAN & LEGALITAS KYC                        --}}
        {{-- ================================================================= --}}
        <div id="register-step-1" class="space-y-2">
            {{-- Nama Perusahaan --}}
            <div>
                <label for="nama_perusahaan" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                    Nama Perusahaan / Instansi <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="nama_perusahaan"
                       name="nama_perusahaan"
                       value="{{ old('nama_perusahaan') }}"
                       required
                       placeholder="PT Inovasi Teknologi Nusantara"
                       class="w-full h-9 px-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('nama_perusahaan') ? 'border-rose-400' : 'border-slate-200 focus:border-[#00877A]' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 transition-colors">
                @error('nama_perusahaan')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIK & NPWP (2 Kolom Kompak) --}}
            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="nik" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                        NIK PIC (16 Digit) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nik"
                           name="nik"
                           value="{{ old('nik') }}"
                           required
                           maxlength="16"
                           inputmode="numeric"
                           placeholder="3276010101900001"
                           class="tabular-nums w-full h-9 px-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('nik') ? 'border-rose-400' : 'border-slate-200 focus:border-[#00877A]' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 transition-colors">
                    @error('nik')
                        <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="npwp_perusahaan" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                        NPWP Badan (16 Digit) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="npwp_perusahaan"
                           name="npwp_perusahaan"
                           value="{{ old('npwp_perusahaan') }}"
                           required
                           maxlength="16"
                           inputmode="numeric"
                           placeholder="0123456789012345"
                           class="tabular-nums w-full h-9 px-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('npwp_perusahaan') ? 'border-rose-400' : 'border-slate-200 focus:border-[#00877A]' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 transition-colors">
                    @error('npwp_perusahaan')
                        <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dokumen KYC Kompak (Grid 2x2) --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-2 space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold tracking-wide text-[#00877A] uppercase">Dokumen Legalitas (KYC)</span>
                    <span class="text-[10px] text-slate-400">PDF / JPG / PNG</span>
                </div>

                <div class="grid grid-cols-2 gap-1.5">
                    <label for="dokumen_ktp"
                           class="flex items-center gap-2 p-1.5 rounded-lg bg-white border border-dashed {{ $errors->has('dokumen_ktp') ? 'border-rose-400' : 'border-slate-300 hover:border-[#00877A]' }} cursor-pointer transition-colors">
                        <input type="file" id="dokumen_ktp" name="dokumen_ktp" accept=".pdf,.jpg,.jpeg,.png" required data-file-preview="preview_ktp" class="sr-only">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-[#E6F4F2] text-[#00877A]">
                            <svg width="13" height="13" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block text-[10px] font-bold text-[#0F172A]">Foto KTP <span class="text-rose-500">*</span></span>
                            <span id="preview_ktp" class="block text-[10px] text-slate-500 truncate">Pilih berkas (Maks 2MB)</span>
                        </span>
                    </label>

                    <label for="dokumen_npwp"
                           class="flex items-center gap-2 p-1.5 rounded-lg bg-white border border-dashed {{ $errors->has('dokumen_npwp') ? 'border-rose-400' : 'border-slate-300 hover:border-[#00877A]' }} cursor-pointer transition-colors">
                        <input type="file" id="dokumen_npwp" name="dokumen_npwp" accept=".pdf,.jpg,.jpeg,.png" required data-file-preview="preview_npwp" class="sr-only">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-[#E6F4F2] text-[#00877A]">
                            <svg width="13" height="13" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block text-[10px] font-bold text-[#0F172A]">Foto NPWP <span class="text-rose-500">*</span></span>
                            <span id="preview_npwp" class="block text-[10px] text-slate-500 truncate">Pilih berkas (Maks 2MB)</span>
                        </span>
                    </label>

                    <label for="dokumen_nib"
                           class="flex items-center gap-2 p-1.5 rounded-lg bg-white border border-dashed {{ $errors->has('dokumen_nib') ? 'border-rose-400' : 'border-slate-200 hover:border-[#00877A]' }} cursor-pointer transition-colors">
                        <input type="file" id="dokumen_nib" name="dokumen_nib" accept=".pdf,.jpg,.jpeg" data-file-preview="preview_nib" class="sr-only">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <svg width="13" height="13" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block text-[10px] font-bold text-slate-700">Dokumen NIB</span>
                            <span id="preview_nib" class="block text-[10px] text-slate-400 truncate">Opsional (Maks 5MB)</span>
                        </span>
                    </label>

                    <label for="dokumen_profil"
                           class="flex items-center gap-2 p-1.5 rounded-lg bg-white border border-dashed {{ $errors->has('dokumen_profil') ? 'border-rose-400' : 'border-slate-200 hover:border-[#00877A]' }} cursor-pointer transition-colors">
                        <input type="file" id="dokumen_profil" name="dokumen_profil" accept=".pdf" data-file-preview="preview_profil" class="sr-only">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <svg width="13" height="13" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block text-[10px] font-bold text-slate-700">Profil PDF</span>
                            <span id="preview_profil" class="block text-[10px] text-slate-400 truncate">Opsional (Maks 10MB)</span>
                        </span>
                    </label>
                </div>

                @foreach (['dokumen_ktp', 'dokumen_npwp', 'dokumen_nib', 'dokumen_profil'] as $docField)
                    @error($docField)
                        <p class="text-[10px] font-medium text-rose-600 leading-tight">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- LANGKAH 2: AKUN & KEAMANAN                                        --}}
        {{-- ================================================================= --}}
        <div id="register-step-2" class="hidden space-y-2">
            {{-- Email Kemitraan --}}
            <div>
                <label for="reg_email" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                    Email Kemitraan <span class="text-rose-500">*</span>
                </label>
                <input type="email"
                       id="reg_email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       placeholder="kemitraan@perusahaan.co.id"
                       class="w-full h-9 px-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('email') ? 'border-rose-400' : 'border-slate-200 focus:border-[#00877A]' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 transition-colors">
                @error('email')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- No HP + Toggle 2FA --}}
            <div>
                <div class="flex items-center justify-between mb-0.5">
                    <label for="no_telepon" class="block text-[11px] font-semibold text-slate-700">
                        No. HP / WhatsApp PIC <span class="text-rose-500">*</span>
                    </label>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                        <span class="text-[10px] font-semibold text-[#00877A]">Aktifkan 2FA</span>
                        <input type="checkbox"
                               name="enable_2fa"
                               value="1"
                               {{ old('enable_2fa', '1') ? 'checked' : '' }}
                               class="w-3.5 h-3.5 rounded border-slate-300 accent-[#00877A]">
                    </label>
                </div>
                <div class="flex h-9 rounded-lg overflow-hidden border {{ $errors->has('no_telepon') ? 'border-rose-400' : 'border-slate-200 focus-within:border-[#00877A]' }} bg-white focus-within:ring-2 focus-within:ring-[#00877A]/20 transition-colors">
                    <span class="tabular-nums inline-flex items-center px-2.5 text-[11px] font-bold text-slate-600 bg-slate-50 border-r border-slate-200">+62</span>
                    <input type="tel"
                           id="no_telepon"
                           name="no_telepon"
                           value="{{ old('no_telepon') }}"
                           required
                           placeholder="0812-3456-7890"
                           class="tabular-nums flex-1 px-3 text-xs text-[#0F172A] placeholder:text-slate-400 focus:outline-none">
                </div>
                @error('no_telepon')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password & Konfirmasi Password (2 Kolom) --}}
            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="reg_password" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="reg_password"
                               name="password"
                               required
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="Min. 8 karakter"
                               class="w-full h-9 pl-3 pr-8 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border {{ $errors->has('password') ? 'border-rose-400' : 'border-slate-200 focus:border-[#00877A]' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 transition-colors">
                        <button type="button"
                                data-toggle-password="reg_password"
                                class="absolute inset-y-0 right-0 pr-2 flex items-center text-slate-400 hover:text-slate-600"
                                aria-label="Tampilkan atau sembunyikan password">
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

                <div>
                    <label for="reg_password_confirmation" class="block text-[11px] font-semibold text-slate-700 mb-0.5">
                        Konfirmasi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password"
                           id="reg_password_confirmation"
                           name="password_confirmation"
                           required
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="Ulangi password"
                           class="w-full h-9 px-3 text-xs text-[#0F172A] placeholder:text-slate-400 bg-white border border-slate-200 focus:border-[#00877A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 transition-colors">
                </div>
            </div>

            {{-- Password Strength Meter Kompak --}}
            <div class="flex items-center gap-2">
                <div class="flex-1 grid grid-cols-4 gap-1" id="password-strength-bars">
                    <span class="h-1 rounded-full bg-slate-200 transition-colors"></span>
                    <span class="h-1 rounded-full bg-slate-200 transition-colors"></span>
                    <span class="h-1 rounded-full bg-slate-200 transition-colors"></span>
                    <span class="h-1 rounded-full bg-slate-200 transition-colors"></span>
                </div>
                <span id="password-strength-label" class="text-[10px] font-semibold text-slate-400 min-w-[64px] text-right">
                    Min. 8 kar.
                </span>
            </div>

            {{-- Persetujuan Privasi --}}
            <div>
                <label class="inline-flex items-start gap-2 cursor-pointer select-none">
                    <input type="checkbox"
                           id="agree_privacy"
                           name="agree_privacy"
                           value="1"
                           required
                           {{ old('agree_privacy') ? 'checked' : '' }}
                           class="mt-0.5 w-3.5 h-3.5 rounded border-slate-300 accent-[#00877A]">
                    <span class="text-[11px] text-slate-600 leading-snug">
                        Saya menyetujui <span class="font-semibold text-[#00877A]">Kebijakan Privasi</span> &amp; enkripsi pengelolaan dokumen KYC PNJ Prime.
                    </span>
                </label>
                @error('agree_privacy')
                    <p class="mt-0.5 text-[10px] font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Bottom Action Bar --}}
    <div class="pt-2.5 mt-2.5 border-t border-slate-100">
        {{-- Aksi Langkah 1 --}}
        <div id="step-1-actions">
            <button type="button"
                    id="btn-next-step"
                    class="w-full h-9 inline-flex items-center justify-center gap-2 bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-bold px-4 rounded-xl transition-colors">
                <span>Lanjut ke Data Akun</span>
                <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </div>

        {{-- Aksi Langkah 2 --}}
        <div id="step-2-actions" class="hidden flex items-center gap-2.5">
            <button type="button"
                    id="btn-prev-step"
                    class="h-9 inline-flex items-center justify-center gap-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3.5 rounded-xl transition-colors shrink-0">
                <svg width="14" height="14" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali</span>
            </button>

            <button type="submit"
                    class="flex-1 h-9 inline-flex items-center justify-center gap-2 bg-[#00877A] hover:bg-[#006E63] text-white text-xs font-bold px-4 rounded-xl transition-colors">
                <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
                <span>Daftar Akun Mitra</span>
            </button>
        </div>
    </div>
</form>

{{-- Footer Card --}}
<div class="bg-slate-50 border-t border-slate-100 px-6 py-2 text-center text-[11px] text-slate-500 shrink-0">
    Sudah memiliki akun Mitra?
    <a href="{{ route('login.mitra') }}" class="font-semibold text-[#00877A] hover:text-[#006E63] transition-colors">
        Masuk Portal Mitra B2B
    </a>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('register-mitra-form');
        const step1 = document.getElementById('register-step-1');
        const step2 = document.getElementById('register-step-2');
        const step1Actions = document.getElementById('step-1-actions');
        const step2Actions = document.getElementById('step-2-actions');
        const tab1 = document.getElementById('step-tab-1');
        const tab2 = document.getElementById('step-tab-2');
        const badge1 = document.getElementById('step-badge-1');
        const badge2 = document.getElementById('step-badge-2');
        const clientAlert = document.getElementById('step-client-alert');
        const nextBtn = document.getElementById('btn-next-step');
        const prevBtn = document.getElementById('btn-prev-step');

        let currentStep = {{ $initialStep }};

        function showClientAlert(msg) {
            if (!clientAlert) return;
            if (!msg) {
                clientAlert.textContent = '';
                clientAlert.classList.add('hidden');
                return;
            }
            clientAlert.textContent = msg;
            clientAlert.classList.remove('hidden');
        }

        function setStep(step) {
            currentStep = step === 2 ? 2 : 1;
            const isStep1 = currentStep === 1;

            step1.classList.toggle('hidden', !isStep1);
            step2.classList.toggle('hidden', isStep1);
            step1Actions.classList.toggle('hidden', !isStep1);
            step2Actions.classList.toggle('hidden', isStep1);

            if (isStep1) {
                tab1.className = 'flex items-center gap-2 rounded-lg border border-[#00877A]/30 bg-[#E6F4F2] px-2.5 py-1 text-left text-[#00877A] transition-colors';
                badge1.className = 'tabular-nums flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00877A] text-white text-[10px] font-bold';
                tab2.className = 'flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-left text-slate-500 hover:border-slate-300 transition-colors';
                badge2.className = 'tabular-nums flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-600 text-[10px] font-bold';
            } else {
                tab1.className = 'flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50/70 px-2.5 py-1 text-left text-emerald-700 transition-colors';
                badge1.className = 'tabular-nums flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-[10px] font-bold';
                tab2.className = 'flex items-center gap-2 rounded-lg border border-[#00877A]/30 bg-[#E6F4F2] px-2.5 py-1 text-left text-[#00877A] transition-colors';
                badge2.className = 'tabular-nums flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00877A] text-white text-[10px] font-bold';
            }
        }

        function validateStep1() {
            const nama = document.getElementById('nama_perusahaan');
            const nik = document.getElementById('nik');
            const npwp = document.getElementById('npwp_perusahaan');
            const ktp = document.getElementById('dokumen_ktp');
            const docNpwp = document.getElementById('dokumen_npwp');

            if (!nama || nama.value.trim() === '') {
                showClientAlert('Mohon isi Nama Perusahaan / Instansi terlebih dahulu.');
                nama && nama.focus();
                return false;
            }
            if (!nik || nik.value.trim().length !== 16) {
                showClientAlert('NIK penanggung jawab harus terdiri dari tepat 16 digit angka.');
                nik && nik.focus();
                return false;
            }
            if (!npwp || npwp.value.trim().length !== 16) {
                showClientAlert('NPWP badan / usaha harus terdiri dari tepat 16 digit angka.');
                npwp && npwp.focus();
                return false;
            }
            if (!ktp || !ktp.files || ktp.files.length === 0) {
                showClientAlert('Mohon unggah Foto / Dokumen KTP terlebih dahulu.');
                return false;
            }
            if (!docNpwp || !docNpwp.files || docNpwp.files.length === 0) {
                showClientAlert('Mohon unggah Foto / Dokumen NPWP terlebih dahulu.');
                return false;
            }

            showClientAlert('');
            return true;
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                if (validateStep1()) {
                    setStep(2);
                }
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                showClientAlert('');
                setStep(1);
            });
        }

        if (tab1) {
            tab1.addEventListener('click', function () {
                showClientAlert('');
                setStep(1);
            });
        }

        if (tab2) {
            tab2.addEventListener('click', function () {
                if (currentStep === 1 && !validateStep1()) {
                    return;
                }
                setStep(2);
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                const pwd = document.getElementById('reg_password');
                const confirmPwd = document.getElementById('reg_password_confirmation');
                if (pwd && confirmPwd && pwd.value !== confirmPwd.value) {
                    e.preventDefault();
                    setStep(2);
                    showClientAlert('Konfirmasi password tidak cocok dengan password yang dimasukkan.');
                    confirmPwd.focus();
                    return;
                }
                showClientAlert('');
            });
        }

        // Password strength indicator
        const pwdInput = document.getElementById('reg_password');
        const barsContainer = document.getElementById('password-strength-bars');
        const strengthLabel = document.getElementById('password-strength-label');

        if (pwdInput && barsContainer && strengthLabel) {
            const bars = Array.from(barsContainer.children);
            const levels = [
                { label: 'Min. 8 kar.', barClass: 'bg-slate-200', textClass: 'text-slate-400' },
                { label: 'Lemah', barClass: 'bg-rose-500', textClass: 'text-rose-600' },
                { label: 'Cukup', barClass: 'bg-amber-500', textClass: 'text-amber-600' },
                { label: 'Kuat', barClass: 'bg-[#00877A]', textClass: 'text-[#00877A]' },
                { label: 'Sangat Kuat', barClass: 'bg-emerald-600', textClass: 'text-emerald-700' },
            ];

            pwdInput.addEventListener('input', function () {
                const val = pwdInput.value || '';
                let score = 0;
                if (val.length >= 8) score++;
                if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
                if (/\d/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                const level = levels[score];
                bars.forEach(function (bar, idx) {
                    bar.className = 'h-1 rounded-full transition-colors ' + (idx < score ? level.barClass : 'bg-slate-200');
                });
                strengthLabel.textContent = level.label;
                strengthLabel.className = 'text-[10px] font-semibold min-w-[64px] text-right ' + level.textClass;
            });
        }

        // File preview label
        document.querySelectorAll('input[type="file"][data-file-preview]').forEach(function (input) {
            input.addEventListener('change', function () {
                const previewEl = document.getElementById(input.getAttribute('data-file-preview'));
                if (!previewEl) return;

                if (input.files && input.files.length > 0) {
                    previewEl.textContent = input.files[0].name;
                    previewEl.classList.remove('text-slate-500', 'text-slate-400');
                    previewEl.classList.add('text-[#00877A]', 'font-semibold');
                }
            });
        });

        // Numeric filter for NIK & NPWP
        ['nik', 'npwp_perusahaan'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', function () {
                    el.value = el.value.replace(/\D/g, '').slice(0, 16);
                });
            }
        });

        // Init step (automatically opens Step 2 if Laravel validation errors are only in Step 2)
        setStep(currentStep);
    })();
</script>
@endpush
