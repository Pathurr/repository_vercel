<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoLengkapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat 1 Guru Baru
        $guru = \App\Models\User::create([
            'name' => 'Guru Demo Lengkap',
            'email' => 'guru.demo@lms.com',
            'password' => 'password',
            'role' => 'guru',
            'nrg' => '9988776655',
            'status' => 'active',
        ]);

        // 2. Buat 1 Kelas Baru
        $kelas = \App\Models\Kelas::create([
            'guru_id' => $guru->id,
            'nama_kelas' => 'Kelas Demo Super Lengkap',
            'mata_pelajaran' => 'Simulasi LMS',
            'kode_kelas' => 'DEMO123',
            'aktif' => 'true',
        ]);

        // 3. Buat 1 Materi
        $materi = \App\Models\Materi::create([
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
            'judul' => 'Materi Simulasi Lengkap',
            'deskripsi' => 'Ini adalah materi contoh yang dibuat secara otomatis.',
            'file_path' => 'demo/materi_simulasi.pdf',
        ]);

        // 4. Buat 1 Tugas
        $tugas = \App\Models\Tugas::create([
            'kelas_id' => $kelas->id,
            'guru_id' => $guru->id,
            'judul' => 'Tugas Simulasi Lengkap',
            'deskripsi' => 'Kerjakan simulasi ini dengan baik.',
            'deadline' => \Carbon\Carbon::now()->addDays(5),
            'nilai_maksimal' => 100,
        ]);

        // 5. Buat 20 Siswa Baru, Masukkan Kelas, dan Simulasikan Interaksi
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 20; $i++) {
            $siswa = \App\Models\User::create([
                'name' => 'Siswa Demo ' . $i,
                'email' => 'siswa.demo' . $i . '@lms.com',
                'password' => 'password',
                'role' => 'murid',
                'nis' => '99990' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'active',
            ]);

            // Assign ke Kelas
            \Illuminate\Support\Facades\DB::table('kelas_siswa')->insert([
                'kelas_id' => $kelas->id,
                'siswa_id' => $siswa->id,
                'joined_at' => now(),
            ]);

            // Simulasi Baca Materi
            \Illuminate\Support\Facades\DB::table('materi_siswa')->insert([
                'materi_id' => $materi->id,
                'siswa_id' => $siswa->id,
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simulasi Kumpul Tugas
            $nilai_tugas = rand(70, 100);
            \Illuminate\Support\Facades\DB::table('pengumpulan_tugas')->insert([
                'tugas_id' => $tugas->id,
                'siswa_id' => $siswa->id,
                'file_path' => 'demo/jawaban_tugas_' . $i . '.pdf',
                'status' => 'tepat_waktu',
                'nilai' => $nilai_tugas,
                'feedback' => 'Bagus sekali, nilai kamu ' . $nilai_tugas,
                'dikumpulkan_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Masukkan Nilai Tugas
            \Illuminate\Support\Facades\DB::table('nilai')->insert([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'nilaiable_type' => \App\Models\Tugas::class,
                'nilaiable_id' => $tugas->id,
                'nilai' => $nilai_tugas,
                'catatan' => 'Bagus sekali, nilai kamu ' . $nilai_tugas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
