<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'specialization', // Adjusted attribute for doctors
        'approved',
    ];

    protected $appends = ['full_name'];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'id');
    }
    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class);
    }
    public function getFullNameAttribute()
    {
        return "Dr. {$this->first_name} {$this->last_name}";

    }
    /**
     * Relationship to Medical Records
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
    
}
