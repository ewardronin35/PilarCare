<?php
namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Assuming 'role' and 'approved' fields exist in your users table
        return new User([
            'id_number'   => $row['id_number'],
            'first_name'  => $row['first_name'],
            'last_name'   => $row['last_name'],
            'grade'       => $row['grade'], // or 'course'
            'role'        => 'Student', // default role
            'approved'    => 1, // automatically approve
        ]);
    }
}
