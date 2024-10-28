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
        'student_id',
        'approved',
    ];

    protected $appends = ['guardian_relationship'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number'); // Parent User
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id_number'); // Student User
    }

    public function information()
    {
        return $this->hasOne(Information::class, 'id_number', 'student_id');
    }
    
    public function getGuardianRelationshipAttribute()
    {
        return $this->information ? $this->information->guardian_relationship : 'Not Specified';
    }
}
