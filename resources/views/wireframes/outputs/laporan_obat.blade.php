@extends('layouts.wireframe')

@section('title', 'Desain Output Laporan Data Obat & Stok')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT LAPORAN INVENTARIS OBAT</h2>
    <p>Klinik Nomor Satu Padang | Periode: Juli 2026</p>
</div>

<div style="padding: 5px 0;">
    <table class="table-output">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 80px;">Kode Obat</th>
                <th>Nama Obat</th>
                <th style="width: 110px;">Kategori</th>
                <th style="width: 60px;" class="text-center">Satuan</th>
                <th style="width: 50px;" class="text-center">Stok</th>
                <th style="width: 90px;" class="text-right">Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>OBT-001</td>
                <td>Paracetamol 500mg</td>
                <td>Analgesik & Antipiretik</td>
                <td class="text-center">Tablet</td>
                <td class="text-center">150</td>
                <td class="text-right">5.000</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>OBT-002</td>
                <td>Amoxicillin 500mg</td>
                <td>Antibiotik</td>
                <td class="text-center">Kaplet</td>
                <td class="text-center">80</td>
                <td class="text-right">20.000</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>OBT-003</td>
                <td>OBH Sirup 100ml</td>
                <td>Obat Batuk</td>
                <td class="text-center">Botol</td>
                <td class="text-center">25</td>
                <td class="text-right">25.000</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px;">
        <div>
            <p>Penanggung Jawab Farmasi: <strong>Apt. Hendra, S.Farm</strong></p>
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
Rancangan Desain Output Laporan Inventaris Data Obat merupakan dokumen laporan persediaan obat di bagian farmasi/apotek klinik. Dokumen ini memuat tabel rincian <strong>Kode Obat</strong>, <strong>Nama Obat</strong>, <strong>Kategori Obat</strong>, <strong>Satuan Kemasan</strong>, <strong>Sisa Jumlah Stok</strong>, dan <strong>Harga Satuan Obat</strong> yang berguna bagi Apoteker dan Pimpinan untuk evaluasi pengadaan obat-obatan.
@endsection
