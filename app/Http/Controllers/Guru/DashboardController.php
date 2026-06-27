<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Ujian;
use App\Models\PengumpulanTugas;

class DashboardController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();
        
        // 1. Kelas Aktif
        $kelasAktif = Kelas::where('guru_id', $guruId)->count();
        
        $kelasIds = Kelas::where('guru_id', $guruId)->pluck('id');

        // 2. Siswa Total
        $siswaTotal = Kelas::with('siswa')
            ->where('guru_id', $guruId)
            ->get()
            ->pluck('siswa')
            ->flatten()
            ->unique('id')
            ->count();

        // 3. Ujian Berlangsung
        // Anggap ujian berlangsung jika mulai_at <= now() dan selesai_at >= now()
        $ujianBerlangsung = Ujian::whereIn('kelas_id', $kelasIds)
            ->where('mulai_at', '<=', now())
            ->where('selesai_at', '>=', now())
            ->count();

        // 4. Belum Dinilai (Tugas yang sudah dikumpulkan tapi belum dinilai)
        $submissionsBelumDinilai = PengumpulanTugas::with(['tugas.kelas', 'siswa'])
            ->whereHas('tugas', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            })
            ->whereNull('nilai')
            ->orderBy('dikumpulkan_at', 'asc')
            ->get();

        $belumDinilaiCount = $submissionsBelumDinilai->count();
        
        $submissionsList = $submissionsBelumDinilai->take(10); // Menampilkan max 10 submission di dashboard

        return view('guru.dashboard', compact(
            'kelasAktif',
            'siswaTotal',
            'ujianBerlangsung',
            'belumDinilaiCount',
            'submissionsList'
        ));
    }
}
