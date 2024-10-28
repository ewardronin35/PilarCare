<?php

namespace App\Mail;

use App\Models\Complaint;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentComplaintNotification extends Mailable
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
         return $this->subject('Your Child Has Submitted a Complaint')
                     ->markdown('emails.parent_complaint_notification')
                     ->with([
                         'studentName' => $this->student->first_name . ' ' . $this->student->last_name,
                         'sicknessDescription' => $this->complaint->sickness_description,
                         'painAssessment' => $this->complaint->pain_assessment,
                         'medicineGiven' => $this->complaint->medicine_given,
                     ]);
    }
}
