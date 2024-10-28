<?php

namespace App\Mail;

use App\Models\MedicalRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicalRecordRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $medicalRecord;

    /**
     * Create a new message instance.
     *
     * @param MedicalRecord $medicalRecord
     */
    public function __construct(MedicalRecord $medicalRecord)
    {
        $this->medicalRecord = $medicalRecord;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Medical Record Has Been Rejected')
                    ->markdown('emails.medical_record_rejected');
    }
}
