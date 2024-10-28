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
}
