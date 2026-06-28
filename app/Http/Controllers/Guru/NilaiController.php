<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\PengumpulanTugas;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $rekap = $this->prepareRekap();
        return view('guru.nilai', $rekap);
    }

    public function rekap()
    {
        $rekap = $this->prepareRekap();
        return view('guru.rekap-nilai', $rekap);
    }

    private function prepareRekap(): array
    {
        $guruId = Auth::id();
        $kelas = Kelas::with('siswa')->where('guru_id', $guruId)->get();
        $kelasIds = $kelas->pluck('id');

        $siswaCollection = collect();
        foreach ($kelas as $k) {
            foreach ($k->siswa as $s) {
                if (!$siswaCollection->has($s->id)) {
                    // attach the class to the student object for display purposes
                    $s->kelas = $k; 
                    $siswaCollection->put($s->id, $s);
                }
            }
        }

        $allNilai = Nilai::whereIn('kelas_id', $kelasIds)->get();
        $allTugas = PengumpulanTugas::whereHas('tugas', fn($q) => $q->whereIn('kelas_id', $kelasIds))->whereNotNull('nilai')->get();

        $students = $siswaCollection->map(function ($siswa) use ($allNilai, $allTugas) {
            $studentNilai = $allNilai->where('siswa_id', $siswa->id);
            $studentTugas = $allTugas->where('siswa_id', $siswa->id);

            $tugasScores = $studentTugas->pluck('nilai')->map(fn($v) => floatval($v))->toArray();
            
            $kuisScores = [];
            $ujianScores = [];
            
            foreach ($studentNilai as $n) {
                $type = strtolower(class_basename($n->nilaiable_type));
                if ($type === 'kuis') {
                    $kuisScores[] = floatval($n->nilai);
                } elseif ($type === 'ujian') {
                    $ujianScores[] = floatval($n->nilai);
                }
            }

            $computeAverage = fn ($scores) => count($scores) ? round(array_sum($scores) / count($scores), 1) : 0;

            $tugas = $computeAverage($tugasScores);
            $kuis = $computeAverage($kuisScores);
            $ujian = $computeAverage($ujianScores);
            
            $available = array_filter([$tugas, $kuis, $ujian], fn ($score) => $score > 0);
            $average = count($available) ? round(array_sum($available) / count($available), 1) : 0;

            return [
                'siswa' => $siswa,
                'kelas' => $siswa->kelas,
                'tugas' => $tugas,
                'kuis' => $kuis,
                'ujian' => $ujian,
                'average' => $average,
                'status' => $average >= 75 ? 'Lulus' : 'Remedial',
                'predikat' => $average >= 90 ? 'A' : ($average >= 80 ? 'B+' : ($average >= 70 ? 'B' : ($average >= 60 ? 'C' : 'D'))),
            ];
        })->values();

        $summary = [
            'total' => $students->count(),
            'average' => $students->count() ? round($students->avg('average'), 1) : 0,
            'max' => $students->count() ? $students->max('average') : 0,
            'min' => $students->count() ? $students->min('average') : 0,
            'pass' => $students->filter(fn ($item) => $item['average'] >= 75)->count(),
            'fail' => $students->filter(fn ($item) => $item['average'] < 75)->count(),
        ];

        // dummy records for view compatibility (if they use $records directly)
        $records = $allNilai;

        return compact('records', 'students', 'summary');
    }
}
