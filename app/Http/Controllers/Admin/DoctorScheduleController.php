<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorScheduleController extends Controller
{
    /**
     * Tampilkan kelola jadwal dokter bagi Resepsionis / Admin.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $poliFilter = $request->input('poli');

        $query = DB::table('tbl_jadwal_dokter')
            ->join('tbl_pengguna', 'tbl_jadwal_dokter.id_pengguna', '=', 'tbl_pengguna.id_pengguna')
            ->leftJoin('tbl_poli', 'tbl_jadwal_dokter.id_poli', '=', 'tbl_poli.id_poli')
            ->select('tbl_jadwal_dokter.*', 'tbl_pengguna.nama_lengkap', 'tbl_pengguna.no_telepon_pegawai', 'tbl_poli.nama_poli as nama_poli_master');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_pengguna.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('tbl_jadwal_dokter.nama_poli', 'like', "%{$search}%")
                    ->orWhere('tbl_poli.nama_poli', 'like', "%{$search}%")
                    ->orWhere('tbl_jadwal_dokter.hari', 'like', "%{$search}%");
            });
        }

        if ($poliFilter) {
            $query->where(function ($q) use ($poliFilter) {
                $q->where('tbl_jadwal_dokter.id_poli', $poliFilter)
                    ->orWhere('tbl_jadwal_dokter.nama_poli', $poliFilter);
            });
        }

        $schedules = $query->orderBy('tbl_jadwal_dokter.hari', 'asc')->paginate(10)->withQueryString();
        $doctors = User::where('peran', 'Dokter')->orWhereHas('roles', function ($q) {
            $q->where('name', 'Dokter');
        })->get();
        $polis = DB::table('tbl_poli')->orderBy('nama_poli', 'asc')->get();

        return view('admin.schedules.index', compact('schedules', 'doctors', 'polis', 'search', 'poliFilter'));
    }

    /**
     * Simpan jadwal dokter baru (Query Builder).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pengguna' => ['required', 'string', 'exists:tbl_pengguna,id_pengguna'],
            'id_poli' => ['required', 'string', 'exists:tbl_poli,id_poli'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'kuota_maksimal' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'id_pengguna.required' => 'Pilih dokter bertugas.',
            'id_poli.required' => 'Pilih Poliklinik.',
            'hari.required' => 'Pilih hari praktik.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'kuota_maksimal.required' => 'Kuota maksimal wajib diisi.',
        ]);

        $poliMaster = DB::table('tbl_poli')->where('id_poli', $validated['id_poli'])->first();

        // Cek duplikasi jadwal pada dokter dan hari yang sama
        $exists = DB::table('tbl_jadwal_dokter')
            ->where('id_pengguna', $validated['id_pengguna'])
            ->where('hari', $validated['hari'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Dokter ini sudah memiliki jadwal pada hari '.$validated['hari'].'.')->withInput();
        }

        DB::table('tbl_jadwal_dokter')->insert([
            'id_pengguna' => $validated['id_pengguna'],
            'id_poli' => $validated['id_poli'],
            'nama_poli' => $poliMaster ? $poliMaster->nama_poli : 'Poli Umum',
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'kuota_maksimal' => $validated['kuota_maksimal'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal praktik dokter berhasil ditambahkan.');
    }

    /**
     * Hapus jadwal dokter (Query Builder).
     */
    public function destroy($id)
    {
        DB::table('tbl_jadwal_dokter')->where('id_jadwal', $id)->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal praktik dokter berhasil dihapus.');
    }
}
