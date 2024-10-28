<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $itemName;
    protected $quantity;

    /**
     * Create a new notification instance.
     *
     * @param string $itemName
     * @param int $quantity
     */
    public function __construct($itemName, $quantity)
    {
        $this->itemName = $itemName;
        $this->quantity = $quantity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Use database channel
        // To add email notifications, include 'mail' here
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Low Stock Alert',
            'message' => "The item '{$this->itemName}' is low in stock with only {$this->quantity} left.",
            'item_name' => $this->itemName,
            'quantity' => $this->quantity,
            'timestamp' => now(),
        ];
    }

 
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Low Stock Alert')
                    ->greeting('Hello ' . $notifiable->first_name . ' (' . $notifiable->role . '),') // Correct: role accessed as property
                    ->line("The item '{$this->itemName}' is low in stock with only {$this->quantity} left.")
                    ->action('View Inventory', url('/admin/inventory'))
                    ->line('Please replenish the stock as soon as possible.');
    }
    
}
