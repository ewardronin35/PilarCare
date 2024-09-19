<?php
namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToModel, WithValidation, WithHeadingRow
{
    protected $duplicates = [];

    public function model(array $row)
    {
        $existingStudent = Student::where('id_number', $row['id_number'])->first();
    
        if ($existingStudent) {
            Log::info('Duplicate entry found:', $row);
            throw ValidationException::withMessages(['id_number' => 'Student already exists']);
        }
    
        Log::info('Importing student:', $row);
    
        return new Student([
            'id_number' => $row['id_number'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'grade_or_course' => $row['grade_or_course'],
            'approved' => false,
        ]);
    }
    

    public function rules(): array
    {
        return [
            '*.id_number' => 'required|unique:students,id_number',
            '*.first_name' => 'required',
            '*.last_name' => 'required',
            '*.grade_or_course' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.id_number.required' => 'ID Number is required',
            '*.id_number.unique' => 'ID Number must be unique',
            '*.first_name.required' => 'First Name is required',
            '*.last_name.required' => 'Last Name is required',
            '*.grade_or_course.required' => 'Grade/Course is required',
        ];
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
