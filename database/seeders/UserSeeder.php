<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin (akses penuh)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => 'superadmin123',
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        // Admin 1
        User::create([
            'name' => 'Admin Satu',
            'email' => 'admin1@admin.com',
            'password' => 'admin123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Admin 2
        User::create([
            'name' => 'Admin Dua',
            'email' => 'admin2@admin.com',
            'password' => 'admin123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Admin 3
        User::create([
            'name' => 'Admin Tiga',
            'email' => 'admin3@admin.com',
            'password' => 'admin123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Guru
        User::create([
            'name' => 'Still Goodman',
            'email' => 'stillgoodman466@gmail.com',
            'password' => 'goodman46',
            'role' => 'guru',
            'nrg' => '1122334455',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'guru1@lms.com',
            'password' => 'password',
            'role' => 'guru',
            'nrg' => '198501012010011001',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'guru2@lms.com',
            'password' => 'password',
            'role' => 'guru',
            'nrg' => '198701012010012002',
            'status' => 'active',
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
                'status' => 'active',
            ]);
        }
    }
}