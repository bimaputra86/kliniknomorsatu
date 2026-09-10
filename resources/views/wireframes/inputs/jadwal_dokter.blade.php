@extends('layouts.wireframe')

@section('title', 'Desain Input Jadwal Dokter')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT JADWAL PRAKTIK DOKTER</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">Pilih Dokter</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>DKT-01 - dr. Andi Pratama</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Nama Poliklinik</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Poli Umum" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Hari Praktik</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>Senin</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Jam Mulai</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="08:00 WIB" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Jam Selesai</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="12:00 WIB" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Kuota Maksimal Pasien</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="20 Orang" readonly>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Simpan Jadwal Praktik</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Jadwal Praktik Dokter digunakan oleh resepsionis atau petugas bagian pendaftaran untuk mengatur alokasi waktu operasional dokter. Form ini memiliki elemen inputan <strong>Nama Dokter</strong>, <strong>Poliklinik</strong>, <strong>Hari Praktik</strong>, <strong>Jam Mulai & Jam Selesai</strong>, serta <strong>Kuota Maksimal Pasien</strong>. Data alokasi ini digunakan sebagai acuan validasi kuota antrean online pasien.
@endsection
