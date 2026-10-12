<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP Jalan - Dinas PUPR Tanjungpinang</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'c-dark': '#03195b',      /* Biru Paling Gelap */
                        'c-primary': '#0b2cb1',   /* Biru Utama */
                        'c-medium': '#1951fc',    /* Biru Sedang */
                        'c-light': '#3781fc',     /* Biru Terang */
                        'c-bg': '#cbe9fd',        /* Biru Muda / Background */
                    }
                }
            }
        }
    </script>

    <!-- Leaflet.js CSS & JS untuk Peta OpenStreetMap -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-c-bg min-h-screen text-c-dark antialiased">
    <!-- Header Navbar -->
    <header class="bg-c-primary text-white shadow-lg border-b-2 border-c-medium sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            
            <!-- Logo Kepri & Identitas Instansi -->
            <a href="{{ route('warga.laporan') }}" class="flex items-center space-x-3">
                <img src="{{ asset('image/Kepulauan Riau.jpg') }}" alt="Logo Kepri" class="h-10 w-auto bg-white/10 p-1 rounded-lg border border-white/20" onerror="this.src='{{ asset('image/Kepulauan Riau.png') }}'">
                <div>
                    <h1 class="text-xl font-black leading-tight tracking-tight">SIGAP Jalan</h1>
                    <p class="text-xs text-c-light font-semibold">Dinas PUPR Tanjungpinang</p>
                </div>
            </a>

            <!-- Navigasi Terpisah Berdasarkan Role -->
            <nav class="flex items-center space-x-2">
                @if(session('role') === 'admin')
                    <span class="bg-yellow-400 text-c-dark font-black text-xs px-3 py-1.5 rounded-lg">Panel Admin PUPR</span>
                    <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-3.5 py-2 rounded-lg transition">Logout</a>
                
                @elseif(session('role') === 'petugas')
                    <span class="bg-green-400 text-c-dark font-black text-xs px-3 py-1.5 rounded-lg">Panel Petugas Lapangan</span>
                    <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-3.5 py-2 rounded-lg transition">Logout</a>
                
                @else
                    <a href="{{ route('warga.laporan') }}" class="px-3.5 py-2 rounded-lg text-sm font-bold transition {{ request()->routeIs('warga.laporan') ? 'bg-c-dark text-white' : 'hover:bg-c-medium text-white' }}">Dashboard Warga</a>
                    
                    <!-- TOMBOL LACAK STATUS (DI SEBELAH DASHBOARD WARGA) -->
                    <a href="{{ route('warga.lacak') }}" class="px-3.5 py-2 rounded-lg text-sm font-bold transition {{ request()->routeIs('warga.lacak') ? 'bg-c-dark text-white' : 'hover:bg-c-medium text-white' }}">Lacak Status</a>
                    
                    <a href="{{ route('warga.buat-laporan') }}" class="px-3.5 py-2 rounded-lg text-sm font-bold transition {{ request()->routeIs('warga.buat-laporan') ? 'bg-c-dark text-white' : 'hover:bg-c-medium text-white' }}">Buat Laporan</a>
                    <a href="{{ route('login') }}" class="bg-white text-c-primary hover:bg-c-bg px-4 py-2 rounded-lg text-sm font-black border border-c-medium shadow-sm transition ml-2">Login</a>
                @endif
            </nav>
        </div>
    </header>

    <!-- Content Utama -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>
</body>
</html>