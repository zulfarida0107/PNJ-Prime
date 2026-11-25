{{-- =======================================================================
     1. MODAL TERBITKAN / SESUAIKAN INVOICE RESMI B2B (SKEMA 100% LUNAS BLU)
     ======================================================================= --}}
<div id="modalIssueInvoiceB2b" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200/90 w-full max-w-[660px] max-h-[92vh] overflow-y-auto">
        {{-- Header --}}
        <div class="sticky top-0 bg-white px-5 py-3.5 border-b border-slate-100 flex items-start justify-between gap-3 z-10">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#EFF6FF] border border-[#BFDBFE] text-[#003366] flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[15px] font-extrabold text-slate-900">Penerbitan Invoice Komersial B2B (BLU PNJ)</h3>
                    <p class="text-[11.5px] text-slate-500 mt-0.5">
                        Atur penyesuaian biaya komersial, estimasi pengerjaan, dan jatuh tempo pembayaran 100% Lunas.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalIssueInvoiceB2b')"
                    class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors active:scale-95">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="issueInvoiceB2bForm" method="POST" action="" class="p-5 space-y-4">
            @csrf

            {{-- Info Ringkas Pesanan & Preview Nomor Invoice --}}
            <div class="rounded-lg bg-slate-50 border border-slate-200/80 p-3.5 grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-[12px]">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Preview Nomor Invoice</p>
                    <p id="invPreviewNumber" class="font-mono font-extrabold text-[#003366] text-[13px] tabular-nums mt-0.5">INV-PNJ-</p>
                    <p id="invPreviewOrderMeta" class="text-[11px] text-slate-500 mt-0.5 truncate">-</p>
                </div>
                <div class="sm:text-right">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Virtual Account BLU Mandiri</p>
                    <p id="invPreviewVa" class="font-mono font-bold text-slate-800 text-[12.5px] tabular-nums mt-0.5">-</p>
                    <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20 text-[10px] font-bold">
                        Skema Pembayaran: 100% Lunas
                    </span>
                </div>
            </div>

            {{-- Penyesuaian Diskon B2B & Jatuh Tempo --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-[11.5px] font-bold text-slate-700 mb-1">Potongan Diskon Kemitraan B2B</label>
                    <div class="flex rounded-lg border border-slate-200/90 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-[#003366]/15 focus-within:border-[#003366]">
                        <select id="invDiscountType" name="discount_type" onchange="recalcInvoiceModal()"
                                class="px-2.5 py-2 text-[12px] font-semibold bg-slate-50 text-slate-700 border-r border-slate-200 focus:outline-none">
                            <option value="nominal">Rp (Nominal)</option>
                            <option value="percent">% (Persen)</option>
                        </select>
                        <input id="invDiscountValue" name="discount_value" type="number" min="0" step="any" value="0"
                               oninput="recalcInvoiceModal()"
                               class="w-full px-3 py-2 text-[12.5px] font-semibold text-slate-900 text-right tabular-nums focus:outline-none">
                    </div>
                    <p class="text-[10.5px] text-slate-400 mt-1">Opsional sesuai kebijakan tarif komersial BLU PNJ (maks. 30%).</p>
                </div>

                <div>
                    <label for="invDueAt" class="block text-[11.5px] font-bold text-slate-700 mb-1">Tanggal Jatuh Tempo (Due Date) <span class="text-rose-600">*</span></label>
                    <input id="invDueAt" name="due_at" type="date" required
                           class="w-full px-3 py-2 text-[12.5px] font-semibold text-slate-900 tabular-nums bg-white border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366]">
                    <p class="text-[10.5px] text-slate-400 mt-1">Batas waktu pelunasan 100% ke rekening penerimaan BLU PNJ.</p>
                </div>
            </div>

            {{-- Ongkos Kirim, Estimasi Hari, Metode Pembayaran --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                <div>
                    <label for="invShippingCost" class="block text-[11.5px] font-bold text-slate-700 mb-1">Biaya Logistik / Ekspedisi (Rp)</label>
                    <input id="invShippingCost" name="shipping_cost" type="number" min="0" step="1000" value="0"
                           oninput="recalcInvoiceModal()"
                           class="w-full px-3 py-2 text-[12.5px] font-semibold text-slate-900 text-right tabular-nums bg-white border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366]">
                </div>

                <div>
                    <label for="invEstimasiHari" class="block text-[11.5px] font-bold text-slate-700 mb-1">Estimasi Pengerjaan (Hari Kerja)</label>
                    <input id="invEstimasiHari" name="estimasi_hari" type="number" min="1" max="180" value="7"
                           class="w-full px-3 py-2 text-[12.5px] font-semibold text-slate-900 text-right tabular-nums bg-white border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366]">
                </div>

                <div>
                    <label for="invPaymentMethod" class="block text-[11.5px] font-bold text-slate-700 mb-1">Kanal Pembayaran BLU</label>
                    <select id="invPaymentMethod" name="payment_method"
                            class="w-full px-3 py-2 text-[12.5px] font-semibold text-slate-900 bg-white border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366]">
                        <option value="va_mandiri_blu">VA Bank Mandiri BLU</option>
                        <option value="qris">QRIS BLU PNJ</option>
                    </select>
                </div>
            </div>

            {{-- Opsi Komponen Pajak & Bea e-Meterai --}}
            <div class="flex flex-wrap items-center gap-4 px-3.5 py-2.5 rounded-lg bg-slate-50/80 border border-slate-200/80 text-[12px]">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="invApplyPph23" name="apply_pph23" type="checkbox" value="1" onchange="recalcInvoiceModal()"
                           class="w-4 h-4 rounded border-slate-300 text-[#003366] focus:ring-[#003366]">
                    <span class="font-semibold text-slate-700">Potongan PPh 23 (2% Jasa / Lab)</span>
                </label>
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="invApplyEmeterai" name="apply_emeterai" type="checkbox" value="1" onchange="recalcInvoiceModal()"
                           class="w-4 h-4 rounded border-slate-300 text-[#003366] focus:ring-[#003366]">
                    <span class="font-semibold text-slate-700">Bea e-Meterai (Rp 10.000)</span>
                </label>
            </div>

            {{-- Rincian Perhitungan Keuangan Live --}}
            <div class="rounded-lg border border-slate-200/90 bg-white overflow-hidden">
                <div class="px-4 py-2 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                    <span>Rincian Perhitungan Keuangan</span>
                    <span>Mata Uang: IDR</span>
                </div>
                <div class="p-4 space-y-2 text-[12.5px] tabular-nums">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Subtotal Item B2B</span>
                        <span id="calcSubtotal" class="font-semibold text-slate-900 text-right">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Diskon Kemitraan B2B</span>
                        <span id="calcDiscount" class="font-semibold text-emerald-700 text-right">-Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Biaya Logistik &amp; Ekspedisi</span>
                        <span id="calcShipping" class="font-semibold text-slate-900 text-right">Rp 0</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="font-bold text-slate-800">Dasar Pengenaan Pajak (DPP)</span>
                        <span id="calcDpp" class="font-bold text-slate-900 text-right">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">PPN 11% (Faktur Pajak Standar BLU)</span>
                        <span id="calcPpn" class="font-semibold text-slate-900 text-right">Rp 0</span>
                    </div>
                    <div id="calcPphRow" class="flex items-center justify-between">
                        <span class="text-slate-600">Pemotongan PPh 23 (2% dari DPP)</span>
                        <span id="calcPph23" class="font-semibold text-rose-700 text-right">-Rp 0</span>
                    </div>
                    <div id="calcMeteraiRow" class="flex items-center justify-between">
                        <span class="text-slate-600">Bea e-Meterai Elektronik</span>
                        <span id="calcMeterai" class="font-semibold text-slate-900 text-right">Rp 0</span>
                    </div>

                    <div class="mt-2 pt-3 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-extrabold text-slate-900">Total Tagihan Akhir (100% Lunas)</p>
                            <p class="text-[11px] text-slate-500">Pelunasan penuh langsung ke rekening penerimaan BLU PNJ</p>
                        </div>
                        <p id="calcGrandTotal" class="text-[19px] font-extrabold text-[#003366] text-right">Rp 0</p>
                    </div>
                </div>
            </div>

            {{-- Catatan / Instruksi Transfer BLU PNJ --}}
            <div>
                <label for="invTransferNotes" class="block text-[11.5px] font-bold text-slate-700 mb-1">Catatan &amp; Instruksi Transfer BLU PNJ</label>
                <textarea id="invTransferNotes" name="transfer_notes" rows="2"
                          placeholder="Contoh: Pembayaran lunas 100% melalui VA Bank Mandiri a.n. RPL 139 PNJ untuk Operasional BLU sebelum tanggal jatuh tempo."
                          class="w-full px-3 py-2 text-[12px] text-slate-800 bg-white border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366]"></textarea>
            </div>

            {{-- Footer Aksi --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal('modalIssueInvoiceB2b')"
                        class="px-4 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span id="invSubmitLabel">Terbitkan &amp; Simpan Invoice Resmi BLU</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =======================================================================
     2. MODAL KONFIRMASI AKSI VALIDASI PESANAN (B2C & B2B — TANPA confirm())
     ======================================================================= --}}
<div id="modalActionConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200/90 w-full max-w-[460px] p-5">
        <div class="flex items-start gap-3">
            <div id="actionModalIcon" class="w-10 h-10 rounded-lg bg-[#EFF6FF] border border-[#BFDBFE] text-[#003366] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="min-w-0">
                <h3 id="actionModalTitle" class="text-[15px] font-extrabold text-slate-900">Konfirmasi Aksi</h3>
                <p id="actionModalMessage" class="mt-1 text-[12.5px] text-slate-600 leading-relaxed">-</p>
            </div>
        </div>

        <form id="actionModalForm" method="POST" action="" class="mt-4 space-y-3">
            @csrf
            <input type="hidden" name="action" id="actionModalType" value="">

            <div id="actionModalRejectWrap" class="hidden">
                <label for="actionModalRejectReason" class="block text-[11.5px] font-bold text-rose-700 mb-1">
                    Alasan Penolakan Resmi <span class="text-rose-600">*</span>
                </label>
                <textarea id="actionModalRejectReason" name="rejection_reason" rows="3"
                          placeholder="Tuliskan alasan penolakan secara spesifik (minimal 10 karakter), misal: jadwal lab tidak tersedia atau dokumen persyaratan tidak valid..."
                          class="w-full px-3 py-2 text-[12px] text-slate-800 bg-white border border-rose-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"></textarea>
                <p id="actionModalRejectError" class="hidden text-[11px] font-semibold text-rose-600 mt-1">Alasan penolakan wajib diisi minimal 10 karakter.</p>
            </div>

            <div id="actionModalNoteWrap">
                <label for="actionModalAdminNote" class="block text-[11.5px] font-semibold text-slate-700 mb-1">Catatan Verifikator (Opsional)</label>
                <input id="actionModalAdminNote" name="admin_note" type="text"
                       placeholder="Tambahkan catatan operasional untuk riwayat pesanan..."
                       class="w-full px-3 py-2 text-[12px] text-slate-800 bg-white border border-slate-200/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#003366]/15 focus:border-[#003366]">
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal('modalActionConfirm')"
                        class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit" id="actionModalSubmitBtn"
                        class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                    Ya, Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =======================================================================
     3. MODAL PANDUAN OPERASIONAL ADMIN RTPU
     ======================================================================= --}}
<div id="modalGuide" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200/90 w-full max-w-[560px] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-[15px] font-extrabold text-slate-900">Panduan Operasional &amp; Keuangan Admin RTPU</h3>
            <button type="button" onclick="closeModal('modalGuide')" class="p-1 text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="mt-4 space-y-3 text-[12px] text-slate-600">
            <div class="p-3 rounded-lg bg-[#EFF6FF] border border-[#BFDBFE]">
                <p class="font-bold text-[#003366]">Kebijakan Pembayaran BLU Politeknik Negeri Jakarta</p>
                <p class="mt-1 leading-relaxed text-slate-700">
                    Seluruh transaksi komersial B2C dan B2B pada PNJ Prime menerapkan skema <strong>100% Lunas</strong> ke rekening resmi penerimaan BLU PNJ (Bank Mandiri Virtual Account / QRIS). Tidak terdapat skema uang muka (DP).
                </p>
            </div>
            <ol class="space-y-2 list-decimal list-inside leading-relaxed">
                <li><strong class="text-slate-800">Validasi B2C:</strong> Periksa kesesuaian identitas pemesan sivitas/umum, ketersediaan stok atau jadwal lab, serta status pembayaran QRIS/VA sebelum meneruskan ke unit pengelola.</li>
                <li><strong class="text-slate-800">Validasi B2B &amp; Invoice:</strong> Pastikan legalitas Mitra B2B (NIB &amp; NPWP), hitung DPP, PPN 11%, dan PPh 23 (2% untuk jasa/lab), lalu terbitkan Invoice Resmi BLU untuk dicetak (.PDF).</li>
                <li><strong class="text-slate-800">Pelaporan Resmi:</strong> Gunakan tombol <em>Cetak Laporan Operasional (.PDF)</em> atau <em>Ekspor CSV</em> untuk pelaporan periodik ke Kepala UPA-RTPU.</li>
            </ol>
        </div>
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeModal('modalGuide')" class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold active:scale-95">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

{{-- =======================================================================
     4. MODAL INFO AKUN & HAK AKSES ADMIN RTPU
     ======================================================================= --}}
<div id="modalAccess" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200/90 w-full max-w-[440px] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-[15px] font-extrabold text-slate-900">Informasi Akses Admin RTPU</h3>
            <button type="button" onclick="closeModal('modalAccess')" class="p-1 text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="mt-4 space-y-2.5 text-[12px]">
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Nama Lengkap</span><span class="font-bold text-slate-900">{{ $portalUser->name ?? Auth::user()->nama_lengkap }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Email Institusi</span><span class="font-mono text-slate-800">{{ $portalUser->email ?? Auth::user()->email }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">NIP / ID Pegawai</span><span class="font-mono text-slate-800 tabular-nums">{{ $portalUser->nim_nip ?? '-' }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Unit Kerja</span><span class="font-semibold text-slate-800">{{ $portalUser->prodi_unit ?? 'UPA-RTPU PNJ' }}</span></div>
            <div class="flex justify-between py-1.5"><span class="text-slate-500">Hak Akses Modul</span><span class="px-2 py-0.5 rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20 font-bold text-[11px]">Operasional &amp; Keuangan RTPU</span></div>
        </div>
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeModal('modalAccess')" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-semibold active:scale-95">Tutup</button>
        </div>
    </div>
</div>

{{-- =======================================================================
     5. MODAL INFO MODUL BATCH 2 (ANTI 404 UNTUK MENU SIDEBAR BATCH 2)
     ======================================================================= --}}
<div id="modalBatch2Info" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-lg shadow-xl border border-slate-200/90 w-full max-w-[430px] p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-[#EFF6FF] border border-[#BFDBFE] text-[#003366] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 id="batch2ModalTitle" class="text-[15px] font-extrabold text-slate-900">Modul Batch 2</h3>
                    <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">Segera</span>
                </div>
                <p id="batch2ModalDesc" class="mt-1.5 text-[12.5px] text-slate-600 leading-relaxed">-</p>
            </div>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
            <button type="button" onclick="closeModal('modalBatch2Info')"
                    class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                Mengerti
            </button>
        </div>
    </div>
</div>

{{-- =======================================================================
     6. MODAL KONFIRMASI LOGOUT
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
                    Pastikan seluruh validasi pesanan dan penerbitan invoice telah tersimpan sebelum Anda mengakhiri sesi ini.
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
    let rtpuInvoiceBaseSubtotal = 0;

    function formatIdr(value) {
        const num = Math.round(Number(value || 0));
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    function openLogoutModal() {
        document.getElementById('modalLogoutConfirm').classList.remove('hidden');
    }

    function openGuideModal() {
        document.getElementById('modalGuide').classList.remove('hidden');
    }

    function openAccessModal() {
        document.getElementById('modalAccess').classList.remove('hidden');
    }

    function openBatch2Modal(title, desc) {
        document.getElementById('batch2ModalTitle').textContent = title;
        document.getElementById('batch2ModalDesc').textContent = desc;
        document.getElementById('modalBatch2Info').classList.remove('hidden');
    }

    /**
     * Modal Konfirmasi Kustom untuk Aksi Validasi B2C / B2B (Verifikasi, Lunas, Siap Diambil, Selesai, Tolak).
     */
    function openOrderActionModal(config) {
        const form = document.getElementById('actionModalForm');
        const titleEl = document.getElementById('actionModalTitle');
        const msgEl = document.getElementById('actionModalMessage');
        const typeEl = document.getElementById('actionModalType');
        const rejectWrap = document.getElementById('actionModalRejectWrap');
        const rejectInput = document.getElementById('actionModalRejectReason');
        const rejectErr = document.getElementById('actionModalRejectError');
        const noteWrap = document.getElementById('actionModalNoteWrap');
        const noteInput = document.getElementById('actionModalAdminNote');
        const submitBtn = document.getElementById('actionModalSubmitBtn');
        const iconWrap = document.getElementById('actionModalIcon');

        form.action = config.url;
        typeEl.value = config.action;
        titleEl.textContent = config.title;
        msgEl.textContent = config.message;
        submitBtn.textContent = config.confirmText || 'Ya, Lanjutkan';

        rejectInput.value = '';
        noteInput.value = '';
        rejectErr.classList.add('hidden');

        const isReject = config.action === 'reject';
        rejectWrap.classList.toggle('hidden', !isReject);
        noteWrap.classList.toggle('hidden', isReject);
        rejectInput.required = isReject;

        if (isReject) {
            submitBtn.className = 'px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-semibold transition-all active:scale-95';
            iconWrap.className = 'w-10 h-10 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0';
            iconWrap.innerHTML = '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
        } else if (config.tone === 'emerald') {
            submitBtn.className = 'px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-semibold transition-all active:scale-95';
            iconWrap.className = 'w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0';
            iconWrap.innerHTML = '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
        } else {
            submitBtn.className = 'px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95';
            iconWrap.className = 'w-10 h-10 rounded-lg bg-[#EFF6FF] border border-[#BFDBFE] text-[#003366] flex items-center justify-center shrink-0';
            iconWrap.innerHTML = '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
        }

        document.getElementById('modalActionConfirm').classList.remove('hidden');
        if (isReject) {
            setTimeout(() => rejectInput.focus(), 80);
        }
    }

    document.getElementById('actionModalForm')?.addEventListener('submit', function (e) {
        const actionType = document.getElementById('actionModalType').value;
        if (actionType === 'reject') {
            const reason = (document.getElementById('actionModalRejectReason').value || '').trim();
            if (reason.length < 10) {
                e.preventDefault();
                document.getElementById('actionModalRejectError').classList.remove('hidden');
                document.getElementById('actionModalRejectReason').focus();
            }
        }
    });

    /**
     * Modal Penerbitan / Penyesuaian Invoice B2B (100% Lunas BLU PNJ).
     */
    function openInvoiceB2bModal(data) {
        rtpuInvoiceBaseSubtotal = Number(data.gross_subtotal || 0);

        document.getElementById('issueInvoiceB2bForm').action = data.submit_url;
        document.getElementById('invPreviewNumber').textContent = data.invoice_number;
        document.getElementById('invPreviewOrderMeta').textContent = data.order_code + ' • ' + data.company_name;
        document.getElementById('invPreviewVa').textContent = data.va_number;

        document.getElementById('invDiscountType').value = 'nominal';
        document.getElementById('invDiscountValue').value = Number(data.discount_b2b || 0);
        document.getElementById('invShippingCost').value = Number(data.shipping_cost || 0);
        document.getElementById('invEstimasiHari').value = Number(data.estimasi_hari || 7);
        document.getElementById('invDueAt').value = data.due_at;
        document.getElementById('invPaymentMethod').value = data.payment_method || 'va_mandiri_blu';
        document.getElementById('invApplyPph23').checked = Boolean(data.apply_pph23);
        document.getElementById('invApplyEmeterai').checked = Boolean(data.apply_emeterai);
        document.getElementById('invTransferNotes').value = data.transfer_notes || 'Pelunasan 100% melalui Virtual Account Bank Mandiri BLU PNJ sebelum tanggal jatuh tempo.';
        document.getElementById('invSubmitLabel').textContent = data.has_invoice
            ? 'Simpan Pembaruan Invoice Resmi BLU'
            : 'Terbitkan & Simpan Invoice Resmi BLU';

        recalcInvoiceModal();
        document.getElementById('modalIssueInvoiceB2b').classList.remove('hidden');
    }

    function recalcInvoiceModal() {
        const discType = document.getElementById('invDiscountType').value;
        const discRaw = Math.max(0, Number(document.getElementById('invDiscountValue').value || 0));
        const shipping = Math.max(0, Number(document.getElementById('invShippingCost').value || 0));
        const applyPph23 = document.getElementById('invApplyPph23').checked;
        const applyMeterai = document.getElementById('invApplyEmeterai').checked;

        let discount = 0;
        if (discType === 'percent') {
            const pct = Math.min(discRaw, 30);
            discount = Math.round(rtpuInvoiceBaseSubtotal * (pct / 100));
        } else {
            discount = Math.min(discRaw, rtpuInvoiceBaseSubtotal);
        }

        const dpp = Math.max(0, rtpuInvoiceBaseSubtotal - discount + shipping);
        const ppn = Math.round(dpp * 0.11);
        const pph23 = applyPph23 ? Math.round(dpp * 0.02) : 0;
        const meterai = applyMeterai ? 10000 : 0;
        const grandTotal = Math.max(0, dpp + ppn - pph23 + meterai);

        document.getElementById('calcSubtotal').textContent = formatIdr(rtpuInvoiceBaseSubtotal);
        document.getElementById('calcDiscount').textContent = discount > 0 ? '-' + formatIdr(discount) : 'Rp 0';
        document.getElementById('calcShipping').textContent = formatIdr(shipping);
        document.getElementById('calcDpp').textContent = formatIdr(dpp);
        document.getElementById('calcPpn').textContent = formatIdr(ppn);
        document.getElementById('calcPph23').textContent = pph23 > 0 ? '-' + formatIdr(pph23) : 'Rp 0';
        document.getElementById('calcMeterai').textContent = formatIdr(meterai);
        document.getElementById('calcGrandTotal').textContent = formatIdr(grandTotal);

        document.getElementById('calcPphRow').classList.toggle('opacity-40', !applyPph23);
        document.getElementById('calcMeteraiRow').classList.toggle('opacity-40', !applyMeterai);
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            ['modalIssueInvoiceB2b', 'modalActionConfirm', 'modalGuide', 'modalAccess', 'modalBatch2Info', 'modalLogoutConfirm'].forEach(closeModal);
        }
    });
</script>
