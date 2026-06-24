<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();

        $kelas = Kelas::withCount(['siswa', 'materi', 'tugas', 'kuis', 'ujian'])
            ->when(!app()->environment('local'), fn($query) => $query->where('guru_id', $guruId))
            ->latest()
            ->get();

        $totals = [
            'kelas' => $kelas->count(),
            'siswa' => $kelas->sum('siswa_count'),
            'materi' => $kelas->sum('materi_count'),
            'tugas' => $kelas->sum('tugas_count'),
        ];

        return view('guru.kelas', compact('kelas', 'totals'));
    }

    public function show(Request $request)
    {
        $guruId = Auth::id();
        $tab = $request->query('tab', 'siswa');

        $kelas = Kelas::with([
                'materi',
                'tugas' => fn ($query) => $query->withCount('pengumpulan'),
                'kuis',
                'ujian',
            ])
            ->when(!app()->environment('local'), fn($query) => $query->where('guru_id', $guruId))
            ->when($request->id, fn ($query, $id) => $query->where('id', $id))
            ->firstOrFail();

        $siswa = $kelas->siswa()->paginate(10)->withQueryString();

        return view('guru.kelas-detail', compact('kelas', 'tab', 'siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'kode_kelas' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'kode_kelas' => $request->kode_kelas,
            'deskripsi' => $request->deskripsi,
            'guru_id' => Auth::id(),
            'aktif' => true,
        ]);

        return redirect()->route('guru.kelas')->with('success', 'Kelas berhasil dibuat!');
    }
}
