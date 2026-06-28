<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\JawabanKuis;
use Illuminate\Http\Request;
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

        $kuis = Kuis::with('soal')->whereIn('kelas_id', $kelasIds)->findOrFail($id);
        return view('siswa.pengerjaan-kuis', compact('kuis'));
    }

    public function store(Request $request, $id)
    {
        $kuis = Kuis::with('soal')->findOrFail($id);
        $answers = json_decode($request->answers, true);
        $user = Auth::user();

        // Cek jika sudah pernah mengerjakan
        $sudah = JawabanKuis::where('kuis_id', $kuis->id)->where('siswa_id', $user->id)->exists();
        if ($sudah) {
            return redirect()->route('siswa.mapel.detail', $kuis->kelas_id)->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        $totalSoal = $kuis->soal->count();
        $benar = 0;

        if (is_array($answers)) {
            foreach ($answers as $ans) {
                if ($ans['jawaban'] === null) continue; // Skip jika tidak dijawab
                $soal = $kuis->soal->where('id', $ans['soal_id'])->first();
                if ($soal) {
                    $isBenar = null;
                    if ($soal->tipe !== 'essay') {
                        $isBenar = ((string) $ans['jawaban'] === (string) $soal->jawaban_benar);
                        if ($isBenar) $benar++;
                    }

                    JawabanKuis::create([
                        'kuis_id'  => $kuis->id,
                        'siswa_id' => $user->id,
                        'soal_id'  => $soal->id,
                        'jawaban'  => (string) $ans['jawaban'],
                        'benar'    => $isBenar
                    ]);
                }
            }
        }
        
        // Auto-grade only multiple choice initially
        $totalMcq = $kuis->soal->where('tipe', '!=', 'essay')->count();
        $skor = $totalMcq > 0 ? ($benar / $totalMcq) * 100 : 0;
        
        \App\Models\Nilai::create([
            'siswa_id' => $user->id,
            'kelas_id' => $kuis->kelas_id,
            'nilaiable_type' => Kuis::class,
            'nilaiable_id' => $kuis->id,
            'nilai' => $skor,
        ]);

        return redirect()->route('siswa.mapel.detail', $kuis->kelas_id)->with('success', 'Kuis berhasil dikumpulkan!');
    }
}