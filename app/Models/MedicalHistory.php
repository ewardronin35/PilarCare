<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
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
        'medical_condition',
        'medicines',
        'health_documents',
        'profile_picture',
        'is_approved',
        'record_date',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'record_date' => 'datetime:Y-m-d H:i:s',
        'medicines' => 'array',
        'health_documents' => 'array',
        'is_approved' => 'boolean',
    ];

    // Relationship to the current medical record
    public function medicalRecord()
    {
        return $this->belongsTo(\App\Models\MedicalRecord::class, 'medical_record_id', 'id_number');
    }
    
}
