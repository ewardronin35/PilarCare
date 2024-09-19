<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',  // Use id_number instead of user_id
        'school_year',
        'health_examination_picture',
        'xray_picture',
        'lab_result_picture',
        'is_approved',
    ];

    // Define the relationship using id_number instead of user_id
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number'); // Reference by id_number
    }

    // Define the relationship with the Information model using id_number
    public function information()
    {
        return $this->hasOne(Information::class, 'id_number', 'id_number');
    }
}
