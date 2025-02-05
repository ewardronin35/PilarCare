<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'pain_assessment',
        'sickness_description',
        'role',
        'medicine_given',
        'go_home',
        'report_url', // Ensure this field exists in your migrations
        'grade_course',
        'section',
    ];

    public $timestamps = true; // Ensure timestamps are enabled

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id'); // Specify foreign and local keys
    }
}
