@extends('layouts.wireframe')

@section('title', 'Desain Output Tiket Nomor Antrean Digital')

@section('wireframe_content')
<div class="header">
    <h2>RANCANGAN DESAIN OUTPUT TIKET ANTREAN DIGITAL</h2>
    <p>Klinik Nomor Satu Padang</p>
</div>

<div style="padding: 15px; text-align: center;">
    <div style="border: 2px dashed #000; padding: 20px; max-width: 400px; margin: 0 auto; background: #fff;">
        <h3 style="margin: 0; font-size: 16px; text-transform: uppercase;">KLINIK NOMOR SATU</h3>
        <p style="font-size: 10px; margin: 3px 0 15px 0;">Jl. Khatib Sulaiman No. 45, Padang | Telp: (0751) 123456</p>
        <hr style="border-top: 1px solid #000;">

        <p style="font-size: 11px; margin-top: 15px; font-weight: bold;">NOMOR ANTREAN PASIEN</p>
        <h1 style="font-size: 48px; margin: 5px 0; font-family: monospace;">A-001</h1>
        <p style="font-size: 12px; font-weight: bold; margin-bottom: 15px;">POLI UMUM</p>

        <div style="text-align: left; font-size: 11px; border-top: 1px solid #000; padding-top: 10px; margin-top: 10px;">
            <p style="margin: 3px 0;"><strong>ID Pasien:</strong> PSN-260728-001</p>
            <p style="margin: 3px 0;"><strong>Nama Pasien:</strong> Budi Santoso</p>
            <p style="margin: 3px 0;"><strong>Dokter:</strong> dr. Andi Pratama</p>
            <p style="margin: 3px 0;"><strong>Tanggal & Jam:</strong> 31-07-2026 / 08:30 WIB</p>
            <p style="margin: 3px 0;"><strong>Status Pasien:</strong> Umum / Mandiri</p>
        </div>

        <hr style="border-top: 1px dashed #000; margin-top: 15px;">
        <p style="font-size: 9px; margin-top: 10px; font-style: italic;">Simpan tiket digital ini dan harap hadir 15 menit sebelum jam pelayanan.</p>
    </div>
</div>

<div class="button-group">
    <button type="button" class="btn btn-primary">🖨 Cetak Tiket (PDF)</button>
    <button type="button" class="btn btn-secondary">✖ Tutup</button>
</div>
@endsection

@section('narasi')
Rancangan Desain Output Tiket Nomor Antrean Digital merupakan hasil keluaran yang diterbitkan sistem setelah pasien berhasil melakukan pengambilan antrean online. Dokumen cetakan ini memuat informasi <strong>Nomor Antrean Unik</strong>, <strong>Poliklinik Tujuan</strong>, <strong>ID & Nama Pasien</strong>, <strong>Dokter Spesialis Dituju</strong>, <strong>Tanggal & Jam Pelayanan</strong>, serta <strong>Status Pasien</strong> yang digunakan sebagai bukti fisik/digital saat verifikasi kehadiran di klinik.
@endsection
