@extends('layouts.admin')

@section('title', 'Data Pendaftaran Pasien Klinik')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Action Button -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Data Pendaftaran Pasien</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola data rekam medis pasien terdaftar, verifikasi identitas, dan registrasi manual.</p>
        </div>

        <a href="{{ route('admin.patients.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-md shadow-teal-600/20 transition-all shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>+ Registrasi Pasien Baru (Manual)</span>
        </a>
    </div>

    <!-- Filter & Search Bar Card -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <form action="{{ route('admin.patients.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Input Search -->
            <div class="sm:col-span-6 relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pasien, ID Pasien, NIK, atau No BPJS..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:border-teal-500 focus:bg-white transition-all">
            </div>

            <!-- Dropdown Filter Jenis Pasien -->
            <div class="sm:col-span-4 relative">
                <select name="jenis" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold focus:border-teal-500 focus:bg-white transition-all">
                    <option value="">-- Semua Kategori Pasien --</option>
                    <option value="Umum/Mandiri" {{ $jenisFilter == 'Umum/Mandiri' ? 'selected' : '' }}>Umum / Mandiri</option>
                    <option value="BPJS" {{ $jenisFilter == 'BPJS' ? 'selected' : '' }}>BPJS Kesehatan</option>
                </select>
            </div>

            <!-- Action Filter -->
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl text-xs transition-all">
                    Filter
                </button>
                @if($search || $jenisFilter)
                    <a href="{{ route('admin.patients.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold transition-all flex items-center justify-center" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>

        <!-- Badge Informasi Filter Aktif -->
        @if($search || $jenisFilter)
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                <span class="font-bold text-slate-500">Filter Aktif Pasien:</span>

                @if($search)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-200 font-bold">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Pencarian: "{{ $search }}"
                    </span>
                @endif

                @if($jenisFilter)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 font-bold">
                        Kategori: {{ $jenisFilter }}
                    </span>
                @endif

                <a href="{{ route('admin.patients.index') }}" class="text-xs text-red-500 hover:underline font-bold ml-1">
                    [Hapus Semua Filter]
                </a>
            </div>
        @endif
    </div>

    <!-- Tabel Data Pasien -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Identitas Pasien</th>
                        <th class="px-6 py-4">Kategori & No BPJS</th>
                        <th class="px-6 py-4">NIK & Kontak</th>
                        <th class="px-6 py-4 text-center">Aksi Manajemen Resepsionis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pasiens as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $p->nama_lengkap }}</div>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500 font-mono">
                                    <span class="text-teal-700 font-bold bg-teal-50 px-1.5 py-0.5 rounded border border-teal-100">ID: {{ $p->id_pasien }}</span>
                                    <span>• {{ $p->jenis_kelamin }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $p->jenis_pasien === 'BPJS' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-800 border border-slate-200' }}">
                                    {{ $p->jenis_pasien }}
                                </span>
                                @if($p->no_bpjs)
                                    <div class="text-xs text-blue-700 font-mono font-bold mt-1">BPJS: {{ $p->no_bpjs }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-mono font-bold text-slate-800">NIK: {{ $p->nik }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">Telp: {{ $p->nomor_telepon }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Tombol Edit Data Pasien -->
                                    <a href="{{ route('admin.patients.edit', $p->id_pasien) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white font-bold text-xs transition-all border border-teal-200" title="Edit Data Pasien">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Tombol Reset Password Pasien 6 Digit Random -->
                                    <button type="button" onclick="confirmResetPasienPassword('{{ $p->id_pasien }}', '{{ addslashes($p->nama_lengkap) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white font-bold text-xs transition-all border border-amber-200 cursor-pointer" title="Reset Password Pasien 6 Digit Random">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        <span>Reset PW</span>
                                    </button>

                                    <!-- Tombol Hapus Data Pasien -->
                                    <button type="button" onclick="confirmDeletePasien('{{ $p->id_pasien }}', '{{ addslashes($p->nama_lengkap) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-bold text-xs transition-all border border-red-200 cursor-pointer" title="Hapus Data Pasien">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-bold">
                                Belum ada data pasien yang terdaftar / sesuai pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $pasiens->links() }}
        </div>
    </div>
</div>

<!-- Modal Form Konfirmasi Reset Password Pasien -->
<div id="confirmPasienResetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center">
        <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto border border-amber-100 shadow-md">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>

        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Reset Password Pasien</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin mereset password akun portal untuk pasien <strong id="resetPasienTargetName" class="text-slate-800"></strong>?</p>
            <p class="text-[11px] text-amber-700 font-bold bg-amber-50 p-2 rounded-xl border border-amber-200 mt-3">Sistem akan secara otomatis membuatkan kata sandi acak 6 digit angka.</p>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closeConfirmPasienResetModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                Batal
            </button>
            <form id="resetPasienPasswordForm" action="" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-amber-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-amber-600/30 hover:bg-amber-700 transition-all">
                    Ya, Reset
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pop-Up Tampil Result Password Baru Pasien 6 Digit Random -->
@if(session('reset_pasien_success'))
<div id="resultPasienResetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center">
        <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-100 shadow-md">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div>
            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase">Reset Pasien Berhasil</span>
            <h3 class="text-xl font-black text-slate-900 tracking-tight mt-2">Password Baru Terbuat</h3>
            <p class="text-xs text-slate-500 mt-1">Berikan kata sandi 6 digit angka berikut kepada pasien <strong class="text-slate-800">{{ session('reset_pasien_name') }}</strong>:</p>
        </div>

        <div class="p-4 rounded-2xl bg-slate-900 text-teal-400 font-mono font-black text-3xl tracking-widest shadow-inner flex items-center justify-center gap-2">
            <span>{{ session('new_pasien_password') }}</span>
        </div>

        <div>
            <button type="button" onclick="closeResultPasienResetModal()" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-xs shadow-lg shadow-teal-600/30 transition-all">
                Selesai / Berikan ke Pasien
            </button>
        </div>
    </div>
</div>
@endif

<!-- Modal Konfirmasi Hapus Pasien -->
<div id="deletePasienModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center">
        <div class="w-16 h-16 rounded-3xl bg-red-50 text-red-600 flex items-center justify-center mx-auto border border-red-100 shadow-md">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>

        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Hapus Data Pasien</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus data pasien <strong id="deletePasienTargetName" class="text-slate-800"></strong>?</p>
            <p class="text-[11px] text-red-700 font-bold bg-red-50 p-2 rounded-xl border border-red-200 mt-3">Tindakan ini tidak dapat dibatalkan!</p>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closeDeletePasienModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                Batal
            </button>
            <form id="deletePasienForm" action="" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 px-4 bg-red-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-red-600/30 hover:bg-red-700 transition-all">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmResetPasienPassword(id, name) {
        document.getElementById('resetPasienTargetName').innerText = name;
        document.getElementById('resetPasienPasswordForm').action = `/admin/patients/${id}/reset-password`;
        document.getElementById('confirmPasienResetModal').classList.remove('hidden');
    }

    function closeConfirmPasienResetModal() {
        document.getElementById('confirmPasienResetModal').classList.add('hidden');
    }

    function closeResultPasienResetModal() {
        const modal = document.getElementById('resultPasienResetModal');
        if(modal) modal.classList.add('hidden');
    }

    function confirmDeletePasien(id, name) {
        document.getElementById('deletePasienTargetName').innerText = name;
        document.getElementById('deletePasienForm').action = `/admin/patients/${id}`;
        document.getElementById('deletePasienModal').classList.remove('hidden');
    }

    function closeDeletePasienModal() {
        document.getElementById('deletePasienModal').classList.add('hidden');
    }
</script>
@endsection
