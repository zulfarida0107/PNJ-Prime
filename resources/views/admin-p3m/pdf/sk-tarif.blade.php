<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salinan SK Tarif {{ $skNumber }} — PNJ Prime</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 18mm;
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
            line-height: 1.55;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .font-mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        /* Toolbar layar (otomatis hilang saat cetak) */
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
            background: #0F766E;
            border-color: #0F766E;
            color: #FFFFFF;
        }

        .btn:hover {
            opacity: 0.92;
        }

        /* Kanvas kertas A4 Portrait */
        .sheet {
            max-width: 210mm;
            min-height: 260mm;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 18mm 18mm 16mm 18mm;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        }

        /* Kop Surat Resmi UPA-RTPU PNJ */
        .letterhead {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 3px double #0F172A;
            margin-bottom: 18px;
        }

        .letterhead-logo {
            width: 56px;
            height: 56px;
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
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-top: 1px;
        }

        .letterhead-text {
            flex: 1;
            text-align: center;
        }

        .letterhead-ministry {
            font-size: 9.5px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #334155;
            margin: 0;
        }

        .letterhead-institution {
            font-size: 14.5px;
            font-weight: 800;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #0F172A;
            margin: 2px 0;
        }

        .letterhead-unit {
            font-size: 10.5px;
            font-weight: 700;
            color: #0F766E;
            margin: 0 0 3px 0;
        }

        .letterhead-address {
            font-size: 9px;
            color: #475569;
            margin: 0;
        }

        .letterhead-spacer {
            width: 56px;
            flex-shrink: 0;
        }

        /* Judul SK */
        .sk-title-block {
            text-align: center;
            margin-bottom: 16px;
        }

        .sk-heading {
            font-size: 12.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #0F172A;
        }

        .sk-subheading {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 3px 0;
            color: #1E293B;
        }

        .sk-number {
            display: inline-block;
            margin-top: 4px;
            padding: 3px 12px;
            border-radius: 4px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            font-size: 10.5px;
            font-weight: 700;
            color: #0F172A;
        }

        .sk-about {
            margin: 12px 0 14px 0;
            text-align: center;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1E293B;
        }

        /* Bagian Konsiderans */
        .section-block {
            margin-bottom: 14px;
        }

        .konsiderans-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10.5px;
        }

        .konsiderans-table td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .konsiderans-label {
            width: 105px;
            font-weight: 700;
            color: #0F172A;
        }

        .konsiderans-colon {
            width: 12px;
            text-align: center;
        }

        .memutuskan-title {
            text-align: center;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 12px 0 10px 0;
            color: #0F172A;
        }

        /* Tabel Rincian Produk & Tarif */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 14px 0;
            font-size: 10.5px;
        }

        .detail-table th {
            background: #1E293B;
            color: #FFFFFF;
            text-align: left;
            padding: 7px 10px;
            border: 1px solid #1E293B;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .detail-table td {
            padding: 7px 10px;
            border: 1px solid #CBD5E1;
            vertical-align: top;
        }

        .detail-table td.field-label {
            width: 34%;
            font-weight: 600;
            background: #F8FAFC;
            color: #334155;
        }

        .tarif-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 14px 0;
            font-size: 10.5px;
        }

        .tarif-table th {
            background: #1E293B;
            color: #FFFFFF;
            padding: 7px 10px;
            border: 1px solid #1E293B;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .tarif-table td {
            padding: 8px 10px;
            border: 1px solid #CBD5E1;
            font-weight: 700;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-teal { color: #0F766E; }

        .clauses {
            margin: 10px 0 16px 0;
            padding-left: 18px;
            font-size: 10px;
            color: #334155;
        }

        .clauses li {
            margin-bottom: 4px;
        }

        /* Blok Tanda Tangan */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .verification-box {
            width: 48%;
            padding: 10px 12px;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            background: #F8FAFC;
            font-size: 9px;
            color: #475569;
        }

        .signature-box {
            width: 240px;
            text-align: left;
            font-size: 10.5px;
            color: #0F172A;
        }

        .signature-space {
            height: 60px;
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
    @php
        $orderTypeLabel = match ($product->order_type) {
            'ready_stock' => 'Ready Stock (Persediaan Langsung)',
            'pre_order' => 'Pre-Order / Make-to-Order (MTO)',
            'lab_reservation' => 'Reservasi Laboratorium / Jasa Pengujian',
            default => $product->order_type,
        };
    @endphp

    {{-- Toolbar Layar (Hilang Saat Print/Save PDF) --}}
    <div class="screen-toolbar">
        <div class="screen-toolbar-info">
            <strong>Salinan Resmi SK Tarif (A4 Portrait)</strong> &mdash; {{ $skNumber }}
        </div>
        <div class="screen-toolbar-actions">
            <button type="button" class="btn" onclick="window.close()">Tutup Tab</button>
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
        </div>
    </div>

    <div class="sheet">
        {{-- Kop Surat Resmi UPA-RTPU Politeknik Negeri Jakarta --}}
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

        {{-- Judul & Nomor SK --}}
        <div class="sk-title-block">
            <h2 class="sk-heading">Salinan Surat Keputusan Penetapan Tarif Komersialisasi</h2>
            <p class="sk-subheading">Kepala UPA-RTPU Politeknik Negeri Jakarta</p>
            <div class="sk-number font-mono tabular-nums">NOMOR: {{ $skNumber }}</div>
        </div>

        <p class="sk-about">
            TENTANG<br>
            PENETAPAN TARIF LAYANAN &amp; OTORISASI PUBLIKASI KATALOG PRODUK UNGGULAN PNJ PRIME
        </p>

        {{-- Konsiderans Ringkas --}}
        <table class="konsiderans-table">
            <tr>
                <td class="konsiderans-label">Menimbang</td>
                <td class="konsiderans-colon">:</td>
                <td>
                    bahwa berdasarkan hasil kurasi teknis, verifikasi komponen Harga Pokok Produksi (HPP), dan Rapat Pleno Hilirisasi UPA-RTPU Politeknik Negeri Jakarta, produk inovasi yang diusulkan oleh Unit P3M dinyatakan layak untuk dikomersialisasikan melalui katalog resmi PNJ Prime.
                </td>
            </tr>
            <tr>
                <td class="konsiderans-label">Mengingat</td>
                <td class="konsiderans-colon">:</td>
                <td>
                    Pedoman Operasional Pengelolaan Tarif Badan Layanan Umum (BLU) Politeknik Negeri Jakarta serta Pedoman Hilirisasi Produk Unggulan No. 042/PNJ/P3M/2026.
                </td>
            </tr>
        </table>

        <p class="memutuskan-title">MEMUTUSKAN</p>

        {{-- Tabel Identitas Produk --}}
        <table class="detail-table">
            <thead>
                <tr>
                    <th colspan="2">I. Identitas Produk &amp; Unit Pengelola (Master Data P08)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="field-label">Nama Produk / Layanan Inovasi</td>
                    <td><strong>{{ $product->name }}</strong></td>
                </tr>
                <tr>
                    <td class="field-label">Kode Pengajuan / Kode Etalase</td>
                    <td class="font-mono tabular-nums">
                        <strong>#{{ $product->code }}</strong> &nbsp;/&nbsp; <strong class="text-teal">{{ $catalogCode }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="field-label">Unit Pengelola / Jurusan</td>
                    <td>{{ $product->unit->name ?? '-' }} ({{ $product->unit->code ?? '-' }})</td>
                </tr>
                <tr>
                    <td class="field-label">Kategori &amp; Sub-Kategori</td>
                    <td>{{ $product->category->name ?? '-' }} &bull; {{ $product->subCategory->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="field-label">Tipe Order &amp; Pengusul</td>
                    <td>{{ $orderTypeLabel }} &bull; {{ $product->submitter->name ?? ($portalUser->name ?? 'Admin P3M') }}</td>
                </tr>
                <tr>
                    <td class="field-label">Tanggal Pengesahan SK</td>
                    <td class="tabular-nums">{{ $approvedAt->translatedFormat('d F Y') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Tabel Penetapan Tarif --}}
        <table class="tarif-table tabular-nums">
            <thead>
                <tr>
                    <th style="text-align: left;">II. Komponen Tarif BLU</th>
                    <th class="text-right">Nominal (IDR)</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600;">Harga Pokok Produksi (HPP) Terverifikasi</td>
                    <td class="text-right">Rp {{ number_format((float) $product->hpp, 0, ',', '.') }}</td>
                    <td class="text-center" style="font-weight: 500; color: #475569;">Basis SBM BLU 2026</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Tarif Resmi Katalog Umum (B2C)</td>
                    <td class="text-right text-teal">Rp {{ number_format((float) $product->price_b2c, 0, ',', '.') }}</td>
                    <td class="text-center" style="font-weight: 600; color: #0F766E;">Margin BLU {{ number_format($marginPct, 1) }}%</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Tarif Khusus Mitra Industri / Instansi (B2B)</td>
                    <td class="text-right">Rp {{ number_format((float) $product->price_b2b, 0, ',', '.') }}</td>
                    <td class="text-center" style="font-weight: 500; color: #475569;">Kontrak / PO B2B</td>
                </tr>
            </tbody>
        </table>

        {{-- Ketentuan Penutup --}}
        <ol class="clauses">
            <li>Tarif yang ditetapkan pada surat keputusan ini belum termasuk Pajak Pertambahan Nilai (PPN 11%) yang akan diperhitungkan otomatis pada penerbitan faktur resmi PNJ Prime.</li>
            <li>Produk telah memperoleh otorisasi penayangan aktif pada katalog digital PNJ Prime dan dapat ditransaksikan sesuai ketentuan pembayaran BLU Politeknik Negeri Jakarta.</li>
            <li>Salinan surat keputusan ini berlaku sejak tanggal pengesahan dan dapat dievaluasi secara berkala oleh UPA-RTPU Politeknik Negeri Jakarta.</li>
        </ol>

        {{-- Blok Tanda Tangan Pimpinan RTPU --}}
        <div class="signature-section">
            <div class="verification-box tabular-nums">
                <p style="margin: 0 0 3px 0; font-weight: 700; color: #0F172A;">Verifikasi Dokumen Digital</p>
                <p style="margin: 0;">No. SK: <strong class="font-mono">{{ $skNumber }}</strong></p>
                <p style="margin: 2px 0 0 0;">Kode Katalog: <strong class="font-mono">{{ $catalogCode }}</strong></p>
                <p style="margin: 2px 0 0 0;">Dicetak: {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>

            <div class="signature-box">
                <p style="margin: 0;">Ditetapkan di: Depok</p>
                <p style="margin: 2px 0 8px 0;" class="tabular-nums">Pada tanggal: {{ $approvedAt->translatedFormat('d F Y') }}</p>
                <p style="margin: 0; font-weight: 700;">Kepala UPA-RTPU</p>
                <p style="margin: 0; font-weight: 600;">Politeknik Negeri Jakarta</p>
                <div class="signature-space"></div>
                <p class="signature-name">Ir. Agus Setiawan, M.Eng.</p>
                <p class="signature-nip tabular-nums">NIP. 197503112001121001</p>
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
