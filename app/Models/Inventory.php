<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'item_name', 'quantity', 'supplier', 'type', 'date_acquired'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($inventory) {
            if ($inventory->quantity <= 2) {
                Notification::create([
                    'user_id' => 'admin', // Adjust this to target the correct user or role
                    'title' => 'Low Inventory Alert',
                    'message' => "The quantity of {$inventory->item_name} is low (Only {$inventory->quantity} left)."
                ]);
            }
        });
    }
}
