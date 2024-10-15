<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;



    // Specify the fillable fields
    protected $fillable = [
        'year', // e.g., '2023-2024'
        'is_current',  // Indicates if the school year is current
    ];
}
