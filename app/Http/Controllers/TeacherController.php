<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display the teacher management view.
     */
    public function showUploadForm()
    {
        $teachers = Teacher::all();
        $programHeads = Teacher::where('role', 'program_head')->pluck('course')->toArray();
        Log::info('Teacher:', $teachers->toArray());
        return view('admin.enrolledteachers', compact('teachers', 'programHeads'));
    }

    /**
     * Handle the import of teachers via Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        DB::beginTransaction();

        try {
            $import = new TeacherImport();
            Excel::import($import, $request->file('file'));

            // Get the imported ID numbers and Program Heads
            $importedIdNumbers = $import->getImportedIdNumbers();
            $importedProgramHeads = $import->getProgramHeadsImported();

            // Update the 'approved' status in the 'users' table for the imported teachers
            $users = User::whereIn('id_number', $importedIdNumbers)->get();

            foreach ($users as $user) {
                $user->approved = true;
                $user->save();
            }

            // Validate Program Head constraints
            $errors = [];

            // Fetch existing Program Heads excluding those being imported
            $existingProgramHeads = Teacher::where('role', 'program_head')
                ->whereNotIn('id_number', $importedIdNumbers)
                ->pluck('course')
                ->toArray();

            // Check if any imported Program Head conflicts with existing ones
            foreach ($importedProgramHeads as $course) {
                if (in_array($course, $existingProgramHeads)) {
                    $errors[] = "A Program Head for the course '{$course}' already exists.";
                }
            }

            // Check for multiple Program Heads for the same course within the import file
            $importedProgramHeadsCount = array_count_values($importedProgramHeads);
            foreach ($importedProgramHeadsCount as $course => $count) {
                if ($count > 1) {
                    $errors[] = "Multiple Program Heads found for the course '{$course}' in the uploaded file. Only one Program Head per course is allowed.";
                }
            }

            if (!empty($errors)) {
                DB::rollBack(); // Undo the import
                return response()->json(['success' => false, 'errors' => $errors]);
            }

            DB::commit(); // Finalize the import

            $teachers = Teacher::all();

            return response()->json(['success' => true, 'message' => 'Teachers imported successfully.', 'teachers' => $teachers]);
        } catch (ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing teachers: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the teachers.']]);
        }
    }

    /**
     * Toggle the approval status of a teacher.
     */
    public function toggleApproval(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        // Validate the 'approved' input
        $validator = Validator::make($request->all(), [
            'approved' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        // Update approval status
        $teacher->approved = $request->input('approved');
        $teacher->save();

        // Sync the approval status with the corresponding user account
        $user = User::where('id_number', $teacher->id_number)->first();
        if ($user) {
            $user->approved = $teacher->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Teacher approval status updated successfully.', 'teacher' => $teacher]);
    }

    /**
     * Fetch all enrolled teachers.
     */
    public function enrolledTeachers()
    {
        $teachers = Teacher::all();
        return response()->json($teachers);
    }

    /**
     * Add a late teacher manually.
     */
    
    /**
     * Download the teacher Excel template.
     */
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

    /**
     * Edit a teacher's details.
     */
    public function edit(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        // Validate the input
        $validator = Validator::make($request->all(), [
            'id_number'  => 'required|string|max:7|unique:teacher,id_number,' . $teacher->id,
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'bed_or_hed' => 'required|string|in:BED,HED',
            'course'     => 'required|string|max:255',
            'role'       => 'required|string|in:teacher,program_head',
            'email'      => 'nullable|email|unique:users,email,' . ($teacher->user->id ?? 'NULL'),
            'profile_picture' => 'nullable|string|max:255',
        ], [
            'id_number.unique' => 'ID Number must be unique.',
            'bed_or_hed.in'    => 'Invalid Department selected.',
            'role.in'          => 'Invalid role selected.',
            'email.email'      => 'The email must be a valid email address.',
            'email.unique'     => 'The email has already been taken.',
            'profile_picture.string' => 'Profile picture must be a valid path or URL.',
            'profile_picture.max' => 'Profile picture path is too long.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        // Set course and role based on department
        $bedOrHed = strtoupper(trim($request->input('bed_or_hed')));
        $course = $bedOrHed === 'BED' ? 'Elementary' : ucfirst(trim($request->input('course')));
        $role = $bedOrHed === 'BED' ? 'teacher' : strtolower(trim($request->input('role')));

        // If role is program_head, ensure only one per course
        if ($role === 'program_head') {
            $existingProgramHead = Teacher::where('course', $course)
                ->where('role', 'program_head')
                ->where('id', '!=', $teacher->id)
                ->first();
            if ($existingProgramHead) {
                return response()->json([
                    'success' => false,
                    'errors' => ["The course '{$course}' already has a Program Head assigned."],
                ]);
            }
        }

        // Update teacher details
        $teacher->id_number = strtoupper(trim($request->input('id_number')));
        $teacher->first_name = ucfirst(trim($request->input('first_name')));
        $teacher->last_name = ucfirst(trim($request->input('last_name')));
        $teacher->bed_or_hed = $bedOrHed;
        $teacher->course = $course;
        $teacher->role = $role;

        // Handle profile picture
        if (isset($request->profile_picture) && $request->profile_picture) {
            $profilePicture = trim($request->profile_picture);
            $profilePicPath = null;

            // 1) If it’s a valid URL, attempt to download
            if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
                try {
                    $imageContents = @file_get_contents($profilePicture);
                    if ($imageContents === false) {
                        throw new \Exception("Unable to download image from URL: $profilePicture");
                    }
                    // Extract extension
                    $extension = pathinfo(parse_url($profilePicture, PHP_URL_PATH), PATHINFO_EXTENSION);
                    if (!$extension) {
                        $extension = 'jpg'; // Fallback if no extension
                    }
                    $imageName = Str::uuid() . '.' . strtolower($extension);

                    $pathToStore = 'profile_pictures/' . $imageName;
                    // Store in public disk
                    Storage::disk('public')->put($pathToStore, $imageContents);

                    $profilePicPath = 'storage/' . $pathToStore;
                } catch (\Exception $e) {
                    Log::error('Error downloading profile picture: ' . $e->getMessage());
                    $profilePicPath = null;
                }
            }
            // 2) Else if it’s a local file path and the server can access it
            elseif (file_exists($profilePicture)) {
                // For example: "C:\Users\eduar\Downloads\aa.png" or "/var/www/images/aa.png"
                try {
                    $extension = pathinfo($profilePicture, PATHINFO_EXTENSION);
                    if (!$extension) {
                        $extension = 'jpg';
                    }
                    $imageName = Str::uuid() . '.' . strtolower($extension);

                    $pathToStore = 'profile_pictures/' . $imageName;

                    // Copy local file into storage/app/public/profile_pictures
                    Storage::disk('public')->putFileAs(
                        'profile_pictures',
                        new File($profilePicture),
                        $imageName
                    );

                    $profilePicPath = 'storage/' . $pathToStore;
                } catch (\Exception $e) {
                    Log::error('Error copying local profile picture: ' . $e->getMessage());
                    $profilePicPath = null;
                }
            } else {
                // Not a valid URL, and local path does not exist on server
                Log::warning("Profile picture field is neither a valid URL nor an existing file path: {$profilePicture}");
            }

            if ($profilePicPath) {
                // Delete old profile picture if exists
                if ($teacher->profile_picture &&
                    Storage::disk('public')->exists(str_replace('storage/', '', $teacher->profile_picture))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $teacher->profile_picture));
                }
                $teacher->profile_picture = $profilePicPath;
            }
        }

        $teacher->approved = true; // Automatically approve upon edit
        $teacher->save();

        // Sync with user table if necessary
        $user = User::where('id_number', $teacher->id_number)->first();
        if ($user) {
            $user->email = isset($request->email) ? trim($request->input('email')) : $user->email;
            $user->first_name = $teacher->first_name;
            $user->last_name = $teacher->last_name;
            $user->role = $teacher->role; // Ensure role is set as per teacher's role
            $user->approved = true;
            $user->save();
        } else {
            // Create a new user if not exists & if email is provided
            if (isset($request->email) && $request->email) {
                $password = Str::random(16);
                $user = User::create([
                    'id_number' => $teacher->id_number,
                    'email'     => trim($request->input('email')),
                    'password'  => Hash::make($password),
                    'role'      => $teacher->role,
                    'approved'  => true,
                ]);
                Password::sendResetLink(['email' => $user->email]);
                Log::info("Password reset link sent to user: {$user->email}");
            }
        }

        return response()->json(['success' => true, 'message' => 'Teacher details updated successfully.', 'teacher' => $teacher]);
    }

    /**
     * Bulk toggle approval status for multiple teachers.
     */
    public function bulkToggleApproval(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:teacher,id',
        ]);

        $teachers = Teacher::whereIn('id', $request->ids)->get();

        foreach ($teachers as $teacher) {
            $teacher->approved = !$teacher->approved;
            $teacher->save();

            $user = User::where('id_number', $teacher->id_number)->first();
            if ($user) {
                $user->approved = $teacher->approved;
                $user->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Approvals have been toggled successfully.']);
    }

    /**
     * Delete a teacher and corresponding user account.
     */
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

        Log::info("Deleted teacher ID: {$teacher->id_number}");

        return response()->json(['success' => true, 'message' => 'Teacher deleted successfully.']);
    }

    /**
     * Show a specific teacher's details.
     */
    public function show($id)
    {
        $teacher = Teacher::with('user')->find($id);

        if ($teacher) {
            return response()->json(['success' => true, 'teacher' => $teacher]);
        }

        return response()->json(['success' => false, 'message' => 'Teacher not found.'], 404);
    }
}
