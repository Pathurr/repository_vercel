<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Nilai;
use App\Models\PengumpulanTugas;
use App\Models\KelasSiswa;
use App\Models\Tugas;

class NilaiController extends Controller
{
    public function index()
    {
        $siswaId = Auth::id();

        // Ambil data nilai kuis dan ujian
        $nilaiUjianKuis = Nilai::with('nilaiable.guru')
            ->where('siswa_id', $siswaId)
            ->get();

        // Ambil data nilai tugas (hanya yang sudah dinilai)
        $nilaiTugas = PengumpulanTugas::with('tugas.guru')
            ->where('siswa_id', $siswaId)
            ->whereNotNull('nilai')
            ->get();

        $dataNilai = [];
        $idCounter = 1;

        foreach ($nilaiUjianKuis as $n) {
            $type = class_basename($n->nilaiable_type) === 'Ujian' ? 'ujian' : 'kuis';
            $guruName = $n->nilaiable->guru->name ?? '-';
            $dataNilai[] = [
                'id' => $idCounter++,
                'name' => $n->nilaiable->judul ?? 'Tidak diketahui',
                'desc' => $type === 'ujian' ? 'Ujian Kelas' : 'Kuis Kelas',
                'category' => $type,
                'dateStr' => $n->created_at->format('d M Y'),
                'timestamp' => $n->created_at->timestamp,
                'score' => floatval($n->nilai),
                'feedback' => $n->catatan ?? '-',
                'teacher' => $guruName
            ];
        }

        foreach ($nilaiTugas as $t) {
            $guruName = $t->tugas->guru->name ?? '-';
            $dataNilai[] = [
                'id' => $idCounter++,
                'name' => $t->tugas->judul ?? 'Tugas',
                'desc' => 'Tugas Kelas',
                'category' => 'tugas',
                'dateStr' => $t->updated_at->format('d M Y'), // Tanggal dinilai
                'timestamp' => $t->updated_at->timestamp,
                'score' => floatval($t->nilai),
                'feedback' => $t->feedback ?? '-',
                'teacher' => $guruName
            ];
        }

        // Hitung rata-rata
        $totalScore = 0;
        $count = count($dataNilai);
        $rataRata = $count > 0 ? round(array_sum(array_column($dataNilai, 'score')) / $count, 1) : 0;

        // Hitung tugas terkumpul vs total tugas (untuk user ini)
        $kelasSiswaIds = KelasSiswa::where('siswa_id', $siswaId)->pluck('kelas_id');
        $totalTugas = Tugas::whereIn('kelas_id', $kelasSiswaIds)->count();
        $tugasSelesai = PengumpulanTugas::where('siswa_id', $siswaId)->count();

        // Urutkan default terbaru (berdasarkan timestamp descending)
        usort($dataNilai, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return view('siswa.nilai', compact('dataNilai', 'rataRata', 'totalTugas', 'tugasSelesai'));
    }
}
