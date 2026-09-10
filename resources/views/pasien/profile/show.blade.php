@extends('layouts.pasien')

@section('title', 'Profil Lengkap Pasien')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Lengkap Pasien</h1>
            <p class="text-sm text-slate-500 mt-1">Informasi data rekam medis dan kepesertaan terdaftar Anda.</p>
        </div>
        <a href="{{ route('pasien.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Banner Header Identitas -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-teal-700 via-teal-600 to-blue-700 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur-md text-white font-black flex items-center justify-center text-3xl shrink-0 border border-white/30 shadow-lg">
                {{ strtoupper(substr($pasien->nama_lengkap ?? 'P', 0, 1)) }}
            </div>
            <div>
                <span class="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-extrabold mb-2 inline-block border border-white/20">
                    Kategori: {{ $pasien->jenis_pasien }}
                </span>
                <h2 class="text-2xl font-black tracking-tight">{{ $pasien->nama_lengkap }}</h2>
                <p class="text-xs text-teal-100 mt-1 font-mono font-bold">ID Pasien: {{ $pasien->id_pasien }}</p>
            </div>
        </div>

        <a href="{{ route('pasien.profile.password') }}" class="px-5 py-3 rounded-2xl bg-white text-teal-800 hover:bg-teal-50 font-extrabold text-xs transition-all shadow-md shrink-0 text-center">
            Ganti Kata Sandi
        </a>
    </div>

    <!-- Detail Data Pasien Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <h3 class="text-base font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Rincian Data Identitas KTP & Rekam Medis</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">NIK (KTP)</span>
                    <span class="font-extrabold text-slate-900 font-mono text-base">{{ $pasien->nik }}</span>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Tempat & Tanggal Lahir</span>
                    <span class="font-bold text-slate-800">{{ $pasien->tempat_lahir }}, {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Jenis Kelamin</span>
                    <span class="font-bold text-slate-800">{{ $pasien->jenis_kelamin }}</span>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Golongan Darah</span>
                    <span class="font-extrabold text-teal-700 bg-teal-50 px-2 py-0.5 rounded border border-teal-100">{{ $pasien->golongan_darah }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Status Kepesertaan Pasien</span>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold mt-1 {{ $pasien->jenis_pasien === 'BPJS' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-800 border border-slate-200' }}">
                        {{ $pasien->jenis_pasien }}
                    </span>
                    @if($pasien->jenis_pasien === 'BPJS')
                        <p class="text-xs font-mono font-bold text-blue-700 mt-1">No. Kartu BPJS: {{ $pasien->no_bpjs }}</p>
                    @endif
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nomor Telepon / WhatsApp</span>
                    <span class="font-bold text-slate-800">{{ $pasien->nomor_telepon }}</span>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Pekerjaan Utama</span>
                    <span class="font-bold text-slate-800">{{ $pasien->pekerjaan }}</span>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</span>
                    <span class="font-bold text-slate-800 leading-relaxed">{{ $pasien->alamat_lengkap }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
