<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number', // Added this line
        'first_name',
        'last_name',
        'age',
        'birthdate',
        'year',
        'contact_number',
        'pain_assessment',
        'sickness_description',
        'status',
        'role',
    ];
}
