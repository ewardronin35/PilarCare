<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Ensure User model is correctly imported


class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'scheduled_time',
        'role',  // Add this line
        'is_opened', // Ensure this field is fillable

    ];

    protected $dates = ['scheduled_time'];
    protected $casts = [
        'is_opened' => 'boolean', // Add this line
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_number');
    }
}
