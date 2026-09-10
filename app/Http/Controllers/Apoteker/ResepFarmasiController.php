<?php

namespace App\Http\Controllers\Apoteker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResepFarmasiController extends Controller
{
    /**
     * Tampilkan daftar antrean resep obat di Farmasi / Apoteker.
     */
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal', date('Y-m-d'));
        $search = $request->input('search');
        $statusFilter = $request->input('status_resep', 'Semua');

        $query = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->leftJoin('tbl_pembayaran', 'tbl_pemeriksaan.id_pemeriksaan', '=', 'tbl_pembayaran.id_pemeriksaan')
            ->whereDate('tbl_pemeriksaan.created_at', $tanggalFilter)
            ->select(
                'tbl_pemeriksaan.id_pemeriksaan',
                'tbl_pemeriksaan.tanggal_pemeriksaan',
                'tbl_pemeriksaan.diagnosis_penyakit',
                'tbl_pemeriksaan.tindakan_medis',
                'tbl_pemeriksaan.status_resep',
                'tbl_pemeriksaan.created_at as tgl_diperiksa',
                'tbl_antrean.id_antrean',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_antrean.status_antrean',
                'tbl_pasien.id_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.tanggal_lahir',
                'tbl_pasien.jenis_kelamin',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli',
                'tbl_pembayaran.id_pembayaran',
                'tbl_pembayaran.status_pembayaran',
                'tbl_pembayaran.metode_pembayaran',
                'tbl_pembayaran.total_tagihan'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_pasien.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('tbl_pasien.nik', 'like', "%{$search}%")
                    ->orWhere('tbl_antrean.kode_antrean', 'like', "%{$search}%");
            });
        }

        if ($statusFilter && $statusFilter !== 'Semua') {
            $query->where('tbl_pemeriksaan.status_resep', $statusFilter);
        }

        $reseps = $query->orderBy('tbl_pemeriksaan.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Attach rincian obat & total item untuk setiap pemeriksaan
        foreach ($reseps as $item) {
            $item->detail_resep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('tbl_detail_resep.id_pemeriksaan', $item->id_pemeriksaan)
                ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.satuan', 'tbl_obat.harga_satuan')
                ->get();

            $item->total_items = $item->detail_resep->count();
            $item->total_biaya_obat = $item->detail_resep->sum(function ($d) {
                return $d->jumlah_obat * $d->harga_satuan;
            });

            // Tentukan apakah sudah lunas di kasir
            $item->is_paid = ($item->status_pembayaran === 'Lunas');
        }

        // Hitung antrean resep yang sudah LUNAS di kasir dan siap diserahkan hari ini untuk lonceng notifikasi
        $readyToDispensCount = DB::table('tbl_pemeriksaan')
            ->join('tbl_pembayaran', 'tbl_pemeriksaan.id_pemeriksaan', '=', 'tbl_pembayaran.id_pemeriksaan')
            ->whereDate('tbl_pemeriksaan.created_at', date('Y-m-d'))
            ->where('tbl_pemeriksaan.status_resep', 'Menunggu')
            ->where('tbl_pembayaran.status_pembayaran', 'Lunas')
            ->count();

        return view('apoteker.resep.index', compact('reseps', 'tanggalFilter', 'search', 'statusFilter', 'readyToDispensCount'));
    }

    /**
     * Ambil detail resep obat untuk modal AJAX Apoteker.
     */
    public function show($idAntrean)
    {
        $pemeriksaan = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->leftJoin('tbl_pembayaran', 'tbl_pemeriksaan.id_pemeriksaan', '=', 'tbl_pembayaran.id_pemeriksaan')
            ->where('tbl_pemeriksaan.id_antrean', $idAntrean)
            ->select(
                'tbl_pemeriksaan.*',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.jenis_kelamin',
                'tbl_pasien.tanggal_lahir',
                'tbl_pasien.alamat_lengkap',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli',
                'tbl_pembayaran.id_pembayaran',
                'tbl_pembayaran.status_pembayaran',
                'tbl_pembayaran.metode_pembayaran'
            )
            ->first();

        if (! $pemeriksaan) {
            return response()->json(['success' => false, 'message' => 'Data resep tidak ditemukan.'], 404);
        }

        $detailResep = DB::table('tbl_detail_resep')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->where('tbl_detail_resep.id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
            ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan', 'tbl_obat.harga_satuan')
            ->get();

        $totalBiaya = $detailResep->sum(function ($item) {
            return $item->jumlah_obat * $item->harga_satuan;
        });

        return response()->json([
            'success' => true,
            'pemeriksaan' => $pemeriksaan,
            'detail_resep' => $detailResep,
            'total_biaya' => $totalBiaya,
            'is_paid' => ($pemeriksaan->status_pembayaran === 'Lunas'),
        ]);
    }

    /**
     * Proses Penyerahan Obat ke Pasien (Dispensasi Selesai) menggunakan DB Transaction.
     */
    public function diserahkan(Request $request, $idAntrean)
    {
        DB::beginTransaction();
        try {
            $antrean = DB::table('tbl_antrean')->where('id_antrean', $idAntrean)->first();
            if (! $antrean) {
                DB::rollBack();

                return back()->with('error', 'Data antrean tidak ditemukan.');
            }

            $pemeriksaan = DB::table('tbl_pemeriksaan')->where('id_antrean', $idAntrean)->first();
            if (! $pemeriksaan) {
                DB::rollBack();

                return back()->with('error', 'Data rekam medis pemeriksaan tidak ditemukan.');
            }

            // Validasi: Pastikan pembayaran di Kasir sudah lunas / terverifikasi BPJS
            $pembayaran = DB::table('tbl_pembayaran')->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)->first();
            if (! $pembayaran || $pembayaran->status_pembayaran !== 'Lunas') {
                DB::rollBack();

                return back()->with('error', 'PASIEN BELUM MENYELESAIKAN PEMBAYARAN: Harap arahkan pasien ke loket Kasir terlebih dahulu untuk menyelesaikan pembayaran atau verifikasi faktur sebelum obat diserahkan.');
            }

            // Update status_resep pada tbl_pemeriksaan menjadi 'Diserahkan'
            DB::table('tbl_pemeriksaan')
                ->where('id_antrean', $idAntrean)
                ->update([
                    'status_resep' => 'Diserahkan',
                    'updated_at' => now(),
                ]);

            // Update status_antrean pada tbl_antrean menjadi 'Selesai' (Pelayanan Selesai Lengkap)
            DB::table('tbl_antrean')
                ->where('id_antrean', $idAntrean)
                ->update([
                    'status_antrean' => 'Selesai',
                    'updated_at' => now(),
                ]);

            DB::commit();
            $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);

            return back()->with('success', 'Obat untuk antrean ('.$kodeTampil.') berhasil diverifikasi lunas & diserahkan kepada pasien.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal penyerahan obat: '.$e->getMessage());

            return back()->with('error', 'Gagal memproses penyerahan obat. Silakan coba lagi.');
        }
    }

    /**
     * Proses Pasien Tidak Mengambil Obat (Batal Ambil Obat).
     * Otomatis mengembalikan (Restock) kuantitas obat dari resep ke inventaris tbl_obat.
     */
    public function batalAmbil(Request $request, $idAntrean)
    {
        DB::beginTransaction();
        try {
            $pemeriksaan = DB::table('tbl_pemeriksaan')
                ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
                ->where('tbl_pemeriksaan.id_antrean', $idAntrean)
                ->select('tbl_pemeriksaan.*', 'tbl_antrean.kode_antrean', 'tbl_antrean.nomor_antrean')
                ->first();

            if (! $pemeriksaan) {
                DB::rollBack();

                return back()->with('error', 'Data resep tidak ditemukan.');
            }

            // Jika status resep sudah 'Tidak Diambil', tidak perlu dikembalikan dua kali
            if ($pemeriksaan->status_resep === 'Tidak Diambil') {
                DB::rollBack();

                return back()->with('warning', 'Resep ini sudah ditandai Tidak Diambil sebelumnya.');
            }

            // Ambil semua detail resep obat untuk dikembalikan stoknya
            $resepLama = DB::table('tbl_detail_resep')
                ->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                ->get();

            $totalItemRestocked = 0;
            foreach ($resepLama as $item) {
                DB::table('tbl_obat')
                    ->where('id_obat', $item->id_obat)
                    ->increment('stok', $item->jumlah_obat);
                $totalItemRestocked += $item->jumlah_obat;
            }

            // Update status_resep menjadi 'Tidak Diambil'
            DB::table('tbl_pemeriksaan')
                ->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                ->update([
                    'status_resep' => 'Tidak Diambil',
                    'updated_at' => now(),
                ]);

            // Pastikan status_antrean pada tbl_antrean juga menjadi 'Selesai'
            DB::table('tbl_antrean')
                ->where('id_antrean', $idAntrean)
                ->update([
                    'status_antrean' => 'Selesai',
                    'updated_at' => now(),
                ]);

            DB::commit();
            $kodeTampil = $pemeriksaan->kode_antrean ?? ('#'.$pemeriksaan->nomor_antrean);

            return back()->with('success', 'Resep antrean ('.$kodeTampil.') ditandai Tidak Diambil. Total '.$totalItemRestocked.' item obat telah dikembalikan otomatis ke inventaris.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal pembatalan obat pasien: '.$e->getMessage());

            return back()->with('error', 'Gagal memproses pembatalan penyerahan obat.');
        }
    }
}
