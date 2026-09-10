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
        Schema::create('tbl_antrean', function (Blueprint $table) {
            $table->increments('id_antrean');
            $table->date('tanggal_antrean');
            $table->integer('nomor_antrean');
            $table->string('id_pasien', 15);
            $table->enum('status_antrean', ['Menunggu', 'Diperiksa', 'Selesai', 'Batal']);
            $table->timestamps();

            $table->foreign('id_pasien')->references('id_pasien')->on('tbl_pasien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_antrean');
    }
};
