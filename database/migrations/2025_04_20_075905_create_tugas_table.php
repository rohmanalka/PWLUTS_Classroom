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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->unsignedBigInteger('id_kelas')->index();
            $table->String('judul', 100);
            $table->text('deskripsi');
            $table->date('tanggal_diberikan');
            $table->date('deadline');
            $table->string('lampiran')->nullable();
            $table->timestamps();

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
