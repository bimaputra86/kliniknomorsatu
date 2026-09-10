<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeLoginController extends Controller
{
    /**
     * Tampilkan halaman login pegawai/admin.
     */
    public function showLoginForm()
    {
        return view('auth.employee-login');
    }

    /**
     * Memproses login pegawai menggunakan Query Builder & Guard 'web'.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username atau ID Pegawai wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Eksekusi pencarian pegawai menggunakan Query Builder (DB::table)
        $pegawaiData = DB::table('tbl_pengguna')
            ->where('username', $validated['username'])
            ->orWhere('id_pengguna', $validated['username'])
            ->first();

        if (! $pegawaiData || ! Hash::check($validated['password'], $pegawaiData->password)) {
            return back()->withInput($request->only('username'))->withErrors([
                'username' => 'Username/ID Pegawai atau Kata Sandi yang Anda masukkan salah.',
            ]);
        }

        // Cek status keaktifan user
        if (isset($pegawaiData->status_aktif) && $pegawaiData->status_aktif === 'Nonaktif') {
            return back()->withInput($request->only('username'))->withErrors([
                'username' => 'Akun Anda saat ini dalam status NONAKTIF. Silakan hubungi Superadmin.',
            ]);
        }

        // Ambil Eloquent User untuk login guard web
        $userModel = User::find($pegawaiData->id_pengguna);

        Auth::guard('web')->login($userModel, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/admin/dashboard')->with('success_login', 'Selamat datang kembali, '.$pegawaiData->nama_lengkap.'!');
    }

    /**
     * Memproses logout akun pegawai/admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('info', 'Anda telah keluar dari Portal Pegawai.');
    }
}
