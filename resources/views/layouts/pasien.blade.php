<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Pasien') - Klinik Nomor Satu</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/img/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/img/icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased bg-slate-50">
    @php
        $pasien = Auth::guard('pasien')->user();
    @endphp

    <div class="min-h-full flex flex-col md:flex-row">
        
        <!-- Sidebar Navigation Pasien (Light Modern Theme with Teal Accent) -->
        <aside class="w-full md:w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col justify-between border-r border-slate-200 shadow-sm">
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 bg-white flex items-center gap-3 border-b border-slate-200/80">
                    <img src="{{ asset('storage/img/icon.png') }}" alt="Logo" class="h-9 w-auto">
                    <div>
                        <span class="font-extrabold text-base text-slate-900 tracking-tight block">Klinik <span class="text-teal-600">Nomor Satu</span></span>
                        <span class="text-[10px] text-teal-700 font-mono uppercase tracking-widest block -mt-1 font-bold">Portal Pasien</span>
                    </div>
                </div>

                <!-- User Profile Summary in Sidebar -->
                <div class="p-4 mx-3 my-4 rounded-2xl bg-teal-50/60 border border-teal-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white font-black flex items-center justify-center text-lg shrink-0 shadow-md shadow-teal-600/20">
                        {{ strtoupper(substr($pasien->nama_lengkap ?? 'P', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-slate-900 truncate leading-tight">{{ $pasien->nama_lengkap ?? 'Pasien' }}</p>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 border border-teal-200 text-[10px] font-bold">
                            {{ $pasien->jenis_pasien ?? 'Umum/Mandiri' }}
                        </span>
                    </div>
                </div>

                <!-- Pasien Navigation Links -->
                <div class="px-3">
                    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Menu Utama Pasien</p>
                    <nav class="space-y-1 text-sm font-semibold">
                        <!-- Dashboard Pasien -->
                        <a href="{{ route('pasien.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('pasien.dashboard') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard Utama</span>
                        </a>

                        <!-- Ambil Antrean Online -->
                        <a href="{{ route('pasien.antrean.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('pasien.antrean.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Ambil Antrean Online</span>
                        </a>

                        <!-- Riwayat Berobat & Rekam Medis Pasien -->
                        <a href="{{ route('pasien.rekam-medis.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('pasien.rekam-medis.*') ? 'bg-teal-600 text-white font-extrabold shadow-md shadow-teal-600/30' : 'hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}">
                            <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('pasien.rekam-medis.*') ? 'text-white' : 'text-blue-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Riwayat Rekam Medis</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Sidebar Footer Logout Button Trigger Modal -->
            <div class="p-4 border-t border-slate-200/80">
                <button type="button" onclick="openPasienLogoutModal()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold text-xs transition-all border border-red-200 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar (Logout)</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
            <!-- Top Navbar Pasien dengan Dropdown User (Sudut Kanan Atas) -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-6 flex items-center justify-between shadow-sm z-30 relative">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-extrabold text-slate-800">Portal Layanan Mandiri Pasien</span>
                </div>

                <!-- Pasien Profile Dropdown Trigger (Sudut Kanan Atas) -->
                <div class="relative">
                    <button type="button" onclick="togglePasienDropdown()" class="flex items-center gap-3 group focus:outline-none cursor-pointer">
                        <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white font-black flex items-center justify-center text-sm shadow-md shadow-teal-600/20 group-hover:scale-105 transition-all">
                            {{ strtoupper(substr($pasien->nama_lengkap ?? 'P', 0, 1)) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-extrabold text-slate-900 group-hover:text-teal-600 transition-colors flex items-center gap-1">
                                <span>{{ $pasien->nama_lengkap }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-teal-600 transition-transform duration-200" id="pasienDropdownArrow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </p>
                            <p class="text-[10px] text-teal-700 font-mono font-bold">ID: {{ $pasien->id_pasien }}</p>
                        </div>
                    </button>

                    <!-- Dropdown Menu Pasien dengan Animasi Smooth Glassmorphism -->
                    <div id="pasienDropdownMenu" class="hidden absolute right-0 mt-3 w-56 bg-white/95 backdrop-blur-md rounded-3xl shadow-xl border border-slate-200/80 py-2.5 z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $pasien->nama_lengkap }}</p>
                            <p class="text-[10px] text-teal-700 font-mono font-bold truncate">ID: {{ $pasien->id_pasien }}</p>
                        </div>

                        <div class="py-1">
                            <!-- Menu Profil Lengkap Pasien -->
                            <a href="{{ route('pasien.profile.show') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition-all">
                                <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Profil Lengkap Pasien</span>
                            </a>

                            <!-- Menu Ganti Kata Sandi -->
                            <a href="{{ route('pasien.profile.password') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700 transition-all">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Ganti Kata Sandi</span>
                            </a>
                        </div>

                        <div class="border-t border-slate-100 pt-1">
                            <!-- Menu Logout Trigger Modal -->
                            <button type="button" onclick="openPasienLogoutModal(); togglePasienDropdown();" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-all text-left cursor-pointer">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>Keluar (Logout)</span>
                            </button>
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

    <!-- Modal Konfirmasi Logout Pasien -->
    <div id="pasienLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-200 space-y-6 text-center transform scale-95 transition-transform duration-200">
            <div class="w-16 h-16 rounded-3xl bg-red-50 text-red-600 flex items-center justify-center mx-auto border border-red-100 shadow-md">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </div>

            <div>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Konfirmasi Keluar</h3>
                <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin keluar dari Portal Pasien?</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePasienLogoutModal()" class="flex-1 py-3 px-4 bg-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-200 transition-all">
                    Batal
                </button>
                <form action="{{ route('logout.pasien') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 bg-red-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-red-600/30 hover:bg-red-700 transition-all">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPasienLogoutModal() {
            document.getElementById('pasienLogoutModal').classList.remove('hidden');
        }

        function closePasienLogoutModal() {
            document.getElementById('pasienLogoutModal').classList.add('hidden');
        }

        function togglePasienDropdown() {
            const menu = document.getElementById('pasienDropdownMenu');
            const arrow = document.getElementById('pasienDropdownArrow');
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
            const menu = document.getElementById('pasienDropdownMenu');
            const btn = e.target.closest('button[onclick*="togglePasienDropdown"]');
            if (!btn && menu && !menu.classList.contains('hidden') && !menu.contains(e.target)) {
                togglePasienDropdown();
            }
        });
    </script>
</body>
</html>
