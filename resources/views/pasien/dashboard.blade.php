@extends('layouts.pasien')

@section('title', 'Dashboard Pasien')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner Pasien -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-teal-700 via-teal-600 to-blue-700 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10">
            <span class="px-3.5 py-1 rounded-full bg-white/20 text-white text-xs font-extrabold mb-3 inline-block backdrop-blur-sm">
                Status Kepesertaan: {{ $pasien->jenis_pasien }}
            </span>
            <h1 class="text-3xl font-black tracking-tight">Selamat Datang, {{ $pasien->nama_lengkap }}!</h1>
            <p class="text-sm text-teal-100 mt-1 max-w-2xl">ID Pasien Anda: <strong class="bg-white/20 px-2 py-0.5 rounded font-mono">{{ $pasien->id_pasien }}</strong>. Gunakan portal ini untuk melihat jadwal dokter dan mengambil nomor antrean online.</p>
        </div>
    </div>

    <!-- Session Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 text-xs font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Cards Layout -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Informasi Identitas Pasien -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-base">Profil Pasien</h3>
            </div>
            
            <div class="space-y-2 text-xs text-slate-600 font-medium">
                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                    <span>NIK:</span>
                    <span class="font-bold text-slate-800">{{ $pasien->nik }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-1.5">
                    <span>No. Telepon:</span>
                    <span class="font-bold text-slate-800">{{ $pasien->nomor_telepon }}</span>
                </div>
                @if($pasien->jenis_pasien === 'BPJS')
                    <div class="flex justify-between border-b border-slate-100 pb-1.5">
                        <span>No. BPJS:</span>
                        <span class="font-bold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded">{{ $pasien->no_bpjs }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Gol. Darah:</span>
                    <span class="font-bold text-slate-800">{{ $pasien->golongan_darah }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Status Antrean Aktif Pasien (Dengan Tombol Cetak & Batalkan) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-base">Antrean Aktif Anda</h3>
            </div>

            @if($antreanAktif && in_array($antreanAktif->status_antrean, ['Menunggu', 'Dipanggil', 'Diperiksa']))
                <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200 text-center space-y-2">
                    <span class="text-xs font-bold text-teal-700 uppercase tracking-wider block">Tiket Antrean Digital</span>
                    <p class="text-4xl font-black text-teal-800 tracking-tight font-mono">{{ $antreanAktif->kode_antrean ?? ('#' . $antreanAktif->nomor_antrean) }}</p>
                    <span class="inline-block text-[11px] font-bold px-2.5 py-0.5 bg-teal-200 text-teal-800 rounded-full">Status: {{ $antreanAktif->status_antrean }}</span>
                    <p class="text-[11px] text-slate-600 font-semibold">Tgl Kunjungan: {{ \Carbon\Carbon::parse($antreanAktif->tanggal_antrean)->translatedFormat('d M Y') }}</p>
                    
                    <!-- Action Buttons: Cetak Tiket & Batalkan Antrean -->
                    <div class="pt-3 border-t border-teal-200/80 flex gap-2">
                        <a href="{{ route('pasien.antrean.print', $antreanAktif->id_antrean) }}" target="_blank" class="flex-1 py-2 px-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-extrabold transition-all flex items-center justify-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak Tiket</span>
                        </a>

                        @php
                            $isToday = ($antreanAktif->tanggal_antrean === date('Y-m-d'));
                            $canCancel = ($antreanAktif->status_antrean === 'Menunggu') && !$isToday;
                        @endphp

                        @if($canCancel)
                            <form action="{{ route('pasien.antrean.cancel', $antreanAktif->id_antrean) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan nomor antrean ini? Sisa kuota akan dikembalikan dan Anda dapat mengambil jadwal baru.')">
                                @csrf
                                <button type="submit" class="w-full py-2 px-3 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl text-xs font-bold transition-all">
                                    Batalkan
                                </button>
                            </form>
                        @elseif($antreanAktif->status_antrean === 'Dipanggil')
                            <div class="flex-1 py-2 px-3 bg-amber-100 text-amber-800 rounded-xl text-[11px] font-bold text-center border border-amber-200 flex items-center justify-center gap-1" title="Antrean Anda saat ini sedang dipanggil oleh resepsionis">
                                <span>📢 Sedang Dipanggil</span>
                            </div>
                        @elseif($isToday)
                            <div class="flex-1 py-2 px-3 bg-slate-100 text-slate-500 rounded-xl text-[10px] font-semibold text-center border border-slate-200 flex items-center justify-center gap-1" title="Pembatalan online tidak berlaku pada hari H kunjungan">
                                <span>🔒 Hari H (Loket Resepsionis)</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-4 rounded-2xl bg-slate-50 text-center text-xs text-slate-500 font-medium">
                    Belum ada antrean aktif untuk hari ini.
                </div>
            @endif
        </div>

        <!-- Card 3: Fitur Cepat -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-base">Aksi Cepat</h3>
            </div>

            <div class="space-y-2">
                <a href="{{ route('pasien.antrean.create') }}" class="block w-full py-3 px-4 bg-teal-600 text-white font-bold rounded-2xl text-xs hover:bg-teal-700 transition-all text-center shadow-md shadow-teal-600/20">
                    + Ambil Nomor Antrean Online
                </a>
                <a href="{{ route('pasien.rekam-medis.index') }}" class="block w-full py-3 px-4 bg-slate-100 text-slate-700 font-bold rounded-2xl text-xs hover:bg-slate-200 transition-all text-center border border-slate-200">
                    📄 Lihat Riwayat Rekam Medis Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
