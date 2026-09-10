<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SelfProfileManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_update_own_profile_info(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        $response = $this->actingAs($superadmin, 'web')->put('/admin/profile', [
            'nama_lengkap' => 'Super Administrator Updated',
            'username' => 'superadmin',
            'no_telepon_pegawai' => '081299990000',
        ]);

        $response->assertRedirect('/admin/profile');
        $response->assertSessionHas('success');

        $superadmin->refresh();
        $this->assertEquals('Super Administrator Updated', $superadmin->nama_lengkap);
        $this->assertEquals('081299990000', $superadmin->no_telepon_pegawai);
    }

    public function test_user_can_update_own_password_with_three_inputs(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        $response = $this->actingAs($superadmin, 'web')->put('/admin/profile/password', [
            'current_password' => 'superadmin',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/admin/profile');
        $response->assertSessionHas('success');

        $superadmin->refresh();
        $this->assertTrue(Hash::check('newpassword123', $superadmin->password));
    }

    public function test_user_fails_if_current_password_is_incorrect(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        $response = $this->actingAs($superadmin, 'web')->put('/admin/profile/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }
}
