<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    
    protected $table = 'teacher';  // Adjust this to match your actual table name

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'bed_or_hed', // HED/BED
        'approved'
    ];
    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class, 'id_number', 'id_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
}

