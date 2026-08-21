<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;

class DemoLengkapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat / Ambil 1 Guru Demo
        $guru = User::firstOrCreate(
            ['email' => 'guru.demo@lms.com'],
            [
                'name' => 'Guru Demo Lengkap',
                'password' => 'password',
                'role' => 'guru',
                'nrg' => '9988776655',
                'status' => 'active',
            ]
        );

        // 2. Buat / Ambil 1 Kelas Demo
        $kelas = Kelas::firstOrCreate(
            ['kode_kelas' => 'DEMO123'],
            [
                'guru_id' => $guru->id,
                'nama_kelas' => 'Kelas Demo Super Lengkap',
                'mata_pelajaran' => 'Simulasi LMS',
                'aktif' => true,
            ]
        );

        // 3. Buat / Ambil 1 Materi
        $materi = Materi::firstOrCreate(
            ['kelas_id' => $kelas->id, 'judul' => 'Materi Simulasi Lengkap'],
            [
                'guru_id' => $guru->id,
                'deskripsi' => 'Ini adalah materi contoh yang dibuat secara otomatis.',
                'file_path' => 'demo/materi_simulasi.pdf',
            ]
        );

        // 4. Buat / Ambil 1 Tugas
        $tugas = Tugas::firstOrCreate(
            ['kelas_id' => $kelas->id, 'judul' => 'Tugas Simulasi Lengkap'],
            [
                'guru_id' => $guru->id,
                'deskripsi' => 'Kerjakan simulasi ini dengan baik.',
                'deadline' => \Carbon\Carbon::now()->addDays(5),
                'nilai_maksimal' => 100,
            ]
        );

        // 5. Buat 20 Siswa Baru, Masukkan Kelas, dan Simulasikan Interaksi
        for ($i = 1; $i <= 20; $i++) {
            $email = 'siswa.demo' . $i . '@lms.com';
            $siswa = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Siswa Demo ' . $i,
                    'password' => 'password',
                    'role' => 'murid',
                    'nis' => '99990' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );

            // Assign ke Kelas jika belum ada
            if (!DB::table('kelas_siswa')->where('kelas_id', $kelas->id)->where('siswa_id', $siswa->id)->exists()) {
                DB::table('kelas_siswa')->insert([
                    'kelas_id' => $kelas->id,
                    'siswa_id' => $siswa->id,
                    'joined_at' => now(),
                ]);
            }

            // Simulasi Baca Materi jika belum ada
            if (!DB::table('materi_siswa')->where('materi_id', $materi->id)->where('siswa_id', $siswa->id)->exists()) {
                DB::table('materi_siswa')->insert([
                    'materi_id' => $materi->id,
                    'siswa_id' => $siswa->id,
                    'read_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Simulasi Kumpul Tugas & Nilai jika belum ada
            $nilai_tugas = rand(70, 100);
            if (!DB::table('pengumpulan_tugas')->where('tugas_id', $tugas->id)->where('siswa_id', $siswa->id)->exists()) {
                DB::table('pengumpulan_tugas')->insert([
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

                DB::table('nilai')->insert([
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
}
