<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number', // Replace 'user_id' with 'id_number'
        'date_of_examination', 
        'grade_section', 
        'lastname', 
        'firstname', 
        'birthdate', 
        'age', 
        'dentist_name',
        'findings',
        'carries_free', 
        'poor_oral_hygiene', 
        'gum_infection', 
        'restorable_caries',
        'other_condition', 
        'personal_attention', 
        'oral_prophylaxis', 
        'fluoride_application',
        'gum_treatment', 
        'ortho_consultation', 
        'sealant_tooth', 
        'filling_tooth', 
        'extraction_tooth',
        'endodontic_tooth', 
        'radiograph_tooth', 
        'prosthesis_tooth', 
        'medical_clearance', 
        'other_recommendation'
    ];
    

    // Relationship with User based on id_number
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number'); // Link by id_number instead of user_id
    }
    public function appointments()
{
    return $this->hasMany(Appointment::class);
}
    public function notifications()
{
    return $this->hasMany(Notification::class);
    }
    public function DentalRecord()
{
    return $this->belongsTo(DentalRecord::class, 'dental_record_id', 'id');
    }
}
    