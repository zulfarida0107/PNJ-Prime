@extends('mitra.layouts.app')

@section('title', 'Profil Perusahaan Mitra B2B')
@section('nav', 'profil')
@section('search_placeholder', 'Cari data legalitas, NIB, NPWP, PIC, atau alamat...')

@php
    $companyName = $mitra->company_name ?: 'PT Inovasi Teknologi Nusantara';
    $initialsCompany = collect(preg_split('/\s+/', trim($companyName)))
        ->filter(fn ($w) => ! in_array(strtoupper($w), ['PT', 'CV', 'UD', 'TBK']))
        ->take(3)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
    $initialsCompany = $initialsCompany !== '' ? $initialsCompany : 'ITN';

    $kycStatus = $mitra->kyc_status ?? 'approved';
    $kycMeta = match ($kycStatus) {
        'approved' => [
            'label' => 'Approved — Terverifikasi PNJ',
            'short' => 'Approved',
            'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'dot' => 'bg-emerald-500',
        ],
        'rejected' => [
            'label' => 'Ditolak — Perlu Perbaikan Berkas',
            'short' => 'Rejected',
            'badge' => 'border-rose-200 bg-rose-50 text-rose-700',
            'dot' => 'bg-rose-500',
        ],
        default => [
            'label' => 'Pending — Menunggu Verifikasi KYC',
            'short' => 'Pending',
            'badge' => 'border-amber-200 bg-amber-50 text-amber-700',
            'dot' => 'bg-amber-500',
        ],
    };
@endphp

@section('content')
<div class="space-y-4">
    {{-- ===================================================================== --}}
    {{-- BREADCRUMB & PAGE HEADER                                              --}}
    {{-- ===================================================================== --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <nav class="mb-1 flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#00897b]">Beranda</a>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span>Pengaturan</span>
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                <span class="font-semibold text-[#00897b]">Profil Perusahaan</span>
            </nav>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pengaturan Akun &amp; Profil Perusahaan</h1>
            <p class="mt-0.5 text-xs text-slate-500">
                Kelola identitas entitas korporat, legalitas NIB/NPWP, kontak penanggung jawab (PIC), dan alamat penagihan B2B.
            </p>
        </div>

        <span class="inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-semibold {{ $kycMeta['badge'] }}">
            <span class="h-2 w-2 rounded-full {{ $kycMeta['dot'] }}"></span>
            Status KYC: {{ $kycMeta['label'] }}
        </span>
    </div>

    {{-- ===================================================================== --}}
    {{-- TABS NAVIGASI COMPACT                                                 --}}
    {{-- ===================================================================== --}}
    <div class="border-b border-slate-200">
        <div class="flex items-center gap-6 text-xs">
            <button type="button" data-profil-tab="profil"
                    class="profil-tab-btn flex items-center gap-2 border-b-2 border-[#00897b] pb-2.5 font-bold text-[#00897b]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>
                </svg>
                Profil Perusahaan &amp; PIC
            </button>
            <button type="button" data-profil-tab="pajak"
                    class="profil-tab-btn flex items-center gap-2 border-b-2 border-transparent pb-2.5 font-medium text-slate-500 hover:text-slate-800">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <path d="m9 15 2 2 4-4"/>
                </svg>
                Kepatuhan Pajak &amp; KYC
            </button>
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- TAB 1: PROFIL PERUSAHAAN & KONTAK PIC                                 --}}
    {{-- ===================================================================== --}}
    <div id="tab-panel-profil" class="space-y-4">
        {{-- Corporate Banner Card --}}
        <section data-search-item
                 data-search-text="{{ strtolower($companyName . ' ' . ($mitra->nib ?? '') . ' ' . ($mitra->npwp ?? '') . ' ' . $kycStatus) }}"
                 class="rounded-lg border border-slate-200/80 bg-white p-5">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3.5">
                    <div class="relative flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-[#00897b]/25 bg-[#e6f7f5] text-base font-extrabold text-[#00897b]">
                        {{ $initialsCompany }}
                        @if ($kycStatus === 'approved')
                            <span class="absolute -right-1 -bottom-1 flex h-5 w-5 items-center justify-center rounded-full bg-[#00998a] text-white ring-2 ring-white" title="Terverifikasi">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-sm font-bold text-slate-900">{{ $companyName }}</h2>
                            <span class="rounded bg-[#e6f7f5] px-2 py-0.5 text-[10px] font-bold text-[#007266]">
                                Mitra B2B Korporat
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">
                            NIB: <span class="tabular-nums font-semibold text-slate-700">{{ $mitra->nib ?: '-' }}</span>
                            &bull;
                            NPWP: <span class="tabular-nums font-semibold text-slate-700">{{ $mitra->npwp ?: '-' }}</span>
                        </p>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200/80 bg-slate-50/80 px-4 py-2.5 text-right">
                    <p class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Status Sertifikasi Mitra</p>
                    <p class="mt-0.5 flex items-center justify-end gap-1.5 text-xs font-bold text-slate-900">
                        <span class="h-2 w-2 rounded-full {{ $kycMeta['dot'] }}"></span>
                        Mitra Industri Terakreditasi PNJ
                    </p>
                    <p class="mt-0.5 tabular-nums text-[10px] font-semibold text-slate-500">
                        KYC: {{ strtoupper($kycStatus) }} &bull; Skema 100% Lunas
                    </p>
                </div>
            </div>

            {{-- Form Data Legalitas & Kontak PIC --}}
            <form method="POST" action="{{ route('b2b.profil.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                @if ($errors->any())
                    <div class="rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs text-rose-700">
                        <ul class="list-disc pl-4 space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {{-- Nama Perusahaan (Readonly Legal) --}}
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Nama Perusahaan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   value="{{ $companyName }}"
                                   readonly
                                   class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 pr-9 text-xs font-semibold text-slate-700 outline-none">
                            @if ($kycStatus === 'approved')
                                <span class="absolute top-1/2 right-2.5 -translate-y-1/2 text-[#00897b]" title="Tervalidasi KYC">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                        <path d="m9 12 2 2 4-4"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">Nama resmi badan usaha terdaftar pada KYC PNJ Prime.</p>
                    </div>

                    {{-- Status Verifikasi KYC --}}
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Status Verifikasi KYC
                        </label>
                        <div class="flex h-9 items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $kycStatus === 'approved' ? 'text-emerald-700' : ($kycStatus === 'rejected' ? 'text-rose-700' : 'text-amber-700') }}">
                                <span class="h-2 w-2 rounded-full {{ $kycMeta['dot'] }}"></span>
                                {{ $kycMeta['label'] }}
                            </span>
                            <span class="tabular-nums rounded bg-white px-2 py-0.5 text-[10px] font-bold uppercase text-slate-600 border border-slate-200">
                                {{ $kycStatus }}
                            </span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">Diverifikasi oleh Admin UPA-RTPU Politeknik Negeri Jakarta.</p>
                    </div>

                    {{-- NIB --}}
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            Nomor Induk Berusaha (NIB) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               value="{{ $mitra->nib ?: '-' }}"
                               readonly
                               class="tabular-nums h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-700 outline-none">
                        <p class="mt-1 text-[11px] text-slate-400">Identitas perizinan berusaha OSS RBA perusahaan.</p>
                    </div>

                    {{-- NPWP --}}
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">
                            NPWP Perusahaan (16 Digit) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               value="{{ $mitra->npwp ?: '-' }}"
                               readonly
                               class="tabular-nums h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-700 outline-none">
                        <p class="mt-1 text-[11px] text-slate-400">Digunakan untuk penerbitan Faktur Pajak PPN 11% &amp; bukti potong PPh 23.</p>
                    </div>

                    {{-- Kontak PIC: Nama PIC --}}
                    <div>
                        <label for="pic_name" class="mb-1 block text-xs font-semibold text-slate-700">
                            Nama Penanggung Jawab (PIC) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="pic_name"
                               name="name"
                               value="{{ old('name', $mitra->name) }}"
                               required
                               class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none transition focus:border-[#00998a] focus:ring-2 focus:ring-[#00998a]/15">
                    </div>

                    {{-- Kontak PIC: Email & Telepon --}}
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-700">
                                Email Kemitraan PIC
                            </label>
                            <input type="email"
                                   value="{{ $mitra->email }}"
                                   readonly
                                   class="h-9 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-medium text-slate-600 outline-none">
                        </div>
                        <div>
                            <label for="pic_phone" class="mb-1 block text-xs font-semibold text-slate-700">
                                Telepon / WhatsApp PIC <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="pic_phone"
                                   name="phone"
                                   value="{{ old('phone', $mitra->phone) }}"
                                   required
                                   class="tabular-nums h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none transition focus:border-[#00998a] focus:ring-2 focus:ring-[#00998a]/15">
                        </div>
                    </div>
                </div>

                {{-- Alamat Penagihan --}}
                <div>
                    <label for="billing_address" class="mb-1 block text-xs font-semibold text-slate-700">
                        Alamat Penagihan &amp; Korespondensi Resmi <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="billing_address"
                              name="address"
                              rows="2"
                              required
                              class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 outline-none transition focus:border-[#00998a] focus:ring-2 focus:ring-[#00998a]/15">{{ old('address', $mitra->address) }}</textarea>
                    <p class="mt-1 text-[11px] text-slate-400">Alamat ini dicantumkan pada Invoice BLU, Faktur Pajak, dan dokumen BAST.</p>
                </div>

                {{-- Compliance Notice --}}
                <div class="flex items-start gap-2.5 rounded-lg border border-slate-200/80 bg-slate-50 px-3.5 py-2.5 text-[11px] text-slate-600">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#00897b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <p>
                        <strong class="text-slate-800">Kepatuhan Data Institusi:</strong> Perubahan pada Nama Perusahaan, NIB, dan NPWP memerlukan verifikasi ulang berkas legalitas oleh Admin UPA-RTPU PNJ maksimal 2&times;24 jam kerja.
                    </p>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3.5">
                    <p class="tabular-nums text-[11px] text-slate-400">
                        Sinkronisasi akun: {{ $mitra->email }}
                    </p>
                    <div class="flex items-center gap-2">
                        <button type="reset"
                                class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 active:scale-95">
                            Batal
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-[#00998a] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#007d71] active:scale-95">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Simpan Perubahan PIC &amp; Alamat
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>

    {{-- ===================================================================== --}}
    {{-- TAB 2: KEPATUHAN PAJAK & KYC                                          --}}
    {{-- ===================================================================== --}}
    <div id="tab-panel-pajak" class="hidden space-y-4">
        <section data-search-item
                 data-search-text="ppn 11% pph 23 e-meterai bast faktur pajak 100% lunas kyc approved"
                 class="rounded-lg border border-slate-200/80 bg-white p-5 space-y-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Profil Kepatuhan Pajak &amp; Billing Komersial B2B</h2>
                <p class="mt-0.5 text-xs text-slate-500">
                    Ringkasan kesiapan administrasi perpajakan transaksi B2B untuk entitas <strong class="text-slate-700">{{ $companyName }}</strong>.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5">
                    <p class="text-[11px] font-bold text-slate-900">PPN 11%</p>
                    <p class="mt-1 text-[11px] text-slate-500">Faktur Pajak keluaran mengacu pada NPWP <span class="tabular-nums font-semibold text-slate-700">{{ $mitra->npwp ?: '-' }}</span>.</p>
                </div>
                <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5">
                    <p class="text-[11px] font-bold text-slate-900">PPh Pasal 23 (2%)</p>
                    <p class="mt-1 text-[11px] text-slate-500">Dipotong pada transaksi jasa pengujian dan sewa fasilitas laboratorium PNJ.</p>
                </div>
                <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5">
                    <p class="text-[11px] font-bold text-slate-900">e-Meterai Rp 10.000</p>
                    <p class="mt-1 text-[11px] text-slate-500">Dibubuhkan otomatis pada invoice resmi BLU PNJ skema 100% Lunas.</p>
                </div>
                <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5">
                    <p class="text-[11px] font-bold text-slate-900">BAST Resmi</p>
                    <p class="mt-1 text-[11px] text-slate-500">Berita Acara Serah Terima diterbitkan atas nama PIC <span class="font-semibold text-slate-700">{{ $mitra->name }}</span>.</p>
                </div>
            </div>
        </section>
    </div>

    {{-- ===================================================================== --}}
    {{-- INFO FOOTER CARDS                                                     --}}
    {{-- ===================================================================== --}}
    <section class="grid grid-cols-1 gap-3.5 md:grid-cols-2">
        <div data-search-item
             data-search-text="kesiapan dokumen pajak ppn 11 pph 23 e-meterai bast 100% lunas"
             class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#e6f7f5] text-[#00897b]">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Kesiapan Pajak &amp; Skema 100% Lunas</h3>
                    <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                        Data NPWP &amp; alamat penagihan di atas otomatis dipakai untuk penerbitan Faktur Pajak PPN 11%, PPh 23, e-Meterai, dan BAST resmi.
                    </p>
                </div>
            </div>
        </div>

        <div data-search-item
             data-search-text="layanan mitra pnj helpdesk kemitraan@pnj.ac.id asistensi legal"
             class="rounded-lg border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <path d="M12 17h.01"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Layanan Bantuan Mitra B2B PNJ</h3>
                    <p class="mt-1 text-[11px] leading-relaxed text-slate-500">
                        Hubungi Desk Layanan Mitra UPA-RTPU di <strong class="text-slate-700">kemitraan@pnj.ac.id</strong> untuk pembaruan akta legalitas atau asistensi faktur pajak.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const tabBtns = document.querySelectorAll('.profil-tab-btn');
        const panelProfil = document.getElementById('tab-panel-profil');
        const panelPajak = document.getElementById('tab-panel-pajak');

        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = btn.getAttribute('data-profil-tab');

                tabBtns.forEach(function (b) {
                    b.classList.remove('border-[#00897b]', 'font-bold', 'text-[#00897b]');
                    b.classList.add('border-transparent', 'font-medium', 'text-slate-500');
                });
                btn.classList.add('border-[#00897b]', 'font-bold', 'text-[#00897b]');
                btn.classList.remove('border-transparent', 'font-medium', 'text-slate-500');

                if (panelProfil && panelPajak) {
                    panelProfil.classList.toggle('hidden', target !== 'profil');
                    panelPajak.classList.toggle('hidden', target !== 'pajak');
                }
            });
        });
    })();
</script>
@endpush
