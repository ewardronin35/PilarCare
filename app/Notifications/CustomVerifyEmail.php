<?php
// app/Notifications/CustomVerifyEmail.php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config; // Add this line
use Illuminate\Support\Facades\Lang;

class CustomVerifyEmail extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
    
        return (new MailMessage)
            ->subject(Lang::get('Verify Your Email Address'))
            ->markdown('emails.verify-email', [
                'url' => $verificationUrl,
                'notifiable' => $notifiable,
            ]);
    }
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1(strtolower($notifiable->getEmailForVerification())),
            ]
        );
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
