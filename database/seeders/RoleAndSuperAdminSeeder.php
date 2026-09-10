<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat role (guard web)
        $roles = [
            'Superadmin',
            'Resepsionis',
            'Perawat',
            'Dokter',
            'Apoteker',
            'Kasir',
            'Pimpinan',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Buat akun-akun default untuk semua role operational klinik
        $defaultUsers = [
            [
                'id_pengguna' => 'ADM-001',
                'nama_lengkap' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('superadmin'),
                'peran' => 'Superadmin',
                'no_telepon_pegawai' => '081234567890',
                'status_aktif' => 'Aktif',
            ],
            [
                'id_pengguna' => 'RSP-001',
                'nama_lengkap' => 'Resepsionis Utama',
                'username' => 'resepsionis',
                'password' => Hash::make('resepsionis'),
                'peran' => 'Resepsionis',
                'no_telepon_pegawai' => '081234567891',
                'status_aktif' => 'Aktif',
            ],
            [
                'id_pengguna' => 'PRW-001',
                'nama_lengkap' => 'Perawat Klinik',
                'username' => 'perawat',
                'password' => Hash::make('perawat'),
                'peran' => 'Perawat',
                'no_telepon_pegawai' => '081234567892',
                'status_aktif' => 'Aktif',
            ],
            [
                'id_pengguna' => 'APT-001',
                'nama_lengkap' => 'Apoteker Klinik',
                'username' => 'apoteker',
                'password' => Hash::make('apoteker'),
                'peran' => 'Apoteker',
                'no_telepon_pegawai' => '081234567893',
                'status_aktif' => 'Aktif',
            ],
            [
                'id_pengguna' => 'KSR-001',
                'nama_lengkap' => 'Kasir Utama',
                'username' => 'kasir',
                'password' => Hash::make('kasir'),
                'peran' => 'Kasir',
                'no_telepon_pegawai' => '081234567894',
                'status_aktif' => 'Aktif',
            ],
        ];

        foreach ($defaultUsers as $uData) {
            DB::table('tbl_pengguna')->updateOrInsert(
                ['id_pengguna' => $uData['id_pengguna']],
                array_merge($uData, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );

            $userModel = User::find($uData['id_pengguna']);
            if ($userModel) {
                $userModel->syncRoles([$uData['peran']]);
            }
        }
    }
}
