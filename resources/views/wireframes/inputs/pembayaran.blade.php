@extends('layouts.wireframe')

@section('title', 'Desain Input Transaksi Pembayaran')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN INPUT TRANSAKSI PEMBAYARAN (KASIR)</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 10px;">
    <div class="form-group">
        <div class="form-label">No. Transaksi Pembayaran</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="BYR-20260731-001" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Pencarian ID / Nama Pasien</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="PSN-260728-001 - Budi Santoso" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Biaya Layanan Medis</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Rp 50.000" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Total Biaya Obat-obatan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Rp 25.000" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Total Tagihan Akhir</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Rp 75.000" style="font-weight: bold;" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Nominal Uang Dibayarkan</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Rp 100.000" readonly>
    </div>

    <div class="form-group">
        <div class="form-label">Uang Kembalian Pasien</div>
        <div class="form-separator">:</div>
        <input type="text" class="form-input" value="Rp 25.000" readonly>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">💾 Proses & Cetak Kuitansi</button>
    <button type="button" class="btn btn-secondary">✖ Batal</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Input Transaksi Pembayaran digunakan oleh petugas kasir untuk memproses pembayaran biaya penanganan medis dan biaya obat-obatan pasien. Form ini terdiri dari masukan <strong>No. Transaksi Pembayaran</strong>, <strong>Pencarian ID / Nama Pasien</strong>, <strong>Biaya Layanan Medis</strong>, <strong>Total Biaya Obat-obatan</strong>, <strong>Total Tagihan Akhir</strong>, <strong>Nominal Uang Dibayarkan</strong>, dan <strong>Uang Kembalian Pasien</strong>. Setelah diproses, sistem akan mengubah status transaksi menjadi lunas dan menerbitkan kuitansi digital.
@endsection
