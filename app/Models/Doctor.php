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

    // Optionally, you can add relationships or additional methods here
}
