@extends('layouts.mitra')

@section('title', 'Dashboard Mitra')
@section('nav', 'dashboard')

@section('content')
<div class="mx-auto max-w-6xl space-y-8">

    {{-- Header Dashboard --}}
    <div class="flex flex-wrap items-start justify-between gap-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="max-w-2xl">
            <p class="flex items-center gap-2 text-[11px] font-semibold tracking-wide text-slate-500 uppercase">
                <span class="text-tosca">Portal Kolaborasi Industri PNJ</span>
                <span class="h-1.5 w-1.5 rounded-full bg-tosca"></span>
                <span class="font-medium normal-case">Tahun Ajaran 2024/2025</span>
            </p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Selamat datang, PT Inovasi Teknologi Nusantara.</h1>
            <p class="mt-2 text-sm text-slate-600">Pantau perkembangan kolaborasi riset terapan, hilirisasi industri, dan pemanfaatan fasilitas lab Politeknik Negeri Jakarta dalam satu pintu.</p>
        </div>

        <div class="flex items-center gap-4 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3">
            <svg class="h-5 w-5 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" /></svg>
            <div class="leading-tight">
                <p class="text-[10px] font-bold tracking-wide text-amber-600 uppercase">Status Akun Mitra</p>
                <p class="mt-0.5 text-xs font-semibold text-amber-900">Menunggu Verifikasi Dokumen Legalitas</p>
            </div>
            <a href="#" class="text-xs font-bold text-orange-700 underline underline-offset-2 hover:text-orange-800">Unggah Ulang</a>
        </div>
    </div>

    {{-- Grid Statistik --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Card 1 --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-700">Pengajuan Aktif</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6M9 13h6M9 17h4" /></svg>
                </span>
            </div>
            <p class="mt-4 flex items-baseline gap-1.5"><span class="text-4xl font-extrabold text-slate-900">0</span><span class="text-xs text-slate-500">Inisiatif</span></p>
            <p class="mt-4 flex items-center gap-1.5 border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10" /><path d="M12 16v-4M12 8h.01" /></svg>
                Belum ada proposal terkirim
            </p>
        </div>

        {{-- Card 2 --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-700">Riset Didanai</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8" /><path d="M3 22h18" /><path d="M14 22a7 7 0 1 0 0-14h-1" /><path d="M9 14h2" /><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2z" /><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3" /></svg>
                </span>
            </div>
            <p class="mt-4 flex items-baseline gap-1.5"><span class="text-4xl font-extrabold text-slate-900">0</span><span class="text-xs text-slate-500">Proyek</span></p>
            <p class="mt-4 flex items-center gap-1.5 border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10" /><path d="M8 12h.01M12 12h.01M16 12h.01" /></svg>
                Menunggu pendanaan skema padanan
            </p>
        </div>

        {{-- Card 3 --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-700">Fasilitas Lab Tersewa</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-5" /><path d="M8 17h8l-1-5H9z" /><circle cx="12" cy="9" r="2" /><path d="M5 5l4 4M19 3l-6 6" /></svg>
                </span>
            </div>
            <p class="mt-4 flex items-baseline gap-1.5"><span class="text-4xl font-extrabold text-slate-900">0</span><span class="text-xs text-slate-500">Jam</span></p>
            <p class="mt-4 flex items-center gap-1.5 border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                Lab Pengujian &amp; Manufaktur PNJ
            </p>
        </div>

        {{-- Card 4 --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-700">MoU &amp; PKS Aktif</p>
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="2" /><path d="M9 8h6M9 12h6M9 16h4" /></svg>
                </span>
            </div>
            <p class="mt-4 flex items-baseline gap-1.5"><span class="text-4xl font-extrabold text-slate-900">0</span><span class="text-xs text-slate-500">Dokumen</span></p>
            <p class="mt-4 flex items-center gap-1.5 border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="m8.5 12 2.5 2.5 4.5-5" /></svg>
                Legalitas kemitraan industri
            </p>
        </div>
    </div>

    {{-- Empty State --}}
    <section class="rounded-2xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-teal-50">
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm">
                <svg class="h-8 w-8 text-tosca" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z" />
                    <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z" />
                    <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0" />
                    <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5" />
                </svg>
            </span>
        </div>

        <h2 class="mt-8 text-2xl font-extrabold text-slate-900">Belum ada aktivitas kemitraan.</h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-600">Mulai jelajahi katalog riset kami atau ajukan proposal kerja sama untuk memulai kolaborasi inovasi bersama civitas akademika Politeknik Negeri Jakarta.</p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            <a href="#" class="inline-flex items-center gap-2 rounded-lg border-2 border-tosca bg-white px-5 py-2.5 text-sm font-semibold text-tosca transition hover:bg-tosca-soft">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2z" /><path d="M22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z" /></svg>
                Jelajahi Katalog Riset
            </a>
            <a href="#" class="inline-flex items-center gap-2 rounded-lg bg-tosca px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-tosca-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10" /><path d="M12 8v8M8 12h8" /></svg>
                Buat Pengajuan Baru
            </a>
        </div>

        <p class="mt-8 inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2" /><path d="M8 11V7a4 4 0 0 1 8 0v4" /></svg>
            Sistem dijamin oleh Unit Pengelola Kerja Sama &amp; Riset Terapan PNJ
        </p>
    </section>

    {{-- Panduan Cepat --}}
    <section>
        <div class="flex flex-wrap items-end justify-between gap-2">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Panduan Cepat Memulai Kolaborasi</h3>
                <p class="text-xs text-slate-500">Ikuti 3 tahapan integrasi kemitraan untuk mempercepat validasi riset dan kontrak industri.</p>
            </div>
            <a href="#" class="inline-flex items-center gap-1 text-xs font-bold text-tosca hover:underline">
                Buku Panduan Mitra (PDF)
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
            </a>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-3">
            {{-- Langkah 1 --}}
            <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-teal-100 text-xs font-bold text-tosca">1</span>
                    <span class="rounded border border-amber-300 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-orange-600">Perlu Tindakan</span>
                </div>
                <h4 class="mt-4 text-sm font-bold text-slate-900">1. Lengkapi Legalitas Akun</h4>
                <p class="mt-2 flex-1 text-xs leading-relaxed text-slate-600">Unggah dokumen NIB, Akta Pendirian, dan NPWP Perusahaan untuk memverifikasi profil institusi secara sah.</p>
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-3 text-[11px]">
                    <span class="font-medium text-slate-500">Estimasi: 5 Menit</span>
                    <a href="#" class="font-bold text-tosca hover:underline">Lengkapi Sekarang &gt;</a>
                </div>
            </div>

            {{-- Langkah 2 --}}
            <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">2</span>
                    <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">Riset Terapan</span>
                </div>
                <h4 class="mt-4 text-sm font-bold text-slate-900">2. Telusuri Paten Siap Pakai</h4>
                <p class="mt-2 flex-1 text-xs leading-relaxed text-slate-600">Eksplorasi lebih dari 120+ prototipe TRL 6-9 dari 7 Jurusan Rekayasa dan Tata Niaga untuk diadopsi langsung oleh bisnis Anda.</p>
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-3 text-[11px]">
                    <span class="font-medium text-slate-500">Katalog 2026</span>
                    <a href="#" class="font-bold text-tosca hover:underline">Buka Direktori &gt;</a>
                </div>
            </div>

            {{-- Langkah 3 --}}
            <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">3</span>
                    <span class="rounded border border-teal-200 bg-teal-50 px-2 py-0.5 text-[10px] font-semibold text-tosca">Fasilitasi 1-on-1</span>
                </div>
                <h4 class="mt-4 text-sm font-bold text-slate-900">3. Konsultasi Desk Industri</h4>
                <p class="mt-2 flex-1 text-xs leading-relaxed text-slate-600">Jadwalkan sesi daring bersama Liaison Officer Riset Industri PNJ untuk mencocokkan kebutuhan R&amp;D dengan tim pakar akademik.</p>
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-3 text-[11px]">
                    <span class="font-medium text-slate-500">Tersedia via Zoom</span>
                    <a href="#" class="font-bold text-tosca hover:underline">Jadwalkan Sesi &gt;</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-200 pt-6 text-[11px] text-slate-500">
        <p class="max-w-md">&copy; 2026 Politeknik Negeri Jakarta (PNJ) Prime B2B Portal. Seluruh hak cipta dilindungi undang-undang.</p>
        <div class="flex items-center gap-4 font-medium">
            <a href="#" class="hover:text-tosca">Kebijakan Privasi</a>
            <span class="h-1 w-1 rounded-full bg-slate-400"></span>
            <a href="#" class="hover:text-tosca">Syarat &amp; Ketentuan Lisensi</a>
            <span class="h-1 w-1 rounded-full bg-slate-400"></span>
            <a href="#" class="hover:text-tosca">Helpdesk Sentra KI</a>
        </div>
    </footer>
</div>
@endsection
