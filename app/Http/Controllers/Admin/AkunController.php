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
        // Get all users except the current admin
        $users = User::where('id', '!=', Auth::id())
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

        $userIds = explode(',', $request->user_ids);
        
        if (empty($userIds)) {
            return back()->withErrors(['msg' => 'Tidak ada pengguna yang dipilih.']);
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
