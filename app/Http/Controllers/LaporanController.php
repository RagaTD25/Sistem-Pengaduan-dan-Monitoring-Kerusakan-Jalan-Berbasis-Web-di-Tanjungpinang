<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    // Dashboard Warga - Menampilkan Data Realtime & Statistik
    public function index()
    {
        // Otomatis isi data sampel wireframe jika database masih kosong
        $this->seedInitialData();

        $laporans = Laporan::latest()->get();
        $totalDiajukan = Laporan::count();
        $sedangDitangani = Laporan::where('status', 'Dalam Penanganan')->count();
        $selesai = Laporan::where('status', 'Selesai')->count();

        return view('warga.laporan', compact('laporans', 'totalDiajukan', 'sedangDitangani', 'selesai'));
    }

    // Form Buat Laporan
    public function create()
    {
        return view('warga.buat-laporan');
    }

    // Proses Simpan Laporan Baru (Realtime Tanggal & Database)
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'koordinat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $coords = explode(',', $request->koordinat);
        $lat = trim($coords[0] ?? '0.916700');
        $lng = trim($coords[1] ?? '104.450000');

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('laporan-foto', 'public');
        }

        // Nomor Tiket Format TPI-2026-XXXX
        $nomorTiket = 'TPI-2026-' . sprintf("%04d", rand(1, 9999));

        Laporan::create([
            'nomor_tiket' => $nomorTiket,
            'nama_pelapor' => session('user_email', 'Warga Tanjungpinang'),
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'latitude' => $lat,
            'longitude' => $lng,
            'foto_kerusakan' => $fotoPath,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('warga.lacak', ['tiket' => $nomorTiket])
            ->with('success', "Laporan berhasil dibuat! Nomor tiket Anda: {$nomorTiket}");
    }

    // HALAMAN TERPISAH: Cek / Lacak Status
    public function lacak(Request $request)
    {
        $tiketSearch = $request->input('tiket');
        $laporan = null;

        if ($tiketSearch) {
            // Normalisasi strip/dash (mengubah en-dash/em-dash '–' menjadi '-' keyboard)
            $cleanTiket = str_replace(['–', '—', ' '], ['-', '-', ''], trim($tiketSearch));

            $laporan = Laporan::where('nomor_tiket', $cleanTiket)
                ->orWhere('nomor_tiket', trim($tiketSearch))
                ->orWhere('nomor_tiket', 'LIKE', '%' . $cleanTiket . '%')
                ->first();
        }

        return view('warga.lacak-status', compact('laporan', 'tiketSearch'));
    }

    // Helper Auto-Seed Data Sampel Wireframe
    private function seedInitialData()
    {
        if (Laporan::count() === 0) {
            $dataAwal = [
                [
                    'nomor_tiket' => 'TPI-2026-0142',
                    'judul' => 'Jl. DI Panjaitan',
                    'kategori' => 'Jalan berlubang',
                    'deskripsi' => 'Lubang berukuran sedang dekat simpang.',
                    'latitude' => 0.916700,
                    'longitude' => 104.450000,
                    'status' => 'Dalam Penanganan',
                    'created_at' => '2026-09-18 09:00:00'
                ],
                [
                    'nomor_tiket' => 'TPI-2026-0138',
                    'judul' => 'Jl. Wiratno',
                    'kategori' => 'Jalan retak',
                    'deskripsi' => 'Retakan memanjang di bahu jalan.',
                    'latitude' => 0.918200,
                    'longitude' => 104.452100,
                    'status' => 'Diverifikasi',
                    'created_at' => '2026-09-10 10:30:00'
                ],
                [
                    'nomor_tiket' => 'TPI-2026-0121',
                    'judul' => 'Jl. Pramuka',
                    'kategori' => 'Jalan ambles',
                    'deskripsi' => 'Jalan ambles akibat genangan air.',
                    'latitude' => 0.914000,
                    'longitude' => 104.448000,
                    'status' => 'Selesai',
                    'created_at' => '2026-08-28 14:15:00'
                ],
                [
                    'nomor_tiket' => 'TPI-2026-0098',
                    'judul' => 'Jl. Kijang',
                    'kategori' => 'Jalan berlubang',
                    'deskripsi' => 'Laporan tidak sesuai dengan area jalan umum.',
                    'latitude' => 0.912000,
                    'longitude' => 104.445000,
                    'status' => 'Ditolak',
                    'created_at' => '2026-08-02 11:20:00'
                ],
            ];

            foreach ($dataAwal as $row) {
                Laporan::create($row);
            }
        }
    }
}