<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientLoginController extends Controller
{
    /**
     * Tampilkan halaman form login pasien.
     */
    public function showLoginForm()
    {
        return view('auth.patient-login');
    }

    /**
     * Memproses login pasien menggunakan Query Builder & Guard 'pasien'.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'NIK / No. BPJS / ID Pasien / Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($validated['username']);

        // Eksekusi pencarian data pasien menggunakan Query Builder
        // Memungkinkan login via: NIK, No. BPJS, ID Pasien, atau Username
        $pasienData = DB::table('tbl_pasien')
            ->where('nik', $loginInput)
            ->orWhere('no_bpjs', $loginInput)
            ->orWhere('id_pasien', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        // Validasi keberadaan akun dan kecocokan password
        if (! $pasienData || ! Hash::check($validated['password'], $pasienData->password)) {
            return back()->withInput($request->only('username'))->withErrors([
                'username' => 'NIK / No. BPJS / ID Pasien atau Kata Sandi yang Anda masukkan tidak sesuai.',
            ]);
        }

        // Ambil instance Eloquent Model Pasien untuk login pada Guard 'pasien'
        $pasienModel = Pasien::find($pasienData->id_pasien);

        Auth::guard('pasien')->login($pasienModel, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('pasien.dashboard')->with('success', 'Selamat datang kembali, '.$pasienData->nama_lengkap.'!');
    }

    /**
     * Memproses logout akun pasien.
     */
    public function logout(Request $request)
    {
        Auth::guard('pasien')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('info', 'Anda telah berhasil keluar dari akun pasien.');
    }
}
