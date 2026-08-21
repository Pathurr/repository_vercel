<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas'; // tambahkan ini
    protected $fillable = [
        'nama_kelas', 'mata_pelajaran', 'guru_id', 'kode_kelas', 'deskripsi', 'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(User::class, 'kelas_siswa', 'kelas_id', 'siswa_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }

    protected static function booted()
    {
        static::deleting(function ($kelas) {
            $kelas->materi()->each(function ($materi) {
                $materi->delete();
            });
            $kelas->tugas()->each(function ($tugas) {
                $tugas->delete();
            });
            $kelas->kuis()->each(function ($kuis) {
                $kuis->delete();
            });
            $kelas->ujian()->each(function ($ujian) {
                $ujian->delete();
            });
        });
    }
}