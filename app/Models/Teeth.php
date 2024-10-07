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
        'dental_pictures',
        'is_current',
        'is_approved',
        
        
    ];
    protected $casts = [
        'dental_pictures' => 'array',
    ];

    // Relationship to the DentalRecord model
    public function dentalRecord()
    {
        return $this->belongsTo(DentalRecord::class, 'dental_record_id', 'dental_record_id'); // Relate to dental_records.id_number
    }
}
