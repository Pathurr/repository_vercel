<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    protected $table = 'soal_ujian'; // tambahkan ini
    protected $fillable = [
        'ujian_id', 'pertanyaan', 'file_path', 'original_file_name', 'tipe', 'pilihan', 'jawaban_benar', 'bobot', 'urutan'
    ];

    protected $casts = ['pilihan' => 'array'];

    public function ujian() { return $this->belongsTo(Ujian::class); }
}