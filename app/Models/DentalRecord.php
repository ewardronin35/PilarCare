<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalRecord extends Model
{
    use HasFactory;

    protected $fillable = ['id_number', 'user_type', 'patient_name', 'grade_section'];

    public $timestamps = false;


    // Relationship to the Teeth model
    public function teeth()
    {
        return $this->hasMany(Teeth::class, 'dental_record_id');
    }

    public function user()
    {
        switch ($this->user_type) {
            case 'student':
                return $this->belongsTo(Student::class, 'id_number', 'id_number');
            case 'teacher':
                return $this->belongsTo(Teacher::class, 'id_number', 'id_number');
            case 'staff':
                return $this->belongsTo(Staff::class, 'id_number', 'id_number');
            default:
                return null;
        }
    }
}
