<?php

namespace App\Imports;

use App\Models\Parents;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParentImport implements ToModel, WithValidation
{
    public function model(array $row)
    {
        return new Parents([
            'id_number' => $row[0],
            'first_name' => $row[1],
            'last_name' => $row[2],
            'student_id' => $row[3],
            'approved' => false,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.0' => 'required|unique:parents,id_number',
            '*.1' => 'required',
            '*.2' => 'required',
            '*.3' => 'required|exists:students,id_number',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.0.required' => 'ID Number is required',
            '*.0.unique' => 'ID Number must be unique',
            '*.1.required' => 'First Name is required',
            '*.2.required' => 'Last Name is required',
            '*.3.required' => 'Student ID is required',
            '*.3.exists' => 'Student ID must exist in students table',
        ];
    }
}
