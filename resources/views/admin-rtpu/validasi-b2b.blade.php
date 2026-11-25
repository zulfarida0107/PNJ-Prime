@extends('admin-rtpu.layouts.app')

@section('title', 'Detail & Validasi Pesanan B2B')

@section('content')
<div class="space-y-4">
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.rtpu.dashboard') }}" class="hover:text-[#003366]">Dashboard RTPU</a>
                <span>/</span>
                <a href="{{ route('admin.rtpu.antrean', ['segment' => 'b2b']) }}" class="hover:text-[#003366]">Antrean B2B</a>
                <span>/</span>
                <span class="text-slate-800">{{ $order ? $order->order_code : 'Validasi B2B' }}</span>
            </nav>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Detail &amp; Validasi Pesanan B2B (Mitra Industri)
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Verifikasi identitas korporasi Mitra B2B, tarif komersial B2B, komponen pajak PPN 11% &amp; PPh 23, serta penerbitan Invoice Resmi BLU (100% Lunas).
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.rtpu.print.laporan', ['segment' => 'b2b']) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak Rekap B2B (.PDF)</span>
            </a>
        </div>
    </div>

    {{-- Strip 4 Metrik Ringkas B2B --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 tabular-nums">
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Menunggu Validasi / Bayar</p>
            <p class="text-[20px] font-extrabold text-slate-900 mt-0.5">{{ $b2bStats['pending'] }} <span class="text-[11.5px] font-medium text-slate-500">Pesanan</span></p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Total Nilai Tagihan B2B</p>
            <p class="text-[18px] font-extrabold text-[#003366] mt-0.5 truncate">Rp {{ number_format($b2bStats['queue_value'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Invoice B2B Diterbitkan</p>
            <p class="text-[20px] font-extrabold text-slate-900 mt-0.5">{{ $b2bStats['issued_invoices'] }} <span class="text-[11.5px] font-medium text-slate-500">Dokumen</span></p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Realisasi Pelunasan B2B</p>
            <p class="text-[18px] font-extrabold text-emerald-700 mt-0.5 truncate">Rp {{ number_format($b2bStats['paid_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Workspace 2 Kolom: Daftar Pesanan B2B (Kiri 4) + Detail Korporasi & Keuangan (Kanan 8) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        {{-- Kolom Kiri: Selector Pesanan B2B --}}
        <div class="lg:col-span-4 bg-white rounded-lg border border-slate-200/80 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-[13px] font-extrabold text-slate-900">Antrean Pesanan B2B</h2>
                <span class="text-[11px] font-bold text-slate-500 tabular-nums">{{ $b2bOrders->count() }} Data</span>
            </div>

            <div id="liveSearchBanner" class="hidden px-3.5 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[11.5px] text-slate-600">
                <span><strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> cocok "<strong id="liveSearchQuery"></strong>"</span>
                <button type="button" onclick="clearGlobalSearch()" class="font-semibold text-[#003366] hover:underline">Reset</button>
            </div>

            <div class="divide-y divide-slate-100 max-h-[640px] overflow-y-auto">
                @forelse ($b2bOrders as $itemOrd)
                    @php
                        $isSelected = $order && $order->id === $itemOrd->id;
                        $companyTitle = $itemOrd->user?->company_name ?: ($itemOrd->user?->name ?? '-');
                        $firstProd = $itemOrd->items->first()?->product?->name ?? '-';
                        $ordVal = $itemOrd->invoice ? (float) $itemOrd->invoice->grand_total : round((float) $itemOrd->items->sum('subtotal') * 1.11, 2);
                        $stBadge = match ($itemOrd->status) {
                            'menunggu_verifikasi' => ['Verifikasi', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                            'menunggu_pembayaran' => ['Invoice Terbit', 'bg-sky-50 text-sky-700 ring-sky-600/20'],
                            'diproses' => ['Diproses', 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
                            'siap_diambil' => ['Siap Diambil', 'bg-teal-50 text-teal-700 ring-teal-600/20'],
                            'selesai' => ['Selesai', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                            'dibatalkan' => ['Dibatalkan', 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                            default => [$itemOrd->status, 'bg-slate-100 text-slate-700 ring-slate-600/20'],
                        };
                        $searchBlob = strtolower(implode(' ', [
                            $itemOrd->order_code,
                            $companyTitle,
                            $itemOrd->user?->name ?? '',
                            $itemOrd->user?->npwp ?? '',
                            $firstProd,
                            $itemOrd->invoice?->invoice_number ?? '',
                            $stBadge[0],
                        ]));
                    @endphp
                    <a href="{{ route('admin.rtpu.validasi-b2b', $itemOrd) }}"
                       data-searchable="true" data-search-text="{{ $searchBlob }}"
                       class="block px-4 py-3 transition-colors {{ $isSelected ? 'bg-[#EFF6FF]/70 border-l-[3px] border-[#003366]' : 'hover:bg-slate-50/80 border-l-[3px] border-transparent' }}">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-[11px] font-bold text-[#003366] tabular-nums">{{ $itemOrd->order_code }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold ring-1 ring-inset {{ $stBadge[1] }}">{{ $stBadge[0] }}</span>
                        </div>
                        <p class="text-[12.5px] font-bold text-slate-900 mt-1 truncate">{{ $companyTitle }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $firstProd }}</p>
                        <div class="mt-1.5 flex items-center justify-between text-[11px] tabular-nums">
                            <span class="text-slate-400">{{ $itemOrd->invoice?->invoice_number ?? 'Belum Invoice' }}</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($ordVal, 0, ',', '.') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-[12px] text-slate-500">Belum ada pesanan B2B.</div>
                @endforelse
            </div>

            <div id="liveSearchEmptyState" class="hidden p-6 text-center text-[12px] text-slate-500">
                Tidak ada pesanan B2B cocok "<span id="liveSearchEmptyQuery"></span>".
            </div>
        </div>

        {{-- Kolom Kanan: Profil Korporasi, Item Tarif B2B, Kalkulasi Keuangan, & Penerbitan Invoice --}}
        <div class="lg:col-span-8 space-y-4">
            @if ($order && $financials)
                @php
                    $inv = $order->invoice;
                    $mitra = $order->user;
                    $companyName = $mitra?->company_name ?: ($mitra?->name ?? 'Mitra Industri B2B');

                    $statusLabelMap = [
                        'menunggu_verifikasi' => ['Menunggu Verifikasi & Invoice', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                        'menunggu_pembayaran' => ['Invoice Terbit — Menunggu Pelunasan', 'bg-sky-50 text-sky-700 ring-sky-600/20'],
                        'diproses' => ['Lunas — Diproses Unit', 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
                        'siap_diambil' => ['Siap Diserahterimakan', 'bg-teal-50 text-teal-700 ring-teal-600/20'],
                        'selesai' => ['Transaksi Selesai', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                        'dibatalkan' => ['Ditolak / Dibatalkan', 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                    ];
                    [$stLabel, $stClass] = $statusLabelMap[$order->status] ?? [$order->status, 'bg-slate-100 text-slate-700 ring-slate-600/20'];

                    $invoiceModalPayload = [
                        'submit_url' => route('admin.rtpu.validasi-b2b.invoice', $order),
                        'has_invoice' => (bool) $inv,
                        'invoice_number' => $previewInvoiceNumber,
                        'order_code' => $order->order_code,
                        'company_name' => $companyName,
                        'va_number' => $previewVaNumber,
                        'gross_subtotal' => $financials['gross_subtotal'],
                        'discount_b2b' => $financials['discount_b2b'],
                        'shipping_cost' => $financials['shipping_cost'],
                        'estimasi_hari' => $order->estimasi_hari ?? 7,
                        'due_at' => optional($inv?->due_at)->format('Y-m-d') ?? now()->addDays(7)->format('Y-m-d'),
                        'payment_method' => $inv?->payment_method ?? 'va_mandiri_blu',
                        'apply_pph23' => $inv ? ((float) $inv->pph23_amount > 0) : $financials['pph23_applicable'],
                        'apply_emeterai' => $inv ? ((float) $inv->emeterai_amount > 0) : true,
                        'transfer_notes' => 'Pelunasan 100% melalui Virtual Account Bank Mandiri BLU PNJ sebelum tanggal jatuh tempo.',
                    ];
                @endphp

                {{-- Header & Profil Korporasi Mitra B2B --}}
                <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-3.5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-[14px] font-extrabold text-[#003366] tabular-nums">{{ $order->order_code }}</span>
                                <span class="px-2 py-0.5 rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20 text-[10.5px] font-bold">B2B Korporasi</span>
                                <span class="px-2 py-0.5 rounded text-[10.5px] font-bold ring-1 ring-inset {{ $stClass }}">{{ $stLabel }}</span>
                            </div>
                            <p class="text-[11.5px] text-slate-500 mt-1 tabular-nums">
                                Tanggal Pengajuan: {{ optional($order->created_at)->translatedFormat('d F Y, H:i') }} WIB
                                &bull; Skema: <strong class="text-slate-800">Pembayaran 100% Lunas BLU</strong>
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            @if ($inv)
                                <a href="{{ route('admin.rtpu.print.invoice', $order) }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                    <span>Cetak Invoice Resmi (.PDF)</span>
                                </a>
                                @if ($inv->payment_status !== 'paid' && $order->status !== 'dibatalkan')
                                    <button type="button" onclick='openInvoiceB2bModal(@json($invoiceModalPayload))'
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-95">
                                        <span>Sesuaikan Invoice</span>
                                    </button>
                                @endif
                            @elseif ($order->status !== 'dibatalkan')
                                <button type="button" onclick='openInvoiceB2bModal(@json($invoiceModalPayload))'
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                                    <span>Terbitkan Invoice Resmi</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Identitas Korporasi Mitra B2B & Info Dokumen Tagihan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[12px]">
                        <div class="rounded-lg bg-slate-50/80 border border-slate-200/70 p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Profil Korporasi Mitra B2B</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold ring-1 ring-inset {{ ($mitra?->kyc_status === 'approved') ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-amber-600/20' }}">
                                    KYC: {{ strtoupper($mitra?->kyc_status ?? 'PENDING') }}
                                </span>
                            </div>
                            <p class="text-[14px] font-extrabold text-slate-900">{{ $companyName }}</p>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-[11.5px] tabular-nums">
                                <span class="text-slate-500">NIB Perusahaan</span>
                                <span class="font-mono font-semibold text-slate-800">{{ $mitra?->nib ?? '-' }}</span>
                                <span class="text-slate-500">NPWP Badan</span>
                                <span class="font-mono font-semibold text-slate-800">{{ $mitra?->npwp ?? '-' }}</span>
                                <span class="text-slate-500">PIC / Kontak</span>
                                <span class="font-semibold text-slate-800">{{ $mitra?->name ?? '-' }}</span>
                                <span class="text-slate-500">Email &amp; Telepon</span>
                                <span class="text-slate-700 truncate">{{ $mitra?->phone ?? '-' }}</span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200/70 text-[11.5px]">
                                <span class="text-slate-500 block">Alamat Penagihan &amp; Pengiriman:</span>
                                <span class="text-slate-800 font-medium">{{ $order->shipping_address ?: ($mitra?->address ?? '-') }}</span>
                            </div>
                        </div>

                        <div class="rounded-lg bg-slate-50/80 border border-slate-200/70 p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Status Invoice &amp; Pelaksanaan</p>
                                @if ($inv)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold ring-1 ring-inset {{ $inv->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : ($inv->payment_status === 'expired' ? 'bg-rose-50 text-rose-700 ring-rose-600/20' : 'bg-sky-50 text-sky-700 ring-sky-600/20') }}">
                                        {{ $inv->payment_status === 'paid' ? 'Lunas 100%' : ($inv->payment_status === 'expired' ? 'Kedaluwarsa' : 'Menunggu Pelunasan') }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 text-[10px] font-bold">Belum Terbit</span>
                                @endif
                            </div>

                            <div class="space-y-1.5 text-[11.5px] tabular-nums">
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Nomor Invoice</span>
                                    <span class="font-mono font-bold text-[#003366]">{{ $inv?->invoice_number ?? 'Draft (' . $previewInvoiceNumber . ')' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">VA Bank Mandiri BLU</span>
                                    <span class="font-mono font-semibold text-slate-800">{{ $inv?->va_number ?? $previewVaNumber }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Jatuh Tempo (Due Date)</span>
                                    <span class="font-semibold text-slate-800">{{ $inv?->due_at ? $inv->due_at->translatedFormat('d M Y') : 'Belum ditetapkan' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Estimasi Pengerjaan</span>
                                    <span class="font-semibold text-slate-800">{{ $order->estimasi_hari ? $order->estimasi_hari . ' Hari Kerja' : '-' }}</span>
                                </div>
                                @if ($order->booking_date)
                                    <div class="flex justify-between gap-2 pt-1 border-t border-slate-200/70">
                                        <span class="text-slate-500">Jadwal Lab</span>
                                        <span class="font-bold text-[#003366]">{{ $order->booking_date->translatedFormat('d M Y') }} {{ $order->booking_slot ? '(' . $order->booking_slot . ')' : '' }}</span>
                                    </div>
                                @endif
                                @if ($order->attachment_path)
                                    <div class="flex justify-between gap-2">
                                        <span class="text-slate-500">Berkas Teknis</span>
                                        <span class="font-mono text-[11px] font-semibold text-slate-700 truncate max-w-[180px]">{{ basename($order->attachment_path) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rincian Item Tarif Komersial B2B + Kalkulasi Keuangan Presisi --}}
                <div class="bg-white rounded-lg border border-slate-200/80 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-[13.5px] font-extrabold text-slate-900">Rincian Item &amp; Kalkulasi Keuangan Komersial B2B</h3>
                        <span class="px-2 py-0.5 rounded bg-[#EFF6FF] text-[#003366] ring-1 ring-inset ring-[#1E3A8A]/20 text-[10.5px] font-bold">
                            Tarif Komersial B2B &bull; 100% Lunas
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-[12px] border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-2.5 text-left">Layanan / Produk &amp; Kategori</th>
                                    <th class="px-3 py-2.5 text-left">Unit Pengelola</th>
                                    <th class="px-3 py-2.5 text-right">Qty</th>
                                    <th class="px-3 py-2.5 text-right">Tarif B2B (Rp)</th>
                                    <th class="px-4 py-2.5 text-right">Subtotal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 tabular-nums">
                                @foreach ($order->items as $item)
                                    @php
                                        $priceB2b = (float) ($item->unit_price > 0 ? $item->unit_price : ($item->product?->price_b2b ?? 0));
                                        $lineSubtotal = (float) ($item->subtotal > 0 ? $item->subtotal : ($priceB2b * $item->quantity));
                                    @endphp
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-2.5 text-left">
                                            <p class="font-bold text-slate-900">{{ $item->product?->name ?? '-' }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">{{ $item->product?->code ?? '-' }} &bull; {{ $item->product?->category?->name ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5 text-left text-slate-700">{{ $item->product?->unit?->name ?? '-' }}</td>
                                        <td class="px-3 py-2.5 text-right font-semibold text-slate-900">{{ $item->quantity }}</td>
                                        <td class="px-3 py-2.5 text-right text-slate-700">{{ number_format($priceB2b, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2.5 text-right font-bold text-slate-900">{{ number_format($lineSubtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Ringkasan Pajak & Tagihan 100% Lunas --}}
                    <div class="p-4 bg-slate-50/70 border-t border-slate-200/80">
                        <div class="max-w-md ml-auto space-y-1.5 text-[12.5px] tabular-nums">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">Subtotal Tarif B2B</span>
                                <span class="font-semibold text-slate-900 text-right">Rp {{ number_format($financials['gross_subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @if ($financials['discount_b2b'] > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Diskon Kemitraan B2B</span>
                                    <span class="font-semibold text-emerald-700 text-right">-Rp {{ number_format($financials['discount_b2b'], 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($financials['shipping_cost'] > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Biaya Logistik / Ekspedisi</span>
                                    <span class="font-semibold text-slate-900 text-right">Rp {{ number_format($financials['shipping_cost'], 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="pt-1.5 border-t border-slate-200/80 flex items-center justify-between">
                                <span class="font-bold text-slate-800">Dasar Pengenaan Pajak (DPP)</span>
                                <span class="font-bold text-slate-900 text-right">Rp {{ number_format($financials['dpp_net'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600">PPN 11% (Faktur Pajak Standar BLU)</span>
                                <span class="font-semibold text-slate-900 text-right">Rp {{ number_format($financials['ppn_amount'], 0, ',', '.') }}</span>
                            </div>
                            @if ($financials['pph23_amount'] > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Pemotongan PPh 23 (2% Jasa/Lab)</span>
                                    <span class="font-semibold text-rose-700 text-right">-Rp {{ number_format($financials['pph23_amount'], 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($financials['emeterai_amount'] > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-600">Bea e-Meterai Elektronik</span>
                                    <span class="font-semibold text-slate-900 text-right">Rp {{ number_format($financials['emeterai_amount'], 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="pt-2.5 mt-1 border-t border-slate-300/80 flex items-center justify-between">
                                <div>
                                    <p class="text-[13px] font-extrabold text-slate-900">Total Tagihan (100% Lunas)</p>
                                    <p class="text-[10.5px] text-slate-500">Pelunasan penuh ke rekening BLU PNJ (tanpa DP)</p>
                                </div>
                                <span class="text-[17px] font-extrabold text-[#003366] text-right">Rp {{ number_format($financials['grand_total'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Operasional & Panel Aksi Status B2B --}}
                @php
                    $b2bActionUrl = route('admin.rtpu.validasi-b2b.action', $order);
                    $b2bPayloads = [
                        'reject' => [
                            'url' => $b2bActionUrl,
                            'action' => 'reject',
                            'title' => 'Tolak Pesanan B2B?',
                            'message' => 'Pesanan B2B ' . $order->order_code . ' akan dibatalkan dan invoice yang belum dibayar ditandai kedaluwarsa. Alasan penolakan wajib diisi.',
                            'confirmText' => 'Ya, Tolak Pesanan',
                            'tone' => 'rose',
                        ],
                        'confirm_paid' => [
                            'url' => $b2bActionUrl,
                            'action' => 'confirm_paid',
                            'title' => 'Konfirmasi Pelunasan 100% BLU?',
                            'message' => 'Invoice ' . ($inv?->invoice_number ?? '-') . ' akan ditandai Lunas 100% dan pesanan ' . $order->order_code . ' masuk tahap pengerjaan unit.',
                            'confirmText' => 'Konfirmasi Lunas & Proses',
                            'tone' => 'emerald',
                        ],
                        'mark_ready' => [
                            'url' => $b2bActionUrl,
                            'action' => 'mark_ready',
                            'title' => 'Tandai Siap Diserahterimakan?',
                            'message' => 'Hasil layanan/produk untuk ' . $companyName . ' akan ditandai Siap Diambil / Diserahterimakan.',
                            'confirmText' => 'Tandai Siap Diambil',
                            'tone' => 'navy',
                        ],
                        'mark_done' => [
                            'url' => $b2bActionUrl,
                            'action' => 'mark_done',
                            'title' => 'Selesaikan Transaksi B2B?',
                            'message' => 'Pesanan B2B ' . $order->order_code . ' akan ditandai Selesai beserta konfirmasi pelunasan penuh.',
                            'confirmText' => 'Ya, Tandai Selesai',
                            'tone' => 'emerald',
                        ],
                    ];
                @endphp
                <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Catatan Pesanan &amp; Log Keuangan BLU</p>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 text-[12px] text-slate-700 whitespace-pre-line leading-relaxed">{{ $order->notes ?: 'Tidak ada catatan operasional.' }}</div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2.5">
                        <div>
                            @if ($order->status !== 'dibatalkan' && $order->status !== 'selesai')
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2bPayloads["reject"]))'
                                        class="px-3.5 py-2 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[12px] font-semibold transition-all active:scale-95">
                                    Tolak Pesanan
                                </button>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if (! $inv && $order->status !== 'dibatalkan')
                                <button type="button" onclick='openInvoiceB2bModal(@json($invoiceModalPayload))'
                                        class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Terbitkan Invoice Resmi
                                </button>
                            @endif

                            @if ($inv && $inv->payment_status !== 'paid' && $order->status !== 'dibatalkan')
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2bPayloads["confirm_paid"]))'
                                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Konfirmasi Pelunasan 100% &amp; Proses
                                </button>
                            @endif

                            @if ($order->status === 'diproses')
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2bPayloads["mark_ready"]))'
                                        class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Tandai Siap Diambil
                                </button>
                            @endif

                            @if (in_array($order->status, ['diproses', 'siap_diambil'], true))
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2bPayloads["mark_done"]))'
                                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Tandai Selesai
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg border border-slate-200/80 p-12 text-center">
                    <p class="text-[14px] font-bold text-slate-800">Belum ada pesanan B2B untuk divalidasi</p>
                    <p class="text-[12px] text-slate-500 mt-1">Pilih pesanan B2B dari daftar antrean di sebelah kiri atau kembali ke Daftar Antrean Pesanan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
