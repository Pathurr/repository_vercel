<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total akun aktif (exclude superadmin)
        $totalAktif = User::where('status', 'active')->where('role', '!=', 'superadmin')->count();

        // Ambil data akun yang masih pending (butuh persetujuan)
        $pendingUsers = User::where('status', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->take(5) // Ambil 5 terbaru untuk dashboard
                            ->get();
        $totalPending = User::where('status', 'pending')->count();

        // Hitung total akun bermasalah (suspended, inactive, rejected)
        $totalSuspended = User::whereIn('status', ['suspended', 'inactive', 'rejected'])->count();

        return view('admin.dashboard', compact('totalAktif', 'pendingUsers', 'totalPending', 'totalSuspended'));
    }
}
