@extends('layouts.admin')

@section('title', 'Nomor Antrean Kunjungan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Nomor Antrean Kunjungan</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau dan kelola antrean pasien online & loket (onsite) real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.antrean-kunjungan.display') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 bg-slate-800 hover:bg-slate-900 text-slate-100 font-bold rounded-2xl text-xs border border-slate-700 shadow-md transition-all">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>🖥️ Buka Layar Display TV</span>
            </a>
            <button type="button" onclick="openOnsiteModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-2xl text-xs shadow-lg shadow-teal-600/30 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Terbitkan Antrean Loket (Walk-In)</span>
            </button>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Total Antrean</span>
            <p class="text-2xl font-black text-slate-800 mt-0.5">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-amber-700 uppercase">Menunggu</span>
            <p class="text-2xl font-black text-amber-800 mt-0.5">{{ $stats['menunggu'] }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-blue-700 uppercase">Dipanggil</span>
            <p class="text-2xl font-black text-blue-800 mt-0.5">{{ $stats['dipanggil'] }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-2xl border border-purple-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-purple-700 uppercase">Diperiksa</span>
            <p class="text-2xl font-black text-purple-800 mt-0.5">{{ $stats['diperiksa'] }}</p>
        </div>
        <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-emerald-700 uppercase">Selesai</span>
            <p class="text-2xl font-black text-emerald-800 mt-0.5">{{ $stats['selesai'] }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-2xl border border-red-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-red-700 uppercase">Dibatalkan</span>
            <p class="text-2xl font-black text-red-800 mt-0.5">{{ $stats['dibatalkan'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.antrean-kunjungan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">
            
            <!-- Search Keyword -->
            <div class="md:col-span-2">
                <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Cari Pasien / Kode</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama, NIK, No BPJS, Kode..." class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Filter Tanggal -->
            <div>
                <label for="tanggal" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggalFilter }}" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
            </div>

            <!-- Filter Jenis Pasien -->
            <div>
                <label for="jenis_pasien" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Kepesertaan</label>
                <select name="jenis_pasien" id="jenis_pasien" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">-- Semua --</option>
                    <option value="Umum/Mandiri" {{ $jenisFilter === 'Umum/Mandiri' ? 'selected' : '' }}>Umum / Mandiri</option>
                    <option value="BPJS" {{ $jenisFilter === 'BPJS' ? 'selected' : '' }}>BPJS Kesehatan</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu" {{ $statusFilter === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Dipanggil" {{ $statusFilter === 'Dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="Diperiksa" {{ $statusFilter === 'Diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                    <option value="Selesai" {{ $statusFilter === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ $statusFilter === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Submit Button & Reset -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    Filter
                </button>
                <a href="{{ route('admin.antrean-kunjungan.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition-all">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">Kode Antrean</th>
                        <th class="py-3.5 px-4">Identitas Pasien</th>
                        <th class="py-3.5 px-4">Kepesertaan</th>
                        <th class="py-3.5 px-4">Poli & Dokter Tujuan</th>
                        <th class="py-3.5 px-4 text-center">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Resepsionis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($antreans as $antrean)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Kode Antrean Badge -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block font-mono text-base font-black px-3 py-1 rounded-xl shadow-sm border {{ $antrean->jenis_pasien === 'BPJS' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-teal-50 text-teal-700 border-teal-200' }}">
                                    {{ $antrean->kode_antrean ?? ('#' . $antrean->nomor_antrean) }}
                                </span>
                            </td>

                            <!-- Identitas Pasien -->
                            <td class="py-3.5 px-4">
                                <p class="font-extrabold text-slate-900 text-sm">{{ $antrean->nama_pasien }}</p>
                                <p class="text-[10px] text-slate-500 font-mono">NIK: {{ $antrean->nik }}</p>
                                @if($antrean->no_bpjs)
                                    <p class="text-[10px] text-blue-700 font-mono font-bold">BPJS: {{ $antrean->no_bpjs }}</p>
                                @endif
                            </td>

                            <!-- Kepesertaan -->
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $antrean->jenis_pasien === 'BPJS' ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-teal-100 text-teal-800 border-teal-200' }}">
                                    {{ $antrean->jenis_pasien }}
                                </span>
                            </td>

                            <!-- Poli & Dokter -->
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">{{ $antrean->nama_poli_master ?? $antrean->nama_poli_jadwal ?? 'Poli Umum' }}</p>
                                <p class="text-[11px] text-slate-500">Dr. {{ $antrean->nama_dokter ?? '-' }}</p>
                            </td>

                            <!-- Tanggal & Waktu -->
                            <td class="py-3.5 px-4 text-center">
                                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($antrean->tanggal_antrean)->translatedFormat('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($antrean->created_at)->format('H:i') }} WIB</p>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4 text-center">
                                @if($antrean->status_antrean === 'Menunggu')
                                    <span class="inline-block px-2.5 py-1 bg-amber-100 text-amber-800 border border-amber-200 rounded-full font-bold text-[10px]">Menunggu</span>
                                @elseif($antrean->status_antrean === 'Dipanggil')
                                    <span class="inline-block px-2.5 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded-full font-bold text-[10px] animate-pulse">Dipanggil</span>
                                @elseif($antrean->status_antrean === 'Diperiksa')
                                    <span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 border border-purple-200 rounded-full font-bold text-[10px]">Diperiksa</span>
                                @elseif($antrean->status_antrean === 'Selesai')
                                    <span class="inline-block px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full font-bold text-[10px]">Selesai</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 bg-red-100 text-red-800 border border-red-200 rounded-full font-bold text-[10px]">Dibatalkan</span>
                                @endif
                            </td>

                            <!-- Aksi Resepsionis -->
                            <td class="py-3.5 px-4 text-center space-y-1">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Print Thermal Struk -->
                                    <a href="{{ route('admin.antrean-kunjungan.print', $antrean->id_antrean) }}" target="_blank" title="Cetak Struk Loket" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <!-- Action Status Buttons -->
                                    @if($antrean->status_antrean === 'Menunggu')
                                        <form action="{{ route('admin.antrean-kunjungan.panggil', $antrean->id_antrean) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Panggil Pasien di Display TV & Loket" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-lg text-[11px] transition-all shadow-md flex items-center gap-1">
                                                <span>📢 Panggil</span>
                                            </button>
                                        </form>
                                    @elseif($antrean->status_antrean === 'Dipanggil')
                                        <div class="inline-flex items-center gap-1">
                                            <form action="{{ route('admin.antrean-kunjungan.panggil', $antrean->id_antrean) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="Panggil Ulang Pasien (Recall)" class="px-2 py-1 bg-amber-500 hover:bg-amber-600 text-white font-extrabold rounded-lg text-[10px] transition-all shadow-sm">
                                                    🔔 Ulang
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.antrean-kunjungan.update-status', $antrean->id_antrean) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_antrean" value="Diperiksa">
                                                <button type="submit" title="Pasien Masuk Ruang Periksa Dokter" class="px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-lg text-[10px] transition-all shadow-sm">
                                                    🩺 Diperiksa
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- Batalkan Button -->
                                    @if(in_array($antrean->status_antrean, ['Menunggu', 'Dipanggil']))
                                        <form action="{{ route('admin.antrean-kunjungan.update-status', $antrean->id_antrean) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan antrean ini?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_antrean" value="Dibatalkan">
                                            <button type="submit" title="Batalkan Antrean" class="p-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                Tidak ada data antrean kunjungan yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $antreans->links() }}
        </div>
    </div>
</div>

<!-- Modal Terbitkan Antrean Loket (Onsite / Walk-In) Cerdas & Server-Side Search -->
<div id="onsiteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full mx-4 shadow-2xl border border-slate-200 space-y-5 transform scale-95 transition-transform duration-200 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Terbitkan Antrean Loket (Walk-In)</h3>
                <p class="text-xs text-slate-500">Pencarian pasien server-side & slot dokter cerdas real-time.</p>
            </div>
            <button type="button" onclick="closeOnsiteModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.antrean-kunjungan.store-onsite') }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. Pencarian Pasien Server-Side (AJAX) -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700">1. Cari & Pilih Pasien <span class="text-red-500">* (Server-Side Live Search)</span></label>
                
                <!-- Input Pencarian Live -->
                <div class="relative">
                    <input type="text" id="pasien_search_input" oninput="searchPatientsServerSide(this.value)" placeholder="Ketik NIK, Nama Pasien, No BPJS, atau ID..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Live Results Dropdown Container -->
                <div id="pasien_search_results" class="hidden max-h-48 overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-lg divide-y divide-slate-100 text-xs">
                </div>

                <!-- Pasien Terpilih Card -->
                <div id="selected_patient_card" class="hidden p-3.5 rounded-2xl bg-teal-50 border border-teal-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-teal-700 bg-teal-100 px-2 py-0.5 rounded-md">Pasien Terpilih</span>
                        <p id="selected_patient_name" class="font-extrabold text-slate-900 text-sm mt-1"></p>
                        <p id="selected_patient_info" class="text-[11px] text-slate-600 font-mono"></p>
                    </div>
                    <button type="button" onclick="resetSelectedPatient()" class="px-2.5 py-1 bg-white hover:bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold transition-all">
                        Ganti Pasien
                    </button>
                </div>

                <input type="hidden" name="id_pasien" id="modal_id_pasien_hidden" required>
            </div>

            <!-- 2. Pilih Poliklinik Tujuan -->
            <div>
                <label for="modal_poli_select" class="block text-xs font-bold text-slate-700 mb-1">
                    2. Pilih Poliklinik Tujuan <span class="text-red-500">*</span>
                </label>
                <select id="modal_poli_select" onchange="onModalPoliOrDateChange()" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">-- Pilih Poliklinik --</option>
                    @foreach($polis as $p)
                        <option value="{{ $p->id_poli }}">{{ $p->nama_poli }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Tanggal Kunjungan Berobat -->
            <div>
                <label for="modal_tanggal_berobat" class="block text-xs font-bold text-slate-700 mb-1">
                    3. Tanggal Kunjungan Berobat <span class="text-red-500">* (Maksimal H+7)</span>
                </label>
                <input type="date" name="tanggal_berobat" id="modal_tanggal_berobat" 
                       value="{{ date('Y-m-d') }}" 
                       min="{{ date('Y-m-d') }}" 
                       max="{{ \Carbon\Carbon::today()->addDays(7)->format('Y-m-d') }}" 
                       onchange="onModalPoliOrDateChange()" 
                       required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                <p id="modalDayBadge" class="text-xs font-bold text-teal-700 mt-1 hidden"></p>
            </div>

            <!-- 4. Dokter Bertugas & Slot Kuota Real-Time -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    4. Dokter Bertugas & Kuota Real-Time <span class="text-red-500">*</span>
                </label>
                <div id="modalDoctorCardsContainer" class="grid grid-cols-1 gap-2.5">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-400 font-medium">
                        Silakan pilih Poliklinik & Tanggal Kunjungan terlebih dahulu.
                    </div>
                </div>
                <input type="hidden" name="id_jadwal" id="modal_id_jadwal_hidden" required>
            </div>

            <!-- Modal Action Buttons -->
            <div class="pt-3 flex gap-3 border-t border-slate-100">
                <button type="button" onclick="closeOnsiteModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <button type="submit" id="btnOnsiteSubmit" disabled class="flex-1 py-3 px-4 bg-teal-600 hover:bg-teal-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white rounded-xl font-extrabold text-xs shadow-lg shadow-teal-600/30 transition-all">
                    Terbitkan Tiket Loket
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let searchDebounceTimer = null;
    const dayNamesList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    function openOnsiteModal() {
        document.getElementById('onsiteModal').classList.remove('hidden');
    }

    function closeOnsiteModal() {
        document.getElementById('onsiteModal').classList.add('hidden');
    }

    // Server-Side Patient Live Search (AJAX with Debounce)
    function searchPatientsServerSide(query) {
        clearTimeout(searchDebounceTimer);
        const resultsContainer = document.getElementById('pasien_search_results');

        if (!query || query.trim().length < 1) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('hidden');
            return;
        }

        searchDebounceTimer = setTimeout(() => {
            fetch(`{{ route('admin.antrean-kunjungan.search-patients') }}?q=${encodeURIComponent(query.trim())}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        resultsContainer.innerHTML = `<div class="p-3 text-center text-slate-400">Tidak ada pasien ditemukan.</div>`;
                        resultsContainer.classList.remove('hidden');
                        return;
                    }

                    let html = '';
                    data.forEach(p => {
                        html += `
                            <div onclick="selectPatientFromSearch('${p.id_pasien}', '${escapeJs(p.nama_lengkap)}', '${p.nik}', '${p.no_bpjs || ''}', '${p.jenis_pasien}')" class="p-3 hover:bg-teal-50 cursor-pointer transition-colors flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-slate-900">${p.nama_lengkap}</p>
                                    <p class="text-[10px] text-slate-500 font-mono">NIK: ${p.nik} ${p.no_bpjs ? '| BPJS: ' + p.no_bpjs : ''}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold text-slate-700">${p.jenis_pasien}</span>
                            </div>
                        `;
                    });
                    resultsContainer.innerHTML = html;
                    resultsContainer.classList.remove('hidden');
                });
        }, 250);
    }

    function escapeJs(str) {
        return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    function selectPatientFromSearch(idPasien, nama, nik, noBpjs, jenisPasien) {
        document.getElementById('modal_id_pasien_hidden').value = idPasien;
        document.getElementById('selected_patient_name').innerText = nama;
        document.getElementById('selected_patient_info').innerText = `NIK: ${nik} ${noBpjs ? '• BPJS: ' + noBpjs : ''} • Kepesertaan: ${jenisPasien}`;
        
        document.getElementById('selected_patient_card').classList.remove('hidden');
        document.getElementById('pasien_search_input').value = '';
        document.getElementById('pasien_search_results').classList.add('hidden');
        
        validateModalSubmitState();
    }

    function resetSelectedPatient() {
        document.getElementById('modal_id_pasien_hidden').value = '';
        document.getElementById('selected_patient_card').classList.add('hidden');
        document.getElementById('pasien_search_input').value = '';
        validateModalSubmitState();
    }

    // Smart Doctor & Quota Fetching via AJAX
    function onModalPoliOrDateChange() {
        const poli = document.getElementById('modal_poli_select').value;
        const tanggal = document.getElementById('modal_tanggal_berobat').value;
        const container = document.getElementById('modalDoctorCardsContainer');
        const dayBadge = document.getElementById('modalDayBadge');
        const hiddenJadwal = document.getElementById('modal_id_jadwal_hidden');

        hiddenJadwal.value = '';

        if (tanggal) {
            const dateObj = new Date(tanggal + 'T00:00:00');
            const dayName = dayNamesList[dateObj.getDay()];
            dayBadge.innerText = `Hari Pilihan: ${dayName} (${tanggal})`;
            dayBadge.classList.remove('hidden');
        } else {
            dayBadge.classList.add('hidden');
        }

        if (!poli || !tanggal) {
            container.innerHTML = `<div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-400 font-medium">Silakan pilih Poliklinik & Tanggal Kunjungan terlebih dahulu.</div>`;
            validateModalSubmitState();
            return;
        }

        container.innerHTML = `<div class="p-3.5 text-center text-xs text-teal-600 font-bold">Mengecek jadwal dokter & sisa kuota real-time...</div>`;

        fetch(`{{ route('admin.antrean-kunjungan.doctors') }}?poli=${encodeURIComponent(poli)}&tanggal_berobat=${encodeURIComponent(tanggal)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `<div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-center text-xs text-amber-800 font-bold">Tidak ada dokter yang berpraktik pada hari tersebut untuk poli ini. Silakan pilih tanggal kunjungan lainnya.</div>`;
                    validateModalSubmitState();
                    return;
                }

                let html = '';
                data.forEach(item => {
                    const isFull = item.is_full;
                    const cardClass = isFull 
                        ? 'p-3 rounded-2xl bg-slate-100 border border-slate-200 opacity-60 cursor-not-allowed flex items-center justify-between text-xs'
                        : 'modal-doctor-card p-3 rounded-2xl bg-white border border-slate-200 hover:border-teal-500 cursor-pointer flex items-center justify-between transition-all text-xs';

                    const badgeHtml = isFull 
                        ? `<span class="px-2 py-0.5 rounded-lg bg-red-100 text-red-700 text-[10px] font-black border border-red-200">KUOTA HABIS</span>`
                        : `<span class="px-2 py-0.5 rounded-lg bg-teal-50 text-teal-700 text-[10px] font-bold border border-teal-100">Sisa Kuota: ${item.sisa_kuota} Pasien</span>`;

                    html += `
                        <div onclick="${isFull ? '' : 'selectModalDoctor(' + item.id_jadwal + ', this)'}" class="${cardClass}">
                            <div class="flex items-center gap-2.5">
                                <input type="radio" name="modal_schedule_radio" value="${item.id_jadwal}" ${isFull ? 'disabled' : ''} class="text-teal-600 focus:ring-teal-500">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-xs">Dr. ${item.nama_lengkap}</h4>
                                    <p class="text-[10px] text-slate-500">Praktik Hari ${item.hari} • Jam ${item.jam_mulai.substr(0,5)} - ${item.jam_selesai.substr(0,5)} WIB</p>
                                </div>
                            </div>
                            ${badgeHtml}
                        </div>
                    `;
                });
                container.innerHTML = html;
                validateModalSubmitState();
            });
    }

    function selectModalDoctor(idJadwal, element) {
        document.getElementById('modal_id_jadwal_hidden').value = idJadwal;
        const radio = element.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        validateModalSubmitState();
    }

    function validateModalSubmitState() {
        const idPasien = document.getElementById('modal_id_pasien_hidden').value;
        const idJadwal = document.getElementById('modal_id_jadwal_hidden').value;
        const btn = document.getElementById('btnOnsiteSubmit');

        if (idPasien && idJadwal) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }
</script>
@endsection
