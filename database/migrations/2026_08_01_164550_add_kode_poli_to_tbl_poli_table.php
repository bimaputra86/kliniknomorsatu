<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_poli', function (Blueprint $table) {
            $table->string('kode_poli', 10)->nullable()->after('nama_poli');
        });

        // Set default kode_poli for existing records
        DB::table('tbl_poli')->where('nama_poli', 'like', '%Umum%')->update(['kode_poli' => 'UM']);
        DB::table('tbl_poli')->where('nama_poli', 'like', '%Gigi%')->update(['kode_poli' => 'GG']);
        DB::table('tbl_poli')->where('nama_poli', 'like', '%Anak%')->update(['kode_poli' => 'AN']);
        DB::table('tbl_poli')->where('nama_poli', 'like', '%Penyakit Dalam%')->update(['kode_poli' => 'PD']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_poli', function (Blueprint $table) {
            $table->dropColumn('kode_poli');
        });
    }
};
