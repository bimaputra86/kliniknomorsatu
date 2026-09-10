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
        Schema::table('tbl_pembayaran', function (Blueprint $table) {
            $table->string('metode_pembayaran', 30)->nullable()->default('Tunai')->after('status_pembayaran');
            $table->string('id_pengguna', 50)->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'id_pengguna']);
        });
    }
};
