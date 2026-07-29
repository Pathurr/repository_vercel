<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InteraksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = \App\Models\User::where('email', 'alfathurahman28@gmail.com')->first();
        $guru = \App\Models\User::where('email', 'stillgoodman466@gmail.com')->first();

        if (!$siswa || !$guru) return;

        $kelas = \App\Models\Kelas::where('guru_id', $guru->id)->first();
        if (!$kelas) return;

        $tugas = \App\Models\Tugas::where('kelas_id', $kelas->id)->first();
        if ($tugas) {
            $pengumpulan = \Illuminate\Support\Facades\DB::table('pengumpulan_tugas')->insertGetId([
                'tugas_id' => $tugas->id,
                'siswa_id' => $siswa->id,
                'file_path' => 'demo/tugas.pdf',
                'status' => 'tepat_waktu',
                'nilai' => 85,
                'feedback' => 'Bagus sekali Alfath, pertahankan!',
                'dikumpulkan_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Illuminate\Support\Facades\DB::table('nilai')->insert([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'nilaiable_type' => \App\Models\Tugas::class,
                'nilaiable_id' => $tugas->id,
                'nilai' => 85,
                'catatan' => 'Bagus sekali Alfath, pertahankan!',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $materi = \App\Models\Materi::where('kelas_id', $kelas->id)->first();
        if ($materi) {
            \Illuminate\Support\Facades\DB::table('materi_siswa')->insert([
                'materi_id' => $materi->id,
                'siswa_id' => $siswa->id,
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
