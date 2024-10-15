<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'Student_ID',
        'approved' // Adjust this field name as needed
    ];

    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class, 'id_number', 'id_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
    public function students()
{
    return $this->hasMany(Student::class, 'id_number', 'id_number');
}

}

