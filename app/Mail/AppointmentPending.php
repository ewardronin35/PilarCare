<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Implement ShouldQueue for queueing
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentPending extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $appointment;
    public $doctor;
    public $user; // The authenticated user who created the appointment

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Appointment $appointment
     * @param \App\Models\Doctor $doctor
     * @param \App\Models\User $user
     */
    public function __construct(Appointment $appointment, Doctor $doctor, User $user)
    {
        $this->appointment = $appointment;
        $this->doctor = $doctor;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Appointment Pending Approval')
                    ->markdown('emails.appointment.pending');
    }
}
