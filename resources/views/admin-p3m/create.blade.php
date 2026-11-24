@extends('admin-p3m.layouts.app')

@section('title', 'Formulir Pengajuan Produk Baru')

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
                Portal P3M &bull; Pemantauan Produk Unggulan &bull; Ajukan Produk Baru
            </p>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug mt-0.5">
                Formulir Pengajuan Produk Riset &amp; Hilirisasi Baru
            </h1>
            <p class="text-[12px] text-slate-500 mt-0.5">
                Daftarkan usulan inovasi berbasis Master Data P08 untuk kurasi teknis, penelaahan HPP, dan penerbitan SK tarif resmi RTPU PNJ.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="px-3 py-1.5 rounded-full bg-[#E6F5F3] border border-[#CCFBF1] text-[#00695C] text-[11px] font-bold">
                Standar Kurasi RTPU 2026
            </span>
        </div>
    </div>

    {{-- Error Summary --}}
    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[12px]">
            <p class="font-bold mb-1">Mohon periksa kembali isian formulir:</p>
            <ul class="list-disc list-inside space-y-0.5 text-[11.5px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stepper 2 Langkah (Mockup 4 & Mockup 9) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <button type="button" id="stepBtn1" onclick="goToStep(1)"
                class="flex items-center justify-between px-4 py-3 rounded-xl border text-left transition-all active:scale-95 bg-[#00796B] border-[#00796B] text-white shadow-sm">
            <div class="flex items-center gap-3">
                <div id="stepIcon1" class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center text-[12px] font-extrabold">1</div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider opacity-85 font-semibold">Langkah 01</p>
                    <p class="text-[13px] font-extrabold">Identitas, Master Data P08 &amp; Foto</p>
                </div>
            </div>
            <span id="stepBadge1" class="px-2 py-0.5 rounded bg-white/20 text-[10px] font-bold">Sedang Diisi</span>
        </button>

        <button type="button" id="stepBtn2" onclick="goToStep(2)"
                class="flex items-center justify-between px-4 py-3 rounded-xl border text-left transition-all active:scale-95 bg-white border-slate-200 text-slate-700 hover:border-[#00796B]/50">
            <div class="flex items-center gap-3">
                <div id="stepIcon2" class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-[12px] font-extrabold">2</div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Langkah 02</p>
                    <p class="text-[13px] font-extrabold">Estimasi HPP &amp; Harga Katalog</p>
                </div>
            </div>
            <span id="stepBadge2" class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-bold">Selanjutnya</span>
        </button>
    </div>

    <form id="createProductForm" method="POST" action="{{ route('admin.p3m.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="submit_action" id="submitActionInput" value="submit">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            {{-- Kolom Kiri (Form Utama) --}}
            <div class="lg:col-span-8 space-y-4">

                {{-- ================= LANGKAH 1 ================= --}}
                <div id="stepPanel1" class="space-y-4">
                    {{-- Section A: Informasi Dasar & Master Data P08 --}}
                    <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#E6F5F3] text-[#00796B] flex items-center justify-center font-bold text-[12px]">A</div>
                                <div>
                                    <h2 class="text-[14px] font-extrabold text-slate-900">A. Informasi Dasar &amp; Master Data P08</h2>
                                    <p class="text-[11px] text-slate-500">Unit pengelola, 3 kategori baku, dan sub-kategori dari database PNJ Prime</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] text-[10px] font-bold uppercase">Wajib Diisi</span>
                        </div>

                        <div class="space-y-3.5 text-[12px]">
                            <div>
                                <label for="name" class="block font-bold text-slate-700 mb-1">Judul Produk Riset / Nama Komersial Inovasi <span class="text-rose-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="180"
                                       placeholder="Contoh: Sistem Monitoring Daya Terintegrasi Microgrid Kampus Cerdas"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="unit_id" class="block font-bold text-slate-700 mb-1">Unit Pengelola / Jurusan (Master P08) <span class="text-rose-500">*</span></label>
                                    <select id="unit_id" name="unit_id" required
                                            class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                        <option value="">-- Pilih Unit Pengelola --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" @selected((string) old('unit_id') === (string) $unit->id)>
                                                {{ $unit->name }} ({{ $unit->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="stock" class="block font-bold text-slate-700 mb-1">Stok Awal / Kapasitas Slot <span class="text-rose-500">*</span></label>
                                    <input type="number" id="stock" name="stock" value="{{ old('stock', 10) }}" required min="0"
                                           class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="category_id" class="block font-bold text-slate-700 mb-1">Kategori Baku (Barang / Jasa / Ruangan-Lab) <span class="text-rose-500">*</span></label>
                                    <select id="category_id" name="category_id" required onchange="filterSubCategories()"
                                            class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                        <option value="">-- Pilih 3 Kategori Baku --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>
                                                {{ $category->name }} ({{ $category->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="sub_category_id" class="block font-bold text-slate-700 mb-1">Sub-Kategori Produk <span class="text-rose-500">*</span></label>
                                    <select id="sub_category_id" name="sub_category_id" required
                                            class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                        <option value="">-- Pilih Sub-Kategori --</option>
                                        @foreach ($subCategories as $sc)
                                            <option value="{{ $sc->id }}" data-category="{{ $sc->category_id }}" @selected((string) old('sub_category_id') === (string) $sc->id)>
                                                {{ $sc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section B: Deskripsi Teknis & Tipe Order --}}
                    <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#E6F5F3] text-[#00796B] flex items-center justify-center font-bold text-[12px]">B</div>
                                <div>
                                    <h2 class="text-[14px] font-extrabold text-slate-900">B. Deskripsi Teknis &amp; Tipe Order</h2>
                                    <p class="text-[11px] text-slate-500">Spesifikasi fungsional, keunggulan komparatif, dan model penyediaan</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3.5 text-[12px]">
                            <div>
                                <label for="description" class="block font-bold text-slate-700 mb-1">Ringkasan Deskripsi Produk &amp; Spesifikasi Teknis <span class="text-rose-500">*</span></label>
                                <textarea id="description" name="description" rows="4" required
                                          placeholder="Jelaskan spesifikasi utama, manfaat industri, standar pengujian, dan kelengkapan paket produk..."
                                          class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-2">Pilihan Tipe Order <span class="text-rose-500">*</span></label>
                                @php $oldOrderType = old('order_type', 'ready_stock'); @endphp
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5">
                                    <label class="cursor-pointer rounded-xl border p-3 flex items-start gap-2.5 transition-all hover:border-[#00796B] has-[:checked]:border-[#00796B] has-[:checked]:bg-[#F0FDF9]">
                                        <input type="radio" name="order_type" value="ready_stock" class="mt-0.5 accent-[#00796B]" @checked($oldOrderType === 'ready_stock') onchange="updateSummary()">
                                        <div>
                                            <p class="font-bold text-slate-900 text-[12px]">Ready Stock</p>
                                            <p class="text-[10.5px] text-slate-500 mt-0.5">Stok fisik siap kirim langsung dari unit/gudang RTPU.</p>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer rounded-xl border p-3 flex items-start gap-2.5 transition-all hover:border-[#00796B] has-[:checked]:border-[#00796B] has-[:checked]:bg-[#F0FDF9]">
                                        <input type="radio" name="order_type" value="pre_order" class="mt-0.5 accent-[#00796B]" @checked($oldOrderType === 'pre_order') onchange="updateSummary()">
                                        <div>
                                            <p class="font-bold text-slate-900 text-[12px]">Pre-Order (MTO)</p>
                                            <p class="text-[10.5px] text-slate-500 mt-0.5">Fabrikasi / perakitan berdasarkan pesanan masuk.</p>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer rounded-xl border p-3 flex items-start gap-2.5 transition-all hover:border-[#00796B] has-[:checked]:border-[#00796B] has-[:checked]:bg-[#F0FDF9]">
                                        <input type="radio" name="order_type" value="lab_reservation" class="mt-0.5 accent-[#00796B]" @checked($oldOrderType === 'lab_reservation') onchange="updateSummary()">
                                        <div>
                                            <p class="font-bold text-slate-900 text-[12px]">Reservasi Lab</p>
                                            <p class="text-[10.5px] text-slate-500 mt-0.5">Jadwal penggunaan ruangan/lab atau jasa pengujian.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section C: Upload Foto Produk Maks 1MB --}}
                    <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#E6F5F3] text-[#00796B] flex items-center justify-center font-bold text-[12px]">C</div>
                                <div>
                                    <h2 class="text-[14px] font-extrabold text-slate-900">C. Foto Prototipe / Produk Riil</h2>
                                    <p class="text-[11px] text-slate-500">Unggah foto produk format JPG, PNG, atau WEBP (maksimal 1MB)</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">Maks. 1 MB</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <label for="image" class="flex-1 w-full cursor-pointer rounded-xl border-2 border-dashed border-slate-300 hover:border-[#00796B] bg-slate-50/70 hover:bg-[#F0FDF9]/50 p-5 text-center transition-all">
                                <svg class="w-8 h-8 mx-auto text-[#00796B] mb-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <p class="text-[12px] font-bold text-slate-800">Klik untuk pilih foto produk</p>
                                <p id="fileNameLabel" class="text-[11px] text-slate-500 mt-0.5">JPG, PNG, WEBP &bull; Ukuran maksimal 1MB</p>
                                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewProductImage(this)">
                            </label>

                            <div id="imagePreviewBox" class="hidden w-32 h-32 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shrink-0">
                                <img id="imagePreviewEl" src="" alt="Preview Foto" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <p id="fileSizeError" class="hidden text-[11.5px] font-semibold text-rose-600"></p>
                    </div>

                    {{-- Footer Navigasi Langkah 1 --}}
                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ route('admin.p3m.dashboard') }}"
                           class="px-4 py-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">
                            Batal &amp; Kembali
                        </a>
                        <button type="button" onclick="goToStep(2)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#00796B] hover:bg-[#00695C] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-95">
                            <span>Lanjut ke Langkah 2: Estimasi HPP</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>

                {{-- ================= LANGKAH 2 ================= --}}
                <div id="stepPanel2" class="hidden space-y-4">
                    <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs space-y-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#E6F5F3] text-[#00796B] flex items-center justify-center font-bold text-[12px]">Rp</div>
                                <div>
                                    <h2 class="text-[14px] font-extrabold text-slate-900">Penetapan Harga Katalog &amp; Estimasi HPP</h2>
                                    <p class="text-[11px] text-slate-500">Penentuan komponen biaya pokok produksi dan usulan tarif komersialisasi ke RTPU</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] text-[10px] font-bold uppercase">Wajib Diisi</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 text-[12px]">
                            <div>
                                <label for="hpp" class="block font-bold text-slate-700 mb-1">Estimasi HPP Produksi (Rp) <span class="text-rose-500">*</span></label>
                                <input type="number" id="hpp" name="hpp" value="{{ old('hpp', 1150000) }}" required min="0" step="1000" oninput="updateSummary()"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                <p class="text-[10.5px] text-slate-400 mt-1">Total BOM, jasa perakitan lab, &amp; kemasan.</p>
                            </div>

                            <div>
                                <label for="price_b2c" class="block font-bold text-slate-700 mb-1">Usulan Harga Katalog B2C (Rp) <span class="text-rose-500">*</span></label>
                                <input type="number" id="price_b2c" name="price_b2c" value="{{ old('price_b2c', 1500000) }}" required min="0" step="1000" oninput="updateSummary()"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold text-[#00796B] focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                <p class="text-[10.5px] text-slate-400 mt-1">Harga resmi pada etalase RTPU PNJ.</p>
                            </div>

                            <div>
                                <label for="price_b2b" class="block font-bold text-slate-700 mb-1">Usulan Harga Mitra B2B (Rp)</label>
                                <input type="number" id="price_b2b" name="price_b2b" value="{{ old('price_b2b', 1350000) }}" min="0" step="1000" oninput="updateSummary()"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-[#00796B]/20 focus:border-[#00796B]">
                                <p class="text-[10.5px] text-slate-400 mt-1">Opsional (otomatis -10% jika kosong).</p>
                            </div>
                        </div>

                        {{-- Live Kalkulator Margin BLU --}}
                        <div id="marginBox" class="rounded-lg bg-slate-50 border border-slate-200/80 px-4 py-3 flex flex-wrap items-center justify-between gap-2 text-[12px] tabular-nums">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800" id="marginText">Estimasi Margin Kotor: 23.3% (Rp 350.000 / unit)</span>
                            </div>
                            <span id="marginBadge" class="px-2.5 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10.5px] font-bold">
                                Sesuai Regulasi BLU &ge; 15%
                            </span>
                        </div>
                    </div>

                    {{-- Footer Submit Langkah 2 --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <button type="button" onclick="goToStep(1)"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">
                            <span>&larr;</span>
                            <span>Kembali ke Langkah 1</span>
                        </button>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="submitFormAs('draft')"
                                    class="px-4 py-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">
                                Simpan Draf
                            </button>
                            <button type="button" onclick="submitFormAs('submit')"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#00796B] hover:bg-[#00695C] text-white text-[12.5px] font-semibold shadow-sm transition-all active:scale-95">
                                <span>Kirim Pengajuan ke RTPU</span>
                                <span>&rarr;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan (Sidebar Pedoman & Simulasi SK Tarif) --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs space-y-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-[12.5px] font-extrabold uppercase tracking-wider text-slate-800">Pedoman Penentuan Tarif &amp; HPP</h3>
                    <ol class="space-y-2.5 text-[11.5px] text-slate-600 list-decimal list-inside">
                        <li><strong class="text-slate-800">Batas Minimum Margin BLU:</strong> Usulan margin kotor minimum 15% - 30% untuk remunerasi tim riset, kas lab, &amp; operasional RTPU.</li>
                        <li><strong class="text-slate-800">Standar Biaya Masukan (SBM):</strong> Honorarium teknisi mengacu pada SBM BLU PNJ TA 2026.</li>
                        <li><strong class="text-slate-800">Transparansi Pajak:</strong> Harga katalog usulan belum termasuk PPN 11% (difakturkan otomatis saat transaksi).</li>
                    </ol>
                </div>

                {{-- Simulasi Dokumen SK Tarif RTPU --}}
                <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-2.5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <h3 class="text-[12.5px] font-extrabold text-slate-900">Simulasi Dokumen SK Tarif RTPU</h3>
                    <div class="rounded-lg bg-slate-50 border border-slate-200/70 p-3 space-y-2 text-[11.5px] tabular-nums">
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Kode Registrasi:</span>
                            <span class="font-mono font-bold text-slate-800">{{ $previewCode }}</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Harga Dasar B2C:</span>
                            <span id="simPriceB2c" class="font-extrabold text-slate-900">Rp 1.500.000</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Estimasi Royalti P3M:</span>
                            <span id="simRoyalty" class="font-bold text-[#0F766E]">Rp 105.000 (30% Margin)</span>
                        </div>
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-500">Tipe Order:</span>
                            <span id="simOrderType" class="font-bold text-slate-800">Ready Stock</span>
                        </div>
                        <div class="flex justify-between gap-2 pt-1.5 border-t border-slate-200/70">
                            <span class="text-slate-500">Status Target:</span>
                            <span class="font-bold text-[#0F766E]">menunggu_kurasi_rtpu</span>
                        </div>
                    </div>
                    <p class="text-[10.5px] text-slate-400">Preview ini akan diperiksa pada Rapat Pleno Hilirisasi RTPU.</p>
                </div>

                {{-- Helpdesk --}}
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-[12.5px] font-extrabold text-slate-900">Helpdesk Sentra Hilirisasi</h3>
                        <span class="text-[10px] font-bold text-emerald-600">&bull; Online</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-lg bg-[#00796B] text-white font-bold text-[12px] flex items-center justify-center shrink-0">RT</div>
                        <div class="leading-tight">
                            <p class="text-[12px] font-bold text-slate-900">Tim Kurasi Teknis &amp; Tarif RTPU</p>
                            <p class="text-[10.5px] text-slate-500">Gedung Direktorat Lt. 2, Ruang Sentra KI</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const allSubCategories = @json($subCategoriesJson);
    const oldSubCategoryId = @json(old('sub_category_id'));

    function filterSubCategories() {
        const catId = document.getElementById('category_id').value;
        const subSelect = document.getElementById('sub_category_id');
        const currentVal = subSelect.value || oldSubCategoryId;

        subSelect.innerHTML = '<option value="">-- Pilih Sub-Kategori --</option>';

        const filtered = catId
            ? allSubCategories.filter(sc => String(sc.category_id) === String(catId))
            : allSubCategories;

        filtered.forEach(sc => {
            const opt = document.createElement('option');
            opt.value = sc.id;
            opt.textContent = sc.name;
            if (String(sc.id) === String(currentVal)) opt.selected = true;
            subSelect.appendChild(opt);
        });
    }

    function goToStep(step) {
        const p1 = document.getElementById('stepPanel1');
        const p2 = document.getElementById('stepPanel2');
        const b1 = document.getElementById('stepBtn1');
        const b2 = document.getElementById('stepBtn2');
        const badge1 = document.getElementById('stepBadge1');
        const badge2 = document.getElementById('stepBadge2');

        if (step === 1) {
            p1.classList.remove('hidden');
            p2.classList.add('hidden');
            b1.className = 'flex items-center justify-between px-4 py-3 rounded-xl border text-left transition-all active:scale-95 bg-[#00796B] border-[#00796B] text-white shadow-sm';
            b2.className = 'flex items-center justify-between px-4 py-3 rounded-xl border text-left transition-all active:scale-95 bg-white border-slate-200 text-slate-700 hover:border-[#00796B]/50';
            badge1.textContent = 'Sedang Diisi';
            badge2.textContent = 'Selanjutnya';
        } else {
            p1.classList.add('hidden');
            p2.classList.remove('hidden');
            b1.className = 'flex items-center justify-between px-4 py-3 rounded-xl border text-left transition-all active:scale-95 bg-[#F0FDF9] border-[#CCFBF1] text-[#00695C]';
            b2.className = 'flex items-center justify-between px-4 py-3 rounded-xl border text-left transition-all active:scale-95 bg-[#00796B] border-[#00796B] text-white shadow-sm';
            badge1.textContent = 'Selesai';
            badge2.textContent = 'Sedang Diisi';
            updateSummary();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function previewProductImage(input) {
        const errEl = document.getElementById('fileSizeError');
        const labelEl = document.getElementById('fileNameLabel');
        const box = document.getElementById('imagePreviewBox');
        const img = document.getElementById('imagePreviewEl');

        errEl.classList.add('hidden');
        if (!input.files || !input.files[0]) {
            box.classList.add('hidden');
            return;
        }

        const file = input.files[0];
        if (file.size > 1024 * 1024) {
            errEl.textContent = 'Ukuran file melebihi batas maksimal 1MB (' + (file.size / 1024 / 1024).toFixed(2) + ' MB). Silakan pilih gambar lain.';
            errEl.classList.remove('hidden');
            input.value = '';
            box.classList.add('hidden');
            return;
        }

        labelEl.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            box.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function updateSummary() {
        const hpp = Number(document.getElementById('hpp').value || 0);
        const b2c = Number(document.getElementById('price_b2c').value || 0);
        const profit = b2c - hpp;
        const marginPct = b2c > 0 ? ((profit / b2c) * 100) : 0;
        const royalty = Math.max(0, Math.round(profit * 0.3));

        document.getElementById('marginText').textContent =
            'Estimasi Margin Kotor: ' + marginPct.toFixed(1) + '% (' + formatRupiah(profit) + ' / unit)';

        const badge = document.getElementById('marginBadge');
        if (marginPct >= 15) {
            badge.textContent = 'Sesuai Regulasi BLU ≥ 15%';
            badge.className = 'px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10.5px] font-bold';
        } else {
            badge.textContent = 'Di bawah margin ideal BLU (15%)';
            badge.className = 'px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10.5px] font-bold';
        }

        document.getElementById('simPriceB2c').textContent = formatRupiah(b2c);
        document.getElementById('simRoyalty').textContent = formatRupiah(royalty) + ' (30% Margin)';

        const checkedOrder = document.querySelector('input[name="order_type"]:checked');
        if (checkedOrder) {
            document.getElementById('simOrderType').textContent = formatOrderType(checkedOrder.value);
        }
    }

    function submitFormAs(action) {
        document.getElementById('submitActionInput').value = action;
        const form = document.getElementById('createProductForm');
        if (!form.checkValidity()) {
            // Jika ada field wajib di langkah 1 yang kosong, kembali ke langkah 1 agar terlihat
            goToStep(1);
            setTimeout(() => form.reportValidity(), 150);
            return;
        }
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', () => {
        filterSubCategories();
        updateSummary();
    });
</script>
@endpush
