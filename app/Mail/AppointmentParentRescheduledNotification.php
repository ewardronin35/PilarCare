<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentParentRescheduledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $doctor;
    public $parent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment, Doctor $doctor, User $parent)
    {
        $this->appointment = $appointment;
        $this->doctor = $doctor;
        $this->parent = $parent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Child\'s Appointment Rescheduled')
                    ->markdown('emails.appointment.parent_rescheduled_notification')
                    ->with([
                        'appointment' => $this->appointment,
                        'doctor' => $this->doctor,
                        'parent' => $this->parent,
                    ]);
    }
}
