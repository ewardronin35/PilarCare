<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MedicalRecord extends Model
{

    
    use HasFactory;
    use SoftDeletes;

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
        'birthdate' => 'date', // Casts to Carbon instance
        'record_date' => 'datetime:Y-m-d H:i:s',
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
    public function healthExaminations()
    {
        return $this->hasMany(HealthExamination::class, 'id_number', 'id_number');
    }

    /**
     * Get the doctor associated with the medical record.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_number', 'id_number');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'id_number', 'id_number');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_number', 'id_number');
    }
    public function previousRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_number', 'id_number')
                    ->where('version', '<', $this->version)
                    ->orderBy('version', 'desc');
    }
    public function histories()
    {
        return $this->hasMany(MedicalHistory::class, 'medical_record_id', 'id_number');
    }
    
    
}


