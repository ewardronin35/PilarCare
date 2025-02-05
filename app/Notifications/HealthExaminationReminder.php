<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HealthExaminationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $student;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Student $student
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // You can add 'sms' or other channels as needed
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Health Examination Reminder')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('This is a friendly reminder that student ' . $this->student->first_name . ' ' . $this->student->last_name . ' (ID: ' . $this->student->id_number . ') has not yet submitted their health examination documents.')
                    ->action('View Student Details', url('/admin/students/' . $this->student->id))
                    ->line('Please follow up with the student to ensure compliance.')
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification for database.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'student_name' => $this->student->first_name . ' ' . $this->student->last_name,
            'student_id_number' => $this->student->id_number,
            'message' => 'Student ' . $this->student->first_name . ' ' . $this->student->last_name . ' has not submitted health examination documents.',
            'action_url' => url('/admin/students/' . $this->student->id),
        ];
    }
}
