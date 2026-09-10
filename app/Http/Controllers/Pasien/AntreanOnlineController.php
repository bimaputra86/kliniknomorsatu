<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AntreanOnlineController extends Controller
{
    /**
     * Tampilkan form pendaftaran antrean online pasien.
     */
    public function create()
    {
        $pasien = Auth::guard('pasien')->user();

        // Ambil daftar poli yang tersedia dari tbl_poli
        $polis = DB::table('tbl_poli')
            ->orderBy('nama_poli', 'asc')
            ->get();

        return view('pasien.antrean.create', compact('pasien', 'polis'));
    }

    /**
     * AJAX/API endpoint untuk mengambil dokter, jadwal, dan sisa kuota real-time berdasarkan Poli & Tanggal Berobat.
     */
    public function getDoctorsByPoli(Request $request)
    {
        $poli = $request->input('poli');
        $tanggal = $request->input('tanggal_berobat', date('Y-m-d'));

        if (! $poli || ! $tanggal) {
            return response()->json([]);
        }

        // Tentukan nama hari dari tanggal yang dipilih (misal: "Senin")
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

        // Hitung sisa kuota real-time untuk tanggal berobat tersebut
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
     * Memproses pengambilan nomor antrean digital untuk pasien.
     */
    public function store(Request $request)
    {
        $pasien = Auth::guard('pasien')->user();

        $validated = $request->validate([
            'id_jadwal' => ['required', 'exists:tbl_jadwal_dokter,id_jadwal'],
            'tanggal_berobat' => ['required', 'date', 'after_or_equal:today'],
        ], [
            'id_jadwal.required' => 'Pilih dokter & jadwal praktik yang tersedia.',
            'tanggal_berobat.required' => 'Pilih tanggal berobat.',
            'tanggal_berobat.after_or_equal' => 'Tanggal berobat tidak boleh hari yang telah lalu.',
        ]);

        // Cek Kuota Tanggal Maksimal H+7 (Standar Mobile JKN)
        $maxDate = Carbon::today()->addDays(7);
        $chosenDate = Carbon::parse($validated['tanggal_berobat']);

        if ($chosenDate->gt($maxDate)) {
            return back()->with('error', 'Pemesanan antrean online maksimal hanya dapat dilakukan untuk 7 hari ke depan.')->withInput();
        }

        // 1. Proteksi Mobile JKN: Mencegah pemesanan ganda (1 Antrean Aktif per Pasien)
        $antreanAktif = DB::table('tbl_antrean')
            ->where('id_pasien', $pasien->id_pasien)
            ->whereIn('status_antrean', ['Menunggu', 'Dipanggil', 'Diperiksa'])
            ->first();

        if ($antreanAktif) {
            $kodeTampil = $antreanAktif->kode_antrean ?? ('#'.$antreanAktif->nomor_antrean);

            return back()->with('error', 'Anda masih memiliki tiket antrean aktif ('.$kodeTampil.') pada tanggal '.Carbon::parse($antreanAktif->tanggal_antrean)->translatedFormat('d F Y').'. Harap selesaikan atau batalkan antrean sebelumnya.')->withInput();
        }

        // 2. Validasi Kesesuaian Hari Tanggal Berobat dengan Hari Praktik Dokter
        $jadwal = DB::table('tbl_jadwal_dokter')->where('id_jadwal', $validated['id_jadwal'])->first();
        $hariTanggal = $chosenDate->locale('id')->isoFormat('dddd');

        if (strtolower($jadwal->hari) !== strtolower($hariTanggal)) {
            return back()->with('error', 'Tanggal yang Anda pilih ('.$hariTanggal.') tidak sesuai dengan hari praktik dokter ini ('.$jadwal->hari.').')->withInput();
        }

        // 3. Validasi Kuota Harian Dokter Real-Time
        $totalAntreanHariIni = DB::table('tbl_antrean')
            ->where('tanggal_antrean', $validated['tanggal_berobat'])
            ->whereIn('status_antrean', ['Menunggu', 'Dipanggil', 'Diperiksa', 'Selesai'])
            ->count();

        if ($totalAntreanHariIni >= $jadwal->kuota_maksimal) {
            return back()->with('error', 'Mohon maaf, kuota antrean untuk tanggal '.$chosenDate->translatedFormat('d F Y').' telah penuh ('.$jadwal->kuota_maksimal.' pasien).')->withInput();
        }

        // 4. GENERATE PREFIX & KODE ANTREAN BERDASARKAN JENIS KEPESERTAAN & KODE POLI
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

        // Hitung nomor urut antrean untuk kombinasi jenis pasien & poli pada tanggal tersebut
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

        // Insert ke tbl_antrean menggunakan Query Builder
        DB::table('tbl_antrean')->insert([
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

        $jalurTipe = ($jenisPasien === 'BPJS') ? 'Jalur BPJS (Verifikasi Berkas SEP)' : 'Jalur Prioritas Umum/Mandiri';

        return redirect()->route('pasien.dashboard')->with('success', 'Berhasil! Nomor Tiket Antrean Anda untuk tanggal '.$chosenDate->translatedFormat('d F Y').' adalah '.$kodeAntrean.' ['.$jalurTipe.'] untuk '.$jadwal->nama_poli.'.');
    }

    /**
     * Membatalkan tiket antrean aktif milik pasien (Query Builder).
     */
    public function cancel(Request $request, $id)
    {
        $pasien = Auth::guard('pasien')->user();

        $antrean = DB::table('tbl_antrean')
            ->where('id_antrean', $id)
            ->where('id_pasien', $pasien->id_pasien)
            ->first();

        if (! $antrean) {
            return back()->with('error', 'Tiket antrean tidak ditemukan.');
        }

        // Proteksi 1: Antrean yang sudah dipanggil / diperiksa / selesai tidak dapat dibatalkan oleh pasien
        if ($antrean->status_antrean !== 'Menunggu') {
            $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);

            return back()->with('error', 'Tiket antrean ('.$kodeTampil.') saat ini berstatus '.$antrean->status_antrean.' dan tidak dapat dibatalkan.');
        }

        // Proteksi 2: Pembatalan tidak dapat dilakukan jika tanggal kunjungan SAMA DENGAN tanggal hari ini
        $today = date('Y-m-d');
        if ($antrean->tanggal_antrean === $today) {
            return back()->with('error', 'Pembatalan antrean secara online tidak dapat dilakukan pada hari H kunjungan ('.Carbon::parse($today)->translatedFormat('d F Y').'). Silakan hubungi loket resepsionis jika berhalangan hadir.');
        }

        DB::table('tbl_antrean')
            ->where('id_antrean', $id)
            ->update([
                'status_antrean' => 'Dibatalkan',
                'updated_at' => now(),
            ]);

        $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);

        return redirect()->route('pasien.dashboard')->with('success', 'Tiket antrean ('.$kodeTampil.') berhasil dibatalkan. Anda sekarang dapat mendaftar untuk jadwal/tanggal baru.');
    }

    /**
     * Tampilkan cetak tiket antrean digital (Thermal Ticket View).
     */
    public function printTicket($id)
    {
        $pasien = Auth::guard('pasien')->user();

        $antrean = DB::table('tbl_antrean')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_antrean.id_antrean', $id)
            ->where('tbl_antrean.id_pasien', $pasien->id_pasien)
            ->select(
                'tbl_antrean.*',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal'
            )
            ->first();

        if (! $antrean) {
            return redirect()->route('pasien.dashboard')->with('error', 'Tiket antrean tidak ditemukan.');
        }

        return view('pasien.antrean.print', compact('pasien', 'antrean'));
    }
}
