<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToothRejectedUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $userName;
    public $patientName;
    public $toothNumber;
    public $status;
    public $notes;

    /**
     * Create a new message instance.
     *
     * @param array $emailData
     */
    public function __construct($emailData)
    {
        $this->userName = $emailData['userName'];
        $this->patientName = $emailData['patientName'];
        $this->toothNumber = $emailData['toothNumber'];
        $this->status = $emailData['status'];
        $this->notes = $emailData['notes'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->subject('Your Dental Record Has Been Rejected')
                   ->markdown('emails.tooth_rejected_user');
    }
}
