<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JawabanKuis;
use App\Models\JawabanUjian;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function tugas(Request $request)
    {
        $submission = PengumpulanTugas::with(['tugas.kelas', 'siswa'])
            ->whereHas('tugas', fn ($query) => $query->where('guru_id', Auth::id()))
            ->when($request->id, fn ($query, $id) => $query->where('id', $id))
            ->firstOrFail();

        return view('guru.penilaian-tugas', compact('submission'));
    }

    public function storeTugas(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        $submission = PengumpulanTugas::whereHas('tugas', fn ($query) => $query->where('guru_id', Auth::id()))
            ->findOrFail($id);

        $submission->update([
            'nilai' => $request->nilai,
            'feedback' => $request->feedback
        ]);

        // Simpan juga ke tabel nilai untuk keperluan rekap
        \App\Models\Nilai::updateOrCreate(
            [
                'siswa_id' => $submission->siswa_id,
                'kelas_id' => $submission->tugas->kelas_id,
                'nilaiable_type' => \App\Models\Tugas::class,
                'nilaiable_id' => $submission->tugas_id,
            ],
            [
                'nilai' => $request->nilai,
                'catatan' => $request->feedback
            ]
        );

        return redirect()->route('guru.monitor.tugas')->with('success', 'Nilai tugas berhasil disimpan!');
    }

    public function kuis(Request $request)
    {
        $answers = JawabanKuis::with(['kuis.kelas', 'siswa', 'soal'])
            ->whereHas('kuis', fn ($query) => $query->where('guru_id', Auth::id()))
            ->orderBy('siswa_id')
            ->orderBy('kuis_id')
            ->get();

        $groups = $answers->groupBy(fn ($item) => $item->siswa_id . '_' . $item->kuis_id);
        $keys = $groups->keys();
        $index = (int) $request->query('s', 0);

        if (!isset($keys[$index])) {
            abort(404);
        }

        $selected = $groups[$keys[$index]];
        $first = $selected->first();
        $submission = [
            'siswa' => $first->siswa,
            'kuis' => $first->kuis,
            'answers' => $selected,
            'score' => $selected->where('benar', true)->count(),
            'total' => $selected->count(),
        ];

        return view('guru.penilaian-kuis', compact('submission'));
    }

    public function ujian(Request $request)
    {
        $answers = JawabanUjian::with(['ujian.kelas', 'siswa', 'soal'])
            ->whereHas('ujian', fn ($query) => $query->where('guru_id', Auth::id()))
            ->orderBy('siswa_id')
            ->orderBy('ujian_id')
            ->orderBy('attempt')
            ->get();

        $groups = $answers->groupBy(fn ($item) => $item->siswa_id . '_' . $item->ujian_id . '_' . $item->attempt);
        $keys = $groups->keys();
        $index = (int) $request->query('s', 0);

        if (!isset($keys[$index])) {
            abort(404);
        }

        $selected = $groups[$keys[$index]];
        $first = $selected->first();
        $submission = [
            'siswa' => $first->siswa,
            'ujian' => $first->ujian,
            'attempt' => $first->attempt,
            'answers' => $selected,
            'score' => $selected->where('benar', true)->sum('nilai') ?: $selected->where('benar', true)->count(),
            'total' => $selected->count(),
        ];

        return view('guru.penilaian-ujian', compact('submission'));
    }

    public function storeKuis(Request $request, $kuis_id, $siswa_id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        $kuis = \App\Models\Kuis::where('guru_id', Auth::id())->findOrFail($kuis_id);
        
        \App\Models\Nilai::updateOrCreate(
            [
                'siswa_id' => $siswa_id,
                'kelas_id' => $kuis->kelas_id,
                'nilaiable_type' => \App\Models\Kuis::class,
                'nilaiable_id' => $kuis->id,
            ],
            [
                'nilai' => $request->nilai
            ]
        );

        return redirect()->route('guru.monitor')->with('success', 'Nilai Kuis berhasil disimpan!');
    }

    public function storeUjian(Request $request, $ujian_id, $siswa_id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        $ujian = \App\Models\Ujian::where('guru_id', Auth::id())->findOrFail($ujian_id);
        
        \App\Models\Nilai::updateOrCreate(
            [
                'siswa_id' => $siswa_id,
                'kelas_id' => $ujian->kelas_id,
                'nilaiable_type' => \App\Models\Ujian::class,
                'nilaiable_id' => $ujian->id,
                'attempt' => $request->attempt ?? 1,
            ],
            [
                'nilai' => $request->nilai
            ]
        );

        return redirect()->route('guru.monitor')->with('success', 'Nilai Ujian berhasil disimpan!');
    }
}
