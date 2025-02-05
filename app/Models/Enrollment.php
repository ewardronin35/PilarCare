<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'semester', 'school_year', 'is_enrolled'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id_number');
    }
}
