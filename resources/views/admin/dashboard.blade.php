@extends('layouts.app')

@section('content')
<div class="mb-6">
    <span class="inline-block bg-white text-c-dark font-bold text-xs px-3.5 py-1 rounded-full border-2 border-c-medium shadow-sm mb-2">
        Admin PUPR
    </span>
    <h1 class="text-3xl font-extrabold text-c-dark tracking-tight">Daftar Laporan & Disposisi Wilayah</h1>
    <p class="text-sm text-c-primary font-medium mt-1">Kelola disposisi internal petugas dan publikasikan tanggapan resmi dinas kepada warga.</p>
</div>

<!-- Alert Notifikasi -->
@if(session('success'))
    <div class="bg-green-100 border-2 border-green-500 text-green-800 p-4 rounded-xl font-bold mb-6 text-sm">
        ✅ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-100 border-2 border-red-500 text-red-800 p-4 rounded-xl font-bold mb-6 text-sm">
        ⚠️ {{ session('error') }}
    </div>
@endif

<!-- 3 Kartu Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm">
        <p class="text-4xl font-black text-c-dark mb-1">{{ $menungguVerifikasi }}</p>
        <p class="text-xs text-c-primary font-bold">Menunggu Disposisi</p>
    </div>
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm">
        <p class="text-4xl font-black text-c-dark mb-1">{{ $dalamPenanganan }}</p>
        <p class="text-xs text-c-primary font-bold">Dalam Penanganan Lapangan</p>
    </div>
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm">
        <p class="text-4xl font-black text-c-dark mb-1">{{ $selesaiBulanIni }}</p>
        <p class="text-xs text-c-primary font-bold">Selesai Bulan Ini</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- Tabel Utama Admin -->
    <div class="lg:col-span-7 bg-white border-2 border-c-medium rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-c-dark text-xs uppercase font-extrabold border-b-2 border-c-medium">
                        <th class="py-3.5 px-4">Laporan</th>
                        <th class="py-3.5 px-4">Catatan Internal Petugas</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-c-bg text-sm text-c-dark font-medium">
                    @forelse($laporans as $laporan)
                        <tr class="hover:bg-c-bg/20 transition">
                            <td class="py-4 px-4">
                                <p class="font-black font-mono text-c-dark text-sm">{{ $laporan->nomor_tiket }}</p>
                                <p class="text-xs text-c-primary font-semibold">{{ $laporan->judul }}</p>
                            </td>
                            
                            <!-- Catatan Internal Petugas (HANYA DILIHAT ADMIN) -->
                            <td class="py-4 px-4 text-xs font-bold">
                                @if($laporan->catatan_petugas)
                                    <span class="text-c-dark block bg-yellow-50 p-2 rounded-lg border border-yellow-200">🔒 {{ $laporan->catatan_petugas }}</span>
                                    @if($laporan->estimasi_selesai)
                                        <span class="text-[10px] text-blue-600 font-bold block mt-1">⏱️ Estimasi: {{ $laporan->estimasi_selesai }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Belum ada catatan petugas</span>
                                @endif
                            </td>

                            <td class="py-4 px-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-black border {{ $laporan->status_badge_class }}">
                                    {{ $laporan->status }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if($laporan->status === 'Diajukan')
                                    <button type="button" onclick="openModalVerifikasi({{ $laporan->id }})" class="bg-c-dark hover:bg-black text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition">
                                        Verifikasi & Disposisi
                                    </button>
                                @elseif($laporan->status === 'Rekomendasi Tolak' || $laporan->status === 'Pengecekan')
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button" onclick="openModalTolak({{ $laporan->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition">
                                            Konfirmasi Tolak
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('warga.lacak', ['tiket' => $laporan->nomor_tiket]) }}" class="inline-block px-3 py-1.5 bg-white text-c-dark font-bold text-xs rounded-lg border-2 border-c-dark hover:bg-c-dark hover:text-white shadow-sm transition">
                                        Lihat Tampilan Warga
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-c-medium font-bold">Belum ada laporan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Peta Sebaran Laporan -->
    <div class="lg:col-span-5 bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="text-lg font-extrabold text-c-dark">Sebaran laporan di peta</h2>
        <div id="adminMap" class="h-72 rounded-xl border-2 border-c-medium z-10"></div>
    </div>
</div>

<!-- Modal Verifikasi & Disposisi (Pemisahan Internal vs Publik) -->
<dialog id="modalVerifikasi" class="rounded-2xl border-2 border-c-medium p-6 bg-white shadow-2xl max-w-xl w-full backdrop:bg-black/50">
    <form id="formVerifikasi" method="POST" class="space-y-4">
        @csrf
        <div class="flex justify-between items-start border-b-2 border-c-bg pb-3">
            <div>
                <h3 class="text-lg font-black text-c-dark">Verifikasi & Disposisi Laporan</h3>
                <p id="vTiket" class="text-xs font-mono font-bold text-c-primary"></p>
            </div>
            <button type="button" onclick="document.getElementById('modalVerifikasi').close()" class="text-gray-400 hover:text-black font-bold text-lg">✕</button>
        </div>

        <!-- 1. Instruksi Internal (Untuk Petugas) -->
        <div class="bg-blue-50 border-2 border-blue-200 p-4 rounded-xl space-y-1">
            <label class="block text-xs font-black text-c-primary uppercase">🛠️ 1. Instruksi Internal (Hanya Untuk Petugas Lapangan)</label>
            <textarea name="instruksi_admin" required placeholder="Contoh: Lakukan peninjauan fisik lokasi dan cek kedalaman lubang jalan." class="w-full p-2.5 bg-white border-2 border-blue-300 rounded-lg text-xs font-medium focus:outline-none" rows="2"></textarea>
        </div>

        <!-- 2. Pesan Tanggapan Formal (Untuk Warga) -->
        <div class="bg-green-50 border-2 border-green-200 p-4 rounded-xl space-y-1">
            <label class="block text-xs font-black text-green-800 uppercase">📢 2. Tanggapan Resmi Dinas PUPR (Ditampilkan ke Warga)</label>
            <textarea name="tanggapan_admin" required class="w-full p-2.5 bg-white border-2 border-green-300 rounded-lg text-xs font-medium focus:outline-none" rows="3">Laporan Anda telah diverifikasi oleh Dinas PUPR Kota Tanjungpinang. Tim teknis lapangan telah ditugaskan ke lokasi untuk melakukan penanganan.</textarea>
        </div>

        <div class="flex justify-end space-x-2 pt-2">
            <button type="button" onclick="document.getElementById('modalVerifikasi').close()" class="px-4 py-2 bg-gray-200 text-c-dark text-xs font-bold rounded-xl">Batal</button>
            <button type="submit" class="px-5 py-2 bg-c-dark text-white text-xs font-black rounded-xl">Proses & Publikasikan</button>
        </div>
    </form>
</dialog>

<!-- Modal Konfirmasi Tolak -->
<dialog id="modalTolak" class="rounded-2xl border-2 border-red-500 p-6 bg-white shadow-2xl max-w-lg w-full backdrop:bg-black/50">
    <form id="formTolak" method="POST" class="space-y-4">
        @csrf
        <div class="flex justify-between items-start border-b-2 border-c-bg pb-3">
            <div>
                <h3 class="text-lg font-black text-red-600">Pemberitahuan Penolakan Resmi</h3>
                <p id="tTiket" class="text-xs font-mono font-bold text-c-primary"></p>
            </div>
            <button type="button" onclick="document.getElementById('modalTolak').close()" class="text-gray-400 hover:text-black font-bold text-lg">✕</button>
        </div>

        <!-- Referensi Internal Petugas -->
        <div id="boxSurveiInternal" class="bg-gray-100 border border-gray-300 p-3 rounded-xl text-xs space-y-1">
            <span class="font-extrabold text-gray-700 block">🔒 Catatan Internal Petugas (Acuan Admin):</span>
            <p id="txtSurveiInternal" class="text-gray-800 italic font-medium"></p>
        </div>

        <div>
            <label class="block text-xs font-bold text-c-dark uppercase mb-1">Surat Penolakan Formal (Ditampilkan ke Warga)</label>
            <textarea name="alasan_penolakan" id="tAlasan" required placeholder="Contoh: Berdasarkan hasil verifikasi dan survei lapangan oleh tim Dinas PUPR Kota Tanjungpinang, lokasi yang dilaporkan berada di luar wewenang jalan kota / kondisi jalan dalam keadaan baik." class="w-full p-3 border-2 border-red-300 rounded-xl text-xs font-medium focus:outline-none focus:border-red-600" rows="3"></textarea>
        </div>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="document.getElementById('modalTolak').close()" class="px-4 py-2 bg-gray-200 text-c-dark text-xs font-bold rounded-xl">Batal</button>
            <button type="submit" class="px-5 py-2 bg-red-600 text-white text-xs font-black rounded-xl">Kirim Surat Penolakan</button>
        </div>
    </form>
</dialog>

<script>
    var laporans = @json($laporans);

    function openModalVerifikasi(id) {
        var item = laporans.find(l => l.id == id);
        if (!item) return;
        document.getElementById('formVerifikasi').action = "/admin/verifikasi/" + item.id;
        document.getElementById('vTiket').innerText = "Nomor Tiket: " + item.nomor_tiket;
        document.getElementById('modalVerifikasi').showModal();
    }

    function openModalTolak(id) {
        var item = laporans.find(l => l.id == id);
        if (!item) return;
        document.getElementById('formTolak').action = "/admin/tolak/" + item.id;
        document.getElementById('tTiket').innerText = "Nomor Tiket: " + item.nomor_tiket;
        document.getElementById('txtSurveiInternal').innerText = item.catatan_petugas ? item.catatan_petugas : "Belum ada catatan survei petugas.";
        document.getElementById('tAlasan').value = "";
        document.getElementById('modalTolak').showModal();
    }

    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('adminMap').setView([0.916700, 104.450000], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        laporans.forEach(function(item) {
            if (item.latitude && item.longitude) {
                L.marker([item.latitude, item.longitude]).addTo(map)
                    .bindPopup("<b>" + item.nomor_tiket + "</b><br>" + item.judul);
            }
        });
    });
</script>
@endsection