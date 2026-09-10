<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $pasien = Auth::guard('pasien')->user();

        // Ambil antrean aktif pasien (hanya yang berstatus Menunggu, Dipanggil, atau Diperiksa)
        $antreanAktif = DB::table('tbl_antrean')
            ->where('id_pasien', $pasien->id_pasien)
            ->whereDate('tanggal_antrean', '>=', now()->toDateString())
            ->whereIn('status_antrean', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->orderBy('id_antrean', 'desc')
            ->first();

        return view('pasien.dashboard', compact('pasien', 'antreanAktif'));
    }
}
