<?php

namespace Tests\Feature;

use App\Models\Pasien;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AntreanKunjunganTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resepsionis_can_view_antrean_kunjungan_page_with_filters(): void
    {
        $resepsionis = User::where('peran', 'Resepsionis')->first() ?? User::where('username', 'superadmin')->first();

        $response = $this->actingAs($resepsionis, 'web')
            ->withSession(['active_role' => 'Resepsionis'])
            ->get('/admin/antrean-kunjungan?tanggal='.date('Y-m-d'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Nomor Antrean Kunjungan');
    }

    public function test_resepsionis_can_search_patients_via_ajax(): void
    {
        $resepsionis = User::where('peran', 'Resepsionis')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-SEARCH-01',
            'nik' => '5544332211009988',
            'nama_lengkap' => 'Budi Searchable Pasien',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1992-02-02',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Search No. 1',
            'nomor_telepon' => '081233445566',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-SEARCH-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($resepsionis, 'web')
            ->get('/admin/antrean-kunjungan/search-patients?q=Budi');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id_pasien' => 'PSN-SEARCH-01',
            'nama_lengkap' => 'Budi Searchable Pasien',
        ]);
    }

    public function test_resepsionis_can_issue_onsite_queue_ticket(): void
    {
        $resepsionis = User::where('peran', 'Resepsionis')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-ONSITE-01',
            'nik' => '1122334455667788',
            'nama_lengkap' => 'Pasien Onsite Walkin',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'B',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Onsite No. 1',
            'nomor_telepon' => '081299887766',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-ONSITE-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasien = Pasien::find('PSN-ONSITE-01');
        $jadwal = DB::table('tbl_jadwal_dokter')->first();

        // Cari tanggal terdekat yang harinya sesuai dengan $jadwal->hari
        $targetDate = Carbon::today();
        for ($i = 0; $i <= 7; $i++) {
            $candidate = Carbon::today()->addDays($i);
            if (strtolower($candidate->locale('id')->isoFormat('dddd')) === strtolower($jadwal->hari)) {
                $targetDate = $candidate;
                break;
            }
        }

        $response = $this->actingAs($resepsionis, 'web')
            ->withSession(['active_role' => 'Resepsionis'])
            ->post('/admin/antrean-kunjungan', [
                'id_pasien' => $pasien->id_pasien,
                'id_jadwal' => $jadwal->id_jadwal,
                'tanggal_berobat' => $targetDate->format('Y-m-d'),
            ]);

        $response->assertRedirect('/admin/antrean-kunjungan');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_antrean', [
            'id_pasien' => $pasien->id_pasien,
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal_antrean' => $targetDate->format('Y-m-d'),
            'status_antrean' => 'Menunggu',
        ]);
    }

    public function test_resepsionis_can_update_antrean_status(): void
    {
        $resepsionis = User::where('peran', 'Resepsionis')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-UPDATE-01',
            'nik' => '9988776655441122',
            'nama_lengkap' => 'Pasien Update Status',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Update No. 1',
            'nomor_telepon' => '081299881122',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-UPDATE-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasien = Pasien::find('PSN-UPDATE-01');

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => $pasien->id_pasien,
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'U-099',
            'nomor_antrean' => 99,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($resepsionis, 'web')
            ->withSession(['active_role' => 'Resepsionis'])
            ->put('/admin/antrean-kunjungan/'.$idAntrean.'/status', [
                'status_antrean' => 'Dipanggil',
            ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_antrean', [
            'id_antrean' => $idAntrean,
            'status_antrean' => 'Dipanggil',
        ]);
    }

    public function test_resepsionis_can_call_antrean_with_audio_response(): void
    {
        $resepsionis = User::where('peran', 'Resepsionis')->first() ?? User::where('username', 'superadmin')->first();

        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-CALL-01',
            'nik' => '9988776655443322',
            'nama_lengkap' => 'Pasien Call Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Call No. 1',
            'nomor_telepon' => '081299883322',
            'jenis_pasien' => 'BPJS',
            'username' => 'PSN-CALL-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => 'PSN-CALL-01',
            'jenis_pasien' => 'BPJS',
            'kode_antrean' => 'B-UM-001',
            'nomor_antrean' => 1,
            'tanggal_antrean' => date('Y-m-d'),
            'status_antrean' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($resepsionis, 'web')
            ->post(route('admin.antrean-kunjungan.panggil', $idAntrean));

        $response->assertSessionHas('success');
        $response->assertSessionHas('audio_panggil');

        $this->assertDatabaseHas('tbl_antrean', [
            'id_antrean' => $idAntrean,
            'status_antrean' => 'Dipanggil',
        ]);
    }

    public function test_display_tv_page_and_json_endpoint(): void
    {
        $resepsionis = User::where('peran', 'Resepsionis')->first() ?? User::where('username', 'superadmin')->first();

        // Check HTML View
        $responseView = $this->actingAs($resepsionis, 'web')
            ->get(route('admin.antrean-kunjungan.display'));

        $responseView->assertStatus(200);
        $responseView->assertSee('KLINIK NOMOR SATU');
        $responseView->assertSee('Panggilan Antrean Utama');

        // Check JSON Data Endpoint
        $responseData = $this->actingAs($resepsionis, 'web')
            ->get(route('admin.antrean-kunjungan.display-data'));

        $responseData->assertStatus(200);
        $responseData->assertJsonStructure([
            'status',
            'timestamp',
            'sedang_dipanggil',
            'poli_summary',
        ]);
    }
}
