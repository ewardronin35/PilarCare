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
}
