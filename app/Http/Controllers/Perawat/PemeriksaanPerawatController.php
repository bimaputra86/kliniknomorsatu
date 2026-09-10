<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanPerawatController extends Controller
{
    /**
     * Tampilkan daftar antrean pasien untuk Skrining Tanda Vital Perawat.
     */
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal', date('Y-m-d'));
        $search = $request->input('search');

        $query = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->leftJoin('tbl_pemeriksaan', 'tbl_antrean.id_antrean', '=', 'tbl_pemeriksaan.id_antrean')
            ->where('tbl_antrean.tanggal_antrean', $tanggalFilter)
            ->whereIn('tbl_antrean.status_antrean', ['Dipanggil', 'Diperiksa', 'Selesai'])
            ->select(
                'tbl_antrean.id_antrean',
                'tbl_antrean.tanggal_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_antrean.status_antrean',
                'tbl_pasien.id_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.jenis_kelamin',
                'tbl_pasien.tanggal_lahir',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal',
                'tbl_pemeriksaan.id_pemeriksaan',
                'tbl_pemeriksaan.tekanan_darah',
                'tbl_pemeriksaan.suhu_tubuh',
                'tbl_pemeriksaan.nadi',
                'tbl_pemeriksaan.berat_badan',
                'tbl_pemeriksaan.tinggi_badan',
                'tbl_pemeriksaan.keluhan_utama'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_pasien.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('tbl_pasien.nik', 'like', "%{$search}%")
                    ->orWhere('tbl_antrean.kode_antrean', 'like', "%{$search}%");
            });
        }

        $antreans = $query->orderBy('tbl_antrean.id_antrean', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('perawat.pemeriksaan.index', compact('antreans', 'tanggalFilter', 'search'));
    }

    /**
     * Simpan/Update Tanda Vital & Anamnesis Awal oleh Perawat.
     */
    public function storeVitalSign(Request $request)
    {
        $validated = $request->validate([
            'id_antrean' => ['required', 'exists:tbl_antrean,id_antrean'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'suhu_tubuh' => ['nullable', 'string', 'max:10'],
            'nadi' => ['nullable', 'string', 'max:10'],
            'berat_badan' => ['nullable', 'string', 'max:10'],
            'tinggi_badan' => ['nullable', 'string', 'max:10'],
            'keluhan_utama' => ['required', 'string'],
        ], [
            'id_antrean.required' => 'Antrean tidak valid.',
            'keluhan_utama.required' => 'Keluhan utama / anamnesis awal pasien wajib diisi.',
        ]);

        $antrean = DB::table('tbl_antrean')->where('id_antrean', $validated['id_antrean'])->first();
        if (! $antrean) {
            return back()->with('error', 'Data antrean tidak ditemukan.');
        }

        // Cek apakah data pemeriksaan untuk antrean ini sudah ada
        $pemeriksaan = DB::table('tbl_pemeriksaan')->where('id_antrean', $antrean->id_antrean)->first();

        if ($pemeriksaan) {
            DB::table('tbl_pemeriksaan')
                ->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                ->update([
                    'tekanan_darah' => $validated['tekanan_darah'] ?? null,
                    'suhu_tubuh' => $validated['suhu_tubuh'] ?? null,
                    'nadi' => $validated['nadi'] ?? null,
                    'berat_badan' => $validated['berat_badan'] ?? null,
                    'tinggi_badan' => $validated['tinggi_badan'] ?? null,
                    'keluhan_utama' => $validated['keluhan_utama'],
                    'updated_at' => now(),
                ]);
        } else {
            // Generate ID Pemeriksaan unik: PMK-ymd-XXXX (Max 15 karakter)
            $countToday = DB::table('tbl_pemeriksaan')->whereDate('created_at', date('Y-m-d'))->count() + 1;
            $idPemeriksaan = 'PMK-'.date('ymd').'-'.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);

            // Ambil ID Pengguna (Perawat/Petugas yang mengisi)
            $idPengguna = auth()->id() ?? 'USR-001';

            DB::table('tbl_pemeriksaan')->insert([
                'id_pemeriksaan' => $idPemeriksaan,
                'id_antrean' => $antrean->id_antrean,
                'tanggal_pemeriksaan' => date('Y-m-d'),
                'id_pasien' => $antrean->id_pasien,
                'id_pengguna' => $idPengguna,
                'tekanan_darah' => $validated['tekanan_darah'] ?? null,
                'suhu_tubuh' => $validated['suhu_tubuh'] ?? null,
                'nadi' => $validated['nadi'] ?? null,
                'berat_badan' => $validated['berat_badan'] ?? null,
                'tinggi_badan' => $validated['tinggi_badan'] ?? null,
                'keluhan_utama' => $validated['keluhan_utama'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pastikan status antrean diubah ke 'Diperiksa' agar muncul di antrean Dokter
        if ($antrean->status_antrean === 'Dipanggil') {
            DB::table('tbl_antrean')
                ->where('id_antrean', $antrean->id_antrean)
                ->update([
                    'status_antrean' => 'Diperiksa',
                    'updated_at' => now(),
                ]);
        }

        $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);

        return back()->with('success', 'Skrining Tanda Vital untuk antrean '.$kodeTampil.' berhasil disimpan dan siap diperiksa Dokter.');
    }
}
