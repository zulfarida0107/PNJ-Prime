<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }} — PNJ Prime</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 landscape;
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
            line-height: 1.45;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .screen-toolbar {
            max-width: 297mm;
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

        .btn:hover {
            opacity: 0.92;
        }

        .sheet {
            max-width: 297mm;
            min-height: 180mm;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 16mm 16mm 14mm 16mm;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        }

        /* Kop Surat Resmi */
        .letterhead {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 12px;
            border-bottom: 3px double #0F172A;
            margin-bottom: 16px;
        }

        .letterhead-logo {
            width: 58px;
            height: 58px;
            border-radius: 8px;
            border: 1.5px solid #003366;
            background: #EFF6FF;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #003366;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .letterhead-logo small {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-top: 1px;
        }

        .letterhead-text {
            flex: 1;
            text-align: center;
        }

        .letterhead-ministry {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #334155;
            margin: 0;
        }

        .letterhead-institution {
            font-size: 15.5px;
            font-weight: 800;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #0F172A;
            margin: 2px 0;
        }

        .letterhead-unit {
            font-size: 11px;
            font-weight: 700;
            color: #003366;
            margin: 0 0 3px 0;
        }

        .letterhead-address {
            font-size: 9.5px;
            color: #475569;
            margin: 0;
        }

        .letterhead-spacer {
            width: 58px;
            flex-shrink: 0;
        }

        /* Header Laporan */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 14px;
            gap: 16px;
        }

        .report-title {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.3px;
            color: #0F172A;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }

        .report-subtitle {
            font-size: 10.5px;
            color: #475569;
            margin: 0;
        }

        .report-meta {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }

        .report-meta strong {
            color: #0F172A;
        }

        /* Ringkasan KPI */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 14px;
        }

        .summary-box {
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 7px 10px;
            background: #F8FAFC;
        }

        .summary-label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #475569;
            margin: 0 0 2px 0;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 800;
            color: #0F172A;
            margin: 0;
        }

        .summary-value.navy {
            color: #003366;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 10px;
        }

        thead th {
            background-color: #0F172A;
            color: #FFFFFF;
            font-weight: 700;
            text-align: left;
            padding: 7px 8px;
            border: 1px solid #0F172A;
            font-size: 9.5px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        tbody td {
            padding: 6.5px 8px;
            border: 1px solid #CBD5E1;
            vertical-align: top;
            color: #1E293B;
        }

        tbody tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        tfoot td {
            padding: 7px 8px;
            border: 1px solid #94A3B8;
            background-color: #F1F5F9;
            font-weight: 800;
            color: #0F172A;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .muted { color: #64748B; font-size: 9px; }

        .status-pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: 700;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .st-selesai { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
        .st-proses { background: #EFF6FF; color: #1E3A8A; border-color: #BFDBFE; }
        .st-pending { background: #FFFBEB; color: #B45309; border-color: #FDE68A; }
        .st-batal { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

        /* Blok Tanda Tangan */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 22px;
            page-break-inside: avoid;
        }

        .doc-footnote {
            font-size: 9px;
            color: #64748B;
            max-width: 56%;
        }

        .signature-box {
            width: 250px;
            text-align: left;
            font-size: 10.5px;
            color: #0F172A;
        }

        .signature-space {
            height: 58px;
        }

        .signature-name {
            font-weight: 800;
            text-decoration: underline;
            margin: 0;
        }

        .signature-nip {
            font-size: 9.5px;
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
    {{-- Toolbar Layar --}}
    <div class="screen-toolbar">
        <div class="screen-toolbar-info">
            <strong>Pratinjau Dokumen Resmi A4 Landscape</strong> &mdash; Laporan Operasional Admin RTPU siap dicetak atau disimpan sebagai PDF.
        </div>
        <div class="screen-toolbar-actions">
            <button type="button" class="btn" onclick="window.close()">Tutup Tab</button>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
        </div>
    </div>

    <div class="sheet">
        {{-- Kop Surat Resmi Kementerian & Politeknik Negeri Jakarta --}}
        <div class="letterhead">
            <div class="letterhead-logo">
                <span>PNJ</span>
                <small>RTPU</small>
            </div>
            <div class="letterhead-text">
                <p class="letterhead-ministry">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                <h1 class="letterhead-institution">Politeknik Negeri Jakarta</h1>
                <p class="letterhead-unit">Unit Penunjang Akademik Rekayasa Teknologi dan Produk Unggulan (UPA-RTPU)</p>
                <p class="letterhead-address">
                    Jl. Prof. Dr. G.A. Siwabessy, Kampus Baru UI, Depok 16425 &bull; Telp. (021) 7270036 &bull; Laman: www.pnj.ac.id
                </p>
            </div>
            <div class="letterhead-spacer"></div>
        </div>

        {{-- Judul & Metadata Dokumen --}}
        <div class="report-header">
            <div>
                <h2 class="report-title">{{ $reportTitle }}</h2>
                <p class="report-subtitle">
                    Sistem Informasi Hilirisasi &amp; Komersialisasi Layanan BLU (PNJ Prime) &bull; Tahun Anggaran 2026/2027
                </p>
            </div>
            <div class="report-meta tabular-nums">
                <div>Tanggal Cetak: <strong>{{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</strong></div>
                <div>Dicetak Oleh: <strong>{{ $portalUser->name ?? 'Admin RTPU' }}</strong></div>
            </div>
        </div>

        {{-- Ringkasan KPI --}}
        <div class="summary-grid tabular-nums">
            <div class="summary-box">
                <p class="summary-label">Total Pesanan</p>
                <p class="summary-value">{{ $summary['total_orders'] }} <span class="muted">({{ $summary['b2c_count'] }} B2C / {{ $summary['b2b_count'] }} B2B)</span></p>
            </div>
            <div class="summary-box">
                <p class="summary-label">Menunggu Validasi</p>
                <p class="summary-value">{{ $summary['pending_count'] }} Pesanan</p>
            </div>
            <div class="summary-box">
                <p class="summary-label">Produk Menunggu Kurasi</p>
                <p class="summary-value">{{ $summary['pending_curation'] }} Usulan</p>
            </div>
            <div class="summary-box">
                <p class="summary-label">Pendapatan Lunas (BLU)</p>
                <p class="summary-value navy">Rp {{ number_format($summary['validated_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="summary-box">
                <p class="summary-label">Total Nilai Transaksi</p>
                <p class="summary-value">Rp {{ number_format($summary['total_gross_value'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel Rekap Operasional Pesanan --}}
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th style="width: 118px;">Kode Pesanan / Invoice</th>
                    <th>Pemesan / Instansi Mitra</th>
                    <th class="text-center" style="width: 48px;">Kanal</th>
                    <th style="width: 155px;">Unit &amp; Layanan Utama</th>
                    <th style="width: 82px;">Tipe Order</th>
                    <th class="text-right" style="width: 98px;">Nominal (Rp)</th>
                    <th class="text-center" style="width: 78px;">Pembayaran</th>
                    <th class="text-center" style="width: 95px;">Status Pesanan</th>
                    <th class="text-center" style="width: 72px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $index => $ord)
                    @php
                        $firstItem = $ord->items->first();
                        $unitName = $firstItem?->product?->unit?->name ?? '-';
                        $prodName = $firstItem?->product?->name ?? '-';
                        $customerName = $ord->segment === 'b2b'
                            ? ($ord->user?->company_name ?: ($ord->user?->name ?? '-'))
                            : ($ord->user?->name ?? '-');
                        $customerSub = $ord->segment === 'b2b'
                            ? ('NPWP: ' . ($ord->user?->npwp ?? '-'))
                            : ($ord->user?->prodi_unit ?? 'Sivitas PNJ');
                        $orderVal = $ord->invoice
                            ? (float) $ord->invoice->grand_total
                            : round((float) $ord->items->sum('subtotal') * 1.11, 2);

                        $typeLabel = match ($ord->order_type) {
                            'ready_stock' => 'Ready Stock',
                            'pre_order' => 'Pre-Order',
                            'lab_reservation' => 'Reservasi Lab',
                            default => $ord->order_type,
                        };

                        $statusMap = [
                            'menunggu_verifikasi' => ['Menunggu Verifikasi', 'st-pending'],
                            'menunggu_pembayaran' => ['Menunggu Bayar', 'st-pending'],
                            'diproses' => ['Diproses Unit', 'st-proses'],
                            'siap_diambil' => ['Siap Diambil', 'st-proses'],
                            'selesai' => ['Selesai', 'st-selesai'],
                            'dibatalkan' => ['Dibatalkan', 'st-batal'],
                        ];
                        [$stLabel, $stClass] = $statusMap[$ord->status] ?? [$ord->status, 'st-proses'];
                    @endphp
                    <tr>
                        <td class="text-center tabular-nums">{{ $index + 1 }}</td>
                        <td class="tabular-nums">
                            <div class="font-mono font-bold">{{ $ord->order_code }}</div>
                            <div class="muted font-mono">{{ $ord->invoice?->invoice_number ?? 'Belum Invoice' }}</div>
                        </td>
                        <td>
                            <div class="font-bold">{{ $customerName }}</div>
                            <div class="muted">{{ $customerSub }}</div>
                        </td>
                        <td class="text-center font-bold">{{ strtoupper($ord->segment) }}</td>
                        <td>
                            <div class="font-bold">{{ $prodName }}</div>
                            <div class="muted">{{ $unitName }}</div>
                        </td>
                        <td>{{ $typeLabel }}</td>
                        <td class="text-right font-bold tabular-nums">{{ number_format($orderVal, 0, ',', '.') }}</td>
                        <td class="text-center">
                            {{ $ord->invoice?->payment_status === 'paid' ? 'Lunas 100%' : ($ord->invoice?->payment_status === 'expired' ? 'Kedaluwarsa' : 'Belum Lunas') }}
                        </td>
                        <td class="text-center">
                            <span class="status-pill {{ $stClass }}">{{ $stLabel }}</span>
                        </td>
                        <td class="text-center tabular-nums">{{ optional($ord->created_at)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 20px; color: #64748B;">
                            Tidak ada data transaksi pesanan pada filter laporan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($orders->count() > 0)
                <tfoot>
                    <tr class="tabular-nums">
                        <td colspan="6" class="text-right">TOTAL NILAI TRANSAKSI TERCATAT ({{ $orders->count() }} PESANAN)</td>
                        <td class="text-right">Rp {{ number_format($summary['total_gross_value'], 0, ',', '.') }}</td>
                        <td colspan="3" class="text-center">Tervalidasi Lunas: Rp {{ number_format($summary['validated_revenue'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- Blok Tanda Tangan Resmi Kepala UPA-RTPU --}}
        <div class="signature-section">
            <div class="doc-footnote">
                <p style="margin: 0 0 3px 0;"><strong>Catatan Dokumen Resmi:</strong></p>
                <p style="margin: 0;">
                    Dokumen ini dicetak secara sah melalui sistem PNJ Prime Modul Admin RTPU sebagai laporan operasional dan penerimaan layanan Badan Layanan Umum (BLU) Politeknik Negeri Jakarta. Seluruh transaksi menerapkan skema pembayaran 100% Lunas.
                </p>
            </div>
            <div class="signature-box">
                <p style="margin: 0;">Depok, {{ $printedAt->translatedFormat('d F Y') }}</p>
                <p style="margin: 2px 0 0 0; font-weight: 600;">Mengetahui &amp; Mengesahkan,</p>
                <p style="margin: 0; font-weight: 700;">Kepala UPA-RTPU Politeknik Negeri Jakarta</p>
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
