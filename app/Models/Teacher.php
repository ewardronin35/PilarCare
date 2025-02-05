<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teacher'; // Ensure this matches your actual table name

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'bed_or_hed',
        'course',
        'approved',
        'role',
        'father_name',
        'mother_name',
        'contact_number',
        'address',
        'emergency_contact',
        'age',
        'profile_picture',
        'birthdate',
    ];

    /**
     * Relationship with Student based on course.
     */
    protected $appends = ['profile_picture_url'];

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture 
            ? asset('storage/' . $this->profile_picture) 
            : asset('images/default-profile.png');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'course', 'course');
    }

    /**
     * Relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }

    /**
     * Scope to get program heads.
     */
    public function scopeProgramHeads($query)
    {
        return $query->where('role', 'program_head');
    }

    /**
     * Check if a program head exists for a given course.
     */
    public static function programHeadExists($course, $excludeId = null)
    {
        $query = self::where('course', $course)->where('role', 'program_head');
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }
}
