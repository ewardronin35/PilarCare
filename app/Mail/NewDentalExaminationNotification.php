<?php

namespace App\Mail;

use App\Models\User;
use App\Models\DentalExamination;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewDentalExaminationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $dentalExamination;
    public $teethData;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param \App\Models\DentalExamination $dentalExamination
     * @param array $teethData
     */
    public function __construct(User $user, DentalExamination $dentalExamination, array $teethData)
    {
        $this->user = $user;
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
        return $this->subject('New Dental Examination Recorded')
                    ->markdown('emails.dental_examination.user_notification')
                    ->with([
                        'teethData' => $this->teethData,
                    ]);
    }
}
