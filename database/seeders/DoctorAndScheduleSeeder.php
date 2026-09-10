<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DoctorAndScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Data Master Poli ke tbl_poli
        $polis = [
            [
                'id_poli' => 'POL-001',
                'kode_poli' => 'PDL',
                'nama_poli' => 'Poli Penyakit Dalam',
                'deskripsi' => 'Layanan diagnosa & penanganan masalah organ dalam dewasa.',
            ],
            [
                'id_poli' => 'POL-002',
                'kode_poli' => 'ANK',
                'nama_poli' => 'Poli Anak',
                'deskripsi' => 'Layanan kesehatan & tumbuh kembang anak-anak dan bayi.',
            ],
            [
                'id_poli' => 'POL-003',
                'kode_poli' => 'GGI',
                'nama_poli' => 'Poli Gigi',
                'deskripsi' => 'Layanan perawatan kesehatan gigi & mulut.',
            ],
            [
                'id_poli' => 'POL-004',
                'kode_poli' => 'UMM',
                'nama_poli' => 'Poli Umum',
                'deskripsi' => 'Layanan pemeriksaan kesehatan umum bagi seluruh pasien.',
            ],
        ];

        foreach ($polis as $p) {
            DB::table('tbl_poli')->updateOrInsert(
                ['id_poli' => $p['id_poli']],
                [
                    'kode_poli' => $p['kode_poli'],
                    'nama_poli' => $p['nama_poli'],
                    'deskripsi' => $p['deskripsi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Role Dokter Spatie
        Role::firstOrCreate(['name' => 'Dokter', 'guard_name' => 'web']);

        // Data 4 Dokter beserta Jadwal Sesuai Permintaan User:
        // - Poli Anak & Poli Gigi: Senin, Selasa, Rabu
        // - Poli Penyakit Dalam & Poli Umum: Kamis, Jumat, Sabtu
        $doctors = [
            [
                'id_pengguna' => 'DKT-001',
                'nama_lengkap' => 'dr. Ratna Sari, Sp.A',
                'username' => 'drratna',
                'password' => Hash::make('dokter123'),
                'peran' => 'Dokter',
                'no_telepon_pegawai' => '081234567001',
                'id_poli' => 'POL-002',
                'poli' => 'Poli Anak',
                'schedules' => [
                    ['hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 20],
                    ['hari' => 'Selasa', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 20],
                    ['hari' => 'Rabu', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 20],
                ],
            ],
            [
                'id_pengguna' => 'DKT-002',
                'nama_lengkap' => 'dr. Hendra Wijaya, Sp.KG',
                'username' => 'drhendra',
                'password' => Hash::make('dokter123'),
                'peran' => 'Dokter',
                'no_telepon_pegawai' => '081234567002',
                'id_poli' => 'POL-003',
                'poli' => 'Poli Gigi',
                'schedules' => [
                    ['hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 15],
                    ['hari' => 'Selasa', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 15],
                    ['hari' => 'Rabu', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 15],
                ],
            ],
            [
                'id_pengguna' => 'DKT-003',
                'nama_lengkap' => 'dr. Andi Pratama, Sp.PD',
                'username' => 'drandi',
                'password' => Hash::make('dokter123'),
                'peran' => 'Dokter',
                'no_telepon_pegawai' => '081234567003',
                'id_poli' => 'POL-001',
                'poli' => 'Poli Penyakit Dalam',
                'schedules' => [
                    ['hari' => 'Kamis', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 20],
                    ['hari' => 'Jumat', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 20],
                    ['hari' => 'Sabtu', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 20],
                ],
            ],
            [
                'id_pengguna' => 'DKT-004',
                'nama_lengkap' => 'dr. Budi Santoso',
                'username' => 'drbudi',
                'password' => Hash::make('dokter123'),
                'peran' => 'Dokter',
                'no_telepon_pegawai' => '081234567004',
                'id_poli' => 'POL-004',
                'poli' => 'Poli Umum',
                'schedules' => [
                    ['hari' => 'Kamis', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 25],
                    ['hari' => 'Jumat', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 25],
                    ['hari' => 'Sabtu', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'kuota_maksimal' => 25],
                ],
            ],
        ];

        foreach ($doctors as $docData) {
            DB::table('tbl_pengguna')->updateOrInsert(
                ['id_pengguna' => $docData['id_pengguna']],
                [
                    'nama_lengkap' => $docData['nama_lengkap'],
                    'username' => $docData['username'],
                    'password' => $docData['password'],
                    'peran' => $docData['peran'],
                    'no_telepon_pegawai' => $docData['no_telepon_pegawai'],
                    'status_aktif' => 'Aktif',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $userDoc = User::find($docData['id_pengguna']);
            if ($userDoc) {
                $userDoc->syncRoles(['Dokter']);
            }

            foreach ($docData['schedules'] as $sched) {
                DB::table('tbl_jadwal_dokter')->updateOrInsert(
                    [
                        'id_pengguna' => $docData['id_pengguna'],
                        'id_poli' => $docData['id_poli'],
                        'hari' => $sched['hari'],
                    ],
                    [
                        'nama_poli' => $docData['poli'],
                        'jam_mulai' => $sched['jam_mulai'],
                        'jam_selesai' => $sched['jam_selesai'],
                        'kuota_maksimal' => $sched['kuota_maksimal'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
