<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Rekam Medis Pasien - {{ $pasien->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #cbd5e1;
            padding: 24px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .grid-col {
            width: 48%;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.info-table td {
            padding: 5px 8px;
            font-size: 11px;
        }
        table.info-table td.label {
            font-weight: bold;
            color: #475569;
            width: 35%;
            background-color: #f8fafc;
        }
        .box {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 16px;
            background-color: #fafafa;
        }
        .box-title {
            font-weight: bold;
            color: #0f766e;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 11px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 60px;
        }
        @media print {
            body { padding: 0; }
            .container { border: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="max-width: 800px; margin: 0 auto 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #0f766e; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
    </div>

    <div class="container">
        <!-- Kop Klinik -->
        <div class="header">
            <h2>KLINIK NOMOR SATU</h2>
            <p>Jl. Kesehatan Utama No. 1, Kota Layanan Medis • Telp: (021) 555-0123 • Email: info@kliniknomorsatu.id</p>
            <p style="font-weight: bold; color: #334155; margin-top: 8px; text-decoration: underline;">RINGKASAN REKAM MEDIS & RESEP OBAT PASIEN</p>
        </div>

        <!-- Identitas Pasien & Berobat -->
        <div class="grid">
            <div class="grid-col">
                <div class="box-title">Identitas Pasien</div>
                <table class="info-table">
                    <tr><td class="label">ID Pasien</td><td>: {{ $pasien->id_pasien }}</td></tr>
                    <tr><td class="label">Nama Lengkap</td><td>: <strong>{{ $pasien->nama_lengkap }}</strong></td></tr>
                    <tr><td class="label">NIK / No. BPJS</td><td>: {{ $pasien->nik }} / {{ $pasien->no_bpjs ?? '-' }}</td></tr>
                    <tr><td class="label">Jenis Kelamin</td><td>: {{ $pasien->jenis_kelamin }} ({{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Thn)</td></tr>
                    <tr><td class="label">Kategori Pasien</td><td>: {{ $pemeriksaan->jenis_pasien }}</td></tr>
                </table>
            </div>
            <div class="grid-col">
                <div class="box-title">Informasi Kunjungan Medis</div>
                <table class="info-table">
                    <tr><td class="label">No. Antrean</td><td>: <strong>{{ $pemeriksaan->kode_antrean ?? ('#' . $pemeriksaan->nomor_antrean) }}</strong></td></tr>
                    <tr><td class="label">Tgl. Pemeriksaan</td><td>: {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->translatedFormat('d F Y') }}</td></tr>
                    <tr><td class="label">Poliklinik</td><td>: {{ $pemeriksaan->nama_poli ?? 'Poli Umum' }}</td></tr>
                    <tr><td class="label">Dokter Bertugas</td><td>: Dr. {{ $pemeriksaan->nama_dokter }}</td></tr>
                    <tr><td class="label">Status Resep</td><td>: {{ $pemeriksaan->status_resep ?? 'Selesai' }}</td></tr>
                </table>
            </div>
        </div>

        <!-- Skrining Tanda Vital -->
        <div class="box">
            <div class="box-title">1. Hasil Skrining Tanda Vital (Perawat)</div>
            <table style="width: 100%; text-align: center; font-size: 11px;">
                <tr>
                    <td><strong>Tekanan Darah:</strong><br>{{ $pemeriksaan->tekanan_darah ? $pemeriksaan->tekanan_darah . ' mmHg' : '-' }}</td>
                    <td><strong>Suhu Tubuh:</strong><br>{{ $pemeriksaan->suhu_tubuh ? $pemeriksaan->suhu_tubuh . ' °C' : '-' }}</td>
                    <td><strong>Denyut Nadi:</strong><br>{{ $pemeriksaan->nadi ? $pemeriksaan->nadi . ' x/menit' : '-' }}</td>
                    <td><strong>Berat / Tinggi:</strong><br>{{ ($pemeriksaan->berat_badan || $pemeriksaan->tinggi_badan) ? $pemeriksaan->berat_badan . ' kg / ' . $pemeriksaan->tinggi_badan . ' cm' : '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Anamnesis & Diagnosis Dokter -->
        <div class="box">
            <div class="box-title">2. Diagnosa Medis & Catatan Dokter</div>
            <p style="margin: 4px 0 8px;"><strong>Keluhan Utama Pasien:</strong> {{ $pemeriksaan->keluhan_utama ?? '-' }}</p>
            <p style="margin: 4px 0 8px;"><strong>Diagnosis Penyakit:</strong> <span style="font-weight: bold; color: #0f766e;">{{ $pemeriksaan->diagnosis_penyakit ?? '-' }}</span></p>
            <p style="margin: 4px 0 0;"><strong>Tindakan Medis / Edukasi:</strong> {{ $pemeriksaan->tindakan_medis ?? '-' }}</p>
        </div>

        <!-- Rincian Resep Obat -->
        <div class="box">
            <div class="box-title">3. Rincian E-Resep Obat Terapi</div>
            @if(count($detailResep) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama Obat & Jenis</th>
                            <th style="width: 15%; text-align: center;">Jumlah Qty</th>
                            <th>Dosis & Aturan Pakai (Etiket)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailResep as $idx => $dr)
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td><strong>{{ $dr->nama_obat }}</strong><br><span style="font-size: 10px; color: #64748b;">{{ $dr->jenis_obat }}</span></td>
                                <td style="text-align: center; font-weight: bold;">{{ $dr->jumlah_obat }} {{ $dr->satuan }}</td>
                                <td><strong>{{ $dr->dosis_aturan_pakai }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="font-style: italic; color: #64748b; margin: 4px 0;">Terapi Non-Farmakologi (Tanpa obat resep).</p>
            @endif
        </div>

        <!-- Footer Tanda Tangan -->
        <div class="footer">
            <div>
                <p style="color: #64748b; font-size: 10px;">* Dokumen ini merupakan ringkasan medis sah yang diterbitkan oleh sistem e-Health Klinik Nomor Satu.</p>
            </div>
            <div class="signature-box">
                <p>Dokter Pemeriksa,</p>
                <div class="signature-space"></div>
                <p style="font-weight: bold; text-decoration: underline;">Dr. {{ $pemeriksaan->nama_dokter }}</p>
                <p style="font-size: 10px; color: #64748b;">SIP. {{ $pemeriksaan->nama_poli ?? 'Poliklinik' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
