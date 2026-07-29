<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan nama fiktif ala Indonesia
        $kelasIds = Kelas::pluck('id')->toArray();

        if (empty($kelasIds)) {
            $this->command->info('Belum ada kelas di database! Silakan buat kelas terlebih dahulu melalui portal guru.');
            return;
        }

        $siswaCount = 30; // Jumlah siswa fiktif yang ingin dibuat
        $this->command->info("Membuat {$siswaCount} data siswa...");

        for ($i = 0; $i < $siswaCount; $i++) {
            // 1. Buat User Siswa
            $siswa = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => 'password', // Password default: password
                'role' => 'murid',
                'nis' => $faker->unique()->numerify('##########'), // 10 digit NIS acak
                'status' => 'active',
            ]);

            // 2. Assign ke Kelas secara acak
            // Setiap siswa bisa masuk ke 1 sampai 2 kelas secara acak
            $assignedKelasCount = rand(1, 2);
            $assignedKelasIds = (array) array_rand(array_flip($kelasIds), $assignedKelasCount);

            foreach ($assignedKelasIds as $kelasId) {
                DB::table('kelas_siswa')->insert([
                    'kelas_id' => $kelasId,
                    'siswa_id' => $siswa->id,
                    'joined_at' => now(),
                ]);
            }
        }

        $this->command->info('Berhasil membuat data siswa dan memasukkan mereka ke kelas!');
    }
}
