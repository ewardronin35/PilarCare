<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'scheduled_time',
        'role',  // Add this line

    ];

    protected $dates = ['scheduled_time'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
}
