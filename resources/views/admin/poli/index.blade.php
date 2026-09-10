@extends('layouts.admin')

@section('title', 'Kelola Master Poliklinik')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Action Button Trigger Modal -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Data Master Poliklinik</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola data poliklinik/spesialisasi layanan kesehatan klinik secara fleksibel.</p>
        </div>

        <button type="button" onclick="openCreatePoliModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-md shadow-teal-600/20 transition-all shrink-0 cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Poliklinik Baru</span>
        </button>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <form action="{{ route('admin.poli.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama poliklinik, ID, atau deskripsi..." class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-medium focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="py-2.5 px-5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl text-xs shadow-sm transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Cari</span>
                </button>

                @if($search)
                    <a href="{{ route('admin.poli.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold transition-all flex items-center justify-center" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>

        @if($search)
            <div class="pt-3 border-t border-slate-100 flex items-center gap-2 text-xs">
                <span class="font-bold text-slate-500">Filter Aktif:</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-200 font-bold">
                    Pencarian: "{{ $search }}"
                </span>
                <a href="{{ route('admin.poli.index') }}" class="text-xs text-red-500 hover:underline font-bold ml-1">
                    [Reset Filter]
                </a>
            </div>
        @endif
    </div>

    <!-- Tabel Data Poliklinik -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Kode Poli</th>
                        <th class="px-6 py-4">Nama Poliklinik</th>
                        <th class="px-6 py-4">Deskripsi Layanan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($polis as $poli)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-extrabold text-teal-700">
                                {{ $poli->id_poli }}
                            </td>

                            <td class="px-6 py-4 font-extrabold text-slate-900">
                                {{ $poli->nama_poli }}
                            </td>

                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $poli->deskripsi ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button Trigger Modal -->
                                    <button type="button" onclick="openEditPoliModal('{{ $poli->id_poli }}', '{{ addslashes($poli->nama_poli) }}', '{{ addslashes($poli->deskripsi ?? '') }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white font-bold text-xs transition-all border border-teal-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Delete Button Trigger Modal -->
                                    <button type="button" onclick="openDeletePoliModal('{{ $poli->id_poli }}', '{{ addslashes($poli->nama_poli) }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-bold text-xs transition-all border border-red-200 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-bold">
                                Belum ada data poliklinik yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $polis->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Poli Baru -->
<div id="createPoliModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full mx-4 shadow-2xl border border-slate-200 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-black text-slate-900 tracking-tight">Tambah Poliklinik Baru</h3>
            <button type="button" onclick="closeCreatePoliModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.poli.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nama_poli" class="block text-xs font-bold text-slate-700 mb-1">Nama Poliklinik <span class="text-teal-600">*</span></label>
                <input type="text" name="nama_poli" id="nama_poli" placeholder="Contoh: Poli Mata, Poli Umum" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Layanan</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Penjelasan singkat mengenai layanan poli..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all"></textarea>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeCreatePoliModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 px-4 bg-teal-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-teal-600/30 hover:bg-teal-700 transition-all">
                    Simpan Poli
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Poli -->
<div id="editPoliModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full mx-4 shadow-2xl border border-slate-200 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-black text-slate-900 tracking-tight">Edit Data Poliklinik</h3>
            <button type="button" onclick="closeEditPoliModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editPoliForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_nama_poli" class="block text-xs font-bold text-slate-700 mb-1">Nama Poliklinik <span class="text-teal-600">*</span></label>
                <input type="text" name="nama_poli" id="edit_nama_poli" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
            </div>

            <div>
                <label for="edit_deskripsi" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Layanan</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all"></textarea>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeEditPoliModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 px-4 bg-teal-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-teal-600/30 hover:bg-teal-700 transition-all">
                    Update Poli
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Poli -->
<div id="deletePoliModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center">
        <div class="w-16 h-16 rounded-3xl bg-red-50 text-red-600 flex items-center justify-center mx-auto border border-red-100 shadow-md">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>

        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Hapus Poliklinik</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus <strong id="deletePoliName" class="text-slate-800"></strong>?</p>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closeDeletePoliModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                Batal
            </button>
            <form id="deletePoliForm" action="" method="POST" class="flex-1">
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
    function openCreatePoliModal() {
        document.getElementById('createPoliModal').classList.remove('hidden');
    }
    function closeCreatePoliModal() {
        document.getElementById('createPoliModal').classList.add('hidden');
    }

    function openEditPoliModal(id, name, desc) {
        document.getElementById('edit_nama_poli').value = name;
        document.getElementById('edit_deskripsi').value = desc;
        document.getElementById('editPoliForm').action = `/admin/polis/${id}`;
        document.getElementById('editPoliModal').classList.remove('hidden');
    }
    function closeEditPoliModal() {
        document.getElementById('editPoliModal').classList.add('hidden');
    }

    function openDeletePoliModal(id, name) {
        document.getElementById('deletePoliName').innerText = name;
        document.getElementById('deletePoliForm').action = `/admin/polis/${id}`;
        document.getElementById('deletePoliModal').classList.remove('hidden');
    }
    function closeDeletePoliModal() {
        document.getElementById('deletePoliModal').classList.add('hidden');
    }
</script>
@endsection
