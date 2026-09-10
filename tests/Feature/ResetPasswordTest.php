<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    public function test_superadmin_can_reset_user_password_to_six_digits(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // Buat user dummy
        DB::table('tbl_pengguna')->insert([
            'id_pengguna' => 'STF-777',
            'nama_lengkap' => 'Staf Reset Test',
            'username' => 'stafreset',
            'password' => '$2y$12$e0MYzXyjpJS7Pd0RVvHwHeFw6fC2rGzU/mD7Q30rL7p98Q9X.4nK.',
            'peran' => 'Resepsionis',
            'no_telepon_pegawai' => '08123456777',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($superadmin, 'web')->post('/admin/users/STF-777/reset-password');

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('reset_success', true);

        $newPassword = session('new_password');
        $this->assertEquals(6, strlen($newPassword));
        $this->assertTrue(ctype_digit($newPassword));
    }
}
