<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the user associated with the login log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
