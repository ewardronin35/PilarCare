<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class StudentController extends Controller
{
    public function showUploadForm()
    {
        $students = Student::all();
        Log::info('Students:', $students->toArray());
        return view('admin.enrolledstudents', compact('students'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new StudentsImport;
            Excel::import($import, $request->file('file'));

            $students = Student::all();
            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Students imported successfully.', 'students' => $students]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing students: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the students.']]);
        }
    }

    public function toggleApproval(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->approved = $request->input('approved');
        $student->save();

        $user = User::where('id_number', $student->id_number)->first();
        if ($user) {
            $user->approved = $student->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Student status updated successfully.', 'student' => $student]);
    }

    public function enrolledStudents()
    {
        $students = Student::all();
        return response()->json($students);
    }
}
