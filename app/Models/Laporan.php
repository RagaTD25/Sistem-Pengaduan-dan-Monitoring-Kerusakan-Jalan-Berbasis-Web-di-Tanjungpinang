<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_tiket',
        'nama_pelapor',
        'judul',
        'kategori',
        'deskripsi',
        'latitude',
        'longitude',
        'foto_kerusakan',
        'status',
        'instruksi_admin',   // Internal: Perintah Admin -> Petugas
        'tanggapan_admin',   // Publik: Pesan Formal Admin -> Warga
        'estimasi_selesai',
        'alasan_penolakan',  // Publik: Alasan Penolakan Formal
        'catatan_petugas',  // Internal: Laporan Petugas -> Admin
        'foto_perbaikan',
    ];

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Diajukan' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'Pengecekan' => 'bg-orange-100 text-orange-800 border-orange-300',
            'Rekomendasi Tolak' => 'bg-purple-100 text-purple-800 border-purple-300',
            'Dalam Pengerjaan' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Selesai' => 'bg-green-600 text-white',
            'Ditolak' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}