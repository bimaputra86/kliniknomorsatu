<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntreanKunjunganController extends Controller
{
    /**
     * Tampilkan kelola nomor antrean kunjungan untuk Resepsionis.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tanggalFilter = $request->input('tanggal', date('Y-m-d'));
        $jenisFilter = $request->input('jenis_pasien');
        $statusFilter = $request->input('status');
        $poliFilter = $request->input('poli');

        $query = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->select(
                'tbl_antrean.*',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.nomor_telepon',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal',
                'tbl_jadwal_dokter.hari',
                'tbl_jadwal_dokter.jam_mulai',
                'tbl_jadwal_dokter.jam_selesai'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_pasien.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('tbl_pasien.nik', 'like', "%{$search}%")
                    ->orWhere('tbl_pasien.no_bpjs', 'like', "%{$search}%")
                    ->orWhere('tbl_antrean.kode_antrean', 'like', "%{$search}%")
                    ->orWhere('tbl_antrean.nomor_antrean', 'like', "%{$search}%");
            });
        }

        if ($tanggalFilter) {
            $query->where('tbl_antrean.tanggal_antrean', $tanggalFilter);
        }

        if ($jenisFilter) {
            $query->where('tbl_antrean.jenis_pasien', $jenisFilter);
        }

        if ($statusFilter) {
            $query->where('tbl_antrean.status_antrean', $statusFilter);
        }

        if ($poliFilter) {
            $query->where(function ($q) use ($poliFilter) {
                $q->where('tbl_jadwal_dokter.id_poli', $poliFilter)
                    ->orWhere('tbl_jadwal_dokter.nama_poli', $poliFilter);
            });
        }

        $antreans = $query->orderBy('tbl_antrean.tanggal_antrean', 'desc')
            ->orderBy('tbl_antrean.id_antrean', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Statistical Data for Widgets
        $statsDate = $tanggalFilter ?: date('Y-m-d');
        $statsQuery = DB::table('tbl_antrean')->where('tanggal_antrean', $statsDate);

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'menunggu' => (clone $statsQuery)->where('status_antrean', 'Menunggu')->count(),
            'dipanggil' => (clone $statsQuery)->where('status_antrean', 'Dipanggil')->count(),
            'diperiksa' => (clone $statsQuery)->where('status_antrean', 'Diperiksa')->count(),
            'selesai' => (clone $statsQuery)->where('status_antrean', 'Selesai')->count(),
            'dibatalkan' => (clone $statsQuery)->whereIn('status_antrean', ['Batal', 'Dibatalkan'])->count(),
        ];

        // Master Data for Modals & Filters
        $pasiens = DB::table('tbl_pasien')->orderBy('nama_lengkap', 'asc')->get();
        $polis = DB::table('tbl_poli')->orderBy('nama_poli', 'asc')->get();

        $schedules = DB::table('tbl_jadwal_dokter')
            ->join('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->select('tbl_jadwal_dokter.*', 'tbl_pengguna.nama_lengkap as nama_dokter', 'tbl_poli.nama_poli as nama_poli_master')
            ->get();

        return view('admin.antrean.index', compact(
            'antreans',
            'stats',
            'pasiens',
            'polis',
            'schedules',
            'search',
            'tanggalFilter',
            'jenisFilter',
            'statusFilter',
            'poliFilter'
        ));
    }

    /**
     * AJAX Endpoint untuk pencarian pasien server-side di modal Walk-In.
     */
    public function searchPatients(Request $request)
    {
        $q = trim($request->input('q'));

        if (! $q || strlen($q) < 1) {
            return response()->json([]);
        }

        $patients = DB::table('tbl_pasien')
            ->where(function ($query) use ($q) {
                $query->where('nama_lengkap', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('no_bpjs', 'like', "%{$q}%")
                    ->orWhere('id_pasien', 'like', "%{$q}%");
            })
            ->select('id_pasien', 'nama_lengkap', 'nik', 'no_bpjs', 'jenis_pasien', 'nomor_telepon')
            ->orderBy('nama_lengkap', 'asc')
            ->limit(10)
            ->get();

        return response()->json($patients);
    }

    /**
     * AJAX/API Endpoint untuk mengambil dokter bertugas & sisa kuota real-time berdasarkan Poli & Tanggal Berobat.
     */
    public function getDoctorsByPoli(Request $request)
    {
        $poli = $request->input('poli');
        $tanggal = $request->input('tanggal_berobat', date('Y-m-d'));

        if (! $poli || ! $tanggal) {
            return response()->json([]);
        }

        $carbonDate = Carbon::parse($tanggal);
        $hariPilihan = $carbonDate->locale('id')->isoFormat('dddd');

        $schedules = DB::table('tbl_jadwal_dokter')
            ->join('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where(function ($q) use ($poli) {
                $q->where('tbl_jadwal_dokter.id_poli', $poli)
                    ->orWhere('tbl_jadwal_dokter.nama_poli', $poli);
            })
            ->where('tbl_jadwal_dokter.hari', $hariPilihan)
            ->select(
                'tbl_jadwal_dokter.id_jadwal',
                'tbl_jadwal_dokter.hari',
                'tbl_jadwal_dokter.jam_mulai',
                'tbl_jadwal_dokter.jam_selesai',
                'tbl_jadwal_dokter.kuota_maksimal',
                'tbl_pengguna.id_pengguna',
                'tbl_pengguna.nama_lengkap'
            )
            ->get();

        $result = $schedules->map(function ($item) use ($tanggal) {
            $antreanTerpakai = DB::table('tbl_antrean')
                ->where('tanggal_antrean', $tanggal)
                ->whereIn('status_antrean', ['Menunggu', 'Dipanggil', 'Diperiksa', 'Selesai'])
                ->count();

            $sisaKuota = max(0, $item->kuota_maksimal - $antreanTerpakai);
            $item->sisa_kuota = $sisaKuota;
            $item->is_full = ($sisaKuota <= 0);

            return $item;
        });

        return response()->json($result);
    }

    /**
     * Tambah Antrean Onsite / Walk-In oleh Resepsionis di Loket.
     */
    public function storeOnsite(Request $request)
    {
        $validated = $request->validate([
            'id_pasien' => ['required', 'string', 'exists:tbl_pasien,id_pasien'],
            'id_jadwal' => ['required', 'exists:tbl_jadwal_dokter,id_jadwal'],
            'tanggal_berobat' => ['required', 'date', 'after_or_equal:today'],
        ], [
            'id_pasien.required' => 'Pilih pasien kunjungan.',
            'id_jadwal.required' => 'Pilih dokter & jadwal praktik.',
            'tanggal_berobat.required' => 'Pilih tanggal kunjungan.',
            'tanggal_berobat.after_or_equal' => 'Tanggal kunjungan tidak boleh hari yang telah lalu.',
        ]);

        $pasien = DB::table('tbl_pasien')->where('id_pasien', $validated['id_pasien'])->first();
        if (! $pasien) {
            return back()->with('error', 'Data pasien tidak ditemukan.')->withInput();
        }

        // Cek Kuota Tanggal Maksimal H+7
        $maxDate = Carbon::today()->addDays(7);
        $chosenDate = Carbon::parse($validated['tanggal_berobat']);

        if ($chosenDate->gt($maxDate)) {
            return back()->with('error', 'Pemesanan antrean maksimal hanya dapat dilakukan untuk 7 hari ke depan.')->withInput();
        }

        // Proteksi 1 Antrean Aktif per Pasien
        $antreanAktif = DB::table('tbl_antrean')
            ->where('id_pasien', $pasien->id_pasien)
            ->whereIn('status_antrean', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->first();

        if ($antreanAktif) {
            $kodeTampil = $antreanAktif->kode_antrean ?? ('#'.$antreanAktif->nomor_antrean);

            return back()->with('error', 'Pasien '.$pasien->nama_lengkap.' masih memiliki antrean aktif ('.$kodeTampil.') pada tanggal '.Carbon::parse($antreanAktif->tanggal_antrean)->translatedFormat('d F Y').'.')->withInput();
        }

        // Validasi Hari Praktik Dokter
        $jadwal = DB::table('tbl_jadwal_dokter')->where('id_jadwal', $validated['id_jadwal'])->first();
        $hariTanggal = $chosenDate->locale('id')->isoFormat('dddd');

        if (strtolower($jadwal->hari) !== strtolower($hariTanggal)) {
            return back()->with('error', 'Tanggal yang Anda pilih ('.$hariTanggal.') tidak sesuai dengan hari praktik dokter ini ('.$jadwal->hari.').')->withInput();
        }

        // Validasi Kuota Dokter Real-Time
        $totalAntreanHariIni = DB::table('tbl_antrean')
            ->where('tanggal_antrean', $validated['tanggal_berobat'])
            ->whereIn('status_antrean', ['Menunggu', 'Dipanggil', 'Diperiksa', 'Selesai'])
            ->count();

        if ($totalAntreanHariIni >= $jadwal->kuota_maksimal) {
            return back()->with('error', 'Mohon maaf, kuota antrean untuk tanggal '.$chosenDate->translatedFormat('d F Y').' telah penuh.')->withInput();
        }

        // Generate Prefix berdasarkan jenis kepesertaan & Kode Poli
        $jenisPasien = $pasien->jenis_pasien ?? 'Umum/Mandiri';
        $prefix = ($jenisPasien === 'BPJS') ? 'B' : 'U';

        // Ambil data poli dari jadwal
        $poliData = null;
        if (! empty($jadwal->id_poli)) {
            $poliData = DB::table('tbl_poli')->where('id_poli', $jadwal->id_poli)->first();
        }
        if (! $poliData && ! empty($jadwal->nama_poli)) {
            $poliData = DB::table('tbl_poli')->where('nama_poli', $jadwal->nama_poli)->first();
        }

        $kodePoli = $poliData->kode_poli ?? null;
        if (! $kodePoli) {
            $namaPoliClean = str_ireplace('poli', '', $jadwal->nama_poli ?? 'UM');
            $namaPoliClean = trim($namaPoliClean);
            $kodePoli = strtoupper(substr($namaPoliClean, 0, 2));
            if (empty($kodePoli)) {
                $kodePoli = 'UM';
            }
        }

        // Hitung nomor urut per kombinasi Jenis Pasien + Poli pada tanggal berobat
        $totalPerJenisPoliHariIni = DB::table('tbl_antrean')
            ->join('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->where('tbl_antrean.tanggal_antrean', $validated['tanggal_berobat'])
            ->where('tbl_antrean.jenis_pasien', $jenisPasien)
            ->where(function ($q) use ($jadwal) {
                if (! empty($jadwal->id_poli)) {
                    $q->where('tbl_jadwal_dokter.id_poli', $jadwal->id_poli);
                } else {
                    $q->where('tbl_jadwal_dokter.nama_poli', $jadwal->nama_poli);
                }
            })
            ->count();

        $nomorUrut = $totalPerJenisPoliHariIni + 1;
        $kodeAntrean = $prefix.'-'.$kodePoli.'-'.str_pad((string) $nomorUrut, 3, '0', STR_PAD_LEFT);
        $nomorAntreanGlobal = $totalAntreanHariIni + 1;

        $idAntrean = DB::table('tbl_antrean')->insertGetId([
            'id_pasien' => $pasien->id_pasien,
            'id_jadwal' => $validated['id_jadwal'],
            'jenis_pasien' => $jenisPasien,
            'tanggal_antrean' => $validated['tanggal_berobat'],
            'nomor_antrean' => $nomorAntreanGlobal,
            'kode_antrean' => $kodeAntrean,
            'status_antrean' => 'Menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.antrean-kunjungan.index')->with('success', 'Antrean Loket Onsite ('.$kodeAntrean.') berhasil diterbitkan untuk pasien '.$pasien->nama_lengkap.'.');
    }

    /**
     * Memanggil antrean pasien di Loket / Resepsionis.
     */
    public function panggilAntrean(Request $request, $id)
    {
        $antrean = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_antrean.id_antrean', $id)
            ->select(
                'tbl_antrean.*',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal'
            )
            ->first();

        if (! $antrean) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Data antrean tidak ditemukan.'], 404);
            }

            return back()->with('error', 'Data antrean tidak ditemukan.');
        }

        DB::table('tbl_antrean')
            ->where('id_antrean', $id)
            ->update([
                'status_antrean' => 'Dipanggil',
                'updated_at' => now(),
            ]);

        $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);
        $namaPoli = $antrean->nama_poli_master ?? ($antrean->nama_poli_jadwal ?? 'Poli Kunjungan');

        $textPanggilan = 'Nomor antrean '.implode(' ', str_split(str_replace('-', '', $kodeTampil))).', atas nama '.$antrean->nama_pasien.', silakan menuju ke '.$namaPoli;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Antrean {$kodeTampil} berhasil dipanggil.",
                'kode_antrean' => $kodeTampil,
                'nama_pasien' => $antrean->nama_pasien,
                'nama_poli' => $namaPoli,
                'text_panggilan' => $textPanggilan,
            ]);
        }

        return back()->with('success', "Antrean ({$kodeTampil}) atas nama {$antrean->nama_pasien} berhasil dipanggil.")
            ->with('audio_panggil', [
                'kode' => $kodeTampil,
                'nama' => $antrean->nama_pasien,
                'poli' => $namaPoli,
                'text' => $textPanggilan,
            ]);
    }

    /**
     * Update Status Antrean oleh Resepsionis (Memanggil, Memeriksa, Membatalkan).
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_antrean' => ['required', 'in:Menunggu,Dipanggil,Diperiksa,Selesai,Dibatalkan,Batal'],
        ]);

        $antrean = DB::table('tbl_antrean')->where('id_antrean', $id)->first();
        if (! $antrean) {
            return back()->with('error', 'Data antrean tidak ditemukan.');
        }

        DB::table('tbl_antrean')
            ->where('id_antrean', $id)
            ->update([
                'status_antrean' => $validated['status_antrean'],
                'updated_at' => now(),
            ]);

        $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);

        return back()->with('success', 'Status antrean ('.$kodeTampil.') berhasil diperbarui menjadi '.$validated['status_antrean'].'.');
    }

    /**
     * Cetak Tiket Struk Antrean Loket Resepsionis.
     */
    public function printTicket($id)
    {
        $antrean = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_antrean.id_antrean', $id)
            ->select(
                'tbl_antrean.*',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal'
            )
            ->first();

        if (! $antrean) {
            return redirect()->route('admin.antrean-kunjungan.index')->with('error', 'Tiket antrean tidak ditemukan.');
        }

        return view('admin.antrean.print', compact('antrean'));
    }

    /**
     * Tampilan Layar Display Monitor/TV untuk Pasien di Ruang Tunggu.
     */
    public function display()
    {
        $polis = DB::table('tbl_poli')->orderBy('nama_poli', 'asc')->get();

        return view('admin.antrean.display', compact('polis'));
    }

    /**
     * JSON Endpoint Real-Time Data untuk Layar Display Monitor/TV.
     */
    public function getDisplayData(Request $request)
    {
        $today = date('Y-m-d');

        // Antrean yang sedang dipanggil saat ini (Ambil antrean terkini yang berstatus Dipanggil tanpa batasan tanggal agar panggilan tanggal berapapun langsung muncul)
        $sedangDipanggil = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_antrean.status_antrean', 'Dipanggil')
            ->select(
                'tbl_antrean.id_antrean',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_antrean.tanggal_antrean',
                'tbl_antrean.updated_at',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal'
            )
            ->orderBy('tbl_antrean.updated_at', 'desc')
            ->first();

        if ($sedangDipanggil) {
            $sedangDipanggil->nama_poli = $sedangDipanggil->nama_poli_master ?? ($sedangDipanggil->nama_poli_jadwal ?? 'Loket Kunjungan');
            $sedangDipanggil->kode = $sedangDipanggil->kode_antrean ?? ('#'.$sedangDipanggil->nomor_antrean);
            $sedangDipanggil->call_token = $sedangDipanggil->id_antrean.'_'.strtotime($sedangDipanggil->updated_at);
        }

        // Rekap Antrean Aktif per Poliklinik
        $polis = DB::table('tbl_poli')->orderBy('nama_poli', 'asc')->get();

        $poliSummary = $polis->map(function ($p) {
            // Antrean sedang dilayani (Dipanggil / Diperiksa)
            $aktif = DB::table('tbl_antrean')
                ->join('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
                ->where(function ($q) use ($p) {
                    $q->where('tbl_jadwal_dokter.id_poli', $p->id_poli)
                        ->orWhere('tbl_jadwal_dokter.nama_poli', $p->nama_poli);
                })
                ->whereIn('tbl_antrean.status_antrean', ['Dipanggil', 'Diperiksa'])
                ->orderBy('tbl_antrean.updated_at', 'desc')
                ->first();

            // Antrean berikutnya yang menunggu
            $berikutnya = DB::table('tbl_antrean')
                ->join('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
                ->where(function ($q) use ($p) {
                    $q->where('tbl_jadwal_dokter.id_poli', $p->id_poli)
                        ->orWhere('tbl_jadwal_dokter.nama_poli', $p->nama_poli);
                })
                ->where('tbl_antrean.status_antrean', 'Menunggu')
                ->orderBy('tbl_antrean.id_antrean', 'asc')
                ->first();

            // Sisa antrean yang menunggu
            $sisaMenunggu = DB::table('tbl_antrean')
                ->join('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
                ->where(function ($q) use ($p) {
                    $q->where('tbl_jadwal_dokter.id_poli', $p->id_poli)
                        ->orWhere('tbl_jadwal_dokter.nama_poli', $p->nama_poli);
                })
                ->where('tbl_antrean.status_antrean', 'Menunggu')
                ->count();

            return [
                'id_poli' => $p->id_poli,
                'nama_poli' => $p->nama_poli,
                'kode_poli' => $p->kode_poli ?? strtoupper(substr(str_ireplace('poli', '', $p->nama_poli), 0, 2)),
                'kode_aktif' => $aktif->kode_antrean ?? ($aktif ? '#'.$aktif->nomor_antrean : '-'),
                'status_aktif' => $aktif->status_antrean ?? 'Standby',
                'kode_berikutnya' => $berikutnya->kode_antrean ?? ($berikutnya ? '#'.$berikutnya->nomor_antrean : '-'),
                'sisa_menunggu' => $sisaMenunggu,
            ];
        });

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->toIso8601String(),
            'sedang_dipanggil' => $sedangDipanggil,
            'poli_summary' => $poliSummary,
        ]);
    }
}
