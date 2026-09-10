<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    /**
     * Tampilkan daftar antrean pembayaran Kasir / Billing & riwayat transaksi lunas.
     */
    public function index(Request $request)
    {
        $tanggalFilter = $request->input('tanggal', date('Y-m-d'));
        $search = $request->input('search');
        $statusFilter = $request->input('status_pembayaran', 'Semua');

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
                'tbl_pemeriksaan.created_at as tgl_pemeriksaan_full',
                'tbl_antrean.id_antrean',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pasien.id_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli',
                'tbl_pembayaran.id_pembayaran',
                'tbl_pembayaran.biaya_layanan_medis',
                'tbl_pembayaran.biaya_obat',
                'tbl_pembayaran.total_tagihan',
                'tbl_pembayaran.nominal_bayar',
                'tbl_pembayaran.kembalian',
                'tbl_pembayaran.status_pembayaran',
                'tbl_pembayaran.metode_pembayaran',
                'tbl_pembayaran.tanggal_pembayaran'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_pasien.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('tbl_pasien.nik', 'like', "%{$search}%")
                    ->orWhere('tbl_antrean.kode_antrean', 'like', "%{$search}%")
                    ->orWhere('tbl_pemeriksaan.diagnosis_penyakit', 'like', "%{$search}%");
            });
        }

        if ($statusFilter && $statusFilter !== 'Semua') {
            if ($statusFilter === 'Lunas') {
                $query->where('tbl_pembayaran.status_pembayaran', 'Lunas');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('tbl_pembayaran.status_pembayaran')
                        ->orWhere('tbl_pembayaran.status_pembayaran', '!=', 'Lunas');
                });
            }
        }

        $tagihans = $query->orderBy('tbl_pemeriksaan.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Hitung estimasi biaya obat untuk setiap tagihan dari resep dokter
        foreach ($tagihans as $item) {
            $detailResep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('tbl_detail_resep.id_pemeriksaan', $item->id_pemeriksaan)
                ->select('tbl_detail_resep.*', 'tbl_obat.harga_satuan', 'tbl_obat.nama_obat', 'tbl_obat.satuan')
                ->get();

            $item->detail_resep = $detailResep;
            // Biaya obat dihitung langsung dari resep dokter
            $item->calc_biaya_obat = $detailResep->sum(fn ($d) => $d->jumlah_obat * $d->harga_satuan);

            // Default estimasi biaya layanan medis: Rp 50.000 (Dapat diubah bebas oleh Kasir)
            $item->calc_biaya_layanan = $item->biaya_layanan_medis ?? 50000;
            $item->calc_total_tagihan = $item->jenis_pasien === 'BPJS' ? 0 : ($item->calc_biaya_layanan + $item->calc_biaya_obat);
        }

        // Hitung jumlah antrean baru yang belum lunas hari ini untuk lonceng notifikasi
        $pendingBayarCount = DB::table('tbl_pemeriksaan')
            ->leftJoin('tbl_pembayaran', 'tbl_pemeriksaan.id_pemeriksaan', '=', 'tbl_pembayaran.id_pemeriksaan')
            ->whereDate('tbl_pemeriksaan.created_at', date('Y-m-d'))
            ->where(function ($q) {
                $q->whereNull('tbl_pembayaran.status_pembayaran')
                    ->orWhere('tbl_pembayaran.status_pembayaran', '!=', 'Lunas');
            })
            ->count();

        return view('admin.pembayaran.index', compact('tagihans', 'tanggalFilter', 'search', 'statusFilter', 'pendingBayarCount'));
    }

    /**
     * Ambil rincian data tagihan untuk Modal AJAX Pembayaran Kasir.
     */
    public function show($idPemeriksaan)
    {
        $pemeriksaan = DB::table('tbl_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->leftJoin('tbl_pembayaran', 'tbl_pemeriksaan.id_pemeriksaan', '=', 'tbl_pembayaran.id_pemeriksaan')
            ->where('tbl_pemeriksaan.id_pemeriksaan', $idPemeriksaan)
            ->select(
                'tbl_pemeriksaan.*',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pasien.id_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.jenis_kelamin',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli',
                'tbl_pembayaran.id_pembayaran',
                'tbl_pembayaran.biaya_layanan_medis',
                'tbl_pembayaran.biaya_obat',
                'tbl_pembayaran.total_tagihan',
                'tbl_pembayaran.nominal_bayar',
                'tbl_pembayaran.kembalian',
                'tbl_pembayaran.status_pembayaran',
                'tbl_pembayaran.metode_pembayaran'
            )
            ->first();

        if (! $pemeriksaan) {
            return response()->json(['success' => false, 'message' => 'Data pemeriksaan tidak ditemukan.'], 404);
        }

        $detailResep = DB::table('tbl_detail_resep')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->where('tbl_detail_resep.id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
            ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.jenis_obat', 'tbl_obat.satuan', 'tbl_obat.harga_satuan')
            ->get();

        // Biaya obat dihitung langsung dari e-resep dokter
        $biayaObat = $detailResep->sum(fn ($i) => $i->jumlah_obat * $i->harga_satuan);
        $biayaLayanan = $pemeriksaan->biaya_layanan_medis ?? 50000;
        $isBPJS = ($pemeriksaan->jenis_pasien === 'BPJS');
        $totalTagihan = $isBPJS ? 0 : ($biayaLayanan + $biayaObat);

        return response()->json([
            'success' => true,
            'pemeriksaan' => $pemeriksaan,
            'detail_resep' => $detailResep,
            'biaya_obat' => $biayaObat,
            'biaya_layanan' => $biayaLayanan,
            'is_bpjs' => $isBPJS,
            'total_tagihan' => $totalTagihan,
        ]);
    }

    /**
     * Simpan / Proses Transaksi Pembayaran Kasir.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pemeriksaan' => ['required', 'exists:tbl_pemeriksaan,id_pemeriksaan'],
            'biaya_layanan_medis' => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'in:Tunai,Transfer,QRIS,BPJS'],
            'nominal_bayar' => ['nullable', 'numeric', 'min:0'],
        ], [
            'id_pemeriksaan.required' => 'Data pemeriksaan tidak valid.',
            'biaya_layanan_medis.required' => 'Biaya layanan medis wajib diisi.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
        ]);

        DB::beginTransaction();
        try {
            $pemeriksaan = DB::table('tbl_pemeriksaan')
                ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
                ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
                ->where('tbl_pemeriksaan.id_pemeriksaan', $validated['id_pemeriksaan'])
                ->select('tbl_pemeriksaan.*', 'tbl_antrean.kode_antrean', 'tbl_antrean.nomor_antrean', 'tbl_pasien.jenis_pasien', 'tbl_pasien.nama_lengkap')
                ->first();

            if (! $pemeriksaan) {
                DB::rollBack();

                return back()->with('error', 'Data pemeriksaan tidak ditemukan.');
            }

            // Hitung Biaya Obat dari e-resep dokter
            $detailResep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('tbl_detail_resep.id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                ->get();
            $biayaObat = $detailResep->sum(fn ($d) => $d->jumlah_obat * $d->harga_satuan);

            $biayaLayanan = (float) $validated['biaya_layanan_medis'];
            $isBPJS = ($pemeriksaan->jenis_pasien === 'BPJS' || $validated['metode_pembayaran'] === 'BPJS');

            $totalTagihan = $isBPJS ? 0 : ($biayaLayanan + $biayaObat);
            $nominalBayar = $isBPJS ? 0 : (float) ($validated['nominal_bayar'] ?? 0);

            if (! $isBPJS && $nominalBayar < $totalTagihan) {
                DB::rollBack();

                return back()->withInput()->with('error', 'Nominal bayar kurang dari total tagihan! (Tagihan: Rp '.number_format($totalTagihan, 0, ',', '.').', Dibayar: Rp '.number_format($nominalBayar, 0, ',', '.').')');
            }

            $kembalian = $isBPJS ? 0 : ($nominalBayar - $totalTagihan);
            $idKasir = Auth::id() ?? 'USR-KASIR';
            $metodeFinal = $isBPJS ? 'BPJS' : $validated['metode_pembayaran'];

            // Insert / Update tbl_pembayaran
            $pembayaranExisting = DB::table('tbl_pembayaran')->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)->first();

            if ($pembayaranExisting) {
                DB::table('tbl_pembayaran')
                    ->where('id_pembayaran', $pembayaranExisting->id_pembayaran)
                    ->update([
                        'tanggal_pembayaran' => now(),
                        'biaya_layanan_medis' => $biayaLayanan,
                        'biaya_obat' => $biayaObat,
                        'total_tagihan' => $totalTagihan,
                        'nominal_bayar' => $nominalBayar,
                        'kembalian' => $kembalian,
                        'status_pembayaran' => 'Lunas',
                        'metode_pembayaran' => $metodeFinal,
                        'id_pengguna' => $idKasir,
                        'updated_at' => now(),
                    ]);
                $idPembayaran = $pembayaranExisting->id_pembayaran;
            } else {
                $countToday = DB::table('tbl_pembayaran')->whereDate('created_at', date('Y-m-d'))->count() + 1;
                $idPembayaran = 'BYR-'.date('ymd').'-'.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);

                DB::table('tbl_pembayaran')->insert([
                    'id_pembayaran' => $idPembayaran,
                    'id_pemeriksaan' => $pemeriksaan->id_pemeriksaan,
                    'tanggal_pembayaran' => now(),
                    'biaya_layanan_medis' => $biayaLayanan,
                    'biaya_obat' => $biayaObat,
                    'total_tagihan' => $totalTagihan,
                    'nominal_bayar' => $nominalBayar,
                    'kembalian' => $kembalian,
                    'status_pembayaran' => 'Lunas',
                    'metode_pembayaran' => $metodeFinal,
                    'id_pengguna' => $idKasir,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            $kodeTampil = $pemeriksaan->kode_antrean ?? ('#'.$pemeriksaan->nomor_antrean);

            return back()->with('success', 'Pembayaran kasir untuk antrean ('.$kodeTampil.') a.n '.$pemeriksaan->nama_lengkap.' BERHASIL diproses!')
                ->with('print_id_pembayaran', $idPembayaran);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses transaksi kasir: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pembayaran.');
        }
    }

    /**
     * Cetak Kuitansi Pembayaran Resmi Klinik (Dengan Logo Klinik & Rincian Lengkap).
     */
    public function printKuitansi($idPembayaran)
    {
        $pembayaran = DB::table('tbl_pembayaran')
            ->join('tbl_pemeriksaan', 'tbl_pembayaran.id_pemeriksaan', '=', 'tbl_pemeriksaan.id_pemeriksaan')
            ->join('tbl_antrean', 'tbl_pemeriksaan.id_antrean', '=', 'tbl_antrean.id_antrean')
            ->join('tbl_pasien', 'tbl_pemeriksaan.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_pengguna as dokter', 'tbl_pemeriksaan.id_pengguna', '=', 'dokter.id_pengguna')
            ->leftJoin('tbl_pengguna as kasir', 'tbl_pembayaran.id_pengguna', '=', 'kasir.id_pengguna')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_pembayaran.id_pembayaran', $idPembayaran)
            ->select(
                'tbl_pembayaran.*',
                'tbl_pemeriksaan.tanggal_pemeriksaan',
                'tbl_pemeriksaan.diagnosis_penyakit',
                'tbl_pemeriksaan.tindakan_medis',
                'tbl_pemeriksaan.status_resep',
                'tbl_antrean.kode_antrean',
                'tbl_antrean.nomor_antrean',
                'tbl_antrean.jenis_pasien',
                'tbl_pasien.id_pasien',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.alamat_lengkap',
                'dokter.nama_lengkap as nama_dokter',
                'kasir.nama_lengkap as nama_kasir',
                'tbl_poli.nama_poli'
            )
            ->firstOrFail();

        // Ambil detail resep obat untuk struk kuitansi / faktur
        $detailResep = DB::table('tbl_detail_resep')
            ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
            ->where('tbl_detail_resep.id_pemeriksaan', $pembayaran->id_pemeriksaan)
            ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.satuan', 'tbl_obat.harga_satuan')
            ->get();

        return view('admin.pembayaran.kuitansi', compact('pembayaran', 'detailResep'));
    }
}
