<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use Carbon\Carbon;

class UpdateEnrollmentStatus extends Command
{
    protected $signature = 'enrollment:update-status';
    protected $description = 'Update the enrollment status for the next semester';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentMonth = Carbon::now()->month;

        if ($currentMonth == 11) {
            // End of First Semester
            Enrollment::where('semester', 'First Semester')
                ->update(['is_enrolled' => false]);
        } elseif ($currentMonth == 4) {
            // End of Second Semester
            Enrollment::where('semester', 'Second Semester')
                ->update(['is_enrolled' => false]);
        }

        $this->info('Enrollment statuses updated successfully.');
    }
}
