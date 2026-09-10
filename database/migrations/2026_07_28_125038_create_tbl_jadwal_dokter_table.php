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
        Schema::create('tbl_jadwal_dokter', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->string('id_pengguna', 10); // FK ke tbl_pengguna (Dokter)
            $table->string('nama_poli', 50); // Poli Umum, Poli Gigi, Poli Anak, dll
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota_maksimal')->default(30);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('tbl_pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_jadwal_dokter');
    }
};
