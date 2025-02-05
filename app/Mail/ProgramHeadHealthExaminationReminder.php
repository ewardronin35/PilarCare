<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgramHeadHealthExaminationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $programHead;
    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($programHead, $student)
    {
        $this->programHead = $programHead;
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reminder: Students Pending Health Examination')
                    ->markdown('emails.health_examination.program_head_reminder');
    }
}
