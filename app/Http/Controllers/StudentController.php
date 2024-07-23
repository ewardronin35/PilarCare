<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student; // Ensure you have a Student model
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function showUploadForm()
    {
        return view('admin.enrolledstudents'); // Assuming the view for uploading students is admin.uploadstudents
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Students imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was a problem importing the students.');
        }
    }

    public function enrolledStudents()
    {
        $students = Student::all(); // Fetch all students from the database
        return view('admin.enrolledstudents', compact('students')); // Pass the students to the view
    }
}
