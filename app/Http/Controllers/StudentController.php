<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\Information;

use App\Models\PhysicalExamination;
use App\Models\MedicalRecord;
use App\Models\HealthExamination;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Make sure to import Carbon
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PDF;

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
            Log::info('Students after import:', $students->toArray());
    
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
    public function delete($id)
    {
        try {
            // Find the student in the students table
            $student = Student::findOrFail($id);
    
            // Find and delete the corresponding user in the users table
            $user = User::where('id_number', $student->id_number)->first();
            if ($user) {
                $user->delete();
                Log::info('User deleted:', ['id_number' => $student->id_number]);
            } else {
                Log::warning('No matching user found for id_number:', ['id_number' => $student->id_number]);
            }
    
            // Delete the student from the students table
            $student->delete();
            Log::info('Student deleted:', ['id' => $id]);
    
            return response()->json(['success' => true, 'message' => 'Student and corresponding user deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting student or user: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem deleting the student and user.']]);
        }
    }
    
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|string|max:255|unique:students,id_number,'.$id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'grade_or_course' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }
    
        try {
            $student = Student::findOrFail($id);
            $student->id_number = $request->input('id_number');
            $student->first_name = $request->input('first_name');
            $student->last_name = $request->input('last_name');
            $student->grade_or_course = $request->input('grade_or_course');
            $student->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully.',
                'student' => $student,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'errors' => ['There was a problem updating the student.']]);
        }
    }    

    public function toggleApproval(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->approved = $request->input('approved');
        $student->save();
        Log::info('Student updated:', $student->toArray());

        $user = User::where('id_number', $student->id_number)->first();
        if ($user) {
            $user->approved = $student->approved;
            $user->save();
            Log::info('User updated:', $user->toArray());
        } else {
            Log::warning('No matching user found for student id_number:', ['id_number' => $student->id_number]);
        }

        return response()->json(['success' => true, 'message' => 'Student status updated successfully.', 'student' => $student]);
    }

    public function enrolledStudents()
    {
        $students = Student::all();
        Log::info('Enrolled students fetched:', $students->toArray());
        return response()->json($students);
    }

    public function addLateStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'late-id_number' => 'required|string|max:255',
            'late-first_name' => 'required|string|max:255',
            'late-last_name' => 'required|string|max:255',
            'late-grade_or_course' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        $student = new Student();
        $student->id_number = $request->input('late-id_number');
        $student->first_name = $request->input('late-first_name');
        $student->last_name = $request->input('late-last_name');
        $student->grade_or_course = $request->input('late-grade_or_course');
        $student->approved = false;
        $student->save();

        Log::info('Late student added:', $student->toArray());

        $students = Student::all();

        return response()->json([
            'success' => true,
            'message' => 'Late student added successfully.',
            'students' => $students,
        ]);
    }
    public function getStudentInfo($id_number)
    {
        // Fetch data from the students table
        $student = DB::table('students')
            ->where('id_number', $id_number)
            ->first(['first_name', 'last_name', 'grade_or_course']);
    
        // Fetch data from the information table
        $information = DB::table('information')
            ->where('id_number', $id_number)
            ->first(['birthdate']);
    
        if ($student) {
            // Calculate age if birthdate is available
            $age = null;
            if ($information && $information->birthdate) {
                $birthdate = Carbon::parse($information->birthdate);
                $age = $birthdate->age;
            }
    
            return response()->json([
                'success' => true,
                'student' => [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'grade_section' => $student->grade_or_course, // Make sure this field is correct
                ],
                'information' => [
                    'birthdate' => $information->birthdate ?? null,
                    'age' => $age,
                ]
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    
    public function exportStudents()
    {
        $students = Student::all();
        
        return (new FastExcel($students))->download('students.xlsx', function ($student) {
            return [
                'ID' => $student->id,
                'First Name' => $student->first_name,
                'Last Name' => $student->last_name,
                'Grade/Course' => $student->grade_or_course,
            ];
        });
    }
    public function downloadStudent()
    {
        $filePath = 'templates/student_template.xlsx';

        if (Storage::exists($filePath)) {
            try {
                $fileSize = Storage::size($filePath);
                Log::info('File size: ' . $fileSize);
            } catch (\Exception $e) {
                Log::error('Error retrieving file size: ' . $e->getMessage());
            }
        } else {
            Log::error('File not found: ' . $filePath);
        }
        
        // If file exists, proceed with download
        return Storage::download($filePath, 'student_template.xlsx');
    }
    public function show($id)
{
    $student = Student::find($id);
    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    return response()->json($student);
}
public function showUpdateRecords($id_number)
{
    $user = Auth::user(); // This fetches the currently logged-in user
    $physicalExam = PhysicalExamination::where('id_number', $id_number)->first(); // Fetch the physical exam based on id_number
    if (!$physicalExam) {
        return redirect()->back()->with('error', 'Physical examination not found.');
    }

    return view('student.updaterecords', compact('user', 'physicalExam'));
}
public function showMedicalRecords()
{
    $user = Auth::user();
  
     $information = Information::where('id_number', $user->id_number)->first();
        $medicalRecord = MedicalRecord::with('medicineIntake') // Eager load medicineIntakes
            ->where('id_number', $user->id_number)
            ->first();
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get();
        $physicalExaminations = PhysicalExamination::where('id_number', $user->id_number)->get(); // Fetch all examinations
        $healthExamination = HealthExamination::where('id_number', $user->id_number)->first();
    
        $healthExaminationPictures = HealthExamination::where('id_number', $user->id_number)
            ->select('school_year', 'health_examination_picture', 'xray_picture', 'lab_result_picture')
            ->get();
    
        $name = $user->first_name . ' ' . $user->last_name;
        $age = $information ? Carbon::parse($information->birthdate)->age : null;
    
    // Other data to pass to the view if needed
    return view('student.medical-record', compact('user', 'information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations', 'healthExaminationPictures'));
}
}
