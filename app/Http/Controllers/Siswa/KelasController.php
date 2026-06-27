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
        $kelas = $user->kelas()->withCount(['materi', 'tugas'])->get();

        return view('siswa.mapel', compact('kelas', 'user'));
    }

    public function show($id, Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'materi');

        $kelas = $user->kelas()->with([
            'guru',
            'materi',
            'tugas' => fn($q) => $q->with(['pengumpulan' => fn($q2) => $q2->where('siswa_id', $user->id)]),
            'kuis' => fn($q) => $q->with(['nilai_siswa' => fn($q2) => $q2->where('siswa_id', $user->id)]),
            'ujian' => fn($q) => $q->with(['nilai_siswa' => fn($q2) => $q2->where('siswa_id', $user->id)])
        ])->findOrFail($id);

        $materiReadIds = \App\Models\MateriSiswa::where('siswa_id', $user->id)
            ->whereIn('materi_id', $kelas->materi->pluck('id'))
            ->pluck('materi_id')->toArray();

        return view('siswa.kelas-detail', compact('kelas', 'tab', 'user', 'materiReadIds'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|string|max:50'
        ]);

        $kelas = Kelas::where('kode_kelas', $request->kode_kelas)->first();

        if (!$kelas) {
            return back()->with('error', 'Kunci kelas tidak ditemukan.');
        }

        $user = Auth::user();

        // Cek apakah siswa sudah terdaftar di kelas ini
        if ($user->kelas()->where('kelas_id', $kelas->id)->exists()) {
            return back()->with('error', 'Anda sudah bergabung di kelas ini.');
        }

        // Attach siswa ke kelas
        $user->kelas()->attach($kelas->id);

        return back()->with('success', 'Berhasil bergabung dengan kelas ' . $kelas->nama_kelas . '!');
    }
}
