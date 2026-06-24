<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
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
        $records = Nilai::with(['siswa', 'kelas'])
            ->whereHas('kelas', fn ($q) => $q->where('guru_id', Auth::id()))
            ->get();

        $students = $records->groupBy('siswa_id')->map(function ($items) {
            $siswa = $items->first()->siswa;
            $kelas = $items->first()->kelas;

            $typeScores = [
                'tugas' => [],
                'kuis' => [],
                'ujian' => [],
            ];

            foreach ($items as $item) {
                $type = strtolower(class_basename($item->nilaiable_type));
                if (isset($typeScores[$type])) {
                    $typeScores[$type][] = $item->nilai;
                }
            }

            $computeAverage = fn ($scores) => count($scores) ? round(array_sum($scores) / count($scores), 1) : 0;

            $tugas = $computeAverage($typeScores['tugas']);
            $kuis = $computeAverage($typeScores['kuis']);
            $ujian = $computeAverage($typeScores['ujian']);
            $available = array_filter([$tugas, $kuis, $ujian], fn ($score) => $score > 0);
            $average = count($available) ? round(array_sum($available) / count($available), 1) : 0;

            return [
                'siswa' => $siswa,
                'kelas' => $kelas,
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

        return compact('records', 'students', 'summary');
    }
}
