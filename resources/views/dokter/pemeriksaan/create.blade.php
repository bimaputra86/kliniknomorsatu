@extends('layouts.admin')

@section('title', 'Input E-Rekam Medis & E-Resep Pasien')

@section('content')
<!-- Tom Select CSS (Untuk style autocomplete pencarian obat) -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Styling TomSelect modern serasi dengan Tailwind */
    .ts-wrapper {
        width: 100% !important;
        position: relative !important;
    }
    .ts-wrapper.single .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        padding: 0.5rem 0.875rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        background-color: #f8fafc !important;
        min-height: 44px !important;
        height: 44px !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .ts-wrapper.single .ts-control input {
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        height: 100% !important;
    }
    .ts-wrapper.single .ts-control .item {
        font-size: 0.875rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
    }
    .ts-control.focus {
        border-color: #0d9488 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15) !important;
    }
    .ts-dropdown {
        border-radius: 0.875rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #cbd5e1 !important;
        padding: 0.375rem !important;
        z-index: 999999 !important;
        background-color: #ffffff !important;
        margin-top: 4px !important;
    }
    .ts-dropdown .option {
        padding: 0.625rem 0.875rem !important;
        border-radius: 0.5rem !important;
        cursor: pointer !important;
    }
    .ts-dropdown .option:hover, .ts-dropdown .option.active {
        background-color: #f0fdfa !important;
        color: #0f766e !important;
    }
    .ts-dropdown .no-results {
        padding: 0.75rem 1rem !important;
        color: #94a3b8 !important;
        font-size: 0.75rem !important;
        font-style: italic !important;
    }
</style>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <a href="{{ route('dokter.pemeriksaan.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition-all inline-flex items-center gap-1.5 mb-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Antrean Periksa
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>E-Rekam Medis & E-Resep Obat Pasien</span>
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">KODE ANTREAN</span>
                <span class="px-3.5 py-1 bg-purple-100 text-purple-900 border border-purple-200 rounded-xl font-mono font-black text-base inline-block">
                    {{ $antrean->kode_antrean ?? ('#' . $antrean->nomor_antrean) }}
                </span>
            </div>
            <span class="px-3.5 py-1.5 bg-teal-50 text-teal-800 border border-teal-200 rounded-xl font-extrabold text-xs">
                {{ $antrean->jenis_pasien }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs font-bold space-y-1">
            <div class="flex items-center gap-2 text-rose-900 font-extrabold text-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Peringatan Validasi / Stok E-Resep Obat:</span>
            </div>
            <ul class="list-disc list-inside pl-7 space-y-0.5 font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TOP HERO BANNER: PROFIL PASIEN, VITAL SIGN PERAWAT & RIWAYAT (FULL WIDTH GRID 3 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- CARD 1: PROFIL PASIEN -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Identitas Pasien</span>
                    <span class="text-xs font-mono font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-lg border border-teal-100">{{ $antrean->id_pasien }}</span>
                </div>
                <div class="mt-3">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight leading-tight">{{ $antrean->nama_pasien }}</h2>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">NIK: {{ $antrean->nik }}</p>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs pt-3">
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-bold">JENIS KELAMIN</span>
                        <span class="font-bold text-slate-800">{{ $antrean->jenis_kelamin }}</span>
                    </div>
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 block font-bold">GOL. DARAH</span>
                        <span class="font-bold text-slate-800">{{ $antrean->golongan_darah ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="text-xs text-slate-600 pt-2 border-t border-slate-100">
                <span class="text-slate-400 block font-bold text-[10px] uppercase tracking-wider">Alamat Lengkap</span>
                <p class="font-medium text-slate-700 leading-snug mt-0.5">{{ $antrean->alamat_lengkap }}</p>
            </div>
        </div>

        <!-- CARD 2: VITAL SIGN SKRINING PERAWAT -->
        <div class="lg:col-span-5 bg-gradient-to-br from-teal-950 to-slate-900 p-6 rounded-3xl text-white shadow-xl space-y-4 flex flex-col justify-between relative overflow-hidden">
            <div>
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <h3 class="text-xs font-black tracking-wider uppercase text-teal-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rose-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Tanda Vital Pasien (Skrining Perawat)
                    </h3>
                    <span class="text-[10px] bg-teal-500/20 text-teal-300 px-2 py-0.5 rounded-full border border-teal-500/30 font-bold">Terverifikasi</span>
                </div>

                <div class="grid grid-cols-4 gap-2 text-center pt-3">
                    <div class="bg-white/5 p-2.5 rounded-2xl border border-white/10">
                        <span class="text-[9px] text-slate-400 block uppercase font-bold">Tek. Darah</span>
                        <span class="text-base font-black text-emerald-400 font-mono">{{ $pemeriksaan->tekanan_darah ?? '-' }}</span>
                        <span class="text-[8px] text-slate-400 block">mmHg</span>
                    </div>
                    <div class="bg-white/5 p-2.5 rounded-2xl border border-white/10">
                        <span class="text-[9px] text-slate-400 block uppercase font-bold">Suhu Tubuh</span>
                        <span class="text-base font-black text-amber-400 font-mono">{{ $pemeriksaan->suhu_tubuh ?? '-' }}</span>
                        <span class="text-[8px] text-slate-400 block">°C</span>
                    </div>
                    <div class="bg-white/5 p-2.5 rounded-2xl border border-white/10">
                        <span class="text-[9px] text-slate-400 block uppercase font-bold">Nadi</span>
                        <span class="text-base font-black text-cyan-400 font-mono">{{ $pemeriksaan->nadi ?? '-' }}</span>
                        <span class="text-[8px] text-slate-400 block">bpm</span>
                    </div>
                    <div class="bg-white/5 p-2.5 rounded-2xl border border-white/10">
                        <span class="text-[9px] text-slate-400 block uppercase font-bold">BB / TB</span>
                        <span class="text-xs font-black text-purple-300 font-mono mt-1 block">{{ $pemeriksaan->berat_badan ?? '-' }}kg / {{ $pemeriksaan->tinggi_badan ?? '-' }}cm</span>
                    </div>
                </div>
            </div>

            <div class="bg-black/40 p-3 rounded-2xl border border-white/10">
                <span class="text-[10px] text-teal-300 uppercase font-extrabold block mb-0.5">Keluhan Utama / Anamnesis Awal:</span>
                <p class="text-xs text-slate-200 italic font-medium leading-snug">
                    "{{ $pemeriksaan->keluhan_utama ?? 'Belum ada catatan keluhan perawat.' }}"
                </p>
            </div>
        </div>

        <!-- CARD 3: RIWAYAT MEDIS TERDAHULU -->
        <div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-xs font-extrabold uppercase text-slate-400 tracking-wider">Riwayat Medis Pasien</h3>
                <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-100">{{ count($riwayatMedis) }} Kunjungan</span>
            </div>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1 flex-1">
                @forelse($riwayatMedis as $rm)
                    <div onclick="openDoctorHistoryModal({{ json_encode($rm) }})" class="p-2.5 bg-slate-50 hover:bg-teal-50/60 rounded-xl border border-slate-200/80 hover:border-teal-300 transition-all cursor-pointer group space-y-1">
                        <div class="flex justify-between text-[10px] font-bold text-slate-600 group-hover:text-teal-900">
                            <span>📅 {{ \Carbon\Carbon::parse($rm->tanggal_pemeriksaan)->translatedFormat('d M Y') }}</span>
                            <span class="text-teal-700 font-extrabold truncate max-w-[90px]">Dr. {{ $rm->nama_dokter }}</span>
                        </div>
                        <div class="text-xs font-extrabold text-purple-900 group-hover:text-teal-800 truncate">
                            {{ $rm->diagnosis_penyakit ?? 'Diagnosa Medis' }}
                        </div>
                        @if(!empty($rm->detail_resep) && count($rm->detail_resep) > 0)
                            <div class="text-[10px] text-slate-500 truncate">
                                💊 {{ count($rm->detail_resep) }} Obat: 
                                <span class="font-medium text-slate-700">
                                    {{ implode(', ', array_map(fn($d) => $d->nama_obat, $rm->detail_resep->toArray())) }}
                                </span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs italic">Belum ada riwayat rekam medis sebelumnya.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- MAIN FORM (FULL WIDTH SINGLE COLUMN) -->
    <form action="{{ route('dokter.pemeriksaan.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="id_antrean" value="{{ $antrean->id_antrean }}">

        <!-- SECTION 1: ANAMNESIS & DIAGNOSA MEDIS DOKTER (FULL WIDTH) -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span>Anamnesis & Diagnosa Medis Dokter</span>
                </h3>
                <span class="text-xs text-slate-400 font-medium">* Wajib Diisi</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 tracking-wider mb-1.5">Keluhan Utama / Anamnesis Pasien <span class="text-red-500">*</span></label>
                    <textarea name="keluhan_utama" rows="3" required placeholder="Tuliskan keluhan yang dirasakan pasien..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all leading-relaxed">{{ old('keluhan_utama', $pemeriksaan->keluhan_utama ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 tracking-wider mb-1.5">Diagnosa Penyakit (ICD-10 / Text) <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis_penyakit" rows="3" required placeholder="Contoh: J06.9 Acute upper respiratory infection / Demam ISPA" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all leading-relaxed">{{ old('diagnosis_penyakit', $pemeriksaan->diagnosis_penyakit ?? '') }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 tracking-wider mb-1.5">Tindakan Medis & Catatan Edukasi Dokter</label>
                <input type="text" name="tindakan_medis" value="{{ old('tindakan_medis', $pemeriksaan->tindakan_medis ?? '') }}" placeholder="Catatan tindakan fisik, rekomendasi istirahat, diet/pola makan..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
            </div>
        </div>

        <!-- SECTION 2: INPUT E-RESEP OBAT DINAMIS (FULL WIDTH TABLE LUAS) -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-3.5">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.594 15.12a2 2 0 00-1.78.89l-.26.433a2 2 0 00.347 2.453l1.83 1.83a2 2 0 002.828 0l1.414-1.414a2 2 0 012.828 0l1.414 1.414a2 2 0 002.828 0l1.83-1.83a2 2 0 00.347-2.453l-.26-.433z"/></svg>
                        </div>
                        <span>Penulisan E-Resep Obat Pasien</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Cari obat secara instant dengan mengetikkan nama obat pada kolom pencarian Select2.</p>
                </div>
                <button type="button" onclick="addResepRow()" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl text-xs font-extrabold transition-all flex items-center gap-2 shadow-md shadow-teal-600/20 cursor-pointer shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Item Obat</span>
                </button>
            </div>

            <!-- Dynamic Resep Table (Full Width Layout) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 bg-slate-50/70">
                            <th class="py-3 px-4 rounded-l-xl w-5/12 min-w-[280px]">Nama Obat (Ketik untuk Mencari)</th>
                            <th class="py-3 px-4 w-2/12 min-w-[100px] text-center">Jumlah Qty</th>
                            <th class="py-3 px-4 w-4/12 min-w-[240px]">Dosis & Aturan Pakai</th>
                            <th class="py-3 px-4 text-center w-1/12 min-w-[60px] rounded-r-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="resepTableBody" class="divide-y divide-slate-100">
                        @if(!empty($existingResep) && count($existingResep) > 0)
                            @foreach($existingResep as $idx => $r)
                                <tr class="resep-row group hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3 px-4 align-middle">
                                        <select name="resep[{{ $idx }}][id_obat]" required class="select-obat-dropdown w-full" placeholder="🔍 Ketik nama obat...">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach($obats as $o)
                                                <option value="{{ $o->id_obat }}" {{ $r->id_obat == $o->id_obat ? 'selected' : '' }}>
                                                    {{ $o->nama_obat }} (Stok: {{ $o->stok }} {{ $o->satuan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <input type="number" name="resep[{{ $idx }}][jumlah_obat]" value="{{ $r->jumlah_obat }}" min="1" required placeholder="Jml" class="w-full h-11 px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-center text-slate-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-xs">
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <input type="text" name="resep[{{ $idx }}][dosis_aturan_pakai]" value="{{ $r->dosis_aturan_pakai }}" required placeholder="Misal: 3 x 1 Tablet Sesudah Makan" class="w-full h-11 px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-xs">
                                    </td>
                                    <td class="py-3 px-4 text-center align-middle">
                                        <button type="button" onclick="removeResepRow(this)" class="w-10 h-10 inline-flex items-center justify-center text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all font-bold text-xl border border-transparent hover:border-rose-200 cursor-pointer" title="Hapus Baris Obat">
                                            &times;
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="resep-row group hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4 align-middle">
                                    <select name="resep[0][id_obat]" required class="select-obat-dropdown w-full" placeholder="🔍 Ketik nama obat...">
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach($obats as $o)
                                            <option value="{{ $o->id_obat }}">
                                                {{ $o->nama_obat }} (Stok: {{ $o->stok }} {{ $o->satuan }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-3 px-4 align-middle">
                                    <input type="number" name="resep[0][jumlah_obat]" min="1" value="1" required placeholder="Jml" class="w-full h-11 px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-center text-slate-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-xs">
                                </td>
                                <td class="py-3 px-4 align-middle">
                                    <input type="text" name="resep[0][dosis_aturan_pakai]" required placeholder="Misal: 3 x 1 Tablet Sesudah Makan" class="w-full h-11 px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-xs">
                                </td>
                                <td class="py-3 px-4 text-center align-middle">
                                    <button type="button" onclick="removeResepRow(this)" class="w-10 h-10 inline-flex items-center justify-center text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all font-bold text-xl border border-transparent hover:border-rose-200 cursor-pointer" title="Hapus Baris Obat">
                                        &times;
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ACTION BUTTONS BAR -->
        <div class="flex items-center justify-end gap-4 pt-2 pb-6">
            <a href="{{ route('dokter.pemeriksaan.index') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-xs transition-all">
                Batal
            </a>
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold rounded-2xl text-xs shadow-xl shadow-purple-600/30 transition-all flex items-center gap-2.5 cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Selesai Pemeriksaan & Simpan Rekam Medis</span>
            </button>
        </div>
    </form>
</div>
</div>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let resepRowIndex = {{ !empty($existingResep) ? count($existingResep) : 1 }};
    let liveSearchTimers = {};

    function addResepRow() {
        const tbody = document.getElementById('resepTableBody');
        const tr = document.createElement('tr');
        tr.className = 'resep-row group hover:bg-slate-50/50 transition-colors';
        tr.innerHTML = `
            <td class="py-3 px-4 align-middle">
                <select name="resep[${resepRowIndex}][id_obat]" required class="select-obat-dropdown w-full" placeholder="🔍 Ketik nama obat...">
                    <option value="">-- Pilih Obat --</option>
                    @foreach($obats as $o)
                        <option value="{{ $o->id_obat }}">
                            {{ $o->nama_obat }} (Stok: {{ $o->stok }} {{ $o->satuan }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="py-3 px-4 align-middle">
                <input type="number" name="resep[${resepRowIndex}][jumlah_obat]" min="1" value="1" required placeholder="Jml" class="w-full h-11 px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-center text-slate-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-xs">
            </td>
            <td class="py-3 px-4 align-middle">
                <input type="text" name="resep[${resepRowIndex}][dosis_aturan_pakai]" required placeholder="Misal: 3 x 1 Tablet Sesudah Makan" class="w-full h-11 px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all shadow-xs">
            </td>
            <td class="py-3 px-4 text-center align-middle">
                <button type="button" onclick="removeResepRow(this)" class="w-10 h-10 inline-flex items-center justify-center text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all font-bold text-xl border border-transparent hover:border-rose-200 cursor-pointer" title="Hapus Baris Obat">
                    &times;
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        
        // Inisialisasi TomSelect pada elemen yang baru ditambahkan
        const newSelect = tr.querySelector('.select-obat-dropdown');
        initTomSelect(newSelect);
        
        resepRowIndex++;
    }

    function removeResepRow(btn) {
        const rows = document.querySelectorAll('.resep-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
        } else {
            alert('Minimal 1 baris resep obat.');
        }
    }

    function initTomSelect(element) {
        if (element.tomselect) return; // Mencegah inisialisasi ganda

        new TomSelect(element, {
            valueField: 'value',
            labelField: 'text',
            searchField: ['text', 'nama_obat'],
            dropdownParent: 'body',
            maxOptions: 25,
            load: function(query, callback) {
                if (!query.length) return callback();
                
                const url = "{{ route('dokter.obat.search') }}?q=" + encodeURIComponent(query);
                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        if(json.success && json.data) {
                            // Menambahkan field tambahan untuk ditampilkan
                            const results = json.data.map(item => ({
                                value: item.id_obat,
                                text: `${item.nama_obat} (Stok: ${item.stok} ${item.satuan})`,
                                id_obat: item.id_obat,
                                nama_obat: item.nama_obat,
                                info_stok: `(Stok: ${item.stok} ${item.satuan})`
                            }));
                            callback(results);
                        } else {
                            callback();
                        }
                    })
                    .catch(() => {
                        callback();
                    });
            },
            render: {
                option: function(item, escape) {
                    let name = item.nama_obat || item.text || '';
                    let stockInfo = item.info_stok || '';
                    
                    if (!stockInfo && typeof name === 'string' && name.includes(' (Stok:')) {
                        let parts = name.split(' (Stok:');
                        name = parts[0];
                        stockInfo = '(Stok:' + parts[1];
                    }

                    return `<div class="flex justify-between items-center w-full py-1">
                                <span class="font-bold text-slate-800">${escape(name)}</span>
                                ${stockInfo ? `<span class="text-[10px] text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-100 font-mono">${escape(stockInfo)}</span>` : ''}
                            </div>`;
                },
                item: function(item, escape) {
                    let name = item.nama_obat || item.text || '';
                    if (typeof name === 'string' && name.includes(' (Stok:')) {
                        name = name.split(' (Stok:')[0];
                    }
                    return `<div>
                                <span class="font-bold text-slate-800">${escape(name)}</span>
                            </div>`;
                },
                no_results: function() {
                    return '<div class="no-results">Obat tidak ditemukan di inventaris...</div>';
                }
            },
            placeholder: "🔍 Cari & Pilih Obat...",
            plugins: ['clear_button'],
        });
    }

    // Inisialisasi pada saat halaman dimuat untuk elemen yang sudah ada
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-obat-dropdown').forEach(function(el) {
            initTomSelect(el);
        });
    });

    function openDoctorHistoryModal(rm) {
        document.getElementById('docHistoryTgl').textContent = `Pemeriksaan Tgl: ${rm.tanggal_pemeriksaan}`;
        document.getElementById('docHistoryDokter').textContent = `Dr. ${rm.nama_dokter || 'Dokter'}`;
        document.getElementById('docHistoryDiagnosis').textContent = rm.diagnosis_penyakit || '-';
        document.getElementById('docHistoryTindakan').textContent = rm.tindakan_medis || '-';
        document.getElementById('docHistoryKeluhan').textContent = rm.keluhan_utama || '-';
        
        document.getElementById('docHistoryTD').textContent = rm.tekanan_darah ? `${rm.tekanan_darah} mmHg` : '-';
        document.getElementById('docHistorySuhu').textContent = rm.suhu_tubuh ? `${rm.suhu_tubuh} °C` : '-';
        document.getElementById('docHistoryNadi').textContent = rm.nadi ? `${rm.nadi} bpm` : '-';
        document.getElementById('docHistoryBBTB').textContent = (rm.berat_badan || rm.tinggi_badan) ? `${rm.berat_badan || 0} kg / ${rm.tinggi_badan || 0} cm` : '-';

        const tbody = document.getElementById('docHistoryResepBody');
        tbody.innerHTML = '';
        if (rm.detail_resep && rm.detail_resep.length > 0) {
            rm.detail_resep.forEach((d, i) => {
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-3 text-center font-mono text-slate-400">${i+1}</td>
                        <td class="py-2 px-3 font-bold text-slate-800">${d.nama_obat}</td>
                        <td class="py-2 px-3 text-center font-mono font-bold text-slate-900">${d.jumlah_obat} ${d.satuan}</td>
                        <td class="py-2 px-3 text-teal-800 font-bold">🏷️ ${d.dosis_aturan_pakai}</td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="4" class="py-3 text-center text-slate-400 italic">Terapi Non-Farmakologi (Tanpa obat resep).</td></tr>';
        }

        document.getElementById('doctorHistoryModal').classList.remove('hidden');
    }

    function closeDoctorHistoryModal() {
        document.getElementById('doctorHistoryModal').classList.add('hidden');
    }
</script>

<!-- Modal Detail Riwayat Medis Terdahulu untuk Dokter -->
<div id="doctorHistoryModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Detail Rekam Medis Terdahulu Pasien</h3>
                <p class="text-[10px] text-teal-700 font-mono font-bold mt-0.5" id="docHistoryTgl">Tgl: -</p>
            </div>
            <button type="button" onclick="closeDoctorHistoryModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 text-xs">
            <span class="text-[10px] text-slate-400 font-bold uppercase block">Dokter Pemeriksa Sebelumnya</span>
            <span class="font-extrabold text-slate-900" id="docHistoryDokter">-</span>
        </div>

        <!-- Vital Signs -->
        <div class="grid grid-cols-4 gap-2 text-center text-xs">
            <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                <span class="text-[9px] text-slate-400 font-bold block">TD</span>
                <span class="font-bold text-slate-800" id="docHistoryTD">-</span>
            </div>
            <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                <span class="text-[9px] text-slate-400 font-bold block">Suhu</span>
                <span class="font-bold text-slate-800" id="docHistorySuhu">-</span>
            </div>
            <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                <span class="text-[9px] text-slate-400 font-bold block">Nadi</span>
                <span class="font-bold text-slate-800" id="docHistoryNadi">-</span>
            </div>
            <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                <span class="text-[9px] text-slate-400 font-bold block">BB/TB</span>
                <span class="font-bold text-slate-800" id="docHistoryBBTB">-</span>
            </div>
        </div>

        <!-- Diagnosis & Keluhan -->
        <div class="space-y-2 text-xs">
            <div>
                <span class="text-[10px] font-extrabold text-purple-900 uppercase block">Diagnosa Medis Terdahulu:</span>
                <p class="p-2.5 bg-purple-50 text-purple-950 font-extrabold rounded-xl border border-purple-100" id="docHistoryDiagnosis">-</p>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-slate-500 uppercase block">Keluhan Utama:</span>
                <p class="p-2 bg-slate-50 text-slate-700 italic rounded-xl border border-slate-100" id="docHistoryKeluhan">-</p>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-slate-500 uppercase block">Tindakan Medis / Edukasi:</span>
                <p class="p-2 bg-slate-50 text-slate-700 rounded-xl border border-slate-100" id="docHistoryTindakan">-</p>
            </div>
        </div>

        <!-- Resep Obat -->
        <div class="space-y-1.5 text-xs">
            <h4 class="font-extrabold text-slate-800 text-xs">Obat Resep Diberikan Saat Itu</h4>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-[9px] uppercase border-b border-slate-100">
                        <tr>
                            <th class="py-2 px-3 text-center w-10">No</th>
                            <th class="py-2 px-3">Nama Obat</th>
                            <th class="py-2 px-3 text-center w-16">Qty</th>
                            <th class="py-2 px-3">Aturan Pakai</th>
                        </tr>
                    </thead>
                    <tbody id="docHistoryResepBody" class="divide-y divide-slate-100 font-medium">
                        <tr><td colspan="4" class="py-3 text-center text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-2 border-t border-slate-100 text-right">
            <button type="button" onclick="closeDoctorHistoryModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection
