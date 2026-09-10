@extends('layouts.wireframe')

@section('title', 'Desain Output Laporan Rekam Medis Pasien')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT LAPORAN REKAM MEDIS</h2>
    <p>Klinik Nomor Satu Padang | Lembar Riwayat Klinis Pasien</p>
</div>

<div style="padding: 10px 5px;">
    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 15px; border-bottom: 1px solid #000; padding-bottom: 10px;">
        <div>
            <p style="margin: 2px 0;"><strong>ID Pasien:</strong> PSN-260728-001</p>
            <p style="margin: 2px 0;"><strong>Nama Pasien:</strong> Budi Santoso</p>
            <p style="margin: 2px 0;"><strong>NIK:</strong> 1371011505950001</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 2px 0;"><strong>Tgl. Lahir:</strong> 15-05-1995 (Laki-laki)</p>
            <p style="margin: 2px 0;"><strong>Gol. Darah:</strong> O</p>
            <p style="margin: 2px 0;"><strong>Kepesertaan:</strong> Umum/Mandiri</p>
        </div>
    </div>

    <table class="table-output">
        <thead>
            <tr>
                <th style="width: 75px;">No. Periksa</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 100px;">Dokter</th>
                <th>Keluhan & Diagnosis</th>
                <th>Tindakan & Resep Obat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PRK-001</td>
                <td>31-07-2026</td>
                <td>dr. Andi P.</td>
                <td><strong>Keluhan:</strong> Demam & pusing<br><strong>Diagnosis:</strong> ISPA + Febris</td>
                <td><strong>Tindakan:</strong> Nebulizer<br><strong>Resep:</strong> Paracetamol 3x1, Amoxicillin 3x1</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px;">
        <div>
            <p>Dokumen Rekam Medis Elektronik (RME) Rahasia</p>
        </div>
        <div style="text-align: center;">
            <p>Padang, 31 Juli 2026<br>Dokter Pemeriksa,</p>
            <br><br><br>
            <p><strong>( dr. Andi Pratama )</strong></p>
        </div>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">🖨 Cetak Rekam Medis (PDF)</button>
    <button type="button" class="btn btn-secondary">✖ Tutup</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Output Laporan Rekam Medis Pasien merupakan lembar riwayat medis individual pasien yang dapat dicetak oleh dokter atau pihak medis berwenang. Keluaran ini menyajikan <strong>Identitas Lengkap Pasien (ID, NIK, Golongan Darah, Kepesertaan)</strong>, <strong>No. Pemeriksaan</strong>, <strong>Tanggal Berobat</strong>, <strong>Dokter Pemeriksa</strong>, <strong>Anamnesis Keluhan & Diagnosis Klinis</strong>, serta <strong>Tindakan Medis & Resep Obat Diberikan</strong>.
@endsection
