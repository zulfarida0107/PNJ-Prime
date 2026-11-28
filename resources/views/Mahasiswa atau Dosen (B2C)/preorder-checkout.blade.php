@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Checkout Pre-Order — ' . $product->name)
@section('nav', 'katalog')

@section('content')
@php
    $unitPrice = (float) $product->price_b2c;
    $initQty = (int) old('quantity', $defaultQty);
    $initSubtotal = $unitPrice * $initQty;
    $initPpn = round($initSubtotal * 0.11);
    $initEstTotal = $initSubtotal + $initPpn;
    $submitted = session('preorder_submitted');
@endphp

<div class="space-y-4">
    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-medium text-slate-400">
        <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('b2c.katalog') }}" class="hover:text-[#00877A]">Katalog Utama</a>
        <span>&rsaquo;</span>
        <a href="{{ route('b2c.produk.show', $product) }}" class="hover:text-[#00877A]">{{ $product->name }}</a>
        <span>&rsaquo;</span>
        <span class="font-semibold text-[#00877A]">Checkout Pre-Order</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 3h6M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                        <path d="M7.5 14h9" />
                    </svg>
                </span>
                <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Checkout Pre-Order B2C</h1>
            </div>
            <p class="mt-0.5 text-xs text-slate-500">Konfirmasi pengajuan Pre-Order produk riset inovasi. Produk mulai dirakit setelah e-Invoice resmi diterbitkan dan pelunasan penuh (100%) terverifikasi.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-bold text-slate-700 shadow-xs">
            <span class="text-slate-400">TIPE:</span>
            <span class="text-[#00877A]">Make-to-Order (MTO)</span>
        </span>
    </div>

    <form action="{{ route('b2c.preorder.store', $product) }}" method="POST" class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        @csrf

        {{-- Kolom Kiri (8 cols) --}}
        <div class="space-y-4 lg:col-span-8">
            {{-- Card 1: Rincian Produk Inovasi (Pre-Order) --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-extrabold text-slate-900">Rincian Produk Inovasi (Pre-Order)</h2>
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">Sistem Pre-Order</span>
                </div>

                <div class="mt-4 flex flex-col gap-4 sm:flex-row">
                    <div class="relative h-32 w-full shrink-0 overflow-hidden rounded-lg border border-slate-200/70 bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/70 sm:w-40">
                        @if ($product->resolved_image_url)
                            <img src="{{ $product->resolved_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#00877A]/70">
                                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 3h6M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                                </svg>
                            </div>
                        @endif
                        <span class="absolute top-2 left-2 rounded bg-[#00877A] px-2 py-0.5 text-[9px] font-bold text-white">Pre-Order</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="rounded bg-[#00877A]/10 px-2 py-0.5 text-[10px] font-bold text-[#00877A]">{{ $product->unit?->name ?? 'UPA-RTPU PNJ' }}</span>
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">{{ $product->category?->name ?? 'Barang Fisik' }} &bull; Pre-Order</span>
                        </div>
                        <h3 class="mt-1.5 text-base font-extrabold text-slate-900">{{ $product->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-600">
                            {{ $product->description ?: 'Produk inovasi riset terapan dirakit sesuai pesanan untuk kebutuhan praktikum, penelitian, dan tugas akhir sivitas akademika PNJ.' }}
                        </p>
                        <p class="mt-2 inline-flex items-center gap-1 text-[11px] font-semibold text-[#00877A]">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                            Lulus Kurasi Resmi Admin UPA-RTPU PNJ
                        </p>
                    </div>
                </div>

                {{-- 4 Info Tiles --}}
                <div class="mt-4 grid grid-cols-2 gap-2.5 rounded-lg border border-slate-100 bg-slate-50/70 p-3 text-xs sm:grid-cols-4">
                    <div>
                        <p class="text-[10px] text-slate-400">Estimasi Pengerjaan</p>
                        <p class="mt-0.5 font-extrabold text-slate-800">5&ndash;7 Hari Kerja</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Skema Produksi</p>
                        <p class="mt-0.5 font-extrabold text-slate-800">Make-to-Order (MTO)</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Lokasi Produksi</p>
                        <p class="mt-0.5 truncate font-extrabold text-slate-800">{{ $product->unit?->name ?? 'Lab PNJ Depok' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400">Harga Dasar Katalog</p>
                        <p class="mt-0.5 tabular-nums font-extrabold text-[#00877A]">Rp {{ number_format($unitPrice, 0, ',', '.') }}/Unit</p>
                    </div>
                </div>

                {{-- Form Spesifikasi & Catatan Pre-Order --}}
                <div class="mt-4 space-y-3.5 border-t border-slate-100 pt-4">
                    <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                        <div>
                            <label for="po-qty" class="block text-xs font-bold text-slate-800">Kuantitas Pesanan <span class="text-rose-500">*</span></label>
                            <div class="mt-1.5 inline-flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
                                <button type="button" id="po-qty-minus" class="flex h-9 w-9 items-center justify-center text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-[#00877A] active:scale-95">&minus;</button>
                                <input type="number" id="po-qty" name="quantity" value="{{ $initQty }}" min="1" max="20" required
                                       class="h-9 w-16 border-x border-slate-200 text-center tabular-nums text-xs font-extrabold text-slate-900 outline-none">
                                <button type="button" id="po-qty-plus" class="flex h-9 w-9 items-center justify-center text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-[#00877A] active:scale-95">+</button>
                            </div>
                        </div>

                        <div>
                            <label for="po-purpose" class="block text-xs font-bold text-slate-800">Keperluan Riset / Tugas Akhir <span class="text-rose-500">*</span></label>
                            <input type="text" id="po-purpose" name="research_purpose"
                                   value="{{ old('research_purpose') }}" required
                                   placeholder="Contoh: Penelitian Tugas Akhir / Praktikum Semester 7"
                                   class="mt-1.5 h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        </div>
                    </div>

                    <div>
                        <label for="po-spec" class="block text-xs font-bold text-slate-800">Spesifikasi Kustomisasi / Varian Teknis (Opsional)</label>
                        <input type="text" id="po-spec" name="custom_spec"
                               value="{{ old('custom_spec') }}"
                               placeholder="Contoh: ESP32-S3 Dual-Core, Casing Waterproof IP65 3D Print, Probe Kalibrasi Standar"
                               class="mt-1.5 h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    </div>

                    <div>
                        <label for="po-notes" class="block text-xs font-bold text-slate-800">Catatan Tambahan untuk Teknisi Lab (Opsional)</label>
                        <textarea id="po-notes" name="notes" rows="2"
                                  placeholder="Contoh: Mohon konfirmasi jika barang siap diambil langsung di lab atau dikirim ke asrama."
                                  class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </section>

            {{-- Card 2: Informasi Pemesan & Pengiriman --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <h2 class="border-b border-slate-100 pb-3 text-sm font-extrabold text-slate-900">Informasi Pemesan &amp; Pengiriman</h2>

                <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700">Nama Lengkap (SSO PNJ)</label>
                        <input type="text" value="{{ $portalUser->name }}" readonly
                               class="mt-1 h-9 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-700">
                    </div>
                    <div>
                        <label for="po-phone" class="block text-[11px] font-bold text-slate-700">Nomor WhatsApp Aktif <span class="text-rose-500">*</span></label>
                        <input type="text" id="po-phone" name="phone" value="{{ old('phone', $portalUser->phone) }}" required
                               class="mt-1 h-9 w-full rounded-lg border border-slate-200 bg-white px-3 tabular-nums text-xs font-medium text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="po-address" class="block text-[11px] font-bold text-slate-700">Alamat Pengiriman / Lokasi Pengambilan <span class="text-rose-500">*</span></label>
                        <textarea id="po-address" name="shipping_address" rows="2" required
                                  class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">{{ old('shipping_address', $portalUser->address ?: 'Asrama Mahasiswa / Lab Terpadu Gedung C, Kampus PNJ Depok') }}</textarea>
                    </div>
                </div>

                {{-- Metode Pembayaran Kas BLU Notice --}}
                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50/80 p-3.5 text-[11px] text-amber-900">
                    <p class="font-extrabold">Pembayaran menunggu penerbitan e-Invoice resmi dari Admin PNJ Prime.</p>
                    <p class="mt-1 leading-relaxed text-amber-800">
                        Tim Admin RTPU &amp; Teknisi Lab akan memeriksa kapasitas pengerjaan, mengonfirmasi estimasi waktu, serta menghitung ongkos kirim final dalam <strong>maks. 1&times;24 jam kerja</strong>. Tidak ada pembayaran di awal hingga e-Invoice resmi terbit (Pelunasan 100% tanpa DP).
                    </p>
                </div>
            </section>
        </div>

        {{-- Kolom Kanan (4 cols): Ringkasan Estimasi Biaya --}}
        <aside class="lg:col-span-4">
            <div class="sticky top-20 rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <h2 class="text-sm font-extrabold text-slate-900">Ringkasan Estimasi Biaya</h2>
                <p class="text-[11px] text-slate-500">Pratinjau sebelum penerbitan e-Invoice penagihan</p>

                <div class="mt-4 space-y-2.5 border-b border-slate-100 pb-3.5 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Komponen &amp; Perakitan (<span id="po-sum-qty" class="tabular-nums font-semibold">{{ $initQty }}</span> Unit)</span>
                        <span id="po-sum-sub" class="tabular-nums font-bold text-slate-900">Rp {{ number_format($initSubtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>PPN 11% (UU HPP)</span>
                        <span id="po-sum-ppn" class="tabular-nums font-bold text-slate-900">Rp {{ number_format($initPpn, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Ongkos Kirim Logistik</span>
                        <span class="rounded bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700">Dihitung Admin</span>
                    </div>
                </div>

                <div class="mt-3.5 flex items-end justify-between">
                    <div>
                        <p class="text-xs font-extrabold text-slate-900">Total Estimasi Pre-Order</p>
                        <p class="text-[10px] text-slate-400">Belum termasuk ongkir final</p>
                    </div>
                    <p id="po-sum-total" class="tabular-nums text-2xl font-extrabold text-[#00877A]">
                        Rp {{ number_format($initEstTotal, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mt-3 rounded-lg border border-[#00877A]/20 bg-[#00877A]/[0.05] p-3 text-[11px] text-slate-600">
                    <p class="font-bold text-[#00877A]">Pelunasan Penuh (100% Full Payment) pasca e-Invoice Resmi terbit</p>
                    <p class="mt-0.5 text-[10px]">Tanpa DP 50% &bull; Pembayaran via Mandiri VA / QRIS BLU PNJ.</p>
                </div>

                <button type="submit"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-5 py-3 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-[#006E63] hover:shadow-md active:scale-95">
                    <span>Ajukan Pre-Order</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>

                <a href="{{ route('b2c.katalog') }}"
                   class="mt-2.5 inline-flex w-full items-center justify-center rounded-lg border border-slate-200 bg-white py-2.5 text-xs font-semibold text-slate-600 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    &larr; Kembali ke Katalog Utama
                </a>
            </div>
        </aside>
    </form>
</div>

{{-- Modal 04c.1: Pengajuan Pre-Order Berhasil Dikirim! --}}
@if ($submitted)
<div id="po-submitted-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="po-submitted-title">
    <div class="w-full max-w-sm overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-2xl">
        <div class="h-1.5 bg-[#00877A]"></div>
        <div class="p-6 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#00877A]/10 text-[#00877A]">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12 5 5L20 7" />
                </svg>
            </div>

            <h3 id="po-submitted-title" class="mt-4 text-lg font-extrabold text-slate-900">Pengajuan Pre-Order Berhasil Dikirim!</h3>
            <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
                Pengajuan Anda masuk antrean verifikasi Admin RTPU. Belum ada pembayaran di muka. e-Invoice resmi akan diterbitkan setelah pengecekan kapasitas &amp; ongkir selesai.
            </p>

            <div class="mt-4 space-y-2 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 text-left text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Nomor Tiket</span>
                    <span class="rounded border border-[#00877A]/30 bg-[#00877A]/10 px-2 py-0.5 tabular-nums font-extrabold text-[#00877A]">#{{ $submitted['order_code'] }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-slate-200/70 pt-2">
                    <span class="text-slate-500">Estimasi Verifikasi</span>
                    <span class="font-bold text-slate-800">Maks. 1&times;24 Jam Kerja</span>
                </div>
            </div>

            <div class="mt-5 space-y-2">
                <a href="{{ $submitted['detail_url'] }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] py-2.5 text-xs font-bold text-white shadow-sm hover:bg-[#006E63] active:scale-95">
                    <span>Pantau Status Pre-Order</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('b2c.pesanan') }}"
                   class="inline-flex w-full items-center justify-center rounded-lg border border-slate-200 bg-white py-2 text-xs font-semibold text-slate-700 hover:border-[#00877A] hover:text-[#00877A] active:scale-95">
                    Ke Daftar Pesanan Saya
                </a>
                <a href="{{ route('b2c.katalog') }}" class="block pt-1 text-[11px] font-medium text-slate-500 hover:text-[#00877A]">
                    Kembali ke Katalog Utama
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        const unitPrice = @json($unitPrice);
        const qtyInput = document.getElementById('po-qty');
        const minusBtn = document.getElementById('po-qty-minus');
        const plusBtn = document.getElementById('po-qty-plus');
        const sumQty = document.getElementById('po-sum-qty');
        const sumSub = document.getElementById('po-sum-sub');
        const sumPpn = document.getElementById('po-sum-ppn');
        const sumTotal = document.getElementById('po-sum-total');

        const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

        const recalc = () => {
            let q = parseInt(qtyInput?.value || '1', 10);
            if (isNaN(q) || q < 1) q = 1;
            if (q > 20) q = 20;
            if (qtyInput) qtyInput.value = q;
            const sub = unitPrice * q;
            const ppn = Math.round(sub * 0.11);
            if (sumQty) sumQty.textContent = q;
            if (sumSub) sumSub.textContent = fmt(sub);
            if (sumPpn) sumPpn.textContent = fmt(ppn);
            if (sumTotal) sumTotal.textContent = fmt(sub + ppn);
        };

        minusBtn?.addEventListener('click', () => {
            if (qtyInput) qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
            recalc();
        });
        plusBtn?.addEventListener('click', () => {
            if (qtyInput) qtyInput.value = Math.min(20, parseInt(qtyInput.value || '1', 10) + 1);
            recalc();
        });
        qtyInput?.addEventListener('input', recalc);
    })();
</script>
@endpush
