<?php

namespace Tests\Feature;

use App\Models\Pasien;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ScheduleAndOnlineQueueTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resepsionis_can_view_and_add_doctor_schedule(): void
    {
        $doctor = User::where('peran', 'Dokter')->first() ?? User::factory()->create(['peran' => 'Dokter']);
        $superadmin = User::where('username', 'superadmin')->first() ?? User::factory()->create(['username' => 'superadmin', 'peran' => 'Superadmin']);
        $poli = DB::table('tbl_poli')->first();
        if (! $poli) {
            DB::table('tbl_poli')->insert(['id_poli' => 'POLI-UM', 'nama_poli' => 'Poli Umum', 'kode_poli' => 'UM', 'created_at' => now(), 'updated_at' => now()]);
            $poli = DB::table('tbl_poli')->first();
        }

        DB::table('tbl_jadwal_dokter')
            ->where('id_pengguna', $doctor->id_pengguna)
            ->where('hari', 'Minggu')
            ->delete();

        $response = $this->actingAs($superadmin, 'web')->post('/admin/schedules', [
            'id_pengguna' => $doctor->id_pengguna,
            'id_poli' => $poli->id_poli,
            'hari' => 'Minggu',
            'jam_mulai' => '10:00',
            'jam_selesai' => '14:00',
            'kuota_maksimal' => 25,
        ]);

        $response->assertRedirect('/admin/schedules');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_jadwal_dokter', [
            'id_pengguna' => $doctor->id_pengguna,
            'id_poli' => $poli->id_poli,
            'hari' => 'Minggu',
        ]);
    }

    public function test_pasien_can_take_online_queue_with_prefix_code(): void
    {
        // 1. Pasien BPJS -> Prefix Kode 'B-001'
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-BPJS-01',
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pasien BPJS Queue',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Test No. 1',
            'nomor_telepon' => '081299991111',
            'jenis_pasien' => 'BPJS',
            'no_bpjs' => '0001112223334',
            'username' => '0001112223334',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasienBPJS = Pasien::find('PSN-BPJS-01');
        $jadwal = DB::table('tbl_jadwal_dokter')->first();

        // Cari tanggal mendatangkan terdekat yang harinya cocok
        $targetDate = Carbon::today();
        for ($i = 0; $i <= 7; $i++) {
            $candidate = Carbon::today()->addDays($i);
            if (strtolower($candidate->locale('id')->isoFormat('dddd')) === strtolower($jadwal->hari)) {
                $targetDate = $candidate;
                break;
            }
        }

        $responseBPJS = $this->actingAs($pasienBPJS, 'pasien')->post('/pasien/antrean', [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal_berobat' => $targetDate->format('Y-m-d'),
        ]);

        $responseBPJS->assertRedirect('/pasien/dashboard');
        $responseBPJS->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_antrean', [
            'id_pasien' => $pasienBPJS->id_pasien,
            'jenis_pasien' => 'BPJS',
            'tanggal_antrean' => $targetDate->format('Y-m-d'),
        ]);

        // 2. Pasien Umum -> Prefix Kode 'U-xxx'
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-UMUM-01',
            'nik' => '9876543210987654',
            'nama_lengkap' => 'Pasien Umum Queue',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'Perempuan',
            'golongan_darah' => 'A',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Test No. 2',
            'nomor_telepon' => '081299992222',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-UMUM-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasienUmum = Pasien::find('PSN-UMUM-01');

        $responseUmum = $this->actingAs($pasienUmum, 'pasien')->post('/pasien/antrean', [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal_berobat' => $targetDate->format('Y-m-d'),
        ]);

        $responseUmum->assertRedirect('/pasien/dashboard');
        $responseUmum->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_antrean', [
            'id_pasien' => $pasienUmum->id_pasien,
            'jenis_pasien' => 'Umum/Mandiri',
            'tanggal_antrean' => $targetDate->format('Y-m-d'),
        ]);
    }

    public function test_pasien_can_cancel_active_queue(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-CANCEL-01',
            'nik' => '7777666655554444',
            'nama_lengkap' => 'Pasien Cancel Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Test No. 3',
            'nomor_telepon' => '081299993333',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-CANCEL-01',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasien = Pasien::find('PSN-CANCEL-01');

        $futureDate = Carbon::tomorrow()->format('Y-m-d');
        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => $pasien->id_pasien,
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'U-UM-001',
            'tanggal_antrean' => $futureDate,
            'nomor_antrean' => 1,
            'status_antrean' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($pasien, 'pasien')->post('/pasien/antrean/'.$idAntrean.'/cancel');
        $response->assertRedirect('/pasien/dashboard');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_antrean', [
            'id_antrean' => $idAntrean,
            'status_antrean' => 'Dibatalkan',
        ]);
    }

    public function test_pasien_cannot_cancel_same_day_or_called_queue(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-CANCEL-02',
            'nik' => '7777666655554499',
            'nama_lengkap' => 'Pasien Protect Cancel Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Test No. 4',
            'nomor_telepon' => '081299993399',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-CANCEL-02',
            'password' => Hash::make('secret123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasien = Pasien::find('PSN-CANCEL-02');

        // Test 1: Same day cancellation attempt
        $idSameDay = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => $pasien->id_pasien,
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'U-UM-002',
            'tanggal_antrean' => date('Y-m-d'),
            'nomor_antrean' => 2,
            'status_antrean' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseSameDay = $this->actingAs($pasien, 'pasien')->post('/pasien/antrean/'.$idSameDay.'/cancel');
        $responseSameDay->assertSessionHas('error');

        // Test 2: Called queue cancellation attempt
        $idCalled = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => $pasien->id_pasien,
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'U-UM-003',
            'tanggal_antrean' => Carbon::tomorrow()->format('Y-m-d'),
            'nomor_antrean' => 3,
            'status_antrean' => 'Dipanggil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $responseCalled = $this->actingAs($pasien, 'pasien')->post('/pasien/antrean/'.$idCalled.'/cancel');
        $responseCalled->assertSessionHas('error');
    }

    public function test_pasien_cannot_take_duplicate_queue(): void
    {
        DB::table('tbl_pasien')->insert([
            'id_pasien' => 'PSN-TEST02',
            'nik' => '9999888877776666',
            'nama_lengkap' => 'Pasien Double Queue Test',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1995-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'O',
            'pekerjaan' => 'Swasta',
            'alamat_lengkap' => 'Jl. Test No. 2',
            'nomor_telepon' => '081299992222',
            'jenis_pasien' => 'Umum/Mandiri',
            'username' => 'PSN-TEST02',
            'password' => Hash::make('PSN-TEST02'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $pasien = Pasien::find('PSN-TEST02');

        // Buat 1 antrean aktif
        DB::table('tbl_antrean')->insert([
            'id_pasien' => $pasien->id_pasien,
            'jenis_pasien' => 'Umum/Mandiri',
            'kode_antrean' => 'U-001',
            'tanggal_antrean' => date('Y-m-d'),
            'nomor_antrean' => 1,
            'status_antrean' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $jadwal = DB::table('tbl_jadwal_dokter')->first();

        // Coba pesan antrean kedua
        $response = $this->actingAs($pasien, 'pasien')->post('/pasien/antrean', [
            'id_jadwal' => $jadwal->id_jadwal,
            'tanggal_berobat' => date('Y-m-d'),
        ]);

        $response->assertSessionHas('error');
    }
}
