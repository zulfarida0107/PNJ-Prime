@extends('admin-p3m.layouts.app')

@section('title', 'Revisi Pengajuan Produk #' . $product->code)

@section('content')
@php
    $subCategoriesJson = $subCategories->map(fn ($sc) => [
        'id' => $sc->id,
        'category_id' => $sc->category_id,
        'name' => $sc->name,
        'code' => $sc->code,
    ])->values();
@endphp

<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <p class="text-[10.5px] font-bold uppercase tracking-wider text-[#00796B]">
                Portal P3M &bull; Pemantauan Produk Unggulan &bull; Revisi Pengajuan
            </p>
            <div class="flex flex-wrap items-center gap-2.5 mt-0.5">
                <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug">
                    Revisi Pengajuan Produk (#{{ $product->code }})
                </h1>
                @if ($product->status === 'revisi')
                    <span class="px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-bold">&bull; Perlu Perbaikan</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-bold">Mode Sunting Usulan</span>
                @endif
            </div>
            <p class="text-[12px] text-slate-500 mt-0.5">
                Perbaiki butir catatan evaluasi Admin/Pimpinan RTPU dan kirimkan kembali usulan untuk telaah ulang SK tarif komersialisasi.
            </p>
        </div>

        <span class="px-3 py-1.5 rounded-full bg-[#E6F5F3] border border-[#CCFBF1] text-[#00695C] text-[11px] font-bold shrink-0">
            Standar Kurasi RTPU 2026
        </span>
    </div>

    {{-- Banner Catatan Revisi / Penolakan RTPU (Persis Mockup 5) --}}
    <div class="rounded-xl bg-rose-50/90 border border-rose-200 px-4 py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <p class="text-[11px] font-extrabold uppercase tracking-wider text-rose-700">Catatan Evaluasi Admin / Pimpinan RTPU:</p>
                    <span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 text-[9.5px] font-bold">Evaluasi Kurasi</span>
                </div>
                <p class="text-[12.5px] font-medium text-rose-900 mt-1 leading-relaxed">
                    "{{ $product->curation_notes ?: 'Mohon periksa kelengkapan spesifikasi teknis, penyesuaian HPP, dan unggah dokumentasi foto produk terbaru.' }}"
                </p>
            </div>
        </div>
        <div class="shrink-0 px-3 py-1.5 rounded-lg bg-white border border-rose-200 text-[11px] font-bold text-rose-700">
            Batas Waktu Perbaikan: 14 Hari Kerja
        </div>
    </div>

    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[12px]">
            <p class="font-bold mb-1">Mohon periksa kembali isian revisi:</p>
            <ul class="list-disc list-inside space-y-0.5 text-[11.5px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="revisiProductForm" method="POST" action="{{ route('admin.p3m.revisi.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="submit_action" id="revisiSubmitAction" value="submit">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            {{-- Kolom Kiri --}}
            <div class="lg:col-span-8 space-y-4">
                {{-- A. Identitas Produk & Master Data P08 --}}
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-[14px] font-extrabold text-slate-900">A. Identitas Produk &amp; Klasifikasi Master Data P08</h2>
                            <p class="text-[11px] text-slate-500">Perbarui nama inovasi, unit pengelola, kategori baku, sub-kategori, dan tipe order</p>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] text-[10px] font-bold uppercase">Terverifikasi P3M</span>
                    </div>

                    <div class="space-y-3.5 text-[12px]">
                        <div>
                            <label for="rev_name" class="block font-bold text-slate-700 mb-1">Nama Produk / Inovasi <span class="text-rose-500">*</span></label>
                            <input type="text" id="rev_name" name="name" value="{{ old('name', $product->name) }}" required maxlength="180"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label for="rev_unit_id" class="block font-bold text-slate-700 mb-1">Unit Pengelola <span class="text-rose-500">*</span></label>
                                <select id="rev_unit_id" name="unit_id" required
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" @selected((string) old('unit_id', $product->unit_id) === (string) $unit->id)>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="rev_category_id" class="block font-bold text-slate-700 mb-1">Kategori Baku <span class="text-rose-500">*</span></label>
                                <select id="rev_category_id" name="category_id" required onchange="filterRevisiSubCategories()"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="rev_sub_category_id" class="block font-bold text-slate-700 mb-1">Sub-Kategori <span class="text-rose-500">*</span></label>
                                <select id="rev_sub_category_id" name="sub_category_id" required
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                    @foreach ($subCategories as $sc)
                                        <option value="{{ $sc->id }}" @selected((string) old('sub_category_id', $product->sub_category_id) === (string) $sc->id)>
                                            {{ $sc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label for="rev_order_type" class="block font-bold text-slate-700 mb-1">Tipe Order <span class="text-rose-500">*</span></label>
                                @php $curOrder = old('order_type', $product->order_type); @endphp
                                <select id="rev_order_type" name="order_type" required
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                    <option value="ready_stock" @selected($curOrder === 'ready_stock')>Ready Stock</option>
                                    <option value="pre_order" @selected($curOrder === 'pre_order')>Pre-Order / Make-to-Order</option>
                                    <option value="lab_reservation" @selected($curOrder === 'lab_reservation')>Reservasi Lab / Jasa Pengujian</option>
                                </select>
                            </div>
                            <div>
                                <label for="rev_stock" class="block font-bold text-slate-700 mb-1">Stok / Kapasitas Slot <span class="text-rose-500">*</span></label>
                                <input type="number" id="rev_stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                            </div>
                        </div>

                        <div>
                            <label for="rev_description" class="block font-bold text-slate-700 mb-1">Deskripsi Produk &amp; Kelengkapan Spesifikasi <span class="text-rose-500">*</span></label>
                            <textarea id="rev_description" name="description" rows="3" required
                                      class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- B. Penetapan Harga & Estimasi HPP --}}
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="pb-3 border-b border-slate-100">
                        <h2 class="text-[14px] font-extrabold text-slate-900">B. Perbaikan Estimasi HPP &amp; Harga Katalog</h2>
                        <p class="text-[11px] text-slate-500">Sesuaikan komponen biaya pokok dan harga usulan sesuai catatan evaluasi RTPU</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-[12px]">
                        <div>
                            <label for="rev_hpp" class="block font-bold text-slate-700 mb-1">Estimasi HPP (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" id="rev_hpp" name="hpp" value="{{ old('hpp', (int) $product->hpp) }}" required min="0" step="1000" oninput="updateRevisiMargin()"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                        </div>
                        <div>
                            <label for="rev_price_b2c" class="block font-bold text-slate-700 mb-1">Harga Katalog B2C (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" id="rev_price_b2c" name="price_b2c" value="{{ old('price_b2c', (int) $product->price_b2c) }}" required min="0" step="1000" oninput="updateRevisiMargin()"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold text-[#00796B] focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                        </div>
                        <div>
                            <label for="rev_price_b2b" class="block font-bold text-slate-700 mb-1">Harga Mitra B2B (Rp)</label>
                            <input type="number" id="rev_price_b2b" name="price_b2b" value="{{ old('price_b2b', (int) $product->price_b2b) }}" min="0" step="1000"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                        </div>
                    </div>

                    <div class="rounded-lg bg-slate-50 border border-slate-200/80 px-4 py-2.5 flex flex-wrap items-center justify-between gap-2 text-[12px] tabular-nums">
                        <span id="revMarginText" class="font-bold text-slate-800">Estimasi Margin Kotor: -</span>
                        <span id="revMarginBadge" class="px-2.5 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10.5px] font-bold">Sesuai Regulasi BLU</span>
                    </div>
                </div>

                {{-- C. Foto Perbaikan & Catatan Tanggapan --}}
                <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-[14px] font-extrabold text-slate-900">C. Foto Produk &amp; Tanggapan Perbaikan</h2>
                            <p class="text-[11px] text-slate-500">Unggah foto terbaru (maks. 1MB) dan tulis ringkasan perbaikan untuk Admin RTPU</p>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">Foto Maks. 1 MB</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4 text-[12px]">
                        <div class="w-24 h-24 rounded-xl border border-slate-200 bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center">
                            @if ($product->image_path && file_exists(public_path('storage/' . $product->image_path)))
                                <img id="revImgPreview" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <img id="revImgPreview" src="" alt="Preview" class="hidden w-full h-full object-cover">
                                <span id="revImgPlaceholder" class="text-[10.5px] text-slate-400 text-center px-2">Belum ada foto</span>
                            @endif
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="image" id="rev_image" accept="image/jpeg,image/png,image/webp" onchange="previewRevisiImage(this)"
                                   class="block w-full text-[11.5px] text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-[11.5px] file:font-semibold file:bg-[#E6F5F3] file:text-[#00695C] hover:file:bg-[#CCFBF1]">
                            <p id="revFileError" class="hidden text-[11px] font-semibold text-rose-600 mt-1"></p>
                            <p class="text-[10.5px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti foto produk saat ini.</p>
                        </div>
                    </div>

                    <div class="text-[12px]">
                        <label for="revision_response" class="block font-bold text-slate-700 mb-1">Catatan Tanggapan Perbaikan untuk RTPU</label>
                        <textarea id="revision_response" name="revision_response" rows="2"
                                  placeholder="Contoh: Rincian HPP instalasi lapangan telah diperbarui dan dokumentasi foto perangkat IP67 telah dilampirkan."
                                  class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">{{ old('revision_response') }}</textarea>
                    </div>
                </div>

                {{-- Footer Aksi Revisi --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                    <button type="button" onclick='openCancelModal(@json(route("admin.p3m.cancel", $product)), @json($product->name))'
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-[12px] font-semibold active:scale-95">
                        <span>&times; Batalkan Pengajuan</span>
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="submitRevisiAs('draft')"
                                class="px-4 py-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">
                            Simpan Perubahan Draf
                        </button>
                        <button type="button" onclick="submitRevisiAs('submit')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#00796B] hover:bg-[#00695C] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-95">
                            <span>Kirim Ulang Revisi ke RTPU</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-[12.5px] font-extrabold text-slate-900">Simulasi Dokumen SK Tarif RTPU</h3>
                    <div class="rounded-lg bg-slate-50 border border-slate-200/70 p-3 space-y-2 text-[11.5px] tabular-nums">
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Nomor Registrasi:</span>
                            <span class="font-mono font-bold text-[#0F766E]">{{ $product->code }}/REV-1</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Harga Unit Dasar:</span>
                            <span id="revSimPrice" class="font-extrabold text-slate-900">Rp {{ number_format((float) $product->price_b2c, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Estimasi Royalti P3M:</span>
                            <span id="revSimRoyalty" class="font-bold text-[#0F766E]">-</span>
                        </div>
                        <div class="flex justify-between gap-2 pt-1.5 border-t border-slate-200/70">
                            <span class="text-slate-500">Status Pengajuan Ulang:</span>
                            <span class="font-bold text-[#0F766E]">menunggu_kurasi_rtpu</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-[12.5px] font-extrabold uppercase tracking-wider text-slate-800">Pedoman Penentuan Tarif &amp; HPP</h3>
                    <ul class="space-y-2 text-[11.5px] text-slate-600 list-disc list-inside">
                        <li>Margin kotor ideal BLU PNJ berkisar <strong>15% - 30%</strong>.</li>
                        <li>Pastikan rincian biaya instalasi / pengujian sudah tercakup dalam HPP.</li>
                        <li>Setelah dikirim ulang, usulan masuk antrean prioritas kurasi RTPU.</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const revSubCategories = @json($subCategoriesJson);
    const initialRevSubId = @json(old('sub_category_id', $product->sub_category_id));

    function filterRevisiSubCategories() {
        const catId = document.getElementById('rev_category_id').value;
        const subSelect = document.getElementById('rev_sub_category_id');
        const currentVal = subSelect.value || initialRevSubId;

        subSelect.innerHTML = '';
        const filtered = revSubCategories.filter(sc => String(sc.category_id) === String(catId));
        filtered.forEach(sc => {
            const opt = document.createElement('option');
            opt.value = sc.id;
            opt.textContent = sc.name;
            if (String(sc.id) === String(currentVal)) opt.selected = true;
            subSelect.appendChild(opt);
        });
    }

    function updateRevisiMargin() {
        const hpp = Number(document.getElementById('rev_hpp').value || 0);
        const b2c = Number(document.getElementById('rev_price_b2c').value || 0);
        const profit = b2c - hpp;
        const pct = b2c > 0 ? ((profit / b2c) * 100) : 0;
        const royalty = Math.max(0, Math.round(profit * 0.3));

        document.getElementById('revMarginText').textContent =
            'Estimasi Margin Kotor: ' + pct.toFixed(1) + '% (' + formatRupiah(profit) + ' / unit)';

        const badge = document.getElementById('revMarginBadge');
        if (pct >= 15) {
            badge.textContent = 'Sesuai Regulasi BLU ≥ 15%';
            badge.className = 'px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10.5px] font-bold';
        } else {
            badge.textContent = 'Margin di bawah 15%';
            badge.className = 'px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10.5px] font-bold';
        }

        document.getElementById('revSimPrice').textContent = formatRupiah(b2c);
        document.getElementById('revSimRoyalty').textContent = formatRupiah(royalty) + ' (30% Margin)';
    }

    function previewRevisiImage(input) {
        const err = document.getElementById('revFileError');
        const img = document.getElementById('revImgPreview');
        const placeholder = document.getElementById('revImgPlaceholder');
        err.classList.add('hidden');

        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        if (file.size > 1024 * 1024) {
            err.textContent = 'Ukuran gambar melebihi 1MB. Silakan pilih file yang lebih kecil.';
            err.classList.remove('hidden');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function submitRevisiAs(action) {
        document.getElementById('revisiSubmitAction').value = action;
        const form = document.getElementById('revisiProductForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
        filterRevisiSubCategories();
        updateRevisiMargin();
    });
</script>
@endpush
