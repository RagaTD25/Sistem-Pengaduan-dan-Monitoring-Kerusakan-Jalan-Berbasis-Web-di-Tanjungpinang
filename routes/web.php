<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// 1. RUTE WARGA
Route::get('/', [LaporanController::class, 'index'])->name('warga.laporan');
Route::get('/buat-laporan', [LaporanController::class, 'create'])->name('warga.buat-laporan');
Route::post('/simpan-laporan', [LaporanController::class, 'store'])->name('warga.simpan-laporan');
Route::get('/lacak-status', [LaporanController::class, 'lacak'])->name('warga.lacak');


// 2. RUTE LOGIN & SESSION
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login-process', function (Request $request) {
    $role = $request->input('role', 'warga');
    $email = $request->input('email', 'user@tanjungpinang.go.id');

    session([
        'user_email' => $email,
        'role' => $role
    ]);

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'petugas') {
        return redirect()->route('petugas.dashboard');
    } else {
        return redirect()->route('warga.laporan');
    }
})->name('login.process');

Route::get('/login-as/{role}', function ($role) {
    session([
        'user_email' => $role . '@tanjungpinang.go.id',
        'role' => $role
    ]);

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'petugas') {
        return redirect()->route('petugas.dashboard');
    } else {
        return redirect()->route('warga.laporan');
    }
})->name('login.as');

Route::get('/logout', function () {
    session()->forget(['user_email', 'role']);
    return redirect()->route('login');
})->name('logout');

// 3. RUTE ADMIN PUPR
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/verifikasi/{id}', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
Route::post('/admin/tolak/{id}', [AdminController::class, 'tolak'])->name('admin.tolak');

// 4. RUTE PETUGAS LAPANGAN
Route::get('/petugas', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
Route::post('/petugas/update/{id}', [PetugasController::class, 'updateStatus'])->name('petugas.update');