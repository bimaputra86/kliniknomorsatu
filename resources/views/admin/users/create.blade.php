@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tambah User / Pegawai Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi data pegawai dan tentukan hak akses Spatie (RBAC).</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            &larr; Kembali
        </a>
    </div>

    <!-- Form Card (Light Theme) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- ID Pengguna -->
                <div>
                    <label for="id_pengguna" class="block text-xs font-bold text-slate-700 mb-1.5">ID Pegawai (Maks 10 Karakter) <span class="text-teal-600">*</span></label>
                    <input type="text" name="id_pengguna" id="id_pengguna" maxlength="10" value="{{ old('id_pengguna') }}" placeholder="Contoh: STF-001 / DKT-001" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-mono transition-all @error('id_pengguna') border-red-500 @enderror">
                    @error('id_pengguna')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-teal-600">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Beserta Gelar Medis" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">Username Login <span class="text-teal-600">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Huruf/angka tanpa spasi" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('username') border-red-500 @enderror">
                    @error('username')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi (Password) <span class="text-teal-600">*</span></label>
                    <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Peran Utama (Tanpa Superadmin) -->
                <div>
                    <label for="peran" class="block text-xs font-bold text-slate-700 mb-1.5">Peran Utama <span class="text-teal-600">*</span></label>
                    <select name="peran" id="peran" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('peran') border-red-500 @enderror">
                        <option value="">-- Pilih Peran Utama --</option>
                        <option value="Resepsionis" {{ old('peran') == 'Resepsionis' ? 'selected' : '' }}>Resepsionis</option>
                        <option value="Perawat" {{ old('peran') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                        <option value="Dokter" {{ old('peran') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                        <option value="Apoteker" {{ old('peran') == 'Apoteker' ? 'selected' : '' }}>Apoteker</option>
                        <option value="Kasir" {{ old('peran') == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="Pimpinan" {{ old('peran') == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    </select>
                    @error('peran')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon Pegawai -->
                <div>
                    <label for="no_telepon_pegawai" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon <span class="text-teal-600">*</span></label>
                    <input type="text" name="no_telepon_pegawai" id="no_telepon_pegawai" value="{{ old('no_telepon_pegawai') }}" placeholder="0812xxxxxxxx" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm font-medium transition-all @error('no_telepon_pegawai') border-red-500 @enderror">
                    @error('no_telepon_pegawai')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Spatie RBAC Multi-Roles Selection (Filtered in Controller) -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                <label class="block text-xs font-bold text-slate-800">
                    Hak Akses Role Spatie (RBAC) <span class="text-teal-600">*</span>
                    <span class="block text-xs font-normal text-slate-500 mt-0.5">Pengguna dapat memiliki lebih dari 1 role Spatie (Multi-Roles).</span>
                </label>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2.5 p-3 rounded-xl bg-white border border-slate-200 cursor-pointer hover:border-teal-500 transition-colors">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500" {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-slate-700">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-teal-600/20 transition-all">
                    Simpan User Baru
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3.5 bg-slate-100 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
