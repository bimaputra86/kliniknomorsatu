@extends('layouts.admin')

@section('title', 'Manajemen User & RBAC')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Action Button -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen User (Pegawai)</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola staf operasional, kredensial login, dan hak akses Spatie RBAC.</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-md shadow-teal-600/20 transition-all shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>Tambah User Baru</span>
        </a>
    </div>

    <!-- Filter & Search Bar Card -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Input Search (Nama Lengkap / Username / ID) -->
            <div class="sm:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama lengkap, username, atau ID..." class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-medium focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
            </div>

            <!-- Dropdown Filter Role -->
            <div class="sm:col-span-4 relative">
                <select name="role" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                    <option value="">-- Semua Role RBAC --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ $roleFilter == $r->name ? 'selected' : '' }}>
                            Role: {{ $r->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter & Reset Buttons -->
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl text-xs shadow-sm transition-all flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>

                @if($search || $roleFilter)
                    <a href="{{ route('admin.users.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold transition-all flex items-center justify-center" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Badge Informasi Filter Aktif -->
        @if($search || $roleFilter)
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                <span class="font-bold text-slate-500">Filter Aktif User:</span>

                @if($search)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-200 font-bold">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Pencarian: "{{ $search }}"
                    </span>
                @endif

                @if($roleFilter)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 font-bold">
                        Role: {{ $roleFilter }}
                    </span>
                @endif

                <a href="{{ route('admin.users.index') }}" class="text-xs text-red-500 hover:underline font-bold ml-1">
                    [Hapus Semua Filter]
                </a>
            </div>
        @endif
    </div>

    <!-- Tabel Data User -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Informasi Staf / Pegawai</th>
                        <th class="px-6 py-4">Peran & Roles Spatie</th>
                        <th class="px-6 py-4">Status Akun</th>
                        <th class="px-6 py-4 text-center">Aksi Manajemen Superadmin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Kolom 1: Profil Staf -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 font-black flex items-center justify-center text-sm shrink-0 border border-teal-100">
                                        {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-sm leading-snug flex items-center gap-2">
                                            <span>{{ $user->nama_lengkap }}</span>
                                        </h4>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500 font-mono">
                                            <span class="text-teal-700 font-bold bg-teal-50 px-1.5 py-0.5 rounded border border-teal-100">ID: {{ $user->id_pengguna }}</span>
                                            <span>• Username: {{ $user->username }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 2: Peran Utama & Spatie Roles -->
                            <td class="px-6 py-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Peran:</span>
                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $user->peran }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-teal-50 text-teal-700 border border-teal-200">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Tidak ada role</span>
                                        @endforelse
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 3: Status Aktif / Nonaktif -->
                            <td class="px-6 py-4">
                                @if(($user->status_aktif ?? 'Aktif') === 'Aktif')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom 4: Tombol Aksi Edit, Toggle Status & Reset Password -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.users.edit', $user->id_pengguna) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white font-bold text-xs transition-all border border-teal-200" title="Edit User & Role">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Tombol Switch Aktif / Nonaktif -->
                                    <form action="{{ route('admin.users.toggle-status', $user->id_pengguna) }}" method="POST" class="inline">
                                        @csrf
                                        @if(($user->status_aktif ?? 'Aktif') === 'Aktif')
                                            <button type="submit" onclick="return confirm('Nonaktifkan user {{ addslashes($user->nama_lengkap) }}? User ini tidak akan bisa login.')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-bold text-xs transition-all border border-red-200 cursor-pointer" title="Nonaktifkan User">
                                                <span>Nonaktifkan</span>
                                            </button>
                                        @else
                                            <button type="submit" onclick="return confirm('Aktifkan kembali user {{ addslashes($user->nama_lengkap) }}?')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-bold text-xs transition-all border border-emerald-200 cursor-pointer" title="Aktifkan User">
                                                <span>Aktifkan</span>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Tombol Reset Password -->
                                    <button type="button" onclick="confirmResetPassword('{{ $user->id_pengguna }}', '{{ addslashes($user->nama_lengkap) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white font-bold text-xs transition-all border border-amber-200 cursor-pointer" title="Reset Password 6 Digit Random">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        <span>Reset PW</span>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-bold">
                                Tidak ada data pegawai yang sesuai dengan pencarian/filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Form Konfirmasi Reset Password -->
<div id="confirmResetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center">
        <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto border border-amber-100 shadow-md">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>

        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Reset Password Pegawai</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin mereset password untuk <strong id="resetTargetName" class="text-slate-800"></strong>?</p>
            <p class="text-[11px] text-amber-700 font-bold bg-amber-50 p-2 rounded-xl border border-amber-200 mt-3">Sistem akan secara otomatis membuatkan kata sandi acak 6 digit angka.</p>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closeConfirmResetModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                Batal
            </button>
            <form id="resetPasswordForm" action="" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-amber-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-amber-600/30 hover:bg-amber-700 transition-all">
                    Ya, Reset
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pop-Up Tampil Result Password Baru 6 Digit Random -->
@if(session('reset_success'))
<div id="resultResetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center">
        <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-100 shadow-md">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div>
            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase">Reset Berhasil</span>
            <h3 class="text-xl font-black text-slate-900 tracking-tight mt-2">Password Baru Terbuat</h3>
            <p class="text-xs text-slate-500 mt-1">Berikan kata sandi 6 digit angka berikut kepada pegawai <strong class="text-slate-800">{{ session('reset_user_name') }}</strong>:</p>
        </div>

        <div class="p-4 rounded-2xl bg-slate-900 text-teal-400 font-mono font-black text-3xl tracking-widest shadow-inner flex items-center justify-center gap-2">
            <span>{{ session('new_password') }}</span>
        </div>

        <div>
            <button type="button" onclick="closeResultResetModal()" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-xs shadow-lg shadow-teal-600/30 transition-all">
                Selesai / Salin Kredensial
            </button>
        </div>
    </div>
</div>
@endif

<script>
    function confirmResetPassword(id, name) {
        document.getElementById('resetTargetName').innerText = name;
        document.getElementById('resetPasswordForm').action = `/admin/users/${id}/reset-password`;
        document.getElementById('confirmResetModal').classList.remove('hidden');
    }

    function closeConfirmResetModal() {
        document.getElementById('confirmResetModal').classList.add('hidden');
    }

    function closeResultResetModal() {
        const modal = document.getElementById('resultResetModal');
        if(modal) modal.classList.add('hidden');
    }
</script>
@endsection
