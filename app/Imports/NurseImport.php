<?php
namespace App\Imports;

use App\Models\Nurse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class NurseImport implements ToModel, WithValidation, WithHeadingRow
{
    protected $duplicates = [];

    public function model(array $row)
    {
        $existingNurse = Nurse::where('id_number', $row['id_number'])->first();
    
        if ($existingNurse) {
            Log::info('Duplicate entry found:', $row);
            throw ValidationException::withMessages(['id_number' => 'Nurse already exists']);
        }
    
        Log::info('Importing Nurse:', $row);
    
        return new Nurse([
            'id_number' => $row['id_number'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'department' => $row['department'],  // Adjusted field for nurses
            'approved' => false,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.id_number' => 'required|unique:nurses,id_number',
            '*.first_name' => 'required',
            '*.last_name' => 'required',
            '*.department' => 'required',  // Adjusted validation for nurses
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.id_number.required' => 'ID Number is required',
            '*.id_number.unique' => 'ID Number must be unique',
            '*.first_name.required' => 'First Name is required',
            '*.last_name.required' => 'Last Name is required',
            '*.department.required' => 'Department is required',
        ];
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
