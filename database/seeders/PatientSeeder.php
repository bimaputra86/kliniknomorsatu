<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 4 Data Pasien Real (2 BPJS & 2 Umum)
        $patients = [
            // Pasien BPJS 1
            [
                'id_pasien' => 'PSN-001',
                'nik' => '1234567890123456',
                'no_bpjs' => '0001234567890',
                'nama_lengkap' => 'Budi Raharjo',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1988-04-12',
                'jenis_kelamin' => 'Laki-laki',
                'golongan_darah' => 'O',
                'pekerjaan' => 'Karyawan Swasta',
                'alamat_lengkap' => 'Jl. Merdeka No. 45, Jakarta Pusat',
                'nomor_telepon' => '081234567890',
                'jenis_pasien' => 'BPJS',
                'username' => 'PSN-001',
                'password' => Hash::make('budi123'),
            ],
            // Pasien BPJS 2
            [
                'id_pasien' => 'PSN-002',
                'nik' => '2345678901234567',
                'no_bpjs' => '0002345678901',
                'nama_lengkap' => 'Siti Aminah',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1992-08-25',
                'jenis_kelamin' => 'Perempuan',
                'golongan_darah' => 'A',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'alamat_lengkap' => 'Jl. Mawar Indah No. 12, Bandung',
                'nomor_telepon' => '082345678901',
                'jenis_pasien' => 'BPJS',
                'username' => 'PSN-002',
                'password' => Hash::make('siti123'),
            ],
            // Pasien Umum 1
            [
                'id_pasien' => 'PSN-003',
                'nik' => '3456789012345678',
                'no_bpjs' => null,
                'nama_lengkap' => 'Ahmad Fauzi',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1995-11-03',
                'jenis_kelamin' => 'Laki-laki',
                'golongan_darah' => 'B',
                'pekerjaan' => 'Wiraswasta',
                'alamat_lengkap' => 'Jl. Pemuda No. 88, Surabaya',
                'nomor_telepon' => '083456789012',
                'jenis_pasien' => 'Umum/Mandiri',
                'username' => 'PSN-003',
                'password' => Hash::make('ahmad123'),
            ],
            // Pasien Umum 2
            [
                'id_pasien' => 'PSN-004',
                'nik' => '4567890123456789',
                'no_bpjs' => null,
                'nama_lengkap' => 'Dewi Lestari',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1997-02-18',
                'jenis_kelamin' => 'Perempuan',
                'golongan_darah' => 'AB',
                'pekerjaan' => 'Pegawai Negeri',
                'alamat_lengkap' => 'Jl. Malioboro No. 21, Yogyakarta',
                'nomor_telepon' => '084567890123',
                'jenis_pasien' => 'Umum/Mandiri',
                'username' => 'PSN-004',
                'password' => Hash::make('dewi123'),
            ],
        ];

        foreach ($patients as $p) {
            DB::table('tbl_pasien')->updateOrInsert(
                ['id_pasien' => $p['id_pasien']],
                array_merge($p, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // 10 Data Obat Master Klinik
        $medicines = [
            ['id_obat' => 'OBT-001', 'nama_obat' => 'Paracetamol 500mg', 'jenis_obat' => 'Tablet', 'harga_satuan' => 5000, 'stok' => 100, 'satuan' => 'Tablet'],
            ['id_obat' => 'OBT-002', 'nama_obat' => 'Amoxicillin 500mg', 'jenis_obat' => 'Kaplet', 'harga_satuan' => 8000, 'stok' => 50, 'satuan' => 'Kaplet'],
            ['id_obat' => 'OBT-003', 'nama_obat' => 'Cefadroxil 500mg', 'jenis_obat' => 'Kapsul', 'harga_satuan' => 12000, 'stok' => 40, 'satuan' => 'Kapsul'],
            ['id_obat' => 'OBT-004', 'nama_obat' => 'Amlodipine 5mg', 'jenis_obat' => 'Tablet', 'harga_satuan' => 4000, 'stok' => 80, 'satuan' => 'Tablet'],
            ['id_obat' => 'OBT-005', 'nama_obat' => 'Asam Mefenamat 500mg', 'jenis_obat' => 'Kaplet', 'harga_satuan' => 6500, 'stok' => 60, 'satuan' => 'Kaplet'],
            ['id_obat' => 'OBT-006', 'nama_obat' => 'Antasida Doen', 'jenis_obat' => 'Tablet Kunyah', 'harga_satuan' => 3500, 'stok' => 90, 'satuan' => 'Tablet'],
            ['id_obat' => 'OBT-007', 'nama_obat' => 'OBH Sirup 100ml', 'jenis_obat' => 'Sirup', 'harga_satuan' => 18000, 'stok' => 25, 'satuan' => 'Botol'],
            ['id_obat' => 'OBT-008', 'nama_obat' => 'Metformin 500mg', 'jenis_obat' => 'Tablet', 'harga_satuan' => 5500, 'stok' => 70, 'satuan' => 'Tablet'],
            ['id_obat' => 'OBT-009', 'nama_obat' => 'Cetirizine 10mg', 'jenis_obat' => 'Tablet', 'harga_satuan' => 4500, 'stok' => 85, 'satuan' => 'Tablet'],
            ['id_obat' => 'OBT-010', 'nama_obat' => 'Vitamin C 500mg', 'jenis_obat' => 'Tablet', 'harga_satuan' => 3000, 'stok' => 150, 'satuan' => 'Tablet'],
        ];

        foreach ($medicines as $m) {
            DB::table('tbl_obat')->updateOrInsert(
                ['id_obat' => $m['id_obat']],
                array_merge($m, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
