@extends('layouts.pasien')

@section('title', 'Ganti Kata Sandi')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Ganti Kata Sandi Akun Pasien</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui kata sandi akun Anda demi keamanan data rekam medis Anda.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('pasien.profile.password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Password Saat Ini -->
            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini <span class="text-teal-600">*</span></label>
                <input type="password" name="current_password" id="current_password" placeholder="Masukkan kata sandi lama Anda" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('current_password') border-red-500 @enderror">
                @error('current_password')
                    <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-teal-600">*</span></label>
                <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-teal-600">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi baru" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-teal-600/20 transition-all">
                    Simpan Kata Sandi Baru
                </button>
                <a href="{{ route('pasien.dashboard') }}" class="px-6 py-3.5 bg-slate-100 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
