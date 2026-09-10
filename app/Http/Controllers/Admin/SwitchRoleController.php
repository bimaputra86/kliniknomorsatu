<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchRoleController extends Controller
{
    /**
     * Memproses alih / switch role aktif pengguna yang tersimpan di Session.
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'active_role' => ['required', 'string'],
        ]);

        $user = Auth::guard('web')->user();

        // Pastikan user benar-benar memiliki role yang diminta (via Spatie / peran utama)
        $userRoles = $user->roles->pluck('name')->toArray();
        if (empty($userRoles)) {
            $userRoles = [$user->peran];
        }

        if (! in_array($request->active_role, $userRoles)) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk role tersebut.');
        }

        // Simpan active role ke dalam session
        session(['active_role' => $request->active_role]);

        return redirect()->route('admin.dashboard')->with('success', 'Role aktif berhasil diubah menjadi "'.$request->active_role.'". Selamat datang di Dashboard '.$request->active_role.'!');
    }
}
