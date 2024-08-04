<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'patient_name',
        'appointment_date',
        'appointment_time',
        'role',
        'appointment_type',
    ];
}
