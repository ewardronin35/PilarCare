<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Enrollment;
use App\Models\Staff;
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
       'id_number',
        'first_name',
        'last_name',
        'gender',
        'grade_or_course',
        'section',
        'education_level',
        'approved',
        'enrollment_status',
        'father_name',
        'mother_name',
        'contact_number',
        'address',
        'emergency_contact',
        'birthdate',
        'profile_picture',
        'age',
    ];
 
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'course', 'course');
    }
    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class, 'id_number', 'id_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }


 

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_number', 'id_number');
    }

    public function healthExaminations()
    {
        return $this->hasMany(HealthExamination::class, 'id_number', 'id_number');
    }
    public function enrollments()
{
    return $this->hasMany(Enrollment::class, 'student_id', 'id_number');
}
public function getFullNameAttribute()
{
    return "{$this->first_name} {$this->last_name}";
}




}
