<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Kelas;
use App\Models\User;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas1  = Kelas::where('kode_kelas', 'MTK-001')->first();
        $guru1   = User::where('email', 'guru1@lms.com')->first();
        $siswaList = User::where('role', 'siswa')->get();

        $tugas = Tugas::create([
            'kelas_id'       => $kelas1->id,
            'guru_id'        => $guru1->id,
            'judul'          => 'Latihan Soal Aljabar',
            'deskripsi'      => 'Kerjakan soal aljabar halaman 25-30',
            'deadline'       => now()->addDays(7),
            'nilai_maksimal' => 100,
        ]);

        // Buat pengumpulan dummy untuk beberapa siswa
        foreach ($siswaList->take(3) as $siswa) {
            PengumpulanTugas::create([
                'tugas_id'       => $tugas->id,
                'siswa_id'       => $siswa->id,
                'catatan'        => 'Sudah dikerjakan semua',
                'dikumpulkan_at' => now(),
                'nilai'          => rand(70, 100),
                'status'         => 'tepat_waktu',
            ]);
        }
    }
}