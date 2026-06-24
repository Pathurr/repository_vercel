<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis'; // tambahkan ini
    protected $fillable = ['kelas_id', 'guru_id', 'judul', 'deskripsi', 'durasi_menit'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
    public function soal() { return $this->hasMany(SoalKuis::class); }
    public function jawaban() { return $this->hasMany(JawabanKuis::class); }
}