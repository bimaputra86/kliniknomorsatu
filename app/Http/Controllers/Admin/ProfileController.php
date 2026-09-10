<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman pengaturan profil & ganti password.
     */
    public function edit()
    {
        $user = Auth::guard('web')->user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update data profil pegawai (Query Builder).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:tbl_pengguna,username,'.$user->id_pengguna.',id_pengguna'],
            'no_telepon_pegawai' => ['required', 'string', 'max:15'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini telah digunakan.',
            'no_telepon_pegawai.required' => 'Nomor telepon wajib diisi.',
        ]);

        // Update ke database menggunakan Query Builder (DB::table)
        DB::table('tbl_pengguna')
            ->where('id_pengguna', $user->id_pengguna)
            ->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
                'no_telepon_pegawai' => $validated['no_telepon_pegawai'],
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Profil Anda telah berhasil diperbarui secara otomatis!');
    }

    /**
     * Update kata sandi pegawai dengan validasi 3 inputan (Password Lama, Baru, Konfirmasi).
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Cek apakah password lama sesuai
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.']);
        }

        // Update password menggunakan Query Builder (DB::table)
        DB::table('tbl_pengguna')
            ->where('id_pengguna', $user->id_pengguna)
            ->update([
                'password' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Kata sandi Anda berhasil diubah!');
    }
}
