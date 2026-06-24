<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\User;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $guru1 = User::where('email', 'guru1@lms.com')->first();
        $guru2 = User::where('email', 'guru2@lms.com')->first();
        $siswaList = User::where('role', 'siswa')->get();

        $kelas1 = Kelas::create([
            'nama_kelas'     => 'X IPA 1',
            'mata_pelajaran' => 'Matematika',
            'guru_id'        => $guru1->id,
            'kode_kelas'     => 'MTK-001',
            'deskripsi'      => 'Kelas Matematika untuk siswa X IPA 1',
            'aktif'          => true,
        ]);

        $kelas2 = Kelas::create([
            'nama_kelas'     => 'X IPA 2',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'guru_id'        => $guru2->id,
            'kode_kelas'     => 'BIN-001',
            'deskripsi'      => 'Kelas Bahasa Indonesia untuk siswa X IPA 2',
            'aktif'          => true,
        ]);

        // Daftarkan semua siswa ke kedua kelas
        foreach ($siswaList as $siswa) {
            $kelas1->siswa()->attach($siswa->id);
            $kelas2->siswa()->attach($siswa->id);
        }
    }
}