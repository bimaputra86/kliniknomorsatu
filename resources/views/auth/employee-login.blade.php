@extends('layouts.app')

@section('content')
<style>
    /* Custom Keyframes for Entrance Animations */
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

    /* Blob animation for background */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 { animation-delay: 2s; }
</style>

<div class="min-h-screen bg-slate-50 text-slate-900 flex flex-col lg:flex-row relative overflow-hidden font-sans">
    
    <!-- Animated Background Blobs (Sama dengan Halaman Utama) -->
    <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 -left-4 w-72 h-72 md:w-96 md:h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 md:w-96 md:h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Left Decorative Panel (Warna Terang & Gradasi Halaman Utama) -->
    <div class="lg:w-1/2 p-8 lg:p-16 flex flex-col justify-between relative z-10 bg-white/60 backdrop-blur-md border-b lg:border-b-0 lg:border-r border-slate-200/80">
        <div>
            <!-- Brand Logo -->
            <a href="/" class="inline-flex items-center gap-3 group">
                <img src="{{ asset('storage/img/icon.png') }}" alt="Logo Klinik" class="h-10 w-auto drop-shadow-sm">
                <div>
                    <span class="font-extrabold text-xl text-slate-900 tracking-tight block">Klinik <span class="text-teal-600">Nomor Satu</span></span>
                    <span class="text-[10px] text-teal-700 font-mono uppercase tracking-widest block -mt-1 font-bold">Portal Staf Pegawai</span>
                </div>
            </a>
        </div>

        <div class="my-12 lg:my-0 max-w-lg">
            <div class="animate-fade-in-up inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-teal-100 shadow-sm text-teal-700 text-sm font-medium mb-6 backdrop-blur-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
                </span>
                Sistem Operasional Internal
            </div>

            <h1 class="animate-fade-in-up delay-100 text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                Pelayanan Medis <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-blue-600">Terpadu & Integratif</span>
            </h1>
            <p class="animate-fade-in-up delay-200 text-slate-600 text-sm sm:text-base leading-relaxed">
                Portal akses terencana bagi Dokter, Perawat, Apoteker, Kasir, Resepsionis, dan Pimpinan Klinik dalam mengelola rekam medis dan transaksi secara real-time.
            </p>
        </div>

        <!-- Footer Note -->
        <div class="text-xs text-slate-500 font-medium">
            &copy; {{ date('Y') }} Klinik Nomor Satu. Hak Cipta Dilindungi.
        </div>
    </div>

    <!-- Right Form Section (Warna Terang Modern) -->
    <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative z-10 bg-slate-50">
        <div class="w-full max-w-md space-y-8 animate-fade-in-up delay-200">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Masuk Akun Staf</h2>
                <p class="text-sm text-slate-600 mt-1">Masukkan kredensial akun pegawai Anda untuk mengakses dashboard.</p>
            </div>

            <!-- Session Alert -->
            @if(session('info'))
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-semibold">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.pegawai.post') }}" method="POST" class="space-y-5 bg-white/90 backdrop-blur-md p-8 rounded-3xl border border-slate-200 shadow-xl">
                @csrf

                <!-- Username / ID Pegawai -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Username / ID Pegawai <span class="text-teal-600">*</span>
                    </label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Contoh: superadmin / DKT-001" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('username') border-red-500 @enderror">
                    @error('username')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kata Sandi <span class="text-teal-600">*</span>
                    </label>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkbox -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:-translate-y-0.5 transition-all duration-300">
                    Masuk ke Dashboard Pegawai
                </button>

                <div class="pt-4 border-t border-slate-100 text-center">
                    <a href="/login" class="text-xs font-bold text-teal-600 hover:text-teal-700 transition-colors inline-flex items-center gap-1">
                        &larr; Beralih ke Portal Pasien
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
