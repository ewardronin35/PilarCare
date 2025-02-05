<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-reminders';
    protected $description = 'Send daily reminders to students who have not submitted health examinations';


    /**
     * The console command description.
     *
     * @var string
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        if (!$currentSchoolYear) {
            $this->error('Current school year not set.');
            return 1;
        }

        // Fetch students who haven't submitted
        $submittedIdNumbers = HealthExamination::where('school_year', $currentSchoolYear->year)->pluck('id_number')->toArray();
        $students = Student::whereNotIn('id_number', $submittedIdNumbers)->with('user')->get();

        foreach ($students as $student) {
            $user = $student->user;
            if (!$user) continue;

            // Check if a reminder was already sent today
            $today = now()->startOfDay();
            $existingReminder = Notification::where('user_id', $user->id_number)
                ->where('title', 'Reminder: Submit Health Examination')
                ->where('created_at', '>=', $today)
                ->first();

            if ($existingReminder) continue;

            // Create notification
            Notification::create([
                'user_id' => $user->id_number,
                'title' => 'Reminder: Submit Health Examination',
                'message' => 'Please submit your health examination documents for the current school year.',
                'scheduled_time' => now(),
            ]);

            // Send email (queue)
            Mail::to($user->email)->queue(new StudentHealthExaminationReminder($user));

            // Notify program head
            $programHead = Teacher::where('course', $student->grade_or_course)
                ->where('role', 'program_head')
                ->first();

            if ($programHead) {
                $programHeadUser = $programHead->user;
                if ($programHeadUser) {
                    // Check if a reminder was already sent today to program head
                    $existingPHReminder = Notification::where('user_id', $programHeadUser->id_number)
                        ->where('title', 'Reminder: Students Pending Health Examination')
                        ->where('created_at', '>=', $today)
                        ->first();

                    if (!$existingPHReminder) {
                        // Create notification for program head
                        Notification::create([
                            'user_id' => $programHeadUser->id_number,
                            'title' => 'Reminder: Students Pending Health Examination',
                            'message' => "Student {$user->first_name} {$user->last_name} has not submitted their health examination documents.",
                            'scheduled_time' => now(),
                        ]);

                        // Send email to program head (queue)
                        Mail::to($programHeadUser->email)->queue(new ProgramHeadHealthExaminationReminder($programHeadUser, $user));
                    }
                }
            }
        }

        $this->info('Daily reminders sent successfully.');
        return 0;
    }
}
