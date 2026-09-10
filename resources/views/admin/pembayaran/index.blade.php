@extends('layouts.admin')

@section('title', 'Kasir & Pembayaran Pasien')

@section('content')
<div class="space-y-6">
    <!-- Header Section dengan Lonceng Notifikasi Interaktif -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-600/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kasir & Billing Pembayaran Pasien</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola perincian biaya tindakan medis, resep obat dokter, dan penerbitan faktur / kuitansi resmi.</p>
                </div>
            </div>
        </div>

        <!-- Lonceng Notifikasi Kasir -->
        <div class="flex items-center gap-3">
            <div class="relative flex items-center gap-2.5 px-4 py-2.5 bg-emerald-50 border border-emerald-200/80 rounded-2xl">
                <div class="relative">
                    <svg class="w-6 h-6 text-emerald-700 {{ ($pendingBayarCount ?? 0) > 0 ? 'animate-bounce' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(($pendingBayarCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-600 text-white font-extrabold text-[9px] rounded-full flex items-center justify-center shadow-sm animate-pulse">
                            {{ $pendingBayarCount }}
                        </span>
                    @endif
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-emerald-800">Antrean Siap Bayar</span>
                    <span class="font-mono font-black text-sm text-emerald-950">
                        {{ $pendingBayarCount ?? 0 }} Pasien
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi Pasien Baru -->
    @if(($pendingBayarCount ?? 0) > 0)
        <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="p-2 bg-emerald-600 text-white rounded-xl shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <div>
                    <span class="text-xs font-black text-emerald-950 block">Pemeriksaan Dokter Selesai!</span>
                    <span class="text-[11px] text-emerald-800">Terdapat <strong>{{ $pendingBayarCount }} pasien</strong> yang baru selesai diperiksa dokter dan menunggu proses pembayaran / faktur kuitansi di kasir.</span>
                </div>
            </div>
            <a href="{{ route('admin.pembayaran.index', ['status_pembayaran' => 'Belum Lunas']) }}" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition shadow-xs">
                Lihat Antrean
            </a>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="w-full sm:w-44">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal Transaksi</label>
                <input type="date" name="tanggal" value="{{ $tanggalFilter }}" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 transition-all">
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status Pembayaran</label>
                <select name="status_pembayaran" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 transition-all">
                    <option value="Semua" {{ $statusFilter === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Belum Lunas" {{ $statusFilter === 'Belum Lunas' ? 'selected' : '' }}>⏳ Belum Lunas</option>
                    <option value="Lunas" {{ $statusFilter === 'Lunas' ? 'selected' : '' }}>✅ Lunas (Selesai)</option>
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Cari Pasien / Antrean</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama pasien, NIK, atau kode antrean..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-emerald-500 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-32">No. Antrean</th>
                        <th class="py-3.5 px-4">Pasien & Poli</th>
                        <th class="py-3.5 px-4">Diagnosa & Resep Obat</th>
                        <th class="py-3.5 px-4 text-right">Biaya Medis</th>
                        <th class="py-3.5 px-4 text-right">Biaya Obat</th>
                        <th class="py-3.5 px-4 text-right">Total Tagihan</th>
                        <th class="py-3.5 px-4 text-center w-48">Aksi Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($tagihans as $item)
                        @php
                            $isLunas = ($item->status_pembayaran === 'Lunas');
                            $totalObatItems = count($item->detail_resep ?? []);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="py-3.5 px-4 font-mono font-black text-emerald-700 text-base">
                                {{ $item->kode_antrean ?? ('#' . $item->nomor_antrean) }}
                                <span class="block text-[10px] font-bold {{ $item->jenis_pasien === 'BPJS' ? 'text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded inline-block mt-0.5' : 'text-slate-400' }}">
                                    {{ $item->jenis_pasien }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $item->nama_pasien }}</div>
                                <div class="text-[11px] text-slate-500">Poli: <span class="font-bold text-slate-700">{{ $item->nama_poli ?? 'Poli Umum' }}</span> • Dr. {{ $item->nama_dokter }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 truncate max-w-xs">{{ $item->diagnosis_penyakit ?? '-' }}</div>
                                <div class="text-[10px] mt-1 space-y-0.5">
                                    @if($totalObatItems > 0)
                                        <span class="inline-flex items-center gap-1 font-bold text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                            💊 {{ $totalObatItems }} Item Resep Dokter
                                        </span>
                                        <div class="text-[10px] text-slate-500 truncate max-w-xs">
                                            @foreach($item->detail_resep as $dr)
                                                <span>{{ $dr->nama_obat }} ({{ $dr->jumlah_obat }}), </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">Non-Farmakologi (0 Obat)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800">
                                Rp {{ number_format($item->calc_biaya_layanan, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800">
                                Rp {{ number_format($item->calc_biaya_obat, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-black text-sm {{ $item->jenis_pasien === 'BPJS' ? 'text-blue-700' : 'text-emerald-700' }}">
                                @if($item->jenis_pasien === 'BPJS')
                                    <span>Rp 0</span>
                                    <span class="block text-[9px] font-bold text-blue-600 uppercase">Covered BPJS</span>
                                @else
                                    <span>Rp {{ number_format($isLunas ? $item->total_tagihan : $item->calc_total_tagihan, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($isLunas)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200/80 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Lunas
                                        </span>
                                        <a href="{{ route('admin.pembayaran.kuitansi', $item->id_pembayaran) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-700 text-slate-600 rounded-xl transition cursor-pointer" title="Cetak Kuitansi / Faktur Pembayaran">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                    @else
                                        <button type="button" onclick="openPembayaranModal('{{ $item->id_pemeriksaan }}')" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Proses Bayar</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                Belum ada data antrean pembayaran kasir pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tagihans->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $tagihans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Form Transaksi Pembayaran Kasir -->
<div id="pembayaranModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Transaksi Kasir & Pembayaran Pasien</h3>
                <p class="text-[10px] text-emerald-700 font-mono font-bold mt-0.5" id="modalKodeAntrean">No. Antrean: -</p>
            </div>
            <button type="button" onclick="closePembayaranModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('admin.pembayaran.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_pemeriksaan" id="modalIdPemeriksaan">

            <!-- Detail Pasien & Poli -->
            <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Pasien</span>
                    <span class="font-extrabold text-slate-900" id="modalNamaPasien">-</span>
                    <span class="block text-[11px] font-bold text-emerald-700" id="modalJenisPasien">-</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Dokter & Poli</span>
                    <span class="font-extrabold text-slate-900" id="modalNamaDokter">-</span>
                    <span class="block text-[11px] text-slate-500" id="modalNamaPoli">-</span>
                </div>
            </div>

            <!-- Rincian Resep Obat yang Diresepkan Dokter -->
            <div class="p-3 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-extrabold text-amber-900 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.02 1.02a2 2 0 00.586 3.414l2.12.707a12.003 12.003 0 008.43 0l2.12-.707a2 2 0 00.586-3.414l-1.02-1.02z"/></svg>
                        Rincian E-Resep Obat Dokter
                    </span>
                    <span class="font-bold text-amber-800 font-mono" id="modalBiayaObatText">Rp 0</span>
                </div>
                <div id="modalRincianResepList" class="text-[11px] text-slate-600 divide-y divide-amber-100/60 max-h-28 overflow-y-auto">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Fleksibel Input Biaya Layanan Medis & Total Billing -->
            <div class="space-y-3 bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-extrabold text-slate-700 flex items-center gap-1">
                        <span>Biaya Layanan / Tindakan Poli (Rp)</span>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-100 px-1.5 py-0.5 rounded">Fleksibel</span>
                    </label>
                    <input type="number" name="biaya_layanan_medis" id="modalBiayaLayanan" min="0" value="50000" oninput="calculateBillingTotal()" required class="w-36 px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-bold text-right text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20">
                </div>

                <div class="flex items-center justify-between text-sm pt-2 border-t border-emerald-200 font-black">
                    <span class="text-slate-900">TOTAL TAGIHAN PASIEN:</span>
                    <span class="font-mono text-base text-emerald-700" id="modalTotalTagihanText">Rp 0</span>
                </div>
            </div>

            <!-- Form Pilihan Pembayaran & Nominal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="modalMetodeBayar" onchange="toggleMetodeBayarUI()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:border-emerald-600 transition-all">
                        <option value="Tunai">💵 Tunai (Cash)</option>
                        <option value="Transfer">💳 Transfer Bank / Debit</option>
                        <option value="QRIS">📱 QRIS / E-Wallet</option>
                        <option value="BPJS">🏥 Cover BPJS Kesehatan</option>
                    </select>
                </div>

                <div id="nominalBayarContainer">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Nominal Diterima Kasir (Rp)</label>
                    <input type="number" name="nominal_bayar" id="modalNominalBayar" min="0" placeholder="Masukkan jumlah uang" oninput="calculateBillingTotal()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:border-emerald-600 transition-all">
                </div>
            </div>

            <!-- Box Kembalian -->
            <div id="kembalianBox" class="p-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between text-xs font-bold">
                <span class="text-slate-600">Kembalian Uang Tunai:</span>
                <span class="font-mono text-sm text-slate-900" id="modalKembalianText">Rp 0</span>
            </div>

            <!-- Notice BPJS Box -->
            <div id="bpjsNoticeBox" class="hidden p-3 bg-blue-50 text-blue-900 border border-blue-200 rounded-2xl text-xs font-medium space-y-1">
                <div class="font-extrabold flex items-center gap-1.5 text-blue-950">
                    <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pasien Peserta BPJS Kesehatan
                </div>
                <p class="text-[11px] text-blue-800 leading-relaxed">
                    Total tagihan otomatis <strong>Rp 0 (Bebas Biaya)</strong> karena ditanggung BPJS. Kasir akan mencetak <strong>Faktur Pelayanan & Bukti Klaim BPJS</strong> yang dapat dibawa pasien ke Apoteker.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closePembayaranModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan & Lunaskan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('print_id_pembayaran'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.open("{{ route('admin.pembayaran.kuitansi', session('print_id_pembayaran')) }}", '_blank');
        });
    </script>
@endif

<script>
    let currentRawBiayaObat = 0;
    let currentJenisPasien = 'Umum';

    function openPembayaranModal(idPemeriksaan) {
        document.getElementById('modalIdPemeriksaan').value = idPemeriksaan;
        document.getElementById('modalKodeAntrean').textContent = 'Memuat data...';
        document.getElementById('modalNamaPasien').textContent = '-';
        document.getElementById('modalJenisPasien').textContent = '-';
        document.getElementById('modalNamaDokter').textContent = '-';
        document.getElementById('modalNamaPoli').textContent = '-';
        document.getElementById('modalBiayaLayanan').value = 50000;
        document.getElementById('modalBiayaObatText').textContent = 'Rp 0';
        document.getElementById('modalTotalTagihanText').textContent = 'Rp 0';
        document.getElementById('modalNominalBayar').value = '';
        document.getElementById('modalKembalianText').textContent = 'Rp 0';
        document.getElementById('modalRincianResepList').innerHTML = '<span class="italic text-slate-400">Memuat resep...</span>';

        document.getElementById('pembayaranModal').classList.remove('hidden');

        const url = "{{ route('admin.pembayaran.show', ':id') }}".replace(':id', idPemeriksaan);
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.pemeriksaan;
                    document.getElementById('modalKodeAntrean').textContent = `No. Antrean: ${p.kode_antrean || '#' + p.nomor_antrean}`;
                    document.getElementById('modalNamaPasien').textContent = p.nama_pasien;
                    document.getElementById('modalJenisPasien').textContent = `Status: ${p.jenis_pasien} ${p.no_bpjs ? '(' + p.no_bpjs + ')' : ''}`;
                    document.getElementById('modalNamaDokter').textContent = `Dr. ${p.nama_dokter || '-'}`;
                    document.getElementById('modalNamaPoli').textContent = p.nama_poli || 'Poli Umum';
                    
                    document.getElementById('modalBiayaLayanan').value = data.biaya_layanan || 50000;
                    currentRawBiayaObat = data.biaya_obat || 0;
                    currentJenisPasien = p.jenis_pasien || 'Umum';

                    // Render list detail resep obat
                    const rincianContainer = document.getElementById('modalRincianResepList');
                    if (data.detail_resep && data.detail_resep.length > 0) {
                        let html = '';
                        data.detail_resep.forEach((item, i) => {
                            const subtotal = item.jumlah_obat * item.harga_satuan;
                            html += `
                                <div class="py-1 flex items-center justify-between">
                                    <span>${i + 1}. <strong>${item.nama_obat}</strong> (${item.jumlah_obat} ${item.satuan})</span>
                                    <span class="font-mono text-slate-700 font-bold">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</span>
                                </div>
                            `;
                        });
                        rincianContainer.innerHTML = html;
                    } else {
                        rincianContainer.innerHTML = '<span class="italic text-slate-400">Tidak ada resep obat (Terapi Non-Farmakologi).</span>';
                    }

                    if (currentJenisPasien === 'BPJS') {
                        document.getElementById('modalMetodeBayar').value = 'BPJS';
                    } else {
                        document.getElementById('modalMetodeBayar').value = 'Tunai';
                    }

                    document.getElementById('modalBiayaObatText').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(currentRawBiayaObat)}`;

                    toggleMetodeBayarUI();
                    calculateBillingTotal();
                } else {
                    alert(data.message || 'Gagal memuat data tagihan.');
                    closePembayaranModal();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi sistem.');
                closePembayaranModal();
            });
    }

    function closePembayaranModal() {
        document.getElementById('pembayaranModal').classList.add('hidden');
    }

    function toggleMetodeBayarUI() {
        const metode = document.getElementById('modalMetodeBayar').value;
        const container = document.getElementById('nominalBayarContainer');
        const kembalianBox = document.getElementById('kembalianBox');
        const bpjsBox = document.getElementById('bpjsNoticeBox');

        if (metode === 'BPJS' || currentJenisPasien === 'BPJS') {
            container.classList.add('hidden');
            kembalianBox.classList.add('hidden');
            bpjsBox.classList.remove('hidden');
            document.getElementById('modalNominalBayar').value = '0';
        } else {
            container.classList.remove('hidden');
            kembalianBox.classList.remove('hidden');
            bpjsBox.classList.add('hidden');
        }
        calculateBillingTotal();
    }

    function calculateBillingTotal() {
        const biayaLayanan = parseFloat(document.getElementById('modalBiayaLayanan').value) || 0;
        const metode = document.getElementById('modalMetodeBayar').value;
        const isBPJS = (currentJenisPasien === 'BPJS' || metode === 'BPJS');

        const totalTagihan = isBPJS ? 0 : (biayaLayanan + currentRawBiayaObat);
        document.getElementById('modalTotalTagihanText').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalTagihan)}`;

        if (!isBPJS) {
            const nominalBayar = parseFloat(document.getElementById('modalNominalBayar').value) || 0;
            const kembalian = Math.max(0, nominalBayar - totalTagihan);
            document.getElementById('modalKembalianText').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(kembalian)}`;
        }
    }
</script>
@endsection
