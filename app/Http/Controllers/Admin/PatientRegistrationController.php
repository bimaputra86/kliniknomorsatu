<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientRegistrationController extends Controller
{
    /**
     * Tampilkan daftar seluruh pasien & form pendaftaran pasien baru secara manual oleh Resepsionis.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenisFilter = $request->input('jenis');

        $query = DB::table('tbl_pasien');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('id_pasien', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_bpjs', 'like', "%{$search}%");
            });
        }

        if ($jenisFilter) {
            $query->where('jenis_pasien', $jenisFilter);
        }

        $pasiens = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.patients.index', compact('pasiens', 'search', 'jenisFilter'));
    }

    /**
     * Tampilkan form registrasi pasien manual baru untuk Resepsionis.
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Simpan pendaftaran pasien manual baru oleh Resepsionis (Query Builder).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:tbl_pasien,nik'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'golongan_darah' => ['required', 'in:A,B,AB,O,-'],
            'pekerjaan' => ['required', 'string', 'max:50'],
            'alamat_lengkap' => ['required', 'string'],
            'nomor_telepon' => ['required', 'string', 'max:15'],
            'jenis_pasien' => ['required', 'in:Umum/Mandiri,BPJS'],
            'no_bpjs' => ['nullable', 'required_if:jenis_pasien,BPJS', 'string', 'max:20', 'unique:tbl_pasien,no_bpjs'],
        ], [
            'nik.required' => 'NIK wajib diisi 16 digit.',
            'nik.size' => 'NIK harus persis 16 digit angka.',
            'nik.unique' => 'NIK ini telah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap pasien wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'golongan_darah.required' => 'Pilih golongan darah.',
            'pekerjaan.required' => 'Pekerjaan pasien wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'jenis_pasien.required' => 'Pilih kategori jenis pasien (Umum/Mandiri atau BPJS).',
            'no_bpjs.required_if' => 'Nomor BPJS wajib diisi untuk pasien peserta BPJS.',
            'no_bpjs.unique' => 'Nomor BPJS ini sudah terdaftar.',
        ]);

        // Generate ID Pasien Unik (Format: PSN-YYMMDD-XXX maks 15 karakter)
        $todayStr = date('ymd');
        $seq = DB::table('tbl_pasien')->whereDate('created_at', date('Y-m-d'))->count() + 1;
        do {
            $idPasien = 'PSN-'.$todayStr.'-'.str_pad((string) $seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (DB::table('tbl_pasien')->where('id_pasien', $idPasien)->exists());

        // Tentukan Username & Password default (Set ke NIK untuk fleksibilitas maksimal)
        $username = $validated['jenis_pasien'] === 'BPJS' ? $validated['no_bpjs'] : $idPasien;
        $defaultPassword = $validated['nik'];

        // Eksekusi insert menggunakan Query Builder (DB::table)
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
            'jenis_pasien' => $validated['jenis_pasien'],
            'no_bpjs' => $validated['jenis_pasien'] === 'BPJS' ? $validated['no_bpjs'] : null,
            'username' => $username,
            'password' => Hash::make($defaultPassword),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.patients.index')->with('success', 'Pasien baru "'.$validated['nama_lengkap'].'" berhasil didaftarkan secara manual dengan ID: '.$idPasien.'. Default password login = NIK ('.$validated['nik'].').');
    }

    /**
     * Tampilkan form edit data pasien.
     */
    public function edit($id)
    {
        $pasien = DB::table('tbl_pasien')->where('id_pasien', $id)->first();

        if (! $pasien) {
            return redirect()->route('admin.patients.index')->with('error', 'Data pasien tidak ditemukan.');
        }

        return view('admin.patients.edit', compact('pasien'));
    }

    /**
     * Update data pasien (Query Builder dengan validasi server-side).
     */
    public function update(Request $request, $id)
    {
        $pasien = DB::table('tbl_pasien')->where('id_pasien', $id)->first();

        if (! $pasien) {
            return redirect()->route('admin.patients.index')->with('error', 'Data pasien tidak ditemukan.');
        }

        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:tbl_pasien,nik,'.$id.',id_pasien'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'golongan_darah' => ['required', 'in:A,B,AB,O,-'],
            'pekerjaan' => ['required', 'string', 'max:50'],
            'alamat_lengkap' => ['required', 'string'],
            'nomor_telepon' => ['required', 'string', 'max:15'],
            'jenis_pasien' => ['required', 'in:Umum/Mandiri,BPJS'],
            'no_bpjs' => ['nullable', 'required_if:jenis_pasien,BPJS', 'string', 'max:20', 'unique:tbl_pasien,no_bpjs,'.$id.',id_pasien'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nik.required' => 'NIK wajib diisi 16 digit.',
            'nik.size' => 'NIK harus persis 16 digit angka.',
            'nik.unique' => 'NIK ini telah digunakan oleh pasien lain.',
            'nama_lengkap.required' => 'Nama lengkap pasien wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'golongan_darah.required' => 'Pilih golongan darah.',
            'pekerjaan.required' => 'Pekerjaan pasien wajib diisi.',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'jenis_pasien.required' => 'Pilih kategori jenis pasien (Umum/Mandiri atau BPJS).',
            'no_bpjs.required_if' => 'Nomor BPJS wajib diisi untuk pasien peserta BPJS.',
            'no_bpjs.unique' => 'Nomor BPJS ini sudah terdaftar untuk pasien lain.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $updateData = [
            'nik' => $validated['nik'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'golongan_darah' => $validated['golongan_darah'],
            'pekerjaan' => $validated['pekerjaan'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'jenis_pasien' => $validated['jenis_pasien'],
            'no_bpjs' => $validated['jenis_pasien'] === 'BPJS' ? $validated['no_bpjs'] : null,
            'updated_at' => now(),
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('tbl_pasien')->where('id_pasien', $id)->update($updateData);

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien "'.$validated['nama_lengkap'].'" berhasil diperbarui.');
    }

    /**
     * Reset password pasien oleh Resepsionis ke 6 digit random angka.
     */
    public function resetPassword($id)
    {
        $pasien = DB::table('tbl_pasien')->where('id_pasien', $id)->first();

        if (! $pasien) {
            return redirect()->route('admin.patients.index')->with('error', 'Data pasien tidak ditemukan.');
        }

        $newPassword = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('tbl_pasien')
            ->where('id_pasien', $id)
            ->update([
                'password' => Hash::make($newPassword),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.patients.index')->with([
            'reset_pasien_success' => true,
            'reset_pasien_name' => $pasien->nama_lengkap,
            'reset_pasien_id' => $pasien->id_pasien,
            'new_pasien_password' => $newPassword,
        ]);
    }

    /**
     * Hapus data pasien (Query Builder).
     */
    public function destroy($id)
    {
        $pasien = DB::table('tbl_pasien')->where('id_pasien', $id)->first();

        if (! $pasien) {
            return redirect()->route('admin.patients.index')->with('error', 'Data pasien tidak ditemukan.');
        }

        DB::table('tbl_pasien')->where('id_pasien', $id)->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien "'.$pasien->nama_lengkap.'" berhasil dihapus dari sistem.');
    }
}
