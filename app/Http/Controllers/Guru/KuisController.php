<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    public function index()
    {
        $kuis = Kuis::with(['kelas.siswa', 'soal', 'jawaban'])->latest()->get();
        return view('guru.kuis', compact('kuis'));
    }

    public function create()
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        return view('guru.buat-kuis', compact('kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
        ]);

        foreach ($request->kelas_id as $kelasId) {
            Kuis::create([
                'guru_id'   => Auth::id(),
                'kelas_id'  => $kelasId,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);
        }

        return redirect()->route('guru.kuis')->with('success', 'Kuis berhasil disimpan!');
    }
}