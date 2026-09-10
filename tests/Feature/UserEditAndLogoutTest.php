<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserEditAndLogoutTest extends TestCase
{
    use DatabaseTransactions;

    public function test_superadmin_can_view_edit_user_page(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        $response = $this->actingAs($superadmin, 'web')->get('/admin/users/ADM-001/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Data User');
        $response->assertSee('Super Administrator');
    }

    public function test_superadmin_can_update_user_and_spatie_roles(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // Buat user dummy
        DB::table('tbl_pengguna')->insert([
            'id_pengguna' => 'PER-001',
            'nama_lengkap' => 'Perawat Test',
            'username' => 'perawattest',
            'password' => '$2y$12$e0MYzXyjpJS7Pd0RVvHwHeFw6fC2rGzU/mD7Q30rL7p98Q9X.4nK.',
            'peran' => 'Perawat',
            'no_telepon_pegawai' => '0812341234',
            'status_aktif' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user = User::find('PER-001');
        $user->assignRole('Perawat');

        // Update data user & ubah role Spatie (tambah role Kasir & Resepsionis)
        $response = $this->actingAs($superadmin, 'web')->put('/admin/users/PER-001', [
            'nama_lengkap' => 'Perawat Test Updated',
            'username' => 'perawattest',
            'peran' => 'Perawat',
            'no_telepon_pegawai' => '081299998888',
            'status_aktif' => 'Aktif',
            'roles' => ['Perawat', 'Kasir'], // Role baru!
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $updatedUser = User::find('PER-001');
        $this->assertEquals('Perawat Test Updated', $updatedUser->nama_lengkap);
        $this->assertTrue($updatedUser->hasRole('Perawat'));
        $this->assertTrue($updatedUser->hasRole('Kasir'));
    }
}
