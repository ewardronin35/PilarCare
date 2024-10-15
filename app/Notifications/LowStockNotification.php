<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

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
     * @return void
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
            'quantity' => $this->quantity,
            'message' => "The item '{$this->itemName}' is low in stock with only {$this->quantity} left.",
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
            'quantity' => $this->quantity,
            'message' => "The item '{$this->itemName}' is low in stock with only {$this->quantity} left.",
        ]);
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
            'quantity' => $this->quantity,
            'message' => "The item '{$this->itemName}' is low in stock with only {$this->quantity} left.",
        ];
    }
}
