<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PatientAndUserManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resepsionis_can_edit_reset_pw_and_delete_patient(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // 1. Create Pasien Dummy
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-TEST-888',
            'nik' => '3322114455667788',
            'nama_lengkap' => 'Pasien Full Feature Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1992-08-10',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A',
            'pekerjaan' => 'PNS',
            'alamat_lengkap' => 'Jl. Khatib Sulaiman',
            'nomor_telepon' => '081233221100',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-TEST-888',
            'password' => '$2y$12$e0MYzXyjpJS7Pd0RVvHwHeFw6fC2rGzU/mD7Q30rL7p98Q9X.4nK.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Edit Pasien
        $responseEdit = $this->actingAs($superadmin, 'web')->put('/admin/patients/PSN-TEST-888', [
            'nik' => '3322114455667788',
            'nama_lengkap' => 'Pasien Full Feature Updated',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1992-08-10',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A',
            'pekerjaan' => 'PNS',
            'alamat_lengkap' => 'Jl. Khatib Sulaiman No. 20',
            'nomor_telepon' => '081233221100',
            'jenis_pasien' => 'Umum/Mandiri',
        ]);
        $responseEdit->assertRedirect('/admin/patients');
        $this->assertDatabaseHas('tbl_pasien', ['nama_lengkap' => 'Pasien Full Feature Updated']);

        // 3. Reset Password Pasien (6 Digit Random)
        $responseReset = $this->actingAs($superadmin, 'web')->post('/admin/patients/PSN-TEST-888/reset-password');
        $responseReset->assertRedirect('/admin/patients');
        $responseReset->assertSessionHas('reset_pasien_success', true);

        // 4. Delete Pasien
        $responseDelete = $this->actingAs($superadmin, 'web')->delete('/admin/patients/PSN-TEST-888');
        $responseDelete->assertRedirect('/admin/patients');
        $this->assertDatabaseMissing('tbl_pasien', ['id_pasien' => 'PSN-TEST-888']);
    }

    public function test_superadmin_can_toggle_user_status_and_block_login(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // 1. Create User Pegawai Dummy
        DB::table('tbl_pengguna')->insert([
            'id_pengguna' => 'STF-888',
            'nama_lengkap' => 'Staf Block Test',
            'username' => 'stafblock',
            'password' => bcrypt('password123'),
            'peran' => 'Resepsionis',
            'no_telepon_pegawai' => '08123456888',
            'status_aktif' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Toggle Status to Nonaktif
        $responseToggle = $this->actingAs($superadmin, 'web')->post('/admin/users/STF-888/toggle-status');
        $responseToggle->assertRedirect('/admin/users');
        $this->assertDatabaseHas('tbl_pengguna', ['id_pengguna' => 'STF-888', 'status_aktif' => 'Nonaktif']);

        // 3. Unauthenticate superadmin before testing login form
        $this->post('/admin/logout');

        // 4. Attempt Login with Nonaktif User (Must Fail)
        $responseLogin = $this->post('/admin/login', [
            'username' => 'stafblock',
            'password' => 'password123',
        ]);
        $responseLogin->assertSessionHasErrors('username');
        $this->assertGuest('web');
    }
}
