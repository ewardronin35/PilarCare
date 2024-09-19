<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_history';

    protected $fillable = [
        'id_number',
        'past_illness',
        'chronic_conditions',
        'surgical_history',
        'family_medical_history',
        'allergies',
        'medical_condition',
        'record_date',
        'approval_status',
        'health_documents',
    ];

    // Relationship with MedicalRecord
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_number', 'id_number');
    }
}
