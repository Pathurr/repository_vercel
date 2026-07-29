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
            'name' => 'Admin Utama',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        // Guru
        User::create([
            'name' => 'Still Goodman',
            'email' => 'stillgoodman466@gmail.com',
            'password' => 'goodman46',
            'role' => 'guru',
            'nrg' => '1122334455',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'guru1@lms.com',
            'password' => 'password',
            'role' => 'guru',
            'nrg' => '198501012010011001',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'guru2@lms.com',
            'password' => 'password',
            'role' => 'guru',
            'nrg' => '198701012010012002',
        ]);

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
            User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => $s['password'],
                'role' => 'murid',
                'nis' => $s['nis'],
            ]);
        }
    }
}