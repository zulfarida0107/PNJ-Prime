@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Pengaturan Akun')
@section('nav', 'pengaturan')

@section('content')
@php
    $isDosen = $portalUser->role === 'dosen';
    $inisial = collect(explode(' ', $portalUser->name))
        ->filter()
        ->take(2)
        ->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))
        ->implode('');
@endphp

<div class="space-y-4">
    {{-- Header --}}
    <div>
        <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Pengaturan Akun</h1>
        <p class="text-xs text-slate-500">Kelola preferensi akun, data profil sivitas akademika SSO PNJ, dan alamat pengiriman pesanan Anda.</p>
    </div>

    {{-- Banner Profil Ringkas --}}
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-slate-200/80 bg-gradient-to-r from-white via-[#00877A]/[0.03] to-sky-50/40 p-5 shadow-xs">
        <div class="flex items-center gap-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#00877A] text-lg font-extrabold text-white ring-4 ring-[#00877A]/15">
                {{ $inisial }}
            </span>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-extrabold text-slate-900">{{ $portalUser->name }}</h2>
                    <span class="inline-flex items-center gap-1 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-0.5 text-[11px] font-bold text-[#00877A]">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m9 12 2 2 4-4" />
                        </svg>
                        SSO PNJ Verified
                    </span>
                </div>
                <p class="mt-0.5 text-xs text-slate-500">
                    {{ $isDosen ? 'Dosen PNJ' : 'Mahasiswa Aktif' }} &bull; {{ $portalUser->prodi_unit ?? 'Politeknik Negeri Jakarta' }}
                </p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-xs">
            <svg width="14" height="14" class="text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10 12 5 2 10l10 5 10-5z" />
                <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </svg>
            Politeknik Negeri Jakarta
        </span>
    </div>

    {{-- Card 1: Informasi Akademik (Terkunci dari SSO PNJ) --}}
    <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
        <div class="flex items-start justify-between gap-2">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Informasi Akademik</h3>
                <p class="text-[11px] text-slate-500">Identitas terverifikasi dari pangkalan data akademik Politeknik Negeri Jakarta.</p>
            </div>
            <svg width="16" height="16" class="text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
        </div>

        <div class="mt-3 flex items-center gap-2 rounded-lg border border-sky-200/70 bg-sky-50/70 px-3.5 py-2.5 text-xs text-slate-700">
            <svg width="15" height="15" class="shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            <span>Data akademik disinkronisasi langsung dari <strong>SSO Kampus PNJ</strong> dan tidak dapat diubah secara mandiri.</span>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold text-slate-700">Nama Lengkap</label>
                <input type="text" value="{{ $portalUser->name }}" readonly
                       class="mt-1.5 h-10 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700">{{ $isDosen ? 'Nomor Induk Pegawai (NIP)' : 'Nomor Induk Mahasiswa (NIM)' }}</label>
                <input type="text" value="{{ $portalUser->nim_nip ?? '-' }}" readonly
                       class="mt-1.5 h-10 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 tabular-nums text-xs font-semibold text-slate-600">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700">Program Studi / Jurusan</label>
                <input type="text" value="{{ $portalUser->prodi_unit ?? '-' }}" readonly
                       class="mt-1.5 h-10 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-600">
            </div>
        </div>
    </div>

    {{-- Card 2: Informasi Kontak & Pengiriman (Editable) --}}
    <form id="b2c-settings-form" action="{{ route('b2c.pengaturan.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
            <h3 class="text-sm font-extrabold text-slate-900">Informasi Kontak &amp; Pengiriman</h3>
            <p class="text-[11px] text-slate-500">Digunakan untuk konfirmasi pesanan, korespondensi riset, dan pengiriman purwarupa.</p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700">Email Aktif (SSO PNJ)</label>
                    <input type="email" id="email" value="{{ $portalUser->email }}" readonly
                           class="mt-1.5 h-10 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-medium text-slate-600">
                    <p class="mt-1 text-[11px] text-slate-400">Digunakan untuk notifikasi status pesanan &amp; e-Nota resmi.</p>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $portalUser->phone) }}" required
                           placeholder="Contoh: 081234567890"
                           class="mt-1.5 h-10 w-full rounded-lg border border-slate-200 bg-white px-3 tabular-nums text-xs font-medium text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    <p class="mt-1 text-[11px] text-slate-400">Untuk konfirmasi jadwal teknisi lab &amp; pengiriman produk fisik.</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-bold text-slate-700">Alamat Pengiriman / Lokasi Lab Kampus <span class="text-rose-500">*</span></label>
                    <textarea id="address" name="address" rows="3" required
                              class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">{{ old('address', $portalUser->address) }}</textarea>
                    <p class="mt-1 text-[11px] text-slate-400">Pastikan mencantumkan nomor laboratorium, ruangan, atau nama gedung penerima dengan detail.</p>
                </div>
            </div>
        </div>

        {{-- Save Footer Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200/80 bg-white px-5 py-3.5 shadow-xs">
            <p class="inline-flex items-center gap-1.5 text-[11px] text-slate-500">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                </svg>
                Terakhir diperbarui: <strong class="tabular-nums text-slate-700">{{ $portalUser->updated_at?->translatedFormat('d M Y, H:i') }} WIB</strong>
            </p>

            <button type="button" id="open-save-confirm-btn"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-5 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] hover:shadow-md active:scale-95">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />
                    <polyline points="7 3 7 8 15 8" />
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

{{-- Polesan 2A: Modal Konfirmasi Simpan Perubahan Profil --}}
<div id="settings-confirm-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="settings-confirm-title">
    <div class="w-full max-w-md overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#00877A]/10 text-[#00877A]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                    </span>
                    <div>
                        <h3 id="settings-confirm-title" class="text-base font-extrabold text-slate-900">Simpan Perubahan Profil?</h3>
                        <p class="text-[11px] text-slate-500">Pastikan informasi kontak dan alamat pengiriman berikut sudah benar.</p>
                    </div>
                </div>
                <button type="button" data-close-confirm-settings
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Tutup">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-2.5 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 text-xs">
                <div>
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Nomor WhatsApp / HP</p>
                    <p id="confirm-preview-phone" class="mt-0.5 tabular-nums font-bold text-slate-900">-</p>
                </div>
                <div class="border-t border-slate-200/70 pt-2.5">
                    <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Alamat Pengiriman / Lokasi Lab</p>
                    <p id="confirm-preview-address" class="mt-0.5 leading-relaxed font-medium text-slate-700">-</p>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-end gap-2.5">
                <button type="button" data-close-confirm-settings
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 active:scale-95">
                    Batal
                </button>
                <button type="button" id="confirm-submit-settings-btn"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#00877A] px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 5 5L20 7" />
                    </svg>
                    <span>Ya, Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Polesan 2B: Modal Pop-up Sukses Simpan Perubahan --}}
@if (session('settings_saved'))
<div id="settings-success-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="settings-success-title">
    <div class="w-full max-w-sm overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="p-6 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#00877A]/10 text-[#00877A] ring-4 ring-[#00877A]/10">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m9 12 2 2 4-4" />
                </svg>
            </div>

            <h3 id="settings-success-title" class="mt-4 text-lg font-extrabold text-slate-900">Perubahan Berhasil Disimpan</h3>
            <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
                Informasi kontak &amp; alamat pengiriman Anda telah diperbarui.
            </p>

            <button type="button" id="close-settings-success-btn"
                    class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                Mengerti / Tutup
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('b2c-settings-form');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const openBtn = document.getElementById('open-save-confirm-btn');
        const confirmModal = document.getElementById('settings-confirm-modal');
        const submitBtn = document.getElementById('confirm-submit-settings-btn');
        const previewPhone = document.getElementById('confirm-preview-phone');
        const previewAddress = document.getElementById('confirm-preview-address');

        const openConfirm = () => {
            if (form && !form.reportValidity()) return;
            if (previewPhone) previewPhone.textContent = phoneInput?.value.trim() || '-';
            if (previewAddress) previewAddress.textContent = addressInput?.value.trim() || '-';
            confirmModal?.classList.remove('hidden');
            confirmModal?.classList.add('flex');
        };
        const closeConfirm = () => {
            confirmModal?.classList.add('hidden');
            confirmModal?.classList.remove('flex');
        };

        openBtn?.addEventListener('click', openConfirm);
        document.querySelectorAll('[data-close-confirm-settings]').forEach((el) => el.addEventListener('click', closeConfirm));
        confirmModal?.addEventListener('click', (e) => {
            if (e.target === confirmModal) closeConfirm();
        });
        submitBtn?.addEventListener('click', () => {
            form?.submit();
        });

        const successModal = document.getElementById('settings-success-modal');
        const closeSuccessBtn = document.getElementById('close-settings-success-btn');
        const closeSuccess = () => successModal?.remove();
        closeSuccessBtn?.addEventListener('click', closeSuccess);
        successModal?.addEventListener('click', (e) => {
            if (e.target === successModal) closeSuccess();
        });
    })();
</script>
@endpush
