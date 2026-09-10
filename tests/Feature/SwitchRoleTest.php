<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SwitchRoleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_switch_active_role_session(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // Buat user dummy yang punya role Dokter & Perawat
        DB::table('tbl_pengguna')->insert([
            'id_pengguna' => 'DKT-999',
            'nama_lengkap' => 'dr. Multi Role',
            'username' => 'drmulti',
            'password' => '$2y$12$e0MYzXyjpJS7Pd0RVvHwHeFw6fC2rGzU/mD7Q30rL7p98Q9X.4nK.',
            'peran' => 'Dokter',
            'no_telepon_pegawai' => '0812345',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::find('DKT-999');
        $user->assignRole(['Dokter', 'Perawat']);

        // Switch role ke Perawat
        $response = $this->actingAs($user, 'web')->post('/admin/switch-role', [
            'active_role' => 'Perawat',
        ]);

        $response->assertSessionHas('active_role', 'Perawat');
        $response->assertSessionHas('success');
    }
}
