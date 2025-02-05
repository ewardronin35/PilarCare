<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determine the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the reset password email message.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Generate the reset URL
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => urlencode($notifiable->email),
        ], false));

        return (new MailMessage)
                    ->subject('Reset Your Password')
                    ->view('emails.custom_reset_password', [
                        'resetUrl' => $resetUrl,
                        'user'     => $notifiable,
                    ]);
    }
}
