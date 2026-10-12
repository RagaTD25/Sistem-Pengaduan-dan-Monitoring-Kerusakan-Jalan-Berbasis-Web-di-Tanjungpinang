@extends('layouts.app')

@section('content')
<div class="mb-6">
    <span class="inline-block bg-white text-c-dark font-bold text-xs px-3.5 py-1 rounded-full border-2 border-c-medium shadow-sm mb-2">
        Petugas Lapangan
    </span>
    <h1 class="text-3xl font-extrabold text-c-dark tracking-tight">Daftar Tugas & Validasi Lapangan</h1>
    <p class="text-sm text-c-primary font-medium mt-1">Lakukan survei lokasi, validasi kebenaran kerusakan, dan perbarui progres fisik.</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border-2 border-green-500 text-green-800 p-4 rounded-xl font-bold mb-6 text-sm">
        ✅ {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border-2 border-red-500 text-red-800 p-4 rounded-xl font-bold mb-6 text-sm">
        ⚠️ {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    @forelse($tugasAktif as $tugas)
        <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex justify-between items-start border-b-2 border-c-bg pb-3">
                <div>
                    <span class="text-xs font-bold text-c-medium uppercase">Nomor Tiket</span>
                    <h2 class="text-xl font-black text-c-dark">{{ $tugas->nomor_tiket }}</h2>
                    <p class="text-xs font-bold text-c-primary">{{ $tugas->judul }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-black border {{ $tugas->status_badge_class }}">
                    {{ $tugas->status }}
                </span>
            </div>

            <!-- Perintah Disposisi Admin -->
            <div class="bg-blue-50 border-2 border-blue-300 p-3.5 rounded-xl">
                <p class="text-xs font-black text-c-primary uppercase tracking-wider mb-1">📋 Perintah Disposisi Admin PUPR:</p>
                <p class="text-xs font-bold text-c-dark leading-relaxed">
                    {{ $tugas->instruksi_admin ?? 'Lakukan survei lokasi dan periksa kebenaran laporan.' }}
                </p>
            </div>

            <!-- Form Pembaruan Status & Validasi -->
            <form action="{{ route('petugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 pt-2">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-c-dark uppercase mb-1">Hasil Survei & Status Pekerjaan</label>
                    <select name="status" onchange="toggleFormFields(this, '{{ $tugas->id }}')" class="w-full p-2.5 rounded-xl border-2 border-c-medium text-xs font-bold bg-white focus:outline-none">
                        <option value="Pengecekan" {{ $tugas->status == 'Pengecekan' ? 'selected' : '' }}>🔍 Dalam Survei Lokasi (Pengecekan Fisik)</option>
                        <option value="Dalam Pengerjaan" {{ $tugas->status == 'Dalam Pengerjaan' ? 'selected' : '' }}>✅ Valid (Beneran Rusak - Mulai Perbaikan)</option>
                        <option value="Rekomendasi Tolak" {{ $tugas->status == 'Rekomendasi Tolak' ? 'selected' : '' }}>❌ Tidak Valid (Jalan Tidak Rusak / Fiktif -> Usulkan Tolak)</option>
                        <option value="Selesai" {{ $tugas->status == 'Selesai' ? 'selected' : '' }}>🎉 Selesai (Perbaikan Tuntas)</option>
                    </select>
                </div>

                <!-- Input Estimasi Waktu (Muncul jika Valid / Dalam Pengerjaan) -->
                <div id="boxEstimasi_{{ $tugas->id }}" class="{{ $tugas->status == 'Dalam Pengerjaan' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-c-dark uppercase mb-1">Estimasi Waktu Pengerjaan</label>
                    <input type="text" name="estimasi_selesai" value="{{ $tugas->estimasi_selesai }}" placeholder="Contoh: 2 Hari (Target Selesai Besok)" class="w-full p-2.5 rounded-xl border-2 border-c-medium text-xs font-bold text-c-dark bg-yellow-50 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-c-dark uppercase mb-1">Catatan Hasil Survei / Pekerjaan Petugas</label>
                    <textarea name="catatan_petugas" rows="2" required placeholder="Tuliskan kondisi fisik di lapangan (misal: 'Sudah di-survei, lokasi aman tidak ada kerusakan' ATAU 'Laporan valid, butuh 2 drum aspal')" class="w-full p-2.5 rounded-xl border-2 border-c-medium text-xs focus:outline-none">{{ old('catatan_petugas', $tugas->catatan_petugas) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-c-dark uppercase mb-1">
                        Unggah Foto Hasil Perbaikan <span class="text-red-500 font-normal text-[11px]">(Wajib untuk tahap Selesai)</span>
                    </label>
                    <input type="file" name="foto_perbaikan" accept="image/*" class="w-full text-xs text-c-dark bg-gray-50 p-2 rounded-xl border-2 border-c-medium">
                </div>

                <button type="submit" class="w-full bg-c-dark hover:bg-black text-white font-black py-3 rounded-xl text-xs transition">
                    Update Status & Kirim ke Admin PUPR
                </button>
            </form>
        </div>
    @empty
        <div class="col-span-2 bg-white border-2 border-c-medium rounded-2xl p-8 text-center text-c-medium font-bold">
            🎉 Tidak ada tugas penanganan aktif saat ini.
        </div>
    @endforelse
</div>

<script>
    function toggleFormFields(selectElem, id) {
        var boxEstimasi = document.getElementById('boxEstimasi_' + id);
        if (selectElem.value === 'Dalam Pengerjaan') {
            boxEstimasi.classList.remove('hidden');
        } else {
            boxEstimasi.classList.add('hidden');
        }
    }
</script>
@endsection