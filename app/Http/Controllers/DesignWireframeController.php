<?php

namespace App\Http\Controllers;

class DesignWireframeController extends Controller
{
    /**
     * Halaman Utama Indeks Wireframe Desain Input & Output (Skripsi BAB IV)
     */
    public function index()
    {
        return view('wireframes.index');
    }

    // --- DESAIN INPUT ---
    public function inputLogin()
    {
        return view('wireframes.inputs.login');
    }

    public function inputRegistrasiPasien()
    {
        return view('wireframes.inputs.registrasi_pasien');
    }

    public function inputAmbilAntrean()
    {
        return view('wireframes.inputs.ambil_antrean');
    }

    public function inputJadwalDokter()
    {
        return view('wireframes.inputs.jadwal_dokter');
    }

    public function inputPemeriksaanAwal()
    {
        return view('wireframes.inputs.pemeriksaan_awal');
    }

    public function inputRekamMedis()
    {
        return view('wireframes.inputs.rekam_medis');
    }

    public function inputResepObat()
    {
        return view('wireframes.inputs.resep_obat');
    }

    public function inputObat()
    {
        return view('wireframes.inputs.obat');
    }

    public function inputPembayaran()
    {
        return view('wireframes.inputs.pembayaran');
    }

    // --- DESAIN OUTPUT ---
    public function outputTiketAntrean()
    {
        return view('wireframes.outputs.tiket_antrean');
    }

    public function outputKuitansi()
    {
        return view('wireframes.outputs.kuitansi');
    }

    public function outputLaporanPasien()
    {
        return view('wireframes.outputs.laporan_pasien');
    }

    public function outputLaporanTransaksi()
    {
        return view('wireframes.outputs.laporan_transaksi');
    }

    public function outputLaporanPemeriksaan()
    {
        return view('wireframes.outputs.laporan_pemeriksaan');
    }

    public function outputLaporanRekamMedis()
    {
        return view('wireframes.outputs.laporan_rekam_medis');
    }

    public function outputLaporanObat()
    {
        return view('wireframes.outputs.laporan_obat');
    }
}
