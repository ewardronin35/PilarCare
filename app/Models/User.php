<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'contact_number', 'gender', 'role', 'id_number', 'parent_id', 
        'student_type', 'program', 'year_level', 'year_section', 'bed_type', 'section', 'grade', 'teacher_type', 'staff_role', 'profile_picture',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function healthExaminations()
    {
        return $this->hasMany(HealthExamination::class);
    }

    // Add this method to establish the relationship
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id_number');
    }
}
