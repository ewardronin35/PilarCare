<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'grade_or_course',
        'approved',
    ];

    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class, 'id_number', 'id_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parents', 'student_id', 'id_number');
    }
    

    public function information()
    {
        return $this->hasOne(Information::class, 'id_number', 'id_number');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_number', 'id_number');
    }

    public function healthExaminations()
    {
        return $this->hasMany(HealthExamination::class, 'id_number', 'id_number');
    }
}
