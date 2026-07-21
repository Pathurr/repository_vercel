<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JawabanKuis extends Model
{
    protected $table = 'jawaban_kuis'; // tambahkan ini
    protected $fillable = ['kuis_id', 'siswa_id', 'soal_id', 'jawaban', 'benar'];
    protected $casts = [
        'benar' => 'boolean',
    ];

    public function kuis() { return $this->belongsTo(Kuis::class); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
    public function soal() { return $this->belongsTo(SoalKuis::class, 'soal_id'); }

    public function getSkorAttribute()
    {
        if (!$this->soal || $this->soal->tipe === 'essay') return 0;
        
        $correctAns = (string) $this->soal->jawaban_benar;
        if ($this->soal->tipe === 'multiple_select' || (is_string($this->jawaban) && is_array(json_decode($this->jawaban, true)))) {
            $c = json_decode($correctAns, true);
            $s = json_decode($this->jawaban, true);
            if (!is_array($s)) $s = [$this->jawaban];
            if (is_array($c) && count($c) > 0) {
                $c = array_map('strval', $c);
                $s = array_map('strval', $s);
                $correctCount = count(array_intersect($s, $c));
                return $correctCount / count($c);
            }
            return 0;
        }
        
        return ((string) $this->jawaban === $correctAns) ? 1 : 0;
    }
}