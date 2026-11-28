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

        /* Toolbar layar (otomatis hilang saat cetak) */
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
            background: #0F766E;
            border-color: #0F766E;
            color: #FFFFFF;
        }

        .btn:hover {
            opacity: 0.92;
        }

        /* Kanvas kertas A4 Landscape */
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
            border: 1.5px solid #0F766E;
            background: #F0FDFA;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #0F766E;
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
            color: #0F766E;
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

        /* Judul & Meta Laporan */
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

        /* Tabel Laporan */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 10px;
        }

        thead th {
            background-color: #1E293B;
            color: #FFFFFF;
            font-weight: 700;
            text-align: left;
            padding: 7px 8px;
            border: 1px solid #1E293B;
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

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .muted { color: #64748B; font-size: 9px; }

        .status-pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .st-tayang { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
        .st-menunggu { background: #F0FDFA; color: #0F766E; border-color: #99F6E4; }
        .st-sk { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
        .st-revisi { background: #FFF1F2; color: #BE123C; border-color: #FECDD3; }
        .st-ditolak { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }
        .st-draft { background: #F1F5F9; color: #475569; border-color: #CBD5E1; }

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
            max-width: 55%;
        }

        .signature-box {
            width: 240px;
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
    {{-- Toolbar Layar (Hilang Saat Print/Save PDF) --}}
    <div class="screen-toolbar">
        <div class="screen-toolbar-info">
            <strong>Pratinjau Dokumen Resmi A4 Landscape</strong> &mdash; Siap dicetak atau disimpan sebagai PDF.
        </div>
        <div class="screen-toolbar-actions">
            <button type="button" class="btn" onclick="window.close()">Tutup Tab</button>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
        </div>
    </div>

    <div class="sheet">
        {{-- Kop Surat Resmi Politeknik Negeri Jakarta --}}
        <div class="letterhead">
            <div class="letterhead-logo">
                <span>PNJ</span>
                <small>PRIME</small>
            </div>
            <div class="letterhead-text">
                <p class="letterhead-ministry">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                <h1 class="letterhead-institution">Politeknik Negeri Jakarta</h1>
                <p class="letterhead-unit">Pusat Penelitian dan Pengabdian kepada Masyarakat (P3M) &bull; UPA-RTPU</p>
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
                    Sistem Informasi Hilirisasi &amp; Komersialisasi Produk Riset (PNJ Prime) &bull; Tahun Anggaran 2026/2027
                </p>
            </div>
            <div class="report-meta tabular-nums">
                <div>Tanggal Cetak: <strong>{{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</strong></div>
                <div>Dicetak Oleh: <strong>{{ $portalUser->name ?? 'Admin P3M' }}</strong></div>
            </div>
        </div>

        {{-- Tabel Data Produk --}}
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 34px;">No</th>
                    <th style="width: 110px;">Kode Pengajuan</th>
                    <th>Nama Produk &amp; Klasifikasi P08</th>
                    <th style="width: 150px;">Unit Pengelola</th>
                    <th style="width: 85px;">Tipe Order</th>
                    <th class="text-right" style="width: 95px;">HPP (Rp)</th>
                    <th class="text-right" style="width: 100px;">Harga B2C (Rp)</th>
                    <th class="text-center" style="width: 115px;">Status Kurasi</th>
                    <th class="text-center" style="width: 82px;">Diperbarui</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $index => $item)
                    @php
                        $isCancelled = $item->status === 'ditolak' && str_contains(strtolower((string) $item->curation_notes), 'dibatalkan');
                        $statusMap = [
                            'tayang' => ['Tayang di Katalog', 'st-tayang'],
                            'menunggu_kurasi_rtpu' => ['Menunggu RTPU', 'st-menunggu'],
                            'menunggu_sk_pimpinan' => ['Menunggu SK', 'st-sk'],
                            'revisi' => ['Perlu Revisi', 'st-revisi'],
                            'draft' => ['Draf', 'st-draft'],
                            'ditolak' => [$isCancelled ? 'Dibatalkan' : 'Ditolak Permanen', $isCancelled ? 'st-draft' : 'st-ditolak'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$item->status] ?? [$item->status, 'st-draft'];
                        $orderLabel = match ($item->order_type) {
                            'ready_stock' => 'Ready Stock',
                            'pre_order' => 'Pre-Order',
                            'lab_reservation' => 'Reservasi Lab',
                            default => $item->order_type,
                        };
                    @endphp
                    <tr>
                        <td class="text-center tabular-nums">{{ $index + 1 }}</td>
                        <td class="font-mono font-bold tabular-nums">#{{ $item->code }}</td>
                        <td>
                            <div class="font-bold">{{ $item->name }}</div>
                            <div class="muted">{{ $item->category->name ?? '-' }} &bull; {{ $item->subCategory->name ?? '-' }}</div>
                        </td>
                        <td>{{ $item->unit->name ?? '-' }}</td>
                        <td>{{ $orderLabel }}</td>
                        <td class="text-right tabular-nums">{{ number_format((float) $item->hpp, 0, ',', '.') }}</td>
                        <td class="text-right font-bold tabular-nums">{{ number_format((float) $item->price_b2c, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-center tabular-nums">{{ optional($item->updated_at)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 20px; color: #64748B;">
                            Tidak ada data pengajuan produk untuk dicetak pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Blok Tanda Tangan Resmi --}}
        <div class="signature-section">
            <div class="doc-footnote">
                <p style="margin: 0 0 3px 0;"><strong>Catatan Dokumen:</strong></p>
                <p style="margin: 0;">
                    Dokumen ini dicetak secara resmi melalui sistem PNJ Prime Modul Admin P3M sesuai Pedoman Operasional RTPU No. 042/PNJ/P3M/2026. Seluruh tarif belum termasuk PPN 11%.
                </p>
            </div>
            <div class="signature-box">
                <p style="margin: 0;">Depok, {{ $printedAt->translatedFormat('d F Y') }}</p>
                <p style="margin: 2px 0 0 0; font-weight: 600;">Mengetahui,</p>
                <p style="margin: 0; font-weight: 600;">Koordinator P3M Politeknik Negeri Jakarta</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ $portalUser->name ?? 'Dr. Rina Wijaya, M.T.' }}</p>
                <p class="signature-nip tabular-nums">NIP. {{ $portalUser->nim_nip ?? '198204152008012002' }}</p>
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
