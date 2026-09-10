<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna/pegawai dengan pencarian, filter role, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $query = User::with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('id_pengguna', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $roles = Role::where('guard_name', 'web')->get();

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleFilter'));
    }

    /**
     * Tampilkan form pembuatan pengguna baru (Tanpa opsi Superadmin).
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')
            ->where('name', '!=', 'Superadmin')
            ->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Simpan pengguna baru dengan Query Builder & Assign Spatie Roles (Multiple Roles).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pengguna' => ['required', 'string', 'max:10', 'unique:tbl_pengguna,id_pengguna'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:tbl_pengguna,username'],
            'password' => ['required', 'string', 'min:6'],
            'peran' => ['required', 'in:Resepsionis,Perawat,Dokter,Apoteker,Kasir,Pimpinan'],
            'no_telepon_pegawai' => ['required', 'string', 'max:15'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name', 'not_in:Superadmin'],
        ], [
            'id_pengguna.required' => 'ID Pegawai wajib diisi.',
            'id_pengguna.unique' => 'ID Pegawai ini sudah digunakan.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'peran.required' => 'Pilih peran utama pegawai.',
            'peran.in' => 'Peran Superadmin tidak dapat ditambahkan kembali (hanya 1 akun Superadmin).',
            'no_telepon_pegawai.required' => 'Nomor telepon pegawai wajib diisi.',
            'roles.required' => 'Pilih minimal satu Role RBAC untuk pengguna ini.',
            'roles.*.not_in' => 'Role Superadmin tidak dapat diberikan ke pegawai lain.',
        ]);

        // Eksekusi insert data ke tbl_pengguna menggunakan Query Builder (DB::table)
        DB::table('tbl_pengguna')->insert([
            'id_pengguna' => $validated['id_pengguna'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'peran' => $validated['peran'],
            'no_telepon_pegawai' => $validated['no_telepon_pegawai'],
            'status_aktif' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign Spatie Roles ke Eloquent User Model
        $user = User::find($validated['id_pengguna']);
        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')->with('success', 'User pegawai "'.$user->nama_lengkap.'" berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit pengguna & hak akses Spatie RBAC.
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Jika user yang diedit adalah akun Superadmin utama, sertakan role Superadmin
        if ($user->username === 'superadmin' || $user->peran === 'Superadmin') {
            $roles = Role::where('guard_name', 'web')->get();
        } else {
            // Untuk user selain Superadmin, sembunyikan opsi Superadmin
            $roles = Role::where('guard_name', 'web')
                ->where('name', '!=', 'Superadmin')
                ->get();
        }

        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update data pengguna & hak akses Spatie RBAC menggunakan Query Builder.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isSuperadminUser = ($user->username === 'superadmin' || $user->peran === 'Superadmin');

        $peranRules = $isSuperadminUser
            ? ['required', 'in:Resepsionis,Perawat,Dokter,Apoteker,Kasir,Pimpinan,Superadmin']
            : ['required', 'in:Resepsionis,Perawat,Dokter,Apoteker,Kasir,Pimpinan'];

        $roleItemRules = $isSuperadminUser
            ? ['exists:roles,name']
            : ['exists:roles,name', 'not_in:Superadmin'];

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:tbl_pengguna,username,'.$id.',id_pengguna'],
            'password' => ['nullable', 'string', 'min:6'],
            'peran' => $peranRules,
            'no_telepon_pegawai' => ['required', 'string', 'max:15'],
            'status_aktif' => ['required', 'in:Aktif,Nonaktif'],
            'roles' => ['required', 'array'],
            'roles.*' => $roleItemRules,
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'peran.required' => 'Pilih peran utama pegawai.',
            'peran.in' => 'Peran Superadmin tidak dapat diberikan ke pegawai lain.',
            'no_telepon_pegawai.required' => 'Nomor telepon pegawai wajib diisi.',
            'status_aktif.required' => 'Pilih status keaktifan user.',
            'roles.required' => 'Pilih minimal satu Role RBAC untuk pengguna ini.',
            'roles.*.not_in' => 'Role Superadmin tidak dapat diberikan ke pegawai lain.',
        ]);

        $updateData = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'peran' => $validated['peran'],
            'no_telepon_pegawai' => $validated['no_telepon_pegawai'],
            'status_aktif' => $validated['status_aktif'],
            'updated_at' => now(),
        ];

        // Jika password diisi, hash password baru
        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Eksekusi update menggunakan Query Builder (DB::table)
        DB::table('tbl_pengguna')
            ->where('id_pengguna', $id)
            ->update($updateData);

        // Update Spatie Roles RBAC
        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.index')->with('success', 'Data user & hak akses Spatie RBAC "'.$user->nama_lengkap.'" berhasil diperbarui.');
    }

    /**
     * Toggle status aktif / nonaktif user pegawai oleh Superadmin (Query Builder).
     */
    public function toggleStatus($id)
    {
        $user = DB::table('tbl_pengguna')->where('id_pengguna', $id)->first();

        if (! $user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Mencegah penonaktifan akun Superadmin utama
        if ($user->username === 'superadmin' || $user->peran === 'Superadmin') {
            return back()->with('error', 'Akun Superadmin utama tidak dapat dinonaktifkan.');
        }

        $newStatus = ($user->status_aktif ?? 'Aktif') === 'Aktif' ? 'Nonaktif' : 'Aktif';

        DB::table('tbl_pengguna')
            ->where('id_pengguna', $id)
            ->update([
                'status_aktif' => $newStatus,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.users.index')->with('success', 'Status user pegawai "'.$user->nama_lengkap.'" berhasil diubah menjadi: '.strtoupper($newStatus).'.');
    }

    /**
     * Reset password user pegawai secara acak 6 digit angka oleh Superadmin (Query Builder).
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Generate password baru berupa 6 digit angka random (contoh: 482910)
        $newPassword = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Update ke tbl_pengguna menggunakan Query Builder (DB::table)
        DB::table('tbl_pengguna')
            ->where('id_pengguna', $id)
            ->update([
                'password' => Hash::make($newPassword),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.users.index')->with([
            'reset_success' => true,
            'reset_user_name' => $user->nama_lengkap,
            'reset_username' => $user->username,
            'new_password' => $newPassword,
        ]);
    }
}
