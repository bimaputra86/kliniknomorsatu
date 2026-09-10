@extends('layouts.admin')

@section('title', 'Ruang Periksa Dokter & E-Rekam Medis')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Ruang Periksa Dokter (Poli)</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola pemeriksaan pasien, E-Rekam Medis (Diagnosa ICD-10), dan E-Resep Obat.</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('dokter.pemeriksaan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
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
                        <th class="py-3.5 px-4">Identitas Pasien</th>
                        <th class="py-3.5 px-4">Hasil Skrining Perawat</th>
                        <th class="py-3.5 px-4">Diagnosa Dokter</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi Pemeriksaan</th>
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
                                @if($antrean->tekanan_darah || $antrean->suhu_tubuh || $antrean->keluhan_utama)
                                    <div class="space-y-0.5 text-[11px]">
                                        <span class="inline-block font-bold text-slate-800">TD: {{ $antrean->tekanan_darah ?? '-' }}</span> |
                                        <span class="inline-block font-bold text-slate-800">Suhu: {{ $antrean->suhu_tubuh ?? '-' }} °C</span>
                                        <div class="text-slate-600 font-semibold truncate max-w-xs">"{{ $antrean->keluhan_utama }}"</div>
                                    </div>
                                @else
                                    <span class="text-amber-600 font-semibold italic text-[11px]">Belum di-skrining Perawat</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($antrean->diagnosis_penyakit)
                                    <div class="font-bold text-emerald-700 text-xs truncate max-w-xs">{{ $antrean->diagnosis_penyakit }}</div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum diperiksa</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($antrean->status_antrean === 'Dipanggil')
                                    <span class="inline-block px-2.5 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded-full font-bold text-[10px]">Dipanggil</span>
                                @elseif($antrean->status_antrean === 'Diperiksa')
                                    <span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 border border-purple-200 rounded-full font-bold text-[10px]">Sedang Diperiksa</span>
                                @else
                                    <span class="inline-block px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full font-bold text-[10px]">Selesai Periksa</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($antrean->status_antrean === 'Selesai')
                                        <button type="button" onclick="openDetailPemeriksaanModal({{ $antrean->id_antrean }})" class="p-1.5 bg-slate-100 hover:bg-teal-100 hover:text-teal-700 text-slate-600 rounded-lg transition cursor-pointer" title="Lihat Hasil Pemeriksaan">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('dokter.pemeriksaan.create', $antrean->id_antrean) }}" class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-xl text-xs transition-all shadow-sm inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>{{ $antrean->status_antrean === 'Selesai' ? 'Edit' : '🩺 Periksa' }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                                Tidak ada antrean pasien untuk pemeriksaan dokter pada tanggal ini.
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

<!-- Modal Detail Rekam Medis & E-Resep -->
<div id="detailPemeriksaanModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Detail Hasil Pemeriksaan Medis</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5" id="detailKodeAntrean">No. Antrean: -</p>
            </div>
            <button onclick="closeDetailPemeriksaanModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <!-- Profil Pasien -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                <h4 class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px]">Identitas Pasien</h4>
                <div class="space-y-1">
                    <div class="flex justify-between"><span class="text-slate-500">Nama Lengkap:</span><span class="font-bold text-slate-800" id="detailNamaPasien">-</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Jenis Kelamin:</span><span class="font-bold text-slate-800" id="detailJenisKelamin">-</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Tanggal Lahir:</span><span class="font-bold text-slate-800" id="detailTanggalLahir">-</span></div>
                </div>
            </div>

            <!-- Tanda Vital -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                <h4 class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px]">Skrining Vital Sign (Perawat)</h4>
                <div class="grid grid-cols-2 gap-2 text-center text-[11px] font-bold">
                    <div class="bg-white p-2 rounded-xl border border-slate-100"><span class="text-[9px] text-slate-400 block uppercase font-bold">TD</span><span class="text-slate-800" id="detailTekanDarah">-</span></div>
                    <div class="bg-white p-2 rounded-xl border border-slate-100"><span class="text-[9px] text-slate-400 block uppercase font-bold">Suhu</span><span class="text-slate-800" id="detailSuhuTubuh">-</span></div>
                    <div class="bg-white p-2 rounded-xl border border-slate-100"><span class="text-[9px] text-slate-400 block uppercase font-bold">Nadi</span><span class="text-slate-800" id="detailNadi">-</span></div>
                    <div class="bg-white p-2 rounded-xl border border-slate-100"><span class="text-[9px] text-slate-400 block uppercase font-bold">Berat/Tinggi</span><span class="text-slate-800" id="detailBeratTinggi">-</span></div>
                </div>
            </div>
        </div>

        <!-- Keluhan & Diagnosa Dokter -->
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3 text-xs">
            <div>
                <h4 class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px] mb-1">Keluhan Utama Pasien</h4>
                <p class="bg-white p-2.5 rounded-xl border border-slate-100 text-slate-700 italic font-semibold" id="detailKeluhanUtama">-</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <h4 class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px] mb-1">Diagnosis Penyakit (Dokter)</h4>
                    <p class="bg-white p-2.5 rounded-xl border border-slate-100 text-emerald-800 font-extrabold" id="detailDiagnosisPenyakit">-</p>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px] mb-1">Tindakan / Catatan Medis</h4>
                    <p class="bg-white p-2.5 rounded-xl border border-slate-100 text-slate-700 font-medium" id="detailTindakanMedis">-</p>
                </div>
            </div>
        </div>

        <!-- E-Resep Obat -->
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2 text-xs">
            <h4 class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px]">E-Resep Obat Terapi</h4>
            <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-[9px] uppercase border-b border-slate-100">
                        <tr>
                            <th class="py-2 px-3">Nama Obat</th>
                            <th class="py-2 px-3 text-center w-20">Jumlah</th>
                            <th class="py-2 px-3">Aturan Pakai / Dosis</th>
                        </tr>
                    </thead>
                    <tbody id="detailResepTableBody" class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400 italic">Tidak ada resep obat.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end pt-2 border-t border-slate-100">
            <button type="button" onclick="closeDetailPemeriksaanModal()" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-extrabold rounded-xl text-xs transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openDetailPemeriksaanModal(idAntrean) {
        // Tampilkan loading / reset data
        document.getElementById('detailKodeAntrean').textContent = 'Memuat data...';
        document.getElementById('detailNamaPasien').textContent = '-';
        document.getElementById('detailJenisKelamin').textContent = '-';
        document.getElementById('detailTanggalLahir').textContent = '-';
        document.getElementById('detailTekanDarah').textContent = '-';
        document.getElementById('detailSuhuTubuh').textContent = '-';
        document.getElementById('detailNadi').textContent = '-';
        document.getElementById('detailBeratTinggi').textContent = '-';
        document.getElementById('detailKeluhanUtama').textContent = '-';
        document.getElementById('detailDiagnosisPenyakit').textContent = '-';
        document.getElementById('detailTindakanMedis').textContent = '-';
        
        const tbody = document.getElementById('detailResepTableBody');
        tbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-slate-400">Memuat resep...</td></tr>';

        // Tampilkan modal
        document.getElementById('detailPemeriksaanModal').classList.remove('hidden');

        // Fetch detail
        const url = "{{ route('dokter.pemeriksaan.detail', ':id') }}".replace(':id', idAntrean);
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('detailKodeAntrean').textContent = `No. Antrean: ${data.antrean.kode_antrean || '#' + data.antrean.nomor_antrean} (${data.antrean.jenis_pasien})`;
                    document.getElementById('detailNamaPasien').textContent = data.antrean.nama_pasien;
                    document.getElementById('detailJenisKelamin').textContent = data.antrean.jenis_kelamin;
                    
                    // Format tanggal lahir
                    if (data.antrean.tanggal_lahir) {
                        const date = new Date(data.antrean.tanggal_lahir);
                        document.getElementById('detailTanggalLahir').textContent = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    }

                    if (data.pemeriksaan) {
                        document.getElementById('detailTekanDarah').textContent = `${data.pemeriksaan.tekanan_darah || '-'} mmHg`;
                        document.getElementById('detailSuhuTubuh').textContent = `${data.pemeriksaan.suhu_tubuh || '-'} °C`;
                        document.getElementById('detailNadi').textContent = `${data.pemeriksaan.nadi || '-'} bpm`;
                        document.getElementById('detailBeratTinggi').textContent = `${data.pemeriksaan.berat_badan || '-'}kg / ${data.pemeriksaan.tinggi_badan || '-'}cm`;
                        document.getElementById('detailKeluhanUtama').textContent = data.pemeriksaan.keluhan_utama || '-';
                        document.getElementById('detailDiagnosisPenyakit').textContent = data.pemeriksaan.diagnosis_penyakit || '-';
                        document.getElementById('detailTindakanMedis').textContent = data.pemeriksaan.tindakan_medis || '-';
                    }

                    // Tampilkan E-Resep
                    tbody.innerHTML = '';
                    if (data.resep && data.resep.length > 0) {
                        data.resep.forEach(item => {
                            tbody.innerHTML += `
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2.5 px-3 font-bold text-slate-800">${item.nama_obat}</td>
                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-900">${item.jumlah_obat} ${item.satuan}</td>
                                    <td class="py-2.5 px-3 text-slate-600 font-semibold">${item.dosis_aturan_pakai}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-slate-400 italic">Tidak ada resep obat (Terapi Non-Farmakologi).</td></tr>';
                    }
                } else {
                    alert(data.message || 'Gagal memuat detail pemeriksaan.');
                    closeDetailPemeriksaanModal();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi sistem.');
                closeDetailPemeriksaanModal();
            });
    }

    function closeDetailPemeriksaanModal() {
        document.getElementById('detailPemeriksaanModal').classList.add('hidden');
    }
</script>
@endsection
