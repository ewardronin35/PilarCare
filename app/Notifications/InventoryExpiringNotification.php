<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InventoryExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $itemName;
    protected $expiryDate;

    /**
     * Create a new notification instance.
     *
     * @param string $itemName
     * @param string $expiryDate
     */
    public function __construct($itemName, $expiryDate)
    {
        $this->itemName = $itemName;
        $this->expiryDate = $expiryDate;
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
            'title' => 'Item Expiry Alert',
            'message' => "The item '{$this->itemName}' is expiring on {$this->expiryDate}.",
            'item_name' => $this->itemName,
            'expiry_date' => $this->expiryDate,
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
                    ->subject('Inventory Expiry Alert')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line("The item '{$this->itemName}' is expiring on {$this->expiryDate}.")
                    ->action('View Inventory', url('/admin/inventory'))
                    ->line('Please take necessary actions.');
    }
    
}
