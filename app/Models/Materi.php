<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi'; // tambahkan ini
    protected $fillable = [
        'kelas_id', 'guru_id', 'judul', 'deskripsi', 'file_path', 'link_video'
    ];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
}