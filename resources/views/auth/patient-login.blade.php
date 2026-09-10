@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 relative overflow-hidden py-12 px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
    
    <!-- Background Blobs -->
    <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-teal-200/40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 -right-20 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl"></div>
    </div>

    <!-- Header Logo & Title -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center relative z-10 mb-6">
        <a href="/" class="inline-flex items-center gap-3 group mb-3">
            <img src="{{ asset('storage/img/logo_klinik.png') }}" alt="Logo Klinik Nomor Satu" class="h-12 w-auto drop-shadow-sm object-contain" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Klinik+Nomor+Satu&background=0D9488&color=fff&rounded=true';">
            <span class="font-extrabold text-2xl text-slate-800 tracking-tight group-hover:text-teal-600 transition-colors">Klinik <span class="text-teal-600">Nomor Satu</span></span>
        </a>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Portal Pasien</h2>
        <p class="mt-2 text-sm text-slate-600">Masuk untuk mengakses layanan kesehatan dan antrean online Anda.</p>
    </div>

    <!-- Login Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white/90 backdrop-blur-md py-8 px-6 shadow-xl border border-slate-200/80 rounded-3xl sm:px-10">
            
            <!-- Explanatory Narrative Box / Informasi Petunjuk Login -->
            <div class="mb-6 p-4 rounded-2xl bg-teal-50/80 border border-teal-200/70 text-slate-700 text-xs leading-relaxed space-y-2">
                <div class="flex items-center gap-2 font-bold text-teal-800 text-sm">
                    <svg class="w-5 h-5 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Panduan Login Pasien</span>
                </div>
                <p>Silakan gunakan identitas Anda untuk masuk ke sistem:</p>
                <ul class="list-disc list-inside space-y-1 pl-1 text-slate-600 font-medium">
                    <li><strong class="text-slate-800">Pasien Umum/Mandiri:</strong> Gunakan <span class="text-teal-700 font-bold">NIK (Nomor Induk Kependudukan)</span> Anda.</li>
                    <li><strong class="text-slate-800">Pasien BPJS:</strong> Gunakan <span class="text-blue-700 font-bold">NIK</span> atau <span class="text-blue-700 font-bold">Nomor Kartu BPJS</span> Anda.</li>
                </ul>
            </div>

            <!-- Session Alerts -->
            @if(session('info'))
                <div class="mb-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form action="{{ route('login.pasien.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- NIK / No. BPJS / Username Field -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 mb-1">
                        NIK / No. BPJS / ID Pasien <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan 16 digit NIK atau No. BPJS Anda" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('username') border-red-500 @enderror">
                    </div>
                    @error('username')
                        <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-slate-700">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <a href="{{ route('pasien.password.reset') }}" class="text-[11px] font-bold text-teal-600 hover:underline">
                            Lupa Kata Sandi?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" placeholder="••••••••" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-6 bg-teal-600 text-white rounded-2xl font-bold text-base shadow-lg shadow-teal-500/30 hover:bg-teal-700 hover:shadow-teal-500/50 hover:-translate-y-0.5 transition-all duration-300">
                    Masuk ke Portal Pasien
                </button>

                <!-- Divider & Sign Up Link -->
                <div class="pt-4 border-t border-slate-200/80 text-center space-y-3">
                    <p class="text-sm text-slate-600">
                        Belum memiliki akun pasien? 
                        <a href="{{ route('register.pasien') }}" class="font-bold text-teal-600 hover:underline">
                            Daftar Pasien Baru
                        </a>
                    </p>
                    <p class="text-xs text-slate-400">
                        Anda Petugas Klinik? <a href="/admin/login" class="text-slate-600 hover:text-slate-800 font-semibold underline">Masuk Portal Pegawai</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
