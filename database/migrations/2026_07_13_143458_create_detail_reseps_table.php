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
        Schema::create('tbl_detail_resep', function (Blueprint $table) {
            $table->increments('id_resep');
            $table->string('id_pemeriksaan', 15);
            $table->string('id_obat', 10);
            $table->integer('jumlah_obat');
            $table->string('dosis_aturan_pakai', 100);
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('tbl_pemeriksaan')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('tbl_obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_detail_resep');
    }
};
