<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientResetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    public function test_patient_can_reset_password_with_valid_nik_and_birthdate(): void
    {
        // Insert pasien dummy
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-TEST-999',
            'nik' => '1122334455667788',
            'nama_lengkap' => 'Pasien Forgot PW Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-10-20',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Pegawai',
            'alamat_lengkap' => 'Jl. Khatib Sulaiman No. 10',
            'nomor_telepon' => '081234567890',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-TEST-999',
            'password' => Hash::make('oldpassword'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/forgot-password', [
            'nik' => '1122334455667788',
            'tanggal_lahir' => '1995-10-20',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('info');

        // Verifikasi password baru dapat digunakan untuk login dan langsung redirect ke /pasien/dashboard
        $this->post('/login', [
            'username' => 'PSN-TEST-999',
            'password' => 'newsecret123',
        ])->assertRedirect('/pasien/dashboard');
    }
}
