<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teeth;
use Illuminate\Support\Facades\DB;

class FixNullToothNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dental:fix-null-tooth-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix teeth records with NULL tooth_number by assigning correct values';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch all teeth records with NULL tooth_number
        $teethWithNull = Teeth::whereNull('tooth_number')->get();

        if ($teethWithNull->isEmpty()) {
            $this->info('No teeth records with NULL tooth_number found.');
            return 0;
        }

        foreach ($teethWithNull as $tooth) {
            // Logic to determine the correct tooth_number
            // This requires knowledge about the dental record and which teeth are already present

            // Example Strategy:
            // Fetch existing tooth_numbers for the dental_record_id
            $existingNumbers = Teeth::where('dental_record_id', $tooth->dental_record_id)
                                    ->whereNotNull('tooth_number')
                                    ->pluck('tooth_number')
                                    ->toArray();

            // Define all possible tooth_numbers
            $allTeethNumbers = [
                11, 12, 13, 14, 15, 16, 17, 18,
                21, 22, 23, 24, 25, 26, 27, 28,
                31, 32, 33, 34, 35, 36, 37, 38,
                41, 42, 43, 44, 45, 46, 47, 48
            ];

            // Determine missing tooth_numbers
            $missingNumbers = array_diff($allTeethNumbers, $existingNumbers);

            if (!empty($missingNumbers)) {
                // Assign the first missing tooth_number
                $assignedNumber = array_shift($missingNumbers);
                
                // Update the tooth record
                $tooth->tooth_number = $assignedNumber;
                $tooth->save();

                $this->info("Updated Teeth ID {$tooth->id} with tooth_number {$assignedNumber}.");
            } else {
                $this->warn("No missing tooth_numbers available for Teeth ID {$tooth->id} in Dental Record ID {$tooth->dental_record_id}.");
            }
        }

        $this->info('Completed fixing NULL tooth_number entries.');

        return 0;
    }
}
