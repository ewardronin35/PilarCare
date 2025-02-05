<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class EmailChangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $originalEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $originalEmail)
    {
        $this->user = $user;
        $this->originalEmail = $originalEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Email Address Has Been Changed')
                    ->view('emails.email_change_notification');
    }
}
