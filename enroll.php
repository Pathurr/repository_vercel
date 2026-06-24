<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Kelas;

$siswas = User::where('role', 'murid')->get();
$kelas = Kelas::all();

if ($kelas->count() == 0) {
    echo "No classes found.\n";
    exit;
}

if ($siswas->count() == 0) {
    echo "No students found.\n";
    exit;
}

foreach ($siswas as $siswa) {
    $randomKelas = $kelas->random(min(2, $kelas->count()))->pluck('id')->toArray();
    $siswa->kelas()->syncWithoutDetaching($randomKelas);
}

echo "Successfully enrolled students into classes!\n";
