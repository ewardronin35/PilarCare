<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Teacher;
use App\Notifications\HealthExaminationReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendHealthExaminationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:health-examinations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to teachers for students who have not submitted health examinations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Define the timeframe after account creation to send reminders
            $reminderDays = 7; // e.g., 7 days after account creation

            // Calculate the cutoff date
            $cutoffDate = Carbon::now()->subDays($reminderDays);

            // Fetch students who registered more than $reminderDays ago and have not submitted health examinations
            $studentsToRemind = Student::where('created_at', '<=', $cutoffDate)
                ->whereDoesntHave('healthExaminations') // Assuming a relationship exists
                ->get();

            Log::info('Health Examination Reminders: Found ' . $studentsToRemind->count() . ' students to remind.');

            foreach ($studentsToRemind as $student) {
                // Find the teacher(s) associated with the student's course
                $teachers = Teacher::where('course', $student->course)
                    ->where('approved', true)
                    ->get();

                foreach ($teachers as $teacher) {
                    // Send notification to each teacher
                    $teacher->notify(new HealthExaminationReminder($student));
                    Log::info('Sent Health Examination Reminder to Teacher ID: ' . $teacher->id . ' for Student ID: ' . $student->id);
                }
            }

            $this->info('Health Examination Reminders sent successfully.');

            return 0;
        } catch (\Exception $e) {
            Log::error('Error sending Health Examination Reminders: ' . $e->getMessage());
            $this->error('Failed to send Health Examination Reminders.');
            return 1;
        }
    }
}
