<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $ujian = Ujian::whereIn('kelas_id', $kelasIds)->latest()->get();
        return view('siswa.ujian', compact('ujian'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $ujian = Ujian::whereIn('kelas_id', $kelasIds)->findOrFail($id);
        return view('siswa.pengerjaan-ujian', compact('ujian'));
    }
}