<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('laporans', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_tiket')->unique();
        $table->string('nama_pelapor')->default('Warga Tanjungpinang');
        $table->string('judul');
        $table->string('kategori');
        $table->text('deskripsi')->nullable();
        $table->decimal('latitude', 10, 8);
        $table->decimal('longitude', 11, 8);
        $table->string('foto_kerusakan')->nullable();
        $table->string('status')->default('Diajukan');
        $table->text('catatan_petugas')->nullable();
        $table->string('foto_perbaikan')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};