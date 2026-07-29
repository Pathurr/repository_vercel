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
        $guru_baru = User::where('email', 'stillgoodman466@gmail.com')->first();
        $siswaList = User::where('role', 'murid')->get();

        $kelas1 = Kelas::create([
            'nama_kelas'     => 'X IPA 1',
            'mata_pelajaran' => 'Matematika',
            'guru_id'        => $guru1->id,
            'kode_kelas'     => 'MTK-001',
            'deskripsi'      => 'Kelas Matematika untuk siswa X IPA 1',
            'aktif'          => 'true',
        ]);

        $kelas2 = Kelas::create([
            'nama_kelas'     => 'X IPA 2',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'guru_id'        => $guru2->id,
            'kode_kelas'     => 'BIN-001',
            'deskripsi'      => 'Kelas Bahasa Indonesia untuk siswa X IPA 2',
            'aktif'          => 'true',
        ]);

        $kelas3 = Kelas::create([
            'nama_kelas'     => 'X IPS 1',
            'mata_pelajaran' => 'Sejarah',
            'guru_id'        => $guru_baru->id,
            'kode_kelas'     => 'SJH-001',
            'deskripsi'      => 'Kelas Sejarah untuk siswa X IPS 1',
            'aktif'          => 'true',
        ]);

        // Daftarkan semua siswa ke semua kelas
        foreach ($siswaList as $siswa) {
            $kelas1->siswa()->attach($siswa->id);
            $kelas2->siswa()->attach($siswa->id);
            $kelas3->siswa()->attach($siswa->id);
        }
    }
}