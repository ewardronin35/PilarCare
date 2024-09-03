<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'vision',
        'medicine_intake',
        'remarks',
        'md_approved', // MD approval status
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
