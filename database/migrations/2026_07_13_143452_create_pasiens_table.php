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
        Schema::create('tbl_pasien', function (Blueprint $table) {
            $table->string('id_pasien', 15)->primary();
            $table->string('nik', 16);
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('golongan_darah', 5);
            $table->string('pekerjaan', 50);
            $table->text('alamat_lengkap');
            $table->string('nomor_telepon', 15);
            $table->text('riwayat_alergi')->nullable();
            $table->enum('jenis_pasien', ['Umum/Mandiri', 'BPJS'])->default('Umum/Mandiri');
            $table->string('no_bpjs', 25)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pasien');
    }
};
