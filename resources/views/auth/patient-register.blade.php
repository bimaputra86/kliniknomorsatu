@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 relative overflow-hidden py-10 px-4 sm:px-6 lg:px-8 flex flex-col justify-center">
    
    <!-- Background Blobs -->
    <div class="absolute inset-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-teal-200/40 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-20 w-96 h-96 bg-blue-200/40 rounded-full blur-3xl"></div>
    </div>

    <!-- Header Logo & Title -->
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl text-center relative z-10 mb-6">
        <a href="/" class="inline-flex items-center gap-3 group mb-3">
            <img src="{{ asset('storage/img/logo_klinik.png') }}" alt="Logo Klinik Nomor Satu" class="h-12 w-auto drop-shadow-sm object-contain" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Klinik+Nomor+Satu&background=0D9488&color=fff&rounded=true';">
            <span class="font-extrabold text-2xl text-slate-800 tracking-tight group-hover:text-teal-600 transition-colors">Klinik <span class="text-teal-600">Nomor Satu</span></span>
        </a>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pendaftaran Pasien Baru</h2>
        <p class="mt-2 text-sm text-slate-600">Silakan lengkapi formulir di bawah ini untuk mendapatkan ID Pasien dan Akun Portal Klinik.</p>
    </div>

    <!-- Success Registration Modal / Alert -->
    @if(session('success_registration'))
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl mb-8 relative z-20">
        <div class="bg-emerald-50 border-2 border-emerald-400 rounded-3xl p-6 shadow-xl relative overflow-hidden">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/30">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-emerald-900">Registrasi Pasien Berhasil!</h3>
                    <p class="text-sm text-emerald-700 mt-1">Akun pasien Anda telah dibuat secara otomatis oleh sistem.</p>
                    
                    <div class="mt-4 bg-white/90 backdrop-blur-sm rounded-2xl p-4 border border-emerald-200 space-y-2 text-sm text-slate-800 font-mono">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                            <span class="text-slate-500 font-sans">Nama Pasien:</span>
                            <span class="font-bold text-slate-900">{{ session('success_registration')['nama_lengkap'] }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                            <span class="text-slate-500 font-sans">ID Pasien:</span>
                            <span class="font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded border border-teal-200">{{ session('success_registration')['id_pasien'] }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                            <span class="text-slate-500 font-sans">Username Default:</span>
                            <span class="font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ session('success_registration')['username'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-sans">Password Default:</span>
                            <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ session('success_registration')['password'] }}</span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-emerald-600 mt-3 italic">*Gunakan Username & Password default di atas untuk login ke Portal Pasien.</p>

                    <div class="mt-4 flex gap-3">
                        <a href="/login" class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-emerald-700 transition-all">
                            Lanjut Login Sekarang &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Registration Form Card -->
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl relative z-10">
        <div class="bg-white/90 backdrop-blur-md py-8 px-6 shadow-xl border border-slate-200/80 rounded-3xl sm:px-10">
            <form action="{{ route('register.pasien.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 1: Jenis Kepesertaan -->
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-200/60">
                    <label class="block text-sm font-bold text-slate-800 mb-2">Tipe Kepesertaan Pasien <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50/50 has-[:checked]:text-teal-800 border-slate-200 bg-white">
                            <input type="radio" name="jenis_pasien" value="Umum/Mandiri" class="sr-only" id="jenis_mandiri" {{ old('jenis_pasien', 'Umum/Mandiri') == 'Umum/Mandiri' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2 font-bold text-sm">
                                <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Umum / Mandiri
                            </div>
                        </label>
                        <label class="relative flex items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50/50 has-[:checked]:text-teal-800 border-slate-200 bg-white">
                            <input type="radio" name="jenis_pasien" value="BPJS" class="sr-only" id="jenis_bpjs" {{ old('jenis_pasien') == 'BPJS' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2 font-bold text-sm">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                Peserta BPJS
                            </div>
                        </label>
                    </div>
                    @error('jenis_pasien')
                        <p class="mt-2 text-xs text-red-500 font-semibold">{{ $message }}</p>
                    @enderror

                    <!-- Input Nomor BPJS (Conditional) -->
                    <div id="wrapper_no_bpjs" class="mt-4 {{ old('jenis_pasien') == 'BPJS' ? '' : 'hidden' }}">
                        <label for="no_bpjs" class="block text-xs font-bold text-slate-700 mb-1">Nomor Kartu BPJS <span class="text-red-500">*</span></label>
                        <input type="text" name="no_bpjs" id="no_bpjs" value="{{ old('no_bpjs') }}" placeholder="Contoh: 0001234567890" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('no_bpjs') border-red-500 @enderror">
                        @error('no_bpjs')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section 2: Identitas Diri -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-200 pb-2">Identitas Pasien</h3>

                    <!-- NIK & Nama Lengkap -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nik" class="block text-xs font-bold text-slate-700 mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" id="nik" maxlength="16" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('nik') border-red-500 @enderror">
                            @error('nik')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Sesuai KTP / KK" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tempat & Tanggal Lahir -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tempat_lahir" class="block text-xs font-bold text-slate-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota / Kabupaten" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('tempat_lahir') border-red-500 @enderror">
                            @error('tempat_lahir')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jenis Kelamin & Golongan Darah -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="jenis_kelamin" class="block text-xs font-bold text-slate-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="golongan_darah" class="block text-xs font-bold text-slate-700 mb-1">Golongan Darah <span class="text-red-500">*</span></label>
                            <select name="golongan_darah" id="golongan_darah" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('golongan_darah') border-red-500 @enderror">
                                <option value="">-- Pilih Gol. Darah --</option>
                                <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                                <option value="-" {{ old('golongan_darah') == '-' ? 'selected' : '' }}>Tidak Tahu (-)</option>
                            </select>
                            @error('golongan_darah')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Pekerjaan, Kontak & Alamat -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-200 pb-2">Kontak & Pekerjaan</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pekerjaan" class="block text-xs font-bold text-slate-700 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="PNS, Swasta, Wiraswasta, dll" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('pekerjaan') border-red-500 @enderror">
                            @error('pekerjaan')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nomor_telepon" class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / WA <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Contoh: 08123456789" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('nomor_telepon') border-red-500 @enderror">
                            @error('nomor_telepon')
                                <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="alamat_lengkap" class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('alamat_lengkap') border-red-500 @enderror">{{ old('alamat_lengkap') }}</textarea>
                        @error('alamat_lengkap')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="riwayat_alergi" class="block text-xs font-bold text-slate-700 mb-1">Riwayat Alergi (Opsional)</label>
                        <textarea name="riwayat_alergi" id="riwayat_alergi" rows="2" placeholder="Contoh: Alergi Obat Paracetamol, Alergi Udara Dingin, dsb. (Kosongkan jika tidak ada)" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-sm transition-all @error('riwayat_alergi') border-red-500 @enderror">{{ old('riwayat_alergi') }}</textarea>
                        @error('riwayat_alergi')
                            <p class="mt-1 text-xs text-red-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 px-6 bg-teal-600 text-white rounded-2xl font-bold text-lg shadow-lg shadow-teal-500/30 hover:bg-teal-700 hover:shadow-teal-500/50 hover:-translate-y-0.5 transition-all duration-300">
                        Daftar Pasien Baru
                    </button>
                </div>

                <!-- Footer link to Login -->
                <div class="text-center pt-2">
                    <p class="text-sm text-slate-600">
                        Sudah pernah mendaftar? <a href="/login" class="font-bold text-teal-600 hover:underline">Masuk ke Portal Pasien</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioMandiri = document.getElementById('jenis_mandiri');
        const radioBpjs = document.getElementById('jenis_bpjs');
        const wrapperBpjs = document.getElementById('wrapper_no_bpjs');
        const inputBpjs = document.getElementById('no_bpjs');

        function toggleBpjs() {
            if (radioBpjs.checked) {
                wrapperBpjs.classList.remove('hidden');
            } else {
                wrapperBpjs.classList.add('hidden');
            }
        }

        radioMandiri.addEventListener('change', toggleBpjs);
        radioBpjs.addEventListener('change', toggleBpjs);
    });
</script>
@endsection
