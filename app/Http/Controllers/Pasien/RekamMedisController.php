<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    /**
     * Tampilkan daftar riwayat rekam medis & E-Resep milik pasien yang sedang login.
     */
    public function index(Request $request)
    {
        $pasien = Auth::guard('pasien')->user();
        $search = $request->input('search');

        $query = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_pemeriksaan.id_pasien', $pasien->id_pasien)
            ->select(
                'tbl_pemeriksaan.id_pemeriksaan',
                'tbl_pemeriksaan.tanggal_pemeriksaan',
                'tbl_pemeriksaan.tekanan_darah',
                'tbl_pemeriksaan.suhu_tubuh',
                'tbl_pemeriksaan.nadi',
                'tbl_pemeriksaan.berat_badan',
                'tbl_pemeriksaan.tinggi_badan',
                'tbl_pemeriksaan.keluhan_utama',
                'tbl_pemeriksaan.diagnosis_penyakit',
                'tbl_pemeriksaan.tindakan_medis',
                'tbl_pemeriksaan.status_resep',
                'tbl_antrean.id_antrean',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_pemeriksaan.diagnosis_penyakit', 'like', "%{$search}%")
                    ->orWhere('tbl_pemeriksaan.keluhan_utama', 'like', "%{$search}%")
                    ->orWhere('tbl_pengguna.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('tbl_poli.nama_poli', 'like', "%{$search}%");
            });
        }

        $rekamMedis = $query->orderBy('tbl_pemeriksaan.tanggal_pemeriksaan', 'desc')
            ->orderBy('tbl_pemeriksaan.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Sub-query rincian obat resep untuk tiap rekam medis
        foreach ($rekamMedis as $rm) {
            $rm->detail_resep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('tbl_detail_resep.id_pemeriksaan', $rm->id_pemeriksaan)
                ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.satuan', 'tbl_obat.jenis_obat')
                ->get();
        }

        return view('pasien.rekam-medis.index', compact('pasien', 'rekamMedis', 'search'));
    }

    /**
     * Ambil detail rekam medis untuk Modal AJAX Pasien.
     */
    public function show($idPemeriksaan)
    {
        $pasien = Auth::guard('pasien')->user();

        $pemeriksaan = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_pemeriksaan.id_pemeriksaan', $idPemeriksaan)
            ->where('tbl_pemeriksaan.id_pasien', $pasien->id_pasien)
            ->select(
                'tbl_pemeriksaan.*',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli'
            )
            ->first();

        if (! $pemeriksaan) {
            return response()->json(['success' => false, 'message' => 'Data rekam medis tidak ditemukan.'], 404);
        }

        $detailResep = DB::table('tbl_detail_resep')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->where('tbl_detail_resep.id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
            ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan', 'tbl_obat.harga_satuan')
            ->get();

        return response()->json([
            'success' => true,
            'pemeriksaan' => $pemeriksaan,
            'detail_resep' => $detailResep,
        ]);
    }

    /**
     * Halaman cetak ringkasan rekam medis pasien.
     */
    public function print($idPemeriksaan)
    {
        $pasien = Auth::guard('pasien')->user();

        $pemeriksaan = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_pemeriksaan.id_pemeriksaan', $idPemeriksaan)
            ->where('tbl_pemeriksaan.id_pasien', $pasien->id_pasien)
            ->select(
                'tbl_pemeriksaan.*',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli'
            )
            ->firstOrFail();

        $detailResep = DB::table('tbl_detail_resep')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->where('tbl_detail_resep.id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
            ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan')
            ->get();

        return view('pasien.rekam-medis.print', compact('pasien', 'pemeriksaan', 'detailResep'));
    }
}
