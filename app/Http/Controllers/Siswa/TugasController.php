<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $tugas = Tugas::with(['pengumpulan' => function ($query) use ($user) {
            $query->where('siswa_id', $user->id);
        }])->whereIn('kelas_id', $kelasIds)->latest()->get();

        return view('siswa.tugas', compact('tugas'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = app()->environment('local') ? \App\Models\Kelas::pluck('id') : $user->kelas()->pluck('kelas.id');

        $tugas = Tugas::with(['pengumpulan' => function ($query) use ($user) {
            $query->where('siswa_id', $user->id);
        }])->whereIn('kelas_id', $kelasIds)->findOrFail($id);
        
        return view('siswa.pengerjaan-tugas', compact('tugas'));
    }

    public function store(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        PengumpulanTugas::create([
            'tugas_id'  => $tugas->id,
            'siswa_id'  => Auth::id(),
            'file_path' => $request->file_path ?? '-',
            'catatan'   => $request->catatan,
        ]);
        return redirect()->route('siswa.tugas')->with('success', 'Tugas berhasil dikumpulkan!');
    }
}