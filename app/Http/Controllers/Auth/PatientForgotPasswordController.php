<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password pasien (Verifikasi NIK & Tanggal Lahir).
     */
    public function showResetForm()
    {
        return view('auth.patient-forgot-password');
    }

    /**
     * Proses reset password pasien berdasar verifikasi KTP (NIK & Tanggal Lahir).
     */
    public function processReset(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'exists:tbl_pasien,nik'],
            'tanggal_lahir' => ['required', 'date'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus persis 16 digit.',
            'nik.exists' => 'NIK ini tidak ditemukan di data rekam medis pasien kami.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Verifikasi kesesuaian NIK dan Tanggal Lahir pasien (Query Builder)
        $pasien = DB::table('tbl_pasien')
            ->where('nik', $validated['nik'])
            ->where('tanggal_lahir', $validated['tanggal_lahir'])
            ->first();

        if (! $pasien) {
            return back()->withErrors(['tanggal_lahir' => 'Kombinasi NIK dan Tanggal Lahir tidak cocok dengan rekam medis.'])->withInput();
        }

        // Update password baru ke tbl_pasien
        DB::table('tbl_pasien')
            ->where('id_pasien', $pasien->id_pasien)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        return redirect()->route('login')->with('info', 'Kata sandi akun pasien ('.$pasien->nama_lengkap.') telah berhasil diperbarui. Silakan login kembali dengan password baru Anda.');
    }
}
