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
            ->get();

        $groups = $answers->groupBy(fn ($item) => $item->siswa_id . '_' . $item->ujian_id);
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
            'answers' => $selected,
            'score' => $selected->where('benar', true)->sum('nilai') ?: $selected->where('benar', true)->count(),
            'total' => $selected->count(),
        ];

        return view('guru.penilaian-ujian', compact('submission'));
    }
}
