<?php

namespace App\Mail;

use App\Models\PhysicalExamination;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Import ShouldQueue
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPhysicalExaminationNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $physicalExamination;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param PhysicalExamination $physicalExamination
     */
    public function __construct(User $user, PhysicalExamination $physicalExamination)
    {
        $this->user = $user;
        $this->physicalExamination = $physicalExamination;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.physical_examination.user_notification')
                    ->subject('New Physical Examination Recorded');
    }
}
