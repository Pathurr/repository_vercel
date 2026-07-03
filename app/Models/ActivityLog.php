<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'email', 'role', 'ip_address', 'user_agent', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
