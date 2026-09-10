@extends('layouts.wireframe')

@section('title', 'Desain Output Laporan Transaksi Pembayaran')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT LAPORAN TRANSAKSI PEMBAYARAN</h2>
    <p>Klinik Nomor Satu Padang | Periode: Juli 2026</p>
</div>

<div style="padding: 5px 0;">
    <table class="table-output">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 100px;">No. Transaksi</th>
                <th style="width: 70px;">Tanggal</th>
                <th>Nama Pasien</th>
                <th style="width: 80px;" class="text-right">Biaya Medis</th>
                <th style="width: 80px;" class="text-right">Biaya Obat</th>
                <th style="width: 90px;" class="text-right">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>BYR-20260731-001</td>
                <td>31-07-2026</td>
                <td>Budi Santoso</td>
                <td class="text-right">50.000</td>
                <td class="text-right">25.000</td>
                <td class="text-right">75.000</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>BYR-20260731-002</td>
                <td>31-07-2026</td>
                <td>Siti Aminah</td>
                <td class="text-right">50.000</td>
                <td class="text-right">35.000</td>
                <td class="text-right">85.000</td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><strong>TOTAL PENDAPATAN BULAN JULI 2026:</strong></td>
                <td class="text-right"><strong>Rp 160.000</strong></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px;">
        <div>
            <p>Keterangan: Laporan Keuangan Sah Klinik Nomor Satu</p>
        </div>
        <div style="text-align: center;">
            <p>Padang, 31 Juli 2026<br>Pimpinan Klinik Nomor Satu,</p>
            <br><br><br>
            <p><strong>( Dr. H. Herman, M.Kes )</strong></p>
        </div>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">🖨 Cetak Laporan (PDF)</button>
    <button type="button" class="btn btn-secondary">✖ Tutup</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Output Laporan Transaksi Pembayaran merupakan dokumen laporan keuangan harian/bulanan yang diserahkan kasir kepada Pimpinan Klinik. Keluaran ini menyajikan rincian <strong>No. Transaksi Pembayaran</strong>, <strong>Tanggal Pembayaran</strong>, <strong>Nama Pasien</strong>, <strong>Rincian Biaya Medis & Obat-obatan</strong>, <strong>Subtotal Tagihan</strong>, serta <strong>Akumulasi Total Pendapatan Klinik</strong> pada periode terkait.
@endsection
