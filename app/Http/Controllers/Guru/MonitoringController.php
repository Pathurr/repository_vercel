<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $allSubmissions = collect();

        // 1. Tugas
        $tugasSubmissions = PengumpulanTugas::with(['tugas.kelas', 'siswa'])
            ->whereHas('tugas', fn ($query) => $query->where('guru_id', Auth::id()))
            ->when($request->tugas_id, fn ($query, $id) => $query->where('tugas_id', $id))
            ->get();

        foreach ($tugasSubmissions as $sub) {
            $status = $sub->status ?? ($sub->dikumpulkan_at && $sub->tugas->deadline ? ($sub->dikumpulkan_at->greaterThan($sub->tugas->deadline) ? 'terlambat' : 'terkumpul') : 'terkumpul');
            $allSubmissions->push((object)[
                'type' => 'tugas',
                'id' => $sub->id,
                'siswa' => $sub->siswa,
                'kelas' => $sub->tugas->kelas,
                'judul' => $sub->tugas->judul,
                'status' => $status,
                'dikumpulkan_at' => $sub->dikumpulkan_at,
                'nilai' => $sub->nilai,
                'link' => route('guru.penilaian.tugas', ['id' => $sub->id])
            ]);
        }

        if (!$request->tugas_id) {
            // 2. Kuis
            $jawabanKuis = \App\Models\JawabanKuis::with(['kuis.kelas', 'siswa'])
                ->whereHas('kuis', fn ($query) => $query->where('guru_id', Auth::id()))
                ->orderBy('siswa_id')
                ->orderBy('kuis_id')
                ->get();
            $groupsKuis = $jawabanKuis->groupBy(fn ($item) => $item->siswa_id . '_' . $item->kuis_id);
            $keysKuis = $groupsKuis->keys();
            foreach ($groupsKuis as $key => $group) {
                $first = $group->first();
                $nilaiM = \App\Models\Nilai::where('siswa_id', $first->siswa_id)->where('nilaiable_type', \App\Models\Kuis::class)->where('nilaiable_id', $first->kuis_id)->first();
                $allSubmissions->push((object)[
                    'type' => 'kuis',
                    'id' => $key,
                    'siswa' => $first->siswa,
                    'kelas' => $first->kuis->kelas,
                    'judul' => $first->kuis->judul,
                    'status' => 'terkumpul',
                    'dikumpulkan_at' => $first->created_at,
                    'nilai' => $nilaiM ? $nilaiM->nilai : null,
                    'link' => route('guru.penilaian.kuis', ['s' => $keysKuis->search($key)])
                ]);
            }

            // 3. Ujian
            $jawabanUjian = \App\Models\JawabanUjian::with(['ujian.kelas', 'siswa'])
                ->whereHas('ujian', fn ($query) => $query->where('guru_id', Auth::id()))
                ->orderBy('siswa_id')
                ->orderBy('ujian_id')
                ->orderBy('attempt')
                ->get();
            $groupsUjian = $jawabanUjian->groupBy(fn ($item) => $item->siswa_id . '_' . $item->ujian_id . '_' . $item->attempt);
            $keysUjian = $groupsUjian->keys();
            foreach ($groupsUjian as $key => $group) {
                $first = $group->first();
                $nilaiM = \App\Models\Nilai::where('siswa_id', $first->siswa_id)
                                          ->where('nilaiable_type', \App\Models\Ujian::class)
                                          ->where('nilaiable_id', $first->ujian_id)
                                          ->where('attempt', $first->attempt)
                                          ->first();
                $allSubmissions->push((object)[
                    'type' => 'ujian',
                    'id' => $key,
                    'siswa' => $first->siswa,
                    'kelas' => $first->ujian->kelas,
                    'judul' => $first->ujian->judul . ' (Percobaan ' . $first->attempt . ')',
                    'status' => 'terkumpul',
                    'dikumpulkan_at' => $first->created_at,
                    'nilai' => $nilaiM ? $nilaiM->nilai : null,
                    'link' => route('guru.penilaian.ujian', ['s' => $keysUjian->search($key)])
                ]);
            }
        }

        $submissions = $allSubmissions->sortByDesc('dikumpulkan_at')->values();

        return view('guru.monitor-tugas', compact('submissions'));
    }
}
