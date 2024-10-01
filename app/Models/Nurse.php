<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'department', // Adjusted attribute for nurses
        'approved',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
}
