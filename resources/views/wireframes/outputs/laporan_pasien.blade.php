@extends('layouts.wireframe')

@section('title', 'Desain Output Laporan Data Pasien')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT LAPORAN DATA PASIEN</h2>
    <p>Klinik Nomor Satu Padang | Periode: Juli 2026</p>
</div>

<div style="padding: 5px 0;">
    <table class="table-output">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 100px;">ID Pasien</th>
                <th style="width: 110px;">NIK</th>
                <th>Nama Lengkap</th>
                <th style="width: 70px;" class="text-center">L/P</th>
                <th style="width: 90px;">Jenis Pasien</th>
                <th style="width: 90px;">No. Telepon</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>PSN-260728-001</td>
                <td>1371011505950001</td>
                <td>Budi Santoso</td>
                <td class="text-center">Laki-laki</td>
                <td>Umum/Mandiri</td>
                <td>081234567890</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>PSN-260728-002</td>
                <td>1371015008980002</td>
                <td>Siti Aminah</td>
                <td class="text-center">Perempuan</td>
                <td>BPJS Kesehatan</td>
                <td>089876543210</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>PSN-260728-003</td>
                <td>1371011001900003</td>
                <td>Ahmad Fauzi</td>
                <td class="text-center">Laki-laki</td>
                <td>Umum/Mandiri</td>
                <td>081122334455</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px;">
        <div>
            <p>Total Pasien Terdaftar: <strong>3 Orang</strong></p>
        </div>
        <div style="text-align: center;">
            <p>Padang, 31 Juli 2026<br>Pimpinan Klinik Nomor Satu,</p>
            <br><br><br>
            <p><strong>( Dr. H. Herman, M.Kes )</strong></p>
        </div>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">🖨 Cetak Laporan (PDF)</button>
    <button type="button" class="btn btn-secondary">✖ Tutup</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Output Laporan Data Pasien merupakan dokumen laporan eksekutif yang ditujukan bagi Pimpinan Klinik untuk memantau rekapitulasi data pendaftaran pasien berdasarkan periode waktu tertentu. Dokumen keluaran ini menampilkan tabel data yang memuat <strong>ID Pasien</strong>, <strong>NIK</strong>, <strong>Nama Lengkap Pasien</strong>, <strong>Jenis Kelamin</strong>, <strong>Kategori Kepesertaan (Umum/BPJS)</strong>, <strong>No. Telepon</strong>, serta lembar validasi tanda tangan Pimpinan Klinik.
@endsection
