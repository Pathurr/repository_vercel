<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $submissions = PengumpulanTugas::with(['tugas.kelas', 'siswa'])
            ->whereHas('tugas', fn ($query) => $query->where('guru_id', Auth::id()))
            ->latest('dikumpulkan_at')
            ->get();

        return view('guru.monitor-tugas', compact('submissions'));
    }
}
