<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRoles = $user->roles->pluck('name')->toArray();
        if (empty($userRoles)) {
            $userRoles = [$user->peran];
        }
        $activeRole = session('active_role', $userRoles[0] ?? 'Superadmin');

        // Parameter Tanggal Bulan Ini (Month-to-Date)
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        $namaBulanIni = Carbon::now()->translatedFormat('F Y');

        // Data statistik spesifik per Role (Diukur berdasarkan data bulan berjalan)
        $cards = [];

        if ($activeRole === 'Superadmin') {
            $cards = [
                [
                    'title' => 'Total User System',
                    'value' => DB::table('tbl_pengguna')->count(),
                    'sub' => 'Pengguna Terdaftar',
                    'color' => 'teal',
                    'icon' => 'users',
                ],
                [
                    'title' => 'User Status Aktif',
                    'value' => DB::table('tbl_pengguna')->where('status_aktif', 'Aktif')->count(),
                    'sub' => 'Akses Aktif Sistem',
                    'color' => 'blue',
                    'icon' => 'check-circle',
                ],
                [
                    'title' => 'Master Poliklinik',
                    'value' => DB::table('tbl_poli')->count(),
                    'sub' => 'Layanan Poli Klinik',
                    'color' => 'amber',
                    'icon' => 'clinic',
                ],
                [
                    'title' => 'Jadwal Dokter Aktif',
                    'value' => DB::table('tbl_jadwal_dokter')->count(),
                    'sub' => 'Alokasi Praktik Dokter',
                    'color' => 'purple',
                    'icon' => 'calendar',
                ],
            ];
        } elseif ($activeRole === 'Resepsionis') {
            $cards = [
                [
                    'title' => 'Pasien Baru ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pasien')->whereBetween('created_at', [$startOfMonth.' 00:00:00', $endOfMonth.' 23:59:59'])->count(),
                    'sub' => 'Registrasi Pasien Baru',
                    'color' => 'teal',
                    'icon' => 'user-add',
                ],
                [
                    'title' => 'Kunjungan ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_antrean')->whereBetween('tanggal_antrean', [$startOfMonth, $endOfMonth])->count(),
                    'sub' => 'Nomor Antrean Diterbitkan',
                    'color' => 'blue',
                    'icon' => 'ticket',
                ],
                [
                    'title' => 'Antrean Menunggu (Hari Ini)',
                    'value' => DB::table('tbl_antrean')->whereDate('tanggal_antrean', date('Y-m-d'))->where('status_antrean', 'Menunggu')->count(),
                    'sub' => 'Menunggu Dipanggil',
                    'color' => 'amber',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'Total Master Pasien',
                    'value' => DB::table('tbl_pasien')->count(),
                    'sub' => 'Database Pasien',
                    'color' => 'purple',
                    'icon' => 'users',
                ],
            ];
        } elseif ($activeRole === 'Perawat') {
            $cards = [
                [
                    'title' => 'Skrining Vital Sign ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pemeriksaan')->whereBetween('tanggal_pemeriksaan', [$startOfMonth, $endOfMonth])->whereNotNull('tekanan_darah')->count(),
                    'sub' => 'Tanda Vital Diperiksa',
                    'color' => 'red',
                    'icon' => 'heart',
                ],
                [
                    'title' => 'Menunggu Skrining (Hari Ini)',
                    'value' => DB::table('tbl_antrean')->whereDate('tanggal_antrean', date('Y-m-d'))->where('status_antrean', 'Dipanggil')->count(),
                    'sub' => 'Pasien Antrean Dipanggil',
                    'color' => 'amber',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'Kunjungan Pasien ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_antrean')->whereBetween('tanggal_antrean', [$startOfMonth, $endOfMonth])->count(),
                    'sub' => 'Total Kunjungan Poli',
                    'color' => 'blue',
                    'icon' => 'ticket',
                ],
                [
                    'title' => 'Selesai Skrining (Hari Ini)',
                    'value' => DB::table('tbl_antrean')->whereDate('tanggal_antrean', date('Y-m-d'))->whereIn('status_antrean', ['Diperiksa', 'Selesai'])->count(),
                    'sub' => 'Siap Diperiksa Dokter',
                    'color' => 'teal',
                    'icon' => 'check-circle',
                ],
            ];
        } elseif ($activeRole === 'Dokter') {
            $cards = [
                [
                    'title' => 'Pemeriksaan Medis ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pemeriksaan')->where('id_pengguna', $user->id_pengguna)->whereBetween('tanggal_pemeriksaan', [$startOfMonth, $endOfMonth])->count(),
                    'sub' => 'Pasien Ditangani Dokter',
                    'color' => 'purple',
                    'icon' => 'stethoscope',
                ],
                [
                    'title' => 'Menunggu Diperiksa (Hari Ini)',
                    'value' => DB::table('tbl_antrean')->whereDate('tanggal_antrean', date('Y-m-d'))->where('status_antrean', 'Diperiksa')->count(),
                    'sub' => 'Antrean di Ruang Periksa',
                    'color' => 'amber',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'E-Resep Diterbitkan ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pemeriksaan')->where('id_pengguna', $user->id_pengguna)->whereBetween('tanggal_pemeriksaan', [$startOfMonth, $endOfMonth])->whereNotNull('status_resep')->count(),
                    'sub' => 'Resep Obat Ditulis',
                    'color' => 'teal',
                    'icon' => 'document-prescription',
                ],
                [
                    'title' => 'Jadwal Praktik Saya',
                    'value' => DB::table('tbl_jadwal_dokter')->where('id_pengguna', $user->id_pengguna)->count(),
                    'sub' => 'Sesi Praktik Terjadwal',
                    'color' => 'blue',
                    'icon' => 'calendar',
                ],
            ];
        } elseif ($activeRole === 'Apoteker') {
            $cards = [
                [
                    'title' => 'Resep Diserahkan ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pemeriksaan')->whereBetween('tanggal_pemeriksaan', [$startOfMonth, $endOfMonth])->where('status_resep', 'Diserahkan')->count(),
                    'sub' => 'Obat Diserahkan ke Pasien',
                    'color' => 'teal',
                    'icon' => 'pill',
                ],
                [
                    'title' => 'Antrean Resep (Hari Ini)',
                    'value' => DB::table('tbl_pemeriksaan')->whereDate('tanggal_pemeriksaan', date('Y-m-d'))->where('status_resep', 'Menunggu')->count(),
                    'sub' => 'Resep Dalam Antrean Farmasi',
                    'color' => 'amber',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'Total Inventaris Obat',
                    'value' => DB::table('tbl_obat')->count(),
                    'sub' => 'Item Obat di Apotek',
                    'color' => 'blue',
                    'icon' => 'box',
                ],
                [
                    'title' => 'Stok Menipis (<= 10)',
                    'value' => DB::table('tbl_obat')->where('stok', '<=', 10)->count(),
                    'sub' => 'Perlu Restock Obat',
                    'color' => 'red',
                    'icon' => 'alert',
                ],
            ];
        } elseif ($activeRole === 'Kasir') {
            $totalPendapatanBulanIni = DB::table('tbl_pembayaran')
                ->whereBetween('tanggal_pembayaran', [$startOfMonth, $endOfMonth])
                ->where('status_pembayaran', 'Lunas')
                ->sum('total_tagihan');

            $cards = [
                [
                    'title' => 'Transaksi Lunas ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pembayaran')->whereBetween('tanggal_pembayaran', [$startOfMonth, $endOfMonth])->where('status_pembayaran', 'Lunas')->count(),
                    'sub' => 'Pembayaran Pasien Selesai',
                    'color' => 'emerald',
                    'icon' => 'receipt',
                ],
                [
                    'title' => 'Penerimaan Kasir ('.$namaBulanIni.')',
                    'value' => 'Rp '.number_format($totalPendapatanBulanIni, 0, ',', '.'),
                    'sub' => 'Total Kas Masuk',
                    'color' => 'teal',
                    'icon' => 'cash',
                ],
                [
                    'title' => 'Belum Lunas (Hari Ini)',
                    'value' => DB::table('tbl_pemeriksaan')
                        ->whereDate('tanggal_pemeriksaan', date('Y-m-d'))
                        ->whereNotIn('id_pemeriksaan', function ($q) {
                            $q->select('id_pemeriksaan')->from('tbl_pembayaran')->where('status_pembayaran', 'Lunas');
                        })->count(),
                    'sub' => 'Perlu Diproses Kasir',
                    'color' => 'amber',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'Pasien BPJS ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_antrean')->whereBetween('tanggal_antrean', [$startOfMonth, $endOfMonth])->where('jenis_pasien', 'BPJS')->count(),
                    'sub' => 'Klaim Pelayanan BPJS',
                    'color' => 'blue',
                    'icon' => 'shield',
                ],
            ];
        } else { // Pimpinan / Default
            $totalPendapatanBulanIni = DB::table('tbl_pembayaran')
                ->whereBetween('tanggal_pembayaran', [$startOfMonth, $endOfMonth])
                ->where('status_pembayaran', 'Lunas')
                ->sum('total_tagihan');

            $cards = [
                [
                    'title' => 'Total Kunjungan ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_antrean')->whereBetween('tanggal_antrean', [$startOfMonth, $endOfMonth])->count(),
                    'sub' => 'Kunjungan Pasien Poli',
                    'color' => 'blue',
                    'icon' => 'ticket',
                ],
                [
                    'title' => 'Pemeriksaan Selesai ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pemeriksaan')->whereBetween('tanggal_pemeriksaan', [$startOfMonth, $endOfMonth])->count(),
                    'sub' => 'Pelayanan Dokter',
                    'color' => 'purple',
                    'icon' => 'stethoscope',
                ],
                [
                    'title' => 'Pendapatan Kasir ('.$namaBulanIni.')',
                    'value' => 'Rp '.number_format($totalPendapatanBulanIni, 0, ',', '.'),
                    'sub' => 'Total Penerimaan Keuangan',
                    'color' => 'emerald',
                    'icon' => 'cash',
                ],
                [
                    'title' => 'Pasien Terdaftar ('.$namaBulanIni.')',
                    'value' => DB::table('tbl_pasien')->whereBetween('created_at', [$startOfMonth.' 00:00:00', $endOfMonth.' 23:59:59'])->count(),
                    'sub' => 'Registrasi Pasien Baru',
                    'color' => 'teal',
                    'icon' => 'user-add',
                ],
            ];
        }

        return view('admin.dashboard', compact(
            'activeRole',
            'namaBulanIni',
            'cards'
        ));
    }
}
