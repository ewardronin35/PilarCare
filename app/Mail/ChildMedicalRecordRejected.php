<?php

namespace App\Mail;

use App\Models\MedicalRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildMedicalRecordRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $medicalRecord;
    public $student;
    public $parentUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MedicalRecord $medicalRecord, Student $student, User $parentUser)
    {
        $this->medicalRecord = $medicalRecord;
        $this->student = $student;
        $this->parentUser = $parentUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->subject('Your Child\'s Medical Record Rejected')
                   ->view('emails.child_medical_record_rejected');
    }
}
