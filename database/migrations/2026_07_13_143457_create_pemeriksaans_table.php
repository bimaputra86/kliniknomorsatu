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
        Schema::create('tbl_pemeriksaan', function (Blueprint $table) {
            $table->string('id_pemeriksaan', 15)->primary();
            $table->date('tanggal_pemeriksaan');
            $table->string('id_pasien', 15);
            $table->string('id_pengguna', 10);
            $table->string('tekanan_darah', 20)->nullable();
            $table->string('suhu_tubuh', 10)->nullable();
            $table->string('berat_badan', 10)->nullable();
            $table->text('keluhan_utama')->nullable();
            $table->text('diagnosis_penyakit')->nullable();
            $table->text('tindakan_medis')->nullable();
            $table->timestamps();

            $table->foreign('id_pasien')->references('id_pasien')->on('tbl_pasien')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('tbl_pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pemeriksaan');
    }
};
