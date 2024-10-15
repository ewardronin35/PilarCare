<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use App\Events\LowStockNotification;
use App\Models\User;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'item_name', 'quantity', 'supplier', 'type', 'date_acquired', 'expiry_date'// Added Expiry Date

    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($inventory) {
            if ($inventory->quantity <= 1) {
                // Find all users with the 'admin' role (or another role as needed)
                $admins = User::whereHas('role', function($query) {
                    $query->where('name', 'admin'); // Adjust 'admin' to the desired role
                })->get();
        
                foreach ($admins as $admin) {
                    // Create a notification for each admin
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => 'Low Inventory Alert',
                        'message' => "The quantity of {$inventory->item_name} is low (Only {$inventory->quantity} left)."
                    ]);
                }

                // Log the low stock event
                \Log::info("Low stock alert for {$inventory->item_name}: Only {$inventory->quantity} left.");

                // Broadcast the low stock event to admins
                event(new LowStockNotification("Low stock alert for {$inventory->item_name}. Only {$inventory->quantity} left."));
            }
        });
    }
}
