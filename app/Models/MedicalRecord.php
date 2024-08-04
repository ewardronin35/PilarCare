<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'birthdate', 'age', 'address', 'father_name', 'mother_name', 'medical_illness', 'allergies', 
        'pediatrician', 'medicines', 'physical_examination', 'consent_signature', 'consent_date', 'contact_no', 'picture_path'
    ];

    protected $casts = [
        'medicines' => 'array',
        'physical_examination' => 'array',
    ];
}
