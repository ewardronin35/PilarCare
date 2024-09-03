<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Include user_id in fillable array
        'name',
        'birthdate',
        'age',
        'address',
        'personal_contact_number',
        'emergency_contact_number',
        'father_name',
        'mother_name',
        'past_illness',
        'chronic_conditions',
        'surgical_history',
        'family_medical_history',
        'allergies',
        'medicines',
        'profile_picture',
    ];

    protected $casts = [
        'medicines' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
