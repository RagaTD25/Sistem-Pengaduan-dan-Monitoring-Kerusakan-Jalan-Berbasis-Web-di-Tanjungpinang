<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->text('instruksi_admin')->nullable()->after('status'); // Disposisi perintah ke Petugas
            $table->text('alasan_penolakan')->nullable()->after('instruksi_admin'); // Keterangan jika ditolak
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['instruksi_admin', 'alasan_penolakan']);
        });
    }
};