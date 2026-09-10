@extends('layouts.admin')

@section('title', 'Pengaturan Profil & Keamanan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Profil & Keamanan Akun</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi identitas diri Anda dan kelola kata sandi akun pegawai.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Form 1: Edit Informasi Profil -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Informasi Profil</h3>
                    <p class="text-xs text-slate-500">Ubah nama lengkap, username, dan kontak.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- ID Pegawai (Readonly) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ID Pegawai (Readonly)</label>
                    <input type="text" value="{{ $user->id_pengguna }}" readonly disabled class="w-full px-4 py-3 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 font-mono text-sm cursor-not-allowed">
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-teal-600">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" placeholder="Beserta Gelar" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">Username <span class="text-teal-600">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" placeholder="Username Login" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('username') border-red-500 @enderror">
                    @error('username')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="no_telepon_pegawai" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon <span class="text-teal-600">*</span></label>
                    <input type="text" name="no_telepon_pegawai" id="no_telepon_pegawai" value="{{ old('no_telepon_pegawai', $user->no_telepon_pegawai) }}" placeholder="0812xxxxxxxx" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('no_telepon_pegawai') border-red-500 @enderror">
                    @error('no_telepon_pegawai')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-teal-600/20 transition-all">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Ganti Password -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Ganti Kata Sandi</h3>
                    <p class="text-xs text-slate-500">Perbarui kata sandi untuk keamanan akun Anda.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- 1. Password Saat Ini -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini <span class="text-teal-600">*</span></label>
                    <input type="password" name="current_password" id="current_password" placeholder="••••••••" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Password Baru -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-teal-600">*</span></label>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Konfirmasi Password Baru -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-teal-600">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-blue-600/20 transition-all">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
