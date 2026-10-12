@extends('layouts.app')

@section('content')
<!-- Header & Tombol Buat Laporan -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <span class="inline-block bg-white text-c-dark font-bold text-xs px-3.5 py-1 rounded-full border-2 border-c-medium shadow-sm mb-2">
            Warga
        </span>
        <h1 class="text-3xl font-extrabold text-c-dark tracking-tight">Laporan Saya</h1>
        <p class="text-sm text-c-primary font-medium mt-1">Pantau seluruh laporan kerusakan jalan yang pernah Anda ajukan.</p>
    </div>
    
    <div>
        <a href="{{ route('warga.buat-laporan') }}" class="inline-block bg-c-dark hover:bg-black text-white font-black py-3.5 px-6 rounded-xl shadow-lg transition">
            + Buat Laporan Baru
        </a>
    </div>
</div>

<!-- 3 Kartu Statistik Realtime -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm">
        <p class="text-4xl font-black text-c-dark mb-1">{{ $totalDiajukan }}</p>
        <p class="text-xs text-c-primary font-bold">Total laporan diajukan</p>
    </div>
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm">
        <p class="text-4xl font-black text-c-dark mb-1">{{ $sedangDitangani }}</p>
        <p class="text-xs text-c-primary font-bold">Sedang ditangani petugas</p>
    </div>
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm">
        <p class="text-4xl font-black text-c-dark mb-1">{{ $selesai }}</p>
        <p class="text-xs text-c-primary font-bold">Selesai diperbaiki</p>
    </div>
</div>

<!-- Tabel Laporan Warga -->
<div class="bg-white border-2 border-c-medium rounded-2xl shadow-sm overflow-hidden mb-12">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-c-medium/80 text-c-dark text-xs uppercase font-extrabold border-b-2 border-c-medium">
                    <th class="py-4 px-6">No. Tiket</th>
                    <th class="py-4 px-6">Kategori</th>
                    <th class="py-4 px-6">Lokasi</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6">Diajukan</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-c-bg text-sm text-c-dark font-medium">
                @forelse($laporans as $laporan)
                    <tr class="hover:bg-c-bg/20 transition">
                        <td class="py-4 px-6 font-black font-mono text-c-dark">{{ $laporan->nomor_tiket }}</td>
                        <td class="py-4 px-6 font-semibold">{{ $laporan->kategori }}</td>
                        <td class="py-4 px-6 font-semibold">{{ $laporan->judul }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black border {{ $laporan->status_badge_class }}">
                                {{ $laporan->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-xs font-bold text-c-primary">
                            {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('warga.lacak', ['tiket' => $laporan->nomor_tiket]) }}" class="inline-block px-4 py-1.5 bg-white text-c-dark font-bold text-xs rounded-lg border-2 border-c-dark hover:bg-c-dark hover:text-white shadow-sm transition">
                                Lacak
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-c-medium font-bold">Belum ada laporan yang diajukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- GALERI ETALASE HASIL PERBAIKAN PUPR (BEFORE & AFTER) -->
<div class="space-y-4">
    <div>
        <h2 class="text-2xl font-black text-c-dark">🏆 Galeri Perbaikan Tuntas Dinas PUPR</h2>
        <p class="text-xs text-c-primary font-medium">Bukti nyata pengerjaan perbaikan jalan dari laporan masyarakat Kota Tanjungpinang.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
            $laporanSelesai = $laporans->where('status', 'Selesai');
        @endphp

        @forelse($laporanSelesai as $item)
            <div class="bg-white border-2 border-c-medium rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="font-mono font-bold text-xs text-c-dark">{{ $item->nomor_tiket }} - {{ $item->judul }}</span>
                    <span class="bg-green-600 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full">Selesai Tuntas</span>
                </div>

                <!-- Grid Komparasi Before vs After -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <span class="block text-[10px] font-black text-red-600 uppercase">Foto Kerusakan (Awal)</span>
                        @if($item->foto_kerusakan)
                            <img src="{{ asset('storage/' . $item->foto_kerusakan) }}" class="h-36 w-full object-cover rounded-xl border border-red-300">
                        @else
                            <div class="h-36 bg-gray-100 rounded-xl border border-gray-300 flex items-center justify-center text-xs font-bold text-gray-400">Tidak ada foto</div>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <span class="block text-[10px] font-black text-green-600 uppercase">Hasil Perbaikan (Selesai)</span>
                        @if($item->foto_perbaikan)
                            <img src="{{ asset('storage/' . $item->foto_perbaikan) }}" class="h-36 w-full object-cover rounded-xl border border-green-400">
                        @else
                            <div class="h-36 bg-gray-100 rounded-xl border border-gray-300 flex items-center justify-center text-xs font-bold text-gray-400">Tidak ada foto</div>
                        @endif
                    </div>
                </div>

                <div class="bg-green-50 p-3 rounded-xl border border-green-200 text-xs">
                    <p class="font-bold text-green-900">Catatan Perbaikan:</p>
                    <p class="text-green-800 font-medium mt-0.5">{{ $item->catatan_petugas ?? 'Pengerjaan pengaspalan dan pembenahan drainase selesai.' }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white border-2 border-c-medium p-6 rounded-2xl text-center text-xs font-bold text-c-medium">
                Belum ada dokumentasi perbaikan selesai. Laporan yang dituntaskan petugas akan dipublikasikan di sini.
            </div>
        @endforelse
    </div>
</div>
@endsection