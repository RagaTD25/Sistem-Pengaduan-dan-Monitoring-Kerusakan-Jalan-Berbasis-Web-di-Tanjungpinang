<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function dashboard()
    {
        // Menampilkan tugas yang masih butuh penanganan/survei dari petugas
        $tugasAktif = Laporan::whereIn('status', ['Diajukan', 'Pengecekan', 'Rekomendasi Tolak', 'Dalam Pengerjaan'])->latest()->get();

        return view('petugas.dashboard', compact('tugasAktif'));
    }

    public function updateStatus(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $rules = [
            'status' => 'required|string',
            'catatan_petugas' => 'required|string|min:5',
        ];

        if ($request->status === 'Dalam Pengerjaan') {
            $rules['estimasi_selesai'] = 'required|string';
        }

        if ($request->status === 'Selesai') {
            $rules['foto_perbaikan'] = 'required|image|mimes:jpg,jpeg,png|max:5120';
        }

        $request->validate($rules, [
            'catatan_petugas.required' => 'Catatan hasil survei / pemeriksaan wajib diisi!',
            'estimasi_selesai.required' => 'Estimasi waktu pengerjaan wajib diisi!',
            'foto_perbaikan.required' => 'Wajib mengunggah foto hasil perbaikan sebelum menyelesaikan laporan!',
        ]);

        if ($request->hasFile('foto_perbaikan')) {
            $laporan->foto_perbaikan = $request->file('foto_perbaikan')->store('perbaikan-foto', 'public');
        }

        $laporan->status = $request->status;
        $laporan->catatan_petugas = $request->catatan_petugas;
        if ($request->filled('estimasi_selesai')) {
            $laporan->estimasi_selesai = $request->estimasi_selesai;
        }
        $laporan->save();

        if ($request->status === 'Rekomendasi Tolak') {
            return redirect()->route('petugas.dashboard')->with('success', "Hasil survei (Jalan Tidak Rusak/Fiktif) telah dikirim ke Admin PUPR untuk konfirmasi penolakan resmi.");
        }

        if ($request->status === 'Selesai') {
            return redirect()->route('petugas.dashboard')->with('success', "Tiket {$laporan->nomor_tiket} Selesai! Foto hasil perbaikan otomatis dipublikasikan ke Dashboard Warga.");
        }

        return redirect()->back()->with('success', "Progres survei/pengerjaan tiket {$laporan->nomor_tiket} berhasil diperbarui!");
    }
}