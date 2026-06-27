<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Tugas;
use Carbon\Carbon;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = Kelas::all();

        foreach ($kelas as $k) {
            $guruId = $k->guru_id;

            // Tugas 1: Sudah Lewat Deadline (untuk testing status terlambat)
            Tugas::create([
                'kelas_id' => $k->id,
                'guru_id' => $guruId,
                'judul' => "Tugas 1: Pengenalan " . $k->mata_pelajaran,
                'deskripsi' => "Harap buat rangkuman tentang materi pertama dari " . $k->mata_pelajaran . ". Dikumpulkan dalam format PDF maksimal 2 halaman.",
                'deadline' => Carbon::now()->subDays(2), // 2 hari yang lalu
                'nilai_maksimal' => 100,
            ]);

            // Tugas 2: Mendekati Deadline / Aktif
            Tugas::create([
                'kelas_id' => $k->id,
                'guru_id' => $guruId,
                'judul' => "Tugas 2: Praktikum " . $k->mata_pelajaran,
                'deskripsi' => "Lakukan studi kasus mengenai " . $k->mata_pelajaran . " dan unggah hasil observasi Anda beserta laporannya.",
                'deadline' => Carbon::now()->addDays(2), // 2 hari lagi
                'nilai_maksimal' => 100,
            ]);
        }
    }
}