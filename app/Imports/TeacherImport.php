<?php
namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;


class TeacherImport implements ToModel, WithValidation, WithHeadingRow
{
    protected $duplicates = [];

    public function model(array $row)
    {
        $existingTeacher = Teacher::where('id_number', $row['id_number'])->first();
    
        if ($existingTeacher) {
            Log::info('Duplicate entry found:', $row);
            throw ValidationException::withMessages(['id_number' => 'Teacher already exists']);
        }
    
        Log::info('Importing Teacher:', $row);
    
        return new Teacher([
            'id_number' => $row['id_number'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'bed_or_hed' => $row['bed_or_hed'],
            'approved' => false,
        ]);
    }
    

    public function rules(): array
    {
        return [
            '*.id_number' => 'required|unique:teacher,id_number',
            '*.first_name' => 'required',
            '*.last_name' => 'required',
            '*.bed_or_hed' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.id_number.required' => 'ID Number is required',
            '*.id_number.unique' => 'ID Number must be unique',
            '*.first_name.required' => 'First Name is required',
            '*.last_name.required' => 'Last Name is required',
            '*.bed_or_hed.required' => 'BED or HED is required',
        ];
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }
}