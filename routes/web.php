<?php

use App\Http\Controllers\Admin\AntreanKunjunganController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PatientRegistrationController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SwitchRoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Apoteker\ResepFarmasiController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\Auth\PatientForgotPasswordController;
use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\Auth\PatientRegisterController;
use App\Http\Controllers\DesignWireframeController;
use App\Http\Controllers\Dokter\PemeriksaanDokterController;
use App\Http\Controllers\Pasien\AntreanOnlineController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Pasien\ProfileController as PasienProfileController;
use App\Http\Controllers\Pasien\RekamMedisController as PasienRekamMedisController;
use App\Http\Controllers\Perawat\PemeriksaanPerawatController;
use Illuminate\Support\Facades\Route;

// Halaman Utama / Landing Page
Route::get('/', function () {
    $jadwalDokters = DB::table('tbl_jadwal_dokter')
        ->join('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
        ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
        ->select('tbl_jadwal_dokter.*', 'tbl_pengguna.nama_lengkap as nama_dokter', 'tbl_poli.nama_poli')
        ->orderBy('tbl_jadwal_dokter.hari', 'asc')
        ->get();

    $polis = DB::table('tbl_poli')->get();

    return view('welcome', compact('jadwalDokters', 'polis'));
});

// --- ROUTE SKRIPSI BAB IV: WIREFRAME DESAIN INPUT & OUTPUT ---
Route::prefix('wireframes')->name('wireframes.')->group(function () {
    Route::get('/', [DesignWireframeController::class, 'index'])->name('index');

    // Desain Input
    Route::get('/input/login', [DesignWireframeController::class, 'inputLogin'])->name('input.login');
    Route::get('/input/registrasi-pasien', [DesignWireframeController::class, 'inputRegistrasiPasien'])->name('input.registrasi-pasien');
    Route::get('/input/ambil-antrean', [DesignWireframeController::class, 'inputAmbilAntrean'])->name('input.ambil-antrean');
    Route::get('/input/jadwal-dokter', [DesignWireframeController::class, 'inputJadwalDokter'])->name('input.jadwal-dokter');
    Route::get('/input/pemeriksaan-awal', [DesignWireframeController::class, 'inputPemeriksaanAwal'])->name('input.pemeriksaan-awal');
    Route::get('/input/rekam-medis', [DesignWireframeController::class, 'inputRekamMedis'])->name('input.rekam-medis');
    Route::get('/input/resep-obat', [DesignWireframeController::class, 'inputResepObat'])->name('input.resep-obat');
    Route::get('/input/obat', [DesignWireframeController::class, 'inputObat'])->name('input.obat');
    Route::get('/input/pembayaran', [DesignWireframeController::class, 'inputPembayaran'])->name('input.pembayaran');

    // Desain Output
    Route::get('/output/tiket-antrean', [DesignWireframeController::class, 'outputTiketAntrean'])->name('output.tiket-antrean');
    Route::get('/output/kuitansi', [DesignWireframeController::class, 'outputKuitansi'])->name('output.kuitansi');
    Route::get('/output/laporan-pasien', [DesignWireframeController::class, 'outputLaporanPasien'])->name('output.laporan-pasien');
    Route::get('/output/laporan-transaksi', [DesignWireframeController::class, 'outputLaporanTransaksi'])->name('output.laporan-transaksi');
    Route::get('/output/laporan-pemeriksaan', [DesignWireframeController::class, 'outputLaporanPemeriksaan'])->name('output.laporan-pemeriksaan');
    Route::get('/output/laporan-rekam-medis', [DesignWireframeController::class, 'outputLaporanRekamMedis'])->name('output.laporan-rekam-medis');
    Route::get('/output/laporan-obat', [DesignWireframeController::class, 'outputLaporanObat'])->name('output.laporan-obat');
});

// Guest Routes (Mencegah user yang sudah login mengakses halaman login/register kembali)
Route::middleware(['guest.multi:pasien,web'])->group(function () {
    // Autentikasi Pasien
    Route::get('/login', [PatientLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PatientLoginController::class, 'login'])->name('login.pasien.post');

    Route::get('/register', [PatientRegisterController::class, 'showRegistrationForm'])->name('register.pasien');
    Route::post('/register', [PatientRegisterController::class, 'register'])->name('register.pasien.post');

    // Fitur Reset / Lupa Password Pasien
    Route::get('/forgot-password', [PatientForgotPasswordController::class, 'showResetForm'])->name('pasien.password.reset');
    Route::post('/forgot-password', [PatientForgotPasswordController::class, 'processReset'])->name('pasien.password.reset.post');

    // Autentikasi Pegawai / Admin
    Route::get('/admin/login', [EmployeeLoginController::class, 'showLoginForm'])->name('login.pegawai');
    Route::post('/admin/login', [EmployeeLoginController::class, 'login'])->name('login.pegawai.post');
});

// Logout Routes
Route::post('/logout', [PatientLoginController::class, 'logout'])->name('logout.pasien');
Route::post('/admin/logout', [EmployeeLoginController::class, 'logout'])->name('logout.pegawai');

// Dashboard Pasien & Form Antrean Online (Terproteksi Auth Guard 'pasien')
Route::middleware(['auth:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');

    // Fitur Profil & Ganti Password Self Pasien
    Route::get('/profile', [PasienProfileController::class, 'show'])->name('pasien.profile.show');
    Route::get('/password', [PasienProfileController::class, 'editPassword'])->name('pasien.profile.password');
    Route::put('/password', [PasienProfileController::class, 'updatePassword'])->name('pasien.profile.password.update');

    // Fitur Ambil, Batalkan & Cetak Nomor Antrean Online
    Route::get('/antrean/create', [AntreanOnlineController::class, 'create'])->name('pasien.antrean.create');
    Route::get('/antrean/doctors', [AntreanOnlineController::class, 'getDoctorsByPoli'])->name('pasien.antrean.doctors');
    Route::post('/antrean', [AntreanOnlineController::class, 'store'])->name('pasien.antrean.store');
    Route::post('/antrean/{id}/cancel', [AntreanOnlineController::class, 'cancel'])->name('pasien.antrean.cancel');
    Route::get('/antrean/{id}/print', [AntreanOnlineController::class, 'printTicket'])->name('pasien.antrean.print');

    // Fitur Riwayat Rekam Medis & Resep Obat Pasien
    Route::get('/rekam-medis', [PasienRekamMedisController::class, 'index'])->name('pasien.rekam-medis.index');
    Route::get('/rekam-medis/{id}', [PasienRekamMedisController::class, 'show'])->name('pasien.rekam-medis.show');
    Route::get('/rekam-medis/{id}/print', [PasienRekamMedisController::class, 'print'])->name('pasien.rekam-medis.print');
});

// Dashboard & Manajemen Sistem Admin/Pegawai (Terproteksi Auth Guard 'web')
Route::middleware(['auth:web'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Fitur Switch Active Role Session
    Route::post('/switch-role', [SwitchRoleController::class, 'switchRole'])->name('admin.switch-role');

    // Fitur Pengaturan Profil & Ganti Password Self User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Kelola Master Poliklinik (Resepsionis/Admin)
    Route::get('/polis', [PoliController::class, 'index'])->name('admin.poli.index');
    Route::post('/polis', [PoliController::class, 'store'])->name('admin.poli.store');
    Route::put('/polis/{id}', [PoliController::class, 'update'])->name('admin.poli.update');
    Route::delete('/polis/{id}', [PoliController::class, 'destroy'])->name('admin.poli.destroy');

    // Kelola Pendaftaran Pasien Manual (Resepsionis/Admin)
    Route::get('/patients', [PatientRegistrationController::class, 'index'])->name('admin.patients.index');
    Route::get('/patients/create', [PatientRegistrationController::class, 'create'])->name('admin.patients.create');
    Route::post('/patients', [PatientRegistrationController::class, 'store'])->name('admin.patients.store');
    Route::get('/patients/{id}/edit', [PatientRegistrationController::class, 'edit'])->name('admin.patients.edit');
    Route::put('/patients/{id}', [PatientRegistrationController::class, 'update'])->name('admin.patients.update');
    Route::post('/patients/{id}/reset-password', [PatientRegistrationController::class, 'resetPassword'])->name('admin.patients.reset-password');
    Route::delete('/patients/{id}', [PatientRegistrationController::class, 'destroy'])->name('admin.patients.destroy');

    // Kelola Jadwal Praktik Dokter (Resepsionis/Admin)
    Route::get('/schedules', [DoctorScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::post('/schedules', [DoctorScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::delete('/schedules/{id}', [DoctorScheduleController::class, 'destroy'])->name('admin.schedules.destroy');

    // Kelola Nomor Antrean Kunjungan Pasien (Resepsionis/Admin)
    Route::get('/antrean-kunjungan', [AntreanKunjunganController::class, 'index'])->name('admin.antrean-kunjungan.index');
    Route::get('/antrean-kunjungan/search-patients', [AntreanKunjunganController::class, 'searchPatients'])->name('admin.antrean-kunjungan.search-patients');
    Route::get('/antrean-kunjungan/doctors', [AntreanKunjunganController::class, 'getDoctorsByPoli'])->name('admin.antrean-kunjungan.doctors');
    Route::post('/antrean-kunjungan', [AntreanKunjunganController::class, 'storeOnsite'])->name('admin.antrean-kunjungan.store-onsite');
    Route::put('/antrean-kunjungan/{id}/status', [AntreanKunjunganController::class, 'updateStatus'])->name('admin.antrean-kunjungan.update-status');
    Route::post('/antrean-kunjungan/{id}/panggil', [AntreanKunjunganController::class, 'panggilAntrean'])->name('admin.antrean-kunjungan.panggil');
    Route::get('/antrean-kunjungan/{id}/print', [AntreanKunjunganController::class, 'printTicket'])->name('admin.antrean-kunjungan.print');
    Route::get('/antrean-display', [AntreanKunjunganController::class, 'display'])->name('admin.antrean-kunjungan.display');
    Route::get('/api/antrean-display/data', [AntreanKunjunganController::class, 'getDisplayData'])->name('admin.antrean-kunjungan.display-data');

    // Manajemen User & Role Spatie RBAC (Superadmin)
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');

    // Modul Skrining Tanda Vital Perawat
    Route::get('/perawat/pemeriksaan', [PemeriksaanPerawatController::class, 'index'])->name('perawat.pemeriksaan.index');
    Route::post('/perawat/pemeriksaan/vital-sign', [PemeriksaanPerawatController::class, 'storeVitalSign'])->name('perawat.pemeriksaan.store-vital-sign');

    // Modul Dokter (E-Rekam Medis & E-Resep Obat)
    Route::get('/dokter/pemeriksaan', [PemeriksaanDokterController::class, 'index'])->name('dokter.pemeriksaan.index');
    Route::get('/dokter/pemeriksaan/{idAntrean}/periksa', [PemeriksaanDokterController::class, 'create'])->name('dokter.pemeriksaan.create');
    Route::post('/dokter/pemeriksaan', [PemeriksaanDokterController::class, 'store'])->name('dokter.pemeriksaan.store');
    Route::get('/dokter/pemeriksaan/{idAntrean}/detail', [PemeriksaanDokterController::class, 'getDetailPemeriksaan'])->name('dokter.pemeriksaan.detail');
    Route::get('/dokter/obat/search', [PemeriksaanDokterController::class, 'searchObat'])->name('dokter.obat.search');

    // Manajemen Inventaris & Stok Obat
    Route::get('/obat', [ObatController::class, 'index'])->name('admin.obat.index');
    Route::post('/obat', [ObatController::class, 'store'])->name('admin.obat.store');
    Route::put('/obat/{id}', [ObatController::class, 'update'])->name('admin.obat.update');
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('admin.obat.destroy');
    Route::post('/obat/{id}/restock', [ObatController::class, 'restock'])->name('admin.obat.restock');

    // Modul Apoteker (Antrean Resep Farmasi & Penyerahan Obat)
    Route::get('/apoteker/resep', [ResepFarmasiController::class, 'index'])->name('apoteker.resep.index');
    Route::get('/apoteker/resep/{idAntrean}', [ResepFarmasiController::class, 'show'])->name('apoteker.resep.show');
    Route::post('/apoteker/resep/{idAntrean}/diserahkan', [ResepFarmasiController::class, 'diserahkan'])->name('apoteker.resep.diserahkan');
    Route::post('/apoteker/resep/{idAntrean}/batal-ambil', [ResepFarmasiController::class, 'batalAmbil'])->name('apoteker.resep.batal_ambil');

    // Modul Kasir / Billing (Pembayaran Pasien & Cetak Kuitansi)
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::get('/pembayaran/{idPemeriksaan}', [PembayaranController::class, 'show'])->name('admin.pembayaran.show');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('admin.pembayaran.store');
    Route::get('/pembayaran/{idPembayaran}/kuitansi', [PembayaranController::class, 'printKuitansi'])->name('admin.pembayaran.kuitansi');

    // Modul Laporan Operasional & Keuangan (Role Pimpinan / Superadmin)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('admin.laporan.print');
});
