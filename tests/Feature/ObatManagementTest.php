<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ObatManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_apoteker_or_superadmin_can_view_medicines_list(): void
    {
        $superadmin = User::where('username', 'superadmin')->first() ?? User::factory()->create(['username' => 'superadmin', 'peran' => 'Superadmin']);

        // Insert mock medicine
        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-9999'],
            [
                'nama_obat' => 'Amoxicillin 500mg Test',
                'jenis_obat' => 'Tablet',
                'harga_satuan' => 3000,
                'stok' => 150,
                'satuan' => 'Tablet',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $response = $this->actingAs($superadmin, 'web')
            ->get(route('admin.obat.index'));

        $response->assertStatus(200);
        $response->assertSee('Amoxicillin 500mg Test');
        $response->assertSee('OBT-9999');
    }

    public function test_can_add_medicine_with_server_side_validation_and_db_transaction(): void
    {
        $superadmin = User::where('username', 'superadmin')->first() ?? User::factory()->create(['username' => 'superadmin', 'peran' => 'Superadmin']);

        // Test creation with validation error
        $responseErr = $this->actingAs($superadmin, 'web')
            ->post(route('admin.obat.store'), [
                'nama_obat' => '', // Empty invalid
                'jenis_obat' => 'Tablet',
                'harga_satuan' => -500, // Invalid price
                'stok' => 10,
                'satuan' => 'Pcs',
            ]);

        $responseErr->assertSessionHasErrors(['nama_obat', 'harga_satuan']);

        // Test valid creation
        $responseValid = $this->actingAs($superadmin, 'web')
            ->post(route('admin.obat.store'), [
                'nama_obat' => 'Cefadroxil 500mg New',
                'jenis_obat' => 'Kapsul',
                'harga_satuan' => 8500,
                'stok' => 200,
                'satuan' => 'Kapsul',
            ]);

        $responseValid->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_obat', [
            'nama_obat' => 'Cefadroxil 500mg New',
            'harga_satuan' => 8500,
            'stok' => 200,
        ]);
    }

    public function test_can_update_medicine_data(): void
    {
        $superadmin = User::where('username', 'superadmin')->first() ?? User::factory()->create(['username' => 'superadmin', 'peran' => 'Superadmin']);

        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-8888'],
            [
                'nama_obat' => 'Lansoprazole 30mg Old',
                'jenis_obat' => 'Kapsul',
                'harga_satuan' => 12000,
                'stok' => 50,
                'satuan' => 'Kapsul',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $response = $this->actingAs($superadmin, 'web')
            ->put(route('admin.obat.update', 'OBT-8888'), [
                'nama_obat' => 'Lansoprazole 30mg Updated',
                'jenis_obat' => 'Kapsul',
                'harga_satuan' => 13500,
                'stok' => 75,
                'satuan' => 'Kapsul',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_obat', [
            'id_obat' => 'OBT-8888',
            'nama_obat' => 'Lansoprazole 30mg Updated',
            'harga_satuan' => 13500,
            'stok' => 75,
        ]);
    }

    public function test_can_delete_medicine_from_inventaris(): void
    {
        $superadmin = User::where('username', 'superadmin')->first() ?? User::factory()->create(['username' => 'superadmin', 'peran' => 'Superadmin']);

        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-7777'],
            [
                'nama_obat' => 'Obat Dihapus',
                'jenis_obat' => 'Sirup',
                'harga_satuan' => 25000,
                'stok' => 10,
                'satuan' => 'Botol',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $response = $this->actingAs($superadmin, 'web')
            ->delete(route('admin.obat.destroy', 'OBT-7777'));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('tbl_obat', [
            'id_obat' => 'OBT-7777',
        ]);
    }

    public function test_can_restock_medicine(): void
    {
        $superadmin = User::where('username', 'superadmin')->first() ?? User::factory()->create(['username' => 'superadmin', 'peran' => 'Superadmin']);

        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-6666'],
            [
                'nama_obat' => 'Loperamide 2mg',
                'jenis_obat' => 'Tablet',
                'harga_satuan' => 500,
                'stok' => 10,
                'satuan' => 'Tablet',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $response = $this->actingAs($superadmin, 'web')
            ->post(route('admin.obat.restock', 'OBT-6666'), [
                'jumlah_masuk' => 50,
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_obat', [
            'id_obat' => 'OBT-6666',
            'stok' => 60,
        ]);
    }
}
