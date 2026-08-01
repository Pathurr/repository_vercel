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
            'file'      => 'nullable|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:4096', // 4MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materi', env('FILESYSTEM_DISK', 'public'));
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

    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        return view('guru.tambah-materi', compact('materi', 'kelases'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        
        $request->validate([
            'judul'     => 'required|string|max:255',
            'kelas_id'  => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deskripsi' => 'nullable|string',
            'file'      => 'nullable|file|mimes:pdf,ppt,pptx,jpg,jpeg,png|max:4096', // 4MB
        ]);

        $dataToUpdate = [
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'link_video'=> $request->link_video,
            // You might want to update status/scheduled_at if added to DB
        ];

        if ($request->hasFile('file')) {
            $disk = env('FILESYSTEM_DISK', 'public');
            if ($materi->file_path && \Illuminate\Support\Facades\Storage::disk($disk)->exists($materi->file_path)) {
                \Illuminate\Support\Facades\Storage::disk($disk)->delete($materi->file_path);
            } elseif ($materi->file_path && $disk !== 'public' && \Illuminate\Support\Facades\Storage::disk('public')->exists($materi->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($materi->file_path);
            }
            $dataToUpdate['file_path'] = $request->file('file')->store('materi', $disk);
        }

        // Handle updating multiple classes for the same material.
        // Currently, it creates duplicate materi records for each class. 
        // If we edit, we should just update this specific materi record and its class_id
        // since the current architecture stores 1 record per class.
        $dataToUpdate['kelas_id'] = $request->kelas_id[0]; // Just take the first one if we edit a specific record.

        $materi->update($dataToUpdate);

        return redirect()->route('guru.materi')->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        // Hapus file jika ada
        $disk = env('FILESYSTEM_DISK', 'public');
        if ($materi->file_path && \Illuminate\Support\Facades\Storage::disk($disk)->exists($materi->file_path)) {
            \Illuminate\Support\Facades\Storage::disk($disk)->delete($materi->file_path);
        } elseif ($materi->file_path && $disk !== 'public' && \Illuminate\Support\Facades\Storage::disk('public')->exists($materi->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }
}