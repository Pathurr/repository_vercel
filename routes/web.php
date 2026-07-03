<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\TugasController;
use App\Http\Controllers\Guru\KuisController;
use App\Http\Controllers\Guru\UjianController;
use App\Http\Controllers\Guru\KelasController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\MonitoringController;
use App\Http\Controllers\Guru\PenilaianController;
use App\Http\Controllers\Siswa\MateriController as SiswaMateriController;
use App\Http\Controllers\Siswa\TugasController as SiswaTugasController;
use App\Http\Controllers\Siswa\UjianController as SiswaUjianController;
use App\Http\Controllers\Siswa\KuisController as SiswaKuisController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - LMS SMK Mandalahayu 1 Bekasi
|--------------------------------------------------------------------------
*/

// Home: halaman profil web sekolah (publik, tidak perlu login)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─── Auth Routes (Laravel Breeze / Manual) ────────────────────
require __DIR__.'/auth.php';

// ─── DEV SHORTCUTS: Akses Langsung Tanpa Login ────────────────
// ⚠️ HAPUS bagian ini sebelum deploy ke production!
if (app()->environment('local')) {

    // Masuk sebagai Guru (auto-login user pertama atau buat user dummy)
    Route::get('/dev/guru', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $user = User::where('role', 'guru')->first() ?? User::create([
            'name'     => 'Bpk. Ahmad Suherman',
            'email'    => 'guru@smkmandalahayu.sch.id',
            'password' => bcrypt('password'),
            'role'     => 'guru',
            'status'   => 'active'
        ]);
        Auth::login($user);
        return redirect()->route('guru.dashboard');
    })->name('dev.guru');

    // Masuk sebagai Siswa (auto-login user kedua atau buat user dummy)
    Route::get('/dev/siswa', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $user = User::where('role', 'murid')->first() ?? User::create([
            'name'     => 'Budi Pratama',
            'email'    => 'siswa@smkmandalahayu.sch.id',
            'password' => bcrypt('password'),
            'role'     => 'murid',
            'status'   => 'active'
        ]);
        
        // Auto-enroll to all existing classes for dev convenience
        $allKelasIds = \App\Models\Kelas::pluck('id');
        $user->kelas()->syncWithoutDetaching($allKelasIds);
        
        Auth::login($user);
        return redirect()->route('siswa.dashboard');
    })->name('dev.siswa');

    // Masuk sebagai Admin (buat user dummy admin)
    Route::get('/dev/admin', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $user = User::firstOrCreate(
            ['email' => 'admin@smkmandalahayu.sch.id'],
            [
                'name'     => 'Administrator',
                'password' => bcrypt('password'),
                'role'     => 'admin',
                'status'   => 'active'
            ]
        );
        Auth::login($user);
        return redirect()->route('admin.dashboard'); // using default dashboard for admin
    })->name('dev.admin');

    // Halaman index semua shortcut
    Route::get('/dev', function () {
        return response()->view('dev-index');
    })->name('dev.index');

    // Cek langsung halaman verifikasi email tanpa login
    Route::get('/dev/verify-email', function () {
        return view('auth.verify-email');
    })->name('dev.verify-email');
}

// ─── Guru Routes (Protected) ──────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');

    // Kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
    Route::get('/kelas/detail', [KelasController::class, 'show'])->name('kelas.detail');
    Route::get('/kelas/buat', fn() => view('guru.buat-kelas'))->name('kelas.buat');
    Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');

   // Materi
    Route::get('/materi', [MateriController::class, 'index'])->name('materi');
    Route::get('/materi/tambah', [MateriController::class, 'create'])->name('materi.tambah');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('materi.store');
    Route::delete('/materi/{id}', [MateriController::class, 'destroy'])->name('materi.destroy');

    // Tugas
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas');
    Route::get('/tugas/buat', [TugasController::class, 'create'])->name('tugas.buat');
    Route::post('/tugas/store', [TugasController::class, 'store'])->name('tugas.store');
    Route::put('/tugas/{id}', [TugasController::class, 'update'])->name('tugas.update');
    Route::delete('/tugas/{id}', [TugasController::class, 'destroy'])->name('tugas.destroy');

    // Kuis
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis');
    Route::get('/kuis/buat', [KuisController::class, 'create'])->name('kuis.buat');
    Route::post('/kuis/store', [KuisController::class, 'store'])->name('kuis.store');
    Route::put('/kuis/{id}', [KuisController::class, 'update'])->name('kuis.update');
    Route::delete('/kuis/{id}', [KuisController::class, 'destroy'])->name('kuis.destroy');

    // Ujian
    Route::get('/ujian', [UjianController::class, 'index'])->name('ujian');
    Route::get('/ujian/buat', [UjianController::class, 'create'])->name('ujian.buat');
    Route::post('/ujian/store', [UjianController::class, 'store'])->name('ujian.store');
    Route::put('/ujian/{id}', [UjianController::class, 'update'])->name('ujian.update');
    Route::delete('/ujian/{id}', [UjianController::class, 'destroy'])->name('ujian.destroy');

    // Nilai & Rekap
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai');
    Route::get('/nilai/rekap', [NilaiController::class, 'rekap'])->name('nilai.rekap');

    // Monitor & Penilaian
    Route::get('/monitor', [MonitoringController::class, 'index'])->name('monitor');
    Route::get('/monitor/tugas', [MonitoringController::class, 'index'])->name('monitor.tugas');
    Route::get('/penilaian/tugas', [PenilaianController::class, 'tugas'])->name('penilaian.tugas');
    Route::post('/penilaian/tugas/{id}', [PenilaianController::class, 'storeTugas'])->name('penilaian.tugas.store');
    Route::get('/penilaian/kuis', [PenilaianController::class, 'kuis'])->name('penilaian.kuis');
    Route::post('/penilaian/kuis/{kuis_id}/{siswa_id}', [PenilaianController::class, 'storeKuis'])->name('penilaian.kuis.store');
    Route::get('/penilaian/ujian', [PenilaianController::class, 'ujian'])->name('penilaian.ujian');
    Route::post('/penilaian/ujian/{ujian_id}/{siswa_id}', [PenilaianController::class, 'storeUjian'])->name('penilaian.ujian.store');

});

// ─── Siswa Routes (Protected) ─────────────────────────────────
Route::prefix('siswa')->name('siswa.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // Mata Pelajaran
    Route::get('/materi', [SiswaMateriController::class, 'index'])->name('materi');
    Route::get('/materi/lihat/{id}', [SiswaMateriController::class, 'show'])->name('lihat-materi');
    Route::post('/materi/{id}/read', [SiswaMateriController::class, 'markAsRead'])->name('materi.read');

    // Tugas
    Route::get('/tugas', [SiswaTugasController::class, 'index'])->name('tugas');
    Route::get('/tugas/{id}', [SiswaTugasController::class, 'show'])->name('pengerjaan-tugas');
    Route::post('/tugas/{id}/kumpul', [SiswaTugasController::class, 'store'])->name('kumpul-tugas');

    // Ujian
    Route::get('/ujian', [SiswaUjianController::class, 'index'])->name('ujian');
    Route::get('/ujian/{id}', [SiswaUjianController::class, 'show'])->name('pengerjaan-ujian');
    Route::post('/ujian/{id}', [SiswaUjianController::class, 'store'])->name('kumpul-ujian');
    // Route::get('/ujian', fn() => view('siswa.ujian'))->name('ujian');
    // Route::get('/ujian/kerjakan', fn() => view('siswa.pengerjaan-ujian'))->name('pengerjaan-ujian');
    // Route::get('/kuis/kerjakan', fn() => view('siswa.pengerjaan-kuis'))->name('pengerjaan-kuis');

    // Kuis
    Route::get('/kuis', [SiswaKuisController::class, 'index'])->name('kuis');
    Route::get('/kuis/{id}', [SiswaKuisController::class, 'show'])->name('pengerjaan-kuis');
    Route::post('/kuis/{id}', [SiswaKuisController::class, 'store'])->name('kumpul-kuis');

    // Mata Pelajaran (Kelas)
    Route::get('/mapel', [App\Http\Controllers\Siswa\KelasController::class, 'index'])->name('mapel');
    Route::post('/mapel/join', [App\Http\Controllers\Siswa\KelasController::class, 'join'])->name('mapel.join');
    Route::get('/mapel/{id}', [App\Http\Controllers\Siswa\KelasController::class, 'show'])->name('mapel.detail');
    Route::get('/nilai', [App\Http\Controllers\Siswa\NilaiController::class, 'index'])->name('nilai');
});

// ─── Admin Routes (Protected) ─────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Akun
    Route::get('/akun', [App\Http\Controllers\Admin\AkunController::class, 'index'])->name('akun');
    Route::post('/akun/status', [App\Http\Controllers\Admin\AkunController::class, 'updateStatus'])->name('akun.status');
    Route::post('/akun/bulk', [App\Http\Controllers\Admin\AkunController::class, 'bulkUpdate'])->name('akun.bulk');
    
    // Laporan Aktivitas
    Route::get('/aktivitas', [App\Http\Controllers\Admin\AktivitasController::class, 'index'])->name('aktivitas');
});
