<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Information;
use App\Models\Parents; // Ensure this points to the correct namespace
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    // Existing Relationships
    public function healthExaminations()
    {
        return $this->hasMany(HealthExamination::class, 'id_number', 'id_number');
    }

    public function information()
    {
        return $this->hasOne(Information::class, 'id_number', 'id_number');
    }

    public function nurse()
    {
        return $this->hasOne(Nurse::class, 'id_number', 'id_number');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id_number', 'id_number');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'id_number', 'id_number');
    }

    public function staffMember()
    {
        return $this->hasOne(Staff::class, 'id_number', 'id_number');
    }

    public function parentRelations()
    {
        return $this->hasMany(Parents::class, 'student_id', 'id_number');
    }
    
    // Updated Relationship: Parents
    /**
     * Get the parents associated with the user (if the user is a student).
     */
    public function parents()
    {
        return $this->hasMany(Parents::class, 'student_id', 'id_number');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'id_number', 'id_number');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id_number');
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_number', 'id_number');
    }

    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class, 'id_number', 'id_number');
    }
    // In User.php
public function complaints()
{
    return $this->hasMany(Complaint::class, 'id_number', 'id_number');
}

}
