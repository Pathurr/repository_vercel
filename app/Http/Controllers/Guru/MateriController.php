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
        $materi = Materi::with('kelas')->where('guru_id', Auth::id())->latest()->get();
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
            'file'      => 'nullable|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:20480', // 20MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materi', 'public');
        }

        foreach ($request->kelas_id as $kelasId) {
            Materi::create([
                'guru_id'   => Auth::id(),
                'kelas_id'  => $kelasId,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_path' => $filePath,
                'link_video'=> $request->link_video,
            ]);
        }
        return redirect()->route('guru.materi')->with('success', 'Materi berhasil disimpan!');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        // Hapus file jika ada
        if ($materi->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($materi->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }
}