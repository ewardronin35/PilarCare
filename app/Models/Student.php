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
        'approved' // Adjust this field name as needed
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
        return $this->hasMany(ParentModel::class, 'student_id', 'id_number');
    }
}
