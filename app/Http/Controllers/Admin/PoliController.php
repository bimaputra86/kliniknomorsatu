<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoliController extends Controller
{
    /**
     * Tampilkan data master poliklinik dengan paging & search filter.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('tbl_poli');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_poli', 'like', "%{$search}%")
                    ->orWhere('id_poli', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $polis = $query->orderBy('id_poli', 'asc')->paginate(10)->withQueryString();

        return view('admin.poli.index', compact('polis', 'search'));
    }

    /**
     * Simpan data poliklinik baru (Server-Side Validation).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:50', 'unique:tbl_poli,nama_poli'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'nama_poli.required' => 'Nama Poliklinik wajib diisi.',
            'nama_poli.unique' => 'Nama Poliklinik sudah terdaftar dalam sistem.',
            'nama_poli.max' => 'Nama Poliklinik maksimal 50 karakter.',
        ]);

        // Auto-generate ID Poli: POL-001, POL-002, dst.
        $lastPoli = DB::table('tbl_poli')->orderBy('id_poli', 'desc')->first();
        if ($lastPoli) {
            $lastNum = (int) substr($lastPoli->id_poli, 4);
            $nextId = 'POL-'.str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextId = 'POL-001';
        }

        DB::table('tbl_poli')->insert([
            'id_poli' => $nextId,
            'nama_poli' => $validated['nama_poli'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.poli.index')->with('success', 'Poliklinik baru ('.$validated['nama_poli'].') berhasil ditambahkan.');
    }

    /**
     * Update data poliklinik (Server-Side Validation).
     */
    public function update(Request $request, $id)
    {
        $poli = DB::table('tbl_poli')->where('id_poli', $id)->first();
        if (! $poli) {
            return redirect()->route('admin.poli.index')->with('error', 'Data Poliklinik tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama_poli' => ['required', 'string', 'max:50', 'unique:tbl_poli,nama_poli,'.$id.',id_poli'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'nama_poli.required' => 'Nama Poliklinik wajib diisi.',
            'nama_poli.unique' => 'Nama Poliklinik sudah digunakan oleh poli lain.',
        ]);

        DB::table('tbl_poli')->where('id_poli', $id)->update([
            'nama_poli' => $validated['nama_poli'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'updated_at' => now(),
        ]);

        // Sinkronkan nama_poli di tbl_jadwal_dokter
        DB::table('tbl_jadwal_dokter')->where('id_poli', $id)->update([
            'nama_poli' => $validated['nama_poli'],
        ]);

        return redirect()->route('admin.poli.index')->with('success', 'Data Poliklinik berhasil diperbarui.');
    }

    /**
     * Hapus data poliklinik.
     */
    public function destroy($id)
    {
        $poli = DB::table('tbl_poli')->where('id_poli', $id)->first();
        if (! $poli) {
            return redirect()->route('admin.poli.index')->with('error', 'Data Poliklinik tidak ditemukan.');
        }

        DB::table('tbl_poli')->where('id_poli', $id)->delete();

        return redirect()->route('admin.poli.index')->with('success', 'Poliklinik ('.$poli->nama_poli.') berhasil dihapus.');
    }
}
