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
        Schema::create('tugas_mahasiswa', function (Blueprint $table) {
            $table->id('id_tugasmhs');
            $table->unsignedBigInteger('id_tugas')->index();
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->enum('status', ['belum_dikumpulkan', 'sudah_dikumpulkan', 'telat'])->default('belum_dikumpulkan');
            $table->timestamps();

            $table->foreign('id_tugas')->references('id_tugas')->on('tugas');
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugasmhs');
    }
};
