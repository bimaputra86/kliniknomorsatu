<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemeriksaanDokterController extends Controller
{
    /**
     * Tampilkan Dashboard Antrean Pasien khusus Dokter (Filter Poli & Dokter Bertugas).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
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
                'tbl_pemeriksaan.berat_badan',
                'tbl_pemeriksaan.keluhan_utama',
                'tbl_pemeriksaan.diagnosis_penyakit'
            );

        // Filter khusus dokter jika user ber-peran Dokter
        if ($user && strtolower($user->peran ?? '') === 'dokter') {
            $query->where(function ($q) use ($user) {
                $q->where('tbl_jadwal_dokter.id_pengguna', $user->id_pengguna)
                    ->orWhere('tbl_pemeriksaan.id_pengguna', $user->id_pengguna);
            });
        }

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

        return view('dokter.pemeriksaan.index', compact('antreans', 'tanggalFilter', 'search'));
    }

    /**
     * Tampilkan Halaman Form E-Rekam Medis & E-Resep Obat Pasien.
     */
    public function create($idAntrean)
    {
        $antrean = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->leftJoin('tbl_jadwal_dokter', 'tbl_antrean.id_jadwal', '=', 'tbl_jadwal_dokter.id_jadwal')
            ->leftJoin('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->where('tbl_antrean.id_antrean', $idAntrean)
            ->select(
                'tbl_antrean.*',
                'tbl_pasien.nama_lengkap as nama_pasien',
                'tbl_pasien.nik',
                'tbl_pasien.no_bpjs',
                'tbl_pasien.jenis_kelamin',
                'tbl_pasien.tanggal_lahir',
                'tbl_pasien.golongan_darah',
                'tbl_pasien.alamat_lengkap',
                'tbl_pengguna.nama_lengkap as nama_dokter',
                'tbl_poli.nama_poli as nama_poli_master',
                'tbl_jadwal_dokter.nama_poli as nama_poli_jadwal'
            )
            ->first();

        if (! $antrean) {
            return redirect()->route('dokter.pemeriksaan.index')->with('error', 'Data antrean tidak ditemukan.');
        }

        // Data vital sign yang diinput perawat
        $pemeriksaan = DB::table('tbl_pemeriksaan')->where('id_antrean', $idAntrean)->first();

        // Data E-Resep eksisting jika sudah pernah diinput
        $existingResep = [];
        if ($pemeriksaan) {
            $existingResep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('tbl_detail_resep.id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.satuan', 'tbl_obat.harga_satuan')
                ->get();
        }

        // Master obat untuk dropdown E-Resep (dibatasi 15 data awal)
        $topObats = DB::table('tbl_obat')->orderBy('nama_obat', 'asc')->limit(15)->get();

        // Jika ada obat di resep eksisting yang tidak masuk top 15, gabungkan agar pilihan tidak hilang
        $existingObatIds = collect($existingResep)->pluck('id_obat')->toArray();
        if (! empty($existingObatIds)) {
            $extraObats = DB::table('tbl_obat')->whereIn('id_obat', $existingObatIds)->get();
            $obats = $topObats->merge($extraObats)->unique('id_obat')->values();
        } else {
            $obats = $topObats;
        }

        // Riwayat rekam medis pasien terdahulu
        $riwayatMedis = DB::table('tbl_pemeriksaan')
            ->join('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->where('tbl_pemeriksaan.id_pasien', $antrean->id_pasien)
            ->where('tbl_pemeriksaan.id_antrean', '!=', $idAntrean)
            ->select('tbl_pemeriksaan.*', 'tbl_pengguna.nama_lengkap as nama_dokter')
            ->orderBy('tbl_pemeriksaan.tanggal_pemeriksaan', 'desc')
            ->limit(5)
            ->get();

        foreach ($riwayatMedis as $rm) {
            $rm->detail_resep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('tbl_detail_resep.id_pemeriksaan', $rm->id_pemeriksaan)
                ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.satuan')
                ->get();
        }

        return view('dokter.pemeriksaan.create', compact('antrean', 'pemeriksaan', 'existingResep', 'obats', 'riwayatMedis'));
    }

    /**
     * Live Search Obat Server-Side (Maksimal 15 hasil per kata kunci).
     */
    public function searchObat(Request $request)
    {
        $q = trim($request->input('q', ''));
        $query = DB::table('tbl_obat');

        if ($q !== '') {
            $query->where(function ($queryBuilder) use ($q) {
                $queryBuilder->where('nama_obat', 'like', "%{$q}%")
                    ->orWhere('jenis_obat', 'like', "%{$q}%")
                    ->orWhere('id_obat', 'like', "%{$q}%");
            });
        }

        $obats = $query->orderBy('nama_obat', 'asc')->limit(15)->get();

        return response()->json([
            'success' => true,
            'data' => $obats,
        ]);
    }

    /**
     * Simpan E-Rekam Medis & E-Resep Obat oleh Dokter (Selesai Periksa).
     */
    public function store(Request $request)
    {
        // Bersihkan data resep kosong sebelum validasi
        if ($request->has('resep') && is_array($request->input('resep'))) {
            $filteredResep = array_filter($request->input('resep'), function ($item) {
                return ! empty($item['id_obat']);
            });
            // Re-index array agar sequential
            $request->merge(['resep' => array_values($filteredResep)]);
        }

        $validated = $request->validate([
            'id_antrean' => ['required', 'exists:tbl_antrean,id_antrean'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'suhu_tubuh' => ['nullable', 'string', 'max:10'],
            'nadi' => ['nullable', 'string', 'max:10'],
            'berat_badan' => ['nullable', 'string', 'max:10'],
            'tinggi_badan' => ['nullable', 'string', 'max:10'],
            'keluhan_utama' => ['required', 'string'],
            'diagnosis_penyakit' => ['required', 'string'],
            'tindakan_medis' => ['nullable', 'string'],
            'resep' => ['nullable', 'array'],
            'resep.*.id_obat' => ['required_with:resep', 'exists:tbl_obat,id_obat'],
            'resep.*.jumlah_obat' => ['required_with:resep', 'numeric', 'min:1'],
            'resep.*.dosis_aturan_pakai' => ['required_with:resep', 'string', 'max:100'],
        ], [
            'id_antrean.required' => 'Data antrean tidak valid.',
            'keluhan_utama.required' => 'Keluhan utama pasien wajib diisi.',
            'diagnosis_penyakit.required' => 'Diagnosa penyakit wajib diisi oleh Dokter.',
            'resep.*.id_obat.required_with' => 'Pilih jenis obat pada E-Resep.',
            'resep.*.jumlah_obat.required_with' => 'Jumlah obat wajib diisi.',
            'resep.*.dosis_aturan_pakai.required_with' => 'Dosis/Aturan pakai obat wajib diisi.',
        ]);

        $antrean = DB::table('tbl_antrean')->where('id_antrean', $validated['id_antrean'])->first();
        if (! $antrean) {
            return back()->with('error', 'Data antrean tidak ditemukan.');
        }

        // -------------------------------------------------------------
        // VALIDASI KETERSEDIAAN STOK OBAT SERVER-SIDE (INSERT & EDIT)
        // -------------------------------------------------------------
        $existingPemeriksaan = DB::table('tbl_pemeriksaan')->where('id_antrean', $antrean->id_antrean)->first();
        $idPemeriksaanLama = $existingPemeriksaan->id_pemeriksaan ?? null;

        // Ambil alokasi stok resep lama pasien ini jika sedang meng-EDIT rekam medis
        $oldResepQtyMap = [];
        if ($idPemeriksaanLama) {
            $oldItems = DB::table('tbl_detail_resep')->where('id_pemeriksaan', $idPemeriksaanLama)->get();
            foreach ($oldItems as $oItem) {
                $oldResepQtyMap[$oItem->id_obat] = ($oldResepQtyMap[$oItem->id_obat] ?? 0) + (int) $oItem->jumlah_obat;
            }
        }

        // Agregat total kuantitas obat baru yang diminta Dokter
        $requestedQtyMap = [];
        if (! empty($validated['resep'])) {
            foreach ($validated['resep'] as $rItem) {
                $idObat = $rItem['id_obat'];
                $requestedQtyMap[$idObat] = ($requestedQtyMap[$idObat] ?? 0) + (int) $rItem['jumlah_obat'];
            }
        }

        // Pengecekan stok: Stok Efektif Tersedia = Stok di tbl_obat + Stok lama resep pasien ini
        $stockErrors = [];
        foreach ($requestedQtyMap as $idObat => $reqQty) {
            $obat = DB::table('tbl_obat')->where('id_obat', $idObat)->first();
            if (! $obat) {
                $stockErrors['resep'] = 'Data obat tidak ditemukan di inventaris.';
                break;
            }

            $oldQty = $oldResepQtyMap[$idObat] ?? 0;
            $effectiveStock = (int) $obat->stok + $oldQty;

            if ($reqQty > $effectiveStock) {
                $stockErrors['resep'] = "Stok obat \"{$obat->nama_obat}\" tidak mencukupi! (Stok Tersedia: {$effectiveStock} {$obat->satuan}, Diminta: {$reqQty} {$obat->satuan}). Silakan kurangi jumlah obat atau lakukan restock.";
                break;
            }
        }

        if (! empty($stockErrors)) {
            return back()->withInput()->withErrors($stockErrors);
        }

        return DB::transaction(function () use ($validated, $antrean) {

            $idDokter = Auth::id() ?? 'USR-001';

            // Cek/Update Record Pemeriksaan
            $pemeriksaan = DB::table('tbl_pemeriksaan')->where('id_antrean', $antrean->id_antrean)->first();
            $idPemeriksaan = $pemeriksaan->id_pemeriksaan ?? null;

            if ($idPemeriksaan) {
                DB::table('tbl_pemeriksaan')
                    ->where('id_pemeriksaan', $idPemeriksaan)
                    ->update([
                        'id_pengguna' => $idDokter,
                        'tekanan_darah' => ! empty($validated['tekanan_darah']) ? $validated['tekanan_darah'] : ($pemeriksaan->tekanan_darah ?? null),
                        'suhu_tubuh' => ! empty($validated['suhu_tubuh']) ? $validated['suhu_tubuh'] : ($pemeriksaan->suhu_tubuh ?? null),
                        'nadi' => ! empty($validated['nadi']) ? $validated['nadi'] : ($pemeriksaan->nadi ?? null),
                        'berat_badan' => ! empty($validated['berat_badan']) ? $validated['berat_badan'] : ($pemeriksaan->berat_badan ?? null),
                        'tinggi_badan' => ! empty($validated['tinggi_badan']) ? $validated['tinggi_badan'] : ($pemeriksaan->tinggi_badan ?? null),
                        'keluhan_utama' => $validated['keluhan_utama'],
                        'diagnosis_penyakit' => $validated['diagnosis_penyakit'],
                        'tindakan_medis' => $validated['tindakan_medis'] ?? null,
                        'updated_at' => now(),
                    ]);
            } else {
                $countToday = DB::table('tbl_pemeriksaan')->whereDate('created_at', date('Y-m-d'))->count() + 1;
                $idPemeriksaan = 'PMK-'.date('ymd').'-'.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);

                DB::table('tbl_pemeriksaan')->insert([
                    'id_pemeriksaan' => $idPemeriksaan,
                    'id_antrean' => $antrean->id_antrean,
                    'tanggal_pemeriksaan' => date('Y-m-d'),
                    'id_pasien' => $antrean->id_pasien,
                    'id_pengguna' => $idDokter,
                    'tekanan_darah' => $validated['tekanan_darah'] ?? null,
                    'suhu_tubuh' => $validated['suhu_tubuh'] ?? null,
                    'nadi' => $validated['nadi'] ?? null,
                    'berat_badan' => $validated['berat_badan'] ?? null,
                    'tinggi_badan' => $validated['tinggi_badan'] ?? null,
                    'keluhan_utama' => $validated['keluhan_utama'],
                    'diagnosis_penyakit' => $validated['diagnosis_penyakit'],
                    'tindakan_medis' => $validated['tindakan_medis'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Process E-Resep Detail
            // Kembalikan stok obat dari resep lama sebelum dihapus
            $oldResep = DB::table('tbl_detail_resep')->where('id_pemeriksaan', $idPemeriksaan)->get();
            foreach ($oldResep as $old) {
                DB::table('tbl_obat')
                    ->where('id_obat', $old->id_obat)
                    ->increment('stok', (int) $old->jumlah_obat);
            }

            // Hapus resep lama jika ada update
            DB::table('tbl_detail_resep')->where('id_pemeriksaan', $idPemeriksaan)->delete();

            if (! empty($validated['resep'])) {
                foreach ($validated['resep'] as $item) {
                    // Insert detail resep
                    DB::table('tbl_detail_resep')->insert([
                        'id_pemeriksaan' => $idPemeriksaan,
                        'id_obat' => $item['id_obat'],
                        'jumlah_obat' => $item['jumlah_obat'],
                        'dosis_aturan_pakai' => $item['dosis_aturan_pakai'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Kurangi stok obat di tbl_obat (tanpa membuat stok minus)
                    DB::table('tbl_obat')
                        ->where('id_obat', $item['id_obat'])
                        ->decrement('stok', (int) $item['jumlah_obat']);
                }
            }

            // Update status antrean menjadi 'Selesai'
            DB::table('tbl_antrean')
                ->where('id_antrean', $antrean->id_antrean)
                ->update([
                    'status_antrean' => 'Selesai',
                    'updated_at' => now(),
                ]);

            $kodeTampil = $antrean->kode_antrean ?? ('#'.$antrean->nomor_antrean);

            return redirect()->route('dokter.pemeriksaan.index')->with('success', 'Pemeriksaan medis & E-Resep antrean ('.$kodeTampil.') telah SELESAI diproses.');
        });
    }

    /**
     * Ambil detail rekam medis & E-Resep untuk Modal Detail.
     */
    public function getDetailPemeriksaan($idAntrean)
    {
        $antrean = DB::table('tbl_antrean')
            ->join('tbl_pasien', 'tbl_antrean.id_pasien', '=', 'tbl_pasien.id_pasien')
            ->where('tbl_antrean.id_antrean', $idAntrean)
            ->select('tbl_antrean.*', 'tbl_pasien.nama_lengkap as nama_pasien', 'tbl_pasien.jenis_kelamin', 'tbl_pasien.tanggal_lahir')
            ->first();

        if (! $antrean) {
            return response()->json(['success' => false, 'message' => 'Data antrean tidak ditemukan.'], 404);
        }

        $pemeriksaan = DB::table('tbl_pemeriksaan')
            ->leftJoin('tbl_pengguna', 'tbl_pemeriksaan.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->where('id_antrean', $idAntrean)
            ->select('tbl_pemeriksaan.*', 'tbl_pengguna.nama_lengkap as nama_dokter')
            ->first();

        $resep = [];
        if ($pemeriksaan) {
            $resep = DB::table('tbl_detail_resep')
                ->join('tbl_obat', 'tbl_detail_resep.id_obat', '=', 'tbl_obat.id_obat')
                ->where('id_pemeriksaan', $pemeriksaan->id_pemeriksaan)
                ->select('tbl_detail_resep.*', 'tbl_obat.nama_obat', 'tbl_obat.satuan')
                ->get();
        }

        return response()->json([
            'success' => true,
            'antrean' => $antrean,
            'pemeriksaan' => $pemeriksaan,
            'resep' => $resep,
        ]);
    }
}
