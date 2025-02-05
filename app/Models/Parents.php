<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'approved',
    ];

    protected $appends = ['guardian_relationship'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number'); // Parent User
    }
    public function student()
    {
        return $this->hasMany(Student::class, 'parent_id', 'id_number');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id', 'id_number');
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id', 'id_number');
    }
    public function information()
    {
        return $this->hasMany(Information::class, 'id_number', 'student_id');
    }
    
    public function getGuardianRelationshipAttribute()
    {
        if ($this->information->isEmpty()) {
            return 'Not Specified';
        }
    
        // Concatenate all guardian relationships
        return $this->information->pluck('guardian_relationship')->filter()->unique()->join(', ');
    }
}    
