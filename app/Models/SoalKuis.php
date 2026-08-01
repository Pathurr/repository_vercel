<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SoalKuis extends Model
{
    protected $table = 'soal_kuis'; // tambahkan ini
    protected $fillable = [
        'kuis_id', 'pertanyaan', 'file_path', 'original_file_name', 'tipe', 'pilihan', 'jawaban_benar', 'bobot', 'urutan'
    ];

    protected $casts = ['pilihan' => 'array'];

    public function kuis() { return $this->belongsTo(Kuis::class); }

    protected static function booted()
    {
        static::deleting(function ($soal) {
            if ($soal->file_path && \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->exists($soal->file_path)) {
                \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($soal->file_path);
            } elseif ($soal->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($soal->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($soal->file_path);
            }
        });
    }
}