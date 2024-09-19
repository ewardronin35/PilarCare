<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'department', // Adjusted attribute for nurses
        'approved',
    ];

    // Optionally, you can add relationships or additional methods here
}
