@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    html { scroll-behavior: smooth; }
</style>

<div class="min-h-screen relative overflow-hidden bg-slate-50 font-sans text-slate-900 flex flex-col">
    
    <!-- Animated Background Blobs -->
    <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 -left-4 w-72 h-72 md:w-96 md:h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 md:w-96 md:h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 md:w-96 md:h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Sticky Navigation Bar -->
    <nav class="sticky top-0 z-50 w-full px-6 py-4 transition-all duration-300 backdrop-blur-lg bg-white/80 border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo Section -->
            <a href="#hero" class="flex items-center gap-3 group">
                <img src="{{ asset('storage/img/icon.png') }}" alt="Logo Klinik Nomor Satu" class="h-10 w-auto drop-shadow-sm object-contain" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Klinik+Nomor+Satu&background=0D9488&color=fff&rounded=true';">
                <span class="font-black text-xl md:text-2xl text-slate-900 tracking-tight">Klinik <span class="text-teal-600">Nomor Satu</span></span>
            </a>
            
            <!-- Desktop Nav Menu Links -->
            <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-600">
                <a href="#hero" class="hover:text-teal-600 transition-colors">Beranda</a>
                <a href="#layanan" class="hover:text-teal-600 transition-colors">Layanan Poli</a>
                <a href="#jadwal" class="hover:text-teal-600 transition-colors">Jadwal Praktik</a>
                <a href="#kontak" class="hover:text-teal-600 transition-colors">Kontak & Lokasi</a>
            </div>

            <!-- Login Access Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all border border-slate-200">
                    Portal Pasien
                </a>
                <a href="{{ route('login.pegawai') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    Portal Pegawai
                </a>
            </div>
        </div>
    </nav>

    <!-- Section 1: Hero Banner -->
    <section id="hero" class="flex-grow flex items-center justify-center relative z-10 px-6 py-16 md:py-24">
        <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
            
            <!-- Badge Status -->
            <div class="animate-fade-in-up inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/90 border border-teal-200 shadow-sm text-teal-700 text-xs font-bold mb-8 backdrop-blur-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
                </span>
                Sistem Informasi & Rekam Medis Digital Klinik Nomor Satu Padang
            </div>
            
            <!-- Heading -->
            <h1 class="animate-fade-in-up delay-100 text-4xl sm:text-5xl md:text-6xl font-black text-slate-900 tracking-tight leading-[1.15] mb-6">
                Pelayanan Medis <br class="hidden sm:block">
                <span class="relative whitespace-nowrap">
                    <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-emerald-600 to-blue-600">Terbaik, Cepat & Terpercaya</span>
                    <svg class="absolute w-full h-3 -bottom-1 left-0 -z-10 text-teal-200/60" viewBox="0 0 100 10" preserveAspectRatio="none">
                        <path d="M0 5 Q 50 10 100 5" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="animate-fade-in-up delay-200 text-base md:text-lg text-slate-600 max-w-2xl mb-10 leading-relaxed font-medium">
                Nikmati kemudahan pendaftaran antrean online, konsultasi poliklinik, e-resep farmasi, serta akses riwayat rekam medis digital dalam satu genggaman.
            </p>

            <!-- Action Buttons -->
            <div class="animate-fade-in-up delay-300 flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="{{ route('pasien.antrean.create') }}" class="px-8 py-4 bg-teal-600 text-white rounded-2xl font-black text-base shadow-lg shadow-teal-600/30 hover:bg-teal-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Ambil Antrean Online</span>
                </a>
                <a href="#jadwal" class="px-8 py-4 bg-white text-slate-700 rounded-2xl font-extrabold text-base border border-slate-200 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Cek Jadwal Dokter</span>
                </a>
            </div>

            <!-- Trust Indicators -->
            <div class="animate-fade-in-up delay-400 mt-14 pt-8 border-t border-slate-200/80 w-full max-w-3xl flex flex-wrap justify-center gap-8 md:gap-16 text-slate-600">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-left">
                        <div class="text-xs font-black text-slate-900">Tanpa Antrean Panjang</div>
                        <div class="text-[11px] text-slate-500">Nomor Antrean Real-time</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="text-left">
                        <div class="text-xs font-black text-slate-900">BPJS & Pasien Umum</div>
                        <div class="text-[11px] text-slate-500">Layanan Terintegrasi</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="text-left">
                        <div class="text-xs font-black text-slate-900">E-Rekam Medis Digital</div>
                        <div class="text-[11px] text-slate-500">Riwayat Medis Pasien Terjaga</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Layanan Unggulan Poliklinik -->
    <section id="layanan" class="py-16 bg-white border-y border-slate-200/80 relative z-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="px-3 py-1 bg-teal-50 text-teal-700 font-extrabold text-xs rounded-full border border-teal-100 uppercase tracking-wider">Layanan Unggulan Medis</span>
                <h2 class="text-3xl font-black text-slate-900 mt-3 tracking-tight">Poliklinik Spesialis & Kesehatan Utama</h2>
                <p class="text-xs text-slate-500 mt-2">Klinik Nomor Satu menyediakan pelayanan dokter profesional yang siap memberikan penanganan medis komprehensif.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($polis as $poli)
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 hover:border-teal-300 hover:shadow-md transition-all space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-bold shadow-md shadow-teal-600/20">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0h-4"/></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">{{ $poli->nama_poli }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium">
                            {{ $poli->deskripsi ?? 'Pelayanan konsultasi dan pemeriksaan medis terpadu oleh tim dokter berpengalaman.' }}
                        </p>
                        <a href="#jadwal" onclick="filterJadwal('{{ $poli->id_poli }}', '{{ $poli->nama_poli }}')" class="pt-2 flex items-center justify-between text-xs font-bold text-teal-700 hover:text-teal-900 group cursor-pointer transition-colors">
                            <span>Layanan Aktif</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-400 text-xs italic">
                        Belum ada data poliklinik terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Section 3: Jadwal Praktik Dokter (Dinamis dari Database) -->
    <section id="jadwal" class="py-16 bg-slate-50 relative z-10">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            <div class="text-center max-w-2xl mx-auto">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 font-extrabold text-xs rounded-full border border-blue-100 uppercase tracking-wider">Informasi Real-Time</span>
                <h2 class="text-3xl font-black text-slate-900 mt-3 tracking-tight">Jadwal Praktik Dokter Klinik</h2>
                <p class="text-xs text-slate-500 mt-2">Daftar jadwal dokter aktif yang dapat Anda pilih saat mendaftar antrean berobat online.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden relative">
                
                <div id="filter-indicator" class="hidden mx-6 mt-6 p-3 bg-teal-50/80 border border-teal-200 rounded-xl flex items-center justify-between">
                    <span class="text-sm font-bold text-teal-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Menampilkan Jadwal: <span id="filter-nama-poli" class="text-teal-900 font-black"></span>
                    </span>
                    <button onclick="resetFilter()" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg border shadow-sm transition-colors">
                        Tampilkan Semua
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">Hari Praktik</th>
                                <th class="py-3 px-4">Dokter Spesialis</th>
                                <th class="py-3 px-4">Poliklinik</th>
                                <th class="py-3 px-4">Jam Operasional</th>
                                <th class="py-3 px-4 text-center">Kuota Harian</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium" id="jadwal-tbody">
                            @forelse($jadwalDokters as $idx => $j)
                                <tr class="hover:bg-slate-50 transition-all jadwal-row" data-poli-id="{{ $j->id_poli ?? 0 }}">
                                    <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-800">
                                        <span class="px-2.5 py-1 rounded-lg bg-teal-50 text-teal-700 border border-teal-200/60 font-extrabold">
                                            {{ $j->hari }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-extrabold text-slate-900 text-sm">Dr. {{ $j->nama_dokter }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-700">
                                        {{ $j->nama_poli ?? 'Poli Umum' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono font-extrabold text-teal-800">
                                        {{ $j->kuota_harian ?? 30 }} Pasien
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="{{ route('pasien.antrean.create') }}" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-xl text-xs transition-all shadow-xs inline-flex items-center gap-1">
                                            <span>Ambil Antrean</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 italic">Belum ada jadwal dokter aktif yang dipublikasikan.</td>
                                </tr>
                            @endforelse
                            <tr id="empty-filter-row" class="hidden">
                                <td colspan="7" class="py-8 text-center text-slate-400 italic">Tidak ada jadwal aktif untuk poliklinik ini.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Kontak & Lokasi Klinik (Lengkap dengan Google Maps & Detail Alamat) -->
    <section id="kontak" class="py-16 bg-white border-t border-slate-200/80 relative z-10">
        <div class="max-w-7xl mx-auto px-6 space-y-10">
            <div class="text-center max-w-2xl mx-auto">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-extrabold text-xs rounded-full border border-emerald-100 uppercase tracking-wider">Lokasi & Kontak Resmi</span>
                <h2 class="text-3xl font-black text-slate-900 mt-3 tracking-tight">Kunjungan & Layanan Informasi</h2>
                <p class="text-xs text-slate-500 mt-2">Kunjungi lokasi resmi Klinik Nomor Satu Padang atau hubungi kontak layanan pelanggan kami.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Informasi Kontak Card (Detail dari Gambar Pengguna) -->
                <div class="lg:col-span-5 bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
                        <img src="{{ asset('storage/img/icon.png') }}" alt="Logo" class="h-10 w-auto">
                        <div>
                            <h3 class="font-black text-slate-900 text-lg">KLINIK NOMOR SATU</h3>
                            <p class="text-xs text-slate-500 font-medium">Pelayanan Medis Utama Kota Padang</p>
                        </div>
                    </div>

                    <!-- Item 1: Alamat Lengkap -->
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Alamat Lengkap</div>
                            <p class="text-sm font-bold text-slate-800 leading-relaxed mt-0.5">
                                Jl. By Pass No.Km.10, Kalumbuk, Kec. Kuranji, Kota Padang, Sumatera Barat 25171
                            </p>
                        </div>
                    </div>

                    <!-- Item 2: Jam Operasional -->
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Jam Operasional Klinik</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-extrabold text-xs">Buka</span>
                                <span class="text-sm font-extrabold text-slate-800">• Tutup pukul 20.00 WIB</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-1">Setiap Hari (Senin - Minggu 08.00 - 20.00 WIB)</p>
                        </div>
                    </div>

                    <!-- Item 3: Telepon / WhatsApp -->
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h328a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Telepon & WhatsApp Resmi</div>
                            <a href="tel:08116680474" class="text-base font-black text-teal-700 hover:text-teal-800 transition-colors block mt-0.5 font-mono">
                                0811-6680-474
                            </a>
                        </div>
                    </div>

                    <!-- Tombol Direct Google Maps -->
                    <div class="pt-2">
                        <a href="https://www.google.com/maps/place/Klinik+Nomor+Satu/@-0.9153096,100.3969714,17z/data=!4m6!3m5!1s0x2fd4b85f2613c983:0x51fbc3c16cc0e166!8m2!3d-0.9153096!4d100.3969714!16s%2Fg%2F11c2l80g35?entry=ttu&g_ep=EgoyMDI2MDcyOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer" class="w-full px-5 py-3 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-2xl text-xs transition-all shadow-md shadow-teal-600/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>Buka di Google Maps Aplikasi</span>
                        </a>
                    </div>
                </div>

                <!-- Google Maps Interactive Embed -->
                <div class="lg:col-span-7 rounded-3xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 h-[420px]">
                    <iframe 
                        src="https://maps.google.com/maps?q=-0.9153096,100.3969714&z=17&output=embed" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200/80 bg-slate-900 text-slate-300 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/img/icon.png') }}" alt="Logo Klinik" class="h-8 w-auto">
                <div>
                    <div class="font-extrabold text-white text-base">Klinik Nomor Satu</div>
                    <div class="text-xs text-slate-400">Jl. By Pass No.Km.10, Kalumbuk, Kuranji, Kota Padang</div>
                </div>
            </div>

            <p class="text-xs text-slate-400 font-medium">
                &copy; {{ date('Y') }} Klinik Nomor Satu Padang. Seluruh Hak Cipta Dilindungi.
            </p>

            <div class="flex items-center gap-4 text-xs font-bold text-slate-300">
                <a href="{{ route('login') }}" class="hover:text-teal-400 transition-colors">Portal Pasien</a>
                <span>•</span>
                <a href="{{ route('login.pegawai') }}" class="hover:text-teal-400 transition-colors">Portal Pegawai</a>
            </div>
        </div>
    </footer>
</div>
    <script>
        function filterJadwal(poliId, poliName) {
            // Tampilkan indikator filter
            document.getElementById('filter-indicator').classList.remove('hidden');
            document.getElementById('filter-nama-poli').textContent = poliName;

            // Saring baris jadwal
            const rows = document.querySelectorAll('.jadwal-row');
            let hasVisibleRows = false;
            
            rows.forEach(row => {
                if (row.getAttribute('data-poli-id') === String(poliId)) {
                    row.style.display = '';
                    hasVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Tampilkan pesan kosong jika tidak ada jadwal di poli ini
            if (hasVisibleRows) {
                document.getElementById('empty-filter-row').classList.add('hidden');
            } else {
                document.getElementById('empty-filter-row').classList.remove('hidden');
            }
        }

        function resetFilter() {
            // Sembunyikan indikator filter
            document.getElementById('filter-indicator').classList.add('hidden');
            document.getElementById('empty-filter-row').classList.add('hidden');
            
            // Tampilkan semua baris jadwal
            const rows = document.querySelectorAll('.jadwal-row');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
    </script>
@endsection
