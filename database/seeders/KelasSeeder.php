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

        if ($guru1) {
            $kelas1 = Kelas::firstOrCreate(
                ['kode_kelas' => 'MTK-001'],
                [
                    'nama_kelas'     => 'X IPA 1',
                    'mata_pelajaran' => 'Matematika',
                    'guru_id'        => $guru1->id,
                    'deskripsi'      => 'Kelas Matematika untuk siswa X IPA 1',
                    'aktif'          => true,
                ]
            );
            foreach ($siswaList as $siswa) {
                $kelas1->siswa()->syncWithoutDetaching([$siswa->id]);
            }
        }

        if ($guru2) {
            $kelas2 = Kelas::firstOrCreate(
                ['kode_kelas' => 'BIN-001'],
                [
                    'nama_kelas'     => 'X IPA 2',
                    'mata_pelajaran' => 'Bahasa Indonesia',
                    'guru_id'        => $guru2->id,
                    'deskripsi'      => 'Kelas Bahasa Indonesia untuk siswa X IPA 2',
                    'aktif'          => true,
                ]
            );
            foreach ($siswaList as $siswa) {
                $kelas2->siswa()->syncWithoutDetaching([$siswa->id]);
            }
        }

        if ($guru_baru) {
            $kelas3 = Kelas::firstOrCreate(
                ['kode_kelas' => 'SJH-001'],
                [
                    'nama_kelas'     => 'X IPS 1',
                    'mata_pelajaran' => 'Sejarah',
                    'guru_id'        => $guru_baru->id,
                    'deskripsi'      => 'Kelas Sejarah untuk siswa X IPS 1',
                    'aktif'          => true,
                ]
            );
            foreach ($siswaList as $siswa) {
                $kelas3->siswa()->syncWithoutDetaching([$siswa->id]);
            }
        }
    }
}