@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Checkout Ready Stock — ' . $product->name)
@section('nav', 'katalog')

@section('content')
@php
    $unitPrice = (float) $product->price_b2c;
    $maxStock = max(1, (int) $product->stock);
    $initQty = (int) old('quantity', $defaultQty);
    $initShip = old('shipping_option', 'jne_reguler');
    $initShipCost = $initShip === 'jne_reguler' ? 15000 : 0;
    $initDpp = $unitPrice * $initQty;
    $initPpn = round($initDpp * 0.11);
    $initTotal = $initDpp + $initShipCost + $initPpn;
    $isDosen = ($portalUser->role ?? 'mahasiswa') === 'dosen';
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
        <span class="font-semibold text-[#00877A]">Checkout Ready Stock</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Checkout Pembelian Langsung (Ready Stock)</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-[#00877A]/25 bg-[#00877A]/10 px-2.5 py-0.5 text-[11px] font-bold text-[#00877A]">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    Stok Terverifikasi Gudang RTPU PNJ
                </span>
            </div>
            <p class="mt-0.5 text-xs text-slate-500">Selesaikan pembelian produk riset siap kirim dengan pelunasan langsung 100% via Kas BLU Politeknik Negeri Jakarta.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200/80 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-700 shadow-xs">
            <svg width="14" height="14" class="text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13" rx="1" />
                <path d="M16 8h4l3 3v5h-7V8z" />
                <circle cx="5.5" cy="18.5" r="2.5" />
                <circle cx="18.5" cy="18.5" r="2.5" />
            </svg>
            Pengiriman Akademik / Ambil di Lab
        </span>
    </div>

    {{-- Main 2 Columns --}}
    <form action="{{ route('b2c.ready-stock.store', $product) }}" method="POST" class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        @csrf

        {{-- Kolom Kiri (8 cols): Produk + Pengiriman --}}
        <div class="space-y-4 lg:col-span-8">
            {{-- Card 1: Ringkasan Produk Ready Stock --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                <path d="M3 6h18M16 10a4 4 0 0 1-8 0" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Ringkasan Produk Ready Stock</h2>
                            <p class="text-[11px] text-slate-500">Batch Terkini Hasil Riset &amp; Produksi {{ $product->unit?->name ?? 'Unit Workshop BLU PNJ' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        Siap Kirim (Dispatch 1&ndash;2 Hari)
                    </span>
                </div>

                <div class="mt-4 flex flex-col gap-4 sm:flex-row">
                    {{-- Foto Produk (resolved_image_url) --}}
                    <div class="relative h-32 w-full shrink-0 overflow-hidden rounded-lg border border-slate-200/70 bg-gradient-to-br from-slate-100 via-[#00877A]/10 to-teal-100/70 sm:w-36">
                        @if ($product->resolved_image_url)
                            <img src="{{ $product->resolved_image_url }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-[#00877A]/70">
                                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                    <rect x="9.5" y="9.5" width="5" height="5" />
                                    <path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4" />
                                </svg>
                            </div>
                        @endif
                        <span class="absolute right-1.5 bottom-1.5 rounded bg-slate-900/75 px-1.5 py-0.5 tabular-nums text-[9px] font-bold text-white">
                            {{ $product->unit?->code ?? 'PNJ-LAB' }}
                        </span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-extrabold text-slate-900">{{ $product->name }}</h3>
                        <p class="mt-0.5 tabular-nums text-[11px] font-semibold text-slate-400">
                            SKU: {{ $product->code }} &bull; Kategori: {{ $product->category?->name ?? 'Barang Fisik' }}{{ $product->subCategory ? ' • ' . $product->subCategory->name : '' }}
                        </p>
                        <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-slate-600">
                            {{ $product->description ?: 'Paket produk riset terapan terstandarisasi hasil inovasi laboratorium Politeknik Negeri Jakarta.' }}
                        </p>

                        <div class="mt-2.5 flex flex-wrap items-center gap-3 text-[11px] font-semibold text-[#00877A]">
                            <span class="inline-flex items-center gap-1">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                                1 Tahun Garansi BLU PNJ
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                </svg>
                                Modul Panduan Praktikum Digital (PDF)
                            </span>
                        </div>

                        <div class="mt-3.5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xs font-bold text-slate-700">Jumlah Unit:</span>
                                <div class="inline-flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
                                    <button type="button" id="rs-qty-minus"
                                            class="flex h-8 w-8 items-center justify-center text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-[#00877A] active:scale-95">&minus;</button>
                                    <input type="number" id="rs-qty-input" name="quantity" value="{{ $initQty }}" min="1" max="{{ $maxStock }}"
                                           class="h-8 w-12 border-x border-slate-200 text-center tabular-nums text-xs font-extrabold text-slate-900 outline-none">
                                    <button type="button" id="rs-qty-plus"
                                            class="flex h-8 w-8 items-center justify-center text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-[#00877A] active:scale-95">+</button>
                                </div>
                                <span class="rounded-md bg-emerald-50 px-2 py-1 tabular-nums text-[10px] font-bold text-emerald-700">
                                    Tersedia {{ $maxStock }} Unit di Gudang
                                </span>
                            </div>

                            <div class="text-right">
                                <p class="text-[10px] font-medium text-slate-400">Harga Dasar Katalog</p>
                                <p class="tabular-nums text-base font-extrabold text-slate-900">
                                    Rp {{ number_format($unitPrice, 0, ',', '.') }}<span class="text-xs font-medium text-slate-400">/Unit</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Pesanan --}}
                <div class="mt-4 border-t border-slate-100 pt-3.5">
                    <div class="flex items-center justify-between">
                        <label for="rs-notes" class="text-xs font-bold text-slate-700">Catatan Pesanan / Pengiriman (Opsional)</label>
                        <span class="text-[10px] text-slate-400">Maks. 250 karakter</span>
                    </div>
                    <textarea id="rs-notes" name="notes" rows="2" maxlength="250"
                              placeholder="Contoh: Mohon sertakan panduan wiring kit praktikum dan konfirmasi WA sebelum paket tiba di Gedung C."
                              class="mt-1.5 w-full rounded-lg border border-slate-200 bg-slate-50/60 px-3 py-2 text-xs text-slate-800 outline-none transition-all focus:border-[#00877A] focus:bg-white focus:ring-2 focus:ring-[#00877A]/15">{{ old('notes') }}</textarea>
                </div>
            </section>

            {{-- Card 2: Informasi Pengiriman & Kontak Penerima --}}
            <section class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" rx="1" />
                            <path d="M16 8h4l3 3v5h-7V8z" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-900">Informasi Pengiriman &amp; Kontak Penerima</h2>
                        <p class="text-[11px] text-slate-500">Data penerima mahasiswa/dosen untuk pengiriman atau pengambilan mandiri di Unit RTPU.</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700">Nama Lengkap Penerima</label>
                        <input type="text" value="{{ $portalUser->name }}" readonly
                               class="mt-1 h-9 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700">{{ $isDosen ? 'NIP' : 'NIM / NIP' }}</label>
                        <input type="text" value="{{ $portalUser->nim_nip ?? '-' }}" readonly
                               class="mt-1 h-9 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 tabular-nums text-xs font-semibold text-slate-700">
                    </div>
                    <div>
                        <label for="rs-phone" class="block text-[11px] font-bold text-slate-700">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" id="rs-phone" name="phone" value="{{ old('phone', $portalUser->phone) }}" required
                               class="mt-1 h-9 w-full rounded-lg border border-slate-200 bg-white px-3 tabular-nums text-xs font-medium text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700">Email Kampus (SSO PNJ)</label>
                        <input type="email" value="{{ $portalUser->email }}" readonly
                               class="mt-1 h-9 w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-medium text-slate-600">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="rs-address" class="block text-[11px] font-bold text-slate-700">Alamat Lengkap Pengiriman <span class="text-rose-500">*</span></label>
                        <textarea id="rs-address" name="shipping_address" rows="2" required
                                  class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">{{ old('shipping_address', $portalUser->address ?: 'Jl. Prof. Dr. G.A. Siwabessy, Kampus UI Depok, Asrama Mahasiswa / Lab Terpadu Gedung C, Beji, Kota Depok, Jawa Barat 16424') }}</textarea>
                    </div>
                </div>

                {{-- Opsi Pengiriman / Pengambilan --}}
                <div class="mt-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-800">Opsi Pengiriman / Pengambilan</p>

                    <label class="block cursor-pointer">
                        <input type="radio" name="shipping_option" value="jne_reguler" class="peer sr-only" {{ $initShip === 'jne_reguler' ? 'checked' : '' }}>
                        <div class="flex items-start justify-between gap-3 rounded-lg border border-slate-200 bg-white p-3.5 transition-all peer-checked:border-[#00877A] peer-checked:bg-[#00877A]/[0.04] peer-checked:ring-1 peer-checked:ring-[#00877A]">
                            <div class="flex items-start gap-2.5">
                                <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-2 border-[#00877A]">
                                    <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-extrabold text-slate-900">JNE Reguler (Jabodetabek)</p>
                                        <span class="rounded bg-[#00877A]/15 px-1.5 py-0.5 text-[10px] font-bold text-[#00877A]">Rekomendasi</span>
                                    </div>
                                    <p class="mt-0.5 text-[11px] text-slate-500">Estimasi Tiba: <strong>1&ndash;2 Hari Kerja</strong></p>
                                    <p class="mt-1 text-[11px] text-slate-500">Sudah termasuk asuransi perlindungan instrumen presisi &amp; kemasan dus bubble wrap standar.</p>
                                </div>
                            </div>
                            <span class="shrink-0 tabular-nums text-xs font-extrabold text-slate-900">Rp 15.000</span>
                        </div>
                    </label>

                    <label class="block cursor-pointer">
                        <input type="radio" name="shipping_option" value="ambil_mandiri" class="peer sr-only" {{ $initShip === 'ambil_mandiri' ? 'checked' : '' }}>
                        <div class="flex items-start justify-between gap-3 rounded-lg border border-slate-200 bg-white p-3.5 transition-all peer-checked:border-[#00877A] peer-checked:bg-[#00877A]/[0.04] peer-checked:ring-1 peer-checked:ring-[#00877A]">
                            <div class="flex items-start gap-2.5">
                                <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-2 border-slate-300"></span>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-900">Ambil Mandiri di Gedung AA Lt. 2 (Unit RTPU)</p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">Siap Diambil: <strong>Hari Kerja (08.00 &ndash; 16.00 WIB)</strong></p>
                                    <p class="mt-1 text-[11px] text-slate-500">Tunjukkan e-Nota &amp; KTM/NIM saat pengambilan di loket Unit RTPU PNJ.</p>
                                </div>
                            </div>
                            <span class="shrink-0 tabular-nums text-xs font-extrabold text-[#00877A]">Rp 0</span>
                        </div>
                    </label>
                </div>

                {{-- Notice Ketentuan --}}
                <div class="mt-4 flex items-start gap-2.5 rounded-lg border border-[#00877A]/20 bg-[#00877A]/[0.05] px-3.5 py-3 text-[11px] text-slate-600">
                    <svg width="16" height="16" class="mt-0.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <div>
                        <p class="font-bold text-slate-800">Ketentuan Pembelian &amp; Penerbitan e-Nota</p>
                        <p class="mt-0.5">Sesuai tata kelola Kas BLU PNJ, pembelian Ready Stock dibayar lunas 100% di muka (tanpa DP 50%) dan otomatis menerbitkan e-Nota resmi serta kartu garansi produk.</p>
                    </div>
                </div>
            </section>
        </div>

        {{-- Kolom Kanan (4 cols): Rincian Pembayaran Sticky --}}
        <aside class="lg:col-span-4">
            <div class="sticky top-20 space-y-3.5">
                <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 2 2 2-2 2 2 3-2V8z" />
                                <path d="M14 2v6h6M8 13h6M8 17h4" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">Rincian Pembayaran</h2>
                            <p class="text-[11px] text-slate-500">Tagihan Resmi Kas BLU PNJ (B2C)</p>
                        </div>
                    </div>

                    <div class="mt-3.5 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Harga Dasar Katalog (<span id="rs-sum-qty" class="tabular-nums font-semibold">{{ $initQty }}</span> Unit)</span>
                            <span id="rs-sum-dpp" class="tabular-nums font-bold text-slate-900">Rp {{ number_format($initDpp, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span id="rs-ship-label">{{ $initShip === 'jne_reguler' ? 'Ongkos Kirim (JNE Reguler)' : 'Ambil Mandiri (Unit RTPU)' }}</span>
                            <span id="rs-sum-ship" class="tabular-nums font-bold text-slate-900">Rp {{ number_format($initShipCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>PPN 11% (UU HPP)</span>
                            <span id="rs-sum-ppn" class="tabular-nums font-bold text-slate-900">Rp {{ number_format($initPpn, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-3.5">
                        <div>
                            <p class="text-[11px] font-extrabold tracking-wide text-slate-800 uppercase">TOTAL KESELURUHAN</p>
                            <p class="text-[10px] text-slate-400">Termasuk seluruh pajak &amp; dokumen</p>
                        </div>
                        <p id="rs-sum-total" class="tabular-nums text-2xl font-extrabold text-[#00877A]">
                            Rp {{ number_format($initTotal, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <p class="text-[11px] font-bold text-slate-700">Metode Pembayaran Lunas (B2C)</p>
                        <div class="mt-1.5 flex items-start gap-2.5 rounded-lg border border-[#00877A] bg-[#00877A]/[0.05] p-3">
                            <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-2 border-[#00877A]">
                                <span class="h-2 w-2 rounded-full bg-[#00877A]"></span>
                            </span>
                            <div>
                                <p class="text-xs font-extrabold text-slate-900">QRIS Dinamis / Mandiri Virtual Account</p>
                                <p class="text-[11px] text-slate-500">Pembayaran Lunas 100% (Konfirmasi Otomatis)</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-5 py-3 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:bg-[#006E63] hover:shadow-md active:scale-95">
                        <span>Bayar Sekarang</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>

                    <p class="mt-2.5 text-center text-[10px] leading-relaxed text-slate-400">
                        Dengan menekan tombol bayar, Anda menyetujui ketentuan pelunasan penuh 100% Kas BLU PNJ.
                    </p>

                    <div class="mt-3.5 space-y-2 border-t border-slate-100 pt-3 text-[11px] text-slate-500">
                        <p class="flex items-center gap-2">
                            <svg width="14" height="14" class="shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            Transaksi dilindungi Rekening Resmi BLU PNJ
                        </p>
                        <p class="flex items-center gap-2">
                            <svg width="14" height="14" class="shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18M5 21V7l7-4 7 4v14" />
                            </svg>
                            Terintegrasi Sistem Kas Kementerian Keuangan RI
                        </p>
                    </div>
                </div>
            </div>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const unitPrice = @json($unitPrice);
        const maxStock = @json($maxStock);
        const qtyInput = document.getElementById('rs-qty-input');
        const minusBtn = document.getElementById('rs-qty-minus');
        const plusBtn = document.getElementById('rs-qty-plus');
        const shipRadios = document.querySelectorAll('input[name="shipping_option"]');

        const sumQty = document.getElementById('rs-sum-qty');
        const sumDpp = document.getElementById('rs-sum-dpp');
        const shipLabel = document.getElementById('rs-ship-label');
        const sumShip = document.getElementById('rs-sum-ship');
        const sumPpn = document.getElementById('rs-sum-ppn');
        const sumTotal = document.getElementById('rs-sum-total');

        const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

        const recalc = () => {
            let qty = parseInt(qtyInput?.value || '1', 10);
            if (isNaN(qty) || qty < 1) qty = 1;
            if (qty > maxStock) qty = maxStock;
            if (qtyInput) qtyInput.value = qty;

            const checkedShip = document.querySelector('input[name="shipping_option"]:checked')?.value || 'jne_reguler';
            const shipCost = checkedShip === 'jne_reguler' ? 15000 : 0;
            const dpp = unitPrice * qty;
            const ppn = Math.round(dpp * 0.11);
            const total = dpp + shipCost + ppn;

            if (sumQty) sumQty.textContent = qty;
            if (sumDpp) sumDpp.textContent = fmt(dpp);
            if (shipLabel) shipLabel.textContent = checkedShip === 'jne_reguler' ? 'Ongkos Kirim (JNE Reguler)' : 'Ambil Mandiri (Unit RTPU)';
            if (sumShip) sumShip.textContent = fmt(shipCost);
            if (sumPpn) sumPpn.textContent = fmt(ppn);
            if (sumTotal) sumTotal.textContent = fmt(total);
        };

        minusBtn?.addEventListener('click', () => {
            if (qtyInput) qtyInput.value = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
            recalc();
        });
        plusBtn?.addEventListener('click', () => {
            if (qtyInput) qtyInput.value = Math.min(maxStock, parseInt(qtyInput.value || '1', 10) + 1);
            recalc();
        });
        qtyInput?.addEventListener('input', recalc);
        shipRadios.forEach((r) => r.addEventListener('change', recalc));
    })();
</script>
@endpush
