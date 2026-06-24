<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get IDs of classes the student is enrolled in (if dev, see all)
        $kelasIds = app()->environment('local') 
            ? \App\Models\Kelas::pluck('id') 
            : $user->kelas()->pluck('kelas.id');

        // Fetch pending assignments (not yet submitted by this student)
        $tugasMendatang = Tugas::whereIn('kelas_id', $kelasIds)
            ->whereDoesntHave('pengumpulan', function ($query) use ($user) {
                $query->where('siswa_id', $user->id);
            })
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();

        // Fetch recent materials
        $materiBaru = Materi::whereIn('kelas_id', $kelasIds)
            ->latest()
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact('tugasMendatang', 'materiBaru', 'user'));
    }
}
