<?php

namespace App\Mail;

use App\Models\User;
use App\Models\DentalExamination;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDentalExaminationParentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $parentUser;
    public $childUser;
    public $dentalExamination;
    public $teethData;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $parentUser
     * @param \App\Models\User $childUser
     * @param \App\Models\DentalExamination $dentalExamination
     * @param array $teethData
     */
    public function __construct(User $parentUser, User $childUser, DentalExamination $dentalExamination, array $teethData)
    {
        $this->parentUser = $parentUser;
        $this->childUser = $childUser;
        $this->dentalExamination = $dentalExamination;
        $this->teethData = $teethData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Dental Examination for Your Child')
                    ->markdown('emails.dental_examination.parent_notification')
                    ->with([
                        'parentUser' => $this->parentUser,
                        'childUser' => $this->childUser,
                        'dentalExamination' => $this->dentalExamination,
                        'teethData' => $this->teethData,
                    ]);
    }
}
