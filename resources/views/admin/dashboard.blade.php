@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
@php
    $user = Auth::user();
    $userRoles = $user->roles->pluck('name')->toArray();
    if (empty($userRoles)) {
        $userRoles = [$user->peran];
    }
@endphp

<div class="space-y-8">
    <!-- Page Header Banner (Professional Light/Dark Teal Theme) -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-teal-700 via-teal-600 to-blue-700 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-96 h-96 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/20 text-white text-xs font-extrabold mb-3 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-teal-300 animate-pulse"></span>
                    Role Aktif Sesi: {{ $activeRole }}
                </span>
                <h1 class="text-3xl font-black tracking-tight">Selamat Datang, {{ $user->nama_lengkap }}!</h1>
                <p class="text-sm text-teal-100 mt-1 max-w-2xl">Panel utama pengelolaan data pelayanan medis, antrean, rekam medis, dan operasional Klinik Nomor Satu Padang.</p>
            </div>

            <!-- Contextual Quick Action Button -->
            @if($activeRole === 'Superadmin')
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>+ Tambah User Pegawai</span>
                </a>
            @elseif($activeRole === 'Resepsionis')
                <a href="{{ route('admin.patients.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>+ Pendaftaran Pasien Baru</span>
                </a>
            @elseif($activeRole === 'Perawat')
                <a href="{{ route('perawat.pemeriksaan.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span>Skrining Vital Sign</span>
                </a>
            @elseif($activeRole === 'Dokter')
                <a href="{{ route('dokter.pemeriksaan.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>🩺 Ruang Periksa Dokter</span>
                </a>
            @elseif($activeRole === 'Apoteker')
                <a href="{{ route('apoteker.resep.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span>Antrean Resep Farmasi</span>
                </a>
            @elseif($activeRole === 'Kasir')
                <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Kasir & Pembayaran</span>
                </a>
            @elseif($activeRole === 'Pimpinan')
                <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-teal-800 hover:bg-teal-50 rounded-2xl font-extrabold text-sm shadow-lg shadow-black/10 hover:-translate-y-0.5 transition-all shrink-0">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Laporan Eksekutif</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Interactive Switch Role Widget (Jika User Memiliki Lebih dari 1 Role) -->
    @if(count($userRoles) > 1)
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        <span>Alih Hak Akses (Switch Active Role)</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih role aktif di bawah. Sistem akan otomatis mengarahkan ke **Dashboard** dan menyesuaikan menu navigasi sidebar & menu cepat!</p>
                </div>

                <form action="{{ route('admin.switch-role') }}" method="POST" class="flex flex-wrap items-center gap-2">
                    @csrf
                    @foreach($userRoles as $roleName)
                        <button type="submit" name="active_role" value="{{ $roleName }}" class="px-4 py-2 rounded-2xl text-xs font-extrabold transition-all border shadow-xs cursor-pointer flex items-center gap-1.5 {{ $activeRole === $roleName ? 'bg-teal-600 text-white border-teal-600 shadow-teal-600/30' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            @if($activeRole === $roleName)
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                            <span>{{ $roleName }}</span>
                        </button>
                    @endforeach
                </form>
            </div>
        </div>
    @endif

    <!-- Dynamic Metrics Cards Grid (Spesifik per Role & Bulan Berjalan) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($cards as $c)
            @php
                $bgBadge = 'bg-' . $c['color'] . '-50';
                $textBadge = 'text-' . $c['color'] . '-600';
                $borderBadge = 'border-' . $c['color'] . '-100';
            @endphp
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition-all">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $c['title'] }}</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-mono tracking-tight">{{ $c['value'] }}</h3>
                    <p class="text-xs font-bold mt-2 text-slate-600">{{ $c['sub'] }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Quick Shortcuts Navigation Section (Menu Cepat Dinamis Sesuai Role & Sidebar) -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-900">Akses Pintar Modul (Menu Cepat {{ $activeRole }})</h3>
            <span class="text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-100">Diselaraskan dengan Sidebar</span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @if($activeRole === 'Superadmin')
                <a href="{{ route('admin.users.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-teal-700">Manajemen User (RBAC)</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Kelola pengguna & hak akses Spatie</p>
                        </div>
                    </div>
                </a>
            @elseif($activeRole === 'Resepsionis')
                <a href="{{ route('admin.patients.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-teal-700">Pendaftaran Pasien</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Registrasi & kelola identitas pasien</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.poli.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0h-4"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-teal-700">Master Poliklinik</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Kelola data master spesialisasi poli</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.schedules.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-teal-700">Kelola Jadwal Dokter</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Atur jam & kuota harian dokter</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.antrean-kunjungan.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-teal-50 border border-slate-200 hover:border-teal-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-teal-700">Nomor Antrean Kunjungan</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Panggilan & pemantauan antrean</p>
                        </div>
                    </div>
                </a>
            @elseif($activeRole === 'Perawat')
                <a href="{{ route('perawat.pemeriksaan.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-red-50 border border-slate-200 hover:border-red-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-red-700">Skrining Vital Sign (Perawat)</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Input tanda vital & keluhan awal pasien</p>
                        </div>
                    </div>
                </a>
            @elseif($activeRole === 'Dokter')
                <a href="{{ route('dokter.pemeriksaan.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-purple-50 border border-slate-200 hover:border-purple-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-purple-700">Ruang Periksa Dokter (Poli)</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Pemeriksaan E-Rekam Medis & E-Resep</p>
                        </div>
                    </div>
                </a>
            @elseif($activeRole === 'Apoteker')
                <a href="{{ route('admin.obat.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.02 1.02a2 2 0 00.586 3.414l2.12.707a12.003 12.003 0 008.43 0l2.12-.707a2 2 0 00.586-3.414l-1.02-1.02z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-amber-700">Inventaris & Stok Obat</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Kelola data obat & restock stok masuk</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('apoteker.resep.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-orange-50 border border-slate-200 hover:border-orange-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-orange-700">Antrean Resep Farmasi</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Penyiapan & dispensasi obat pasien</p>
                        </div>
                    </div>
                </a>
            @elseif($activeRole === 'Kasir')
                <a href="{{ route('admin.pembayaran.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-emerald-700">Kasir & Pembayaran</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Proses pembayaran tagihan pasien & kuitansi</p>
                        </div>
                    </div>
                </a>
            @elseif($activeRole === 'Pimpinan')
                <a href="{{ route('admin.laporan.index') }}" class="p-5 rounded-2xl bg-slate-50 hover:bg-cyan-50 border border-slate-200 hover:border-cyan-300 transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-600 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-cyan-700">Laporan Operasional & Keuangan</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi operasional & keuangan klinik</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
