<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JawabanKuis extends Model
{
    protected $table = 'jawaban_kuis'; // tambahkan ini
    protected $fillable = ['kuis_id', 'siswa_id', 'soal_id', 'jawaban', 'benar'];

    public function kuis() { return $this->belongsTo(Kuis::class); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
    public function soal() { return $this->belongsTo(SoalKuis::class, 'soal_id'); }
}