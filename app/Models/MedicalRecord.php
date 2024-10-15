<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number', // Include user_id in fillable array
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
        'is_current',
        'is_approved',
        'record_date',
    ];

    protected $casts = [
        'medicines' => 'array',
        'health_documents' => 'array', // Assuming you have this field
        'is_approved' => 'boolean',
        'is_current' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
    public function medicineIntakes()
    {
        return $this->hasMany(MedicineIntake::class, 'id_number', 'id_number');
    }
    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class, 'id_number', 'id_number');
    }
    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }

    /**
     * Get the doctor associated with the medical record.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}


