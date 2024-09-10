<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teeth extends Model
{
    protected $table = 'teeth'; // Specify the correct table name

    protected $fillable = [
        'dental_record_id',
        'tooth_number',
        'status',
        'notes',
        'svg_path',
    ];

    // Relationship to the DentalRecord model
    public function dentalRecord()
    {
        return $this->belongsTo(DentalRecord::class, 'dental_record_id');
    }
}
