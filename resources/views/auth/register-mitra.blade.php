@extends('auth.layouts.auth')

@section('title', 'Pendaftaran Mitra Industri & KYC')

@section('content')
<div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg shadow-slate-200/60 border border-slate-100 overflow-hidden my-2">
    {{-- Header Card --}}
    <div class="px-6 pt-5 pb-4 text-center">
        <div class="flex justify-center mb-2.5">
            <div class="w-10 h-10 rounded-xl bg-[#E0F2F1] border border-[#00897B]/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#00897B]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12 2.25c-.41 0-.82.09-1.2.26L4.6 5.26A1.75 1.75 0 0 0 3.5 6.87v4.78c0 5.05 3.41 9.53 8.12 10.97a1.3 1.3 0 0 0 .76 0c4.71-1.44 8.12-5.92 8.12-10.97V6.87a1.75 1.75 0 0 0-1.1-1.61l-6.2-2.75a2.95 2.95 0 0 0-1.2-.26Z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#E0F2F1] border border-[#00897B]/20 text-[#00897B] text-[11px] font-semibold mb-2">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span>Portal Resmi PNJ</span>
        </div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Portal Akses &amp; Kemitraan</h1>
        <p class="mt-1 text-xs text-slate-500 leading-snug max-w-md mx-auto">
            Sistem autentikasi terverifikasi Politeknik Negeri Jakarta untuk Sivitas Akademika &amp; Mitra Industri B2B.
        </p>
    </div>

    {{-- Tab Navigasi Login / Register --}}
    <div class="grid grid-cols-2 border-b border-slate-200/80 text-xs">
        <a href="{{ route('login') }}"
           class="flex items-center justify-center gap-1.5 py-2.5 font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50/70 transition-colors">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
            <span>Login</span>
        </a>
        <a href="{{ route('register.mitra') }}"
           class="flex items-center justify-center gap-1.5 py-2.5 font-semibold text-[#00897B] border-b-2 border-[#00897B] bg-white">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <span>Register</span>
        </a>
    </div>

    {{-- Form Register & KYC --}}
    <form action="{{ route('register.mitra.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
        @csrf

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Pendaftaran Mitra Industri</h2>
                <p class="text-xs text-slate-500">
                    Lengkapi data legalitas dan profil perusahaan untuk verifikasi kemitraan industri terakreditasi.
                </p>
            </div>
        </div>

        {{-- Alert Error Global --}}
        @if ($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
                <p class="font-semibold">Mohon periksa kembali data pendaftaran Anda:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                {{ session('error') }}
            </div>
        @endif

        {{-- Grid 2 Kolom Input Dasar (Desktop) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Nama Perusahaan / Lengkap --}}
            <div>
                <label for="nama_perusahaan" class="block text-[11px] font-bold tracking-wide text-slate-600 uppercase mb-1">
                    Nama Perusahaan / Lengkap
                </label>
                <input type="text"
                       id="nama_perusahaan"
                       name="nama_perusahaan"
                       value="{{ old('nama_perusahaan') }}"
                       required
                       placeholder="PT Inovasi Teknologi Nusantara"
                       class="w-full px-3.5 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('nama_perusahaan') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-xl focus:outline-none focus:ring-2 transition-colors">
                @error('nama_perusahaan')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Kemitraan --}}
            <div>
                <label for="reg_email" class="block text-[11px] font-bold tracking-wide text-slate-600 uppercase mb-1">
                    Email Kemitraan
                </label>
                <input type="email"
                       id="reg_email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
                       placeholder="kemitraan@perusahaan.co.id"
                       class="w-full px-3.5 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-xl focus:outline-none focus:ring-2 transition-colors">
                @error('email')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Akses + Strength Meter --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="reg_password" class="block text-[11px] font-bold tracking-wide text-slate-600 uppercase">
                        Password Akses
                    </label>
                    <span class="text-[10px] font-semibold text-[#00897B]">Min. 8 Karakter &amp; Simbol</span>
                </div>
                <div class="relative">
                    <input type="password"
                           id="reg_password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••••••"
                           class="w-full pl-3.5 pr-10 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-[#00897B] focus:ring-[#00897B]/20' }} rounded-xl focus:outline-none focus:ring-2 transition-colors">
                    <button type="button"
                            data-toggle-password="reg_password"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                            aria-label="Tampilkan atau sembunyikan password">
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
                <div class="mt-2 flex items-center gap-2">
                    <div class="flex-1 grid grid-cols-4 gap-1.5" id="password-strength-bars">
                        <span class="h-1.5 rounded-full bg-slate-200 transition-colors"></span>
                        <span class="h-1.5 rounded-full bg-slate-200 transition-colors"></span>
                        <span class="h-1.5 rounded-full bg-slate-200 transition-colors"></span>
                        <span class="h-1.5 rounded-full bg-slate-200 transition-colors"></span>
                    </div>
                    <span id="password-strength-label" class="text-[11px] font-semibold text-slate-400 min-w-[68px] text-right">
                        Belum diisi
                    </span>
                </div>
                @error('password')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Telepon / WhatsApp + Toggle Enable 2FA --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="no_telepon" class="block text-[11px] font-bold tracking-wide text-slate-600 uppercase">
                        Nomor Telepon / WhatsApp
                    </label>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                        <span class="text-[10px] font-semibold text-[#00897B]">Enable 2FA</span>
                        <span class="relative inline-flex items-center">
                            <input type="checkbox"
                                   name="enable_2fa"
                                   value="1"
                                   class="sr-only peer"
                                   {{ old('enable_2fa', '1') ? 'checked' : '' }}>
                            <span class="w-8 h-[18px] bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#00897B]/30 rounded-full peer peer-checked:bg-[#00897B] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-[14px] after:w-[14px] after:transition-all peer-checked:after:translate-x-[14px]"></span>
                        </span>
                    </label>
                </div>
                <div class="flex rounded-xl overflow-hidden border {{ $errors->has('no_telepon') ? 'border-rose-400 focus-within:border-rose-500 focus-within:ring-rose-500/20' : 'border-slate-200 focus-within:border-[#00897B] focus-within:ring-[#00897B]/20' }} bg-white focus-within:ring-2 transition-colors">
                    <span class="inline-flex items-center px-3 text-xs font-bold text-slate-600 bg-slate-50 border-r border-slate-200">
                        +62
                    </span>
                    <input type="tel"
                           id="no_telepon"
                           name="no_telepon"
                           value="{{ old('no_telepon') }}"
                           required
                           placeholder="0812-3456-7890"
                           class="flex-1 px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none">
                </div>
                <p class="mt-1 text-[10px] text-slate-500">Kode OTP 2FA dikirim via WhatsApp / SMS saat login.</p>
                @error('no_telepon')
                    <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Kartu Verifikasi Legalitas & Kepatuhan (KYC) - Tahap 1/2 --}}
        <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#00897B] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span class="text-xs font-bold text-[#00897B] tracking-wide uppercase">
                        Verifikasi Legalitas &amp; Kepatuhan (KYC)
                    </span>
                </div>
                <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[10px] font-semibold text-slate-600 shrink-0">
                    Tahap 1/2
                </span>
            </div>

            {{-- Input NIK & NPWP --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="nik" class="block text-[10px] font-bold tracking-wide text-slate-600 uppercase mb-1">
                        Nomor Induk Kependudukan (NIK)
                    </label>
                    <input type="text"
                           id="nik"
                           name="nik"
                           value="{{ old('nik') }}"
                           required
                           maxlength="16"
                           inputmode="numeric"
                           placeholder="16 digit NIK penanggung jawab"
                           class="w-full px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('nik') ? 'border-rose-400 focus:border-rose-500' : 'border-slate-200 focus:border-[#00897B]' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00897B]/20 transition-colors">
                    @error('nik')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="npwp_perusahaan" class="block text-[10px] font-bold tracking-wide text-slate-600 uppercase mb-1">
                        NPWP Badan / Usaha
                    </label>
                    <input type="text"
                           id="npwp_perusahaan"
                           name="npwp_perusahaan"
                           value="{{ old('npwp_perusahaan') }}"
                           required
                           maxlength="16"
                           inputmode="numeric"
                           placeholder="16 digit NPWP"
                           class="w-full px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400 bg-white border {{ $errors->has('npwp_perusahaan') ? 'border-rose-400 focus:border-rose-500' : 'border-slate-200 focus:border-[#00897B]' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00897B]/20 transition-colors">
                    @error('npwp_perusahaan')
                        <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Upload KTP & NPWP --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label for="dokumen_ktp"
                       class="group flex items-center gap-3 p-3 rounded-xl bg-white border-2 border-dashed {{ $errors->has('dokumen_ktp') ? 'border-rose-300' : 'border-slate-200 hover:border-[#00897B]/60' }} cursor-pointer transition-colors">
                    <input type="file"
                           id="dokumen_ktp"
                           name="dokumen_ktp"
                           accept=".pdf,.jpg,.jpeg,.png"
                           required
                           data-file-preview="preview_ktp"
                           class="sr-only">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 group-hover:bg-[#E0F2F1] flex items-center justify-center text-slate-500 group-hover:text-[#00897B] transition-colors shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-bold text-slate-800">Unggah Foto KTP</span>
                        <span class="block text-[10px] text-slate-500">PDF, JPG, PNG (Maks 2MB)</span>
                        <span id="preview_ktp" class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#E0F2F1]/70 text-[#00897B] text-[10px] font-semibold max-w-full truncate">
                            Pilih Berkas
                        </span>
                    </div>
                </label>

                <label for="dokumen_npwp"
                       class="group flex items-center gap-3 p-3 rounded-xl bg-white border-2 border-dashed {{ $errors->has('dokumen_npwp') ? 'border-rose-300' : 'border-slate-200 hover:border-[#00897B]/60' }} cursor-pointer transition-colors">
                    <input type="file"
                           id="dokumen_npwp"
                           name="dokumen_npwp"
                           accept=".pdf,.jpg,.jpeg,.png"
                           required
                           data-file-preview="preview_npwp"
                           class="sr-only">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 group-hover:bg-[#E0F2F1] flex items-center justify-center text-slate-500 group-hover:text-[#00897B] transition-colors shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-bold text-slate-800">Unggah Foto NPWP</span>
                        <span class="block text-[10px] text-slate-500">PDF, JPG, PNG (Maks 2MB)</span>
                        <span id="preview_npwp" class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#E0F2F1]/70 text-[#00897B] text-[10px] font-semibold max-w-full truncate">
                            Pilih Berkas
                        </span>
                    </div>
                </label>
            </div>
            @error('dokumen_ktp')
                <p class="text-[11px] text-rose-600">{{ $message }}</p>
            @enderror
            @error('dokumen_npwp')
                <p class="text-[11px] text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kartu Verifikasi Izin Usaha - Tahap 2/2 --}}
        <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-200/80 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#00897B] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span class="text-xs font-bold text-[#00897B] tracking-wide uppercase">
                        Verifikasi Izin Usaha - Tahap 2/2
                    </span>
                </div>
                <span class="px-2 py-0.5 rounded-md bg-[#E0F2F1] border border-[#00897B]/20 text-[10px] font-semibold text-[#00897B] shrink-0">
                    Tahap 2/2
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label for="dokumen_nib"
                       class="group flex items-center gap-3 p-3 rounded-xl bg-white border-2 border-dashed {{ $errors->has('dokumen_nib') ? 'border-rose-300' : 'border-slate-200 hover:border-[#00897B]/60' }} cursor-pointer transition-colors">
                    <input type="file"
                           id="dokumen_nib"
                           name="dokumen_nib"
                           accept=".pdf,.jpg,.jpeg"
                           data-file-preview="preview_nib"
                           class="sr-only">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 group-hover:bg-[#E0F2F1] flex items-center justify-center text-slate-500 group-hover:text-[#00897B] transition-colors shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-bold text-slate-800">Unggah NIB (Nomor Induk Berusaha)</span>
                        <span class="block text-[10px] text-slate-500">Format PDF, JPG, maks. 5MB</span>
                        <span id="preview_nib" class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#E0F2F1]/70 text-[#00897B] text-[10px] font-semibold max-w-full truncate">
                            Pilih Berkas
                        </span>
                    </div>
                </label>

                <label for="dokumen_profil"
                       class="group flex items-center gap-3 p-3 rounded-xl bg-white border-2 border-dashed {{ $errors->has('dokumen_profil') ? 'border-rose-300' : 'border-slate-200 hover:border-[#00897B]/60' }} cursor-pointer transition-colors">
                    <input type="file"
                           id="dokumen_profil"
                           name="dokumen_profil"
                           accept=".pdf"
                           data-file-preview="preview_profil"
                           class="sr-only">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 group-hover:bg-[#E0F2F1] flex items-center justify-center text-slate-500 group-hover:text-[#00897B] transition-colors shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-bold text-slate-800">Unggah Profil Perusahaan</span>
                        <span class="block text-[10px] text-slate-500">Company Profile format PDF, maks. 10MB</span>
                        <span id="preview_profil" class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-md bg-[#E0F2F1]/70 text-[#00897B] text-[10px] font-semibold max-w-full truncate">
                            Pilih Berkas
                        </span>
                    </div>
                </label>
            </div>
            @error('dokumen_nib')
                <p class="text-[11px] text-rose-600">{{ $message }}</p>
            @enderror
            @error('dokumen_profil')
                <p class="text-[11px] text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Baris Persetujuan + Info Enkripsi + Tombol Daftar --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pt-1">
            <div class="space-y-1.5">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox"
                           name="agree_privacy"
                           value="1"
                           required
                           {{ old('agree_privacy') ? 'checked' : '' }}
                           class="w-3.5 h-3.5 rounded border-slate-300 text-[#00897B] focus:ring-[#00897B]/30 accent-[#00897B]">
                    <span class="text-xs text-slate-600">
                        Saya menyetujui <a href="#" class="font-semibold text-[#00897B] hover:underline">Kebijakan Privasi</a> pengelolaan data.
                    </span>
                </label>
                @error('agree_privacy')
                    <p class="text-[11px] text-rose-600">{{ $message }}</p>
                @enderror
                <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
                    <svg class="w-3.5 h-3.5 text-[#00897B] shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12 2.25c-.41 0-.82.09-1.2.26L4.6 5.26A1.75 1.75 0 0 0 3.5 6.87v4.78c0 5.05 3.41 9.53 8.12 10.97a1.3 1.3 0 0 0 .76 0c4.71-1.44 8.12-5.92 8.12-10.97V6.87a1.75 1.75 0 0 0-1.1-1.61l-6.2-2.75a2.95 2.95 0 0 0-1.2-.26Z" clip-rule="evenodd" />
                    </svg>
                    <span>Data legalitas Anda dienkripsi secara aman.</span>
                </div>
            </div>

            <button type="submit"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-[#00897B] hover:bg-[#00796B] active:bg-[#00695C] text-white text-sm font-semibold py-2.5 px-6 rounded-xl shadow-md shadow-[#00897B]/20 transition-colors shrink-0">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span>Daftar Akun PNJ Prime</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const pwdInput = document.getElementById('reg_password');
        const barsContainer = document.getElementById('password-strength-bars');
        const strengthLabel = document.getElementById('password-strength-label');

        if (pwdInput && barsContainer && strengthLabel) {
            const bars = Array.from(barsContainer.children);

            function evaluateStrength(val) {
                if (!val) return 0;
                let score = 0;
                if (val.length >= 8) score++;
                if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
                if (/\d/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;
                return score;
            }

            const levels = [
                { label: 'Belum diisi', barClass: 'bg-slate-200', textClass: 'text-slate-400' },
                { label: 'Lemah', barClass: 'bg-rose-500', textClass: 'text-rose-600' },
                { label: 'Cukup', barClass: 'bg-amber-500', textClass: 'text-amber-600' },
                { label: 'Kuat', barClass: 'bg-[#00897B]', textClass: 'text-[#00897B]' },
                { label: 'Sangat Kuat', barClass: 'bg-emerald-600', textClass: 'text-emerald-700' },
            ];

            pwdInput.addEventListener('input', function () {
                const score = evaluateStrength(pwdInput.value);
                const level = levels[score];

                bars.forEach(function (bar, idx) {
                    bar.className = 'h-1.5 rounded-full transition-colors ' + (idx < score ? level.barClass : 'bg-slate-200');
                });

                strengthLabel.textContent = level.label;
                strengthLabel.className = 'text-[11px] font-semibold min-w-[68px] text-right ' + level.textClass;
            });
        }

        document.querySelectorAll('input[type="file"][data-file-preview]').forEach(function (input) {
            input.addEventListener('change', function () {
                const previewId = input.getAttribute('data-file-preview');
                const previewEl = document.getElementById(previewId);
                if (!previewEl) return;

                if (input.files && input.files.length > 0) {
                    const file = input.files[0];
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    previewEl.textContent = file.name + ' (' + sizeMB + ' MB)';
                    previewEl.classList.remove('bg-[#E0F2F1]/70', 'text-[#00897B]');
                    previewEl.classList.add('bg-[#00897B]', 'text-white');
                } else {
                    previewEl.textContent = 'Pilih Berkas';
                    previewEl.classList.remove('bg-[#00897B]', 'text-white');
                    previewEl.classList.add('bg-[#E0F2F1]/70', 'text-[#00897B]');
                }
            });
        });

        ['nik', 'npwp_perusahaan'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', function () {
                    el.value = el.value.replace(/\D/g, '').slice(0, 16);
                });
            }
        });
    })();
</script>
@endpush
