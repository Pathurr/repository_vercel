<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Kuis;
use App\Models\Ujian;
use App\Models\SoalKuis;
use App\Models\SoalUjian;
use Carbon\Carbon;

class KuisUjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = Kelas::all();

        foreach ($kelas as $k) {
            $guruId = $k->guru_id;

            // Buat 2 Kuis
            for ($i = 1; $i <= 2; $i++) {
                $kuis = Kuis::create([
                    'kelas_id' => $k->id,
                    'guru_id' => $guruId,
                    'judul' => "Kuis $i: Evaluasi Pemahaman " . $k->mata_pelajaran,
                    'deskripsi' => "Kuis ini dibuat secara otomatis untuk menguji pemahaman materi ke-$i.",
                    'durasi_menit' => 30 + ($i * 15),
                ]);

                // Buat 3 soal untuk masing-masing kuis
                for ($j = 1; $j <= 3; $j++) {
                    $pilihan = [
                        'A' => "Jawaban A untuk soal $j",
                        'B' => "Jawaban B untuk soal $j",
                        'C' => "Jawaban C untuk soal $j",
                        'D' => "Jawaban D untuk soal $j",
                    ];
                    $jawabanBenar = array_rand(['A'=>1, 'B'=>1, 'C'=>1, 'D'=>1]);
                    
                    SoalKuis::create([
                        'kuis_id' => $kuis->id,
                        'pertanyaan' => "Pertanyaan Kuis $i Soal $j untuk kelas " . $k->nama_kelas . ". Manakah jawaban yang benar?",
                        'pilihan' => $pilihan,
                        'jawaban_benar' => $jawabanBenar,
                        'bobot' => 10,
                        'urutan' => $j
                    ]);
                }
            }

            // Buat 2 Ujian
            for ($i = 1; $i <= 2; $i++) {
                $isMendatang = $i === 2; // Ujian 1 mungkin sedang berlangsung/sudah lewat, Ujian 2 mendatang
                
                $mulaiAt = $isMendatang ? Carbon::now()->addDays(2) : Carbon::now()->subHours(1);
                $selesaiAt = $isMendatang ? Carbon::now()->addDays(2)->addHours(2) : Carbon::now()->addHours(1);
                
                $ujian = Ujian::create([
                    'kelas_id' => $k->id,
                    'guru_id' => $guruId,
                    'judul' => $i === 1 ? "Ujian Tengah Semester " . $k->mata_pelajaran : "Ujian Akhir Semester " . $k->mata_pelajaran,
                    'deskripsi' => "Ujian resmi yang tercatat di sistem untuk evaluasi kelas " . $k->nama_kelas,
                    'mulai_at' => $mulaiAt,
                    'selesai_at' => $selesaiAt,
                    'durasi_menit' => 120,
                    'nilai_maksimal' => 100,
                ]);

                // Buat 5 soal untuk masing-masing ujian
                for ($j = 1; $j <= 5; $j++) {
                    $pilihan = [
                        'A' => "Pilihan Jawaban A untuk soal $j ujian",
                        'B' => "Pilihan Jawaban B untuk soal $j ujian",
                        'C' => "Pilihan Jawaban C untuk soal $j ujian",
                        'D' => "Pilihan Jawaban D untuk soal $j ujian",
                        'E' => "Pilihan Jawaban E untuk soal $j ujian",
                    ];
                    $jawabanBenar = array_rand(['A'=>1, 'B'=>1, 'C'=>1, 'D'=>1, 'E'=>1]);
                    
                    SoalUjian::create([
                        'ujian_id' => $ujian->id,
                        'pertanyaan' => "Ini adalah soal Ujian nomor $j. Harap pilih jawaban yang paling tepat sesuai dengan materi yang dipelajari.",
                        'pilihan' => $pilihan,
                        'jawaban_benar' => $jawabanBenar,
                        'bobot' => 20,
                        'urutan' => $j
                    ]);
                }
            }
        }
    }
}
