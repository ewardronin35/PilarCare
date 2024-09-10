<?php
namespace App\Imports;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class DoctorImport implements ToModel, WithValidation, WithHeadingRow
{
    protected $duplicates = [];

    public function model(array $row)
    {
        $existingDoctor = Doctor::where('id_number', $row['id_number'])->first();
    
        if ($existingDoctor) {
            Log::info('Duplicate entry found:', $row);
            throw ValidationException::withMessages(['id_number' => 'Doctor already exists']);
        }
    
        Log::info('Importing Doctor:', $row);
    
        return new Doctor([
            'id_number' => $row['id_number'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'specialization' => $row['specialization'],  // Using specialization for doctors
            'approved' => false,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.id_number' => 'required|unique:doctors,id_number',
            '*.first_name' => 'required',
            '*.last_name' => 'required',
            '*.specialization' => 'required',  // Adjusted validation for specialization
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.id_number.required' => 'ID Number is required',
            '*.id_number.unique' => 'ID Number must be unique',
            '*.first_name.required' => 'First Name is required',
            '*.last_name.required' => 'Last Name is required',
            '*.specialization.required' => 'Specialization is required',
        ];
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
