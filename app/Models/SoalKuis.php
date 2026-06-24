<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SoalKuis extends Model
{
    protected $table = 'soal_kuis'; // tambahkan ini
    protected $fillable = [
        'kuis_id', 'pertanyaan', 'pilihan', 'jawaban_benar', 'bobot', 'urutan'
    ];

    protected $casts = ['pilihan' => 'array'];

    public function kuis() { return $this->belongsTo(Kuis::class); }
}