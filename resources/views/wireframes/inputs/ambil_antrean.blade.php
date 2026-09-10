@extends('layouts.wireframe')

@section('title', 'Desain Input Ambil Antrean Online')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT AMBIL ANTREAN ONLINE</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">ID / Nama Pasien</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="PSN-260728-001 - Budi Santoso" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Pilih Poliklinik</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>Poli Umum</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Pilih Dokter Spesialis</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>dr. Andi Pratama (Poli Umum)</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Tanggal Kunjungan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="31-07-2026" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Keluhan Singkat / Alasan</div>
        <div class="form-separator">:</div>
        <textarea class="form-input" readonly>Demam dan batuk sejak 2 hari yang lalu.</textarea>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Ambil Nomor Antrean</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Ambil Antrean Online berfungsi memfasilitasi pasien dalam mendaftarkan nomor urut kunjungan secara daring sebelum hadir di lokasi klinik. Antarmuka ini memuat elemen masukan <strong>ID / Nama Pasien</strong>, <strong>Pilihan Poliklinik</strong>, <strong>Pilihan Dokter Spesialis & Jadwal</strong>, <strong>Tanggal Kunjungan</strong>, serta <strong>Keluhan Singkat</strong>. Sistem akan mengecek ketersediaan kuota harian dokter sebelum menerbitkan nomor antrean digital.
@endsection
