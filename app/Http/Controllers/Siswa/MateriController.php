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
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $materi = Materi::whereIn('kelas_id', $kelasIds)->latest()->get();
        return view('siswa.materi', compact('materi'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $materi = Materi::whereIn('kelas_id', $kelasIds)->findOrFail($id);
        return view('siswa.lihat-materi', compact('materi'));
    }
}