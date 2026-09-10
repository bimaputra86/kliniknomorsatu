<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MasterPoliManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resepsionis_can_view_and_manage_master_poli(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        // 1. Tambah Poli Baru
        $responseStore = $this->actingAs($superadmin, 'web')->post('/admin/polis', [
            'nama_poli' => 'Poli THT Test',
            'deskripsi' => 'Layanan kesehatan telinga hidung tenggorokan.',
        ]);
        $responseStore->assertRedirect('/admin/polis');
        $this->assertDatabaseHas('tbl_poli', ['nama_poli' => 'Poli THT Test']);

        $poli = DB::table('tbl_poli')->where('nama_poli', 'Poli THT Test')->first();

        // 2. Edit Poli
        $responseUpdate = $this->actingAs($superadmin, 'web')->put('/admin/polis/' . $poli->id_poli, [
            'nama_poli' => 'Poli THT Updated',
            'deskripsi' => 'Layanan THT lengkap.',
        ]);
        $responseUpdate->assertRedirect('/admin/polis');
        $this->assertDatabaseHas('tbl_poli', ['nama_poli' => 'Poli THT Updated']);

        // 3. Hapus Poli
        $responseDelete = $this->actingAs($superadmin, 'web')->delete('/admin/polis/' . $poli->id_poli);
        $responseDelete->assertRedirect('/admin/polis');
        $this->assertDatabaseMissing('tbl_poli', ['id_poli' => $poli->id_poli]);
    }
}
