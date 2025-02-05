<?php

namespace App\Models;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Parents;
use App\Models\Nurse;
use App\Models\Doctor;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\Custom;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPassword;
use App\Mail\EmailChangeNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_number',
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
        $this->notify(new CustomVerifyEmail);
    }


    // Define relationships for various roles
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
    public function staff()
    {
        return $this->hasOne(Staff::class, 'id_number', 'id_number');
    }
    public function parents()
    {
      

        return $this->hasManyThrough(
            Parents::class,
            Student::class,
            'id_number', // Foreign key on Student table...
            'id_number', // Foreign key on ParentModel table...
            'id_number', // Local key on User table...
            'parent_id' // Local key on Student table...
        );
    }
    public function parentRecord()
    {
        return $this->hasOne(Parents::class, 'id_number', 'id_number');
    }
    public function student()
    {
        return $this->hasOne(Student::class, 'id_number', 'id_number'); // Corrected to hasOne
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id_number');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_number', 'id_number');
    }

    public function dentalRecords()
    {
        return $this->hasMany(DentalRecord::class, 'id_number', 'id_number');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'id_number', 'id_number');
    }

    public function getFullNameAttribute()
    {
        if ($this->role === 'student' && $this->student) {
            return $this->student->first_name . ' ' . $this->student->last_name;
        } elseif ($this->role === 'staff' && $this->staffMember) {
            return $this->staffMember->first_name . ' ' . $this->staffMember->last_name;
        } elseif ($this->role === 'teacher' && $this->teacher) {
            return $this->teacher->first_name . ' ' . $this->teacher->last_name;
        } elseif ($this->role === 'parent' && $this->parents->first()) {
            return $this->parents->first()->first_name . ' ' . $this->parents->first()->last_name;
        } elseif ($this->role === 'nurse' && $this->nurse) {
            return $this->nurse->first_name . ' ' . $this->nurse->last_name;
        } elseif ($this->role === 'doctor' && $this->doctor) {
            return $this->doctor->first_name . ' ' . $this->doctor->last_name;
        } elseif ($this->role === 'admin') {
            return 'ITRC Pilar College';
        }


        return null;
    }
    public function getPersonNameAttribute()
    {
        switch (strtolower($this->role)) {
            case 'student':
                return $this->student ? ($this->student->first_name . ' ' . $this->student->last_name) : 'N/A';
            case 'parent':
                return $this->parentRecord ? ($this->parentRecord->first_name . ' ' . $this->parentRecord->last_name) : 'N/A';
            case 'teacher':
                return $this->teacher ? ($this->teacher->first_name . ' ' . $this->teacher->last_name) : 'N/A';
            case 'staff':
                return $this->staff ? ($this->staff->first_name . ' ' . $this->staff->last_name) : 'N/A';
            case 'nurse':
                return $this->nurse ? ($this->nurse->first_name . ' ' . $this->nurse->last_name) : 'N/A';
            case 'doctor':
                return $this->doctor ? ($this->doctor->first_name . ' ' . $this->doctor->last_name) : 'N/A';
                case 'admin':
                    return $this->admin ? $this->admin->name : 'ITRC Pilar College';
            default:
                return 'User';
        }
    }
    
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_number', 'id_number');
    }
    public function getEmailForVerification()
{
    return strtolower($this->email);
}
public function getPatientNameAttribute()
{
    return $this->name;
}

public function sendPasswordResetNotification($token)
{
    $this->notify(new CustomResetPassword($token));
}
}
