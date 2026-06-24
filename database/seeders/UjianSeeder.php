<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ujian;
use App\Models\SoalUjian;
use App\Models\Kelas;
use App\Models\User;

class UjianSeeder extends Seeder
{
    public function run(): void
    {
        $kelas1 = Kelas::where('kode_kelas', 'MTK-001')->first();
        $guru1  = User::where('email', 'guru1@lms.com')->first();

        $ujian = Ujian::create([
            'kelas_id'       => $kelas1->id,
            'guru_id'        => $guru1->id,
            'judul'          => 'Ujian Tengah Semester - Matematika',
            'deskripsi'      => 'UTS Matematika Kelas X IPA 1',
            'mulai_at'       => now()->addDays(3),
            'selesai_at'     => now()->addDays(3)->addHours(2),
            'durasi_menit'   => 90,
            'nilai_maksimal' => 100,
        ]);

        $soalList = [
            [
                'pertanyaan'    => 'Berapakah nilai x dari persamaan 2x + 4 = 10?',
                'tipe'          => 'pilihan_ganda',
                'pilihan'       => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5'],
                'jawaban_benar' => 'B',
                'bobot'         => 25,
                'urutan'        => 1,
            ],
            [
                'pertanyaan'    => 'Hasil dari 3² + 4² adalah?',
                'tipe'          => 'pilihan_ganda',
                'pilihan'       => ['A' => '20', 'B' => '25', 'C' => '30', 'D' => '35'],
                'jawaban_benar' => 'B',
                'bobot'         => 25,
                'urutan'        => 2,
            ],
        ];

        foreach ($soalList as $soal) {
            SoalUjian::create(array_merge(['ujian_id' => $ujian->id], $soal));
        }
    }
}