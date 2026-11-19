@extends('layouts.mahasiswa')

@section('title', 'Katalog Utama')
@section('nav', 'katalog')

@section('content')
@php
    $produk = [
        ['kategori' => 'IoT Hardware', 'judul' => 'Microcontroller IoT Starter Kit PNJ', 'harga' => 'Rp 350.000', 'bg' => 'from-slate-100 via-emerald-50 to-emerald-200', 'ikon' => 'chip'],
        ['kategori' => 'IoT Hardware', 'judul' => 'Sensor Lingkungan Presisi Tinggi', 'harga' => 'Rp 480.000', 'bg' => 'from-slate-50 via-slate-100 to-slate-300', 'ikon' => 'sensor'],
        ['kategori' => 'Bioteknologi', 'judul' => 'Modul Spektrofotometri UV-Vis', 'harga' => 'Rp 1.250.000', 'bg' => 'from-slate-100 via-teal-100 to-teal-300', 'ikon' => 'flask'],
        ['kategori' => 'Publikasi Riset', 'judul' => 'Biomaterial Sample Kit Uji Lab', 'harga' => 'Rp 210.000', 'bg' => 'from-sky-50 via-teal-50 to-amber-200', 'ikon' => 'kit'],
    ];
@endphp

<div class="space-y-8">

    {{-- Statistik --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <p class="text-xs font-medium text-slate-500">Menunggu Pembayaran</p>
                <p class="mt-1 text-4xl font-extrabold text-slate-900">2</p>
                <p class="mt-1 text-xs text-slate-500">Segera konfirmasi invoice</p>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 2 2 2-2 2 2 3-2V8z" />
                    <path d="M14 2v6h6" />
                    <path d="M8 13h6M8 17h4" />
                </svg>
            </span>
        </div>

        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <p class="text-xs font-medium text-slate-500">Diproses RTPU</p>
                <p class="mt-1 text-4xl font-extrabold text-slate-900">5</p>
                <p class="mt-1 text-xs text-slate-500">Dalam preparasi lab riset</p>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 3h6" />
                    <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                    <path d="M7.5 14h9" />
                </svg>
            </span>
        </div>

        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <p class="text-xs font-medium text-slate-500">Selesai</p>
                <p class="mt-1 text-4xl font-extrabold text-slate-900">14</p>
                <p class="mt-1 text-xs text-slate-500">Instrumen telah diterima</p>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m8 12 3 3 5-6" />
                </svg>
            </span>
        </div>
    </div>

    {{-- Hero Banner --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal-600 to-teal-500 p-8 text-white shadow-lg md:px-12 md:py-12">
        <div class="pointer-events-none absolute -right-20 -bottom-28 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute top-0 right-1/3 h-48 w-48 rounded-full bg-white/5 blur-2xl"></div>

        <div class="relative">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-3 py-1 text-xs font-medium">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m8 12 3 3 5-6" />
                </svg>
                Riset Terapan PNJ
            </span>
            <h2 class="mt-5 mb-2 text-3xl font-bold tracking-tight">Eksplorasi Produk Riset Unggulan</h2>
            <p class="mb-6 text-white/80">Inovasi teknologi dan publikasi riset terapan kampus langsung untuk mahasiswa.</p>
            <a href="#katalog"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-2.5 font-semibold text-teal-700 shadow-sm transition hover:bg-teal-50">
                Jelajahi Sekarang
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </section>

    {{-- Katalog --}}
    <section id="katalog">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Katalog Produk Riset</h3>
                <p class="text-sm text-slate-500">Menampilkan 4 instrumen pilihan</p>
            </div>
            <div class="relative">
                <svg class="pointer-events-none absolute top-1/2 left-3 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6h16M7 12h10M10 18h4" />
                </svg>
                <select class="h-9 appearance-none rounded-lg border border-slate-200 bg-white pr-8 pl-9 text-xs font-medium text-slate-600 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    <option>Semua Departemen Riset</option>
                    <option>Teknik Elektro</option>
                    <option>Teknik Mesin</option>
                    <option>Teknik Informatika &amp; Komputer</option>
                    <option>Teknik Grafika &amp; Penerbitan</option>
                </select>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($produk as $item)
                <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-xl bg-gradient-to-br {{ $item['bg'] }}">
                        <div class="flex h-full w-full items-center justify-center text-teal-700/60">
                            @if ($item['ikon'] === 'chip')
                                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="6" y="6" width="12" height="12" rx="1.5" />
                                    <rect x="9.5" y="9.5" width="5" height="5" />
                                    <path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4" />
                                </svg>
                            @elseif ($item['ikon'] === 'sensor')
                                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="9" rx="2" />
                                    <path d="M7 9h6M8 14v7M16 14v7" />
                                </svg>
                            @elseif ($item['ikon'] === 'flask')
                                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 3h6" />
                                    <path d="M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2.2h12.4a1.5 1.5 0 0 0 1.3-2.2L14 9V3" />
                                    <path d="M7.5 14h9" />
                                </svg>
                            @else
                                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M12 3v6M12 15v6M3 12h6M15 12h6" />
                                </svg>
                            @endif
                        </div>
                        <span class="absolute right-2 bottom-2 rounded-md bg-slate-700/85 px-2 py-0.5 text-[10px] font-medium text-white">Optimized &lt; 1MB</span>
                    </div>

                    <div class="mt-4 flex flex-1 flex-col">
                        <span class="w-fit rounded-full bg-teal-50 px-2.5 py-0.5 text-[10px] font-semibold text-teal-600">{{ $item['kategori'] }}</span>
                        <h4 class="mt-2 min-h-[3.5rem] text-lg leading-snug font-bold text-slate-900">{{ $item['judul'] }}</h4>

                        <div class="mt-3 border-t border-slate-100 pt-3">
                            <p class="text-base font-bold text-slate-900">{{ $item['harga'] }}</p>
                        </div>

                        <a href="#"
                           class="mt-3 block w-full rounded-lg bg-teal-600 py-2 text-center font-medium text-white transition hover:bg-teal-700">
                            Lihat Detail
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Footer --}}
    <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-6 text-xs text-slate-500">
        <p>&copy; 2025 Politeknik Negeri Jakarta &bull; Unit Layanan Riset Terapan &amp; Pengabdian Mahasiswa</p>
        <div class="flex items-center gap-6">
            <span class="inline-flex items-center gap-1.5 text-teal-600">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="11" width="16" height="10" rx="2" />
                    <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                </svg>
                Verifikasi Mahasiswa Aktif
            </span>
            <span class="inline-flex items-center gap-1.5 text-teal-600">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 3h13v13H1zM14 8h4l4 4v4h-8" />
                    <circle cx="6" cy="18" r="2" />
                    <circle cx="18" cy="18" r="2" />
                </svg>
                Pengambilan di Lab RTPU
            </span>
        </div>
    </footer>

</div>
@endsection
