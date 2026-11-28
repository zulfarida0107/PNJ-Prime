@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Pembayaran & Tagihan')
@section('nav', 'pembayaran')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Pembayaran &amp; Tagihan</span>
            </nav>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Pembayaran &amp; Tagihan</h1>
            <p class="text-xs text-slate-500">Daftar tagihan aktif (Pelunasan 100% tanpa DP) dan riwayat pembayaran transaksi riset BLU PNJ.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-3 py-1 text-[11px] font-semibold text-[#00877A]">
            <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
            Sinkronisasi Rekening BLU PNJ Aktif
        </span>
    </div>

    {{-- Tab Bar + Search --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('b2c.pembayaran') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-3.5 py-2 text-xs font-bold text-white shadow-xs transition-all active:scale-95">
                <span>Tagihan Aktif</span>
                <span class="rounded-full bg-white/20 px-1.5 py-0.5 tabular-nums text-[10px] font-bold">{{ $activeInvoices->count() }}</span>
            </a>
            <a href="{{ route('b2c.pembayaran.riwayat') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                <span>Riwayat Selesai</span>
                <span class="rounded-full bg-slate-100 px-1.5 py-0.5 tabular-nums text-[10px] font-bold text-slate-600">{{ $paidCount }}</span>
            </a>
        </div>

        <form action="{{ route('b2c.pembayaran') }}" method="GET" class="relative w-full sm:w-64">
            <svg width="14" height="14" class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari nomor invoice / VA..."
                   class="h-9 w-full rounded-lg border border-slate-200 bg-white pr-3 pl-9 text-xs text-slate-700 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
        </form>
    </div>

    {{-- Daftar Tagihan Aktif --}}
    @if ($activeInvoices->isEmpty())
        <div class="rounded-lg border border-slate-200/80 bg-white px-6 py-12 text-center shadow-xs">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-[#00877A]">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m9 12 2 2 4-4" />
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-slate-900">Tidak Ada Tagihan Menunggu Pembayaran</h3>
            <p class="mt-1 text-xs text-slate-500">Seluruh transaksi Anda telah lunas atau belum ada pesanan baru yang menunggu pembayaran.</p>
            <div class="mt-4 flex items-center justify-center gap-2.5">
                <a href="{{ route('b2c.katalog') }}"
                   class="rounded-lg bg-[#00877A] px-4 py-2 text-xs font-semibold text-white transition-all hover:bg-[#006E63] active:scale-95">
                    Jelajahi Katalog Riset
                </a>
                <a href="{{ route('b2c.pembayaran.riwayat') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 active:scale-95">
                    Lihat Riwayat Lunas
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($activeInvoices as $invoice)
                @php
                    $order = $invoice->order;
                    $firstItem = $order?->items?->first();
                    $product = $firstItem?->product;
                    $vaRaw = $invoice->va_number ?: '887702107411032';
                    $vaFormatted = trim(chunk_split($vaRaw, 4, ' '));
                @endphp
                <article class="overflow-hidden rounded-lg border border-slate-200/80 bg-white shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="h-1 bg-[#00877A]"></div>
                    <div class="p-5">
                        {{-- Top Header Invoice --}}
                        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-3.5">
                            <div>
                                <p class="tabular-nums text-sm font-extrabold text-slate-900">{{ $invoice->invoice_number }}</p>
                                <p class="mt-0.5 text-[11px] text-slate-500">
                                    Diterbitkan {{ $invoice->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </p>
                                @if ($invoice->due_at)
                                    <p class="mt-1 inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 6v6l4 2" />
                                        </svg>
                                        Jatuh tempo: {{ $invoice->due_at->translatedFormat('d M Y, H:i') }} WIB
                                    </p>
                                @endif
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Menunggu Pembayaran (100% Lunas)
                            </span>
                        </div>

                        {{-- Item Summary Box --}}
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-4 rounded-lg border border-slate-200/70 bg-slate-50/70 p-3.5">
                            <div class="flex items-start gap-3">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-200/80 bg-[#00877A]/10 text-[#00877A]">
                                    @if ($product?->resolved_image_url)
                                        <img src="{{ $product->resolved_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="14" rx="2" />
                                            <path d="M7 21h10M12 17v4" />
                                        </svg>
                                    @endif
                                </span>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900">{{ $product?->name ?? 'Pesanan Riset PNJ Prime' }}</h3>
                                        <span class="rounded bg-slate-200/70 px-2 py-0.5 text-[10px] font-semibold text-slate-700">
                                            {{ $product?->unit?->code ?? 'RTPU' }}
                                        </span>
                                    </div>
                                    <p class="mt-0.5 text-[11px] text-slate-500">
                                        {{ $order?->order_code }}
                                        @if ($order?->booking_date)
                                            &bull; Jadwal: {{ $order->booking_date->translatedFormat('d M Y') }} ({{ $order->booking_slot }})
                                        @else
                                            &bull; Jumlah: {{ $firstItem?->quantity ?? 1 }} Unit
                                        @endif
                                    </p>
                                    @if ($order?->notes)
                                        <p class="mt-1 line-clamp-1 text-[11px] text-[#00877A]">{{ $order->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-[11px] font-medium text-slate-500">Total Tagihan (Lunas 100%):</p>
                                <p class="tabular-nums text-xl font-extrabold text-[#00877A]">
                                    Rp {{ number_format((float) $invoice->grand_total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Virtual Account Mandiri BLU PNJ Box --}}
                        <div class="mt-4 rounded-lg border border-slate-200/80 bg-white p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-50 text-xs font-extrabold text-sky-700">BM</span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Bank Mandiri (BLU PNJ)</p>
                                        <p class="text-[11px] text-slate-500">Mandiri Virtual Account (MVA) &mdash; Tanpa DP</p>
                                    </div>
                                </div>
                                <span class="rounded-md bg-sky-50 px-2.5 py-1 text-[10px] font-semibold text-sky-700">Verifikasi Instan 24 Jam</span>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200/70 bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="text-[10px] font-medium text-slate-500">Nomor Virtual Account</p>
                                    <p class="mt-0.5 tabular-nums text-lg font-extrabold tracking-wider text-slate-900">{{ $vaFormatted }}</p>
                                </div>
                                <button type="button"
                                        data-copy-va="{{ $vaRaw }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="9" y="9" width="13" height="13" rx="2" />
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                    </svg>
                                    <span>Salin No. VA</span>
                                </button>
                            </div>

                            <div class="mt-3 space-y-1.5 text-[11px] text-slate-600">
                                <p class="font-bold text-slate-800">Petunjuk Transfer Singkat Melalui Livin' by Mandiri / ATM:</p>
                                <p><span class="mr-1.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A]/10 text-[10px] font-bold text-[#00877A]">1</span>Buka aplikasi m-Banking Livin' by Mandiri, pilih menu <strong>Bayar / Beli &gt; Multipayment</strong>.</p>
                                <p><span class="mr-1.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A]/10 text-[10px] font-bold text-[#00877A]">2</span>Masukkan Kode Perusahaan BLU PNJ: <strong class="tabular-nums">88770</strong>.</p>
                                <p><span class="mr-1.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-[#00877A]/10 text-[10px] font-bold text-[#00877A]">3</span>Masukkan Nomor VA di atas (<strong class="tabular-nums">{{ $vaFormatted }}</strong>) dan pastikan nominal tepat <strong class="tabular-nums">Rp {{ number_format((float) $invoice->grand_total, 0, ',', '.') }}</strong>.</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4 flex flex-wrap items-center justify-end gap-2.5">
                            <button type="button"
                                    data-open-qris
                                    data-invoice-number="{{ $invoice->invoice_number }}"
                                    data-invoice-amount="Rp {{ number_format((float) $invoice->grand_total, 0, ',', '.') }}"
                                    data-confirm-url="{{ route('b2c.pembayaran.konfirmasi', $invoice) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition-all hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7" />
                                    <rect x="14" y="3" width="7" height="7" />
                                    <rect x="3" y="14" width="7" height="7" />
                                    <path d="M14 14h3v3h-3zM18 18h3v3h-3z" />
                                </svg>
                                <span>Ubah Metode Pembayaran (QRIS)</span>
                            </button>

                            <form action="{{ route('b2c.pembayaran.konfirmasi', $invoice) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="va_mandiri_blu">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#00877A] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] hover:shadow-md active:scale-95">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                                        <path d="M21 3v5h-5" />
                                    </svg>
                                    <span>Cek Status &amp; Konfirmasi Lunas</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

{{-- Modal 06a: Pembayaran QRIS (100% Lunas) --}}
<div id="b2c-qris-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="qris-modal-title">
    <div class="w-full max-w-sm overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <div class="flex items-center gap-2">
                <span class="rounded bg-rose-600 px-2 py-0.5 text-[10px] font-extrabold tracking-wider text-white">QRIS</span>
                <h3 id="qris-modal-title" class="text-sm font-bold text-slate-900">Pembayaran QRIS (100% Lunas)</h3>
            </div>
            <button type="button" data-close-qris
                    class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700"
                    aria-label="Tutup modal QRIS">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-3.5 p-5">
            {{-- Nominal Box --}}
            <div class="flex items-center justify-between rounded-lg border border-slate-200/80 bg-slate-50/70 px-3.5 py-2.5">
                <div>
                    <p class="text-[10px] font-medium text-slate-500">Total Tagihan (<span id="qris-invoice-num" class="tabular-nums font-semibold"></span>)</p>
                    <p id="qris-amount" class="tabular-nums text-xl font-extrabold text-[#00877A]">Rp 0</p>
                </div>
                <span class="rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">&bull; BLU PNJ</span>
            </div>

            {{-- QRIS Code Card --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 text-center shadow-xs">
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                    <span class="text-slate-900">QRIS</span>
                    <span>STANDAR NASIONAL</span>
                </div>
                <div class="my-3 flex justify-center">
                    <svg id="qris-svg-code" width="164" height="164" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg" class="rounded bg-white">
                        <rect width="29" height="29" fill="#FFFFFF" />
                        <path fill="#0F172A" d="M2 2h7v7H2V2zm1 1v5h5V3H3zm1 1h3v3H4V4zm16-2h7v7h-7V2zm1 1v5h5V3h-5zm1 1h3v3h-3V4zM2 20h7v7H2v-7zm1 1v5h5v-5H3zm1 1h3v3H4v-3zM11 2h2v2h-2V2zm4 0h3v2h-3V2zm-4 4h4v2h-4V6zm6 0h1v3h-1V6zm-6 4h2v3h-2v-3zm4 1h3v2h-3v-2zM2 11h3v2H2v-2zm5 0h2v2H7v-2zm-5 4h2v3H2v-3zm4 1h3v2H6v-2zm5-1h3v3h-3v-3zm5-4h3v3h-3v-3zm5 0h6v2h-6v-2zm-1 4h2v3h-2v-3zm4 0h3v2h-3v-2zm-12 5h3v2h-3v-2zm5 0h2v4h-2v-4zm-4 4h3v3h-3v-3zm5 1h3v2h-3v-2zm4-5h3v3h-3v-3zm4 2h2v5h-2v-5zm-4 3h2v2h-2v-2z"/>
                        <rect x="13" y="13" width="3" height="3" rx="0.5" fill="#00877A" />
                    </svg>
                </div>
                <p class="tabular-nums text-[10px] font-medium text-slate-400">NMID: ID1020084920251 &bull; BLU PNJ</p>
            </div>

            <div class="text-center">
                <button type="button" id="download-qris-btn"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Unduh Gambar QRIS
                </button>
            </div>

            {{-- Timer --}}
            <div class="flex items-center justify-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 py-2 text-xs font-bold text-amber-700">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                </svg>
                <span>Sisa waktu: <span id="qris-countdown" class="tabular-nums">14:59</span></span>
            </div>

            <p class="text-center text-[10px] text-slate-500">Buka aplikasi m-banking atau e-wallet Anda, lalu scan QR Code di atas.</p>

            {{-- Polesan 1: Logo Brand Berwarna 8 Kanal QRIS --}}
            @include('Mahasiswa atau Dosen (B2C).partials.qris-logos')

            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button type="button" data-close-qris
                        class="rounded-lg border border-[#00877A] bg-white py-2.5 text-xs font-bold text-[#00877A] hover:bg-[#00877A]/5 active:scale-95">
                    Batal
                </button>
                <form id="qris-confirm-form" method="POST" action="#">
                    @csrf
                    <input type="hidden" name="payment_method" value="qris">
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                            <path d="M21 3v5h-5" />
                        </svg>
                        <span>Konfirmasi Lunas</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        // Salin VA
        document.querySelectorAll('[data-copy-va]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const va = btn.dataset.copyVa;
                try {
                    await navigator.clipboard.writeText(va);
                    const span = btn.querySelector('span');
                    if (span) {
                        const prev = span.textContent;
                        span.textContent = 'Tersalin!';
                        setTimeout(() => { span.textContent = prev; }, 1600);
                    }
                } catch (_) {}
            });
        });

        // Modal QRIS (06a)
        const modal = document.getElementById('b2c-qris-modal');
        const amountEl = document.getElementById('qris-amount');
        const invNumEl = document.getElementById('qris-invoice-num');
        const confirmForm = document.getElementById('qris-confirm-form');
        const countdownEl = document.getElementById('qris-countdown');
        let timerId = null;

        const startCountdown = () => {
            clearInterval(timerId);
            let secs = 14 * 60 + 59;
            const tick = () => {
                const m = String(Math.floor(secs / 60)).padStart(2, '0');
                const s = String(secs % 60).padStart(2, '0');
                if (countdownEl) countdownEl.textContent = `${m}:${s}`;
                if (secs > 0) secs--;
            };
            tick();
            timerId = setInterval(tick, 1000);
        };

        const openQris = (btn) => {
            if (amountEl) amountEl.textContent = btn.dataset.invoiceAmount;
            if (invNumEl) invNumEl.textContent = btn.dataset.invoiceNumber;
            if (confirmForm) confirmForm.action = btn.dataset.confirmUrl;
            startCountdown();
            modal?.classList.remove('hidden');
            modal?.classList.add('flex');
        };
        const closeQris = () => {
            clearInterval(timerId);
            modal?.classList.add('hidden');
            modal?.classList.remove('flex');
        };

        document.querySelectorAll('[data-open-qris]').forEach((b) => b.addEventListener('click', () => openQris(b)));
        document.querySelectorAll('[data-close-qris]').forEach((b) => b.addEventListener('click', closeQris));
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeQris();
        });

        // Unduh SVG QRIS sebagai file PNG
        document.getElementById('download-qris-btn')?.addEventListener('click', () => {
            const svg = document.getElementById('qris-svg-code');
            if (!svg) return;
            const data = new XMLSerializer().serializeToString(svg);
            const blob = new Blob([data], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `QRIS-BLU-PNJ.svg`;
            a.click();
            URL.revokeObjectURL(url);
        });
    })();
</script>
@endpush
