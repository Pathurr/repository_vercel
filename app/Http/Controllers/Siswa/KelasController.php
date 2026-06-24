<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelas = app()->environment('local') 
            ? Kelas::withCount(['materi', 'tugas', 'kuis', 'ujian'])->get()
            : $user->kelas()->withCount(['materi', 'tugas', 'kuis', 'ujian'])->get();

        return view('siswa.mapel', compact('kelas', 'user'));
    }

    public function show($id, Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'materi');

        $query = app()->environment('local') ? Kelas::query() : $user->kelas();
        $kelas = $query->with([
            'guru',
            'materi',
            'tugas' => fn($q) => $q->with(['pengumpulan' => fn($q2) => $q2->where('siswa_id', $user->id)]),
            'kuis',
            'ujian'
        ])->findOrFail($id);

        return view('siswa.kelas-detail', compact('kelas', 'tab', 'user'));
    }
}
