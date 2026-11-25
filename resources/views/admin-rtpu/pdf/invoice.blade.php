<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice?->invoice_number ?? $order->order_code }} — PNJ Prime</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
            color: #0F172A;
            background-color: #E2E8F0;
            margin: 0;
            padding: 20px;
            font-size: 11px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .screen-toolbar {
            max-width: 210mm;
            margin: 0 auto 14px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 10px 16px;
        }

        .screen-toolbar-info {
            font-size: 12px;
            color: #334155;
        }

        .screen-toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #CBD5E1;
            background: #FFFFFF;
            color: #1E293B;
            font-family: inherit;
        }

        .btn-primary {
            background: #003366;
            border-color: #003366;
            color: #FFFFFF;
        }

        .sheet {
            max-width: 210mm;
            min-height: 267mm;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 15mm;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        }

        /* Kop Surat Resmi */
        .letterhead {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 3px double #0F172A;
            margin-bottom: 16px;
        }

        .letterhead-logo {
            width: 54px;
            height: 54px;
            border-radius: 8px;
            border: 1.5px solid #003366;
            background: #EFF6FF;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #003366;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        .letterhead-logo small {
            font-size: 7.5px;
            letter-spacing: 0.8px;
            font-weight: 700;
        }

        .letterhead-text {
            flex: 1;
        }

        .letterhead-ministry {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin: 0;
        }

        .letterhead-institution {
            font-size: 14.5px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0F172A;
            margin: 1px 0;
        }

        .letterhead-unit {
            font-size: 10.5px;
            font-weight: 700;
            color: #003366;
            margin: 0 0 2px 0;
        }

        .letterhead-address {
            font-size: 8.8px;
            color: #64748B;
            margin: 0;
        }

        .invoice-title-block {
            text-align: right;
            flex-shrink: 0;
        }

        .invoice-title {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #003366;
            margin: 0;
        }

        .invoice-stamp {
            display: inline-block;
            margin-top: 4px;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .stamp-paid {
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #6EE7B7;
        }

        .stamp-unpaid {
            background: #EFF6FF;
            color: #003366;
            border: 1px solid #93C5FD;
        }

        /* Grid Identitas & Meta Invoice */
        .meta-grid {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }

        .info-card {
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 10px 12px;
            background: #F8FAFC;
        }

        .info-card-title {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748B;
            margin: 0 0 6px 0;
        }

        .buyer-name {
            font-size: 12.5px;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 4px 0;
        }

        .kv-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            font-size: 10px;
            margin-bottom: 2.5px;
        }

        .kv-label {
            color: #64748B;
        }

        .kv-val {
            font-weight: 700;
            color: #0F172A;
            text-align: right;
        }

        .font-mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        /* Tabel Item */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }

        thead th {
            background-color: #003366;
            color: #FFFFFF;
            font-weight: 700;
            text-align: left;
            padding: 7px 8px;
            border: 1px solid #003366;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        tbody td {
            padding: 7px 8px;
            border: 1px solid #CBD5E1;
            vertical-align: top;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .muted { color: #64748B; font-size: 9px; }

        /* Ringkasan Keuangan & Rekening BLU */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
            align-items: start;
        }

        .bank-box {
            border: 1px solid #BFDBFE;
            background: #EFF6FF;
            border-radius: 6px;
            padding: 11px 12px;
        }

        .bank-title {
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #003366;
            margin: 0 0 6px 0;
        }

        .bank-va {
            font-size: 14px;
            font-weight: 800;
            color: #003366;
            letter-spacing: 0.8px;
            margin: 4px 0;
        }

        .totals-box {
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 10px 12px;
            background: #FFFFFF;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 10.5px;
        }

        .total-row.divider {
            border-top: 1px solid #E2E8F0;
            margin-top: 4px;
            padding-top: 6px;
        }

        .total-row.grand {
            border-top: 2px solid #003366;
            margin-top: 6px;
            padding-top: 8px;
            font-size: 13px;
            font-weight: 800;
            color: #003366;
        }

        /* Blok Pengesahan */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 22px;
            page-break-inside: avoid;
        }

        .terms-box {
            max-width: 55%;
            font-size: 9px;
            color: #475569;
        }

        .signature-box {
            width: 230px;
            font-size: 10px;
        }

        .signature-space {
            height: 54px;
        }

        .signature-name {
            font-weight: 800;
            text-decoration: underline;
            margin: 0;
        }

        .signature-nip {
            font-size: 9px;
            color: #475569;
            margin: 2px 0 0 0;
        }

        @media print {
            body {
                background: #FFFFFF;
                padding: 0;
            }
            .screen-toolbar {
                display: none !important;
            }
            .sheet {
                box-shadow: none;
                padding: 0;
                max-width: none;
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    @php
        $buyer = $order->user;
        $isB2b = $order->segment === 'b2b';
        $buyerTitle = $isB2b ? ($buyer?->company_name ?: ($buyer?->name ?? '-')) : ($buyer?->name ?? '-');
        $invNo = $invoice?->invoice_number ?? ('DRAFT-' . $order->order_code);
        $isPaid = $invoice && $invoice->payment_status === 'paid';
    @endphp

    {{-- Toolbar Layar --}}
    <div class="screen-toolbar">
        <div class="screen-toolbar-info">
            <strong>Pratinjau Invoice Resmi A4 Portrait</strong> &mdash; {{ $invNo }}
        </div>
        <div class="screen-toolbar-actions">
            <button type="button" class="btn" onclick="window.close()">Tutup Tab</button>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
        </div>
    </div>

    <div class="sheet">
        {{-- Kop Surat PNJ + Judul Invoice --}}
        <div class="letterhead">
            <div class="letterhead-logo">
                <span>PNJ</span>
                <small>BLU</small>
            </div>
            <div class="letterhead-text">
                <p class="letterhead-ministry">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                <h1 class="letterhead-institution">Politeknik Negeri Jakarta</h1>
                <p class="letterhead-unit">UPA Rekayasa Teknologi &amp; Produk Unggulan (UPA-RTPU) &bull; BLU PNJ</p>
                <p class="letterhead-address">
                    Jl. Prof. Dr. G.A. Siwabessy, Kampus Baru UI, Depok 16425 &bull; NPWP Instansi: 00.123.456.7-412.000
                </p>
            </div>
            <div class="invoice-title-block">
                <p class="invoice-title">INVOICE</p>
                <span class="invoice-stamp {{ $isPaid ? 'stamp-paid' : 'stamp-unpaid' }}">
                    {{ $isPaid ? 'LUNAS 100%' : 'TAGIHAN 100% LUNAS' }}
                </span>
            </div>
        </div>

        {{-- Identitas Pembeli & Metadata Invoice --}}
        <div class="meta-grid">
            <div class="info-card">
                <p class="info-card-title">Ditagihkan Kepada ({{ $isB2b ? 'Mitra Industri B2B' : 'Pemesan B2C' }})</p>
                <p class="buyer-name">{{ $buyerTitle }}</p>
                <div class="kv-row">
                    <span class="kv-label">{{ $isB2b ? 'PIC / Penanggung Jawab' : 'NIM / NIP' }}</span>
                    <span class="kv-val tabular-nums">{{ $isB2b ? ($buyer?->name ?? '-') : ($buyer?->nim_nip ?? '-') }}</span>
                </div>
                @if ($isB2b)
                    <div class="kv-row">
                        <span class="kv-label">NPWP Perusahaan</span>
                        <span class="kv-val font-mono tabular-nums">{{ $buyer?->npwp ?? '-' }}</span>
                    </div>
                    <div class="kv-row">
                        <span class="kv-label">NIB Perusahaan</span>
                        <span class="kv-val font-mono tabular-nums">{{ $buyer?->nib ?? '-' }}</span>
                    </div>
                @else
                    <div class="kv-row">
                        <span class="kv-label">Program Studi / Unit</span>
                        <span class="kv-val">{{ $buyer?->prodi_unit ?? '-' }}</span>
                    </div>
                @endif
                <div class="kv-row">
                    <span class="kv-label">Alamat Penagihan</span>
                    <span class="kv-val" style="max-width: 65%;">{{ $order->shipping_address ?: ($buyer?->address ?? '-') }}</span>
                </div>
            </div>

            <div class="info-card tabular-nums">
                <p class="info-card-title">Rincian Dokumen Tagihan</p>
                <div class="kv-row">
                    <span class="kv-label">Nomor Invoice</span>
                    <span class="kv-val font-mono" style="color: #003366;">{{ $invNo }}</span>
                </div>
                <div class="kv-row">
                    <span class="kv-label">Kode Pesanan</span>
                    <span class="kv-val font-mono">{{ $order->order_code }}</span>
                </div>
                <div class="kv-row">
                    <span class="kv-label">Tanggal Terbit</span>
                    <span class="kv-val">{{ optional($invoice?->created_at ?? $printedAt)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="kv-row">
                    <span class="kv-label">Jatuh Tempo (Due Date)</span>
                    <span class="kv-val">{{ $invoice?->due_at ? $invoice->due_at->translatedFormat('d F Y') : $printedAt->copy()->addDays(7)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="kv-row">
                    <span class="kv-label">Skema Pembayaran</span>
                    <span class="kv-val" style="color: #003366;">100% Lunas (Tanpa DP)</span>
                </div>
                @if ($order->estimasi_hari)
                    <div class="kv-row">
                        <span class="kv-label">Estimasi Pengerjaan</span>
                        <span class="kv-val">{{ $order->estimasi_hari }} Hari Kerja</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tabel Item Tagihan --}}
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th>Uraian Produk / Jasa / Layanan Lab</th>
                    <th style="width: 130px;">Unit Pengelola</th>
                    <th class="text-right" style="width: 48px;">Qty</th>
                    <th class="text-right" style="width: 105px;">Harga Satuan (Rp)</th>
                    <th class="text-right" style="width: 110px;">Subtotal (Rp)</th>
                </tr>
            </thead>
            <tbody class="tabular-nums">
                @foreach ($order->items as $idx => $item)
                    @php
                        $unitPrice = (float) ($item->unit_price > 0 ? $item->unit_price : ($isB2b ? ($item->product?->price_b2b ?? 0) : ($item->product?->price_b2c ?? 0)));
                        $lineTotal = (float) ($item->subtotal > 0 ? $item->subtotal : ($unitPrice * $item->quantity));
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-bold">{{ $item->product?->name ?? '-' }}</div>
                            <div class="muted font-mono">{{ $item->product?->code ?? '-' }} &bull; {{ $item->product?->category?->name ?? '-' }}</div>
                        </td>
                        <td>{{ $item->product?->unit?->name ?? '-' }}</td>
                        <td class="text-right font-bold">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($unitPrice, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">{{ number_format($lineTotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Rekening Penerimaan BLU PNJ + Rincian Pajak & Total --}}
        <div class="bottom-grid">
            <div class="bank-box">
                <p class="bank-title">Informasi Rekening Penerimaan BLU PNJ</p>
                <div style="font-size: 10px; color: #1E293B;">
                    <div><strong>Bank Penerima:</strong> Bank Mandiri (Persero) Tbk — Layanan BLU</div>
                    <div><strong>Nama Rekening:</strong> RPL 139 PNJ untuk Operasional BLU</div>
                    <div><strong>Metode:</strong> {{ $invoice?->payment_method === 'qris' ? 'QRIS Resmi BLU PNJ' : 'Virtual Account Mandiri BLU' }}</div>
                </div>
                <p class="bank-va font-mono tabular-nums">
                    VA: {{ $invoice?->va_number ?? ('8801' . $printedAt->format('Ymd') . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT)) }}
                </p>
                <p style="font-size: 9px; color: #334155; margin: 4px 0 0 0;">
                    Mohon melakukan pelunasan penuh <strong>100% Lunas</strong> sesuai nominal tagihan akhir sebelum tanggal jatuh tempo. Pengerjaan layanan dimulai setelah dana terverifikasi pada rekening BLU PNJ.
                </p>
            </div>

            <div class="totals-box tabular-nums">
                <div class="total-row">
                    <span>Subtotal Item</span>
                    <span class="font-bold">Rp {{ number_format($financials['gross_subtotal'], 0, ',', '.') }}</span>
                </div>
                @if ($financials['discount_b2b'] > 0)
                    <div class="total-row">
                        <span>Diskon Kemitraan B2B</span>
                        <span style="color: #047857; font-weight: 700;">-Rp {{ number_format($financials['discount_b2b'], 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($financials['shipping_cost'] > 0)
                    <div class="total-row">
                        <span>Biaya Logistik / Ekspedisi</span>
                        <span class="font-bold">Rp {{ number_format($financials['shipping_cost'], 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-row divider">
                    <span class="font-bold">Dasar Pengenaan Pajak (DPP)</span>
                    <span class="font-bold">Rp {{ number_format($financials['dpp_net'], 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>PPN 11% (Faktur Pajak Standar BLU)</span>
                    <span class="font-bold">Rp {{ number_format($financials['ppn_amount'], 0, ',', '.') }}</span>
                </div>
                @if ($financials['pph23_amount'] > 0)
                    <div class="total-row">
                        <span>Pemotongan PPh 23 (2% Jasa/Lab)</span>
                        <span style="color: #BE123C; font-weight: 700;">-Rp {{ number_format($financials['pph23_amount'], 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($financials['emeterai_amount'] > 0)
                    <div class="total-row">
                        <span>Bea e-Meterai Elektronik</span>
                        <span class="font-bold">Rp {{ number_format($financials['emeterai_amount'], 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-row grand">
                    <span>TOTAL TAGIHAN (100% LUNAS)</span>
                    <span>Rp {{ number_format($financials['grand_total'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Pengesahan Resmi --}}
        <div class="signature-section">
            <div class="terms-box">
                <p style="margin: 0 0 3px 0; font-weight: 700; color: #0F172A;">Ketentuan Penagihan &amp; Bukti Sah:</p>
                <p style="margin: 0;">
                    1. Invoice ini merupakan dokumen penagihan komersial resmi Badan Layanan Umum Politeknik Negeri Jakarta (PNJ Prime).<br>
                    2. Pembayaran wajib lunas 100% melalui kanal resmi BLU PNJ.<br>
                    3. Faktur Pajak elektronik dan bukti pelunasan diterbitkan setelah dana efektif masuk ke rekening BLU PNJ.
                </p>
            </div>

            <div class="signature-box">
                <p style="margin: 0;">Depok, {{ $printedAt->translatedFormat('d F Y') }}</p>
                <p style="margin: 2px 0 0 0; font-weight: 700;">Pejabat Penagihan &amp; Pengesahan BLU</p>
                <p style="margin: 0; font-weight: 600;">Kepala UPA-RTPU PNJ</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ $pimpinanRtpu->name ?? 'Ir. Agus Setiawan, M.Eng.' }}</p>
                <p class="signature-nip tabular-nums">NIP. {{ $pimpinanRtpu->nim_nip ?? '197503112001121001' }}</p>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 350);
        });
    </script>
</body>
</html>
