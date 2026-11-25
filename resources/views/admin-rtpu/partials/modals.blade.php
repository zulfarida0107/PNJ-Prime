{{-- =======================================================================
     1. MODAL 03c: PENYESUAIAN BIAYA & PENERBITAN INVOICE B2B (PERSIS MOCKUP)
     ======================================================================= --}}
<div id="modalIssueInvoiceB2b" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 border-t-4 border-t-[#00877A] w-full max-w-[650px] max-h-[92vh] overflow-y-auto">
        {{-- Header Modal --}}
        <div class="px-6 pt-5 pb-3.5 flex items-start justify-between gap-3">
            <div class="flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#F0FDFA] text-[#00877A] flex items-center justify-center shrink-0 mt-0.5">
                    <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" style="width:18px;height:18px;min-width:18px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[16.5px] font-extrabold text-slate-900 tracking-tight">Penyesuaian Biaya &amp; Penerbitan Invoice B2B</h3>
                    <p class="text-[12px] text-slate-500 mt-0.5 leading-snug">
                        Atur diskon skema kemitraan industri, estimasi waktu, dan logistik sebelum menerbitkan invoice resmi BLU PNJ.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalIssueInvoiceB2b')"
                    class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors active:scale-[0.98]">
                <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" style="width:18px;height:18px;min-width:18px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="issueInvoiceB2bForm" method="POST" action="" class="px-6 pb-5 space-y-4">
            @csrf

            {{-- Strip Info Mint Lembut --}}
            <div class="rounded-lg bg-[#F0FDFA] border border-[#CCFBF1] px-3.5 py-2.5 flex items-start gap-2.5 text-[12px]">
                <svg width="18" height="18" class="w-[18px] h-[18px] text-[#00877A] shrink-0 mt-0.5" style="width:18px;height:18px;min-width:18px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
                <div class="leading-snug min-w-0">
                    <p class="text-slate-700">
                        <strong class="text-[#00877A] tabular-nums">No. Pengajuan: <span id="invPreviewOrderCode">#ORD</span></strong>
                        <span class="mx-1.5 text-slate-300">&bull;</span>
                        <span>Mitra: <strong id="invPreviewCompany" class="text-slate-900">-</strong></span>
                    </p>
                    <p class="text-[11.5px] text-slate-600 mt-0.5 truncate">
                        Layanan: <span id="invPreviewService" class="font-medium text-slate-700">-</span>
                        <span class="mx-1.5 text-slate-300">&bull;</span>
                        <span class="font-mono text-[11px] text-slate-500" id="invPreviewNumber">INV-PNJ</span>
                    </p>
                </div>
            </div>

            {{-- Potongan Diskon Kemitraan B2B --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-[12.5px] font-bold text-slate-800">Potongan Diskon Kemitraan B2B</label>
                    <span class="px-2 py-0.5 rounded bg-[#F0FDFA] text-[#00877A] border border-[#99F6E4] text-[10px] font-extrabold tracking-wider uppercase">
                        DISKON KHUSUS MITRA
                    </span>
                </div>
                <div class="flex rounded-lg border border-slate-300 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-[#00877A]/20 focus-within:border-[#00877A]">
                    <select id="invDiscountType" name="discount_type" onchange="recalcInvoiceModal()"
                            class="px-3 py-2 text-[12.5px] font-semibold bg-slate-50 text-slate-800 border-r border-slate-200 focus:outline-none">
                        <option value="percent">% (Persentase)</option>
                        <option value="nominal">Rp (Nominal)</option>
                    </select>
                    <input id="invDiscountValue" name="discount_value" type="number" min="0" step="any" value="0"
                           oninput="recalcInvoiceModal()"
                           class="w-full px-3.5 py-2 text-[13px] font-semibold text-slate-900 tabular-nums focus:outline-none">
                </div>
                <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                    <svg width="14" height="14" class="w-3.5 h-3.5 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>Maksimal diskon sesuai kebijakan skema kemitraan industri BLU PNJ</span>
                </p>
            </div>

            {{-- Grid 2 Kolom: Ongkos Kirim & Estimasi Pengerjaan Dinamis --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label for="invShippingCost" class="block text-[12.5px] font-bold text-slate-800 mb-1.5">Ongkos Kirim Manual / Ekspedisi</label>
                    <div class="flex rounded-lg border border-slate-300 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-[#00877A]/20 focus-within:border-[#00877A]">
                        <span class="px-3 py-2 text-[12.5px] font-semibold text-slate-500 bg-slate-50 border-r border-slate-200">Rp</span>
                        <input id="invShippingCost" name="shipping_cost" type="number" min="0" step="1000" value="0"
                               oninput="recalcInvoiceModal()"
                               class="w-full px-3 py-2 text-[13px] font-semibold text-slate-900 tabular-nums focus:outline-none">
                    </div>
                    <p class="text-[10.5px] text-slate-500 mt-1 leading-snug">Pengiriman dokumen hasil uji &amp; spesimen prototipe via ekspedisi khusus</p>
                </div>

                <div>
                    <label for="invEstimasiHari" class="block text-[12.5px] font-bold text-slate-800 mb-1.5">Estimasi Pengerjaan Dinamis</label>
                    <div class="flex items-center rounded-lg border border-slate-300 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-[#00877A]/20 focus-within:border-[#00877A]">
                        <span class="px-3 py-2 text-slate-400 bg-slate-50 border-r border-slate-200">
                            <svg width="16" height="16" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        <input id="invEstimasiHari" name="estimasi_hari" type="number" min="1" max="180" value="14"
                               class="w-full px-3 py-2 text-[13px] font-semibold text-slate-900 tabular-nums focus:outline-none">
                        <span class="pr-3 text-[12px] font-medium text-slate-500 whitespace-nowrap">Hari Kerja</span>
                    </div>
                    <p class="text-[10.5px] text-slate-500 mt-1 leading-snug">Dihitung sejak konfirmasi pelunasan 100% (VA Mandiri) diterima</p>
                </div>
            </div>

            {{-- Baris Ringkas: Jatuh Tempo, Metode BLU, & Toggle Pajak --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-0.5">
                <div>
                    <label for="invDueAt" class="block text-[11.5px] font-bold text-slate-700 mb-1">Tanggal Jatuh Tempo (Due Date)</label>
                    <input id="invDueAt" name="due_at" type="date" required
                           class="w-full px-3 py-1.5 text-[12px] font-semibold text-slate-900 tabular-nums bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 focus:border-[#00877A]">
                </div>
                <div>
                    <label for="invPaymentMethod" class="block text-[11.5px] font-bold text-slate-700 mb-1">Kanal Penerimaan BLU PNJ</label>
                    <select id="invPaymentMethod" name="payment_method"
                            class="w-full px-3 py-1.5 text-[12px] font-semibold text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/20 focus:border-[#00877A]">
                        <option value="va_mandiri_blu">Virtual Account Mandiri BLU (100% Lunas)</option>
                        <option value="qris">QRIS Resmi BLU PNJ (100% Lunas)</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 text-[11.5px] text-slate-700">
                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                    <input id="invApplyPph23" name="apply_pph23" type="checkbox" value="1" onchange="recalcInvoiceModal()"
                           class="w-3.5 h-3.5 rounded border-slate-300 text-[#00877A] focus:ring-[#00877A]">
                    <span class="font-semibold">Pemotongan PPh 23 (2% Jasa/Lab)</span>
                </label>
                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                    <input id="invApplyEmeterai" name="apply_emeterai" type="checkbox" value="1" onchange="recalcInvoiceModal()"
                           class="w-3.5 h-3.5 rounded border-slate-300 text-[#00877A] focus:ring-[#00877A]">
                    <span class="font-semibold">Bea e-Meterai Otomatis (Rp 10.000)</span>
                </label>
            </div>
            <input type="hidden" name="transfer_notes" id="invTransferNotes" value="Pelunasan 100% melalui Virtual Account Bank Mandiri BLU PNJ sebelum tanggal jatuh tempo.">

            {{-- Kotak RINCIAN PERHITUNGAN KEUANGAN Persis Mockup 03c --}}
            <div class="bg-slate-50/90 border border-slate-200 rounded-xl p-4 space-y-2 text-[12.5px] tabular-nums">
                <div class="flex items-center justify-between pb-1.5 border-b border-slate-200/80 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                    <span>RINCIAN PERHITUNGAN KEUANGAN</span>
                    <span class="normal-case font-semibold text-slate-500">Mata Uang: IDR</span>
                </div>

                <div class="space-y-1.5 pt-1">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Subtotal Layanan Riset</span>
                        <span id="calcSubtotal" class="font-bold text-slate-900 text-right">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#00877A] font-semibold inline-flex items-center gap-1">
                            <span id="calcDiscountLabel">Diskon Kemitraan (0%)</span>
                            <svg width="14" height="14" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </span>
                        <span id="calcDiscount" class="font-bold text-[#00877A] text-right">-Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Biaya Logistik &amp; Ekspedisi</span>
                        <span id="calcShipping" class="font-bold text-slate-900 text-right">Rp 0</span>
                    </div>
                </div>

                <div class="pt-2 mt-1 border-t border-slate-200/80 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-extrabold text-slate-900">Dasar Pengenaan Pajak (DPP)</span>
                        <span id="calcDpp" class="font-extrabold text-slate-900 text-right">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">PPN 11% (Faktur Pajak Standar BLU)</span>
                        <span id="calcPpn" class="font-semibold text-slate-800 text-right">Rp 0</span>
                    </div>
                    <div id="calcPphRow" class="flex items-center justify-between">
                        <span class="text-slate-600">Pemotongan PPh 23 (2% dari DPP)</span>
                        <span id="calcPph23" class="font-semibold text-slate-800 text-right">-Rp 0</span>
                    </div>
                    <div id="calcMeteraiRow" class="flex items-center justify-between">
                        <span class="text-slate-600">Bea e-Meterai (Otomatis)</span>
                        <span id="calcMeterai" class="font-semibold text-slate-800 text-right">Rp 10.000</span>
                    </div>
                </div>

                {{-- Box Putih Total Tagihan Akhir --}}
                <div class="mt-3 bg-white border border-slate-200/90 rounded-lg px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <p class="text-[13.5px] font-extrabold text-slate-900">Total Tagihan Akhir</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Termin penagihan langsung ke rekening BLU Politeknik Negeri Jakarta</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p id="calcGrandTotal" class="text-[22px] font-extrabold text-[#00877A] leading-tight tabular-nums">Rp 0</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200 text-[9.5px] font-extrabold tracking-wider uppercase">
                            STATUS: SIAP DITAGIHKAN
                        </span>
                    </div>
                </div>
            </div>

            {{-- Footnote Stempel Elektronik BLU --}}
            <div class="flex items-start gap-2 text-[11px] text-slate-500 leading-relaxed">
                <svg width="16" height="16" class="w-4 h-4 text-[#00877A] shrink-0 mt-0.5" style="width:16px;height:16px;min-width:16px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                <span>Dokumen invoice resmi akan dicetak dengan stempel elektronik tersertifikasi Badan Layanan Umum (BLU) PNJ dan nomor faktur pajak otomatis terdaftar.</span>
            </div>

            {{-- Footer 2 Tombol Sejajar Persis Mockup 03c --}}
            <div class="pt-3 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-2.5">
                <button type="button" id="invRejectTriggerBtn"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-[12.5px] font-bold transition-all active:scale-[0.98]">
                    <svg width="16" height="16" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>Tolak dengan Catatan</span>
                </button>

                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12.5px] font-bold shadow-sm transition-all active:scale-[0.98]">
                    <svg width="16" height="16" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    <span id="invSubmitLabel">Terbitkan &amp; Kirim Invoice Resmi BLU</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =======================================================================
     2. MODAL DETAIL BERKAS & AKSI LENGKAP (B2C & B2B — DARI "Detail Berkas")
     ======================================================================= --}}
<div id="modalDetailBerkas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 border-t-4 border-t-[#00877A] w-full max-w-[740px] max-h-[92vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between z-10">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-[15.5px] font-extrabold text-slate-900">Detail Berkas &amp; Validasi Pesanan</h3>
                    <span id="dbSegmentBadge" class="px-2 py-0.5 rounded-full text-[10.5px] font-bold">-</span>
                    <span id="dbStatusBadge" class="px-2 py-0.5 rounded-full text-[10.5px] font-bold">-</span>
                </div>
                <p class="text-[11.5px] text-slate-500 mt-0.5 tabular-nums">
                    <span id="dbOrderCode" class="font-mono font-bold text-[#00877A]">#ORD</span> &bull; Diajukan <span id="dbCreatedAt">-</span>
                </p>
            </div>
            <button type="button" onclick="closeModal('modalDetailBerkas')"
                    class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 active:scale-[0.98]">
                <svg width="18" height="18" class="w-[18px] h-[18px] shrink-0" style="width:18px;height:18px;min-width:18px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-4 text-[12px]">
            {{-- Identitas & Pembayaran --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3.5 space-y-1.5">
                    <p id="dbIdentityHeading" class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Identitas Pemesan</p>
                    <p id="dbCustomerName" class="text-[13.5px] font-extrabold text-slate-900">-</p>
                    <div id="dbIdentityRows" class="space-y-1 text-[11.5px] pt-1"></div>
                </div>

                <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3.5 space-y-1.5 tabular-nums">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Status Pembayaran &amp; Tagihan (100% Lunas)</p>
                    <div id="dbInvoiceRows" class="space-y-1 text-[11.5px] pt-1"></div>
                </div>
            </div>

            {{-- Rincian Item --}}
            <div class="rounded-xl border border-slate-200/90 overflow-hidden">
                <table class="w-full text-[12px] border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="px-3.5 py-2 text-left">Item / Layanan</th>
                            <th class="px-3 py-2 text-left">Unit</th>
                            <th class="px-3 py-2 text-right">Qty</th>
                            <th class="px-3 py-2 text-right">Harga (Rp)</th>
                            <th class="px-3.5 py-2 text-right">Subtotal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody id="dbItemsBody" class="divide-y divide-slate-100 tabular-nums"></tbody>
                </table>
                <div id="dbTotalsFooter" class="px-4 py-3 bg-slate-50/80 border-t border-slate-200 space-y-1 text-[12px] tabular-nums"></div>
            </div>

            {{-- Catatan Pesanan --}}
            <div>
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Catatan &amp; Riwayat Status</p>
                <div id="dbNotes" class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 text-slate-700 whitespace-pre-line leading-relaxed">-</div>
            </div>
        </div>

        {{-- Footer Aksi Nyata --}}
        <div id="dbActionFooter" class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/70 flex flex-wrap items-center justify-between gap-2 rounded-b-xl"></div>
    </div>
</div>

{{-- =======================================================================
     3. MODAL LOG VERIFIKASI (UNTUK TOMBOL "Log Verifikasi" B2C & B2B)
     ======================================================================= --}}
<div id="modalLogVerifikasi" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-[600px] max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-[15px] font-extrabold text-slate-900">Log Aktivitas Verifikasi &amp; Invoice</h3>
                <p class="text-[11.5px] text-slate-500">Riwayat pembaruan status pesanan dan penerbitan tagihan resmi BLU PNJ.</p>
            </div>
            <button type="button" onclick="closeModal('modalLogVerifikasi')" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700">&times;</button>
        </div>
        <div class="p-5 divide-y divide-slate-100">
            @forelse (($verificationLogs ?? []) as $logOrd)
                <div class="py-3 first:pt-0 last:pb-0 flex items-start justify-between gap-3 text-[12px]">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-[#00877A] tabular-nums">#{{ $logOrd->order_code }}</span>
                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase">{{ $logOrd->segment }}</span>
                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-semibold">{{ str_replace('_', ' ', ucfirst($logOrd->status)) }}</span>
                        </div>
                        <p class="font-bold text-slate-900 mt-1 truncate">
                            {{ $logOrd->segment === 'b2b' ? ($logOrd->user?->company_name ?: ($logOrd->user?->name ?? '-')) : ($logOrd->user?->name ?? '-') }}
                        </p>
                        <p class="text-[11px] text-slate-500 truncate">
                            {{ $logOrd->invoice ? ('Invoice: ' . $logOrd->invoice->invoice_number . ' (' . strtoupper($logOrd->invoice->payment_status) . ')') : 'Belum ada invoice terbit' }}
                        </p>
                    </div>
                    <span class="text-[11px] text-slate-400 tabular-nums shrink-0">{{ optional($logOrd->updated_at)->format('d/m/Y H:i') }}</span>
                </div>
            @empty
                <p class="text-center text-[12px] text-slate-500 py-6">Belum ada riwayat log verifikasi tercatat.</p>
            @endforelse
        </div>
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/70 flex justify-end rounded-b-xl">
            <button type="button" onclick="closeModal('modalLogVerifikasi')" class="px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-semibold active:scale-[0.98]">Tutup</button>
        </div>
    </div>
</div>

{{-- =======================================================================
     4. MODAL KONFIRMASI AKSI VALIDASI (B2C & B2B — TANPA confirm())
     ======================================================================= --}}
<div id="modalActionConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-[460px] p-5">
        <div class="flex items-start gap-3">
            <div id="actionModalIcon" class="w-10 h-10 rounded-lg bg-[#F0FDFA] border border-[#CCFBF1] text-[#00877A] flex items-center justify-center shrink-0">
                <svg width="20" height="20" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
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
                          placeholder="Tuliskan alasan penolakan secara spesifik (minimal 10 karakter)..."
                          class="w-full px-3 py-2 text-[12px] text-slate-800 bg-white border border-rose-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"></textarea>
                <p id="actionModalRejectError" class="hidden text-[11px] font-semibold text-rose-600 mt-1">Alasan penolakan wajib diisi minimal 10 karakter.</p>
            </div>

            <div id="actionModalNoteWrap">
                <label for="actionModalAdminNote" class="block text-[11.5px] font-semibold text-slate-700 mb-1">Catatan Verifikator (Opsional)</label>
                <input id="actionModalAdminNote" name="admin_note" type="text"
                       placeholder="Tambahkan catatan operasional untuk riwayat pesanan..."
                       class="w-full px-3 py-2 text-[12px] text-slate-800 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00877A]/15 focus:border-[#00877A]">
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModal('modalActionConfirm')"
                        class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-[0.98]">
                    Batal
                </button>
                <button type="submit" id="actionModalSubmitBtn"
                        class="px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-semibold transition-all active:scale-[0.98]">
                    Ya, Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =======================================================================
     5. MODAL PANDUAN SOP RTPU
     ======================================================================= --}}
<div id="modalGuide" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-[560px] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-[15px] font-extrabold text-slate-900">Panduan SOP Operasional &amp; Keuangan BLU RTPU</h3>
            <button type="button" onclick="closeModal('modalGuide')" class="p-1 text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="mt-4 space-y-3 text-[12px] text-slate-600">
            <div class="p-3.5 rounded-xl bg-[#F0FDFA] border border-[#CCFBF1]">
                <p class="font-bold text-[#00877A]">Kebijakan Pembayaran BLU Politeknik Negeri Jakarta</p>
                <p class="mt-1 leading-relaxed text-slate-700">
                    Seluruh transaksi komersial B2C dan B2B di PNJ Prime menerapkan skema <strong>100% Lunas</strong> ke rekening resmi penerimaan BLU PNJ (Virtual Account Mandiri / QRIS).
                </p>
            </div>
            <ol class="space-y-2 list-decimal list-inside leading-relaxed">
                <li><strong class="text-slate-800">Validasi B2C:</strong> Periksa identitas mahasiswa/sivitas, kelayakan jadwal lab, lalu klik <em>Approve &amp; Terbitkan Invoice</em> atau tinjau rincian pada <em>Detail Berkas</em>.</li>
                <li><strong class="text-slate-800">Validasi B2B &amp; Invoice:</strong> Telaah profil PT (NIB &amp; NPWP), atur diskon mitra/ongkir pada modal penyesuaian biaya, pastikan PPN 11% &amp; PPh 23 2% tepat, lalu terbitkan invoice resmi BLU.</li>
                <li><strong class="text-slate-800">Cetak Dokumen Resmi:</strong> Gunakan <em>Ekspor Laporan</em> untuk laporan operasional A4 Landscape dan <em>Cetak Invoice (.PDF)</em> untuk dokumen penagihan A4 Portrait.</li>
            </ol>
        </div>
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeModal('modalGuide')" class="px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-semibold active:scale-[0.98]">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

{{-- =======================================================================
     6. MODAL INFO AKUN ADMIN RTPU
     ======================================================================= --}}
<div id="modalAccess" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-[440px] p-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-[15px] font-extrabold text-slate-900">Informasi Akses Admin Kepala RTPU</h3>
            <button type="button" onclick="closeModal('modalAccess')" class="p-1 text-slate-400 hover:text-slate-700">&times;</button>
        </div>
        <div class="mt-4 space-y-2.5 text-[12px]">
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Nama Lengkap</span><span class="font-bold text-slate-900">{{ $portalUser->name ?? Auth::user()->nama_lengkap }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Email Institusi</span><span class="font-mono text-slate-800">{{ $portalUser->email ?? Auth::user()->email }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">NIP / ID Pegawai</span><span class="font-mono text-slate-800 tabular-nums">{{ $portalUser->nim_nip ?? '-' }}</span></div>
            <div class="flex justify-between py-1.5 border-b border-slate-100"><span class="text-slate-500">Unit Kerja</span><span class="font-semibold text-slate-800">{{ $portalUser->prodi_unit ?? 'UPA-RTPU PNJ' }}</span></div>
            <div class="flex justify-between py-1.5"><span class="text-slate-500">Otoritas</span><span class="px-2 py-0.5 rounded bg-[#F0FDFA] text-[#00877A] border border-[#99F6E4] font-bold text-[11px]">Superadmin Unit RTPU</span></div>
        </div>
        <div class="mt-5 flex justify-end">
            <button type="button" onclick="closeModal('modalAccess')" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-semibold active:scale-[0.98]">Tutup</button>
        </div>
    </div>
</div>

{{-- =======================================================================
     7. MODAL INFO MODUL BATCH 2 (ANTI 404)
     ======================================================================= --}}
<div id="modalBatch2Info" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-[440px] p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-[#F0FDFA] border border-[#CCFBF1] text-[#00877A] flex items-center justify-center shrink-0">
                <svg width="20" height="20" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 id="batch2ModalTitle" class="text-[15px] font-extrabold text-slate-900">Modul Batch 2</h3>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">Batch 2</span>
                </div>
                <p id="batch2ModalDesc" class="mt-1.5 text-[12.5px] text-slate-600 leading-relaxed">-</p>
            </div>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
            <a id="batch2PdfLink" href="#" target="_blank" rel="noopener noreferrer"
               class="hidden px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold">
                Cetak Laporan PDF
            </a>
            <button type="button" onclick="closeModal('modalBatch2Info')"
                    class="px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-semibold transition-all active:scale-[0.98]">
                Mengerti
            </button>
        </div>
    </div>
</div>

{{-- =======================================================================
     8. MODAL KONFIRMASI LOGOUT
     ======================================================================= --}}
<div id="modalLogoutConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/55 backdrop-blur-[2px] p-4">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-[400px] p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-200/80 text-rose-600 flex items-center justify-center shrink-0">
                <svg width="20" height="20" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <div>
                <h3 class="text-[15px] font-extrabold text-slate-900">Keluar dari PNJ Prime?</h3>
                <p class="mt-1 text-[12.5px] text-slate-600 leading-relaxed">
                    Pastikan seluruh validasi pesanan dan penerbitan invoice telah tersimpan sebelum mengakhiri sesi ini.
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-5 flex items-center justify-end gap-2">
            @csrf
            <button type="button" onclick="closeModal('modalLogoutConfirm')"
                    class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-[0.98]">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-semibold transition-all active:scale-[0.98]">
                Ya, Keluar
            </button>
        </form>
    </div>
</div>

<script>
    let rtpuInvoiceBaseSubtotal = 0;
    let rtpuCurrentInvoiceOrder = null;

    function formatIdr(value) {
        const num = Math.round(Number(value || 0));
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
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

    function openLogVerifikasiModal() {
        document.getElementById('modalLogVerifikasi').classList.remove('hidden');
    }

    function openBatch2Modal(title, desc, pdfUrl) {
        document.getElementById('batch2ModalTitle').textContent = title;
        document.getElementById('batch2ModalDesc').textContent = desc;
        const pdfLink = document.getElementById('batch2PdfLink');
        if (pdfUrl) {
            pdfLink.href = pdfUrl;
            pdfLink.classList.remove('hidden');
        } else {
            pdfLink.classList.add('hidden');
        }
        document.getElementById('modalBatch2Info').classList.remove('hidden');
    }

    /**
     * Trigger cerdas tombol "Terbitkan Invoice" di Dashboard/Antrean/B2C/B2B:
     * - Jika B2B -> buka Modal 03c Penyesuaian Biaya & Penerbitan Invoice B2B
     * - Jika B2C -> buka Modal Konfirmasi Persetujuan & Penerbitan Invoice B2C
     */
    function triggerIssueOrApprove(orderData) {
        if (orderData.segment === 'b2b') {
            openInvoiceB2bModal(orderData);
        } else {
            const isWaitingPay = orderData.status === 'menunggu_pembayaran';
            openOrderActionModal({
                url: orderData.action_url,
                action: isWaitingPay ? 'mark_paid_process' : 'approve',
                title: isWaitingPay ? 'Konfirmasi Pelunasan & Proses B2C?' : 'Approve & Terbitkan Invoice B2C?',
                message: isWaitingPay
                    ? ('Konfirmasi pelunasan 100% pesanan #' + orderData.order_code + ' atas nama ' + orderData.customer_name + ' dan teruskan ke unit pengelola?')
                    : ('Setujui pesanan #' + orderData.order_code + ' atas nama ' + orderData.customer_name + ' dan terbitkan invoice resmi 100% Lunas BLU PNJ?'),
                confirmText: isWaitingPay ? 'Konfirmasi Lunas & Proses' : 'Approve & Terbitkan Invoice',
                tone: 'teal',
            });
        }
    }

    /**
     * Trigger tombol Tolak langsung dari baris tabel.
     */
    function triggerRejectOrder(orderData) {
        openOrderActionModal({
            url: orderData.action_url,
            action: 'reject',
            title: 'Tolak Pesanan #' + orderData.order_code + '?',
            message: 'Pesanan dari ' + orderData.customer_name + ' akan ditolak/dibatalkan. Mohon cantumkan alasan penolakan resmi.',
            confirmText: 'Ya, Tolak Pesanan',
            tone: 'rose',
        });
    }

    /**
     * Buka Modal 03c Penyesuaian Biaya & Penerbitan Invoice B2B.
     */
    function openInvoiceB2bModal(data) {
        rtpuCurrentInvoiceOrder = data;
        const fin = data.financials || {};
        rtpuInvoiceBaseSubtotal = Number(fin.gross_subtotal || 0);

        document.getElementById('issueInvoiceB2bForm').action = data.invoice_submit_url;
        document.getElementById('invPreviewOrderCode').textContent = '#' + data.order_code;
        document.getElementById('invPreviewCompany').textContent = data.customer_name;
        document.getElementById('invPreviewService').textContent = data.service_summary || '-';
        document.getElementById('invPreviewNumber').textContent = data.invoice_number;

        const existingDiscount = Number(fin.discount_b2b || 0);
        if (existingDiscount > 0 && rtpuInvoiceBaseSubtotal > 0) {
            const pct = (existingDiscount / rtpuInvoiceBaseSubtotal) * 100;
            if (Math.abs(pct - Math.round(pct)) < 0.01 && pct <= 30) {
                document.getElementById('invDiscountType').value = 'percent';
                document.getElementById('invDiscountValue').value = Math.round(pct);
            } else {
                document.getElementById('invDiscountType').value = 'nominal';
                document.getElementById('invDiscountValue').value = existingDiscount;
            }
        } else {
            document.getElementById('invDiscountType').value = 'percent';
            document.getElementById('invDiscountValue').value = 0;
        }

        document.getElementById('invShippingCost').value = Number(fin.shipping_cost || 0);
        document.getElementById('invEstimasiHari').value = Number(data.estimasi_hari || 14);
        document.getElementById('invDueAt').value = data.due_at;
        document.getElementById('invPaymentMethod').value = data.payment_method || 'va_mandiri_blu';
        document.getElementById('invApplyPph23').checked = Boolean(data.apply_pph23);
        document.getElementById('invApplyEmeterai').checked = Boolean(data.apply_emeterai);
        document.getElementById('invSubmitLabel').textContent = data.has_invoice
            ? 'Perbarui & Kirim Invoice Resmi BLU'
            : 'Terbitkan & Kirim Invoice Resmi BLU';

        const rejectBtn = document.getElementById('invRejectTriggerBtn');
        rejectBtn.onclick = function () {
            closeModal('modalIssueInvoiceB2b');
            triggerRejectOrder(data);
        };

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
        let discountPctText = '0%';
        if (discType === 'percent') {
            const pct = Math.min(discRaw, 30);
            discount = Math.round(rtpuInvoiceBaseSubtotal * (pct / 100));
            discountPctText = pct + '%';
        } else {
            discount = Math.min(discRaw, rtpuInvoiceBaseSubtotal);
            const approxPct = rtpuInvoiceBaseSubtotal > 0 ? ((discount / rtpuInvoiceBaseSubtotal) * 100).toFixed(1) : '0';
            discountPctText = approxPct + '%';
        }

        const dpp = Math.max(0, rtpuInvoiceBaseSubtotal - discount + shipping);
        const ppn = Math.round(dpp * 0.11);
        const pph23 = applyPph23 ? Math.round(dpp * 0.02) : 0;
        const meterai = applyMeterai ? 10000 : 0;
        const grandTotal = Math.max(0, dpp + ppn - pph23 + meterai);

        document.getElementById('calcSubtotal').textContent = formatIdr(rtpuInvoiceBaseSubtotal);
        document.getElementById('calcDiscountLabel').textContent = 'Diskon Kemitraan (' + discountPctText + ')';
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

    /**
     * Buka Modal Detail Berkas lengkap (B2C & B2B).
     */
    function openDetailBerkasModal(data) {
        const isB2b = data.segment === 'b2b';
        const fin = data.financials || {};

        document.getElementById('dbOrderCode').textContent = '#' + data.order_code;
        document.getElementById('dbCreatedAt').textContent = data.created_at;

        const segBadge = document.getElementById('dbSegmentBadge');
        segBadge.textContent = isB2b ? 'Mitra B2B' : 'Mahasiswa / B2C';
        segBadge.className = isB2b
            ? 'px-2 py-0.5 rounded-full bg-[#F0FDFA] text-[#00877A] border border-[#99F6E4] text-[10.5px] font-bold'
            : 'px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-[10.5px] font-bold';

        const statusNames = {
            menunggu_verifikasi: ['Menunggu Approval', 'bg-amber-50 text-amber-700 border border-amber-200'],
            menunggu_pembayaran: ['Menunggu Pelunasan', 'bg-sky-50 text-sky-700 border border-sky-200'],
            diproses: ['Diproses Unit', 'bg-indigo-50 text-indigo-700 border border-indigo-200'],
            siap_diambil: ['Siap Diambil', 'bg-teal-50 text-teal-700 border border-teal-200'],
            selesai: ['Selesai', 'bg-emerald-50 text-emerald-700 border border-emerald-200'],
            dibatalkan: ['Dibatalkan', 'bg-rose-50 text-rose-700 border border-rose-200'],
        };
        const stInfo = statusNames[data.status] || [data.status, 'bg-slate-100 text-slate-700'];
        const stBadge = document.getElementById('dbStatusBadge');
        stBadge.textContent = stInfo[0];
        stBadge.className = 'px-2 py-0.5 rounded-full text-[10.5px] font-bold ' + stInfo[1];

        document.getElementById('dbIdentityHeading').textContent = isB2b ? 'Profil Korporasi Mitra B2B' : 'Identitas Pemesan B2C';
        document.getElementById('dbCustomerName').textContent = data.customer_name;

        const idRows = isB2b
            ? [
                ['PIC / Penanggung Jawab', data.pic_name],
                ['NIB Perusahaan', data.nib],
                ['NPWP Badan', data.npwp],
                ['Telepon / Email', data.phone + ' • ' + data.email],
                ['Alamat Penagihan', data.shipping_address],
            ]
            : [
                ['NIM / NIP', data.nim_nip],
                ['Prodi / Unit', data.prodi_unit],
                ['Telepon / Email', data.phone + ' • ' + data.email],
                ['Alamat / Lokasi', data.shipping_address],
            ];
        if (data.booking_date) {
            idRows.push(['Jadwal Lab', data.booking_date + (data.booking_slot ? ' (' + data.booking_slot + ')' : '')]);
        }
        document.getElementById('dbIdentityRows').innerHTML = idRows.map(function (r) {
            return '<div class="flex justify-between gap-3"><span class="text-slate-500 shrink-0">' + escapeHtml(r[0]) + '</span><span class="font-semibold text-slate-800 text-right">' + escapeHtml(r[1]) + '</span></div>';
        }).join('');

        const invRows = [
            ['Nomor Invoice', data.has_invoice ? data.invoice_number : 'Belum Terbit'],
            ['Status Bayar', data.payment_status === 'paid' ? 'Lunas 100%' : (data.payment_status === 'expired' ? 'Kedaluwarsa' : 'Menunggu Pelunasan')],
            ['Kanal BLU', data.payment_method === 'qris' ? 'QRIS BLU PNJ' : 'VA Mandiri BLU'],
            ['Nomor VA', data.va_number],
            ['Jatuh Tempo', data.due_at_label],
            ['Waktu Lunas', data.paid_at_label],
        ];
        document.getElementById('dbInvoiceRows').innerHTML = invRows.map(function (r) {
            return '<div class="flex justify-between gap-3"><span class="text-slate-500 shrink-0">' + escapeHtml(r[0]) + '</span><span class="font-mono font-semibold text-slate-800 text-right">' + escapeHtml(r[1]) + '</span></div>';
        }).join('');

        document.getElementById('dbItemsBody').innerHTML = (data.items || []).map(function (it) {
            return '<tr>' +
                '<td class="px-3.5 py-2.5 text-left"><div class="font-bold text-slate-900">' + escapeHtml(it.name) + '</div><div class="text-[10.5px] text-slate-500 font-mono">' + escapeHtml(it.code) + ' • ' + escapeHtml(it.category) + '</div></td>' +
                '<td class="px-3 py-2.5 text-left text-slate-700">' + escapeHtml(it.unit) + '</td>' +
                '<td class="px-3 py-2.5 text-right font-semibold">' + escapeHtml(it.quantity) + '</td>' +
                '<td class="px-3 py-2.5 text-right">' + formatIdr(it.unit_price).replace('Rp ', '') + '</td>' +
                '<td class="px-3.5 py-2.5 text-right font-bold text-slate-900">' + formatIdr(it.subtotal).replace('Rp ', '') + '</td>' +
                '</tr>';
        }).join('');

        let totalsHtml = '<div class="flex justify-between"><span class="text-slate-600">Subtotal Item</span><span class="font-semibold text-slate-900">' + formatIdr(fin.gross_subtotal) + '</span></div>';
        if (Number(fin.discount_b2b || 0) > 0) {
            totalsHtml += '<div class="flex justify-between text-[#00877A]"><span>Diskon Kemitraan B2B</span><span class="font-semibold">-' + formatIdr(fin.discount_b2b) + '</span></div>';
        }
        if (Number(fin.shipping_cost || 0) > 0) {
            totalsHtml += '<div class="flex justify-between"><span class="text-slate-600">Biaya Logistik / Ongkir</span><span class="font-semibold text-slate-900">' + formatIdr(fin.shipping_cost) + '</span></div>';
        }
        totalsHtml += '<div class="flex justify-between"><span class="text-slate-600">PPN 11%</span><span class="font-semibold text-slate-900">' + formatIdr(fin.ppn_amount) + '</span></div>';
        if (Number(fin.pph23_amount || 0) > 0) {
            totalsHtml += '<div class="flex justify-between text-rose-700"><span>Pemotongan PPh 23 (2%)</span><span class="font-semibold">-' + formatIdr(fin.pph23_amount) + '</span></div>';
        }
        if (Number(fin.emeterai_amount || 0) > 0) {
            totalsHtml += '<div class="flex justify-between"><span class="text-slate-600">Bea e-Meterai</span><span class="font-semibold text-slate-900">' + formatIdr(fin.emeterai_amount) + '</span></div>';
        }
        totalsHtml += '<div class="pt-1.5 mt-1 border-t border-slate-200 flex justify-between text-[13.5px] font-extrabold text-[#00877A]"><span>Total Tagihan (100% Lunas)</span><span>' + formatIdr(fin.grand_total) + '</span></div>';
        document.getElementById('dbTotalsFooter').innerHTML = totalsHtml;

        document.getElementById('dbNotes').textContent = data.notes || 'Tidak ada catatan.';

        // Build Footer Action Buttons cleanly
        const footer = document.getElementById('dbActionFooter');
        footer.innerHTML = '';

        const leftGroup = document.createElement('div');
        leftGroup.className = 'flex flex-wrap items-center gap-2';

        if (data.status !== 'selesai' && data.status !== 'dibatalkan') {
            const btnRej = document.createElement('button');
            btnRej.type = 'button';
            btnRej.className = 'px-3.5 py-2 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-[12px] font-bold transition-all active:scale-[0.98]';
            btnRej.textContent = 'Tolak Pesanan';
            btnRej.onclick = function () { closeModal('modalDetailBerkas'); triggerRejectOrder(data); };
            leftGroup.appendChild(btnRej);
        }

        const rightGroup = document.createElement('div');
        rightGroup.className = 'flex flex-wrap items-center gap-2';

        if (data.has_invoice) {
            const btnPdf = document.createElement('a');
            btnPdf.href = data.print_invoice_url;
            btnPdf.target = '_blank';
            btnPdf.rel = 'noopener noreferrer';
            btnPdf.className = 'inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-bold transition-all active:scale-[0.98]';
            btnPdf.textContent = 'Cetak Invoice (.PDF)';
            rightGroup.appendChild(btnPdf);
        }

        if (isB2b && data.status !== 'dibatalkan' && data.payment_status !== 'paid') {
            const btnInv = document.createElement('button');
            btnInv.type = 'button';
            btnInv.className = 'px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-bold transition-all active:scale-[0.98]';
            btnInv.textContent = data.has_invoice ? 'Sesuaikan Invoice B2B' : 'Terbitkan Invoice Resmi';
            btnInv.onclick = function () { closeModal('modalDetailBerkas'); openInvoiceB2bModal(data); };
            rightGroup.appendChild(btnInv);
        }

        if (data.status === 'menunggu_pembayaran' && data.has_invoice) {
            const btnPaid = document.createElement('button');
            btnPaid.type = 'button';
            btnPaid.className = 'px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-bold transition-all active:scale-[0.98]';
            btnPaid.textContent = 'Konfirmasi Lunas 100% & Proses';
            btnPaid.onclick = function () {
                closeModal('modalDetailBerkas');
                openOrderActionModal({
                    url: data.action_url,
                    action: isB2b ? 'confirm_paid' : 'mark_paid_process',
                    title: 'Konfirmasi Pelunasan 100% BLU?',
                    message: 'Tagihan ' + data.invoice_number + ' akan ditandai Lunas 100% dan pesanan diproses unit.',
                    confirmText: 'Konfirmasi Lunas & Proses',
                    tone: 'teal',
                });
            };
            rightGroup.appendChild(btnPaid);
        } else if (!isB2b && data.status === 'menunggu_verifikasi') {
            const btnApp = document.createElement('button');
            btnApp.type = 'button';
            btnApp.className = 'px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-bold transition-all active:scale-[0.98]';
            btnApp.textContent = 'Approve & Terbitkan Invoice';
            btnApp.onclick = function () { closeModal('modalDetailBerkas'); triggerIssueOrApprove(data); };
            rightGroup.appendChild(btnApp);
        } else if (data.status === 'diproses') {
            const btnReady = document.createElement('button');
            btnReady.type = 'button';
            btnReady.className = 'px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-bold transition-all active:scale-[0.98]';
            btnReady.textContent = 'Tandai Siap Diambil';
            btnReady.onclick = function () {
                closeModal('modalDetailBerkas');
                openOrderActionModal({
                    url: data.action_url,
                    action: 'mark_ready',
                    title: 'Tandai Siap Diambil?',
                    message: 'Pesanan #' + data.order_code + ' akan ditandai Siap Diambil / Diserahterimakan.',
                    confirmText: 'Tandai Siap Diambil',
                    tone: 'teal',
                });
            };
            rightGroup.appendChild(btnReady);

            const btnDone = document.createElement('button');
            btnDone.type = 'button';
            btnDone.className = 'px-3.5 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-bold transition-all active:scale-[0.98]';
            btnDone.textContent = 'Tandai Selesai';
            btnDone.onclick = function () {
                closeModal('modalDetailBerkas');
                openOrderActionModal({
                    url: data.action_url,
                    action: 'mark_done',
                    title: 'Selesaikan Pesanan?',
                    message: 'Pesanan #' + data.order_code + ' akan ditandai Selesai.',
                    confirmText: 'Ya, Tandai Selesai',
                    tone: 'teal',
                });
            };
            rightGroup.appendChild(btnDone);
        } else if (data.status === 'siap_diambil') {
            const btnDone = document.createElement('button');
            btnDone.type = 'button';
            btnDone.className = 'px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-bold transition-all active:scale-[0.98]';
            btnDone.textContent = 'Tandai Selesai';
            btnDone.onclick = function () {
                closeModal('modalDetailBerkas');
                openOrderActionModal({
                    url: data.action_url,
                    action: 'mark_done',
                    title: 'Selesaikan Pesanan?',
                    message: 'Pesanan #' + data.order_code + ' akan ditandai Selesai.',
                    confirmText: 'Ya, Tandai Selesai',
                    tone: 'teal',
                });
            };
            rightGroup.appendChild(btnDone);
        }

        const btnClose = document.createElement('button');
        btnClose.type = 'button';
        btnClose.className = 'px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-[12px] font-semibold';
        btnClose.textContent = 'Tutup';
        btnClose.onclick = function () { closeModal('modalDetailBerkas'); };
        rightGroup.appendChild(btnClose);

        footer.appendChild(leftGroup);
        footer.appendChild(rightGroup);

        document.getElementById('modalDetailBerkas').classList.remove('hidden');
    }

    /**
     * Modal Konfirmasi Kustom untuk Aksi Validasi (Verifikasi, Lunas, Siap Diambil, Selesai, Tolak).
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
            submitBtn.className = 'px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[12px] font-semibold transition-all active:scale-[0.98]';
            iconWrap.className = 'w-10 h-10 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0';
            iconWrap.innerHTML = '<svg width="20" height="20" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
        } else {
            submitBtn.className = 'px-4 py-2 rounded-lg bg-[#00877A] hover:bg-[#006E63] text-white text-[12px] font-semibold transition-all active:scale-[0.98]';
            iconWrap.className = 'w-10 h-10 rounded-lg bg-[#F0FDFA] border border-[#CCFBF1] text-[#00877A] flex items-center justify-center shrink-0';
            iconWrap.innerHTML = '<svg width="20" height="20" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
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

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            ['modalIssueInvoiceB2b', 'modalDetailBerkas', 'modalLogVerifikasi', 'modalActionConfirm', 'modalGuide', 'modalAccess', 'modalBatch2Info', 'modalLogoutConfirm'].forEach(closeModal);
        }
    });
</script>
