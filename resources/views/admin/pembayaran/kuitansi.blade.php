<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Pembayaran {{ $pembayaran->id_pembayaran }} - {{ $pembayaran->nama_pasien }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .kuitansi-card {
            max-width: 750px;
            margin: 0 auto;
            border: 1px solid #cbd5e1;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #059669;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-logo {
            height: 54px;
            width: auto;
        }
        .header-title h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
            color: #065f46;
            letter-spacing: -0.5px;
        }
        .header-title p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .receipt-badge {
            text-align: right;
        }
        .receipt-badge h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }
        .receipt-badge p {
            margin: 3px 0 0;
            font-family: monospace;
            font-size: 12px;
            color: #059669;
            font-weight: bold;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
            background-color: #f8fafc;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
        }
        .info-col {
            width: 48%;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 11px;
        }
        .info-label {
            color: #64748b;
            font-weight: 600;
        }
        .info-val {
            font-weight: 800;
            color: #0f172a;
        }
        table.table-charges {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.table-charges th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
        }
        table.table-charges td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
        .totals-table {
            width: 320px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 12px;
            font-size: 11px;
        }
        .totals-table tr.grand-total {
            background-color: #ecfdf5;
            border-top: 2px solid #059669;
            border-bottom: 2px solid #059669;
        }
        .totals-table tr.grand-total td {
            font-size: 13px;
            font-weight: 900;
            color: #065f46;
            padding: 10px 12px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            pt-4;
            border-top: 1px dashed #cbd5e1;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-space {
            height: 50px;
        }

        @media print {
            body { padding: 0; background-color: #fff; }
            .kuitansi-card { border: none; shadow: none; padding: 0; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="max-width: 750px; margin: 0 auto 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 18px; background-color: #059669; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Kuitansi / Simpan PDF
        </button>
    </div>

    <div class="kuitansi-card">
        <!-- HEADER DENGAN LOGO KLINIK -->
        <div class="header">
            <div class="header-brand">
                <img src="{{ asset('storage/img/icon.png') }}" alt="Logo Klinik" class="header-logo">
                <div class="header-title">
                    <h2>KLINIK NOMOR SATU</h2>
                    <p>Jl. Kesehatan Utama No. 1, Kota Layanan Medis • Telp: (021) 555-0123</p>
                </div>
            </div>
            <div class="receipt-badge">
                <h3>{{ ($pembayaran->jenis_pasien === 'BPJS' || $pembayaran->metode_pembayaran === 'BPJS') ? 'FAKTUR PELAYANAN BPJS' : 'KUITANSI PEMBAYARAN' }}</h3>
                <p>{{ $pembayaran->id_pembayaran }}</p>
            </div>
        </div>

        <!-- INFO PASIEN & TRANSAKSI -->
        <div class="info-grid">
            <div class="info-col">
                <div class="info-row">
                    <span class="info-label">Nama Pasien:</span>
                    <span class="info-val">{{ $pembayaran->nama_pasien }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIK / BPJS:</span>
                    <span class="info-val">{{ $pembayaran->nik }} {{ $pembayaran->no_bpjs ? '('.$pembayaran->no_bpjs.')' : '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kategori Pasien:</span>
                    <span class="info-val">{{ $pembayaran->jenis_pasien }}</span>
                </div>
            </div>
            <div class="info-col">
                <div class="info-row">
                    <span class="info-label">Tgl. Transaksi:</span>
                    <span class="info-val">{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->translatedFormat('d F Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Antrean:</span>
                    <span class="info-val font-mono">{{ $pembayaran->kode_antrean ?? ('#'.$pembayaran->nomor_antrean) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Poli & Dokter:</span>
                    <span class="info-val">{{ $pembayaran->nama_poli ?? 'Poli Umum' }} • Dr. {{ $pembayaran->nama_dokter }}</span>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN BIAYA -->
        <table class="table-charges">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th>Rincian Layanan / Komponen Biaya</th>
                    <th style="width: 15%;" class="text-center">Qty</th>
                    <th style="width: 25%;" class="text-right">Biaya (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1: Biaya Layanan Medis -->
                <tr>
                    <td class="text-center font-mono">1</td>
                    <td>
                        <strong style="color: #0f172a;">Jasa Konsultasi & Layanan Medis Poli</strong>
                        <div style="font-size: 10px; color: #64748b;">Pemeriksaan kesehatan & tindakan medis poli</div>
                    </td>
                    <td class="text-center font-mono font-bold">1 Paket</td>
                    <td class="text-right font-mono font-bold">Rp {{ number_format($pembayaran->biaya_layanan_medis, 0, ',', '.') }}</td>
                </tr>

                <!-- Row 2: Biaya Obat Resep (Jika Ada) -->
                @if(count($detailResep) > 0)
                    @foreach($detailResep as $idx => $dr)
                        <tr>
                            <td class="text-center font-mono">{{ $idx + 2 }}</td>
                            <td>
                                <strong>Obat: {{ $dr->nama_obat }}</strong>
                                <div style="font-size: 10px; color: #64748b;">Harga Satuan: Rp {{ number_format($dr->harga_satuan, 0, ',', '.') }} / {{ $dr->satuan }}</div>
                            </td>
                            <td class="text-center font-mono font-bold">{{ $dr->jumlah_obat }} {{ $dr->satuan }}</td>
                            <td class="text-right font-mono font-bold">Rp {{ number_format($dr->jumlah_obat * $dr->harga_satuan, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center font-mono">2</td>
                        <td>
                            <strong>Biaya Obat-Obatan Resep Farmasi</strong>
                            <div style="font-size: 10px; color: #64748b;">Terapi Non-Farmakologi (0 Obat)</div>
                        </td>
                        <td class="text-center font-mono font-bold">0</td>
                        <td class="text-right font-mono font-bold">Rp 0</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- TOTALS SECTION -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="info-label">Subtotal Medis:</td>
                    <td class="text-right font-mono font-bold">Rp {{ number_format($pembayaran->biaya_layanan_medis, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Subtotal Obat:</td>
                    <td class="text-right font-mono font-bold">Rp {{ number_format($pembayaran->biaya_obat, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total">
                    <td>TOTAL DIBAYAR:</td>
                    <td class="text-right font-mono">
                        @if($pembayaran->jenis_pasien === 'BPJS' || $pembayaran->metode_pembayaran === 'BPJS')
                            Rp 0 <span style="font-size: 9px; font-weight: normal; display: block; color: #0284c7;">(Covered BPJS - Bebas Biaya)</span>
                        @else
                            Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
                @if($pembayaran->jenis_pasien !== 'BPJS' && $pembayaran->metode_pembayaran !== 'BPJS')
                    <tr>
                        <td class="info-label">Metode Pembayaran:</td>
                        <td class="text-right font-bold" style="color: #059669;">{{ $pembayaran->metode_pembayaran }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Nominal Uang Diterima:</td>
                        <td class="text-right font-mono font-bold">Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Kembalian:</td>
                        <td class="text-right font-mono font-bold">Rp {{ number_format($pembayaran->kembalian, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <!-- FOOTER TANDA TANGAN -->
        <div class="footer">
            <div style="font-size: 10px; color: #64748b; max-width: 400px; line-height: 1.4;">
                <p style="margin: 0; font-weight: bold; color: #0f172a;">* Bawa kuitansi / faktur ini ke bagian Farmasi/Apoteker untuk pengambilan obat.</p>
                <p style="margin: 3px 0 0;">* Dokumen ini merupakan bukti transaksi sah yang diterbitkan resmi oleh Klinik Nomor Satu.</p>
                <p style="margin: 3px 0 0;">* Terima kasih atas kepercayaan Anda berobat di Klinik Nomor Satu.</p>
            </div>
            <div class="signature-box">
                <p style="margin: 0; font-size: 11px;">Kasir / Petugas Finance,</p>
                <div class="signature-space"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $pembayaran->nama_kasir ?? 'Petugas Kasir' }}</p>
                <p style="margin: 2px 0 0; font-size: 10px; color: #64748b;">Klinik Nomor Satu</p>
            </div>
        </div>
    </div>

</body>
</html>
