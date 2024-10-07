<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToModel, WithValidation, WithHeadingRow, WithMapping
{
    protected $duplicates = [];

    /**
     * Define the mappings for each row to ensure correct data types.
     *
     * @param array $row
     * @return array
     */
    public function map($row): array
    {
        // Force id_number to be treated as a string
        $row['id_number'] = (string) $row['id_number'];

        // Ensure all required fields are present in the row
        $requiredFields = ['id_number', 'first_name', 'last_name', 'grade_or_course'];
        foreach ($requiredFields as $field) {
            if (!isset($row[$field]) || empty($row[$field])) {
                throw ValidationException::withMessages([
                    $field => "The {$field} field is required and cannot be empty.",
                ]);
            }
        }

        return $row;
    }

    /**
     * Create a new model instance for each row of data.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function model(array $row)
    {
        // Ensure ID number is a string and validate its length
        $idNumber = str_pad($row['id_number'], 7, '0', STR_PAD_LEFT);

        // Check if the student already exists
        $existingStudent = Student::where('id_number', $idNumber)->first();
        if ($existingStudent) {
            $this->duplicates[] = $idNumber; // Add to duplicates array for tracking
            throw ValidationException::withMessages(['id_number' => "Student with ID Number '{$idNumber}' already exists."]);
        }

        Log::info('Importing student:', $row);

        return new Student([
            'id_number' => $idNumber, // Store the ID number as a string
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'grade_or_course' => $row['grade_or_course'],
            'approved' => false,
        ]);
    }

    /**
     * Define the validation rules for the import.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.id_number' => 'required|string|size:7|unique:students,id_number',
            '*.first_name' => 'required|string',
            '*.last_name' => 'required|string',
            '*.grade_or_course' => 'required|string',
        ];
    }

    /**
     * Define custom error messages for the validation rules.
     *
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.id_number.required' => 'The ID Number field is required.',
            '*.id_number.unique' => 'The ID Number must be unique and should not already exist in the database.',
            '*.id_number.size' => 'The ID Number must be exactly 7 characters long.',
            '*.first_name.required' => 'The First Name field is required.',
            '*.last_name.required' => 'The Last Name field is required.',
            '*.grade_or_course.required' => 'The Grade or Course field is required.',
        ];
    }
    

    /**
     * Get the list of duplicate records found during import.
     *
     * @return array
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
