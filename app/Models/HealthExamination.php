<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'health_examination_picture',
        'xray_picture',
        'lab_result_picture',
        'is_approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
