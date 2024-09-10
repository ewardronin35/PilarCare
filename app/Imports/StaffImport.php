<?php

namespace App\Imports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class StaffImport implements ToModel, WithValidation
{

    public $duplicates = [];

    public function model(array $row)
    {
        return new Staff([
            'id_number' => $row[0],
            'first_name' => $row[1],
            'last_name' => $row[2],
            'approved' => false
        ]);
    }

    public function rules(): array
    {
        return [
            '*.0' => 'required|unique:staff,id_number',
            '*.1' => 'required',
            '*.2' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.0.required' => 'ID Number is required',
            '*.0.unique' => 'ID Number must be unique',
            '*.1.required' => 'First Name is required',
            '*.2.required' => 'Last Name is required',
        ];
    }
}
