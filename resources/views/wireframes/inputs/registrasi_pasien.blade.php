@extends('layouts.wireframe')

@section('title', 'Desain Input Registrasi Pasien')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT REGISTRASI PASIEN</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">NIK (KTP)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="1371011505950001" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Nama Lengkap Pasien</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Budi Santoso" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Tempat & Tgl Lahir</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Padang, 15-05-1995" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Jenis Kelamin</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>Laki-laki</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Golongan Darah</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>O</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Status Kepesertaan</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>BPJS Kesehatan</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">No. Kartu BPJS</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="0001234567890" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Nomor HP / WhatsApp</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="081234567890" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Alamat Lengkap</div>
        <div class="form-separator">:</div>
        <textarea class="form-input" readonly>Jl. Khatib Sulaiman No. 45, Padang</textarea>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Simpan Data Pasien</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Registrasi Pasien digunakan untuk mencatat dan mengelola identitas rekam medis pasien baru baik melalui registrasi mandiri pasien maupun pendaftaran langsung oleh resepsionis. Form ini memuat elemen masukan <strong>NIK (KTP)</strong>, <strong>Nama Lengkap</strong>, <strong>Tempat & Tanggal Lahir</strong>, <strong>Jenis Kelamin</strong>, <strong>Golongan Darah</strong>, <strong>Status Kepesertaan (Umum/BPJS)</strong>, <strong>No. BPJS</strong>, <strong>Nomor HP</strong>, dan <strong>Alamat Lengkap</strong> yang berguna sebagai data induk pasien dalam basis data klinik.
@endsection
