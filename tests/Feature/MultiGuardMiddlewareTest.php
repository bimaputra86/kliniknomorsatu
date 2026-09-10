<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MultiGuardMiddlewareTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_patient_is_redirected_away_from_login_and_register(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-TEST-001',
            'nik' => '1111222233334444',
            'nama_lengkap' => 'Test Pasien',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Mahasiswa',
            'alamat_lengkap' => 'Jl. Test',
            'nomor_telepon' => '0812345',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-TEST-001',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pasien = Pasien::find('PSN-TEST-001');

        // Login sebagai Pasien
        $response = $this->actingAs($pasien, 'pasien')->get('/login');
        $response->assertRedirect('/pasien/dashboard');

        $responseRegister = $this->actingAs($pasien, 'pasien')->get('/register');
        $responseRegister->assertRedirect('/pasien/dashboard');
    }

    public function test_authenticated_employee_is_redirected_away_from_admin_login(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // Login sebagai Staf / Admin
        $response = $this->actingAs($superadmin, 'web')->get('/admin/login');
        $response->assertRedirect('/admin/dashboard');
    }
}
