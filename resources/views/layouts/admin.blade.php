<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - Klinik Nomor Satu</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/img/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/img/icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased bg-slate-50">
    @php
        $user = Auth::guard('web')->user();
        $userRoles = $user ? $user->roles->pluck('name')->toArray() : [];
        if (empty($userRoles) && $user) {
            $userRoles = [$user->peran];
        }
        
        // Active role ditentukan dari Session, jika tidak ada maka default ke role pertama
        $activeRole = session('active_role', $userRoles[0] ?? 'Superadmin');
    @endphp

    <div class="min-h-full flex flex-col md:flex-row">
        
        <!-- Sidebar Navigation (Light Modern Theme with Dynamic Role Menus) -->
        <aside class="w-full md:w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col justify-between border-r border-slate-200 shadow-sm">
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 bg-white flex items-center gap-3 border-b border-slate-200/80">
                    <img src="{{ asset('storage/img/icon.png') }}" alt="Logo" class="h-9 w-auto">
                    <div>
                        <span class="font-extrabold text-base text-slate-900 tracking-tight block">Klinik <span class="text-teal-600">Nomor Satu</span></span>
                        <span class="text-[10px] text-teal-700 font-mono uppercase tracking-widest block -mt-1 font-bold">Portal Administrasi</span>
                    </div>
                </div>

                <!-- User Profile Summary in Sidebar -->
                <div class="p-4 mx-3 my-4 rounded-2xl bg-teal-50/60 border border-teal-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white font-black flex items-center justify-center text-lg shrink-0 shadow-md shadow-teal-600/20">
                        {{ strtoupper(substr($user->nama_lengkap ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-slate-900 truncate leading-tight">{{ $user->nama_lengkap ?? 'Pegawai' }}</p>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-teal-600 text-white text-[10px] font-extrabold shadow-sm">
                            Active: {{ $activeRole }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Links (Superadmin HANYA memiliki menu Manajemen User) -->
                <div class="px-3">
                    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                        Menu Akses: <span class="text-teal-700 font-extrabold">{{ $activeRole }}</span>
                    </p>
                    <nav class="space-y-1 text-sm font-semibold">
                        <!-- Default Common Dashboard Menu -->
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard Utama</span>
                        </a>

                        <!-- Superadmin Menus (HANYA Manajemen User) -->
                        @if($activeRole === 'Superadmin')
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Manajemen User (RBAC)</span>
                            </a>
                        @endif

                        <!-- Resepsionis Menus -->
                        @if($activeRole === 'Resepsionis')
                            <a href="{{ route('admin.patients.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.patients.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                <span>Pendaftaran Pasien</span>
                            </a>
                            <a href="{{ route('admin.poli.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.poli.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0h-4"/></svg>
                                <span>Master Poliklinik</span>
                            </a>
                            <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.schedules.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Kelola Jadwal Dokter</span>
                            </a>
                            <a href="{{ route('admin.antrean-kunjungan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.antrean-kunjungan.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Nomor Antrean Kunjungan</span>
                            </a>
                        @endif

                        <!-- Perawat Menus -->
                        @if($activeRole === 'Perawat')
                            <a href="{{ route('perawat.pemeriksaan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('perawat.pemeriksaan.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span>Skrining Vital Sign (Perawat)</span>
                            </a>
                        @endif

                        <!-- Dokter Menus -->
                        @if($activeRole === 'Dokter')
                            <a href="{{ route('dokter.pemeriksaan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('dokter.pemeriksaan.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Ruang Periksa Dokter (Poli)</span>
                            </a>
                        @endif

                        <!-- Apoteker Menus -->
                        @if($activeRole === 'Apoteker')
                            <a href="{{ route('admin.obat.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.obat.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.02 1.02a2 2 0 00.586 3.414l2.12.707a12.003 12.003 0 008.43 0l2.12-.707a2 2 0 00.586-3.414l-1.02-1.02z"/></svg>
                                <span>Inventaris & Stok Obat</span>
                            </a>
                            <a href="{{ route('apoteker.resep.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('apoteker.resep.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span>Antrean Resep Farmasi</span>
                            </a>
                        @endif

                        <!-- Kasir Menus -->
                        @if($activeRole === 'Kasir')
                            <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.pembayaran.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.pembayaran.*') ? 'text-white' : 'text-emerald-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Kasir & Pembayaran</span>
                            </a>
                        @endif

                        <!-- Pimpinan Menus -->
                        @if($activeRole === 'Pimpinan')
                            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.laporan.*') ? 'text-white' : 'text-cyan-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Laporan Operasional & Keuangan</span>
                            </a>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Sidebar Footer Logout Button Trigger Modal -->
            <div class="p-4 border-t border-slate-200/80">
                <button type="button" onclick="openLogoutModal()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold text-xs transition-all border border-red-200 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar Akun</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
            <!-- Top Navbar dengan Form Switch Role Active Session & Animated User Settings Dropdown -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-6 flex items-center justify-between shadow-sm z-30 relative">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-extrabold text-slate-800 hidden sm:inline">Sistem Informasi Klinik Nomor Satu</span>
                </div>

                <!-- Right Side: Switch Role & Interactive User Settings Dropdown -->
                <div class="flex items-center gap-4">
                    <!-- Dropdown Form Switch Role -->
                    <form action="{{ route('admin.switch-role') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <label for="active_role_select" class="text-xs font-bold text-slate-500 hidden sm:inline">Switch Role:</label>
                        <select name="active_role" id="active_role_select" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-200 text-teal-900 font-extrabold text-xs focus:ring-2 focus:ring-teal-500/20 transition-all cursor-pointer shadow-sm">
                            @foreach($userRoles as $roleName)
                                <option value="{{ $roleName }}" {{ $activeRole === $roleName ? 'selected' : '' }}>
                                    Role: {{ $roleName }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- User Settings Dropdown Trigger (Sudut Kanan Atas Dashboard) -->
                    <div class="relative border-l border-slate-200 pl-4">
                        <button type="button" onclick="toggleUserDropdown()" class="flex items-center gap-3 group focus:outline-none cursor-pointer">
                            <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white font-black flex items-center justify-center text-sm shadow-md shadow-teal-600/20 group-hover:scale-105 transition-all">
                                {{ strtoupper(substr($user->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-xs font-extrabold text-slate-900 group-hover:text-teal-600 transition-colors flex items-center gap-1">
                                    <span>{{ $user->nama_lengkap }}</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-teal-600 transition-transform duration-200" id="dropdownArrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </p>
                                <p class="text-[10px] text-teal-700 font-mono font-bold">{{ $user->id_pengguna }}</p>
                            </div>
                        </button>

                        <!-- Dropdown Menu dengan Animasi Smooth Glassmorphism -->
                        <div id="userDropdownMenu" class="hidden absolute right-0 mt-3 w-56 bg-white/95 backdrop-blur-md rounded-3xl shadow-xl border border-slate-200/80 py-2.5 z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $user->nama_lengkap }}</p>
                                <p class="text-[10px] text-slate-500 font-medium truncate">{{ $user->username }}</p>
                            </div>

                            <div class="py-1">
                                <!-- Menu Ganti Profil & Password -->
                                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition-all">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Ganti Profil & Password</span>
                                </a>
                            </div>

                            <div class="border-t border-slate-100 pt-1">
                                <!-- Menu Logout Trigger Modal -->
                                <button type="button" onclick="openLogoutModal(); toggleUserDropdown();" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-all text-left cursor-pointer">
                                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm font-bold shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 text-sm font-bold shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center transform scale-95 transition-transform duration-200">
            <div class="w-16 h-16 rounded-3xl bg-red-50 text-red-600 flex items-center justify-center mx-auto border border-red-100 shadow-md">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </div>

            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Konfirmasi Keluar</h3>
                <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin keluar dari sesi portal administrasi ini?</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeLogoutModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <form action="{{ route('logout.pegawai') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 bg-red-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-red-600/30 hover:bg-red-700 transition-all">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        function toggleUserDropdown() {
            const menu = document.getElementById('userDropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                    if (arrow) arrow.classList.add('rotate-180');
                }, 10);
            } else {
                menu.classList.remove('opacity-100', 'scale-100');
                menu.classList.add('opacity-0', 'scale-95');
                if (arrow) arrow.classList.remove('rotate-180');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
            }
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('userDropdownMenu');
            const btn = e.target.closest('button[onclick*="toggleUserDropdown"]');
            if (!btn && menu && !menu.classList.contains('hidden') && !menu.contains(e.target)) {
                toggleUserDropdown();
            }
        });
    </script>
</body>
</html>
