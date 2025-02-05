<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admins::create([
            'id_number' => 'C120000',
            'name' => 'ITRC Pilar College',
            'created_at' => '2024-09-02 11:39:59',
            'updated_at' => '2024-09-06 15:46:17',
        ]);
    }
}
