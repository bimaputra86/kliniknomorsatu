@extends('layouts.admin')

@section('title', 'Inventaris & Stok Obat')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Inventaris & Stok Obat</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola master data obat, harga satuan, dan stok real-time untuk E-Resep dokter.</p>
        </div>
        <div>
            <button onclick="openAddObatModal()" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-xl text-xs transition-all shadow-md shadow-amber-600/20 flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Obat Baru</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.obat.index') }}" method="GET" class="flex gap-3">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama obat, kode obat, atau jenis obat..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-amber-500 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-28">Kode Obat</th>
                        <th class="py-3.5 px-4">Nama Obat</th>
                        <th class="py-3.5 px-4">Jenis / Kategori</th>
                        <th class="py-3.5 px-4">Harga Satuan</th>
                        <th class="py-3.5 px-4 text-center">Stok</th>
                        <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($obats as $obat)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="py-3.5 px-4 font-mono font-black text-amber-700 text-sm">
                                {{ $obat->id_obat }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $obat->nama_obat }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 rounded-full font-bold text-[10px] text-slate-700">
                                    {{ $obat->jenis_obat }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-900 font-bold">
                                Rp {{ number_format($obat->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="font-mono text-sm {{ $obat->stok <= 10 ? 'text-red-600 font-black' : 'text-slate-800' }}">
                                    {{ $obat->stok }}
                                </div>
                                <span class="text-[10px] font-bold text-slate-400">{{ $obat->satuan }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="openRestockModal('{{ json_encode($obat) }}')" class="p-1.5 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-700 text-slate-600 rounded-lg transition" title="Restock Obat (Stok Masuk)">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                    <button type="button" onclick="openEditObatModal('{{ json_encode($obat) }}')" class="p-1.5 bg-slate-100 hover:bg-blue-100 hover:text-blue-700 text-slate-600 rounded-lg transition" title="Edit Obat">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.obat.destroy', $obat->id_obat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat {{ $obat->nama_obat }} dari inventaris?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-red-100 hover:text-red-700 text-slate-600 rounded-lg transition" title="Hapus Obat">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                                Belum ada data obat terdaftar di inventaris.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($obats->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $obats->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Obat -->
<div id="addObatModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900">Tambah Obat Baru</h3>
            <button onclick="closeAddObatModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1">&times;</button>
        </div>

        <form action="{{ route('admin.obat.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                <input type="text" name="nama_obat" required placeholder="Contoh: Paracetamol 500mg" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jenis / Kategori Obat <span class="text-red-500">*</span></label>
                <input type="text" name="jenis_obat" required placeholder="Contoh: Tablet, Sirup, Kapsul, Salep" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_satuan" required min="0" placeholder="Contoh: 5000" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" required placeholder="Contoh: Tablet, Botol, Pcs" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Stok Awal <span class="text-red-500">*</span></label>
                <input type="number" name="stok" required min="0" placeholder="Contoh: 100" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeAddObatModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-xl text-xs shadow-md transition-all">
                    Simpan Obat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Obat -->
<div id="editObatModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900">Edit Data Obat</h3>
            <button onclick="closeEditObatModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1">&times;</button>
        </div>

        <form id="editObatForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Obat <span class="text-red-500">*</span></label>
                <input type="text" name="nama_obat" id="editNamaObat" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jenis / Kategori Obat <span class="text-red-500">*</span></label>
                <input type="text" name="jenis_obat" id="editJenisObat" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_satuan" id="editHargaSatuan" required min="0" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" id="editSatuan" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Stok Obat <span class="text-red-500">*</span></label>
                <input type="number" name="stok" id="editStok" required min="0" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-amber-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditObatModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl text-xs shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Restock Obat -->
<div id="restockObatModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900">Restock / Tambah Stok Obat</h3>
            <button onclick="closeRestockModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1">&times;</button>
        </div>

        <form id="restockObatForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Obat</label>
                <div id="restockNamaObat" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                    -
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Stok Saat Ini</label>
                <div id="restockStokSaatIni" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                    -
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Stok Masuk <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_masuk" required min="1" placeholder="Masukkan jumlah obat yang masuk..." class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold focus:border-emerald-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeRestockModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow-md transition-all">
                    Tambah Stok
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddObatModal() {
        document.getElementById('addObatModal').classList.remove('hidden');
    }

    function closeAddObatModal() {
        document.getElementById('addObatModal').classList.add('hidden');
    }

    function openEditObatModal(jsonStr) {
        const item = JSON.parse(jsonStr);
        document.getElementById('editObatForm').action = `/admin/obat/${item.id_obat}`;
        document.getElementById('editNamaObat').value = item.nama_obat;
        document.getElementById('editJenisObat').value = item.jenis_obat;
        document.getElementById('editHargaSatuan').value = item.harga_satuan;
        document.getElementById('editSatuan').value = item.satuan;
        document.getElementById('editStok').value = item.stok;

        document.getElementById('editObatModal').classList.remove('hidden');
    }

    function closeEditObatModal() {
        document.getElementById('editObatModal').classList.add('hidden');
    }

    function openRestockModal(jsonStr) {
        const item = JSON.parse(jsonStr);
        document.getElementById('restockObatForm').action = `/admin/obat/${item.id_obat}/restock`;
        document.getElementById('restockNamaObat').textContent = item.nama_obat;
        document.getElementById('restockStokSaatIni').textContent = `${item.stok} ${item.satuan}`;
        
        document.getElementById('restockObatModal').classList.remove('hidden');
    }

    // Helper close restock
    function closeRestockModal() {
        document.getElementById('restockObatModal').classList.add('hidden');
    }
</script>
@endsection
