<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class EmployeeManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_login_as_superadmin(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'superadmin',
            'password' => 'superadmin',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs(User::where('username', 'superadmin')->first(), 'web');
    }

    public function test_superadmin_can_create_new_user_with_spatie_roles(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        $response = $this->actingAs($superadmin, 'web')->post('/admin/users', [
            'id_pengguna' => 'DKT-999',
            'nama_lengkap' => 'dr. Budi Raharjo, Sp.PD',
            'username' => 'drbudi',
            'password' => 'password123',
            'peran' => 'Dokter',
            'no_telepon_pegawai' => '081299887766',
            'roles' => ['Dokter', 'Perawat'], // Multiple roles!
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $newUser = User::find('DKT-999');
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('Dokter'));
        $this->assertTrue($newUser->hasRole('Perawat'));
    }

    public function test_can_search_and_filter_users(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // Test search
        $responseSearch = $this->actingAs($superadmin, 'web')->get('/admin/users?search=Super');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Super Administrator');

        // Test role filter
        $responseRole = $this->actingAs($superadmin, 'web')->get('/admin/users?role=Superadmin');
        $responseRole->assertStatus(200);
        $responseRole->assertSee('Super Administrator');
    }
}
