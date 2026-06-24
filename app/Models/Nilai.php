<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai'; // tambahkan ini
    protected $fillable = ['siswa_id', 'kelas_id', 'nilaiable_type', 'nilaiable_id', 'nilai', 'catatan'];

    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function nilaiable() { return $this->morphTo(); }
}