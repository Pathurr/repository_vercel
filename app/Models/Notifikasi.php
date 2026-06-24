<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi'; // tambahkan ini
    protected $fillable = ['user_id', 'judul', 'pesan', 'tipe', 'dibaca'];

    public function user() { return $this->belongsTo(User::class); }
}