@extends('layouts.pasien')

@section('title', 'Riwayat Rekam Medis Pasien')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Rekam Medis & E-Resep</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar riwayat pemeriksaan medis, hasil diagnosis dokter, dan resep obat Anda.</p>
        </div>
        <div>
            <a href="{{ route('pasien.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-xs transition-all inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Search Filter Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('pasien.rekam-medis.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari diagnosa penyakit, keluhan, nama dokter, atau poli..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl text-xs transition-all shadow-md shadow-teal-600/20">
                    Cari Data
                </button>
                @if($search)
                    <a href="{{ route('pasien.rekam-medis.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl text-xs transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Medical Record Cards / Table -->
    <div class="space-y-4">
        @forelse($rekamMedis as $rm)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden hover:shadow-md transition-all">
                <div class="p-6 space-y-4">
                    <!-- Top Info Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 font-bold flex flex-col items-center justify-center shrink-0 border border-teal-100">
                                <span class="text-xs uppercase leading-none text-teal-500 font-mono">{{ \Carbon\Carbon::parse($rm->tanggal_pemeriksaan)->translatedFormat('M') }}</span>
                                <span class="text-lg font-black leading-none mt-0.5 text-teal-900">{{ \Carbon\Carbon::parse($rm->tanggal_pemeriksaan)->format('d') }}</span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                    <span>{{ $rm->nama_poli ?? 'Poliklinik Umum' }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full bg-teal-50 text-teal-700 text-[10px] font-bold border border-teal-200/60">
                                        {{ $rm->jenis_pasien }}
                                    </span>
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">Dokter Pemeriksa: <strong class="text-slate-800">Dr. {{ $rm->nama_dokter }}</strong></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Status Resep Badge -->
                            @if(($rm->status_resep ?? 'Menunggu') === 'Diserahkan')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full font-extrabold text-[11px] inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Obat Diserahkan
                                </span>
                            @elseif(($rm->status_resep ?? 'Menunggu') === 'Tidak Diambil')
                                <span class="px-3 py-1 bg-rose-50 text-rose-800 border border-rose-200 rounded-full font-extrabold text-[11px] inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Obat Tidak Diambil
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full font-extrabold text-[11px] inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Proses Farmasi
                                </span>
                            @endif

                            <!-- Action Buttons -->
                            <button type="button" onclick="openPasienRekamMedisModal('{{ $rm->id_pemeriksaan }}')" class="px-3.5 py-2 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 font-bold rounded-xl text-xs transition-all cursor-pointer inline-flex items-center gap-1" title="Lihat Detail Rekam Medis">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Detail</span>
                            </button>

                            <a href="{{ route('pasien.rekam-medis.print', $rm->id_pemeriksaan) }}" target="_blank" class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition-all inline-flex items-center gap-1 shadow-sm" title="Cetak Ringkasan Rekam Medis">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Cetak</span>
                            </a>
                        </div>
                    </div>

                    <!-- Details Body Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <!-- Column 1: Diagnosa & Keluhan -->
                        <div class="md:col-span-2 space-y-2.5">
                            <div>
                                <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider block">Diagnosa Medis Dokter</span>
                                <p class="text-slate-900 font-extrabold text-sm mt-0.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    {{ $rm->diagnosis_penyakit ?? 'Tidak ada catatan diagnosa spesifik' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Keluhan Utama Saat Berobat</span>
                                    <p class="text-slate-700 font-medium italic mt-0.5">"{{ $rm->keluhan_utama ?? '-' }}"</p>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Tindakan / Terapi Medis</span>
                                    <p class="text-slate-700 font-medium mt-0.5">{{ $rm->tindakan_medis ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Resep Obat Diberikan -->
                        <div class="bg-teal-50/50 p-3.5 rounded-2xl border border-teal-100/80 space-y-2">
                            <span class="text-[10px] text-teal-800 font-extrabold uppercase tracking-wider block">E-Resep Obat Terapi</span>
                            @if(count($rm->detail_resep) > 0)
                                <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                    @foreach($rm->detail_resep as $dr)
                                        <div class="p-2 bg-white rounded-xl border border-teal-100 flex items-start justify-between gap-2 shadow-2xs">
                                            <div>
                                                <span class="font-bold text-slate-900 block text-[11px]">{{ $dr->nama_obat }}</span>
                                                <span class="text-[10px] text-teal-700 font-semibold block">🏷️ {{ $dr->dosis_aturan_pakai }}</span>
                                            </div>
                                            <span class="px-2 py-0.5 bg-teal-100 text-teal-900 font-mono font-bold rounded text-[10px] shrink-0">
                                                {{ $dr->jumlah_obat }} {{ $dr->satuan }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-slate-500 italic text-[11px]">Terapi Non-Farmakologi (Tanpa Obat Resep).</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
                <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-base">Belum Ada Riwayat Rekam Medis</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Riwayat rekam medis Anda akan muncul di sini secara otomatis setelah Dokter menyelesaikan pemeriksaan di Poliklinik.</p>
                <a href="{{ route('pasien.antrean.create') }}" class="inline-block px-5 py-2.5 bg-teal-600 text-white font-bold rounded-2xl text-xs hover:bg-teal-700 transition-all shadow-md shadow-teal-600/20">
                    + Ambil Antrean Berobat Now
                </a>
            </div>
        @endforelse
    </div>

    @if($rekamMedis->hasPages())
        <div class="pt-4">
            {{ $rekamMedis->links() }}
        </div>
    @endif
</div>

<!-- Modal Detail Rekam Medis Pasien -->
<div id="modalPasienRekamMedis" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Detail Ringkasan Rekam Medis Pasien</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5" id="modalTglPemeriksaan">Tgl: -</p>
            </div>
            <button onclick="closePasienRekamMedisModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1 cursor-pointer">&times;</button>
        </div>

        <!-- Info Poli & Dokter -->
        <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Poliklinik & Antrean</span>
                <span class="font-extrabold text-slate-900" id="modalPoli">-</span>
                <span class="block text-[11px] text-teal-700 font-mono font-bold" id="modalKodeAntrean">-</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Dokter Pemeriksa</span>
                <span class="font-extrabold text-slate-900" id="modalDokter">-</span>
            </div>
        </div>

        <!-- Vital Signs (Perawat) -->
        <div class="bg-teal-50/60 p-3.5 rounded-2xl border border-teal-100 space-y-2">
            <span class="text-[10px] font-extrabold text-teal-800 uppercase tracking-wider block">Hasil Skrining Tanda Vital (Vital Signs)</span>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-semibold">
                <div class="bg-white p-2 rounded-xl text-center border border-teal-100">
                    <span class="text-[10px] text-slate-400 block">Tekanan Darah</span>
                    <span class="font-bold text-slate-800" id="modalTD">-</span>
                </div>
                <div class="bg-white p-2 rounded-xl text-center border border-teal-100">
                    <span class="text-[10px] text-slate-400 block">Suhu Tubuh</span>
                    <span class="font-bold text-slate-800" id="modalSuhu">-</span>
                </div>
                <div class="bg-white p-2 rounded-xl text-center border border-teal-100">
                    <span class="text-[10px] text-slate-400 block">Denyut Nadi</span>
                    <span class="font-bold text-slate-800" id="modalNadi">-</span>
                </div>
                <div class="bg-white p-2 rounded-xl text-center border border-teal-100">
                    <span class="text-[10px] text-slate-400 block">BB / TB</span>
                    <span class="font-bold text-slate-800" id="modalBBTB">-</span>
                </div>
            </div>
        </div>

        <!-- Diagnosa & Tindakan Medis -->
        <div class="space-y-2 text-xs">
            <div>
                <span class="text-[10px] font-extrabold text-slate-500 uppercase block">Diagnosa Dokter:</span>
                <p class="p-3 bg-amber-50 text-amber-950 font-bold rounded-2xl border border-amber-200/80" id="modalDiagnosis">-</p>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-slate-500 uppercase block">Tindakan / Terapi Medis:</span>
                <p class="p-3 bg-slate-50 text-slate-800 font-medium rounded-2xl border border-slate-200/80" id="modalTindakan">-</p>
            </div>
        </div>

        <!-- Rincian Obat Resep -->
        <div class="space-y-2 text-xs">
            <h4 class="font-extrabold text-slate-800 text-xs">Daftar Obat Resep & Aturan Pakai</h4>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-[9px] uppercase border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3">Nama & Jenis Obat</th>
                            <th class="py-2.5 px-3 text-center w-20">Jumlah</th>
                            <th class="py-2.5 px-3">Dosis / Aturan Pakai</th>
                        </tr>
                    </thead>
                    <tbody id="modalResepTableBody" class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400">Memuat data obat...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" onclick="closePasienRekamMedisModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openPasienRekamMedisModal(idPemeriksaan) {
        document.getElementById('modalTglPemeriksaan').textContent = 'Memuat data...';
        document.getElementById('modalPoli').textContent = '-';
        document.getElementById('modalKodeAntrean').textContent = '-';
        document.getElementById('modalDokter').textContent = '-';
        document.getElementById('modalTD').textContent = '-';
        document.getElementById('modalSuhu').textContent = '-';
        document.getElementById('modalNadi').textContent = '-';
        document.getElementById('modalBBTB').textContent = '-';
        document.getElementById('modalDiagnosis').textContent = '-';
        document.getElementById('modalTindakan').textContent = '-';
        
        const tbody = document.getElementById('modalResepTableBody');
        tbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-slate-400">Memuat data obat...</td></tr>';

        document.getElementById('modalPasienRekamMedis').classList.remove('hidden');

        const url = "{{ route('pasien.rekam-medis.show', ':id') }}".replace(':id', idPemeriksaan);
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const p = data.pemeriksaan;
                    document.getElementById('modalTglPemeriksaan').textContent = `Tgl Pemeriksaan: ${p.tanggal_pemeriksaan}`;
                    document.getElementById('modalPoli').textContent = p.nama_poli || 'Poliklinik Umum';
                    document.getElementById('modalKodeAntrean').textContent = `No. Antrean: ${p.kode_antrean || '#' + p.nomor_antrean} (${p.jenis_pasien})`;
                    document.getElementById('modalDokter').textContent = `Dr. ${p.nama_dokter || 'Dokter Bertugas'}`;
                    
                    document.getElementById('modalTD').textContent = p.tekanan_darah ? `${p.tekanan_darah} mmHg` : '-';
                    document.getElementById('modalSuhu').textContent = p.suhu_tubuh ? `${p.suhu_tubuh} °C` : '-';
                    document.getElementById('modalNadi').textContent = p.nadi ? `${p.nadi} x/menit` : '-';
                    document.getElementById('modalBBTB').textContent = (p.berat_badan || p.tinggi_badan) ? `${p.berat_badan || 0} kg / ${p.tinggi_badan || 0} cm` : '-';
                    
                    document.getElementById('modalDiagnosis').textContent = p.diagnosis_penyakit || '-';
                    document.getElementById('modalTindakan').textContent = p.tindakan_medis || '-';

                    tbody.innerHTML = '';
                    if (data.detail_resep && data.detail_resep.length > 0) {
                        data.detail_resep.forEach(item => {
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
                                </tr>
                            `;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-slate-400 italic">Tidak ada resep obat (Terapi Non-Farmakologi).</td></tr>';
                    }
                } else {
                    alert(data.message || 'Gagal memuat detail rekam medis.');
                    closePasienRekamMedisModal();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi sistem.');
                closePasienRekamMedisModal();
            });
    }

    function closePasienRekamMedisModal() {
        document.getElementById('modalPasienRekamMedis').classList.add('hidden');
    }
</script>
@endsection
