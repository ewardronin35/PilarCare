<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class SendMedicineIntakeReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $notification;
    protected $user;

    public function __construct(Notification $notification, User $user)
    {
        $this->notification = $notification;
        $this->user = $user;
    }

    public function handle()
    {
        // Update the notification as sent
        $this->notification->save();


    }
}
