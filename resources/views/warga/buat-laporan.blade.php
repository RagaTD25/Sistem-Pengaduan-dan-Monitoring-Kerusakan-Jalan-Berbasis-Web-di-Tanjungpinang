@extends('layouts.app')

@section('content')
<!-- Badge Warga & Judul Halaman -->
<div class="mb-6">
    <span class="inline-block bg-white text-c-dark font-bold text-xs px-3.5 py-1 rounded-full border-2 border-c-medium shadow-sm mb-2">
        Warga
    </span>
    <h1 class="text-3xl font-extrabold text-c-dark tracking-tight">Buat Laporan Baru</h1>
    <p class="text-sm text-c-primary font-medium mt-1">Lengkapi seluruh bagian berikut sebelum mengirim laporan.</p>
</div>

<!-- Pesan Error Validasi (Jika Ada Input yang Belum Terisi) -->
@if ($errors->any())
    <div class="bg-red-100 border-2 border-red-500 text-red-800 p-4 rounded-xl font-bold mb-6 text-sm">
        ⚠️ Mohon lengkapi seluruh isian formulir dengan benar!
    </div>
@endif

<!-- Form Grid 2 Kartu Utama -->
<form action="{{ route('warga.simpan-laporan') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
    @csrf
    
    <!-- KARTU KIRI: Section 1 & Section 2 -->
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm space-y-6">
        
        <!-- 1. Detail kerusakan -->
        <div class="space-y-4">
            <h2 class="text-lg font-extrabold text-c-dark">1. Detail kerusakan</h2>
            
            <!-- Judul laporan -->
            <div>
                <label class="block text-xs font-bold text-c-dark uppercase mb-1.5">Judul laporan</label>
                <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: jalan berlubang dekat simpang ..." required class="w-full px-4 py-3 rounded-xl border-2 border-c-medium text-sm text-c-dark placeholder-gray-400 focus:outline-none focus:border-c-primary bg-white">
            </div>

            <!-- Kategori kerusakan -->
            <div>
                <label class="block text-xs font-bold text-c-dark uppercase mb-1.5">Kategori kerusakan</label>
                <select name="kategori" required class="w-full px-4 py-3 rounded-xl border-2 border-c-medium text-sm text-c-dark bg-white focus:outline-none focus:border-c-primary cursor-pointer">
                    <option value="" disabled selected>Pilih kategori (jalan berlubang, jalan amblas, ...)</option>
                    <option value="Jalan berlubang" {{ old('kategori') == 'Jalan berlubang' ? 'selected' : '' }}>Jalan Berlubang</option>
                    <option value="Jalan ambles" {{ old('kategori') == 'Jalan ambles' ? 'selected' : '' }}>Jalan Ambles</option>
                    <option value="Jalan retak" {{ old('kategori') == 'Jalan retak' ? 'selected' : '' }}>Jalan Retak</option>
                </select>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-bold text-c-dark uppercase mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="5" placeholder="Jelaskan kondisi kerusakan, perkiraan ukuran, dan risiko ..." class="w-full px-4 py-3 rounded-xl border-2 border-c-medium text-sm text-c-dark placeholder-gray-400 focus:outline-none focus:border-c-primary bg-white">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <!-- 2. Bukti foto -->
        <div class="space-y-3 pt-2">
            <h2 class="text-lg font-extrabold text-c-dark">2. Bukti foto</h2>
            
            <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="previewImage(event)">
            
            <div onclick="document.getElementById('fotoInput').click()" class="border-2 border-c-medium rounded-xl p-8 text-center bg-gray-100 hover:bg-c-bg/30 transition cursor-pointer relative min-h-[140px] flex flex-col items-center justify-center">
                <img id="imagePreview" class="hidden max-h-40 rounded-lg mb-2 border border-c-medium shadow-sm">
                
                <div id="uploadPlaceholder" class="space-y-1">
                    <p class="text-sm font-bold text-c-dark">[ + ] Ketuk untuk unggah foto</p>
                    <p class="text-xs text-gray-500 font-medium">JPG/PNG, maksimal 5 MB</p>
                </div>
            </div>
        </div>

    </div>

    <!-- KARTU KANAN: Section 3 -->
    <div class="bg-white border-2 border-c-medium rounded-2xl p-6 shadow-sm space-y-4">
        <div>
            <h2 class="text-lg font-extrabold text-c-dark">3. Titik lokasi pada peta</h2>
            <p class="text-xs text-c-primary font-medium mt-1">Geser pin ke lokasi kerusakan yang tepat.</p>
        </div>

        <div class="relative">
            <div id="map" class="h-72 rounded-xl border-2 border-c-medium z-10"></div>
            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg border border-c-medium text-[11px] font-bold text-c-dark z-[400] shadow-sm">
                Peta (Leaflet.js + OpenStreetMap)
            </div>
        </div>

        <div class="pt-2">
            <label class="block text-xs font-bold text-c-dark uppercase mb-1.5">Koordinat terkunci</label>
            <input type="text" id="koordinat" name="koordinat" readonly placeholder="Latitude, Longitude" value="0.916700, 104.450000" class="w-full px-4 py-3 rounded-xl border-2 border-c-medium bg-gray-100 font-mono text-sm font-bold text-c-dark focus:outline-none">
        </div>

        <button type="submit" class="w-full bg-c-dark hover:bg-black text-white font-black py-4 px-6 rounded-xl shadow-md transition text-base tracking-wide">
            Kirim Laporan
        </button>

        <p class="text-xs text-gray-500 text-center font-medium leading-relaxed">
            Setelah dikirim, sistem membuat nomor tiket unik dan status awal "Diajukan".
        </p>
    </div>
</form>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('imagePreview');
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('uploadPlaceholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        var defaultLat = 0.916700;
        var defaultLng = 104.450000;

        var map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);
        marker.bindPopup("<b style='font-family:sans-serif; font-size:12px;'>Titik laporan Anda</b>").openPopup();

        function updateCoords(lat, lng) {
            document.getElementById('koordinat').value = lat.toFixed(6) + ", " + lng.toFixed(6);
        }

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
            marker.openPopup();
        });

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            updateCoords(position.lat, position.lng);
            marker.openPopup();
        });
    });
</script>
@endsection