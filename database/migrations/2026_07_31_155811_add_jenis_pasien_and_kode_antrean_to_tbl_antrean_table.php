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
        Schema::table('tbl_antrean', function (Blueprint $table) {
            $table->string('jenis_pasien', 30)->default('Umum/Mandiri')->after('id_pasien');
            $table->string('kode_antrean', 20)->nullable()->after('nomor_antrean');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_antrean', function (Blueprint $table) {
            $table->dropColumn(['jenis_pasien', 'kode_antrean']);
        });
    }
};
