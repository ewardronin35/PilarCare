<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'patient_name',
        'appointment_date',
        'appointment_time',
        'role',
        'doctor_id',          // Added doctor_id
        'appointment_type',
        'status',
    ];
    protected $dates = ['appointment_date'];
    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // Optional: Add a method to format the date as needed
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->appointment_date)->format('F j, Y');
    }
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now());
    }

    // Scope for past appointments
    public function scopeCompleted($query)
    {
        return $query->where('appointment_date', '<', now());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
    public function patient()
{
    return $this->belongsTo(User::class, 'id_number', 'id_number');
}
}

