<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        // Ambil semua materi dari Supabase agar data lama langsung tampil di halaman guru.
        $materi = Materi::latest()->get();
        return view('guru.materi', compact('materi'));
    }

    public function create()
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        return view('guru.tambah-materi', compact('kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'kelas_id'  => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deskripsi' => 'nullable|string',
        ]);

        foreach ($request->kelas_id as $kelasId) {
            Materi::create([
                'guru_id'   => Auth::id(),
                'kelas_id'  => $kelasId,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'link_video'=> $request->link_video,
            ]);
        }
        return redirect()->route('guru.materi')->with('success', 'Materi berhasil disimpan!');
    }
}