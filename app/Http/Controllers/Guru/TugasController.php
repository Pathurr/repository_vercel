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
        $tugas = Tugas::with(['kelas', 'pengumpulan'])->where('guru_id', Auth::id())->latest()->get();
        return view('guru.tugas', compact('tugas'));
    }

    public function create(Request $request)
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        $tugas = null;
        if ($request->mode === 'edit' && $request->id) {
            $tugas = Tugas::where('guru_id', Auth::id())->findOrFail($request->id);
        }
        return view('guru.buat-tugas', compact('kelases', 'tugas'));
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
                'format_pengumpulan' => $request->format_pengumpulan,
            ]);
        }

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::where('guru_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul'    => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deadline' => 'required|date',
        ]);

        $tugas->update([
            'kelas_id'       => $request->kelas_id[0],
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'deadline'       => $request->deadline,
            'nilai_maksimal' => $request->nilai_maksimal ?? 100,
            'format_pengumpulan' => $request->format_pengumpulan,
        ]);

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}