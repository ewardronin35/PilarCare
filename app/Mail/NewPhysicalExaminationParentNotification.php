<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPhysicalExaminationParentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $parentUser;
    public $studentUser;
    public $physicalExamination;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parentUser, $studentUser, $physicalExamination)
    {
        $this->parentUser = $parentUser;
        $this->studentUser = $studentUser;
        $this->physicalExamination = $physicalExamination;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Child\'s New Physical Examination')
                    ->markdown('emails.physical_examination.parent_notification')
                    ->with([
                        'parentUser' => $this->parentUser,
                        'studentUser' => $this->studentUser,
                        'physicalExamination' => $this->physicalExamination,
                    ]);
    }
    
}
