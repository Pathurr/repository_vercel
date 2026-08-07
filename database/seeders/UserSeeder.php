<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Utama',
                'password' => 'password',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Guru
        User::firstOrCreate(
            ['email' => 'stillgoodman466@gmail.com'],
            [
                'name' => 'Still Goodman',
                'password' => 'goodman46',
                'role' => 'guru',
                'nrg' => '1122334455',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'guru1@lms.com'],
            [
                'name' => 'Budi Santoso',
                'password' => 'password',
                'role' => 'guru',
                'nrg' => '198501012010011001',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'guru2@lms.com'],
            [
                'name' => 'Siti Rahayu',
                'password' => 'password',
                'role' => 'guru',
                'nrg' => '198701012010012002',
                'status' => 'active',
            ]
        );

        // Siswa
        $siswa = [
            ['name' => 'Alfathurahman',  'email' => 'alfathurahman28@gmail.com', 'nis' => '2024000', 'password' => 'dompak28jb'],
            ['name' => 'Andi Pratama',   'email' => 'siswa1@lms.com', 'nis' => '2024001', 'password' => 'password'],
            ['name' => 'Dewi Lestari',   'email' => 'siswa2@lms.com', 'nis' => '2024002', 'password' => 'password'],
            ['name' => 'Rizky Ramadan',  'email' => 'siswa3@lms.com', 'nis' => '2024003', 'password' => 'password'],
            ['name' => 'Aulia Putri',    'email' => 'siswa4@lms.com', 'nis' => '2024004', 'password' => 'password'],
            ['name' => 'Fajar Nugroho',  'email' => 'siswa5@lms.com', 'nis' => '2024005', 'password' => 'password'],
        ];

        foreach ($siswa as $s) {
            User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'password' => $s['password'],
                    'role' => 'murid',
                    'nis' => $s['nis'],
                    'status' => 'active',
                ]
            );
        }
    }
}