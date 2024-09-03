<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'grade_section',
        'user_id',
    ];
    public $timestamps = false;


    // Relationship to the Teeth model
    public function teeth()
    {
        return $this->hasMany(Teeth::class, 'dental_record_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
