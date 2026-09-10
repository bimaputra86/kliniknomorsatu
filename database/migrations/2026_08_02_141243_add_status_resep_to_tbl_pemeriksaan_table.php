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
            $table->enum('status_resep', ['Menunggu', 'Diserahkan', 'Tidak Diambil'])->default('Menunggu')->after('tindakan_medis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pemeriksaan', function (Blueprint $table) {
            $table->dropColumn('status_resep');
        });
    }
};
