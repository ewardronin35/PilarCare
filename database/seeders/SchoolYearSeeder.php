<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;

class SchoolYearSeeder extends Seeder
{
    public function run()
    {
        // Start year
        $startYear = 2024;
        // End year
        $endYear = 2040;

        for ($year = $startYear; $year < $endYear; $year++) {
            $nextYear = $year + 1;
            $schoolYear = $year . '-' . $nextYear;
            SchoolYear::create([
                'year' => $schoolYear,
            ]);
        }
    }
}
