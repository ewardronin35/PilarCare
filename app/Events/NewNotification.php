<?php

namespace App\Events;

use App\Models\Notification as UserNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel; // Use PrivateChannel if notifications are user-specific
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Notification $notification
     * @return void
     */
    public function __construct(UserNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * For role-based notifications, broadcasting on a public or presence channel.
     * For user-specific notifications, use a private channel.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->notification->role) {
            // Broadcast to a role-based public channel
            return new Channel('notifications.role.' . strtolower($this->notification->role));
        } elseif ($this->notification->user_id) {
            // Broadcast to a user-specific private channel
            return new PrivateChannel('notifications.user.' . $this->notification->user_id);
        }

        // Default channel if neither role nor user_id is set
        return new Channel('notifications.general');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'NewNotification';
    }
}
