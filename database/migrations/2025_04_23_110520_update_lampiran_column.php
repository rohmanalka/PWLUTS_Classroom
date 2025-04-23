<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus kolom lampiran dari tabel tugas
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });

        // Tambahkan kolom lampiran ke tabel tugas_mahasiswa sebelum status
        Schema::table('tugas_mahasiswa', function (Blueprint $table) {
            $table->string('lampiran')->nullable()->after('id_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan kembali kolom lampiran ke tabel tugas
        Schema::table('tugas', function (Blueprint $table) {
            $table->string('lampiran')->nullable();
        });

        // Hapus kolom lampiran dari tabel tugas_mahasiswa
        Schema::table('tugas_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });
    }
};
