<?php

namespace App\Mail;

use App\Models\MedicineIntake;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicineIntakeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $medicineIntake;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param MedicineIntake $medicineIntake
     * @param User $user
     */
    public function __construct(MedicineIntake $medicineIntake, User $user)
    {
        $this->medicineIntake = $medicineIntake;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Medicine Intake Reminder')
                    ->markdown('emails.medicine_intake_reminder');
    }
}
