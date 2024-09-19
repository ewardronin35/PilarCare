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
        'picture',
    ];

    // Mutator to handle picture upload
    public function setPictureAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['picture'] = json_encode($value);
        } else {
            $this->attributes['picture'] = $value;
        }
    }

    // Accessor to get the pictures as an array
    public function getPictureAttribute($value)
    {
        return json_decode($value, true);
    }

    // Relationships if needed
    // Assuming `id_number` relates to a `User` model
    public function user()
    {
        return $this->belongsTo(User::class, 'id_number', 'id_number');
    }
}
