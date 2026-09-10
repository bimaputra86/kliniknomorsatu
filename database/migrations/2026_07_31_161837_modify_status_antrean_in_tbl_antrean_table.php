<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tbl_antrean MODIFY COLUMN status_antrean ENUM('Menunggu', 'Dipanggil', 'Diperiksa', 'Selesai', 'Batal', 'Dibatalkan') NOT NULL DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tbl_antrean MODIFY COLUMN status_antrean ENUM('Menunggu', 'Diperiksa', 'Selesai', 'Batal') NOT NULL DEFAULT 'Menunggu'");
    }
};
