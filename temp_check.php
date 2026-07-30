<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$guru = \App\Models\User::where('email', 'guru.demo@lms.com')->first();
if ($guru) {
    $kelas = \App\Models\Kelas::where('guru_id', $guru->id)->get();
    echo "Jumlah kelas: " . $kelas->count() . "\n";
    foreach($kelas as $k) {
        echo "Kelas: " . $k->nama_kelas . "\n";
    }
} else {
    echo "Guru tidak ditemukan.";
}
