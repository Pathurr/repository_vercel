<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function index()
    {
        $ujian = Ujian::with(['kelas.siswa', 'soal', 'jawaban'])->latest()->get();
        return view('guru.ujian', compact('ujian'));
    }

    public function create()
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        return view('guru.buat-ujian', compact('kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deskripsi' => 'nullable|string',
        ]);

        foreach ($request->kelas_id as $kelasId) {
            Ujian::create([
                'guru_id'   => Auth::id(),
                'kelas_id'  => $kelasId,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'mulai_at'  => now(),
                'selesai_at'=> now()->addHours(2),
                'durasi_menit' => $request->durasi ?? 90,
            ]);
        }

        return redirect()->route('guru.ujian')->with('success', 'Ujian berhasil disimpan!');
    }
}