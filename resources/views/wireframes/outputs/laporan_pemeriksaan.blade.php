@extends('layouts.wireframe')

@section('title', 'Desain Output Laporan Hasil Pemeriksaan (Triage)')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT LAPORAN PEMERIKSAAN AWAL</h2>
    <p>Klinik Nomor Satu Padang | Periode: Juli 2026</p>
</div>

<div style="padding: 5px 0;">
    <table class="table-output">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 80px;">No. Antrean</th>
                <th style="width: 75px;">Tanggal</th>
                <th>Nama Pasien</th>
                <th style="width: 75px;" class="text-center">Tensi</th>
                <th style="width: 60px;" class="text-center">Suhu</th>
                <th style="width: 50px;" class="text-center">BB</th>
                <th>Catatan Triage Perawat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>A-001</td>
                <td>31-07-2026</td>
                <td>Budi Santoso</td>
                <td class="text-center">120/80</td>
                <td class="text-center">37.5°C</td>
                <td class="text-center">65 Kg</td>
                <td>Demam & pusing berputar sejak 2 hari</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>A-002</td>
                <td>31-07-2026</td>
                <td>Siti Aminah</td>
                <td class="text-center">110/70</td>
                <td class="text-center">36.8°C</td>
                <td class="text-center">52 Kg</td>
                <td>Batuk kering & nyeri tenggorokan</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px;">
        <div>
            <p>Penanggung Jawab Poli: <strong>Ns. Ratna, S.Kep</strong></p>
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
Rancangan Desain Output Laporan Pemeriksaan Awal (Triage) digunakan untuk menyajikan rekapitulasi data pemeriksaan fisik awal pasien yang dilakukan oleh perawat sebelum pasien diperiksa oleh dokter. Dokumen keluaran ini memuat tabel dengan kolom <strong>No. Antrean</strong>, <strong>Tanggal Pelayanan</strong>, <strong>Nama Pasien</strong>, <strong>Tekanan Darah (Tensi)</strong>, <strong>Suhu Tubuh</strong>, <strong>Berat Badan</strong>, serta <strong>Catatan Triage Perawat</strong>.
@endsection
