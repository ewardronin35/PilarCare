<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date_of_examination', 'grade_section', 'lastname', 'firstname', 
        'birthdate', 'age', 'carries_free', 'poor_oral_hygiene', 'gum_infection', 'restorable_caries',
        'other_condition', 'personal_attention', 'oral_prophylaxis', 'fluoride_application',
        'gum_treatment', 'ortho_consultation', 'sealant_tooth', 'filling_tooth', 'extraction_tooth',
        'endodontic_tooth', 'radiograph_tooth', 'prosthesis_tooth', 'medical_clearance', 'other_recommendation'
    ];
    

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
