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
}