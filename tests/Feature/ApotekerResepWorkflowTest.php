<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApotekerResepWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_apoteker_can_view_resep_queue_page(): void
    {
        $apoteker = User::where('peran', 'Apoteker')->first() ?? User::where('username', 'superadmin')->first();

        // Direct request with active role session
        $response = $this->actingAs($apoteker, 'web')
            ->withSession(['active_role' => 'Apoteker'])
            ->get(route('apoteker.resep.index'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Resep Farmasi');
    }

    public function test_apoteker_can_fetch_resep_detail_json(): void
    {
        $apoteker = User::where('peran', 'Apoteker')->first() ?? User::where('username', 'superadmin')->first();

        // 1. Create Patient & Queue & Examination
        DB::table('tbl_pasien')->updateOrInsert(
            ['id_pasien' => 'PSN-APT-01'],
            [
                'nik' => '1122334455667788',
                'nama_lengkap' => 'Pasien Farmasi Test',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1995-05-05',
                'jenis_kelamin' => 'Laki-laki',
                'golongan_darah' => 'O',
                'pekerjaan' => 'Swasta',
                'alamat_lengkap' => 'Jl. Apotek No. 1',
                'nomor_telepon' => '081299001122',
                'jenis_pasien' => 'Umum/Mandiri',
                'username' => 'PSN-APT-01',
                'password' => Hash::make('secret123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-APT-01',
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'A-UM-001',
            'nomor_antrean' => 1,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tbl_pemeriksaan')->insert([
            'id_pemeriksaan' => 'PMK-APT-001',
            'id_antrean' => $idAntrean,
            'tanggal_pemeriksaan' => date('Y-m-d'),
            'id_pasien' => 'PSN-APT-01',
            'id_pengguna' => $apoteker->id_pengguna ?? 'USR-001',
            'tekanan_darah' => '120/80',
            'suhu_tubuh' => '36.5',
            'keluhan_utama' => 'Pusing',
            'diagnosis_penyakit' => 'Cephalgia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($apoteker, 'web')
            ->withSession(['active_role' => 'Apoteker'])
            ->get(route('apoteker.resep.show', $idAntrean));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'pemeriksaan' => [
                'nama_pasien' => 'Pasien Farmasi Test',
                'diagnosis_penyakit' => 'Cephalgia',
            ],
        ]);
    }

    public function test_apoteker_can_complete_medicine_dispensing(): void
    {
        $apoteker = User::where('peran', 'Apoteker')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->updateOrInsert(
            ['id_pasien' => 'PSN-APT-01'],
            [
                'nik' => '1122334455667788',
                'nama_lengkap' => 'Pasien Farmasi Test',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '1995-05-05',
                'jenis_kelamin' => 'Laki-laki',
                'golongan_darah' => 'O',
                'pekerjaan' => 'Swasta',
                'alamat_lengkap' => 'Jl. Apotek No. 1',
                'nomor_telepon' => '081299001122',
                'jenis_pasien' => 'Umum/Mandiri',
                'username' => 'PSN-APT-01',
                'password' => Hash::make('secret123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-APT-01',
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'A-UM-002',
            'nomor_antrean' => 2,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($apoteker, 'web')
            ->withSession(['active_role' => 'Apoteker'])
            ->post(route('apoteker.resep.diserahkan', $idAntrean));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_apoteker_can_cancel_medicine_dispensing_and_restore_stock(): void
    {
        $apoteker = User::where('peran', 'Apoteker')->first() ?? User::where('username', 'superadmin')->first();

        // 1. Create Patient
        DB::table('tbl_pasien')->updateOrInsert(
            ['id_pasien' => 'PSN-APT-02'],
            ['nik' => '9988776655443322', 'nama_lengkap' => 'Pasien Batal Obat', 'tempat_lahir' => 'Padang', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'Perempuan', 'golongan_darah' => 'A', 'pekerjaan' => 'Swasta', 'alamat_lengkap' => 'Jl. Batal No. 2', 'nomor_telepon' => '081299887766', 'jenis_pasien' => 'Umum/Mandiri', 'username' => 'PSN-APT-02', 'password' => Hash::make('secret123'), 'created_at' => now(), 'updated_at' => now()]
        );

        // 2. Create Medicine
        DB::table('tbl_obat')->updateOrInsert(
            ['id_obat' => 'OBT-BTL-01'],
            ['nama_obat' => 'Paracetamol Batal Test', 'jenis_obat' => 'Tablet', 'harga_satuan' => 5000, 'stok' => 50, 'satuan' => 'Tablet', 'created_at' => now(), 'updated_at' => now()]
        );

        // 3. Create Queue
        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-APT-02',
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'A-UM-003',
            'nomor_antrean' => 3,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Create Examination & Detail Resep (10 tablet paracetamol)
        DB::table('tbl_pemeriksaan')->insert([
            'id_pemeriksaan' => 'PMK-APT-002',
            'id_antrean' => $idAntrean,
            'tanggal_pemeriksaan' => date('Y-m-d'),
            'id_pasien' => 'PSN-APT-02',
            'id_pengguna' => $apoteker->id_pengguna ?? 'USR-001',
            'tekanan_darah' => '120/80',
            'suhu_tubuh' => '36.5',
            'keluhan_utama' => 'Demam',
            'diagnosis_penyakit' => 'Febris',
            'status_resep' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tbl_detail_resep')->insert([
            'id_pemeriksaan' => 'PMK-APT-002',
            'id_obat' => 'OBT-BTL-01',
            'jumlah_obat' => 10,
            'dosis_aturan_pakai' => '3 x 1 Tablet',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Apoteker posts batalAmbil
        $response = $this->actingAs($apoteker, 'web')
            ->withSession(['active_role' => 'Apoteker'])
            ->post(route('apoteker.resep.batal_ambil', $idAntrean));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify status_resep changed to 'Tidak Diambil'
        $this->assertDatabaseHas('tbl_pemeriksaan', [
            'id_antrean' => $idAntrean,
            'status_resep' => 'Tidak Diambil',
        ]);

        // Verify stock was restored (+10) from 50 -> 60
        $this->assertDatabaseHas('tbl_obat', [
            'id_obat' => 'OBT-BTL-01',
            'stok' => 60,
        ]);
    }
}
