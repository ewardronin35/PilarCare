<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_number',
        'birthdate',
        'profile_picture',
        'parent_name_father',
        'parent_name_mother',
        'emergency_contact_number',
        'personal_contact_number',
        'guardian_name',
        'guardian_relationship',
        'address',
        'birthdate',
        'profile_picture',
    ];

    /**
     * Get the user associated with the information.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
    
}
