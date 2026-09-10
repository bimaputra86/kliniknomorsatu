@extends('layouts.admin')

@section('title', 'Dashboard Laporan Operasional & Keuangan - Pimpinan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Laporan Eksekutif Pimpinan</h1>
            <p class="text-xs text-slate-500 mt-1">Rekapitulasi data operasional pelayanan medis, rekam medis, inventaris obat, dan transaksi keuangan klinik.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.laporan.print', ['jenis' => $tabActive, 'tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai]) }}" target="_blank" class="px-4 py-2.5 bg-cyan-700 hover:bg-cyan-800 text-white font-extrabold rounded-2xl text-xs shadow-md shadow-cyan-700/20 transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan {{ strtoupper($tabActive) }}</span>
            </a>
        </div>
    </div>

    <!-- Filter Periode Laporan -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            <input type="hidden" name="tab" value="{{ $tabActive }}">
            
            <div class="w-full sm:w-44">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $tglMulai }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-cyan-600 transition-all">
            </div>

            <div class="w-full sm:w-44">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $tglSelesai }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-cyan-600 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.laporan.index', ['tanggal_mulai' => date('Y-m-01'), 'tanggal_selesai' => date('Y-m-d'), 'tab' => $tabActive]) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition-all">
                    Bulan Ini
                </a>
            </div>
        </form>
    </div>

    <!-- 4 KPI Metrics Indicator Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Kunjungan Pasien -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Kunjungan Pasien</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($totalKunjungan) }}</div>
            <div class="text-[11px] text-slate-500 font-semibold flex items-center gap-2">
                <span class="text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded font-bold">BPJS: {{ $totalPasienBPJS }}</span>
                <span>•</span>
                <span class="text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded font-bold">Umum: {{ $totalPasienUmum }}</span>
            </div>
        </div>

        <!-- Card 2: Total Pemeriksaan Medis -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Pemeriksaan Dokter Selesai</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($totalPemeriksaanSelesai) }}</div>
            <p class="text-[11px] text-slate-500 font-semibold">Tercatat di E-Rekam Medis</p>
        </div>

        <!-- Card 3: Obat Terjual / Diserahkan -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Jenis Obat Keluar</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.02 1.02a2 2 0 00.586 3.414l2.12.707a12.003 12.003 0 008.43 0l2.12-.707a2 2 0 00.586-3.414l-1.02-1.02z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ number_format(count($laporanObat)) }}</div>
            <p class="text-[11px] text-amber-800 font-semibold">Resep Diserahkan Farmasi</p>
        </div>

        <!-- Card 4: Total Pendapatan Klinik -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Pendapatan Kasir</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-700 font-mono tracking-tight">Rp {{ number_format($totalPendapatanKasir, 0, ',', '.') }}</div>
            <p class="text-[11px] text-emerald-800 font-semibold">Transaksi Pembayaran Lunas</p>
        </div>
    </div>

    <!-- TABBED REPORTS NAVIGATION (5 DESAIN OUTPUT SKRIPSI BAB IV) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-4 p-6">
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-4">
            <a href="{{ route('admin.laporan.index', ['tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai, 'tab' => 'pasien']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all flex items-center gap-1.5 {{ $tabActive === 'pasien' || $tabActive === 'ringkasan' ? 'bg-cyan-700 text-white shadow-md shadow-cyan-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>📊 Laporan Pasien & Kunjungan</span>
            </a>
            <a href="{{ route('admin.laporan.index', ['tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai, 'tab' => 'pemeriksaan']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all flex items-center gap-1.5 {{ $tabActive === 'pemeriksaan' ? 'bg-cyan-700 text-white shadow-md shadow-cyan-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>🩺 Laporan Pemeriksaan Medis</span>
            </a>
            <a href="{{ route('admin.laporan.index', ['tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai, 'tab' => 'rekam_medis']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all flex items-center gap-1.5 {{ $tabActive === 'rekam_medis' ? 'bg-cyan-700 text-white shadow-md shadow-cyan-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>📄 Laporan Rekam Medis & Diagnosa</span>
            </a>
            <a href="{{ route('admin.laporan.index', ['tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai, 'tab' => 'obat']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all flex items-center gap-1.5 {{ $tabActive === 'obat' ? 'bg-cyan-700 text-white shadow-md shadow-cyan-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>💊 Laporan Pengeluaran Obat</span>
            </a>
            <a href="{{ route('admin.laporan.index', ['tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai, 'tab' => 'transaksi']) }}" class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all flex items-center gap-1.5 {{ $tabActive === 'transaksi' ? 'bg-cyan-700 text-white shadow-md shadow-cyan-700/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>💰 Laporan Transaksi Kasir</span>
            </a>
        </div>

        <!-- TAB CONTENT 1: LAPORAN PASIEN & KUNJUNGAN -->
        @if($tabActive === 'pasien' || $tabActive === 'ringkasan')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Data Pasien & Rekapitulasi Kunjungan</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.laporan.print', ['jenis' => 'pasien', 'tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai]) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                        🖨️ Cetak Laporan Pasien
                    </a>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">Tgl. Kunjungan</th>
                                <th class="py-3 px-4">No. Antrean</th>
                                <th class="py-3 px-4">Nama Pasien & NIK</th>
                                <th class="py-3 px-4">Jenis Kelamin</th>
                                <th class="py-3 px-4">Poli Tujuan</th>
                                <th class="py-3 px-4">Kategori Pasien</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($laporanPasien as $idx => $p)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ \Carbon\Carbon::parse($p->tanggal_antrean)->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 px-4 font-mono font-black text-cyan-700">{{ $p->kode_antrean ?? ('#' . $p->nomor_antrean) }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900">{{ $p->nama_pasien }}</div>
                                        <span class="text-[10px] text-slate-400 font-mono">NIK: {{ $p->nik }}</span>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-700">{{ $p->jenis_kelamin }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $p->nama_poli ?? 'Poli Umum' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold {{ $p->jenis_pasien === 'BPJS' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                            {{ $p->jenis_pasien }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-400 italic">Tidak ada data kunjungan pasien pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- TAB CONTENT 2: LAPORAN PEMERIKSAAN MEDIS -->
        @if($tabActive === 'pemeriksaan')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Pemeriksaan Medis & Vital Signs</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.laporan.print', ['jenis' => 'pemeriksaan', 'tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai]) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                        🖨️ Cetak Laporan Pemeriksaan
                    </a>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">Tgl. Pemeriksaan</th>
                                <th class="py-3 px-4">Pasien & Dokter</th>
                                <th class="py-3 px-4">Hasil Vital Signs (TD/Suhu/Nadi)</th>
                                <th class="py-3 px-4">Keluhan Utama</th>
                                <th class="py-3 px-4">Diagnosa Dokter</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($laporanPemeriksaan as $idx => $pm)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ \Carbon\Carbon::parse($pm->tanggal_pemeriksaan)->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900">{{ $pm->nama_pasien }}</div>
                                        <span class="text-[10px] text-slate-500">Dr. {{ $pm->nama_dokter }} ({{ $pm->nama_poli ?? 'Poli Umum' }})</span>
                                    </td>
                                    <td class="py-3 px-4 font-mono text-[11px] text-slate-800">
                                        TD: <strong>{{ $pm->tekanan_darah ?? '-' }}</strong> • Suhu: <strong>{{ $pm->suhu_tubuh ?? '-' }}°C</strong> • Nadi: <strong>{{ $pm->nadi ?? '-' }}</strong>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600 italic">"{{ $pm->keluhan_utama ?? '-' }}"</td>
                                    <td class="py-3 px-4 font-bold text-purple-900">{{ $pm->diagnosis_penyakit ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 italic">Tidak ada data pemeriksaan medis pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- TAB CONTENT 3: LAPORAN REKAM MEDIS & TOP 10 DIAGNOSA -->
        @if($tabActive === 'rekam_medis')
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Pola Diagnosa Medis Terbanyak (Top 10 Diagnosa Klinik)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.laporan.print', ['jenis' => 'rekam_medis', 'tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai]) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                        🖨️ Cetak Laporan Diagnosa
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Tabel Top 10 Diagnosa -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-purple-50 border-b border-purple-100 text-purple-900 uppercase font-bold text-[10px]">
                                <tr>
                                    <th class="py-3 px-4 w-12 text-center">Peringkat</th>
                                    <th class="py-3 px-4">Nama Diagnosis Penyakit</th>
                                    <th class="py-3 px-4 text-center w-28">Jumlah Kasus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @forelse($topDiagnosa as $idx => $td)
                                    <tr class="hover:bg-purple-50/50">
                                        <td class="py-3 px-4 text-center font-mono font-black text-purple-700">{{ $idx + 1 }}</td>
                                        <td class="py-3 px-4 font-bold text-slate-900">{{ $td->diagnosis_penyakit }}</td>
                                        <td class="py-3 px-4 text-center font-mono font-extrabold text-purple-900 bg-purple-50/80 rounded-lg">
                                            {{ $td->jumlah }} Kasus
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-slate-400 italic">Belum ada data diagnosa penyakit pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Visual Progress Bar Diagnosa -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-3">
                        <h4 class="font-extrabold text-slate-800 text-xs uppercase tracking-wider">Persentase Pola Penyakit Klinik</h4>
                        <div class="space-y-3">
                            @php
                                $maxKasus = $topDiagnosa->max('jumlah') ?? 1;
                            @endphp
                            @foreach($topDiagnosa as $td)
                                @php
                                    $percent = round(($td->jumlah / $maxKasus) * 100);
                                @endphp
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between font-bold text-slate-800">
                                        <span>{{ $td->diagnosis_penyakit }}</span>
                                        <span class="font-mono text-purple-900">{{ $td->jumlah }} Kasus</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-purple-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- TAB CONTENT 4: LAPORAN PENGELUARAN OBAT -->
        @if($tabActive === 'obat')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Rekapitulasi Pengeluaran & Inventaris Obat Farmasi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.laporan.print', ['jenis' => 'obat', 'tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai]) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                        🖨️ Cetak Laporan Obat
                    </a>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">ID Obat</th>
                                <th class="py-3 px-4">Nama Obat & Jenis</th>
                                <th class="py-3 px-4 text-center">Total Terpakai/Keluar</th>
                                <th class="py-3 px-4 text-center">Sisa Stok Inventaris</th>
                                <th class="py-3 px-4 text-right">Harga Satuan</th>
                                <th class="py-3 px-4 text-right">Total Nominal Nilai Obat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($laporanObat as $idx => $ob)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-amber-700">{{ $ob->id_obat }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900">{{ $ob->nama_obat }}</div>
                                        <span class="text-[10px] text-slate-400">{{ $ob->jenis_obat }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-mono font-black text-amber-900 bg-amber-50 rounded">
                                        {{ $ob->total_keluar }} {{ $ob->satuan }}
                                    </td>
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-700">
                                        {{ $ob->sisa_stok }} {{ $ob->satuan }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold text-slate-700">
                                        Rp {{ number_format($ob->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-slate-900">
                                        Rp {{ number_format($ob->total_nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-400 italic">Tidak ada rekapitulasi pengeluaran obat pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- TAB CONTENT 5: LAPORAN TRANSAKSI KEUANGAN KASIR -->
        @if($tabActive === 'transaksi')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Laporan Transaksi Keuangan & Penerimaan Kasir</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Periode: {{ \Carbon\Carbon::parse($tglMulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($tglSelesai)->translatedFormat('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.laporan.print', ['jenis' => 'transaksi', 'tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai]) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                        🖨️ Cetak Laporan Keuangan
                    </a>
                </div>

                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">ID Pembayaran & Tgl</th>
                                <th class="py-3 px-4">Pasien & No. Antrean</th>
                                <th class="py-3 px-4 text-right">Biaya Medis</th>
                                <th class="py-3 px-4 text-right">Biaya Obat</th>
                                <th class="py-3 px-4 text-right">Total Tagihan</th>
                                <th class="py-3 px-4 text-center">Metode Bayar</th>
                                <th class="py-3 px-4 text-center">Kasir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($laporanTransaksi as $idx => $tr)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-4 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-mono font-bold text-emerald-800">{{ $tr->id_pembayaran }}</div>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($tr->tanggal_pembayaran)->translatedFormat('d M Y H:i') }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900">{{ $tr->nama_pasien }}</div>
                                        <span class="text-[10px] font-mono text-cyan-700">{{ $tr->kode_antrean ?? ('#' . $tr->nomor_antrean) }} ({{ $tr->jenis_pasien }})</span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold text-slate-700">
                                        Rp {{ number_format($tr->biaya_layanan_medis, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-semibold text-slate-700">
                                        Rp {{ number_format($tr->biaya_obat, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-black text-emerald-700">
                                        Rp {{ number_format($tr->total_tagihan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ $tr->metode_pembayaran ?? 'Tunai' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-700">
                                        {{ $tr->nama_kasir ?? 'Kasir' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 text-center text-slate-400 italic">Tidak ada transaksi pembayaran lunas pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
