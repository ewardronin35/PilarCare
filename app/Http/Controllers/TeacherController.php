<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;

class TeacherController extends Controller
{
    public function showUploadForm()
    {
        $teachers = Teacher::all();
        Log::info('Teacher:', $teachers->toArray());
        return view('admin.enrolledteachers', compact('teachers'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
    
        try {
            $import = new TeacherImport(); // Use TeacherImport (singular)
            Excel::import($import, $request->file('file'));
    
            $teachers = Teacher::all();
            $duplicates = $import->getDuplicates(); // Handle duplicates if necessary
    
            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }
    
            return response()->json(['success' => true, 'message' => 'Teachers imported successfully.', 'teachers' => $teachers]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing teachers: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the teachers.']]);
        }
    }
    
    public function toggleApproval(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->approved = $request->input('approved');
        $teacher->save();

        $user = User::where('id_number', $teacher->id_number)->first();
        if ($user) {
            $user->approved = $teacher->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Teacher status updated successfully.', 'teacher' => $teacher]);
    }

    public function enrolledTeachers()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }
    public function addlateTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'late-id_number' => 'required|string|max:255',
            'late-first_name' => 'required|string|max:255',
            'late-last_name' => 'required|string|max:255',
            'late-bed_or_hed' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        $teacher = new Teacher();
        $teacher->id_number = $request->input('late-id_number');
        $teacher->first_name = $request->input('late-first_name');
        $teacher->last_name = $request->input('late-last_name');
        $teacher->grade_or_course = $request->input('late-bed_or_hed');
        $teacher->approved = false;
        $teacher->save();

        Log::info('Late teacher added:', $teacher->toArray());

        $teachers = Teacher::all();

        return response()->json([
            'success' => true,
            'message' => 'Late Teacher added successfully.',
            'teacher' => $students,
        ]);
    }

    public function downloadTeacher()
    {
        $filePath = 'templates/teacher_template.xlsx';

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
        return Storage::download($filePath, 'teacher_template.xlsx');
    }
    public function edit(Request $request, $id)
{
    $teacher = Teacher::findOrFail($id);
    
    // Validate the input
    $request->validate([
        'id_number' => 'required|max:10',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'bed_or_hed' => 'required|string|max:255'  // Use bed_or_hed for validation
    ]);

    // Update teacher details
    $teacher->id_number = $request->input('id_number');
    $teacher->first_name = $request->input('first_name');
    $teacher->last_name = $request->input('last_name');
    $teacher->bed_or_hed = $request->input('bed_or_hed'); // Update 'bed_or_hed'
    $teacher->save();

    // Sync with user table if necessary
    $user = User::where('id_number', $teacher->id_number)->first();
    if ($user) {
        $user->first_name = $teacher->first_name;
        $user->last_name = $teacher->last_name;
        $user->save();
    }

    return response()->json(['success' => true, 'message' => 'Teacher details updated successfully.', 'teacher' => $teacher]);
}

public function delete($id)
{
    $teacher = Teacher::findOrFail($id);

    // Also delete the user if exists
    $user = User::where('id_number', $teacher->id_number)->first();
    if ($user) {
        $user->delete();
    }

    // Delete the teacher
    $teacher->delete();

    return response()->json(['success' => true, 'message' => 'Teacher deleted successfully.']);
}

public function show($id)
{
    // Find the teacher by ID
    $teacher = Teacher::find($id);

    // Check if the teacher exists
    if (!$teacher) {
        return response()->json(['message' => 'Teacher not found'], 404);
    }

    // Return the teacher data
    return response()->json($teacher);
}

}
