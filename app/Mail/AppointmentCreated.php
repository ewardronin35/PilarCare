<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $doctor;
    public $creator;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Appointment  $appointment
     * @param  \App\Models\Doctor  $doctor
     * @param  \App\Models\User  $creator
     * @return void
     */
    public function __construct(Appointment $appointment, Doctor $doctor, User $creator)
    {
        $this->appointment = $appointment;
        $this->doctor = $doctor;
        $this->creator = $creator;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->subject('New Appointment Scheduled')
                   ->markdown('emails.appointment.created');
    }
}
