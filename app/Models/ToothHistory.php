<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToothHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tooth_id',
        'tooth_number',
        'status',
        'notes',
        'svg_path',
        'dental_pictures',
        'is_current',
        'is_approved',
        'is_new',
    ];

    protected $casts = [
        'dental_pictures' => 'array',
        'is_current' => 'boolean',
        'is_approved' => 'boolean',
        'is_new' => 'boolean',
    ];

    /**
     * Relationship to Teeth.
     */
    public function tooth()
    {
        return $this->belongsTo(Teeth::class, 'tooth_id');
    }
}
