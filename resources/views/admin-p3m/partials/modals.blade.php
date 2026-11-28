{{-- =======================================================================
     1. MODAL SUKSES PENGAJUAN PRODUK (Mockup 6)
     Otomatis tampil saat session('submitted_product') tersedia.
     ======================================================================= --}}
@if (session('submitted_product'))
    @php $sp = session('submitted_product'); @endphp
    <div id="modalSubmitSuccess" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-[490px] p-6 relative animate-[fadeIn_0.2s_ease-out]">
            <button type="button" onclick="document.getElementById('modalSubmitSuccess').remove()"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1 rounded-lg transition-colors active:scale-95">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

            <div class="text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-[#E6F5F3] border border-[#CCFBF1] flex items-center justify-center mb-3.5">
                    <svg class="w-7 h-7 text-[#00796B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <h3 class="text-[19px] font-extrabold text-slate-900 tracking-tight">
                    {{ !empty($sp['is_revision']) ? 'Revisi Pengajuan Berhasil Dikirim!' : 'Pengajuan Produk Berhasil Dikirim!' }}
                </h3>
                <p class="mt-1 text-[12px] text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Usulan produk inovasi telah masuk ke antrean verifikasi Admin Sentra RTPU Politeknik Negeri Jakarta.
                </p>
            </div>

            {{-- Ringkasan Usulan --}}
            <div class="mt-4 rounded-xl bg-slate-50/80 border border-slate-200/80 p-3.5 space-y-2.5 text-[12px]">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">ID Pengajuan</span>
                    <span class="px-2 py-0.5 rounded bg-white border border-slate-200 font-mono font-bold text-slate-800 text-[11px]">#{{ $sp['code'] }}</span>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <span class="text-slate-500 shrink-0">Nama Produk</span>
                    <span class="font-bold text-slate-900 text-right">{{ $sp['name'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Usulan Harga Katalog</span>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-[#00796B]">Rp {{ number_format($sp['price_b2c'], 0, ',', '.') }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] text-[10px] font-semibold border border-[#CCFBF1]">
                            {{ $sp['order_type'] === 'ready_stock' ? 'Ready Stock' : ($sp['order_type'] === 'pre_order' ? 'Pre-Order' : 'Reservasi Lab') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                    <span class="text-slate-500">Status Saat Ini</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 font-semibold text-[11px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Menunggu Review Admin RTPU
                    </span>
                </div>
            </div>

            {{-- Alur Tahapan Kurasi & Validasi --}}
            <div class="mt-4">
                <div class="flex items-center justify-between text-[10.5px] font-bold uppercase tracking-wider mb-3">
                    <span class="text-slate-400">Alur Tahapan Kurasi &amp; Validasi</span>
                    <span class="text-[#00796B] normal-case">Tahap 2 dari 3</span>
                </div>
                <div class="grid grid-cols-3 gap-2 relative">
                    <div class="text-center">
                        <div class="w-7 h-7 mx-auto rounded-full bg-[#00796B] text-white flex items-center justify-center mb-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <p class="text-[11px] font-bold text-slate-800">1. Diajukan P3M</p>
                        <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[9.5px] font-semibold">Selesai</span>
                    </div>
                    <div class="text-center">
                        <div class="w-7 h-7 mx-auto rounded-full bg-white border-2 border-[#00796B] text-[#00796B] flex items-center justify-center mb-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <p class="text-[11px] font-bold text-slate-800">2. Verifikasi RTPU</p>
                        <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 text-[9.5px] font-semibold">Sedang Diproses</span>
                    </div>
                    <div class="text-center">
                        <div class="w-7 h-7 mx-auto rounded-full bg-slate-100 text-slate-400 text-[11px] font-bold flex items-center justify-center mb-1.5">3</div>
                        <p class="text-[11px] font-medium text-slate-500">3. Persetujuan &amp; Tayang</p>
                        <span class="inline-block mt-0.5 text-[9.5px] text-slate-400">Menunggu</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-2.5">
                <a href="{{ route('admin.p3m.dashboard') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <span>Kembali ke Dashboard</span>
                </a>
                <button type="button" onclick="document.getElementById('modalSubmitSuccess').remove()"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-[#00796B] hover:bg-[#00695C] text-white text-[12px] font-semibold shadow-sm transition-all active:scale-95">
                    <span>Pantau Status Pengajuan</span>
                    <span>&rarr;</span>
                </button>
            </div>
        </div>
    </div>
@endif

{{-- =======================================================================
     2. MODAL DETAIL ARSIP & LEGALITAS PRODUK HILIRISASI (Mockup 8 - Tayang)
     ======================================================================= --}}
<div id="modalApprovedArchive" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-[780px] max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="sticky top-0 bg-white px-5 py-3.5 border-b border-slate-100 flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#E6F5F3] text-[#00796B] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15l2 2 4-4"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-[15px] font-extrabold text-slate-900">Detail Arsip &amp; Legalitas Produk Hilirisasi</h3>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10.5px] font-bold">&check; Tayang di Katalog</span>
                    </div>
                    <p class="text-[11px] text-slate-500">
                        <span id="appCode" class="font-mono font-bold text-[#00796B]">#PRD</span> &bull; Terbit Resmi SK Direktur PNJ
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalApprovedArchive')" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 active:scale-95">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Ringkasan Identitas Produk --}}
                <div class="rounded-xl border border-slate-200/80 p-4 space-y-2.5">
                    <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#00796B]"></span>
                        Ringkasan Identitas Produk
                    </p>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Nama Inovasi Produk</p>
                        <p id="appName" class="text-[13.5px] font-extrabold text-slate-900 mt-0.5">-</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-2.5 border border-slate-100">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Unit Pengelola &amp; Kategori</p>
                        <p id="appUnit" class="text-[12px] font-bold text-slate-800 mt-0.5">-</p>
                        <p id="appCategory" class="text-[11px] text-[#00796B] font-medium">-</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-2.5 border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Pengusul &amp; Tipe Order</p>
                            <p id="appSubmitter" class="text-[11.5px] font-semibold text-slate-800 mt-0.5">-</p>
                        </div>
                        <span id="appOrderType" class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] text-[10.5px] font-bold">-</span>
                    </div>
                    <div class="rounded-lg bg-emerald-50/70 border border-emerald-200/70 p-2.5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-emerald-700 font-bold">Status Validasi RTPU</p>
                            <p class="text-[11.5px] font-extrabold text-slate-900">Lolos Kurasi &amp; Siap Komersial</p>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-white border border-emerald-200 text-emerald-700 text-[10px] font-bold">SNI / BLU Valid</span>
                    </div>
                </div>

                {{-- Penetapan Tarif & Etalase BLU --}}
                <div class="rounded-xl border border-slate-200/80 p-4 space-y-3">
                    <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Penetapan Tarif &amp; Etalase BLU
                    </p>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-2.5">
                            <p class="text-[10px] text-slate-500 font-medium">Harga Resmi Katalog (B2C)</p>
                            <p id="appPriceB2c" class="text-[16px] font-extrabold text-[#00796B] mt-0.5">Rp 0</p>
                            <p id="appPriceB2b" class="text-[10px] text-slate-500 mt-0.5">B2B: Rp 0</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-100 p-2.5">
                            <p class="text-[10px] text-slate-500 font-medium">HPP &amp; Margin BLU</p>
                            <p id="appHpp" class="text-[14px] font-extrabold text-slate-800 mt-0.5">Rp 0</p>
                            <p id="appMargin" class="text-[10.5px] font-bold text-emerald-600 mt-0.5">Margin: 0%</p>
                        </div>
                    </div>
                    <div class="rounded-lg border border-slate-100 p-3 space-y-1.5 text-[11.5px]">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Nomor SK Tarif Direktur:</span>
                            <span id="appSkNumber" class="font-mono font-bold text-slate-800">SK-RTPU/PNJ/2026</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Tanggal Pengesahan SK:</span>
                            <span id="appUpdatedAt" class="font-semibold text-slate-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Kode Etalase B2B/B2C:</span>
                            <span id="appCatalogCode" class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] font-mono font-bold text-[10.5px]">#KAT-2026</span>
                        </div>
                    </div>
                    <p class="text-[10.5px] italic text-slate-500 leading-relaxed">
                        *Produk ini telah memperoleh otorisasi distribusi resmi dan dapat ditransaksikan melalui kontrak B2B maupun katalog B2C PNJ Prime.
                    </p>
                </div>
            </div>

            {{-- Riwayat Timeline Kurasi & Putusan --}}
            <div class="rounded-xl border border-slate-200/80 p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Riwayat Timeline Kurasi &amp; Putusan</p>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold">Selesai &amp; Diarsipkan</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-[11.5px]">
                    <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                        <span class="inline-flex w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold items-center justify-center mb-1.5">1</span>
                        <p class="font-bold text-slate-800">Usulan Masuk P3M</p>
                        <p class="text-[10.5px] text-slate-500 mt-0.5">Kelengkapan administrasi, Master Data P08, dan HPP terverifikasi.</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                        <span class="inline-flex w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold items-center justify-center mb-1.5">2</span>
                        <p class="font-bold text-slate-800">Sidang Pleno RTPU</p>
                        <p id="appNotes" class="text-[10.5px] text-slate-500 mt-0.5">Rekomendasi penetapan tarif disetujui tanpa catatan revisi.</p>
                    </div>
                    <div class="rounded-lg bg-[#E6F5F3]/70 border border-[#CCFBF1] p-3">
                        <span class="inline-flex w-5 h-5 rounded-full bg-[#00796B] text-white text-[10px] font-bold items-center justify-center mb-1.5">3</span>
                        <p class="font-bold text-[#00695C]">SK Tarif &amp; Publikasi</p>
                        <p class="text-[10.5px] text-slate-600 mt-0.5">Pengesahan SK Direktur PNJ dan tayang aktif di katalog daring.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between gap-3 rounded-b-2xl">
            <button type="button" onclick="closeModal('modalApprovedArchive')" class="text-[12px] font-semibold text-slate-600 hover:text-slate-900">Tutup Jendela</button>
            <div class="flex items-center gap-2">
                <a id="appPrintSkLink" href="#" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#0F766E] hover:bg-[#115E59] text-white text-[12px] font-semibold shadow-sm transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    <span>Cetak Salinan SK Resmi (.PDF)</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =======================================================================
     3. MODAL DETAIL ARSIP & RISALAH PENOLAKAN USULAN (Mockup 7 - Ditolak)
     ======================================================================= --}}
<div id="modalRejectedArchive" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-[780px] max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="sticky top-0 bg-white px-5 py-3.5 border-b border-slate-100 flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><line x1="9" y1="15" x2="15" y2="9"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-[15px] font-extrabold text-slate-900">Detail Arsip &amp; Risalah Penolakan Usulan</h3>
                        <span id="rejBadge" class="px-2 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[10.5px] font-bold">&times; Ditolak Permanen</span>
                    </div>
                    <p class="text-[11px] text-slate-500">
                        <span id="rejCode" class="font-mono font-bold text-rose-600">#PROP</span> &bull; Berkas Arsip Putusan Sidang Kurasi RTPU
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalRejectedArchive')" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 active:scale-95">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Ringkasan Identitas Produk --}}
                <div class="rounded-xl border border-slate-200/80 p-4 space-y-2.5">
                    <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Ringkasan Identitas Produk</p>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Nama Inovasi Produk</p>
                        <p id="rejName" class="text-[13.5px] font-extrabold text-slate-900 mt-0.5">-</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-2.5 border border-slate-100">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Unit Pengelola &amp; Kategori</p>
                        <p id="rejUnit" class="text-[12px] font-bold text-slate-800 mt-0.5">-</p>
                        <p id="rejCategory" class="text-[11px] text-[#00796B] font-medium">-</p>
                    </div>
                    <div class="rounded-lg bg-rose-50/70 border border-rose-200/70 p-2.5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-rose-700 font-bold">Estimasi HPP &amp; Harga Usulan</p>
                            <p id="rejPriceInfo" class="text-[11.5px] font-extrabold text-slate-900 mt-0.5">-</p>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-white border border-rose-200 text-rose-700 text-[10px] font-bold">Tidak Layak Komersial</span>
                    </div>
                </div>

                {{-- Putusan Kurasi RTPU --}}
                <div class="rounded-xl border border-slate-200/80 p-4 space-y-3">
                    <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-rose-600">Putusan Kurasi RTPU</p>
                    <div class="rounded-lg bg-rose-50 border border-rose-200 p-3 flex items-center justify-between">
                        <div>
                            <p class="text-[9.5px] uppercase tracking-wider text-rose-600 font-bold">Status Putusan</p>
                            <p id="rejStatusTitle" class="text-[15px] font-extrabold text-rose-700">Ditolak Permanen</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9.5px] uppercase tracking-wider text-slate-400 font-semibold">Tingkat Keputusan</p>
                            <p class="text-[11px] font-bold text-slate-800">Final / Siklus 2026</p>
                        </div>
                    </div>
                    <div class="space-y-1.5 text-[11.5px]">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Nomor Dokumen:</span>
                            <span id="rejDocNo" class="px-2 py-0.5 rounded bg-slate-100 font-mono font-bold text-slate-800 text-[10.5px]">Naskah Telaah #TL-RTPU</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Tanggal Putusan:</span>
                            <span id="rejUpdatedAt" class="font-semibold text-slate-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Dewan Kurator:</span>
                            <span class="font-semibold text-slate-800">Unit RTPU &amp; Komite HAKI PNJ</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Poin Pokok Pertimbangan Penolakan --}}
            <div class="rounded-xl bg-rose-50/50 border border-rose-200/80 p-4 space-y-2.5">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-extrabold uppercase tracking-wider text-rose-700 flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Poin Pokok Pertimbangan Penolakan RTPU
                    </p>
                    <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold">Status: Final</span>
                </div>
                <div class="bg-white rounded-lg border border-rose-100 p-3 text-[12px] text-slate-700 leading-relaxed" id="rejNotes">
                    -
                </div>
            </div>
        </div>

        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between gap-3 rounded-b-2xl">
            <button type="button" onclick="closeModal('modalRejectedArchive'); openGuideModal();"
                    class="text-[12px] font-semibold text-[#00796B] hover:underline">Panduan Standar Kurasi P3M</button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeModal('modalRejectedArchive')"
                        class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">Tutup</button>
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-semibold shadow-sm transition-all active:scale-95">
                    <span>Cetak Naskah Telaah (.PDF)</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =======================================================================
     4. MODAL DETAIL STATUS PENGAJUAN (Antrean / Revisi / Draft)
     ======================================================================= --}}
<div id="modalStatusDetail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-[660px] max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-5 py-3.5 border-b border-slate-100 flex items-center justify-between z-10">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-[15px] font-extrabold text-slate-900">Detail Status Pengajuan Produk</h3>
                    <span id="detStatusBadge" class="px-2 py-0.5 rounded-full text-[10.5px] font-bold">-</span>
                </div>
                <p class="text-[11px] text-slate-500"><span id="detCode" class="font-mono font-bold text-[#00796B]">#PRD</span> &bull; Pemantauan Real-Time RTPU PNJ</p>
            </div>
            <button type="button" onclick="closeModal('modalStatusDetail')" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 active:scale-95">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div id="detImageWrap" class="w-full sm:w-32 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                    <img id="detImage" src="" alt="Foto Produk" class="hidden w-full h-full object-cover">
                    <div id="detImagePlaceholder" class="text-slate-400 text-center p-2">
                        <svg class="w-8 h-8 mx-auto mb-1 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span class="text-[10px]">Tanpa Foto</span>
                    </div>
                </div>
                <div class="flex-1 space-y-1.5">
                    <h4 id="detName" class="text-[15px] font-extrabold text-slate-900 leading-snug">-</h4>
                    <div class="flex flex-wrap gap-1.5 text-[11px]">
                        <span id="detUnit" class="px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-semibold border border-sky-100">-</span>
                        <span id="detCategory" class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">-</span>
                        <span id="detOrderType" class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] font-semibold">-</span>
                    </div>
                    <p id="detDescription" class="text-[12px] text-slate-600 leading-relaxed pt-1">-</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2.5 text-center">
                <div class="rounded-xl bg-slate-50 border border-slate-200/70 p-2.5">
                    <p class="text-[10px] text-slate-500 font-medium">Estimasi HPP</p>
                    <p id="detHpp" class="text-[13.5px] font-extrabold text-slate-800 mt-0.5">Rp 0</p>
                </div>
                <div class="rounded-xl bg-[#F0FDF9] border border-[#CCFBF1] p-2.5">
                    <p class="text-[10px] text-[#00796B] font-medium">Harga Katalog (B2C)</p>
                    <p id="detPriceB2c" class="text-[13.5px] font-extrabold text-[#00796B] mt-0.5">Rp 0</p>
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-200/70 p-2.5">
                    <p class="text-[10px] text-slate-500 font-medium">Harga Mitra (B2B)</p>
                    <p id="detPriceB2b" class="text-[13.5px] font-extrabold text-slate-800 mt-0.5">Rp 0</p>
                </div>
            </div>

            <div id="detNotesBox" class="rounded-xl bg-amber-50/70 border border-amber-200 p-3.5">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-800 mb-1">Catatan Kurasi / Progres RTPU</p>
                <p id="detNotes" class="text-[12px] text-slate-700 leading-relaxed">-</p>
            </div>
        </div>

        <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between rounded-b-2xl">
            <span id="detUpdatedAt" class="text-[11px] text-slate-500">Diperbarui: -</span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeModal('modalStatusDetail')" class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">Tutup</button>
                <a id="detEditLink" href="#" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#00796B] hover:bg-[#00695C] text-white text-[12px] font-semibold shadow-sm transition-all active:scale-95">
                    <span>Edit / Perbaiki Pengajuan</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- =======================================================================
     5. MODAL KONFIRMASI PEMBATALAN PENGAJUAN
     ======================================================================= --}}
<div id="modalCancelConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-[420px] p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div>
                <h3 class="text-[15px] font-extrabold text-slate-900">Batalkan Pengajuan Produk?</h3>
                <p class="mt-1 text-[12px] text-slate-600 leading-relaxed">
                    Usulan <strong id="cancelProductName" class="text-slate-900">-</strong> akan dibatalkan dan dipindahkan ke Arsip Riwayat Pengajuan.
                </p>
            </div>
        </div>
        <form id="cancelProductForm" method="POST" action="" class="mt-5 flex items-center justify-end gap-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modalCancelConfirm')" class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold active:scale-95">Kembali</button>
            <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-semibold shadow-sm active:scale-95">Ya, Batalkan Pengajuan</button>
        </form>
    </div>
</div>

{{-- =======================================================================
     6. MODAL PANDUAN SISTEM & ALUR KURASI RTPU
     ======================================================================= --}}
<div id="modalGuide" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-[580px] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-[15px] font-extrabold text-slate-900">Panduan Pengajuan &amp; Alur Kurasi RTPU PNJ</h3>
            <button type="button" onclick="closeModal('modalGuide')" class="p-1 text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="mt-4 space-y-3 text-[12px] text-slate-600">
            <div class="p-3 rounded-xl bg-[#F0FDF9] border border-[#CCFBF1]">
                <p class="font-bold text-[#00695C]">Pedoman Operasional RTPU No. 042/PNJ/P3M/2026</p>
                <p class="mt-1 leading-relaxed">Setiap usulan produk riset, jasa pengujian, maupun reservasi laboratorium dari Admin P3M wajib mengacu pada Master Data P08 (Unit, 3 Kategori Baku, Sub-Kategori) serta melampirkan estimasi HPP dan margin BLU minimal 15% - 30%.</p>
            </div>
            <ol class="space-y-2 list-decimal list-inside">
                <li><strong class="text-slate-800">Pengajuan P3M:</strong> Admin P3M mengisi identitas, tipe order, HPP, harga usulan, dan foto produk (maks. 1MB). Status otomatis <code class="px-1 py-0.5 bg-slate-100 rounded text-[11px]">menunggu_kurasi_rtpu</code>.</li>
                <li><strong class="text-slate-800">Kurasi Admin RTPU:</strong> Tim Sentra RTPU memeriksa kelayakan teknis dan tarif. Jika perlu perbaikan, status menjadi <code class="px-1 py-0.5 bg-rose-50 text-rose-700 rounded text-[11px]">revisi</code> (batas perbaikan 14 hari kerja).</li>
                <li><strong class="text-slate-800">Penetapan SK Pimpinan:</strong> Usulan lolos diteruskan ke Pimpinan RTPU / Direktur PNJ (<code class="px-1 py-0.5 bg-sky-50 text-sky-700 rounded text-[11px]">menunggu_sk_pimpinan</code>) hingga berstatus <code class="px-1 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[11px]">tayang</code> di katalog PNJ Prime.</li>
            </ol>
        </div>
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeModal('modalGuide')" class="px-4 py-2 rounded-lg bg-[#00796B] text-white text-[12px] font-semibold active:scale-95">Saya Mengerti</button>
        </div>
    </div>
</div>

{{-- =======================================================================
     7. MODAL PENGATURAN AKSES / INFO AKUN ADMIN P3M
     ======================================================================= --}}
<div id="modalAccess" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-[440px] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-[15px] font-extrabold text-slate-900">Informasi Akses Admin P3M</h3>
            <button type="button" onclick="closeModal('modalAccess')" class="p-1 text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="mt-4 space-y-2.5 text-[12px]">
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Nama Lengkap</span><span class="font-bold text-slate-900">{{ $portalUser->name ?? Auth::user()->nama_lengkap }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Email Institusi</span><span class="font-mono text-slate-800">{{ $portalUser->email ?? Auth::user()->email }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">NIP / ID Pegawai</span><span class="font-mono text-slate-800">{{ $portalUser->nim_nip ?? '-' }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Unit Kerja</span><span class="font-semibold text-slate-800">{{ $portalUser->prodi_unit ?? 'P3M PNJ' }}</span></div>
            <div class="flex justify-between py-1.5"><span class="text-slate-500">Hak Akses Modul</span><span class="px-2 py-0.5 rounded bg-[#E6F5F3] text-[#00796B] font-bold text-[11px]">Pengelola Produk Unit (P3M)</span></div>
        </div>
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeModal('modalAccess')" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-[12px] font-semibold active:scale-95">Tutup</button>
        </div>
    </div>
</div>

{{-- =======================================================================
     8. MODAL KONFIRMASI LOGOUT
     ======================================================================= --}}
<div id="modalLogoutConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200/90 w-full max-w-[400px] p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-200/80 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <div>
                <h3 class="text-[15px] font-extrabold text-slate-900">Keluar dari PNJ Prime?</h3>
                <p class="mt-1 text-[12.5px] text-slate-600 leading-relaxed">
                    Pastikan seluruh perubahan data telah tersimpan sebelum Anda mengakhiri sesi ini.
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-5 flex items-center justify-end gap-2">
            @csrf
            <button type="button" onclick="closeModal('modalLogoutConfirm')"
                    class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-95">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-semibold transition-all active:scale-95">
                Ya, Keluar
            </button>
        </form>
    </div>
</div>

<script>
    const skPrintUrlTemplate = @json(route('admin.p3m.print-sk', ['product' => '__PRODUCT_ID__']));

    function openLogoutModal() {
        document.getElementById('modalLogoutConfirm').classList.remove('hidden');
    }

    function formatRupiah(val) {
        const num = Number(val || 0);
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function formatOrderType(type) {
        if (type === 'ready_stock') return 'Ready Stock';
        if (type === 'pre_order') return 'Pre-Order / MTO';
        if (type === 'lab_reservation') return 'Reservasi Lab / Jasa';
        return type || '-';
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    function openGuideModal() {
        document.getElementById('modalGuide').classList.remove('hidden');
    }

    function openAccessModal() {
        document.getElementById('modalAccess').classList.remove('hidden');
    }

    function openCancelModal(url, name) {
        document.getElementById('cancelProductForm').action = url;
        document.getElementById('cancelProductName').textContent = name;
        document.getElementById('modalCancelConfirm').classList.remove('hidden');
    }

    function openProductModal(data) {
        const isCancelled = data.status === 'ditolak' && (data.curation_notes || '').toLowerCase().includes('dibatalkan');

        if (data.status === 'tayang') {
            document.getElementById('appCode').textContent = '#' + data.code;
            document.getElementById('appName').textContent = data.name;
            document.getElementById('appUnit').textContent = data.unit;
            document.getElementById('appCategory').textContent = data.category + ' • ' + data.sub_category;
            document.getElementById('appSubmitter').textContent = data.submitter;
            document.getElementById('appOrderType').textContent = formatOrderType(data.order_type);
            document.getElementById('appPriceB2c').textContent = formatRupiah(data.price_b2c);
            document.getElementById('appPriceB2b').textContent = 'B2B: ' + formatRupiah(data.price_b2b);
            document.getElementById('appHpp').textContent = formatRupiah(data.hpp);
            const marginPct = data.price_b2c > 0 ? (((data.price_b2c - data.hpp) / data.price_b2c) * 100).toFixed(1) : '0.0';
            document.getElementById('appMargin').textContent = 'Margin Bersih BLU: ' + marginPct + '%';
            document.getElementById('appSkNumber').textContent = 'SK-RTPU/PNJ/2026/' + String(data.id).padStart(3, '0');
            document.getElementById('appUpdatedAt').textContent = data.updated_at;
            document.getElementById('appCatalogCode').textContent = '#KAT-2026-' + String(data.id).padStart(3, '0');
            document.getElementById('appNotes').textContent = data.curation_notes || 'Rekomendasi penetapan tarif diterima tanpa catatan revisi.';
            const skLink = document.getElementById('appPrintSkLink');
            if (skLink) {
                skLink.href = skPrintUrlTemplate.replace('__PRODUCT_ID__', encodeURIComponent(data.id));
            }
            document.getElementById('modalApprovedArchive').classList.remove('hidden');
            return;
        }

        if (data.status === 'ditolak' && !isCancelled) {
            document.getElementById('rejCode').textContent = '#' + data.code;
            document.getElementById('rejName').textContent = data.name;
            document.getElementById('rejUnit').textContent = data.unit;
            document.getElementById('rejCategory').textContent = data.category + ' • ' + data.sub_category;
            document.getElementById('rejPriceInfo').textContent = 'HPP ' + formatRupiah(data.hpp) + ' / Jual ' + formatRupiah(data.price_b2c);
            document.getElementById('rejDocNo').textContent = 'Naskah Telaah #TL-RTPU-' + String(data.id).padStart(3, '0');
            document.getElementById('rejUpdatedAt').textContent = data.updated_at;
            document.getElementById('rejNotes').textContent = data.curation_notes || 'Usulan belum memenuhi syarat kelayakan komersial & standar kurasi RTPU PNJ.';
            document.getElementById('modalRejectedArchive').classList.remove('hidden');
            return;
        }

        // Default: Detail Status Pengajuan (menunggu_kurasi_rtpu, menunggu_sk_pimpinan, revisi, draft, dibatalkan)
        document.getElementById('detCode').textContent = '#' + data.code;
        document.getElementById('detName').textContent = data.name;
        document.getElementById('detUnit').textContent = data.unit;
        document.getElementById('detCategory').textContent = data.category + ' • ' + data.sub_category;
        document.getElementById('detOrderType').textContent = formatOrderType(data.order_type);
        document.getElementById('detDescription').textContent = data.description || '-';
        document.getElementById('detHpp').textContent = formatRupiah(data.hpp);
        document.getElementById('detPriceB2c').textContent = formatRupiah(data.price_b2c);
        document.getElementById('detPriceB2b').textContent = formatRupiah(data.price_b2b);
        document.getElementById('detNotes').textContent = data.curation_notes || 'Menunggu penelaahan dari Tim Kurator Sentra RTPU.';
        document.getElementById('detUpdatedAt').textContent = 'Terakhir diperbarui: ' + data.updated_at;

        const imgEl = document.getElementById('detImage');
        const placeholderEl = document.getElementById('detImagePlaceholder');
        if (data.image_url) {
            imgEl.src = data.image_url;
            imgEl.classList.remove('hidden');
            placeholderEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            placeholderEl.classList.remove('hidden');
        }

        const badge = document.getElementById('detStatusBadge');
        const map = {
            'revisi': ['Perlu Revisi RTPU', 'bg-rose-50 text-rose-700 border border-rose-200'],
            'menunggu_kurasi_rtpu': ['Menunggu Review RTPU', 'bg-teal-50 text-teal-700 border border-teal-200'],
            'menunggu_sk_pimpinan': ['Menunggu SK Pimpinan', 'bg-sky-50 text-sky-700 border border-sky-200'],
            'draft': ['Draf Pengajuan', 'bg-slate-100 text-slate-700 border border-slate-200'],
            'ditolak': ['Dibatalkan Pengusul', 'bg-slate-100 text-slate-600 border border-slate-200'],
        };
        const [label, cls] = map[data.status] || [data.status, 'bg-slate-100 text-slate-700'];
        badge.textContent = label;
        badge.className = 'px-2 py-0.5 rounded-full text-[10.5px] font-bold ' + cls;

        const editLink = document.getElementById('detEditLink');
        if (data.edit_url && !isCancelled) {
            editLink.href = data.edit_url;
            editLink.classList.remove('hidden');
        } else {
            editLink.classList.add('hidden');
        }

        document.getElementById('modalStatusDetail').classList.remove('hidden');
    }
</script>
