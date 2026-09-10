@extends('layouts.wireframe')

@section('title', 'Desain Input Resep Obat Dokter')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT RESEP OBAT DIGITAL</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">No. Pemeriksaan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="PRK-20260731-001" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Pilih Obat dari Inventaris</div>
        <div class="form-separator">:</div>
        <select class="form-input" disabled><option>OBT-01 - Paracetamol 500mg (Tablet)</option></select>
    </div>

    <div class="form-group">
        <div class="form-label">Jumlah Diresepkan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="10 Tablet" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Dosis & Aturan Pakai</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="3 x 1 Tablet Sehari Setelah Makan" readonly>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Terbitkan Resep ke Farmasi</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Resep Obat Digital digunakan oleh dokter untuk meresepkan obat secara elektronik kepada bagian farmasi/apotek tanpa menggunakan kertas. Form ini memuat elemen masukan <strong>No. Pemeriksaan</strong>, <strong>Pilihan Obat dari Inventaris</strong>, <strong>Jumlah Diresepkan</strong>, dan <strong>Dosis & Aturan Pakai</strong>. Saat diterbitkan, sistem akan secara otomatis mengirimkan notifikasi resep kepada Apoteker.
@endsection
