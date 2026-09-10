<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil lengkap pasien.
     */
    public function show()
    {
        $pasien = Auth::guard('pasien')->user();

        return view('pasien.profile.show', compact('pasien'));
    }

    /**
     * Tampilkan form ganti password mandiri pasien.
     */
    public function editPassword()
    {
        $pasien = Auth::guard('pasien')->user();

        return view('pasien.profile.password', compact('pasien'));
    }

    /**
     * Update password mandiri pasien (3 Inputan: Password Lama, Baru, Konfirmasi).
     */
    public function updatePassword(Request $request)
    {
        $pasien = Auth::guard('pasien')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Verifikasi kata sandi lama pasien
        if (! Hash::check($validated['current_password'], $pasien->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.'])->withInput();
        }

        // Update password baru ke tbl_pasien (Query Builder)
        DB::table('tbl_pasien')
            ->where('id_pasien', $pasien->id_pasien)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        return redirect()->route('pasien.dashboard')->with('success', 'Kata sandi Anda berhasil diperbarui.');
    }
}
