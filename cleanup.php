<?php

use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Materi;
use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\Ujian;
use App\Models\SoalUjian;
use Illuminate\Support\Facades\Storage;

echo "Memulai pembersihan data Kelas Simulasi...\n";

// Hapus Kelas Simulasi (Ini akan memicu event deleting yang menghapus materi, tugas, kuis, ujian, dan file-file di dalamnya)
$kelasSimulasi = Kelas::where('nama_kelas', 'like', '%Simulasi%')->get();
foreach ($kelasSimulasi as $kelas) {
    echo "Menghapus kelas: " . $kelas->nama_kelas . "\n";
    // Menggunakan delete() secara eksplisit pada setiap instansi untuk memicu event model
    $kelas->delete();
}

echo "Penghapusan Kelas Simulasi selesai.\n\n";

echo "Mencari file yatim (orphaned) yang tidak ada di database...\n";

$disk = env('FILESYSTEM_DISK', 'public');
$storage = Storage::disk($disk);

// Folder yang akan dicek
$folders = ['materi', 'tugas_attachments', 'pengumpulan_tugas', 'soal_kuis', 'soal_ujian'];
$deletedFilesCount = 0;

foreach ($folders as $folder) {
    if (!$storage->exists($folder)) {
        continue;
    }
    
    $files = $storage->files($folder);
    foreach ($files as $file) {
        $inUse = false;
        
        switch ($folder) {
            case 'materi':
                $inUse = Materi::where('file_path', $file)->exists();
                break;
            case 'tugas_attachments':
                $inUse = Tugas::where('file_path', $file)->exists();
                break;
            case 'pengumpulan_tugas':
                $inUse = PengumpulanTugas::where('file_path', $file)->exists();
                break;
            case 'soal_kuis':
                $inUse = SoalKuis::where('file_path', $file)->exists();
                break;
            case 'soal_ujian':
                $inUse = SoalUjian::where('file_path', $file)->exists();
                break;
        }
        
        if (!$inUse) {
            echo "Menghapus file tak terpakai: " . $file . "\n";
            $storage->delete($file);
            $deletedFilesCount++;
        }
    }
}

echo "Selesai. $deletedFilesCount file yatim berhasil dihapus.\n";
