<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\Kuis;
use App\Models\Nilai;
use App\Models\PengumpulanTugas;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get IDs of classes the student is enrolled in (if dev, see all)
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $kelasAktifCount = $kelasIds->count();

        // 1. Fetch pending Tugas
        $tugasBelumDikerjakanQuery = Tugas::whereIn('kelas_id', $kelasIds)
            ->whereDoesntHave('pengumpulan', function ($query) use ($user) {
                $query->where('siswa_id', $user->id);
            });
            
        $tugasBelumDikerjakanCount = $tugasBelumDikerjakanQuery->count();
        
        $tugasMendekatiDeadlineCount = (clone $tugasBelumDikerjakanQuery)
            ->whereNotNull('deadline')
            ->where('deadline', '>', now())
            ->where('deadline', '<=', now()->addDays(3))
            ->count();

        $tugasMendatang = (clone $tugasBelumDikerjakanQuery)
            ->orderBy('deadline', 'asc')
            ->get();

        // 2. Fetch pending Ujian
        $ujianMendatang = Ujian::whereIn('kelas_id', $kelasIds)
            ->whereDoesntHave('nilai_siswa', function ($query) use ($user) {
                $query->where('siswa_id', $user->id);
            })
            ->get();

        // 3. Fetch pending Kuis
        $kuisMendatang = Kuis::whereIn('kelas_id', $kelasIds)
            ->whereDoesntHave('nilai_siswa', function ($query) use ($user) {
                $query->where('siswa_id', $user->id);
            })
            ->get();

        // Merge all pending activities
        $aktivitasMendatang = collect();

        foreach ($tugasMendatang as $t) {
            $aktivitasMendatang->push((object)[
                'id' => $t->id,
                'judul' => $t->judul,
                'tipe' => 'Tugas',
                'mata_pelajaran' => $t->kelas->mata_pelajaran ?? 'Umum',
                'deadline' => $t->deadline,
                'route' => route('siswa.pengerjaan-tugas', $t->id)
            ]);
        }

        foreach ($ujianMendatang as $u) {
            $aktivitasMendatang->push((object)[
                'id' => $u->id,
                'judul' => $u->judul,
                'tipe' => 'Ujian',
                'mata_pelajaran' => $u->kelas->mata_pelajaran ?? 'Umum',
                // Ujian menggunakan selesai_at sebagai deadline
                'deadline' => $u->selesai_at,
                'route' => route('siswa.pengerjaan-ujian', $u->id)
            ]);
        }

        foreach ($kuisMendatang as $k) {
            $aktivitasMendatang->push((object)[
                'id' => $k->id,
                'judul' => $k->judul,
                'tipe' => 'Kuis',
                'mata_pelajaran' => $k->kelas->mata_pelajaran ?? 'Umum',
                // Kuis tidak memiliki deadline di skema database saat ini (kita anggap null)
                'deadline' => null,
                'route' => route('siswa.pengerjaan-kuis', $k->id)
            ]);
        }

        // Sort: yang punya deadline lebih dekat dulu, yang null di akhir
        $aktivitasMendatang = $aktivitasMendatang->sortBy(function ($item) {
            return $item->deadline ? \Carbon\Carbon::parse($item->deadline)->timestamp : PHP_INT_MAX;
        })->take(6)->values(); // Ambil 6 aktivitas

        // Fetch recent materials
        $materiBaru = Materi::whereIn('kelas_id', $kelasIds)
            ->latest()
            ->take(5)
            ->get();

        // Hitung Rata-rata Nilai dan Ambil Nilai Terbaru
        $nilaiUjianKuis = Nilai::with('nilaiable.kelas')
            ->where('siswa_id', $user->id)
            ->get();

        $nilaiTugas = PengumpulanTugas::with('tugas.kelas')
            ->where('siswa_id', $user->id)
            ->whereNotNull('nilai')
            ->get();

        $allNilai = [];
        foreach ($nilaiUjianKuis as $n) {
            $type = class_basename($n->nilaiable_type) === 'Ujian' ? 'Ujian' : 'Kuis';
            $allNilai[] = [
                'mapel' => $n->nilaiable->kelas->mata_pelajaran ?? 'Umum',
                'judul' => $n->nilaiable->judul ?? $type,
                'nilai' => floatval($n->nilai),
                'timestamp' => $n->created_at->timestamp,
            ];
        }

        foreach ($nilaiTugas as $t) {
            $allNilai[] = [
                'mapel' => $t->tugas->kelas->mata_pelajaran ?? 'Umum',
                'judul' => $t->tugas->judul ?? 'Tugas',
                'nilai' => floatval($t->nilai),
                'timestamp' => $t->updated_at->timestamp,
            ];
        }

        $totalScore = array_sum(array_column($allNilai, 'nilai'));
        $countScore = count($allNilai);
        $rataRataNilai = $countScore > 0 ? round($totalScore / $countScore, 1) : 0;

        // Urutkan nilai terbaru
        usort($allNilai, function($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        $nilaiTerbaru = array_slice($allNilai, 0, 5);

        return view('siswa.dashboard', compact(
            'aktivitasMendatang', 
            'materiBaru', 
            'user', 
            'kelasAktifCount', 
            'tugasBelumDikerjakanCount', 
            'tugasMendekatiDeadlineCount', 
            'rataRataNilai', 
            'nilaiTerbaru'
        ));
    }
}
