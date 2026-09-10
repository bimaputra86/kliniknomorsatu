@extends('layouts.admin')

@section('title', 'Antrean Resep Farmasi & Dispensasi Obat')

@section('content')
<div class="space-y-6">
    <!-- Header Section dengan Lonceng Notifikasi Apoteker -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.02 1.02a2 2 0 00.586 3.414l2.12.707a12.003 12.003 0 008.43 0l2.12-.707a2 2 0 00.586-3.414l-1.02-1.02z"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Antrean Resep Farmasi & Apoteker</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Verifikasi pembayaran faktur kasir, penyiapan obat, dan penyerahan obat ke pasien.</p>
                </div>
            </div>
        </div>

        <!-- Lonceng Notifikasi Resep Siap Diserahkan -->
        <div class="flex items-center gap-3">
            <div class="relative flex items-center gap-2.5 px-4 py-2.5 bg-amber-50 border border-amber-200/80 rounded-2xl">
                <div class="relative">
                    <svg class="w-6 h-6 text-amber-700 {{ ($readyToDispensCount ?? 0) > 0 ? 'animate-bounce' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(($readyToDispensCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-600 text-white font-extrabold text-[9px] rounded-full flex items-center justify-center shadow-sm animate-pulse">
                            {{ $readyToDispensCount }}
                        </span>
                    @endif
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-amber-900">Resep Lunas Siap Ambil</span>
                    <span class="font-mono font-black text-sm text-amber-950">
                        {{ $readyToDispensCount ?? 0 }} Pasien
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Banner Pasien Siap Ambil Obat -->
    @if(($readyToDispensCount ?? 0) > 0)
        <div class="p-4 bg-gradient-to-r from-amber-50 to-emerald-50 border border-amber-200 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="p-2 bg-amber-500 text-white rounded-xl shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <div>
                    <span class="text-xs font-black text-amber-950 block">Pembayaran Kasir Terverifikasi!</span>
                    <span class="text-[11px] text-amber-900">Terdapat <strong>{{ $readyToDispensCount }} resep pasien</strong> yang sudah menyelesaikan pembayaran / verifikasi BPJS di Kasir dan siap diserahkan obatnya.</span>
                </div>
            </div>
            <a href="{{ route('apoteker.resep.index', ['status_resep' => 'Menunggu']) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl transition shadow-xs">
                Lihat Resep
            </a>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('apoteker.resep.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="w-full sm:w-44">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal Resep</label>
                <input type="date" name="tanggal" value="{{ $tanggalFilter }}" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 transition-all">
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status Resep</label>
                <select name="status_resep" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 transition-all">
                    <option value="Semua" {{ $statusFilter === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Menunggu" {{ $statusFilter === 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu Penyiapan</option>
                    <option value="Diserahkan" {{ $statusFilter === 'Diserahkan' ? 'selected' : '' }}>✅ Sudah Diserahkan</option>
                    <option value="Tidak Diambil" {{ $statusFilter === 'Tidak Diambil' ? 'selected' : '' }}>⚠️ Tidak Diambil</option>
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Cari Pasien / Antrean</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama pasien, NIK, atau kode antrean..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-amber-500 transition-all">
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
                        <th class="py-3.5 px-4">Pasien & Dokter Pengirim</th>
                        <th class="py-3.5 px-4">Status Kasir</th>
                        <th class="py-3.5 px-4">Status Penyiapan</th>
                        <th class="py-3.5 px-4">Rincian Obat Terapi</th>
                        <th class="py-3.5 px-4 text-right">Total Biaya Obat</th>
                        <th class="py-3.5 px-4 text-center w-52">Aksi Farmasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($reseps as $item)
                        @php
                            $isPaid = $item->is_paid;
                            $isBPJS = ($item->jenis_pasien === 'BPJS' || $item->metode_pembayaran === 'BPJS');
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="py-3.5 px-4 font-mono font-black text-amber-700 text-base">
                                {{ $item->kode_antrean ?? ('#' . $item->nomor_antrean) }}
                                <span class="block text-[10px] font-bold {{ $item->jenis_pasien === 'BPJS' ? 'text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded inline-block mt-0.5' : 'text-slate-400' }}">
                                    {{ $item->jenis_pasien }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $item->nama_pasien }}</div>
                                <div class="text-[11px] text-slate-500">Poli: <span class="font-bold text-slate-700">{{ $item->nama_poli ?? 'Poli Umum' }}</span> • Dr. {{ $item->nama_dokter }}</div>
                            </td>
                            <!-- Status Pembayaran Kasir -->
                            <td class="py-3.5 px-4">
                                @if($isPaid)
                                    @if($isBPJS)
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-800 border border-blue-200/80 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Faktur BPJS (Lunas)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200/80 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Kuitansi Lunas
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-300 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1 animate-pulse">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Belum Bayar di Kasir
                                    </span>
                                @endif
                            </td>
                            <!-- Status Penyiapan Resep -->
                            <td class="py-3.5 px-4">
                                @if(($item->status_resep ?? 'Menunggu') === 'Diserahkan')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200/80 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1 shadow-sm">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Sudah Diserahkan
                                    </span>
                                @elseif(($item->status_resep ?? 'Menunggu') === 'Tidak Diambil')
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-800 border border-rose-200/80 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1 shadow-sm">
                                        <svg class="w-3 h-3 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tidak Diambil (Stok Kembali)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200/80 rounded-full font-extrabold text-[10px] inline-flex items-center gap-1 shadow-sm">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Menunggu Penyiapan
                                    </span>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-1">Diag: <span class="font-bold text-slate-600 truncate max-w-xs inline-block align-bottom">{{ $item->diagnosis_penyakit }}</span></div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->total_items > 0)
                                    <div class="space-y-1">
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 border border-amber-200 rounded-md font-extrabold text-[10px]">
                                            {{ $item->total_items }} Jenis Obat
                                        </span>
                                        <div class="text-[11px] text-slate-600 truncate max-w-xs">
                                            @foreach($item->detail_resep as $dr)
                                                <span class="inline-block bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-semibold text-slate-700 mr-1 mb-1">
                                                    {{ $dr->nama_obat }} ({{ $dr->jumlah_obat }} {{ $dr->satuan }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Terapi Non-Farmakologi (0 Obat)</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">
                                Rp {{ number_format($item->total_biaya_obat, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="openDetailResepModal({{ $item->id_antrean }})" class="p-2 bg-slate-100 hover:bg-amber-100 hover:text-amber-700 text-slate-600 rounded-xl transition cursor-pointer" title="Lihat E-Resep & Etiket">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>

                                    @if(($item->status_resep ?? 'Menunggu') === 'Menunggu')
                                        @if($isPaid)
                                            <!-- Tombol Serahkan Aktif (Karena Sudah Lunas / Terverifikasi di Kasir) -->
                                            <form action="{{ route('apoteker.resep.diserahkan', $item->id_antrean) }}" method="POST" class="inline" onsubmit="return confirm('Konfirmasi Penyerahan:\n\nPembayaran pasien {{ $item->nama_pasien }} telah LUNAS.\nObat siap diserahkan kepada pasien?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1 cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Serahkan</span>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Tombol Disabled Jika Belum Lunas di Kasir -->
                                            <button type="button" onclick="alert('PERINGATAN: Pasien {{ $item->nama_pasien }} belum menyelesaikan pembayaran di Kasir!\n\nHarap arahkan pasien ke loket Kasir terlebih dahulu untuk membayar tagihan / mendapatkan faktur kuitansi sebelum obat diserahkan.');" class="px-3 py-1.5 bg-slate-200 text-slate-500 font-extrabold rounded-xl text-xs flex items-center gap-1 cursor-not-allowed hover:bg-slate-300 transition-all" title="Arahkan Pasien ke Kasir Terlebih Dahulu">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                <span>Arahkan ke Kasir</span>
                                            </button>
                                        @endif

                                        <form action="{{ route('apoteker.resep.batal_ambil', $item->id_antrean) }}" method="POST" class="inline" onsubmit="return confirm('PASIEN TIDAK MENGAMBIL OBAT:\n\nApakah Anda yakin ingin membatalkan penyerahan resep ini? Stok {{ $item->total_items }} obat akan dikembalikan otomatis ke inventaris!');">
                                            @csrf
                                            <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all cursor-pointer" title="Pasien Tidak Mengambil Obat (Kembalikan Stok)">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @elseif(($item->status_resep ?? 'Menunggu') === 'Diserahkan')
                                        <span class="text-[11px] font-extrabold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-100">Selesai Diserahkan</span>
                                    @else
                                        <span class="text-[11px] font-extrabold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-xl border border-rose-100">Batal Ambil</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                Belum ada antrean resep obat dari Dokter pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reseps->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $reseps->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Detail E-Resep & Etiket Obat Apoteker -->
<div id="detailResepModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Verifikasi & Penyiapan E-Resep Farmasi</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5" id="modalKodeAntrean">No. Antrean: -</p>
            </div>
            <button onclick="closeDetailResepModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <!-- Status Pembayaran Kasir Badge di Modal -->
        <div id="modalPaymentStatusContainer" class="p-3 rounded-2xl border flex items-center justify-between text-xs">
            <span class="font-bold" id="modalPaymentStatusText">-</span>
            <span class="font-mono font-black text-sm" id="modalPaymentStatusSub">-</span>
        </div>

        <!-- Info Pasien & Dokter -->
        <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Pasien</span>
                <span class="font-extrabold text-slate-900" id="modalNamaPasien">-</span>
                <span class="block text-[11px] text-slate-500" id="modalJenisKelamin">-</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Dokter Penulis Resep</span>
                <span class="font-extrabold text-slate-900" id="modalNamaDokter">-</span>
                <span class="block text-[11px] text-slate-500" id="modalNamaPoli">-</span>
            </div>
        </div>

        <!-- Diagnosa & Status Resep -->
        <div class="flex items-center justify-between text-xs bg-amber-50/50 p-3 rounded-2xl border border-amber-100">
            <div>
                <span class="text-[10px] font-extrabold text-amber-800 uppercase tracking-wider block">Diagnosa Dokter:</span>
                <p class="font-bold text-amber-950" id="modalDiagnosis">-</p>
            </div>
            <div id="modalStatusResepBadge"></div>
        </div>

        <!-- Tabel Obat & Etiket -->
        <div class="space-y-2 text-xs">
            <h4 class="font-extrabold text-slate-800 text-xs">Daftar Obat & Aturan Pakai (Etiket)</h4>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-[9px] uppercase border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3">Nama & Jenis Obat</th>
                            <th class="py-2.5 px-3 text-center w-20">Jumlah</th>
                            <th class="py-2.5 px-3">Dosis / Etiket Aturan Pakai</th>
                            <th class="py-2.5 px-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modalResepTableBody" class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-400">Memuat data obat...</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200 font-bold">
                        <tr>
                            <td colspan="3" class="py-2.5 px-3 text-right text-slate-600">Total Estimasi Harga Obat:</td>
                            <td class="py-2.5 px-3 text-right font-mono text-amber-700" id="modalTotalBiaya">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" onclick="closeDetailResepModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openDetailResepModal(idAntrean) {
        document.getElementById('modalKodeAntrean').textContent = 'Memuat data...';
        document.getElementById('modalNamaPasien').textContent = '-';
        document.getElementById('modalJenisKelamin').textContent = '-';
        document.getElementById('modalNamaDokter').textContent = '-';
        document.getElementById('modalNamaPoli').textContent = '-';
        document.getElementById('modalDiagnosis').textContent = '-';
        document.getElementById('modalTotalBiaya').textContent = 'Rp 0';
        document.getElementById('modalStatusResepBadge').innerHTML = '';
        
        const tbody = document.getElementById('modalResepTableBody');
        tbody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-slate-400">Memuat data obat...</td></tr>';

        document.getElementById('detailResepModal').classList.remove('hidden');

        const url = "{{ route('apoteker.resep.show', ':id') }}".replace(':id', idAntrean);
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.pemeriksaan;
                    document.getElementById('modalKodeAntrean').textContent = `No. Antrean: ${p.kode_antrean || '#' + p.nomor_antrean} (${p.jenis_pasien})`;
                    document.getElementById('modalNamaPasien').textContent = p.nama_pasien;
                    document.getElementById('modalJenisKelamin').textContent = `${p.jenis_kelamin} • NIK: ${p.nik}`;
                    document.getElementById('modalNamaDokter').textContent = p.nama_dokter || 'Dokter Bertugas';
                    document.getElementById('modalNamaPoli').textContent = p.nama_poli || 'Poli Umum';
                    document.getElementById('modalDiagnosis').textContent = p.diagnosis_penyakit || '-';
                    document.getElementById('modalTotalBiaya').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(data.total_biaya)}`;

                    // Render status pembayaran di modal
                    const payContainer = document.getElementById('modalPaymentStatusContainer');
                    const payText = document.getElementById('modalPaymentStatusText');
                    const paySub = document.getElementById('modalPaymentStatusSub');
                    if (data.is_paid) {
                        payContainer.className = 'p-3 rounded-2xl border flex items-center justify-between text-xs bg-emerald-50 border-emerald-200 text-emerald-950';
                        payText.innerHTML = '✅ <strong>Status Pembayaran: LUNAS</strong> (Kuitansi Kasir Terverifikasi)';
                        paySub.textContent = p.metode_pembayaran ? `Metode: ${p.metode_pembayaran}` : 'Lunas';
                    } else {
                        payContainer.className = 'p-3 rounded-2xl border flex items-center justify-between text-xs bg-rose-50 border-rose-200 text-rose-950';
                        payText.innerHTML = '⚠️ <strong>Status Pembayaran: BELUM DIBAYAR</strong> (Pasien harus ke Kasir)';
                        paySub.textContent = 'Menunggu Kasir';
                    }

                    const statusResep = p.status_resep || 'Menunggu';
                    let badgeHtml = '';
                    if (statusResep === 'Diserahkan') {
                        badgeHtml = '<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-extrabold text-[10px]">✅ Diserahkan</span>';
                    } else if (statusResep === 'Tidak Diambil') {
                        badgeHtml = '<span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full font-extrabold text-[10px]">⚠️ Tidak Diambil</span>';
                    } else {
                        badgeHtml = '<span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-extrabold text-[10px]">⏳ Menunggu Penyiapan</span>';
                    }
                    document.getElementById('modalStatusResepBadge').innerHTML = badgeHtml;

                    tbody.innerHTML = '';
                    if (data.detail_resep && data.detail_resep.length > 0) {
                        data.detail_resep.forEach(item => {
                            const subtotal = item.jumlah_obat * item.harga_satuan;
                            tbody.innerHTML += `
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2.5 px-3">
                                        <div class="font-bold text-slate-900">${item.nama_obat}</div>
                                        <span class="text-[10px] text-slate-400 font-semibold">${item.jenis_obat}</span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-900">${item.jumlah_obat} ${item.satuan}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-1 bg-amber-50 text-amber-900 border border-amber-200/60 rounded-lg text-[11px] font-bold inline-block">
                                            🏷️ ${item.dosis_aturan_pakai}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-slate-400 italic">Tidak ada resep obat (Terapi Non-Farmakologi).</td></tr>';
                    }
                } else {
                    alert(data.message || 'Gagal memuat detail resep.');
                    closeDetailResepModal();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi sistem.');
                closeDetailResepModal();
            });
    }

    function closeDetailResepModal() {
        document.getElementById('detailResepModal').classList.add('hidden');
    }
</script>
@endsection
