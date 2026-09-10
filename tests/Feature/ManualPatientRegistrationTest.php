<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ManualPatientRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resepsionis_can_view_and_register_manual_patient(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // Resepsionis mendaftarkan pasien umum baru
        $response = $this->actingAs($superadmin, 'web')->post('/admin/patients', [
            'nik' => '9988776655443322',
            'nama_lengkap' => 'Pasien Walk In Manual',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'B',
            'pekerjaan' => 'Wiraswasta',
            'alamat_lengkap' => 'Jl. Sudirman No. 45',
            'nomor_telepon' => '081288776655',
            'jenis_pasien' => 'Umum/Mandiri',
        ]);

        $response->assertRedirect('/admin/patients');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_pasien', [
            'nik' => '9988776655443322',
            'nama_lengkap' => 'Pasien Walk In Manual',
            'jenis_pasien' => 'Umum/Mandiri',
        ]);
    }
}
