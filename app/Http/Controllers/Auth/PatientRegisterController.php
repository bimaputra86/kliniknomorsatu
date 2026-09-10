<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientRegisterController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran pasien.
     */
    public function showRegistrationForm()
    {
        return view('auth.patient-register');
    }

    /**
     * Proses pendaftaran akun pasien baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'digits:16', 'unique:tbl_pasien,nik'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'golongan_darah' => ['required', 'string', 'in:A,B,AB,O,-'],
            'pekerjaan' => ['required', 'string', 'max:50'],
            'alamat_lengkap' => ['required', 'string'],
            'nomor_telepon' => ['required', 'string', 'max:15'],
            'riwayat_alergi' => ['nullable', 'string'],
            'jenis_pasien' => ['required', 'in:Umum/Mandiri,BPJS'],
            'no_bpjs' => ['required_if:jenis_pasien,BPJS', 'nullable', 'string', 'max:25', 'unique:tbl_pasien,no_bpjs'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berisikan 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'golongan_darah.required' => 'Pilih golongan darah.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'jenis_pasien.required' => 'Pilih jenis kepesertaan pasien.',
            'no_bpjs.required_if' => 'Nomor BPJS wajib diisi untuk pasien BPJS.',
            'no_bpjs.unique' => 'Nomor BPJS ini sudah terdaftar dalam sistem.',
        ]);

        // Generate ID Pasien Unik (Format: PSN-YYMMDD-XXXX, max 15 karakter)
        do {
            $idPasien = 'PSN-'.date('ymd').'-'.str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $exists = DB::table('tbl_pasien')->where('id_pasien', $idPasien)->exists();
        } while ($exists);

        // Default password diset ke NIK untuk kemudahan login mandiri pasien
        $username = $validated['jenis_pasien'] === 'BPJS' ? $validated['no_bpjs'] : $idPasien;
        $defaultPassword = $validated['nik'];

        // Eksekusi Insert Data menggunakan Query Builder (DB::table)
        DB::table('tbl_pasien')->insert([
            'id_pasien' => $idPasien,
            'nik' => $validated['nik'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'golongan_darah' => $validated['golongan_darah'],
            'pekerjaan' => $validated['pekerjaan'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'riwayat_alergi' => $validated['riwayat_alergi'] ?? null,
            'jenis_pasien' => $validated['jenis_pasien'],
            'no_bpjs' => $validated['no_bpjs'] ?? null,
            'username' => $username,
            'password' => Hash::make($defaultPassword),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('register.pasien')->with('success_registration', [
            'id_pasien' => $idPasien,
            'nama_lengkap' => $validated['nama_lengkap'],
            'jenis_pasien' => $validated['jenis_pasien'],
            'username' => $username,
            'password' => $defaultPassword,
        ]);
    }
}
