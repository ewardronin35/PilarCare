<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\User;
use App\Notifications\InventoryExpiringNotification;
use Carbon\Carbon;

class CheckExpiringInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for inventory items that are expiring soon and notify users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Define the period to check (e.g., items expiring in the next 7 days)
        $now = Carbon::now();
        $checkDate = $now->copy()->addDays(7);

        // Fetch items expiring within the next 7 days
        $expiringItems = Inventory::whereBetween('expiry_date', [$now, $checkDate])->get();

        if ($expiringItems->isEmpty()) {
            $this->info('No inventory items are expiring within the next 7 days.');
            return 0;
        }

        // Fetch all users who should receive notifications
        // Adjust the query as per your application's logic (e.g., roles)
        $users = User::all(); // Or filter by roles if needed

        foreach ($expiringItems as $item) {
            foreach ($users as $user) {
                $user->notify(new InventoryExpiringNotification($item));
            }
        }

        $this->info('Notifications sent for expiring inventory items.');
        return 0;
    }
}
