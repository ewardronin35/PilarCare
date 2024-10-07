<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalRecord extends Model
{
    use HasFactory;

    protected $fillable = ['dental_record_id', 'id_number', 'user_type', 'patient_name', 'grade_section'];

    public $timestamps = true;


    // Relationship to the Teeth model
    public function teeth()
    {
        return $this->hasMany(Teeth::class, 'dental_record_id', 'dental_record_id');
    }
    public function user()
    {
        return $this->morphTo(null, 'user_type', 'id_number', 'id_number');
    }
    

    public function dentalExaminations()
    {
        return $this->hasMany(DentalExamination::class, 'dental_record_id', 'dental_record_id');
    }
}
