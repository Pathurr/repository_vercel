<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Guru
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'guru1@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'nip' => '198501012010011001',
            'no_hp' => '081234567890',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'guru2@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'nip' => '198701012010012002',
            'no_hp' => '081234567891',
        ]);

        // Siswa
        $siswa = [
            ['name' => 'Andi Pratama',   'email' => 'siswa1@lms.com', 'nis' => '2024001'],
            ['name' => 'Dewi Lestari',   'email' => 'siswa2@lms.com', 'nis' => '2024002'],
            ['name' => 'Rizky Ramadan',  'email' => 'siswa3@lms.com', 'nis' => '2024003'],
            ['name' => 'Aulia Putri',    'email' => 'siswa4@lms.com', 'nis' => '2024004'],
            ['name' => 'Fajar Nugroho',  'email' => 'siswa5@lms.com', 'nis' => '2024005'],
        ];

        foreach ($siswa as $s) {
            User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'nis' => $s['nis'],
            ]);
        }
    }
}