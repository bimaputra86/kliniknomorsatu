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
        Schema::create('tbl_poli', function (Blueprint $table) {
            $table->string('id_poli', 10)->primary();
            $table->string('nama_poli', 50)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tambah id_poli (nullable/FK) ke tbl_jadwal_dokter
        Schema::table('tbl_jadwal_dokter', function (Blueprint $table) {
            $table->string('id_poli', 10)->nullable()->after('id_pengguna');
            $table->foreign('id_poli')->references('id_poli')->on('tbl_poli')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_jadwal_dokter', function (Blueprint $table) {
            $table->dropForeign(['id_poli']);
            $table->dropColumn('id_poli');
        });

        Schema::dropIfExists('tbl_poli');
    }
};
