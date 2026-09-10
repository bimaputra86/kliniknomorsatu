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
        Schema::create('tbl_pembayaran', function (Blueprint $table) {
            $table->string('id_pembayaran', 15)->primary();
            $table->string('id_pemeriksaan', 15);
            $table->dateTime('tanggal_pembayaran');
            $table->integer('biaya_layanan_medis');
            $table->integer('biaya_obat');
            $table->integer('total_tagihan');
            $table->integer('nominal_bayar')->nullable();
            $table->integer('kembalian')->nullable();
            $table->enum('status_pembayaran', ['Lunas', 'Menunggu']);
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('tbl_pemeriksaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pembayaran');
    }
};
