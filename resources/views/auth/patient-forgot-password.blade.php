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
            <img src="{{ asset('storage/img/icon.png') }}" alt="Logo Klinik" class="h-10 w-auto">
            <span class="font-extrabold text-2xl text-slate-800 tracking-tight group-hover:text-teal-600 transition-colors">Klinik <span class="text-teal-600">Nomor Satu</span></span>
        </a>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Reset Password Pasien</h2>
        <p class="mt-2 text-sm text-slate-600">Verifikasi NIK & Tanggal Lahir KTP Anda untuk membuat kata sandi baru.</p>
    </div>

    <!-- Form Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white/90 backdrop-blur-md py-8 px-6 shadow-xl border border-slate-200/80 rounded-3xl sm:px-10">
            
            <form action="{{ route('pasien.password.reset.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- NIK Field -->
                <div>
                    <label for="nik" class="block text-xs font-bold text-slate-700 mb-1">
                        NIK (16 Digit KTP) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" placeholder="16 Digit NIK KTP Anda" maxlength="16" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('nik') border-red-500 @enderror">
                    @error('nik')
                        <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir Field -->
                <div>
                    <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 mb-1">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('tanggal_lahir') border-red-500 @enderror">
                    @error('tanggal_lahir')
                        <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                        Kata Sandi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                        Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi baru" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-6 bg-teal-600 text-white rounded-2xl font-bold text-base shadow-lg shadow-teal-500/30 hover:bg-teal-700 transition-all duration-300">
                    Simpan Kata Sandi Baru
                </button>

                <div class="pt-4 border-t border-slate-200/80 text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                        &larr; Batal & Kembali ke Login Pasien
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
