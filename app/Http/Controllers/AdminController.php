<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $laporans = Laporan::latest()->get();

        $menungguVerifikasi = Laporan::where('status', 'Diajukan')->count();
        $dalamPenanganan = Laporan::whereIn('status', ['Pengecekan', 'Dalam Pengerjaan', 'Rekomendasi Tolak'])->count();
        $selesaiBulanIni = Laporan::where('status', 'Selesai')->count();

        return view('admin.dashboard', compact('laporans', 'menungguVerifikasi', 'dalamPenanganan', 'selesaiBulanIni'));
    }

    // Process: Disposisi Petugas & Penerbitan Pesan Resmi Publik Warga
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'instruksi_admin' => 'required|string|min:5',
            'tanggapan_admin' => 'required|string|min:10',
        ], [
            'instruksi_admin.required' => 'Instruksi internal untuk petugas lapangan wajib diisi!',
            'tanggapan_admin.required' => 'Pesan formal untuk warga wajib diisi!',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'Pengecekan';
        $laporan->instruksi_admin = $request->instruksi_admin;
        $laporan->tanggapan_admin = $request->tanggapan_admin;
        $laporan->save();

        return redirect()->back()->with('success', "Laporan {$laporan->nomor_tiket} berhasil didisposisikan dan pemberitahuan formal dikirim ke Warga!");
    }

    // Process: Konfirmasi Penolakan Resmi
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan resmi untuk warga wajib diisi secara formal!',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'Ditolak';
        $laporan->alasan_penolakan = $request->alasan_penolakan;
        $laporan->tanggapan_admin = $request->alasan_penolakan;
        $laporan->save();

        return redirect()->back()->with('error', "Laporan {$laporan->nomor_tiket} telah resmi ditolak.");
    }
}