@extends('layouts.admin')

@section('title', 'Pendaftaran Pasien Baru (Manual)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Form Pendaftaran Pasien Baru (Manual)</h1>
            <p class="text-sm text-slate-500 mt-1">Digunakan oleh Resepsionis saat pasien datang berobat secara langsung ke klinik.</p>
        </div>
        <a href="{{ route('admin.patients.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            &larr; Kembali ke Daftar Pasien
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.patients.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Kategori Jenis Pasien (Umum vs BPJS) -->
            <div class="p-5 rounded-2xl bg-teal-50/60 border border-teal-100 space-y-3">
                <label class="block text-xs font-bold text-slate-800">
                    Kategori Kepesertaan Pasien <span class="text-teal-600">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-3.5 rounded-xl bg-white border border-slate-200 cursor-pointer hover:border-teal-500 transition-all">
                        <input type="radio" name="jenis_pasien" value="Umum/Mandiri" onclick="toggleBpjsInput(false)" {{ old('jenis_pasien', 'Umum/Mandiri') === 'Umum/Mandiri' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                        <div>
                            <span class="block text-xs font-extrabold text-slate-900">Pasien Umum / Mandiri</span>
                            <span class="block text-[10px] text-slate-500">Pembayaran tunai / mandiri</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 rounded-xl bg-white border border-slate-200 cursor-pointer hover:border-teal-500 transition-all">
                        <input type="radio" name="jenis_pasien" value="BPJS" onclick="toggleBpjsInput(true)" {{ old('jenis_pasien') === 'BPJS' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                        <div>
                            <span class="block text-xs font-extrabold text-slate-900">Pasien Peserta BPJS</span>
                            <span class="block text-[10px] text-slate-500">Jaminan BPJS Kesehatan</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Input No BPJS (Kondisional) -->
            <div id="bpjsContainer" class="{{ old('jenis_pasien') === 'BPJS' ? '' : 'hidden' }}">
                <label for="no_bpjs" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Kartu BPJS Kesehatan <span class="text-teal-600">*</span></label>
                <input type="text" name="no_bpjs" id="no_bpjs" value="{{ old('no_bpjs') }}" placeholder="13 Digit Nomor BPJS" class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('no_bpjs') border-red-500 @enderror">
                @error('no_bpjs')
                    <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- NIK Pasien -->
                <div>
                    <label for="nik" class="block text-xs font-bold text-slate-700 mb-1.5">NIK (Nomor Induk Kependudukan) <span class="text-teal-600">*</span></label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" placeholder="16 Digit NIK KTP" maxlength="16" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('nik') border-red-500 @enderror">
                    @error('nik')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap Pasien -->
                <div>
                    <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Pasien <span class="text-teal-600">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Nama sesuai KTP" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Tempat Lahir -->
                <div>
                    <label for="tempat_lahir" class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Lahir <span class="text-teal-600">*</span></label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota Kelahiran" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('tempat_lahir') border-red-500 @enderror">
                    @error('tempat_lahir')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir <span class="text-teal-600">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('tanggal_lahir') border-red-500 @enderror">
                    @error('tanggal_lahir')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-teal-600">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Golongan Darah -->
                <div>
                    <label for="golongan_darah" class="block text-xs font-bold text-slate-700 mb-1.5">Golongan Darah <span class="text-teal-600">*</span></label>
                    <select name="golongan_darah" id="golongan_darah" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('golongan_darah') border-red-500 @enderror">
                        <option value="A" {{ old('golongan_darah') === 'A' ? 'selected' : '' }}>Golongan Darah A</option>
                        <option value="B" {{ old('golongan_darah') === 'B' ? 'selected' : '' }}>Golongan Darah B</option>
                        <option value="AB" {{ old('golongan_darah') === 'AB' ? 'selected' : '' }}>Golongan Darah AB</option>
                        <option value="O" {{ old('golongan_darah') === 'O' ? 'selected' : '' }}>Golongan Darah O</option>
                        <option value="-" {{ old('golongan_darah') === '-' ? 'selected' : '' }}>Belum Tahu (-)</option>
                    </select>
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label for="pekerjaan" class="block text-xs font-bold text-slate-700 mb-1.5">Pekerjaan Pasien <span class="text-teal-600">*</span></label>
                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="Pekerjaan utama" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('pekerjaan') border-red-500 @enderror">
                </div>

                <!-- Nomor Telepon Pasien -->
                <div>
                    <label for="nomor_telepon" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WA <span class="text-teal-600">*</span></label>
                    <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="08xxxxxxxxxx" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('nomor_telepon') border-red-500 @enderror">
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label for="alamat_lengkap" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Tempat Tinggal Lengkap <span class="text-teal-600">*</span></label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" placeholder="Alamat rumah lengkap" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all @error('alamat_lengkap') border-red-500 @enderror">{{ old('alamat_lengkap') }}</textarea>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="submit" class="flex-1 py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-teal-600/20 transition-all">
                    Daftarkan Pasien Baru
                </button>
                <a href="{{ route('admin.patients.index') }}" class="px-6 py-3.5 bg-slate-100 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleBpjsInput(show) {
        const container = document.getElementById('bpjsContainer');
        const input = document.getElementById('no_bpjs');
        if (show) {
            container.classList.remove('hidden');
            input.required = true;
        } else {
            container.classList.add('hidden');
            input.required = false;
            input.value = '';
        }
    }
</script>
@endsection
