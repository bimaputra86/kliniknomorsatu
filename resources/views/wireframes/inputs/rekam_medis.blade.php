@extends('layouts.wireframe')

@section('title', 'Desain Input Rekam Medis Dokter')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT REKAM MEDIS DOKTER</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">No. Pemeriksaan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="PRK-20260731-001" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Nama Pasien & ID</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Budi Santoso (PSN-260728-001)" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Dokter Pemeriksa</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="dr. Andi Pratama" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Keluhan Utama Anamnesis</div>
        <div class="form-separator">:</div>
        <textarea class="form-input" readonly>Badan lemas, kepala pusing berputar, dan tenggorokan sakit saat menelan.</textarea>
    </div>

    <div class="form-group">
        <div class="form-label">Hasil Diagnosis Klinis</div>
        <div class="form-separator">:</div>
        <textarea class="form-input" readonly>ISPA (Infeksi Saluran Pernapasan Akut) + Febris.</textarea>
    </div>

    <div class="form-group">
        <div class="form-label">Tindakan Medis Ditangani</div>
        <div class="form-separator">:</div>
        <textarea class="form-input" readonly>Konsultasi dokter umum, pemeriksaan fisik THT sederhana, dan nebulizer 1x.</textarea>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Simpan Rekam Medis</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Rekam Medis Dokter digunakan oleh dokter pemeriksa untuk menginputkan hasil wawancara klinis (anamnesis), diagnosis penyakit, dan tindakan medis yang diberikan kepada pasien. Form ini terdiri dari elemen masukan <strong>No. Pemeriksaan</strong>, <strong>Nama Pasien & ID</strong>, <strong>Dokter Pemeriksa</strong>, <strong>Keluhan Utama (Anamnesis)</strong>, <strong>Hasil Diagnosis Klinis</strong>, dan <strong>Tindakan Medis</strong> yang disimpan secara permanen ke dalam basis data Rekam Medis Elektronik (RME).
@endsection
