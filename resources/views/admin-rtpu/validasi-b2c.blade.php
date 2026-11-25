@extends('admin-rtpu.layouts.app')

@section('title', 'Detail & Validasi Pesanan B2C')

@section('content')
<div class="space-y-4">
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 mb-1">
                <a href="{{ route('admin.rtpu.dashboard') }}" class="hover:text-[#003366]">Dashboard RTPU</a>
                <span>/</span>
                <a href="{{ route('admin.rtpu.antrean', ['segment' => 'b2c']) }}" class="hover:text-[#003366]">Antrean B2C</a>
                <span>/</span>
                <span class="text-slate-800">{{ $order ? $order->order_code : 'Validasi B2C' }}</span>
            </nav>
            <h1 class="text-[20px] font-extrabold text-slate-900 tracking-tight leading-snug">
                Detail &amp; Validasi Pesanan B2C (Sivitas / Umum)
            </h1>
            <p class="text-[12.5px] text-slate-500 mt-0.5">
                Verifikasi identitas pemesan, rincian item tarif B2C, status pembayaran QRIS/VA, dan pembaruan pengerjaan unit.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.rtpu.print.laporan', ['segment' => 'b2c']) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200/90 text-[12px] font-semibold text-slate-700 transition-all active:scale-95">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span>Cetak Rekap B2C (.PDF)</span>
            </a>
        </div>
    </div>

    {{-- Strip 4 Metrik Ringkas B2C --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 tabular-nums">
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Menunggu Validasi B2C</p>
            <p class="text-[20px] font-extrabold text-slate-900 mt-0.5">{{ $b2cStats['pending'] }} <span class="text-[11.5px] font-medium text-slate-500">Pesanan</span></p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Total Item Dipesan</p>
            <p class="text-[20px] font-extrabold text-slate-900 mt-0.5">{{ $b2cStats['total_items'] }} <span class="text-[11.5px] font-medium text-slate-500">Unit/Sesi</span></p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Pembayaran Lunas</p>
            <p class="text-[20px] font-extrabold text-emerald-700 mt-0.5">{{ $b2cStats['paid_invoices'] }} <span class="text-[11.5px] font-medium text-slate-500">Invoice</span></p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200/80 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500">Total Transaksi B2C</p>
            <p class="text-[20px] font-extrabold text-[#003366] mt-0.5">{{ $b2cStats['total_b2c_orders'] }} <span class="text-[11.5px] font-medium text-slate-500">Pesanan</span></p>
        </div>
    </div>

    {{-- Workspace 2 Kolom: Daftar Antrean B2C (Kiri 4) + Detail & Validasi (Kanan 8) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        {{-- Kolom Kiri: Selector Pesanan B2C --}}
        <div class="lg:col-span-4 bg-white rounded-lg border border-slate-200/80 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-[13px] font-extrabold text-slate-900">Antrean Pesanan B2C</h2>
                <span class="text-[11px] font-bold text-slate-500 tabular-nums">{{ $b2cOrders->count() }} Data</span>
            </div>

            <div id="liveSearchBanner" class="hidden px-3.5 py-2 bg-slate-50 border-b border-slate-200/70 flex items-center justify-between text-[11.5px] text-slate-600">
                <span><strong id="liveSearchCount" class="text-slate-900 tabular-nums">0</strong> cocok "<strong id="liveSearchQuery"></strong>"</span>
                <button type="button" onclick="clearGlobalSearch()" class="font-semibold text-[#003366] hover:underline">Reset</button>
            </div>

            <div class="divide-y divide-slate-100 max-h-[600px] overflow-y-auto">
                @forelse ($b2cOrders as $itemOrd)
                    @php
                        $isSelected = $order && $order->id === $itemOrd->id;
                        $firstProd = $itemOrd->items->first()?->product?->name ?? '-';
                        $ordVal = $itemOrd->invoice ? (float) $itemOrd->invoice->grand_total : round((float) $itemOrd->items->sum('subtotal') * 1.11, 2);
                        $stBadge = match ($itemOrd->status) {
                            'menunggu_verifikasi' => ['Verifikasi', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                            'menunggu_pembayaran' => ['Bayar', 'bg-sky-50 text-sky-700 ring-sky-600/20'],
                            'diproses' => ['Diproses', 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
                            'siap_diambil' => ['Siap Diambil', 'bg-teal-50 text-teal-700 ring-teal-600/20'],
                            'selesai' => ['Selesai', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                            'dibatalkan' => ['Dibatalkan', 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                            default => [$itemOrd->status, 'bg-slate-100 text-slate-700 ring-slate-600/20'],
                        };
                        $searchBlob = strtolower(implode(' ', [
                            $itemOrd->order_code,
                            $itemOrd->user?->name ?? '',
                            $itemOrd->user?->nim_nip ?? '',
                            $firstProd,
                            $stBadge[0],
                        ]));
                    @endphp
                    <a href="{{ route('admin.rtpu.validasi-b2c', $itemOrd) }}"
                       data-searchable="true" data-search-text="{{ $searchBlob }}"
                       class="block px-4 py-3 transition-colors {{ $isSelected ? 'bg-[#EFF6FF]/70 border-l-[3px] border-[#003366]' : 'hover:bg-slate-50/80 border-l-[3px] border-transparent' }}">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-[11px] font-bold text-[#003366] tabular-nums">{{ $itemOrd->order_code }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold ring-1 ring-inset {{ $stBadge[1] }}">{{ $stBadge[0] }}</span>
                        </div>
                        <p class="text-[12.5px] font-bold text-slate-900 mt-1 truncate">{{ $itemOrd->user?->name ?? '-' }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $firstProd }}</p>
                        <div class="mt-1.5 flex items-center justify-between text-[11px] tabular-nums">
                            <span class="text-slate-400">{{ optional($itemOrd->created_at)->format('d M Y') }}</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($ordVal, 0, ',', '.') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-[12px] text-slate-500">Belum ada pesanan B2C.</div>
                @endforelse
            </div>

            <div id="liveSearchEmptyState" class="hidden p-6 text-center text-[12px] text-slate-500">
                Tidak ada pesanan B2C cocok "<span id="liveSearchEmptyQuery"></span>".
            </div>
        </div>

        {{-- Kolom Kanan: Detail Lengkap & Panel Aksi Validasi B2C --}}
        <div class="lg:col-span-8 space-y-4">
            @if ($order)
                @php
                    $itemsSubtotal = (float) $order->items->sum('subtotal');
                    $inv = $order->invoice;
                    $shippingCost = $inv ? (float) $inv->shipping_cost : 0.0;
                    $ppnAmount = $inv ? (float) $inv->ppn_amount : round($itemsSubtotal * 0.11, 2);
                    $grandTotal = $inv ? (float) $inv->grand_total : ($itemsSubtotal + $shippingCost + $ppnAmount);

                    $statusLabelMap = [
                        'menunggu_verifikasi' => ['Menunggu Verifikasi RTPU', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
                        'menunggu_pembayaran' => ['Menunggu Pelunasan 100%', 'bg-sky-50 text-sky-700 ring-sky-600/20'],
                        'diproses' => ['Sedang Diproses Unit', 'bg-indigo-50 text-indigo-700 ring-indigo-600/20'],
                        'siap_diambil' => ['Siap Diambil / Digunakan', 'bg-teal-50 text-teal-700 ring-teal-600/20'],
                        'selesai' => ['Pesanan Selesai', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
                        'dibatalkan' => ['Ditolak / Dibatalkan', 'bg-rose-50 text-rose-700 ring-rose-600/20'],
                    ];
                    [$stLabel, $stClass] = $statusLabelMap[$order->status] ?? [$order->status, 'bg-slate-100 text-slate-700 ring-slate-600/20'];

                    $orderTypeLabel = match ($order->order_type) {
                        'ready_stock' => 'Ready Stock',
                        'pre_order' => 'Pre-Order / MTO',
                        'lab_reservation' => 'Reservasi Lab / Jasa',
                        default => $order->order_type,
                    };
                @endphp

                {{-- Kartu Header Pesanan + Status --}}
                <div class="bg-white rounded-lg border border-slate-200/80 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-slate-100">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-[14px] font-extrabold text-[#003366] tabular-nums">{{ $order->order_code }}</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-600/20 text-[10.5px] font-bold">B2C</span>
                                <span class="px-2 py-0.5 rounded text-[10.5px] font-bold ring-1 ring-inset {{ $stClass }}">{{ $stLabel }}</span>
                            </div>
                            <p class="text-[11.5px] text-slate-500 mt-1 tabular-nums">
                                Dibuat {{ optional($order->created_at)->translatedFormat('d F Y, H:i') }} WIB &bull; Tipe: <strong class="text-slate-700">{{ $orderTypeLabel }}</strong>
                                @if ($order->estimasi_hari)
                                    &bull; Estimasi: <strong class="text-slate-700">{{ $order->estimasi_hari }} hari kerja</strong>
                                @endif
                            </p>
                        </div>

                        @if ($inv)
                            <a href="{{ route('admin.rtpu.print.invoice', $order) }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-[12px] font-semibold transition-all active:scale-95 shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                <span>Cetak Invoice (.PDF)</span>
                            </a>
                        @endif
                    </div>

                    {{-- Identitas Pemesan & Status Pembayaran --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3.5 text-[12px]">
                        <div class="rounded-lg bg-slate-50/80 border border-slate-200/70 p-3.5 space-y-1.5">
                            <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Identitas Pemesan (Sivitas / Umum)</p>
                            <p class="text-[13.5px] font-extrabold text-slate-900">{{ $order->user?->name ?? '-' }}</p>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-1 pt-1 text-[11.5px]">
                                <span class="text-slate-500">NIM / NIP</span>
                                <span class="font-mono font-semibold text-slate-800 tabular-nums">{{ $order->user?->nim_nip ?? '-' }}</span>
                                <span class="text-slate-500">Prodi / Unit</span>
                                <span class="font-semibold text-slate-800">{{ $order->user?->prodi_unit ?? '-' }}</span>
                                <span class="text-slate-500">Email</span>
                                <span class="text-slate-700 truncate">{{ $order->user?->email ?? '-' }}</span>
                                <span class="text-slate-500">Telepon</span>
                                <span class="font-mono text-slate-700 tabular-nums">{{ $order->user?->phone ?? '-' }}</span>
                            </div>
                            @if ($order->shipping_address)
                                <div class="pt-1.5 border-t border-slate-200/70 text-[11.5px]">
                                    <span class="text-slate-500 block">Alamat / Lokasi Pengambilan:</span>
                                    <span class="text-slate-800 font-medium">{{ $order->shipping_address }}</span>
                                </div>
                            @endif
                            @if ($order->booking_date)
                                <div class="pt-1.5 border-t border-slate-200/70 text-[11.5px] tabular-nums">
                                    <span class="text-slate-500">Jadwal Reservasi Lab:</span>
                                    <strong class="text-[#003366]">{{ $order->booking_date->translatedFormat('d F Y') }} {{ $order->booking_slot ? '(' . $order->booking_slot . ')' : '' }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="rounded-lg bg-slate-50/80 border border-slate-200/70 p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Bukti &amp; Status Pembayaran</p>
                                @if ($inv && $inv->payment_status === 'paid')
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 text-[10px] font-bold">Lunas 100%</span>
                                @elseif ($inv && $inv->payment_status === 'expired')
                                    <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20 text-[10px] font-bold">Kedaluwarsa</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20 text-[10px] font-bold">Belum Lunas</span>
                                @endif
                            </div>

                            <div class="space-y-1.5 text-[11.5px] tabular-nums">
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Nomor Invoice</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $inv?->invoice_number ?? 'Akan diterbitkan saat disetujui' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Metode Bayar</span>
                                    <span class="font-semibold text-slate-800">
                                        {{ $inv ? ($inv->payment_method === 'qris' ? 'QRIS BLU PNJ' : 'VA Bank Mandiri BLU') : 'QRIS / VA Mandiri BLU' }}
                                    </span>
                                </div>
                                @if ($inv?->va_number)
                                    <div class="flex justify-between gap-2">
                                        <span class="text-slate-500">Nomor VA</span>
                                        <span class="font-mono font-semibold text-slate-800">{{ $inv->va_number }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Waktu Pelunasan</span>
                                    <span class="font-semibold text-slate-800">{{ $inv?->paid_at ? $inv->paid_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}</span>
                                </div>
                                @if ($inv?->qr_pass_code)
                                    <div class="flex justify-between gap-2 pt-1 border-t border-slate-200/70">
                                        <span class="text-slate-500">Kode QR Pass Loket</span>
                                        <span class="font-mono font-bold text-emerald-700">{{ $inv->qr_pass_code }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Rincian Item B2C --}}
                <div class="bg-white rounded-lg border border-slate-200/80 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-[13.5px] font-extrabold text-slate-900">Rincian Item Pesanan (Tarif B2C)</h3>
                        <span class="text-[11px] font-semibold text-slate-500 tabular-nums">{{ $order->items->count() }} Baris Item</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-[12px] border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-2.5 text-left">Produk / Jasa / Lab</th>
                                    <th class="px-3 py-2.5 text-left">Unit Pengelola</th>
                                    <th class="px-3 py-2.5 text-right">Qty</th>
                                    <th class="px-3 py-2.5 text-right">Harga B2C (Rp)</th>
                                    <th class="px-4 py-2.5 text-right">Subtotal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 tabular-nums">
                                @foreach ($order->items as $item)
                                    @php
                                        $unitPrice = (float) ($item->unit_price > 0 ? $item->unit_price : ($item->product?->price_b2c ?? 0));
                                    @endphp
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-2.5 text-left">
                                            <p class="font-bold text-slate-900">{{ $item->product?->name ?? '-' }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">{{ $item->product?->code ?? '-' }} &bull; {{ $item->product?->category?->name ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-2.5 text-left text-slate-700">{{ $item->product?->unit?->name ?? '-' }}</td>
                                        <td class="px-3 py-2.5 text-right font-semibold text-slate-900">{{ $item->quantity }}</td>
                                        <td class="px-3 py-2.5 text-right text-slate-700">{{ number_format($unitPrice, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2.5 text-right font-bold text-slate-900">{{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-slate-50/70 border-t border-slate-200/80 text-[12px] tabular-nums">
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-right font-medium text-slate-600">Subtotal Item</td>
                                    <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($shippingCost > 0)
                                    <tr>
                                        <td colspan="4" class="px-4 py-1.5 text-right font-medium text-slate-600">Biaya Pengiriman</td>
                                        <td class="px-4 py-1.5 text-right font-semibold text-slate-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="px-4 py-1.5 text-right font-medium text-slate-600">PPN 11%</td>
                                    <td class="px-4 py-1.5 text-right font-semibold text-slate-800">Rp {{ number_format($ppnAmount, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-t border-slate-200">
                                    <td colspan="4" class="px-4 py-2.5 text-right font-extrabold text-slate-900">Total Tagihan (100% Lunas)</td>
                                    <td class="px-4 py-2.5 text-right text-[14px] font-extrabold text-[#003366]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Catatan Pesanan & Panel Aksi Validasi B2C --}}
                @php
                    $b2cActionUrl = route('admin.rtpu.validasi-b2c.action', $order);
                    $b2cPayloads = [
                        'reject' => [
                            'url' => $b2cActionUrl,
                            'action' => 'reject',
                            'title' => 'Tolak Pesanan B2C?',
                            'message' => 'Pesanan ' . $order->order_code . ' akan dibatalkan dan tagihan aktif ditandai kedaluwarsa. Wajib cantumkan alasan penolakan.',
                            'confirmText' => 'Ya, Tolak Pesanan',
                            'tone' => 'rose',
                        ],
                        'approve' => [
                            'url' => $b2cActionUrl,
                            'action' => 'approve',
                            'title' => 'Verifikasi & Setujui Pesanan B2C?',
                            'message' => 'Pesanan ' . $order->order_code . ' akan disetujui dan tagihan pelunasan 100% BLU diterbitkan secara otomatis jika belum tersedia.',
                            'confirmText' => 'Verifikasi & Setujui',
                            'tone' => 'navy',
                        ],
                        'mark_paid_process' => [
                            'url' => $b2cActionUrl,
                            'action' => 'mark_paid_process',
                            'title' => 'Konfirmasi Lunas & Tandai Diproses?',
                            'message' => 'Status pembayaran pesanan ' . $order->order_code . ' akan ditandai Lunas 100% dan diteruskan ke unit pengelola untuk diproses.',
                            'confirmText' => 'Tandai Diproses',
                            'tone' => 'emerald',
                        ],
                        'mark_ready' => [
                            'url' => $b2cActionUrl,
                            'action' => 'mark_ready',
                            'title' => 'Tandai Pesanan Siap Diambil?',
                            'message' => 'Pesanan ' . $order->order_code . ' akan ditandai Siap Diambil / Digunakan pada loket unit pengelola.',
                            'confirmText' => 'Tandai Siap Diambil',
                            'tone' => 'navy',
                        ],
                        'mark_done' => [
                            'url' => $b2cActionUrl,
                            'action' => 'mark_done',
                            'title' => 'Tandai Pesanan B2C Selesai?',
                            'message' => 'Pastikan produk/layanan pada pesanan ' . $order->order_code . ' telah diserahterimakan kepada pemesan.',
                            'confirmText' => 'Ya, Tandai Selesai',
                            'tone' => 'emerald',
                        ],
                    ];
                @endphp
                <div class="bg-white rounded-lg border border-slate-200/80 p-4 space-y-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Catatan Pemesan &amp; Riwayat Verifikasi</p>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/70 text-[12px] text-slate-700 whitespace-pre-line leading-relaxed">{{ $order->notes ?: 'Tidak ada catatan tambahan.' }}</div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2.5">
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($order->status !== 'dibatalkan' && $order->status !== 'selesai')
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2cPayloads["reject"]))'
                                        class="px-3.5 py-2 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[12px] font-semibold transition-all active:scale-95">
                                    Tolak Pesanan
                                </button>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($order->status === 'menunggu_verifikasi')
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2cPayloads["approve"]))'
                                        class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Verifikasi &amp; Setujui Pesanan
                                </button>
                            @endif

                            @if (in_array($order->status, ['menunggu_verifikasi', 'menunggu_pembayaran'], true))
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2cPayloads["mark_paid_process"]))'
                                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Konfirmasi Lunas &amp; Diproses
                                </button>
                            @endif

                            @if ($order->status === 'diproses')
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2cPayloads["mark_ready"]))'
                                        class="px-4 py-2 rounded-lg bg-[#003366] hover:bg-[#1E3A8A] text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Tandai Siap Diambil
                                </button>
                            @endif

                            @if (in_array($order->status, ['diproses', 'siap_diambil'], true))
                                <button type="button"
                                        onclick='openOrderActionModal(@json($b2cPayloads["mark_done"]))'
                                        class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-semibold transition-all active:scale-95">
                                    Tandai Selesai
                                </button>
                            @endif

                            @if (in_array($order->status, ['selesai', 'dibatalkan'], true))
                                <span class="text-[12px] font-semibold text-slate-500">
                                    Status pesanan sudah final ({{ $stLabel }}).
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg border border-slate-200/80 p-12 text-center">
                    <p class="text-[14px] font-bold text-slate-800">Belum ada pesanan B2C untuk divalidasi</p>
                    <p class="text-[12px] text-slate-500 mt-1">Pilih pesanan dari daftar antrean di sebelah kiri atau kembali ke Daftar Antrean Pesanan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
