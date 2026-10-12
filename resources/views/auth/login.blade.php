@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-6">
    <!-- Card utama split design -->
    <div class="w-full max-w-4xl bg-c-dark rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-white/10">
        
        <!-- BAGIAN KIRI: Putih (Info & Ilustrasi) -->
        <div class="bg-white p-8 sm:p-10 flex flex-col justify-between items-center text-center">
            
            <!-- Header Logo -->
            <div class="flex items-center space-x-3 w-full justify-start text-left">
                <img src="{{ asset('image/Kepulauan Riau.jpg') }}" alt="Logo Kepri" class="h-10 w-auto bg-c-primary/10 p-1 rounded-lg border border-c-primary/20" onerror="this.src='{{ asset('image/Kepulauan Riau.png') }}'">
                <div>
                    <h2 class="text-base font-black text-c-dark leading-tight">SIGAP Jalan</h2>
                    <p class="text-[11px] font-semibold text-c-medium">Dinas PUPR Tanjungpinang</p>
                </div>
            </div>

            <!-- Icon Ilustrasi Jalan -->
            <div class="my-8 flex flex-col items-center">
                <div class="w-36 h-36 bg-c-bg rounded-full flex items-center justify-center border-4 border-c-medium/20 shadow-inner">
                    <svg class="w-20 h-20 text-c-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <h3 class="mt-4 font-black text-c-dark text-base">Sistem Pengaduan Jalan</h3>
                <p class="text-xs text-gray-500 max-w-xs mt-1 font-medium">Lapor kerusakan jalan di Tanjungpinang secara cepat dan mudah.</p>
            </div>

            <!-- Footer Kiri -->
            <div class="text-[10px] text-gray-400 font-medium w-full text-left">
                © 2026 Dinas PUPR Tanjungpinang
            </div>
        </div>

        <!-- BAGIAN KANAN: Form Login Dark -->
        <div class="bg-c-dark p-8 sm:p-10 flex flex-col justify-center text-white space-y-5">
            <div>
                <h2 class="text-2xl font-black text-white">Login</h2>
                <p class="text-xs text-c-light mt-1">Masukkan akun Anda untuk melanjutkan</p>
            </div>

            <!-- Notifikasi Error/Sukses -->
            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500/40 text-red-200 text-xs p-3 rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 text-xs p-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1 ml-1">Email / Username</label>
                    <input type="text" name="email" value="{{ old('email') }}" required placeholder="Enter your username/email" 
                        class="w-full bg-slate-800/90 border border-slate-700 text-white text-xs rounded-full px-4 py-3 outline-none focus:border-c-medium focus:ring-1 focus:ring-c-medium transition placeholder:text-gray-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1 ml-1">Password</label>
                    <input type="password" name="password" required placeholder="Enter your password" 
                        class="w-full bg-slate-800/90 border border-slate-700 text-white text-xs rounded-full px-4 py-3 outline-none focus:border-c-medium focus:ring-1 focus:ring-c-medium transition placeholder:text-gray-500">
                    <div class="text-right mt-1">
                        <a href="#" class="text-[10px] text-c-light hover:underline">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-c-medium hover:bg-c-light text-white font-bold text-xs py-3 rounded-full shadow-md transition">
                    Login
                </button>
            </form>

            <!-- Shortcut Login Akses Cepat -->
            <div class="pt-3 border-t border-slate-800 text-center space-y-2">
                <p class="text-[10px] text-gray-400">Masuk cepat sebagai:</p>
                <div class="flex justify-center gap-1.5">
                    <a href="{{ route('login.as', ['role' => 'warga']) }}" class="bg-slate-800 hover:bg-slate-700 text-[10px] px-3 py-1 rounded-full font-bold text-gray-300 border border-slate-700">Warga</a>
                    <a href="{{ route('login.as', ['role' => 'petugas']) }}" class="bg-slate-800 hover:bg-slate-700 text-[10px] px-3 py-1 rounded-full font-bold text-gray-300 border border-slate-700">Petugas</a>
                    <a href="{{ route('login.as', ['role' => 'admin']) }}" class="bg-slate-800 hover:bg-slate-700 text-[10px] px-3 py-1 rounded-full font-bold text-gray-300 border border-slate-700">Admin</a>
                </div>
            </div>

            <!-- Footer Form -->
            <div class="text-center text-[10px] text-gray-400">
                Belum punya akun? <a href="#" class="text-c-light font-bold hover:underline">Daftar Sekarang</a>
            </div>
        </div>

    </div>
</div>
@endsection