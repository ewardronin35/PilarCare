<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Information;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'approved',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
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
    public function healthExaminations()
{
    return $this->hasMany(HealthExamination::class, 'id_number', 'id_number');
}


public function information()
{
    return $this->hasOne(Information::class, 'id_number', 'id_number');
}
// User.php Model

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

public function parent()
{
    return $this->hasOne(Parents::class, 'id_number', 'id_number');
}
public function students()
{
    return $this->hasMany(Student::class, 'id_number', 'id_number');
}
public function notifications()
{
    return $this->hasMany(Notification::class, 'user_id', 'id_number');
}
}
