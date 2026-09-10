<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PemeriksaanWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_perawat_can_view_screening_page_and_input_vital_signs(): void
    {
        $perawat = User::where('peran', 'Perawat')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-NURSE-01',
            'nik' => '1122334455667700',
            'nama_lengkap' => 'Pasien Skrining Perawat',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Nurse No. 1',
            'nomor_telepon' => '081299001122',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-NURSE-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-NURSE-01',
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'U-UM-001',
            'nomor_antrean' => 1,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Dipanggil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseView = $this->actingAs($perawat, 'web')
            ->get(route('perawat.pemeriksaan.index'));

        $responseView->assertStatus(200);
        $responseView->assertSee('Skrining Tanda Vital (Perawat)');

        $responseStore = $this->actingAs($perawat, 'web')
            ->post(route('perawat.pemeriksaan.store-vital-sign'), [
                'id_antrean' => $idAntrean,
                'tekanan_darah' => '120/80',
                'suhu_tubuh' => '36.5',
                'nadi' => '80',
                'berat_badan' => '65',
                'tinggi_badan' => '170',
                'keluhan_utama' => 'Demam tinggi sejak 2 hari yang lalu dan pusing.',
            ]);

        $responseStore->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_pemeriksaan', [
            'id_antrean' => $idAntrean,
            'tekanan_darah' => '120/80',
            'suhu_tubuh' => '36.5',
            'keluhan_utama' => 'Demam tinggi sejak 2 hari yang lalu dan pusing.',
        ]);

        $this->assertDatabaseHas('tbl_antrean', [
            'id_antrean' => $idAntrean,
            'status_antrean' => 'Diperiksa',
        ]);
    }

    public function test_dokter_can_view_exam_page_and_submit_medical_record_with_eresep(): void
    {
        $dokter = User::where('peran', 'Dokter')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-DOC-01',
            'nik' => '9988776655443300',
            'nama_lengkap' => 'Pasien Exam Dokter',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'B',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Dokter No. 1',
            'nomor_telepon' => '081299883300',
            'jenis_pasien' => 'BPJS',
            'username' => 'PSN-DOC-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Obat Dummy
        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-TS-01'],
            [
                'nama_obat' => 'Paracetamol 500mg Test',
                'jenis_obat' => 'Tablet',
                'harga_satuan' => 5000,
                'stok' => 100,
                'satuan' => 'Tablet',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Insert Obat Dummy 2
        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-TS-02'],
            [
                'nama_obat' => 'Amoxicillin 500mg Test',
                'jenis_obat' => 'Tablet',
                'harga_satuan' => 4000,
                'stok' => 50,
                'satuan' => 'Tablet',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-DOC-01',
            'jenis_pasien' => 'BPJS',
            'kode_antrean' => 'B-UM-001',
            'nomor_antrean' => 1,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Diperiksa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Input Vital Sign dari perawat terlebih dahulu
        DB::table('tbl_pemeriksaan')->insert([
            'id_pemeriksaan' => 'PMK-TEST-001',
            'id_antrean' => $idAntrean,
            'tanggal_pemeriksaan' => date('Y-m-d'),
            'id_pasien' => 'PSN-DOC-01',
            'id_pengguna' => $dokter->id_pengguna ?? 'USR-001',
            'tekanan_darah' => '110/70',
            'suhu_tubuh' => '38.2',
            'keluhan_utama' => 'Demam dan batuk berdahak.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseView = $this->actingAs($dokter, 'web')
            ->get(route('dokter.pemeriksaan.create', $idAntrean));

        $responseView->assertStatus(200);
        $responseView->assertSee('E-Rekam Medis');
        $responseView->assertSee('Paracetamol 500mg Test');

        $responseStore = $this->actingAs($dokter, 'web')
            ->post(route('dokter.pemeriksaan.store'), [
                'id_antrean' => $idAntrean,
                'tekanan_darah' => '110/70',
                'suhu_tubuh' => '38.2',
                'keluhan_utama' => 'Demam dan batuk berdahak.',
                'diagnosis_penyakit' => 'J06.9 Acute upper respiratory infection',
                'tindakan_medis' => 'Edukasi istirahat cukup & minum air hangat.',
                'resep' => [
                    [
                        'id_obat' => 'OBT-TS-01',
                        'jumlah_obat' => 10,
                        'dosis_aturan_pakai' => '3 x 1 Tablet Sesudah Makan',
                    ],
                    [
                        'id_obat' => 'OBT-TS-02',
                        'jumlah_obat' => 5,
                        'dosis_aturan_pakai' => '3 x 1 Tablet Sesudah Makan',
                    ],
                ],
            ]);

        $responseStore->assertRedirect(route('dokter.pemeriksaan.index'));
        $responseStore->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_pemeriksaan', [
            'id_antrean' => $idAntrean,
            'diagnosis_penyakit' => 'J06.9 Acute upper respiratory infection',
        ]);

        $this->assertDatabaseHas('tbl_detail_resep', [
            'id_obat' => 'OBT-TS-01',
            'jumlah_obat' => 10,
            'dosis_aturan_pakai' => '3 x 1 Tablet Sesudah Makan',
        ]);

        $this->assertDatabaseHas('tbl_detail_resep', [
            'id_obat' => 'OBT-TS-02',
            'jumlah_obat' => 5,
            'dosis_aturan_pakai' => '3 x 1 Tablet Sesudah Makan',
        ]);

        // Stok obat terpotong
        $this->assertDatabaseHas('tbl_obat', [
            'id_obat' => 'OBT-TS-01',
            'stok' => 90,
        ]);
        $this->assertDatabaseHas('tbl_obat', [
            'id_obat' => 'OBT-TS-02',
            'stok' => 45,
        ]);

        // Status antrean berubah menjadi 'Selesai'
        $this->assertDatabaseHas('tbl_antrean', [
            'id_antrean' => $idAntrean,
            'status_antrean' => 'Selesai',
        ]);
    }

    public function test_dokter_edit_examination_restores_and_updates_resep_stock(): void
    {
        $dokter = User::where('peran', 'Dokter')->first() ?? User::where('username', 'superadmin')->first();

        // 1. Persiapkan pasien
        DB::table('tbl_pasien')->updateOrInsert(
            ['id_pasien' => 'PSN-DOC-01'],
            [
                'nik' => '9988776655443300',
                'nama_lengkap' => 'Pasien Exam Dokter',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Perempuan',
                'golongan_darah' => 'B',
                'pekerjaan' => 'Swasta',
                'alamat_lengkap' => 'Jl. Dokter No. 1',
                'nomor_telepon' => '081299883300',
                'jenis_pasien' => 'BPJS',
                'username' => 'PSN-DOC-01',
                'password' => Hash::make('secret123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Persiapkan obat dummy
        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-ED-01'],
            ['nama_obat' => 'Amlodipine 5mg Test', 'jenis_obat' => 'Tablet', 'harga_satuan' => 2000, 'stok' => 100, 'satuan' => 'Tablet', 'created_at' => now(), 'updated_at' => now()]
        );

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-DOC-01',
            'jenis_pasien' => 'BPJS',
            'kode_antrean' => 'B-UM-002',
            'nomor_antrean' => 2,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Diperiksa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Simpan pemeriksaan awal (10 amlodipine dirujuk)
        $response1 = $this->actingAs($dokter, 'web')
            ->post(route('dokter.pemeriksaan.store'), [
                'id_antrean' => $idAntrean,
                'keluhan_utama' => 'Hipertensi',
                'diagnosis_penyakit' => 'Essential Hypertension',
                'resep' => [
                    [
                        'id_obat' => 'OBT-ED-01',
                        'jumlah_obat' => 10,
                        'dosis_aturan_pakai' => '1 x 1 Tablet',
                    ],
                ],
            ]);

        // Pastikan stok terpotong dari 100 -> 90
        $this->assertDatabaseHas('tbl_obat', ['id_obat' => 'OBT-ED-01', 'stok' => 90]);

        // 3. Edit pemeriksaan (mengubah kuantitas obat menjadi 15)
        $response2 = $this->actingAs($dokter, 'web')
            ->post(route('dokter.pemeriksaan.store'), [
                'id_antrean' => $idAntrean,
                'keluhan_utama' => 'Hipertensi',
                'diagnosis_penyakit' => 'Essential Hypertension',
                'resep' => [
                    [
                        'id_obat' => 'OBT-ED-01',
                        'jumlah_obat' => 15, // Diubah menjadi 15
                        'dosis_aturan_pakai' => '1 x 1 Tablet',
                    ],
                ],
            ]);

        // Stok seharusnya dikembalikan (+10) lalu dikurangi (-15), jadi sisa 85 (bukan 75!)
        $this->assertDatabaseHas('tbl_obat', ['id_obat' => 'OBT-ED-01', 'stok' => 85]);
    }

    public function test_dokter_cannot_prescribe_more_than_available_medicine_stock(): void
    {
        $dokter = User::where('peran', 'Dokter')->first() ?? User::where('username', 'superadmin')->first();

        // 1. Persiapkan pasien
        DB::table('tbl_pasien')->updateOrInsert(
            ['id_pasien' => 'PSN-DOC-01'],
            [
                'nik' => '9988776655443300',
                'nama_lengkap' => 'Pasien Exam Dokter',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Perempuan',
                'golongan_darah' => 'B',
                'pekerjaan' => 'Swasta',
                'alamat_lengkap' => 'Jl. Dokter No. 1',
                'nomor_telepon' => '081299883300',
                'jenis_pasien' => 'BPJS',
                'username' => 'PSN-DOC-01',
                'password' => Hash::make('secret123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Persiapkan obat dengan stok 5
        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-LMT-01'],
            ['nama_obat' => 'Obat Stok Langka', 'jenis_obat' => 'Tablet', 'harga_satuan' => 10000, 'stok' => 5, 'satuan' => 'Tablet', 'created_at' => now(), 'updated_at' => now()]
        );

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-DOC-01',
            'jenis_pasien' => 'BPJS',
            'kode_antrean' => 'B-UM-003',
            'nomor_antrean' => 3,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Diperiksa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Dokter meminta 10 tablet (Padahal stok hanya 5)
        $response = $this->actingAs($dokter, 'web')
            ->post(route('dokter.pemeriksaan.store'), [
                'id_antrean' => $idAntrean,
                'keluhan_utama' => 'Sakit Kepala Berat',
                'diagnosis_penyakit' => 'Cephalgia',
                'resep' => [
                    [
                        'id_obat' => 'OBT-LMT-01',
                        'jumlah_obat' => 10,
                        'dosis_aturan_pakai' => '3 x 1 Tablet',
                    ],
                ],
            ]);

        // Harus gagal validasi stok
        $response->assertSessionHasErrors('resep');

        // Pastikan stok obat TIDAK berkurang dan TIDAK menjadi minus (Tetap 5)
        $this->assertDatabaseHas('tbl_obat', [
            'id_obat' => 'OBT-LMT-01',
            'stok' => 5,
        ]);
    }
}
