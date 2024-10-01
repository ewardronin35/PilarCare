<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalExamination extends Model
{
    use HasFactory;

    protected $table = 'physical_examinations';

    protected $fillable = [
        'id_number',
        'height',
        'weight',
        'vision',
        'remarks',
        'md_approved',
    ];


    // Relationships if needed
    // Assuming `id_number` relates to a `User` model
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
    public function information()
    {
        return $this->belongsTo(Information::class, 'id_number', 'id_number');
    }
}
