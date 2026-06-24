<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['kelas', 'pengumpulan'])->latest()->get();
        return view('guru.tugas', compact('tugas'));
    }

    public function create()
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        return view('guru.buat-tugas', compact('kelases'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'judul'    => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deadline' => 'required|date',
        ]);

        foreach ($request->kelas_id as $kelasId) {
            Tugas::create([
                'guru_id'        => Auth::id(),
                'kelas_id'       => $kelasId,
                'judul'          => $request->judul,
                'deskripsi'      => $request->deskripsi,
                'deadline'       => $request->deadline,
                'nilai_maksimal' => $request->nilai_maksimal ?? 100,
            ]);
        }

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil disimpan!');
    }
}