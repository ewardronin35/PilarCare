<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_year',
        'health_examination_picture',
        'xray_picture',
        'lab_result_picture',
        'is_approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_number');
    }
 
public function information()
{
    return $this->hasOne(Information::class, 'id_number', 'id_number');
}

}
