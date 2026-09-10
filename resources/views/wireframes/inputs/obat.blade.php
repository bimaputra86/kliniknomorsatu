@extends('layouts.wireframe')

@section('title', 'Desain Input Data Obat')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT DATA OBAT (FARMASI)</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">Kode / ID Obat</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="OBT-001" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Nama Obat</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Paracetamol 500mg" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Jenis / Kategori Obat</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>Analgesik & Antipiretik</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Satuan Kemasan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Strip / Tablet" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Jumlah Stok Tersedia</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="150" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Harga Satuan (Rp)</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Rp 5.000" readonly>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Simpan Data Obat</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Data Obat digunakan oleh Apoteker untuk mengelola data inventaris persediaan obat di klinik. Form ini memiliki elemen masukan <strong>Kode / ID Obat</strong>, <strong>Nama Obat</strong>, <strong>Jenis / Kategori Obat</strong>, <strong>Satuan Kemasan</strong>, <strong>Jumlah Stok Tersedia</strong>, dan <strong>Harga Satuan (Rp)</strong>. Data obat ini akan terhubung langsung dengan pemotongan stok otomatis saat resep dilayani.
@endsection
