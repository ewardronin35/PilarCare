<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'birthdate',
        'health_history',
        'year',
        'section',
        'contact_number',
        'pain_assessment',
        'sickness_description',
        'status',
        'role', 
    ];
}
