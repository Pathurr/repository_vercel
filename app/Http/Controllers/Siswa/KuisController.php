<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $kuis = Kuis::whereIn('kelas_id', $kelasIds)->latest()->get();
        return view('siswa.kuis', compact('kuis'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $kuis = Kuis::whereIn('kelas_id', $kelasIds)->findOrFail($id);
        return view('siswa.pengerjaan-kuis', compact('kuis'));
    }
}