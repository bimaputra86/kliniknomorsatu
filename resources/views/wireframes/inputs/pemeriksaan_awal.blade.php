@extends('layouts.wireframe')

@section('title', 'Desain Input Pemeriksaan Awal (Triage)')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT PEMERIKSAAN AWAL (TRIAGE)</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">Nomor Antrean / Pasien</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>A-001 | Budi Santoso (PSN-260728-001)</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Tekanan Darah (Sistol/Diastol)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="120/80 mmHg" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Suhu Tubuh (°C)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="37.5 °C" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Berat Badan (Kg)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="65 Kg" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Tinggi Badan (Cm)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="170 Cm" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Keluhan Awal / Catatan Perawat</div>
        <div class="form-separator">:</div>
        <textarea class="form-input" readonly>Pasien mengeluh pusing dan demam sejak kemarin.</textarea>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Simpan Data Triage</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Pemeriksaan Awal (Triage) digunakan oleh perawat untuk mencatat data fisik dasar pasien sebelum diarahkan masuk ke ruang periksa dokter. Antarmuka ini memiliki elemen masukan <strong>Nomor Antrean / Pasien</strong>, <strong>Tekanan Darah</strong>, <strong>Suhu Tubuh</strong>, <strong>Berat Badan</strong>, <strong>Tinggi Badan</strong>, serta <strong>Catatan Keluhan Awal</strong>. Data fisik ini menjadi acuan awal bagi dokter dalam melakukan diagnosis klinis.
@endsection
