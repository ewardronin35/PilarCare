<?php

namespace App\Mail;

use App\Models\Complaint;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentGoHomeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;
    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Complaint $complaint, Student $student)
    {
        $this->complaint = $complaint;
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Student Going Home Notification')
                    ->markdown('emails.parent_go_home_notification')
                    ->with([
                        'complaint' => $this->complaint,
                        'student' => $this->student,
                    ]);
    }
    
}
