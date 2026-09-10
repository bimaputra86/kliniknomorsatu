@extends('layouts.admin')

@section('title', 'Kelola Jadwal Praktik Dokter')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Action Button -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Jadwal Praktik Dokter</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola alokasi poli, hari praktik, jam operasional, dan kuota harian dokter.</p>
        </div>

        <button type="button" onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-md shadow-teal-600/20 transition-all shrink-0 cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>+ Tambah Jadwal Dokter</span>
        </button>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <form action="{{ route('admin.schedules.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-6 relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama dokter, poli, atau hari..." class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:border-teal-500 focus:bg-white transition-all">
            </div>

            <div class="sm:col-span-4 relative">
                <select name="poli" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold focus:border-teal-500 focus:bg-white transition-all">
                    <option value="">-- Semua Poliklinik --</option>
                    @foreach($polis as $p)
                        <option value="{{ $p->id_poli }}" {{ $poliFilter == $p->id_poli ? 'selected' : '' }}>{{ $p->nama_poli }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl text-xs transition-all">
                    Filter
                </button>
                @if($search || $poliFilter)
                    <a href="{{ route('admin.schedules.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold transition-all flex items-center justify-center" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Jadwal Dokter -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Dokter Bertugas</th>
                        <th class="px-6 py-4">Poliklinik</th>
                        <th class="px-6 py-4">Hari & Jam Operasional</th>
                        <th class="px-6 py-4 text-center">Kuota Harian</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $s)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $s->nama_lengkap }}</div>
                                <span class="text-xs text-slate-400 font-mono">ID: {{ $s->id_pengguna }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-teal-50 text-teal-700 border border-teal-200">
                                    {{ $s->nama_poli_master ?? $s->nama_poli }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $s->hari }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ substr($s->jam_mulai,0,5) }} - {{ substr($s->jam_selesai,0,5) }} WIB</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $s->kuota_maksimal }} Pasien
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.schedules.destroy', $s->id_jadwal) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-bold transition-all border border-red-200 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-bold">
                                Belum ada jadwal dokter yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $schedules->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal Dokter -->
<div id="addModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full mx-4 shadow-2xl border border-slate-200 space-y-5">
        <h3 class="text-xl font-extrabold text-slate-900">Tambah Jadwal Dokter</h3>

        <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Dokter Bertugas <span class="text-teal-600">*</span></label>
                <select name="id_pengguna" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-300 text-xs font-medium focus:border-teal-500">
                    <option value="">-- Pilih Dokter --</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id_pengguna }}">{{ $d->nama_lengkap }} ({{ $d->id_pengguna }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Poliklinik <span class="text-teal-600">*</span></label>
                <select name="id_poli" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-300 text-xs font-medium focus:border-teal-500">
                    <option value="">-- Pilih Poliklinik --</option>
                    @foreach($polis as $p)
                        <option value="{{ $p->id_poli }}">{{ $p->nama_poli }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Hari Praktik <span class="text-teal-600">*</span></label>
                <select name="hari" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-300 text-xs font-medium focus:border-teal-500">
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Mulai <span class="text-teal-600">*</span></label>
                    <input type="time" name="jam_mulai" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-300 text-xs font-medium focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Selesai <span class="text-teal-600">*</span></label>
                    <input type="time" name="jam_selesai" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-300 text-xs font-medium focus:border-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kuota Maksimal Pasien <span class="text-teal-600">*</span></label>
                <input type="number" name="kuota_maksimal" value="20" min="1" max="100" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-300 text-xs font-medium focus:border-teal-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeAddModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200">Batal</button>
                <button type="submit" class="flex-1 py-3 px-4 bg-teal-600 text-white rounded-2xl font-bold text-xs shadow-md hover:bg-teal-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('addModal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('addModal').classList.add('hidden'); }
</script>
@endsection
