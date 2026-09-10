<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class PatientRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_display_registration_form(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Pasien Baru');
    }

    public function test_can_register_mandiri_patient(): void
    {
        $response = $this->post('/register', [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Khatib Sulaiman No. 10',
            'nomor_telepon' => '08123456789',
            'riwayat_alergi' => 'Tidak ada',
            'jenis_pasien' => 'Umum/Mandiri',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHas('success_registration');

        $this->assertDatabaseHas('tbl_pasien', [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'jenis_pasien' => 'Umum/Mandiri',
        ]);

        $pasien = DB::table('tbl_pasien')->where('nik', '1234567890123456')->first();
        $this->assertEquals($pasien->id_pasien, $pasien->username);
    }

    public function test_can_register_bpjs_patient(): void
    {
        $response = $this->post('/register', [
            'nik' => '9876543210987654',
            'nama_lengkap' => 'Siti Aminah',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1998-08-20',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'A',
            'pekerjaan' => 'PNS',
            'alamat_lengkap' => 'Jl. Sudirman No. 45',
            'nomor_telepon' => '08987654321',
            'jenis_pasien' => 'BPJS',
            'no_bpjs' => '0001234567890',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHas('success_registration');

        $this->assertDatabaseHas('tbl_pasien', [
            'nik' => '9876543210987654',
            'no_bpjs' => '0001234567890',
            'username' => '0001234567890',
        ]);
    }
}
