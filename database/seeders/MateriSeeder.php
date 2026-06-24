<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Kelas;
use App\Models\User;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $kelas1 = Kelas::where('kode_kelas', 'MTK-001')->first();
        $kelas2 = Kelas::where('kode_kelas', 'BIN-001')->first();
        $guru1  = User::where('email', 'guru1@lms.com')->first();
        $guru2  = User::where('email', 'guru2@lms.com')->first();

        $materiMtk = [
            ['judul' => 'Pengenalan Aljabar', 'deskripsi' => 'Dasar-dasar aljabar dan persamaan linear'],
            ['judul' => 'Fungsi Kuadrat',     'deskripsi' => 'Memahami fungsi kuadrat dan grafiknya'],
            ['judul' => 'Trigonometri Dasar', 'deskripsi' => 'Pengenalan sin, cos, tan'],
        ];

        foreach ($materiMtk as $m) {
            Materi::create([
                'kelas_id'  => $kelas1->id,
                'guru_id'   => $guru1->id,
                'judul'     => $m['judul'],
                'deskripsi' => $m['deskripsi'],
            ]);
        }

        $materiBin = [
            ['judul' => 'Teks Narasi',     'deskripsi' => 'Memahami struktur teks narasi'],
            ['judul' => 'Teks Eksposisi',  'deskripsi' => 'Cara menulis teks eksposisi yang baik'],
        ];

        foreach ($materiBin as $m) {
            Materi::create([
                'kelas_id'  => $kelas2->id,
                'guru_id'   => $guru2->id,
                'judul'     => $m['judul'],
                'deskripsi' => $m['deskripsi'],
            ]);
        }
    }
}