<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\JawabanUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UjianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $ujian = Ujian::whereIn('kelas_id', $kelasIds)->latest()->get();
        return view('siswa.ujian', compact('ujian'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $ujian = Ujian::with('soal')->whereIn('kelas_id', $kelasIds)->findOrFail($id);

        if ($ujian->acak_soal) {
            // Shuffle the soal collection but maintain it as a collection
            $ujian->setRelation('soal', $ujian->soal->shuffle());
        }

        $attemptsCount = JawabanUjian::where('ujian_id', $ujian->id)
                            ->where('siswa_id', $user->id)
                            ->max('attempt') ?? 0;

        if ($attemptsCount >= $ujian->batas_percobaan) {
            return redirect()->route('siswa.mapel.detail', $ujian->kelas_id)->with('error', 'Anda telah mencapai batas maksimal percobaan untuk ujian ini.');
        }
        
        $currentAttempt = $attemptsCount + 1;
        $cacheKey = "ujian_start_{$user->id}_{$ujian->id}_{$currentAttempt}";
        $startTime = Cache::get($cacheKey);
        
        if (!$startTime || !is_numeric($startTime)) {
            $startTime = now()->timestamp;
            Cache::put($cacheKey, $startTime, now()->addMinutes($ujian->durasi_menit));
        }
        
        $elapsedSeconds = now()->timestamp - $startTime;
        $remainingSeconds = max(0, ($ujian->durasi_menit * 60) - $elapsedSeconds);

        return view('siswa.pengerjaan-ujian', compact('ujian', 'remainingSeconds'));
    }

    public function store(Request $request, $id)
    {
        $ujian = Ujian::with('soal')->findOrFail($id);
        $answers = json_decode($request->answers, true);
        $user = Auth::user();

        $latestAttempt = JawabanUjian::where('ujian_id', $ujian->id)
                            ->where('siswa_id', $user->id)
                            ->max('attempt') ?? 0;

        if ($latestAttempt >= $ujian->batas_percobaan) {
            return redirect()->route('siswa.mapel.detail', $ujian->kelas_id)->with('error', 'Anda telah mencapai batas maksimal percobaan untuk ujian ini.');
        }
        
        $currentAttempt = $latestAttempt + 1;

        $totalSoal = $ujian->soal->count();
        $benar = 0;

        if (is_array($answers)) {
            foreach ($answers as $ans) {
                if ($ans['jawaban'] === null) continue; // Skip jika tidak dijawab
                $soal = $ujian->soal->where('id', $ans['soal_id'])->first();
                if ($soal) {
                    $isBenar = null;
                    if ($soal->tipe !== 'essay') {
                        $correctAns = (string) $soal->jawaban_benar;
                        $points = 0;
                        if (is_array($ans['jawaban']) || $soal->tipe === 'multiple_select') {
                            $c = json_decode($correctAns, true);
                            if (is_array($c) && count($c) > 0) {
                                $s = is_array($ans['jawaban']) ? $ans['jawaban'] : [$ans['jawaban']];
                                $c = array_map('strval', $c);
                                $s = array_map('strval', $s);
                                
                                $correctCount = count(array_intersect($s, $c));
                                $points = $correctCount / count($c);
                                $isBenar = ($points > 0);
                            } else {
                                $isBenar = false;
                            }
                        } else {
                            $isBenar = ((string) $ans['jawaban'] === $correctAns);
                            $points = $isBenar ? 1 : 0;
                        }
                        
                        $benar += $points;
                    }

                    JawabanUjian::create([
                        'ujian_id' => $ujian->id,
                        'siswa_id' => $user->id,
                        'soal_id'  => $soal->id,
                        'attempt'  => $currentAttempt,
                        'jawaban'  => is_array($ans['jawaban']) ? json_encode($ans['jawaban']) : (string) $ans['jawaban'],
                        'benar'    => $isBenar === null ? null : ($isBenar ? 'true' : 'false')
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
            'attempt' => $currentAttempt,
            'nilai' => $skor,
        ]);

        Cache::forget("ujian_start_{$user->id}_{$ujian->id}_{$currentAttempt}");

        return redirect()->route('siswa.mapel.detail', $ujian->kelas_id)->with('success', 'Ujian berhasil dikumpulkan!');
    }
}