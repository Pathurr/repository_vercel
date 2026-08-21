<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function index()
    {
        // Get all users except superadmin (superadmin tidak boleh dikelola siapapun)
        $users = User::where('role', '!=', 'superadmin')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('admin.manajemen-akun', compact('users'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action'  => 'required|in:aktifkan,tolak,suspend,nonaktif,hapus'
        ]);

        $user = User::findOrFail($request->user_id);

        // Aksi yang hanya boleh dilakukan superadmin
        $superadminOnlyActions = ['suspend', 'nonaktif', 'hapus'];

        if (in_array($request->action, $superadminOnlyActions) && !Auth::user()->isSuperAdmin()) {
            return back()->withErrors(['msg' => 'Anda tidak memiliki hak akses untuk melakukan aksi ini.']);
        }

        // Proteksi: tidak boleh mengubah status superadmin atau admin lain (kecuali superadmin)
        if ($user->isAdminLevel() && !Auth::user()->isSuperAdmin()) {
            return back()->withErrors(['msg' => 'Anda tidak memiliki hak akses untuk mengelola akun admin.']);
        }

        // Proteksi: superadmin tidak bisa menghapus dirinya sendiri
        if ($user->id === Auth::id() && $request->action === 'hapus') {
            return back()->withErrors(['msg' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if ($request->action === 'hapus') {
            $user->delete();
            return back()->with('success', 'Akun berhasil dihapus permanen.');
        }

        $statusMap = [
            'aktifkan' => 'active',
            'tolak'    => 'rejected',
            'suspend'  => 'suspended',
            'nonaktif' => 'inactive',
        ];

        $user->status = $statusMap[$request->action];
        $user->save();

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|string', // comma separated list
            'action'   => 'required|in:aktifkan,suspend,hapus'
        ]);

        // Aksi bulk suspend & hapus hanya boleh superadmin
        if (in_array($request->action, ['suspend', 'hapus']) && !Auth::user()->isSuperAdmin()) {
            return back()->withErrors(['msg' => 'Anda tidak memiliki hak akses untuk melakukan aksi massal ini.']);
        }

        $userIds = explode(',', $request->user_ids);
        
        if (empty($userIds)) {
            return back()->withErrors(['msg' => 'Tidak ada pengguna yang dipilih.']);
        }

        // Proteksi: jangan pernah bulk action pada superadmin
        $userIds = array_filter($userIds, function ($id) {
            $user = User::find($id);
            return $user && !$user->isSuperAdmin();
        });

        if (empty($userIds)) {
            return back()->withErrors(['msg' => 'Tidak ada pengguna valid yang dipilih.']);
        }

        if ($request->action === 'hapus') {
            User::whereIn('id', $userIds)->delete();
            return back()->with('success', count($userIds) . ' Akun berhasil dihapus permanen.');
        }

        $statusMap = [
            'aktifkan' => 'active',
            'suspend'  => 'suspended',
        ];

        User::whereIn('id', $userIds)->update(['status' => $statusMap[$request->action]]);

        return back()->with('success', count($userIds) . ' Status akun berhasil diperbarui.');
    }
}
