<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Dashboard Laporan Operasional & Keuangan Pimpinan.
     */
    public function index(Request $request)
    {
        $tglMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tglSelesai = $request->input('tanggal_selesai', date('Y-m-d'));
        $tabActive = $request->input('tab', 'ringkasan');

        // -------------------------------------------------------------
        // 1. KAPI METRICS & SUMMARY INDIKATOR UTAMA
        // -------------------------------------------------------------
        $totalPasienTerdaftar = DB::table('tbl_pasien')
            ->whereDate('created_at', '>=', $tglMulai)
            ->whereDate('created_at', '<=', $tglSelesai)
            ->count();

        $totalKunjungan = DB::table('tbl_antrean')
            ->whereDate('tanggal_antrean', '>=', $tglMulai)
            ->whereDate('tanggal_antrean', '<=', $tglSelesai)
            ->count();

        $totalPemeriksaanSelesai = DB::table('tbl_pemeriksaan')
            ->whereDate('tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tanggal_pemeriksaan', '<=', $tglSelesai)
            ->count();

        $totalPendapatanKasir = DB::table('tbl_pembayaran')
            ->whereDate('tanggal_pembayaran', '>=', $tglMulai)
            ->whereDate('tanggal_pembayaran', '<=', $tglSelesai)
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_tagihan');

        $totalPasienBPJS = DB::table('tbl_antrean')
            ->whereDate('tanggal_antrean', '>=', $tglMulai)
            ->whereDate('tanggal_antrean', '<=', $tglSelesai)
            ->where('jenis_pasien', 'BPJS')
            ->count();

        $totalPasienUmum = DB::table('tbl_antrean')
            ->whereDate('tanggal_antrean', '>=', $tglMulai)
            ->whereDate('tanggal_antrean', '<=', $tglSelesai)
            ->where('jenis_pasien', 'Umum')
            ->count();

        // -------------------------------------------------------------
        // 2. DATA UNTUK LAPORAN 1: PASIEN & KUNJUNGAN
        // -------------------------------------------------------------
        $laporanPasien = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->whereDate('tbl_antrean.tanggal_antrean', '>=', $tglMulai)
            ->whereDate('tbl_antrean.tanggal_antrean', '<=', $tglSelesai)
            ->select(
                'tbl_antrean.*',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.jenis_kelamin',
                'tbl_pasien.tanggal_lahir',
                'tbl_poli.nama_poli'
            )
            ->orderBy('tbl_antrean.tanggal_antrean', 'desc')
            ->get();

        // -------------------------------------------------------------
        // 3. DATA UNTUK LAPORAN 2: PEMERIKSAAN & VITAL SIGNS
        // -------------------------------------------------------------
        $laporanPemeriksaan = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '<=', $tglSelesai)
            ->select(
                'tbl_pemeriksaan.*',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli'
            )
            ->orderBy('tbl_pemeriksaan.tanggal_pemeriksaan', 'desc')
            ->get();

        // -------------------------------------------------------------
        // 4. DATA UNTUK LAPORAN 3: REKAM MEDIS & TOP 10 DIAGNOSA
        // -------------------------------------------------------------
        $topDiagnosa = DB::table('tbl_pemeriksaan')
            ->whereDate('tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tanggal_pemeriksaan', '<=', $tglSelesai)
            ->whereNotNull('diagnosis_penyakit')
            ->select('diagnosis_penyakit', DB::raw('count(*) as jumlah'))
            ->groupBy('diagnosis_penyakit')
            ->orderBy('jumlah', 'desc')
            ->limit(10)
            ->get();

        // -------------------------------------------------------------
        // 5. DATA UNTUK LAPORAN 4: PEMAKAIAN & INVENTARIS OBAT
        // -------------------------------------------------------------
        $laporanObat = DB::table('tbl_detail_resep')
            ->join('tbl_pemeriksaan', 'tbl_detail_resep.id_pemeriksaan', '=', 'tbl_pemeriksaan.id_pemeriksaan')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '<=', $tglSelesai)
            ->where('tbl_pemeriksaan.status_resep', 'Diserahkan')
            ->select(
                'tbl_obat.id_obat',
                'tbl_obat.nama_obat',
                'tbl_obat.jenis_obat',
                'tbl_obat.satuan',
                'tbl_obat.stok as sisa_stok',
                'tbl_obat.harga_satuan',
                DB::raw('SUM(tbl_detail_resep.jumlah_obat) as total_keluar'),
                DB::raw('SUM(tbl_detail_resep.jumlah_obat * tbl_obat.harga_satuan) as total_nominal')
            )
            ->groupBy('tbl_obat.id_obat', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan', 'tbl_obat.stok', 'tbl_obat.harga_satuan')
            ->orderBy('total_keluar', 'desc')
            ->get();

        // -------------------------------------------------------------
        // 6. DATA UNTUK LAPORAN 5: TRANSAKSI KEUANGAN KASIR
        // -------------------------------------------------------------
        $laporanTransaksi = DB::table('tbl_pembayaran')
            ->join('tbl_pemeriksaan', 'tbl_pembayaran.id_pemeriksaan', '=', 'tbl_pemeriksaan.id_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna as kasir', 'tbl_pembayaran.id_pengguna', '=', 'kasir.id_pengguna')
            ->whereDate('tbl_pembayaran.tanggal_pembayaran', '>=', $tglMulai)
            ->whereDate('tbl_pembayaran.tanggal_pembayaran', '<=', $tglSelesai)
            ->select(
                'tbl_pembayaran.*',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'kasir.nama_lengkap as nama_kasir'
            )
            ->orderBy('tbl_pembayaran.tanggal_pembayaran', 'desc')
            ->get();

        return view('admin.laporan.index', compact(
            'tglMulai',
            'tglSelesai',
            'tabActive',
            'totalPasienTerdaftar',
            'totalKunjungan',
            'totalPemeriksaanSelesai',
            'totalPendapatanKasir',
            'totalPasienBPJS',
            'totalPasienUmum',
            'laporanPasien',
            'laporanPemeriksaan',
            'topDiagnosa',
            'laporanObat',
            'laporanTransaksi'
        ));
    }

    /**
     * Halaman Cetak Laporan Eksekutif Resmi Klinik Pimpinan.
     */
    public function print(Request $request)
    {
        $jenis = $request->input('jenis', 'semua');
        $tglMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tglSelesai = $request->input('tanggal_selesai', date('Y-m-d'));

        $totalPasienTerdaftar = DB::table('tbl_pasien')
            ->whereDate('created_at', '>=', $tglMulai)
            ->whereDate('created_at', '<=', $tglSelesai)
            ->count();

        $totalKunjungan = DB::table('tbl_antrean')
            ->whereDate('tanggal_antrean', '>=', $tglMulai)
            ->whereDate('tanggal_antrean', '<=', $tglSelesai)
            ->count();

        $totalPemeriksaanSelesai = DB::table('tbl_pemeriksaan')
            ->whereDate('tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tanggal_pemeriksaan', '<=', $tglSelesai)
            ->count();

        $totalPendapatanKasir = DB::table('tbl_pembayaran')
            ->whereDate('tanggal_pembayaran', '>=', $tglMulai)
            ->whereDate('tanggal_pembayaran', '<=', $tglSelesai)
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_tagihan');

        $laporanPasien = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->whereDate('tbl_antrean.tanggal_antrean', '>=', $tglMulai)
            ->whereDate('tbl_antrean.tanggal_antrean', '<=', $tglSelesai)
            ->select('tbl_antrean.*', 'tbl_pasien.nama_lengkap as nama_pasien', 'tbl_pasien.nik', 'tbl_pasien.no_bpjs', 'tbl_pasien.jenis_kelamin', 'tbl_poli.nama_poli')
            ->orderBy('tbl_antrean.tanggal_antrean', 'desc')
            ->get();

        $laporanPemeriksaan = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '<=', $tglSelesai)
            ->select('tbl_pemeriksaan.*', 'tbl_antrean.kode_antrean', 'tbl_antrean.nomor_antrean', 'tbl_antrean.jenis_pasien', 'tbl_pasien.nama_lengkap as nama_pasien', 'tbl_pengguna.nama_lengkap as nama_dokter', 'tbl_poli.nama_poli')
            ->orderBy('tbl_pemeriksaan.tanggal_pemeriksaan', 'desc')
            ->get();

        $topDiagnosa = DB::table('tbl_pemeriksaan')
            ->whereDate('tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tanggal_pemeriksaan', '<=', $tglSelesai)
            ->whereNotNull('diagnosis_penyakit')
            ->select('diagnosis_penyakit', DB::raw('count(*) as jumlah'))
            ->groupBy('diagnosis_penyakit')
            ->orderBy('jumlah', 'desc')
            ->limit(10)
            ->get();

        $laporanObat = DB::table('tbl_detail_resep')
            ->join('tbl_pemeriksaan', 'tbl_detail_resep.id_pemeriksaan', '=', 'tbl_pemeriksaan.id_pemeriksaan')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '>=', $tglMulai)
            ->whereDate('tbl_pemeriksaan.tanggal_pemeriksaan', '<=', $tglSelesai)
            ->where('tbl_pemeriksaan.status_resep', 'Diserahkan')
            ->select('tbl_obat.id_obat', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan', 'tbl_obat.stok as sisa_stok', 'tbl_obat.harga_satuan', DB::raw('SUM(tbl_detail_resep.jumlah_obat) as total_keluar'), DB::raw('SUM(tbl_detail_resep.jumlah_obat * tbl_obat.harga_satuan) as total_nominal'))
            ->groupBy('tbl_obat.id_obat', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan', 'tbl_obat.stok', 'tbl_obat.harga_satuan')
            ->orderBy('total_keluar', 'desc')
            ->get();

        $laporanTransaksi = DB::table('tbl_pembayaran')
            ->join('tbl_pemeriksaan', 'tbl_pembayaran.id_pemeriksaan', '=', 'tbl_pemeriksaan.id_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna as kasir', 'tbl_pembayaran.id_pengguna', '=', 'kasir.id_pengguna')
            ->whereDate('tbl_pembayaran.tanggal_pembayaran', '>=', $tglMulai)
            ->whereDate('tbl_pembayaran.tanggal_pembayaran', '<=', $tglSelesai)
            ->select('tbl_pembayaran.*', 'tbl_antrean.kode_antrean', 'tbl_antrean.nomor_antrean', 'tbl_antrean.jenis_pasien', 'tbl_pasien.nama_lengkap as nama_pasien', 'kasir.nama_lengkap as nama_kasir')
            ->orderBy('tbl_pembayaran.tanggal_pembayaran', 'desc')
            ->get();

        return view('admin.laporan.print', compact(
            'jenis',
            'tglMulai',
            'tglSelesai',
            'totalPasienTerdaftar',
            'totalKunjungan',
            'totalPemeriksaanSelesai',
            'totalPendapatanKasir',
            'laporanPasien',
            'laporanPemeriksaan',
            'topDiagnosa',
            'laporanObat',
            'laporanTransaksi'
        ));
    }
}
