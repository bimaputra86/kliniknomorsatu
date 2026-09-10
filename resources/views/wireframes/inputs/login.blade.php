@extends('layouts.wireframe')

@section('title', 'Desain Input Form Login')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT FORM LOGIN</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 20px;">
    <div class="form-group">
        <div class="form-label">Nama Pengguna (Username)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="superadmin" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Kata Sandi (Password)</div>
        <div class="form-separator">:</div>
        <input type="password" class="form-input" value="********" readonly>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Masuk (Login)</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Form Login digunakan sebagai antarmuka autentikasi hak akses bagi pengguna (Pasien, Resepsionis, Perawat, Dokter, Apoteker, Kasir, dan Pimpinan) sebelum masuk ke dalam sistem. Antarmuka ini terdiri dari elemen inputan <strong>Nama Pengguna (Username)</strong> dan <strong>Kata Sandi (Password)</strong> yang berfungsi memverifikasi kredensial pengguna guna menjamin keamanan serta pemisahan hak akses data sesuai dengan peran masing-masing pengguna.
@endsection
