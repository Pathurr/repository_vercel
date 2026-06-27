<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriSiswa extends Model
{
    protected $table = 'materi_siswa';
    protected $fillable = ['materi_id', 'siswa_id', 'read_at'];

    public function materi() { return $this->belongsTo(Materi::class); }
    public function siswa() { return $this->belongsTo(User::class, 'siswa_id'); }
}

