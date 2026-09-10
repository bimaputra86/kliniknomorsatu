<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Klinik - {{ strtoupper($jenis) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #cbd5e1;
            padding: 24px;
            border-radius: 8px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #0f766e;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-logo {
            height: 60px;
            width: auto;
        }
        .header-title h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
            color: #0f766e;
            letter-spacing: -0.5px;
        }
        .header-title p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h3 {
            margin: 0;
            font-size: 15px;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }
        .report-title p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #475569;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            text-align: left;
            font-size: 10.5px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
            font-size: 9.5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 11px;
        }
        .signature-box {
            text-align: center;
            width: 220px;
        }
        .signature-space {
            height: 65px;
        }
        @media print {
            body { padding: 0; }
            .container { border: none; padding: 0; width: 100%; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="max-width: 900px; margin: 0 auto 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 18px; background-color: #0f766e; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Laporan Resmi / Simpan PDF
        </button>
    </div>

    <div class="container">
        <!-- HEADER DENGAN LOGO KLINIK -->
        <div class="header">
            <div class="header-brand">
                <img src="{{ asset('storage/img/icon.png') }}" alt="Logo Klinik" class="header-logo">
                <div class="header-title">
                    <h2>KLINIK NOMOR SATU</h2>
                    <p>Jl. Kesehatan Utama No. 1, Kota Layanan Medis • Telp: (021) 555-0123 • Email: info@kliniknomorsatu.id</p>
                </div>
            </div>
            <div style="text-align: right; font-size: 10px; color: #64748b;">
                <strong>DOKUMEN MANAJEMEN KLINIK</strong><br>
                Tgl Cetak: {{ date('d/m/Y H:i') }}
            </div>
        </div>

        <div class="report-title">
            <h3>LAPORAN EKSEKUTIF MANAJEMEN OPERASIONAL & KEUANGAN</h3>
            <p>Periode Tanggal: {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d F Y') }}</p>
        </div>

        <!-- REKAPITULASI SUMMARY RINGKASAN -->
        <div style="margin-bottom: 20px; background-color: #f8fafc; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
            <strong style="color: #0f766e; font-size: 11px; text-transform: uppercase;">Ringkasan KPI Utama Klinik:</strong>
            <table style="width: 100%; margin-top: 8px; font-size: 11px;">
                <tr>
                    <td>Total Pasien Terdaftar: <strong>{{ number_format($totalPasienTerdaftar) }} Pasien</strong></td>
                    <td>Total Kunjungan Poli: <strong>{{ number_format($totalKunjungan) }} Pasien</strong></td>
                </tr>
                <tr>
                    <td>Total Pemeriksaan Selesai: <strong>{{ number_format($totalPemeriksaanSelesai) }} Pemeriksaan</strong></td>
                    <td>Total Pendapatan Kasir: <strong>Rp {{ number_format($totalPendapatanKasir, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- 1. LAPORAN PASIEN & KUNJUNGAN -->
        @if($jenis === 'pasien' || $jenis === 'semua' || $jenis === 'ringkasan')
            <h4 style="margin: 15px 0 8px; color: #0f766e; text-transform: uppercase;">1. Laporan Data Kunjungan Pasien</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th>Tgl. Kunjungan</th>
                        <th>No. Antrean</th>
                        <th>Nama Pasien & NIK</th>
                        <th>Jenis Kelamin</th>
                        <th>Poli Tujuan</th>
                        <th>Kategori Pasien</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanPasien as $idx => $p)
                        <tr>
                            <td class="text-center font-mono">{{ $idx + 1 }}</td>
                            <td class="font-mono font-bold">{{ \Carbon\Carbon::parse($p->tanggal_antrean)->translatedFormat('d/m/Y') }}</td>
                            <td class="font-mono font-bold">{{ $p->kode_antrean ?? ('#' . $p->nomor_antrean) }}</td>
                            <td><strong>{{ $p->nama_pasien }}</strong><br><span style="font-size: 9px; color: #64748b;">NIK: {{ $p->nik }}</span></td>
                            <td>{{ $p->jenis_kelamin }}</td>
                            <td>{{ $p->nama_poli ?? 'Poli Umum' }}</td>
                            <td class="font-bold">{{ $p->jenis_pasien }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center italic">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <!-- 2. LAPORAN PEMERIKSAAN MEDIS -->
        @if($jenis === 'pemeriksaan' || $jenis === 'semua')
            <h4 style="margin: 20px 0 8px; color: #0f766e; text-transform: uppercase;">2. Laporan Hasil Pemeriksaan Medis & Vital Signs</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th>Tgl. Pemeriksaan</th>
                        <th>Pasien & Dokter</th>
                        <th>Vital Signs (TD / Suhu / Nadi)</th>
                        <th>Keluhan Utama</th>
                        <th>Diagnosa Penyakit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanPemeriksaan as $idx => $pm)
                        <tr>
                            <td class="text-center font-mono">{{ $idx + 1 }}</td>
                            <td class="font-mono font-bold">{{ \Carbon\Carbon::parse($pm->tanggal_pemeriksaan)->translatedFormat('d/m/Y') }}</td>
                            <td><strong>{{ $pm->nama_pasien }}</strong><br><span style="font-size: 9px; color: #64748b;">Dr. {{ $pm->nama_dokter }}</span></td>
                            <td class="font-mono">TD: {{ $pm->tekanan_darah ?? '-' }} | S: {{ $pm->suhu_tubuh ?? '-' }}°C | N: {{ $pm->nadi ?? '-' }}</td>
                            <td style="font-style: italic;">"{{ $pm->keluhan_utama ?? '-' }}"</td>
                            <td class="font-bold">{{ $pm->diagnosis_penyakit ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center italic">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <!-- 3. LAPORAN TOP DIAGNOSA -->
        @if($jenis === 'rekam_medis' || $jenis === 'semua')
            <h4 style="margin: 20px 0 8px; color: #0f766e; text-transform: uppercase;">3. Laporan Rekapitulasi Top 10 Diagnosa Penyakit Terbanyak</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 10%;" class="text-center">Peringkat</th>
                        <th>Nama Diagnosis Penyakit Medis</th>
                        <th style="width: 20%;" class="text-center">Jumlah Kasus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDiagnosa as $idx => $td)
                        <tr>
                            <td class="text-center font-mono font-bold">{{ $idx + 1 }}</td>
                            <td><strong>{{ $td->diagnosis_penyakit }}</strong></td>
                            <td class="text-center font-mono font-bold">{{ $td->jumlah }} Kasus</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center italic">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <!-- 4. LAPORAN OBAT -->
        @if($jenis === 'obat' || $jenis === 'semua')
            <h4 style="margin: 20px 0 8px; color: #0f766e; text-transform: uppercase;">4. Laporan Rekapitulasi Pengeluaran & Inventaris Obat</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th>ID Obat & Nama Obat</th>
                        <th class="text-center">Total Terpakai</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Total Nominal Nilai Obat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanObat as $idx => $ob)
                        <tr>
                            <td class="text-center font-mono">{{ $idx + 1 }}</td>
                            <td><strong>{{ $ob->nama_obat }}</strong> ({{ $ob->id_obat }})</td>
                            <td class="text-center font-mono font-bold">{{ $ob->total_keluar }} {{ $ob->satuan }}</td>
                            <td class="text-center font-mono">{{ $ob->sisa_stok }} {{ $ob->satuan }}</td>
                            <td class="text-right font-mono">Rp {{ number_format($ob->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right font-mono font-bold">Rp {{ number_format($ob->total_nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center italic">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <!-- 5. LAPORAN TRANSAKSI KEUANGAN KASIR -->
        @if($jenis === 'transaksi' || $jenis === 'semua')
            <h4 style="margin: 20px 0 8px; color: #0f766e; text-transform: uppercase;">5. Laporan Rekapitulasi Transaksi Keuangan Kasir</h4>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th>ID Pembayaran & Tgl</th>
                        <th>Nama Pasien (Kategori)</th>
                        <th class="text-right">Biaya Medis</th>
                        <th class="text-right">Biaya Obat</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-center">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanTransaksi as $idx => $tr)
                        <tr>
                            <td class="text-center font-mono">{{ $idx + 1 }}</td>
                            <td><strong class="font-mono">{{ $tr->id_pembayaran }}</strong><br><span style="font-size: 9px;">{{ \Carbon\Carbon::parse($tr->tanggal_pembayaran)->translatedFormat('d/m/Y H:i') }}</span></td>
                            <td><strong>{{ $tr->nama_pasien }}</strong> ({{ $tr->jenis_pasien }})</td>
                            <td class="text-right font-mono">Rp {{ number_format($tr->biaya_layanan_medis, 0, ',', '.') }}</td>
                            <td class="text-right font-mono">Rp {{ number_format($tr->biaya_obat, 0, ',', '.') }}</td>
                            <td class="text-right font-mono font-bold">Rp {{ number_format($tr->total_tagihan, 0, ',', '.') }}</td>
                            <td class="text-center font-bold">{{ $tr->metode_pembayaran ?? 'Tunai' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center italic">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <!-- FOOTER TANDA TANGAN PIMPINAN KLINIK -->
        <div class="footer">
            <div style="font-size: 10px; color: #64748b;">
                <p style="margin: 0;">* Laporan ini diterbitkan secara resmi oleh Sistem Informasi Klinik Nomor Satu.</p>
                <p style="margin: 2px 0 0;">* Berkas sah untuk pertanggungjawaban operasional dan keuangan manajemen klinik.</p>
            </div>
            <div class="signature-box">
                <p style="margin: 0; font-size: 11px;">Pimpinan / Direktur Klinik,</p>
                <div class="signature-space"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">Dr. H. Ahmad Subagyo, M.Kes</p>
                <p style="margin: 2px 0 0; font-size: 10px; color: #64748b;">Direktur Operasional Klinik</p>
            </div>
        </div>
    </div>

</body>
</html>
