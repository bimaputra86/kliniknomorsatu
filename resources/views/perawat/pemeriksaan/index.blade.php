@extends('layouts.admin')

@section('title', 'Skrining Tanda Vital Perawat')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Skrining Tanda Vital (Perawat)</h1>
            <p class="text-xs text-slate-500 mt-1">Input data tanda vital & anamnesis awal pasien sebelum diperiksa oleh Dokter.</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('perawat.pemeriksaan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal Kunjungan</label>
                <input type="date" name="tanggal" value="{{ $tanggalFilter }}" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-teal-500 transition-all">
            </div>

            <div class="flex-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Cari Pasien / Kode Antrean</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama pasien, NIK, atau kode antrean..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-teal-500 transition-all">
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
                        <th class="py-3.5 px-4">No. Antrean</th>
                        <th class="py-3.5 px-4">Data Pasien</th>
                        <th class="py-3.5 px-4">Poli / Dokter Bertugas</th>
                        <th class="py-3.5 px-4">Tanda Vital (Hasil Skrining)</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Skrining</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($antreans as $antrean)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="py-3.5 px-4 font-mono font-black text-teal-700 text-base">
                                {{ $antrean->kode_antrean ?? ('#' . $antrean->nomor_antrean) }}
                                <span class="block text-[10px] font-bold text-slate-400">{{ $antrean->jenis_pasien }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $antrean->nama_pasien }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">NIK: {{ $antrean->nik }} • {{ $antrean->jenis_kelamin }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800">{{ $antrean->nama_poli_master ?? ($antrean->nama_poli_jadwal ?? 'Poli Kunjungan') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $antrean->nama_dokter ?? 'Dokter Jaga' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($antrean->tekanan_darah || $antrean->suhu_tubuh || $antrean->keluhan_utama)
                                    <div class="space-y-0.5 text-[11px]">
                                        <span class="inline-block font-bold text-slate-800">TD: {{ $antrean->tekanan_darah ?? '-' }} mmHg</span> |
                                        <span class="inline-block font-bold text-slate-800">Suhu: {{ $antrean->suhu_tubuh ?? '-' }} °C</span> |
                                        <span class="inline-block font-bold text-slate-800">BB: {{ $antrean->berat_badan ?? '-' }} kg</span>
                                        <div class="text-slate-500 italic truncate max-w-xs">"{{ $antrean->keluhan_utama }}"</div>
                                    </div>
                                @else
                                    <span class="text-amber-600 font-semibold italic text-[11px]">Belum diisi perawat</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($antrean->status_antrean === 'Dipanggil')
                                    <span class="inline-block px-2.5 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded-full font-bold text-[10px]">Dipanggil</span>
                                @elseif($antrean->status_antrean === 'Diperiksa')
                                    <span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 border border-purple-200 rounded-full font-bold text-[10px]">Diperiksa Dokter</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full font-bold text-[10px]">Selesai</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" onclick="openVitalSignModal('{{ json_encode($antrean) }}')" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-1 mx-auto">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Input Vital Sign</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                                Tidak ada antrean pasien untuk skrining perawat pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($antreans->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $antreans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Input Vital Sign Perawat -->
<div id="vitalSignModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Input Tanda Vital & Anamnesis Awal</h3>
                <p id="modalPasienInfo" class="text-xs font-semibold text-teal-700 font-mono mt-0.5">-</p>
            </div>
            <button onclick="closeVitalSignModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1">&times;</button>
        </div>

        <form action="{{ route('perawat.pemeriksaan.store-vital-sign') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_antrean" id="modalIdAntrean">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tekanan Darah (mmHg)</label>
                    <input type="text" name="tekanan_darah" id="modalTekananDarah" placeholder="Contoh: 120/80" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Suhu Tubuh (°C)</label>
                    <input type="text" name="suhu_tubuh" id="modalSuhuTubuh" placeholder="Contoh: 36.5" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nadi (bpm)</label>
                    <input type="text" name="nadi" id="modalNadi" placeholder="Contoh: 80" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Berat Badan (kg)</label>
                    <input type="text" name="berat_badan" id="modalBeratBadan" placeholder="Contoh: 65" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tinggi Badan (cm)</label>
                <input type="text" name="tinggi_badan" id="modalTinggiBadan" placeholder="Contoh: 168" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keluhan Utama / Anamnesis Awal Pasien <span class="text-red-500">*</span></label>
                <textarea name="keluhan_utama" id="modalKeluhanUtama" rows="3" required placeholder="Keluhan yang dirasakan pasien saat datang..." class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-teal-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeVitalSignModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-xl text-xs shadow-md shadow-teal-600/30 transition-all">
                    Simpan Tanda Vital
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVitalSignModal(jsonStr) {
        const item = JSON.parse(jsonStr);
        document.getElementById('modalIdAntrean').value = item.id_antrean;
        document.getElementById('modalPasienInfo').textContent = `${item.kode_antrean ?? ('#' + item.nomor_antrean)} - ${item.nama_pasien}`;
        
        document.getElementById('modalTekananDarah').value = item.tekanan_darah || '';
        document.getElementById('modalSuhuTubuh').value = item.suhu_tubuh || '';
        document.getElementById('modalNadi').value = item.nadi || '';
        document.getElementById('modalBeratBadan').value = item.berat_badan || '';
        document.getElementById('modalTinggiBadan').value = item.tinggi_badan || '';
        document.getElementById('modalKeluhanUtama').value = item.keluhan_utama || '';

        document.getElementById('vitalSignModal').classList.remove('hidden');
    }

    function closeVitalSignModal() {
        document.getElementById('vitalSignModal').classList.add('hidden');
    }
</script>
@endsection
