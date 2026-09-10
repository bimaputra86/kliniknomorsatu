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
        Schema::create('tbl_obat', function (Blueprint $table) {
            $table->string('id_obat', 10)->primary();
            $table->string('nama_obat', 100);
            $table->string('jenis_obat', 50);
            $table->integer('harga_satuan');
            $table->integer('stok');
            $table->string('satuan', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_obat');
    }
};
