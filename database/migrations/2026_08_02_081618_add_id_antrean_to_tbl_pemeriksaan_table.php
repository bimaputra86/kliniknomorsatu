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
        Schema::table('tbl_pemeriksaan', function (Blueprint $table) {
            $table->unsignedInteger('id_antrean')->nullable()->after('id_pemeriksaan');
            $table->string('nadi', 10)->nullable()->after('suhu_tubuh');
            $table->string('tinggi_badan', 10)->nullable()->after('berat_badan');

            $table->foreign('id_antrean')->references('id_antrean')->on('tbl_antrean')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pemeriksaan', function (Blueprint $table) {
            $table->dropForeign(['id_antrean']);
            $table->dropColumn(['id_antrean', 'nadi', 'tinggi_badan']);
        });
    }
};
