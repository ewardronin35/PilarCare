<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class TeacherController extends Controller
{
    public function showUploadForm()
    {
        $teachers = Teacher::all();
        Log::info('Teachers:', $teachers->toArray());
        return view('admin.enrolledteachers', compact('teachers'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new TeachersImport;
            Excel::import($import, $request->file('file'));

            $teachers = Teacher::all();
            $duplicates = $import->getDuplicates();

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
}
