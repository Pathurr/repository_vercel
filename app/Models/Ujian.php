<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian'; // tambahkan ini
    protected $fillable = [
        'kelas_id', 'guru_id', 'judul', 'deskripsi', 'mulai_at', 'selesai_at', 'durasi_menit', 'batas_percobaan', 'acak_soal', 'nilai_maksimal'
    ];

    protected $casts = ['mulai_at' => 'datetime', 'selesai_at' => 'datetime', 'acak_soal' => 'boolean'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
    public function soal() { return $this->hasMany(SoalUjian::class); }
    public function jawaban() { return $this->hasMany(JawabanUjian::class); }
    public function nilai_siswa() { return $this->morphMany(Nilai::class, 'nilaiable'); }

    protected static function booted()
    {
        static::deleting(function ($ujian) {
            $ujian->soal()->each(function ($soal) {
                $soal->delete();
            });
            $ujian->nilai_siswa()->delete();
        });
    }
}