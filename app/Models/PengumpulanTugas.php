<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas'; // tambahkan ini
    protected $fillable = [
        'tugas_id', 'siswa_id', 'file_path', 'catatan', 'dikumpulkan_at', 'nilai', 'feedback', 'status'
    ];

    protected $casts = ['dikumpulkan_at' => 'datetime'];

    public function tugas() { return $this->belongsTo(Tugas::class); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
}