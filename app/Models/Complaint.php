<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'birthdate',
        'health_history',
        'year_section',
        'contact_number',
    ];

    public function details()
    {
        return $this->hasMany(ComplaintDetail::class);
    }
}
