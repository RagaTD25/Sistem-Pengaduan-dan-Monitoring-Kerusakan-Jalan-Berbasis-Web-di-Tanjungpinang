@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="text-center space-y-2">
        <span class="inline-block bg-white text-c-dark font-bold text-xs px-3.5 py-1 rounded-full border-2 border-c-medium shadow-sm">
            Warga
        </span>
        <h1 class="text-3xl font-black text-c-dark">Lacak Status Laporan</h1>
        <p class="text-sm text-c-primary font-medium">Masukkan nomor tiket laporan Anda untuk memeriksa progres penanganan secara langsung.</p>
    </div>

    <!-- Form Cari Tiket -->
    <div class="bg-white p-6 rounded-2xl border-2 border-c-medium shadow-sm">
        <form action="{{ route('warga.lacak') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="tiket" value="{{ $tiketSearch }}" placeholder="Contoh: TPI-2026-0142" required class="flex-1 px-4 py-3 rounded-xl border-2 border-c-medium text-sm font-bold text-c-dark uppercase focus:outline-none focus:border-c-primary">
            <button type="submit" class="bg-c-dark hover:bg-black text-white font-black px-6 py-3 rounded-xl shadow transition">
                Cek Status Tiket
            </button>
        </form>
    </div>

    @if($tiketSearch)
        @if($laporan)
            <div class="bg-white p-6 rounded-2xl border-2 border-c-medium shadow-md space-y-6">
                <!-- Header Tiket & Status Badge -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b-2 border-c-bg pb-4 gap-2">
                    <div>
                        <span class="text-xs font-bold text-c-medium block uppercase">Nomor Tiket</span>
                        <h2 class="text-2xl font-black text-c-dark">{{ $laporan->nomor_tiket }}</h2>
                    </div>
                    <div>
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $laporan->status_badge_class }}">
                            {{ $laporan->status }}
                        </span>
                    </div>
                </div>

                <!-- 1. TANGGAPAN RESMI FORMAL DINAS PUPR TANJUNGPINANG (WARGA ONLY) -->
                @if($laporan->status === 'Ditolak')
                    <div class="bg-red-50 border-2 border-red-400 p-5 rounded-xl space-y-1">
                        <p class="text-xs font-extrabold text-red-700 uppercase tracking-wider">❌ TANGGAPAN RESMI DINAS PUPR TANJUNGPINANG:</p>
                        <p class="text-sm font-bold text-red-900 leading-relaxed">{{ $laporan->alasan_penolakan ?? $laporan->tanggapan_admin }}</p>
                    </div>
                @elseif($laporan->status === 'Selesai')
                    <div class="bg-green-100 border-2 border-green-500 p-5 rounded-xl space-y-2">
                        <h3 class="text-base font-extrabold text-green-900">🎉 Terima Kasih Atas Partisipasi Anda!</h3>
                        <p class="text-xs text-green-800 font-medium leading-relaxed">
                            Laporan kerusakan jalan yang Anda ajukan telah selesai ditangani secara tuntas oleh Dinas PUPR Kota Tanjungpinang.
                        </p>
                    </div>
                @else
                    <div class="bg-blue-50 border-2 border-blue-300 p-5 rounded-xl space-y-2">
                        <p class="text-xs font-extrabold text-c-primary uppercase tracking-wider">📢 TANGGAPAN RESMI DINAS PUPR TANJUNGPINANG:</p>
                        <p class="text-sm font-bold text-c-dark leading-relaxed">
                            {{ $laporan->tanggapan_admin ?? 'Laporan Anda telah masuk ke dalam sistem dan sedang dalam antrean penanganan Dinas PUPR Kota Tanjungpinang.' }}
                        </p>
                        
                        <!-- Estimasi Selesai Formal -->
                        @if($laporan->estimasi_selesai)
                            <div class="pt-2 border-t border-blue-200 text-xs font-black text-blue-900">
                                ⏱️ Estimasi Waktu Penanganan: <span class="underline">{{ $laporan->estimasi_selesai }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- 2. Rincian Laporan Awal Warga -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-c-dark pt-2">
                    <div>
                        <p class="text-xs font-bold text-c-medium uppercase">Judul Laporan</p>
                        <p class="font-bold">{{ $laporan->judul }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-c-medium uppercase">Kategori</p>
                        <p class="font-bold">{{ $laporan->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-c-medium uppercase">Tanggal Diajukan</p>
                        <p class="font-medium">{{ $laporan->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-c-medium uppercase">Koordinat Lokasi</p>
                        <p class="font-mono font-bold">{{ $laporan->latitude }}, {{ $laporan->longitude }}</p>
                    </div>
                </div>

                <!-- 3. BUKTI FOTO PERBAIKAN SELESAI (HANYA MUNCUL JIKA SELESAI) -->
                @if($laporan->status === 'Selesai' && $laporan->foto_perbaikan)
                    <div class="bg-gray-50 p-4 rounded-xl border-2 border-c-medium space-y-2">
                        <p class="text-xs font-extrabold text-c-dark uppercase">📸 Foto Dokumentasi Hasil Perbaikan Fisik:</p>
                        <img src="{{ asset('storage/' . $laporan->foto_perbaikan) }}" class="max-h-64 rounded-lg border-2 border-c-medium shadow-sm object-cover w-full">
                    </div>
                @endif
            </div>
        @else
            <div class="bg-red-50 border-2 border-red-300 p-6 rounded-2xl text-center text-red-700 font-bold text-sm">
                ❌ Nomor tiket "<span class="underline">{{ $tiketSearch }}</span>" tidak ditemukan dalam sistem.
            </div>
        @endif
    @endif
</div>
@endsection