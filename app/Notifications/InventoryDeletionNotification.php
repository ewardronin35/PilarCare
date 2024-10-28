<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InventoryDeletionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $itemName;

    /**
     * Create a new notification instance.
     *
     * @param string $itemName
     */
    public function __construct($itemName)
    {
        $this->itemName = $itemName;
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
            'title' => 'Inventory Deletion Alert',
            'message' => "The item '{$this->itemName}' has been deleted from the inventory.",
            'item_name' => $this->itemName,
            'timestamp' => now(),
        ];
    }

    /**
     * (Optional) If you want to send email notifications as well.
     *
     * Uncomment and configure the following method.
     */
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Inventory Deletion Alert')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line("The item '{$this->itemName}' has been deleted from the inventory.")
                    ->action('View Inventory', url('/admin/inventory'))
                    ->line('Please verify if this action was intentional.');
    }
    
}
