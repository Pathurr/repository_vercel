<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $materi = Materi::whereIn('kelas_id', $kelasIds)->latest()->get();
        return view('siswa.materi', compact('materi'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $materi = Materi::whereIn('kelas_id', $kelasIds)->findOrFail($id);
        
        // Fetch all materi in the same class for the sidebar
        $materis = Materi::where('kelas_id', $materi->kelas_id)->orderBy('created_at')->get();

        $isRead = \App\Models\MateriSiswa::where('materi_id', $materi->id)->where('siswa_id', $user->id)->exists();

        // Resolve file URL: coba disk utama (supabase), fallback ke local public
        $fileUrl = null;
        if ($materi->file_path) {
            $disk = env('FILESYSTEM_DISK', 'public');
            if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($materi->file_path)) {
                $fileUrl = \Illuminate\Support\Facades\Storage::disk($disk)->url($materi->file_path);
            } elseif ($disk !== 'public' && \Illuminate\Support\Facades\Storage::disk('public')->exists($materi->file_path)) {
                // Fallback: file lama yang masih tersimpan di local storage
                $fileUrl = asset('storage/' . $materi->file_path);
            }
        }

        return view('siswa.lihat-materi', compact('materi', 'materis', 'isRead', 'fileUrl'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $materi = Materi::whereIn('kelas_id', $kelasIds)->findOrFail($id);

        \App\Models\MateriSiswa::firstOrCreate(
            ['materi_id' => $materi->id, 'siswa_id' => $user->id],
            ['read_at' => now()]
        );

        return redirect()->back()->with('success', 'Materi ditandai sudah dibaca.');
    }
}