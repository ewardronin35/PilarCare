<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Teeth;

class InitializeTeeth
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $dentalRecord = $event->model;

        $allToothNumbers = [
            11, 12, 13, 14, 15, 16, 17, 18,
            21, 22, 23, 24, 25, 26, 27, 28,
            31, 32, 33, 34, 35, 36, 37, 38,
            41, 42, 43, 44, 45, 46, 47, 48
        ];

        foreach ($allToothNumbers as $toothNumber) {
            Teeth::create([
                'dental_record_id' => $dentalRecord->dental_record_id,
                'tooth_number' => $toothNumber,
                'status' => 'Healthy',
                'notes' => null,
                'svg_path' => '',
                'dental_pictures' => null,
                'is_current' => true,
                'is_approved' => true,
            ]);
        }
    }
}
