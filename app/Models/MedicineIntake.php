<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineIntake extends Model
{
    use HasFactory;
    protected $table = 'medicine_intake'; // Explicitly specify the table name

    protected $fillable = [
        'id_number',
        'medicine_name',
        'dosage',
        'intake_time',
        'notes'
    ];

    // Relationship with MedicalRecord
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'id_number', 'id_number');
    }
}
