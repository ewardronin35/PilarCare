<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;

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
     * @return void
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
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for the database channel.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'item_name' => $this->itemName,
            'expiry_date' => $this->expiryDate,
            'message' => "The item '{$this->itemName}' is expiring on {$this->expiryDate}. Please take necessary actions.",
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'item_name' => $this->itemName,
            'expiry_date' => $this->expiryDate,
            'message' => "The item '{$this->itemName}' is expiring on {$this->expiryDate}. Please take necessary actions.",
        ]);
    }

    /**
     * Specify the channels the notification should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('inventory-channel');
    }

    /**
     * Define the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'inventory-expiring';
    }

    /**
     * Get the notification's representation as an array (optional, for other channels).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'item_name' => $this->itemName,
            'expiry_date' => $this->expiryDate,
            'message' => "The item '{$this->itemName}' is expiring on {$this->expiryDate}. Please take necessary actions.",
        ];
    }
}
