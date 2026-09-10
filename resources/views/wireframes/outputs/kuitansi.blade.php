@extends('layouts.wireframe')

@section('title', 'Desain Output Kuitansi Pembayaran Digital')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT KUITANSI PEMBAYARAN</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 10px 5px;">
    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 15px;">
        <div>
            <p style="margin: 2px 0;"><strong>No. Kuitansi:</strong> KWT-20260731-001</p>
            <p style="margin: 2px 0;"><strong>ID Pasien:</strong> PSN-260728-001</p>
            <p style="margin: 2px 0;"><strong>Nama Pasien:</strong> Budi Santoso</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 2px 0;"><strong>Tanggal Transaksi:</strong> 31-07-2026</p>
            <p style="margin: 2px 0;"><strong>Kasir:</strong> Rina Melati</p>
            <p style="margin: 2px 0;"><strong>Status:</strong> LUNAS</p>
        </div>
    </div>

    <table class="table-output">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Keterangan Layanan / Nama Obat</th>
                <th style="width: 50px;" class="text-center">Qty</th>
                <th style="width: 90px;" class="text-right">Harga (Rp)</th>
                <th style="width: 100px;" class="text-right">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>Jasa Konsultasi Dokter Umum & Pemeriksaan Physical Triage</td>
                <td class="text-center">1</td>
                <td class="text-right">50.000</td>
                <td class="text-right">50.000</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>Paracetamol 500mg (Tablet)</td>
                <td class="text-center">10</td>
                <td class="text-right">500</td>
                <td class="text-right">5.000</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td>Amoxicillin 500mg (Kaplet)</td>
                <td class="text-center">10</td>
                <td class="text-right">2.000</td>
                <td class="text-right">20.000</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>TOTAL TAGIHAN:</strong></td>
                <td class="text-right"><strong>Rp 75.000</strong></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Nominal Tunai Dibayarkan:</td>
                <td class="text-right">Rp 100.000</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Uang Kembalian:</td>
                <td class="text-right">Rp 25.000</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">🖨 Cetak Kuitansi (PDF)</button>
    <button type="button" class="btn btn-secondary">✖ Tutup</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Output Kuitansi Pembayaran Digital merupakan dokumen bukti transaksi keuangan sah yang diberikan kepada pasien setelah memproses pembayaran di kasir. Keluaran ini menyajikan rincian <strong>No. Kuitansi</strong>, <strong>Identitas Pasien & Kasir</strong>, <strong>Tabel Rincian Biaya Medis & Obat-obatan</strong>, <strong>Total Tagihan Lunas</strong>, serta <strong>Nominal Pembayaran dan Uang Kembalian Pasien</strong>.
@endsection
