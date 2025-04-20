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
        Schema::create('kelas_mahasiswa', function (Blueprint $table) {
            $table->id('id_kelasmhs');
            $table->unsignedBigInteger('id_kelas')->index();
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->timestamps();

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_mahasiswa');
    }
};
