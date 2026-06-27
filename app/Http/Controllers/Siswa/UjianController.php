<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\JawabanUjian;
use Illuminate\Http\Request;
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

        $ujian = Ujian::with('soal')->whereIn('kelas_id', $kelasIds)->findOrFail($id);
        return view('siswa.pengerjaan-ujian', compact('ujian'));
    }

    public function store(Request $request, $id)
    {
        $ujian = Ujian::with('soal')->findOrFail($id);
        $answers = json_decode($request->answers, true);
        $user = Auth::user();

        // Cek jika sudah pernah mengerjakan
        $sudah = JawabanUjian::where('ujian_id', $ujian->id)->where('siswa_id', $user->id)->exists();
        if ($sudah) {
            return redirect()->route('siswa.mapel.detail', $ujian->kelas_id)->with('error', 'Anda sudah mengerjakan ujian ini.');
        }

        $totalSoal = $ujian->soal->count();
        $benar = 0;

        if (is_array($answers)) {
            foreach ($answers as $ans) {
                if ($ans['jawaban'] === null) continue; // Skip jika tidak dijawab
                $soal = $ujian->soal->where('id', $ans['soal_id'])->first();
                if ($soal) {
                    $isBenar = null;
                    if ($soal->tipe !== 'essay') {
                        $isBenar = ((string) $ans['jawaban'] === (string) $soal->jawaban_benar);
                        if ($isBenar) $benar++;
                    }

                    JawabanUjian::create([
                        'ujian_id' => $ujian->id,
                        'siswa_id' => $user->id,
                        'soal_id'  => $soal->id,
                        'jawaban'  => (string) $ans['jawaban'],
                        'benar'    => $isBenar
                    ]);
                }
            }
        }
        
        // Auto-grade only multiple choice initially
        $totalMcq = $ujian->soal->where('tipe', '!=', 'essay')->count();
        $skor = $totalMcq > 0 ? ($benar / $totalMcq) * 100 : 0;
        
        \App\Models\Nilai::create([
            'siswa_id' => $user->id,
            'kelas_id' => $ujian->kelas_id,
            'nilaiable_type' => Ujian::class,
            'nilaiable_id' => $ujian->id,
            'nilai' => $skor,
        ]);

        return redirect()->route('siswa.mapel.detail', $ujian->kelas_id)->with('success', 'Ujian berhasil dikumpulkan!');
    }
}