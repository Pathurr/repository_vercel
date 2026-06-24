<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JawabanUjian extends Model
{
    protected $table = 'jawaban_ujian'; // tambahkan ini
    protected $fillable = [
        'ujian_id', 'siswa_id', 'soal_id', 'jawaban', 'benar', 'nilai'
    ];

    public function ujian() { return $this->belongsTo(Ujian::class); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
    public function soal() { return $this->belongsTo(SoalUjian::class, 'soal_id'); }
}