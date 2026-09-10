<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Pasien;

class PatientLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_display_login_form(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Portal Pasien');
        $response->assertSee('Panduan Login Pasien');
        $response->assertSee('Daftar Pasien Baru');
    }

    public function test_mandiri_patient_can_login_with_nik(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-260728-0001',
            'nik' => '1371011505950001',
            'nama_lengkap' => 'Budi Santoso',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Khatib Sulaiman',
            'nomor_telepon' => '08123456789',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-260728-0001',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test login menggunakan NIK untuk Pasien Mandiri
        $response = $this->post('/login', [
            'username' => '1371011505950001',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/pasien/dashboard');
        $this->assertAuthenticatedAs(Pasien::find('PSN-260728-0001'), 'pasien');
    }

    public function test_bpjs_patient_can_login_with_nik_or_no_bpjs(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-260728-0002',
            'nik' => '1371022008980002',
            'nama_lengkap' => 'Siti Aminah',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1998-08-20',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'A',
            'pekerjaan' => 'PNS',
            'alamat_lengkap' => 'Jl. Sudirman',
            'nomor_telepon' => '08987654321',
            'jenis_pasien' => 'BPJS',
            'no_bpjs' => '0001234567890',
            'username' => '0001234567890',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1. Login menggunakan No. BPJS
        $responseBPJS = $this->post('/login', [
            'username' => '0001234567890',
            'password' => 'secret123',
        ]);
        $responseBPJS->assertRedirect('/pasien/dashboard');
        $this->assertAuthenticatedAs(Pasien::find('PSN-260728-0002'), 'pasien');

        // Logout
        $this->post('/logout');

        // 2. Login menggunakan NIK untuk Pasien BPJS
        $responseNIK = $this->post('/login', [
            'username' => '1371022008980002',
            'password' => 'secret123',
        ]);
        $responseNIK->assertRedirect('/pasien/dashboard');
        $this->assertAuthenticatedAs(Pasien::find('PSN-260728-0002'), 'pasien');
    }

    public function test_cannot_login_with_wrong_password(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-260728-0003',
            'nik' => '1371030101900003',
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'B',
            'pekerjaan' => 'Wiraswasta',
            'alamat_lengkap' => 'Jl. Veteran',
            'nomor_telepon' => '0811223344',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-260728-0003',
            'password' => Hash::make('PSN-260728-0003'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/login', [
            'username' => '1371030101900003',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest('pasien');
    }
}
