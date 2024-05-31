<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'datetime',
        'complaint',
        'management',
        'remarks',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
