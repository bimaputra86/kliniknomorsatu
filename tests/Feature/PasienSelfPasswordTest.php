<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasienSelfPasswordTest extends TestCase
{
    use DatabaseTransactions;

    public function test_patient_is_redirected_to_dashboard_after_login(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-NAV-111',
            'nik' => '1111222233334444',
            'nama_lengkap' => 'Pasien Nav Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1998-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Veteran',
            'nomor_telepon' => '081211112222',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-NAV-111',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/login', [
            'username' => 'PSN-NAV-111',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/pasien/dashboard');
    }

    public function test_patient_can_change_password_self(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-PW-222',
            'nik' => '2222333344445555',
            'nama_lengkap' => 'Pasien PW Self Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1998-01-01',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'B',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Raden Saleh',
            'nomor_telepon' => '081222223333',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-PW-222',
            'password' => Hash::make('oldsecret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pasien = Pasien::find('PSN-PW-222');

        $response = $this->actingAs($pasien, 'pasien')->put('/pasien/password', [
            'current_password' => 'oldsecret123',
            'password' => 'newsecret456',
            'password_confirmation' => 'newsecret456',
        ]);

        $response->assertRedirect('/pasien/dashboard');
        $response->assertSessionHas('success');

        $updatedPasien = DB::table('tbl_pasien')->where('id_pasien', 'PSN-PW-222')->first();
        $this->assertTrue(Hash::check('newsecret456', $updatedPasien->password));
    }
}
