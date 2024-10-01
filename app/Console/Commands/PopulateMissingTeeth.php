<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DentalRecord;
use App\Models\Teeth;

class PopulateMissingTeeth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dental:populate-missing-teeth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate missing teeth entries for dental records with default status "Healthy"';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $allToothNumbers = [
            11, 12, 13, 14, 15, 16, 17, 18,
            21, 22, 23, 24, 25, 26, 27, 28,
            31, 32, 33, 34, 35, 36, 37, 38,
            41, 42, 43, 44, 45, 46, 47, 48
        ];

        $dentalRecords = DentalRecord::all();

        foreach ($dentalRecords as $record) {
            $existingTeethNumbers = Teeth::where('dental_record_id', $record->dental_record_id)
                ->pluck('tooth_number')
                ->toArray();

            $missingTeethNumbers = array_diff($allToothNumbers, $existingTeethNumbers);

            if (!empty($missingTeethNumbers)) {
                foreach ($missingTeethNumbers as $toothNumber) {
                    Teeth::create([
                        'dental_record_id' => $record->dental_record_id,
                        'tooth_number' => $toothNumber,
                        'status' => 'Healthy',
                        'notes' => null,
                        'svg_path' => '',
                        'dental_pictures' => null,
                        'is_current' => true,
                        'is_approved' => true,
                    ]);

                    $this->info("Added tooth number {$toothNumber} for Dental Record ID {$record->dental_record_id}.");
                }
            } else {
                $this->info("Dental Record ID {$record->dental_record_id} already has all teeth entries.");
            }
        }

        $this->info('Completed populating missing teeth entries.');

        return 0;
    }
}
