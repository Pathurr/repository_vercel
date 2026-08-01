<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas'; // tambahkan ini
    protected $fillable = [
        'guru_id',
        'kelas_id',
        'judul',
        'deskripsi',
        'file_path',
        'original_file_name',
        'deadline',
        'nilai_maksimal',
        'status',
        'scheduled_at',
        'format_pengumpulan',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'format_pengumpulan' => 'array'
    ];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
    public function pengumpulan() { return $this->hasMany(PengumpulanTugas::class); }
    public function nilai_siswa() { return $this->morphMany(Nilai::class, 'nilaiable'); }

    protected static function booted()
    {
        static::deleting(function ($tugas) {
            // Delete file attachments
            if ($tugas->file_path && \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->exists($tugas->file_path)) {
                \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($tugas->file_path);
            } elseif ($tugas->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($tugas->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tugas->file_path);
            }

            // Explicitly delete related pengumpulan to trigger their deleting events
            $tugas->pengumpulan()->each(function ($pengumpulan) {
                $pengumpulan->delete();
            });

            // Delete polymorphic relation (Nilai)
            $tugas->nilai_siswa()->delete();
        });
    }
}