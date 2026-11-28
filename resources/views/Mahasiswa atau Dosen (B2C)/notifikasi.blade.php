@extends('Mahasiswa atau Dosen (B2C).layouts.mahasiswa')

@section('title', 'Pusat Notifikasi')
@section('nav', 'notifikasi')

@section('content')
<div class="space-y-4">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <nav class="flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                <a href="{{ url('/dashboard-mahasiswa') }}" class="hover:text-[#00877A]">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Pusat Notifikasi</span>
            </nav>
            <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">Pusat Notifikasi</h1>
            <p class="text-xs text-slate-500">Pantau update status pesanan riset, verifikasi jadwal lab, dan informasi akademik.</p>
        </div>

        <form action="{{ route('b2c.notifikasi.read-all') }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-[#00877A]/30 bg-white px-3.5 py-2 text-xs font-bold text-[#00877A] shadow-xs transition-all hover:bg-[#00877A]/5 active:scale-95">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 7 17l-5-5" />
                    <path d="m22 10-7.5 7.5L13 16" />
                </svg>
                <span>Tandai semua sudah dibaca</span>
            </button>
        </form>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-5 border-b border-slate-200/80 text-xs font-bold">
        @foreach ([
            'semua' => ['label' => 'Semua', 'count' => $counts['semua']],
            'transaksi' => ['label' => 'Transaksi', 'count' => $counts['transaksi']],
            'sistem' => ['label' => 'Sistem', 'count' => $counts['sistem']],
        ] as $key => $meta)
            <a href="{{ route('b2c.notifikasi', $key === 'semua' ? [] : ['tab' => $key]) }}"
               class="inline-flex items-center gap-1.5 border-b-2 pb-2.5 transition-colors {{ $tab === $key ? 'border-[#00877A] text-[#00877A]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <span>{{ $meta['label'] }}</span>
                <span class="rounded-full px-1.5 py-0.5 tabular-nums text-[10px] {{ $tab === $key ? 'bg-[#00877A]/10 text-[#00877A]' : 'bg-slate-100 text-slate-500' }}">
                    {{ $meta['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Daftar Notifikasi --}}
    @if ($notifications->isEmpty())
        <div class="rounded-lg border border-slate-200/80 bg-white px-6 py-12 text-center shadow-xs">
            <p class="text-sm font-bold text-slate-800">Tidak Ada Notifikasi</p>
            <p class="mt-1 text-xs text-slate-500">Semua pembaruan transaksi dan sistem akan muncul di sini.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($notifications as $notif)
                @php
                    $isUnread = ! $notif->is_read;
                    $jenisBadge = match ($notif->jenis_notif) {
                        'pesanan' => ['label' => 'Transaksi', 'cls' => 'bg-sky-50 text-sky-700'],
                        'bayar' => ['label' => 'Pembayaran BLU', 'cls' => 'bg-emerald-50 text-emerald-700'],
                        default => ['label' => 'Sistem & Regulasi', 'cls' => 'bg-slate-100 text-slate-600'],
                    };
                @endphp
                <article class="flex flex-col justify-between gap-3 rounded-lg border p-4 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg sm:flex-row sm:items-start {{ $isUnread ? 'border-[#00877A]/30 bg-[#00877A]/[0.04]' : 'border-slate-200/80 bg-white' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center gap-2 pt-0.5">
                            @if ($isUnread)
                                <span class="h-2 w-2 shrink-0 rounded-full bg-[#00877A]"></span>
                            @endif
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $isUnread ? 'bg-white text-[#00877A] shadow-xs' : 'bg-slate-100 text-slate-500' }}">
                                @if ($notif->jenis_notif === 'bayar')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2" />
                                        <circle cx="12" cy="12" r="2.5" />
                                    </svg>
                                @elseif ($notif->jenis_notif === 'pesanan')
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                @else
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                @endif
                            </span>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">{{ $notif->judul }}</h3>
                                <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $jenisBadge['cls'] }}">
                                    {{ $jenisBadge['label'] }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $notif->pesan }}</p>

                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                @if ($notif->url_tujuan)
                                    <a href="{{ $notif->url_tujuan }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#00877A] hover:underline">
                                        <span>Lihat Detail</span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14M13 6l6 6-6 6" />
                                        </svg>
                                    </a>
                                @endif
                                @if ($isUnread)
                                    <form action="{{ route('b2c.notifikasi.read', $notif->id_notif) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-semibold text-slate-500 hover:text-slate-800">
                                            Tandai sudah dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <span class="shrink-0 tabular-nums text-[11px] font-medium text-slate-400">
                        {{ $notif->created_at?->translatedFormat('d M Y, H:i') }} WIB
                    </span>
                </article>
            @endforeach
        </div>

        <div>{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
