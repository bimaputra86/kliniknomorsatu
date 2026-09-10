<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObatController extends Controller
{
    /**
     * Tampilkan daftar obat / inventaris obat.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('tbl_obat');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('id_obat', 'like', "%{$search}%")
                    ->orWhere('jenis_obat', 'like', "%{$search}%");
            });
        }

        $obats = $query->orderBy('nama_obat', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.obat.index', compact('obats', 'search'));
    }

    /**
     * Simpan data obat baru menggunakan DB Transaction & Validator.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:100'],
            'jenis_obat' => ['required', 'string', 'max:50'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'jenis_obat.required' => 'Jenis obat wajib diisi.',
            'harga_satuan.required' => 'Harga satuan wajib diisi.',
            'harga_satuan.numeric' => 'Harga harus berupa angka.',
            'stok.required' => 'Stok obat wajib diisi.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'satuan.required' => 'Satuan obat wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            // Generate id_obat format: OBT-XXXX (Max 10 chars)
            $lastObat = DB::table('tbl_obat')
                ->where('id_obat', 'like', 'OBT-%')
                ->orderBy('id_obat', 'desc')
                ->first();

            $num = 1;
            if ($lastObat) {
                $lastNum = (int) substr($lastObat->id_obat, 4);
                $num = $lastNum + 1;
            }
            $idObat = 'OBT-'.str_pad((string) $num, 4, '0', STR_PAD_LEFT);

            DB::table('tbl_obat')->insert([
                'id_obat' => $idObat,
                'nama_obat' => $validated['nama_obat'],
                'jenis_obat' => $validated['jenis_obat'],
                'harga_satuan' => (int) $validated['harga_satuan'],
                'stok' => (int) $validated['stok'],
                'satuan' => $validated['satuan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Obat baru ('.$validated['nama_obat'].') berhasil ditambahkan ke inventaris.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal tambah obat: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data obat. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Update data obat menggunakan DB Transaction & Validator.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:100'],
            'jenis_obat' => ['required', 'string', 'max:50'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'jenis_obat.required' => 'Jenis obat wajib diisi.',
            'harga_satuan.required' => 'Harga satuan wajib diisi.',
            'stok.required' => 'Stok obat wajib diisi.',
            'satuan.required' => 'Satuan obat wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $exists = DB::table('tbl_obat')->where('id_obat', $id)->exists();
            if (! $exists) {
                DB::rollBack();

                return back()->with('error', 'Data obat tidak ditemukan.');
            }

            DB::table('tbl_obat')
                ->where('id_obat', $id)
                ->update([
                    'nama_obat' => $validated['nama_obat'],
                    'jenis_obat' => $validated['jenis_obat'],
                    'harga_satuan' => (int) $validated['harga_satuan'],
                    'stok' => (int) $validated['stok'],
                    'satuan' => $validated['satuan'],
                    'updated_at' => now(),
                ]);

            DB::commit();

            return back()->with('success', 'Data obat ('.$validated['nama_obat'].') berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update obat: '.$e->getMessage());

            return back()->with('error', 'Gagal memperbarui data obat. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Hapus data obat menggunakan DB Transaction.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $obat = DB::table('tbl_obat')->where('id_obat', $id)->first();
            if (! $obat) {
                DB::rollBack();

                return back()->with('error', 'Data obat tidak ditemukan.');
            }

            // Hapus obat
            DB::table('tbl_obat')->where('id_obat', $id)->delete();

            DB::commit();

            return back()->with('success', 'Obat ('.$obat->nama_obat.') berhasil dihapus dari inventaris.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus obat: '.$e->getMessage());

            return back()->with('error', 'Gagal menghapus obat dari inventaris.');
        }
    }

    /**
     * Tambah stok obat masuk menggunakan DB Transaction & Validator.
     */
    public function restock(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
        ], [
            'jumlah_masuk.required' => 'Jumlah obat masuk wajib diisi.',
            'jumlah_masuk.integer' => 'Jumlah masuk harus berupa angka bulat.',
            'jumlah_masuk.min' => 'Jumlah masuk minimal 1.',
        ]);

        DB::beginTransaction();
        try {
            $obat = DB::table('tbl_obat')->where('id_obat', $id)->first();
            if (! $obat) {
                DB::rollBack();

                return back()->with('error', 'Data obat tidak ditemukan.');
            }

            DB::table('tbl_obat')
                ->where('id_obat', $id)
                ->increment('stok', (int) $validated['jumlah_masuk']);

            DB::commit();

            return back()->with('success', 'Stok obat ('.$obat->nama_obat.') berhasil ditambah sebanyak '.$validated['jumlah_masuk'].' '.$obat->satuan.'.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal restock obat: '.$e->getMessage());

            return back()->with('error', 'Gagal memproses penambahan stok obat.');
        }
    }
}
