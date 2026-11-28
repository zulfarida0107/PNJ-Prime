@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Formulir Pemesanan — ' . $product->name)
@section('nav', 'katalog')

@section('content')
@php
    $isLab = $product->order_type === 'lab_reservation';
    $basePrice = (float) $product->price_b2c;
    $initialSubtotal = $isLab ? $basePrice : ($basePrice * $defaultQty);
    $initialOpFee = ($isLab && $withOperator) ? $operatorFee : 0;
    $initialTotal = $initialSubtotal + $initialOpFee;
    $submitted = session('order_submitted');
@endphp

<div class="space-y-4">
    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-medium text-slate-400">
        <a href="{{ route('b2c.katalog') }}" class="hover:text-[#00877A]">Katalog Utama</a>
        <span>&rsaquo;</span>
        <a href="{{ route('b2c.produk.show', $product) }}" class="hover:text-[#00877A]">{{ $product->name }}</a>
        <span>&rsaquo;</span>
        <span class="font-semibold text-slate-700">Formulir Pemesanan</span>
    </nav>

    {{-- Stepper 3 Tahap --}}
    <div class="rounded-lg border border-slate-200/80 bg-white px-5 py-3.5 shadow-xs">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#00877A] tabular-nums text-xs font-bold text-white">1</span>
                <div class="leading-tight">
                    <p class="text-xs font-bold text-[#00877A]">Isi Formulir &amp; Jadwal</p>
                    <p class="text-[10px] text-slate-400">Langkah Aktif</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 tabular-nums text-xs font-bold text-slate-500">2</span>
                <div class="leading-tight">
                    <p class="text-xs font-semibold text-slate-600">Verifikasi Admin &amp; Lab</p>
                    <p class="text-[10px] text-slate-400">Penerbitan Invoice BLU</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 tabular-nums text-xs font-bold text-slate-500">3</span>
                <div class="leading-tight">
                    <p class="text-xs font-semibold text-slate-600">Pembayaran BLU (Lunas 100%)</p>
                    <p class="text-[10px] text-slate-400">Virtual Account / QRIS PNJ</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form 2 Kolom --}}
    <form action="{{ route('b2c.pemesanan.store', $product) }}" method="POST" enctype="multipart/form-data"
          class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        @csrf

        {{-- Kolom Kiri: Formulir Operasional --}}
        <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs lg:col-span-7">
            <h1 class="text-lg font-extrabold tracking-tight text-slate-900">
                {{ $isLab ? 'Formulir Reservasi Lab' : 'Formulir Pemesanan Produk Riset' }}
            </h1>
            <p class="mt-0.5 text-xs text-slate-500">Lengkapi detail operasional dan kebutuhan riset Anda untuk pencatatan logbook UPA-RTPU.</p>

            <div class="mt-4 space-y-4">
                @if ($isLab)
                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="booking_date" class="block text-xs font-bold text-slate-800">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="booking_date" name="booking_date"
                               value="{{ old('booking_date', $defaultDate) }}"
                               min="{{ now()->format('Y-m-d') }}" required
                               class="mt-1.5 h-10 w-full rounded-lg border border-slate-200 bg-white px-3 tabular-nums text-xs font-medium text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                        <p class="mt-1 text-[11px] text-slate-400">Slot mesin tersedia untuk tanggal terpilih pada {{ $product->unit?->name ?? 'Laboratorium RTPU' }}.</p>
                    </div>

                    {{-- Durasi / Sesi --}}
                    <div>
                        <label for="booking_slot" class="block text-xs font-bold text-slate-800">
                            Durasi &amp; Sesi Penggunaan <span class="text-rose-500">*</span>
                        </label>
                        <select id="booking_slot" name="booking_slot" required
                                class="mt-1.5 h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs font-medium text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                            <option value="08:00 - 12:00 WIB" {{ old('booking_slot', $defaultSlot) === '08:00 - 12:00 WIB' ? 'selected' : '' }}>
                                4 Jam (Sesi Pagi: 08:00 - 12:00 WIB)
                            </option>
                            <option value="13:00 - 17:00 WIB" {{ old('booking_slot', $defaultSlot) === '13:00 - 17:00 WIB' ? 'selected' : '' }}>
                                4 Jam (Sesi Siang: 13:00 - 17:00 WIB)
                            </option>
                        </select>
                    </div>

                    {{-- Pendampingan Operator --}}
                    <label class="flex cursor-pointer items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/70 px-3.5 py-2.5">
                        <div class="flex items-center gap-2.5">
                            <input type="checkbox" id="form-with-operator" name="with_operator" value="1"
                                   {{ old('with_operator', $withOperator) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-[#00877A] focus:ring-[#00877A]">
                            <div>
                                <p class="text-xs font-bold text-slate-800">Layanan Pendamping Teknisi Lab</p>
                                <p class="text-[11px] text-slate-500">Bantuan teknis pengoperasian mesin &amp; keselamatan kerja K3</p>
                            </div>
                        </div>
                        <span class="tabular-nums text-xs font-bold text-[#00877A]">+Rp {{ number_format($operatorFee, 0, ',', '.') }}</span>
                    </label>
                @else
                    {{-- Jumlah Unit --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="form-qty" class="block text-xs font-bold text-slate-800">
                                Jumlah Unit <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="form-qty" name="quantity"
                                   value="{{ old('quantity', $defaultQty) }}" min="1" max="{{ max(1, $product->stock) }}" required
                                   class="mt-1.5 h-10 w-full rounded-lg border border-slate-200 bg-white px-3 tabular-nums text-xs font-bold text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                            <p class="mt-1 text-[11px] text-slate-400">Stok tersedia: {{ $product->stock }} unit</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800">Estimasi Kesiapan</label>
                            <div class="mt-1.5 flex h-10 items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-600">
                                {{ $product->order_type === 'pre_order' ? 'Pre-Order (± 7 Hari Kerja)' : 'Ready Stock (1 Hari Kerja)' }}
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Pengiriman / Pengambilan --}}
                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-slate-800">
                            Alamat Pengiriman / Lokasi Pengambilan <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="shipping_address" name="shipping_address" rows="2" required
                                  class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">{{ old('shipping_address', $portalUser->address ?? 'Gedung UPA-RTPU Kampus PNJ Depok') }}</textarea>
                    </div>
                @endif

                {{-- Keperluan Riset / Judul Tugas Akhir --}}
                <div>
                    <label for="research_purpose" class="block text-xs font-bold text-slate-800">
                        Keperluan Riset / Judul Tugas Akhir <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="research_purpose" name="research_purpose"
                           value="{{ old('research_purpose') }}"
                           placeholder="Contoh: Fabrikasi Impeller Turbin Mikrohidro / Riset IoT Tugas Akhir" required
                           class="mt-1.5 h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-800 outline-none placeholder:text-slate-400 focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">
                    <p class="mt-1 text-[11px] text-slate-400">Judul akan dicatat pada logbook inventaris laboratorium RTPU &amp; PNJ Prime.</p>
                </div>

                {{-- Catatan Tambahan --}}
                <div>
                    <label for="notes" class="block text-xs font-bold text-slate-800">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Contoh: Memerlukan tool bit karbida 4 flute diameter 6mm untuk bahan duralium 6061..."
                              class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 outline-none placeholder:text-slate-400 focus:border-[#00877A] focus:ring-2 focus:ring-[#00877A]/15">{{ old('notes') }}</textarea>
                </div>

                {{-- Unggah File CAD / Gambar Kerja (Opsional) --}}
                <div>
                    <label for="attachment" class="block text-xs font-bold text-slate-800">
                        Unggah File CAD / Gambar Kerja (.STEP / .IGES / .PDF) &mdash; Opsional
                    </label>
                    <label for="attachment"
                           class="mt-1.5 flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-200 bg-slate-50/60 px-4 py-5 text-center transition-colors hover:border-[#00877A] hover:bg-[#00877A]/5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00877A]/10 text-[#00877A]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M12 18v-6M9 15l3-3 3 3" />
                            </svg>
                        </span>
                        <p class="mt-2 text-xs font-bold text-slate-800">Klik untuk unggah atau pilih berkas</p>
                        <p class="mt-0.5 text-[11px] text-slate-400">Maksimal berkas 10 MB format PDF, STEP, IGES, ZIP, JPG, atau PNG</p>
                        <span id="attachment-filename" class="mt-2 hidden rounded-full bg-[#00877A]/10 px-3 py-1 text-[11px] font-semibold text-[#00877A]"></span>
                        <input type="file" id="attachment" name="attachment" class="sr-only"
                               accept=".pdf,.step,.stp,.iges,.igs,.zip,.jpg,.jpeg,.png">
                    </label>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Pesanan & Skema Pembayaran 100% Lunas --}}
        <div class="space-y-3.5 lg:col-span-5">
            <div class="rounded-lg border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-extrabold text-slate-900">Ringkasan Pesanan</h2>
                    <span class="rounded-full bg-[#00877A]/10 px-2.5 py-0.5 text-[10px] font-bold text-[#00877A]">
                        {{ $isLab ? 'Sesi Terjadwal' : 'Tarif Sivitas' }}
                    </span>
                </div>

                {{-- Product Card Mini --}}
                <div class="mt-3 flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/70 p-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-[#00877A]/10 text-[#00877A]">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="14" rx="2" />
                            <path d="M7 21h10M12 17v4" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900">{{ $product->name }}</p>
                        <p class="mt-0.5 text-[11px] text-slate-500">{{ $product->unit?->name ?? 'UPA-RTPU PNJ' }}</p>
                        <span class="mt-1 inline-block rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-700">
                            Status: Siap Operasi
                        </span>
                    </div>
                </div>

                {{-- Rincian Biaya --}}
                <div class="mt-4 space-y-2 border-b border-slate-100 pb-3.5 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span id="summary-item-label">
                            {{ $isLab ? 'Reservasi Lab (1 Sesi)' : 'Produk Riset (' . $defaultQty . ' Unit)' }}
                        </span>
                        <span id="summary-subtotal" class="tabular-nums font-bold text-slate-900">
                            Rp {{ number_format($initialSubtotal, 0, ',', '.') }}
                        </span>
                    </div>
                    @if ($isLab)
                        <div id="summary-operator-row" class="flex items-center justify-between text-slate-600 {{ $withOperator ? '' : 'hidden' }}">
                            <span>Layanan Pendamping Teknisi Lab</span>
                            <span class="tabular-nums font-bold text-slate-900">Rp {{ number_format($operatorFee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Biaya Admin BLU PNJ</span>
                        <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">Rp 0 (Gratis)</span>
                    </div>
                </div>

                <div class="mt-3.5 flex items-end justify-between">
                    <div>
                        <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Biaya</p>
                        <p id="summary-grand-total" class="tabular-nums text-2xl font-extrabold text-[#00877A]">
                            Rp {{ number_format($initialTotal, 0, ',', '.') }}
                        </p>
                    </div>
                    <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-600">Tarif Subsidi Sivitas</span>
                </div>

                {{-- Skema Pembayaran B2C: 100% Lunas Tanpa DP 50% --}}
                <div class="mt-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-800">Skema Pembayaran B2C</p>

                    <div class="rounded-lg border-2 border-[#00877A] bg-[#00877A]/5 p-3">
                        <div class="flex items-start gap-2.5">
                            <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-4 border-[#00877A] bg-white"></span>
                            <div>
                                <p class="text-xs font-extrabold text-slate-900">Pelunasan Penuh (100% Lunas)</p>
                                <p class="mt-0.5 text-[11px] leading-relaxed text-slate-600">
                                    Sesuai aturan B2C sivitas akademika PNJ, seluruh tagihan dibayarkan lunas 100% tanpa skema DP 50%.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-sky-200/80 bg-sky-50/70 p-3">
                        <div class="flex items-start gap-2.5">
                            <svg width="16" height="16" class="mt-0.5 shrink-0 text-[#00877A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" />
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-[#006E63]">Virtual Account Bank Mandiri BLU &amp; QRIS PNJ</p>
                                <p class="mt-0.5 text-[11px] text-slate-600">
                                    Nomor VA resmi dan kode QRIS diterbitkan otomatis setelah pengajuan dikirim.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-4 py-3 text-xs font-bold text-white shadow-sm transition-all duration-200 hover:bg-[#006E63] hover:shadow-md active:scale-95">
                    <span>Ajukan Pesanan &amp; Terbitkan Tagihan</span>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>

                <p class="mt-2.5 text-center text-[10px] text-slate-400">
                    Sesuai tata kelola keuangan Badan Layanan Umum (BLU) Politeknik Negeri Jakarta.
                </p>
            </div>
        </div>
    </form>
</div>

{{-- Modal 05a: Pengajuan Reservasi / Pesanan Terkirim --}}
@if ($submitted)
<div id="b2c-submitted-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 p-4 backdrop-blur-[2px]"
     role="dialog" aria-modal="true" aria-labelledby="submitted-modal-title">
    <div class="w-full max-w-md rounded-xl border border-slate-200/80 bg-white p-6 text-center shadow-2xl">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#00877A]/10 text-[#00877A]">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m5 12 5 5L20 7" />
            </svg>
        </div>

        <h2 id="submitted-modal-title" class="mt-4 text-lg font-extrabold text-slate-900">
            {{ $submitted['order_type'] === 'lab_reservation' ? 'Pengajuan Reservasi Lab Terkirim!' : 'Pesanan Riset Berhasil Dikirim!' }}
        </h2>
        <p class="mt-1.5 text-xs leading-relaxed text-slate-500">
            Permohonan Anda (<strong class="tabular-nums text-slate-800">{{ $submitted['order_code'] }}</strong>) telah tercatat. Tagihan Virtual Account &amp; QRIS (Pelunasan 100%) kini siap dibayarkan di menu Pembayaran.
        </p>

        <div class="mt-4 space-y-2 rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 text-left text-xs">
            <div class="flex items-center justify-between gap-2">
                <span class="text-slate-500">Produk / Fasilitas:</span>
                <span class="truncate font-bold text-slate-800">{{ $submitted['product_name'] }}</span>
            </div>
            @if ($submitted['booking_date'])
                <div class="flex items-center justify-between gap-2">
                    <span class="text-slate-500">Jadwal:</span>
                    <span class="tabular-nums font-bold text-slate-800">{{ $submitted['booking_date'] }} &bull; {{ $submitted['booking_slot'] }}</span>
                </div>
            @else
                <div class="flex items-center justify-between gap-2">
                    <span class="text-slate-500">Jumlah:</span>
                    <span class="tabular-nums font-bold text-slate-800">{{ $submitted['quantity'] }} Unit</span>
                </div>
            @endif
            <div class="flex items-center justify-between gap-2 border-t border-slate-200/70 pt-2">
                <span class="text-slate-500">Total Tagihan (100% Lunas):</span>
                <span class="tabular-nums font-extrabold text-[#00877A]">Rp {{ number_format((float) $submitted['grand_total'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-5 space-y-2">
            <a href="{{ route('b2c.pembayaran') }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#00877A] px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#006E63] active:scale-95">
                <span>Lanjut ke Pembayaran BLU (VA / QRIS)</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
            <div class="flex items-center justify-center gap-4 pt-1">
                <a href="{{ route('b2c.pesanan') }}" class="text-xs font-semibold text-[#00877A] hover:underline">
                    Pantau di Pesanan Saya
                </a>
                <span class="text-slate-300">&bull;</span>
                <a href="{{ route('b2c.katalog') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
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
        const isLab = @json($isLab);
        const basePrice = @json($basePrice);
        const operatorFee = @json($operatorFee);

        const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');
        const subtotalEl = document.getElementById('summary-subtotal');
        const grandTotalEl = document.getElementById('summary-grand-total');
        const itemLabelEl = document.getElementById('summary-item-label');
        const opRowEl = document.getElementById('summary-operator-row');

        if (isLab) {
            const opCheck = document.getElementById('form-with-operator');
            const recalc = () => {
                const withOp = Boolean(opCheck?.checked);
                opRowEl?.classList.toggle('hidden', !withOp);
                if (grandTotalEl) grandTotalEl.textContent = formatRp(basePrice + (withOp ? operatorFee : 0));
            };
            opCheck?.addEventListener('change', recalc);
        } else {
            const qtyInput = document.getElementById('form-qty');
            const recalc = () => {
                const qty = Math.max(1, parseInt(qtyInput?.value || '1', 10) || 1);
                const sub = basePrice * qty;
                if (itemLabelEl) itemLabelEl.textContent = `Produk Riset (${qty} Unit)`;
                if (subtotalEl) subtotalEl.textContent = formatRp(sub);
                if (grandTotalEl) grandTotalEl.textContent = formatRp(sub);
            };
            qtyInput?.addEventListener('input', recalc);
        }

        const fileInput = document.getElementById('attachment');
        const fileNameBadge = document.getElementById('attachment-filename');
        fileInput?.addEventListener('change', () => {
            const f = fileInput.files?.[0];
            if (f && fileNameBadge) {
                fileNameBadge.textContent = `${f.name} (${(f.size / 1024 / 1024).toFixed(2)} MB) terlampir`;
                fileNameBadge.classList.remove('hidden');
            }
        });
    })();
</script>
@endpush
