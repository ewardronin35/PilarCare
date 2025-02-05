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
use Carbon\Carbon;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PDF;

class StudentController extends Controller
{
    /**
     * Display the upload form and list of students.
     */
    public function showUploadForm()
    {
        $students = Student::all();
        Log::info('Displaying all students for upload form.', ['count' => $students->count()]);
        return view('admin.enrolledstudents', compact('students'));
    }

    /**
     * Import students from an Excel or CSV file.
     */
    public function import(Request $request)
    {
        // Validate the uploaded file format and additional fields
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv',
            'grade_or_course' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Import validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        $gradeOrCourse = strtoupper(trim($request->input('grade_or_course')));

        DB::beginTransaction();

        try {
            // Instantiate the import class with only the grade_or_course
            $import = new StudentsImport($gradeOrCourse);

            // Run the import
            Excel::import($import, $request->file('file'));

            // Deactivate missing enrollments, if any
            $import->deactivateMissingEnrollments();

            // Commit the database changes
            DB::commit();

            // Gather information about skipped rows
            $skippedRows    = $import->failures(); // Rows that failed validation
            $duplicateRows  = $import->getDuplicates(); // Duplicates found
            $mismatchErrors = $import->getMismatchErrors();

            Log::info('Students imported and missing students deactivated successfully.', [
                'grade_or_course' => $gradeOrCourse,
                'imported_count'  => count($import->getImportedStudentIdNumbers()),
                'skipped_rows'    => count($skippedRows),
                'duplicate_rows'  => count($duplicateRows),
                'mismatch_errors' => count($mismatchErrors),
            ]);

            // Prepare a response message
            $responseMessage = 'Students imported successfully.';
            if (count($skippedRows) > 0) {
                $responseMessage .= ' Some rows were skipped due to validation errors.';
            }
            if (count($duplicateRows) > 0) {
                $responseMessage .= ' Duplicate ID Numbers were found and skipped.';
            }
            if (count($mismatchErrors) > 0) {
                $responseMessage .= ' There were mismatches in Grade/Course entries.';
            }

            return response()->json([
                'success'         => true,
                'message'         => $responseMessage,
                'imported_count'  => count($import->getImportedStudentIdNumbers()),
                'skipped_rows'    => $skippedRows,
                'duplicate_rows'  => $duplicateRows,
                'mismatch_errors' => $mismatchErrors,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->handleImportValidationException($e);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exception during student import: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the students.']], 500);
        }
    }

    /**
     * Handle validation exceptions during import.
     */
    private function handleImportValidationException(ValidationException $e)
    {
        // Extract all error messages
        $errorMessages = $e->validator->errors()->all();

        // Log each error message
        foreach ($errorMessages as $error) {
            Log::warning('Import validation failure.', ['error' => $error]);
        }

        // Return the error messages as a JSON response
        return response()->json(['success' => false, 'errors' => $errorMessages], 422);
    }

    /**
     * Delete a student and their corresponding user account.
     */
    public function delete($id_number)
    {
        try {
            $student = Student::where('id_number', $id_number)->firstOrFail();
            $user    = User::where('id_number', $student->id_number)->first();

            if ($user) {
                $user->delete();
                Log::info('User account deleted for student.', ['id_number' => $student->id_number]);
            } else {
                Log::warning('No corresponding user account found for student.', ['id_number' => $student->id_number]);
            }

            $student->delete();
            Log::info('Student record deleted.', ['student_id_number' => $id_number]);

            return response()->json(['success' => true, 'message' => 'Student and corresponding user deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting student or user: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem deleting the student and user.']], 500);
        }
    }

    /**
     * Edit student details.
     */
    public function edit(Request $request, $id_number)
    {
        // Fetch the existing student
        $student = Student::where('id_number', $id_number)->firstOrFail();

        // Validate the input
        $validator = Validator::make($request->all(), [
            // 'id_number' is read-only and not validated for uniqueness
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'grade_or_course' => 'required|string|max:255',
            'section'         => 'required|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'gender'          => 'required|string|in:Male,Female,Other',
            'father_name'     => 'required|string|max:255',
            'mother_name'     => 'required|string|max:255',
            'contact_number'  => 'required|digits_between:7,20',
            'address'         => 'required|string|max:500',
            'emergency_contact' => 'required|digits_between:7,20',
            'is_scholar'      => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            Log::warning('Edit validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
        }

        try {
            $student->first_name       = trim($request->input('first_name'));
            $student->last_name        = trim($request->input('last_name'));
            $student->grade_or_course  = strtoupper(trim($request->input('grade_or_course')));
            $student->section          = strtoupper(trim($request->input('section')));
            $student->education_level  = trim($request->input('education_level'));
            $student->gender           = ucfirst(strtolower(trim($request->input('gender'))));
            $student->father_name      = trim($request->input('father_name'));
            $student->mother_name      = trim($request->input('mother_name'));
            $student->contact_number   = trim($request->input('contact_number'));
            $student->address          = trim($request->input('address'));
            $student->emergency_contact= trim($request->input('emergency_contact'));
            $student->is_scholar       = $request->has('is_scholar') ? $request->input('is_scholar') : $student->is_scholar;
            $student->save();

            Log::info('Student details updated.', [
                'student_id_number' => $id_number,
                'updated_data'      => $student->toArray()
            ]);

            return response()->json(['success' => true, 'message' => 'Student updated successfully.', 'student' => $student]);
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem updating the student.']]);
        }
    }

    /**
     * Toggle enrollment status for a student.
     */
    public function toggleEnrollment(Request $request, $id_number)
    {
        $validator = Validator::make($request->all(), [
            'is_enrolled'     => 'required|boolean',
            'semester'        => 'required|string',
            'school_year'     => 'required|string|regex:/^\d{4}-\d{4}$/',
            'grade_or_course' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Toggle enrollment validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        try {
            $student = Student::where('id_number', $id_number)->firstOrFail();

            // Update enrollment record for the specified semester and school year
            $enrollment = Enrollment::updateOrCreate(
                [
                    'student_id'  => $student->id_number,
                    'semester'    => strtoupper(trim($request->input('semester'))),
                    'school_year' => strtoupper(trim($request->input('school_year'))),
                ],
                [
                    'is_enrolled'     => $request->input('is_enrolled'),
                    'grade_or_course' => strtoupper(trim($request->input('grade_or_course'))),
                ]
            );

            // Update the student's overall enrollment status and approval
            if ($enrollment->is_enrolled) {
                $student->update([
                    'enrollment_status' => 'active',
                    'approved'          => 1, // Approve the student
                ]);
            } else {
                // Check if the student is enrolled in any other active semesters
                $activeEnrollments = Enrollment::where('student_id', $student->id_number)
                    ->where('is_enrolled', true)
                    ->count();

                if ($activeEnrollments === 0) {
                    $student->update([
                        'enrollment_status' => 'inactive',
                        'approved'          => 0, // Disapprove the student
                    ]);
                }
            }

            // Update the corresponding user's approval status
            $user = User::where('id_number', $student->id_number)->first();
            if ($user) {
                $user->approved = $student->approved;
                $user->save();
                Log::info('User approval status updated.', [
                    'user_id'  => $user->id,
                    'approved' => $user->approved
                ]);
            } else {
                Log::warning('No corresponding user found for student.', ['student_id_number' => $id_number]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Student enrollment status updated successfully.',
                'student' => $student
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error toggling enrollment status: Student not found.', ['student_id_number' => $id_number]);
            return response()->json(['success' => false, 'errors' => ['Student not found.']], 404);
        } catch (\Exception $e) {
            Log::error('Error toggling enrollment status: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem updating the enrollment status.']], 500);
        }
    }

    /**
     * Show specific student information by ID Number.
     */
    public function show($id_number)
    {
        // Eager load the 'user' relationship
        $student = Student::with('user')->where('id_number', $id_number)->first();
    
        if (!$student) {
            Log::warning('Student not found.', ['student_id_number' => $id_number]);
            return response()->json(['error' => 'Student not found'], 404);
        }
    
        Log::info('Fetching student data.', ['student_id_number' => $id_number]);
    
        return response()->json([
            'student' => $student,
            'email' => $student->user ? $student->user->email : 'N/A'
        ]);
    }

    /**
     * Enroll a student for a specific semester and school year.
     */
    public function enrollStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_number'       => 'required|exists:students,id_number',
            'semester'        => 'required|string',
            'school_year'     => 'required|string|regex:/^\d{4}-\d{4}$/',
            'grade_or_course' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Enroll student validation failed.', ['errors' => $validator->errors()]);
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        try {
            $student = Student::where('id_number', $request->input('id_number'))->firstOrFail();

            // Enroll or update the enrollment for the student in the specified semester and school year
            $enrollment = Enrollment::updateOrCreate(
                [
                    'student_id'  => $student->id_number,
                    'semester'    => strtoupper(trim($request->input('semester'))),
                    'school_year' => strtoupper(trim($request->input('school_year'))),
                ],
                [
                    'is_enrolled'     => true,
                    'grade_or_course' => strtoupper(trim($request->input('grade_or_course'))),
                ]
            );

            // Update the student's overall enrollment status and approval
            if ($enrollment->is_enrolled) {
                $student->update([
                    'enrollment_status' => 'active',
                    'approved'          => 1, // Approve the student
                ]);
            } else {
                // Check if the student is enrolled in any other active semesters
                $activeEnrollments = Enrollment::where('student_id', $student->id_number)
                    ->where('is_enrolled', true)
                    ->count();

                if ($activeEnrollments === 0) {
                    $student->update([
                        'enrollment_status' => 'inactive',
                        'approved'          => 0, // Disapprove the student
                    ]);
                }
            }

            // Update the corresponding user's approval status
            $user = User::where('id_number', $student->id_number)->first();
            if ($user) {
                $user->approved = $student->approved;
                $user->save();
                Log::info('User approval status updated.', [
                    'user_id'  => $user->id,
                    'approved' => $user->approved
                ]);
            } else {
                Log::warning('No corresponding user found for student.', ['student_id_number' => $student->id_number]);
            }

            return response()->json(['success' => true, 'message' => 'Student enrolled successfully.']);
        } catch (\Exception $e) {
            Log::error('Error enrolling student: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem enrolling the student.']], 500);
        }
    }

    /**
     * Download the student template Excel file.
     */
    public function downloadStudent()
    {
        $filePath = 'templates/student_template.xlsx';

        if (Storage::exists($filePath)) {
            try {
                $fileSize = Storage::size($filePath);
                Log::info('Template file size retrieved.', ['file_path' => $filePath, 'size' => $fileSize]);
            } catch (\Exception $e) {
                Log::error('Error retrieving file size: ' . $e->getMessage());
                return response()->json(['success' => false, 'errors' => ['Error retrieving the template file.']], 500);
            }
        } else {
            Log::error('Template file not found.', ['file_path' => $filePath]);
            return response()->json(['success' => false, 'errors' => ['Template file not found.']], 404);
        }

        return Storage::download($filePath, 'student_template.xlsx');
    }

    /**
     * Show medical records for the logged-in user.
     */
    public function showMedicalRecords()
    {
        $user              = Auth::user();
        $information       = Information::where('id_number', $user->id_number)->first();
        $medicalRecord     = MedicalRecord::with('medicineIntake')->where('id_number', $user->id_number)->first();
        $medicalRecords    = MedicalRecord::where('id_number', $user->id_number)->get();
        $physicalExaminations = PhysicalExamination::where('id_number', $user->id_number)->get();
        $healthExamination = HealthExamination::where('id_number', $user->id_number)->first();
        $healthExaminationPictures = HealthExamination::where('id_number', $user->id_number)
            ->select('school_year', 'health_examination_picture', 'xray_picture', 'lab_result_picture')
            ->get();

        $name = $user->first_name . ' ' . $user->last_name;
        $age  = $information ? Carbon::parse($information->birthdate)->age : null;

        return view('student.medical-record', compact(
            'user',
            'information',
            'name',
            'age',
            'healthExamination',
            'medicalRecord',
            'medicalRecords',
            'physicalExaminations',
            'healthExaminationPictures'
        ));
    }

    /**
     * Retrieve enrolled students based on filters.
     */
    public function enrolledStudents(Request $request)
    {
        $educationLevel = $request->input('education_level', null);
        $semester       = strtoupper(trim($request->input('semester', null)));
        $schoolYear     = strtoupper(trim($request->input('school_year', null)));
        $gradeOrCourse  = strtoupper(trim($request->input('grade_or_course', null))); // New filter

        // Query the students
        $query = Student::query();

        // Optional filters based on form input
        if ($educationLevel) {
            $query->where('education_level', $educationLevel);
        }
        if ($gradeOrCourse) {
            $query->where('grade_or_course', $gradeOrCourse); // Apply grade/course filter
        }
        if ($semester && $schoolYear) {
            $query->whereHas('enrollments', function($q) use ($semester, $schoolYear) {
                $q->where('semester', $semester)
                  ->where('school_year', $schoolYear)
                  ->where('is_enrolled', true);
            });
        } elseif ($semester) {
            $query->whereHas('enrollments', function($q) use ($semester) {
                $q->where('semester', $semester)
                  ->where('is_enrolled', true);
            });
        } elseif ($schoolYear) {
            $query->whereHas('enrollments', function($q) use ($schoolYear) {
                $q->where('school_year', $schoolYear)
                  ->where('is_enrolled', true);
            });
        }

        // Eager load enrollments with proper ordering to get the latest first
        $query->with(['enrollments' => function($q) use ($semester, $schoolYear) {
            if ($semester && $schoolYear) {
                $q->where('semester', $semester)
                  ->where('school_year', $schoolYear);
            } elseif ($semester) {
                $q->where('semester', $semester);
            } elseif ($schoolYear) {
                $q->where('school_year', $schoolYear);
            }
            // Order by school_year descending and then semester
            $q->orderBy('school_year', 'desc')
              ->orderByRaw("
                  CASE 
                      WHEN semester = 'FIRST SEMESTER' THEN 1
                      WHEN semester = 'SECOND SEMESTER' THEN 2
                      WHEN semester = 'SUMMER' THEN 3
                      ELSE 4
                  END DESC
              ");
        }]);

        $students = $query->get();

        // Transform the data to include enrollment status and section
        $students = $students->map(function($student) {
            $enrollment = $student->enrollments->first(); // should be the latest enrollment
            return [
                'id_number'        => $student->id_number,
                'first_name'       => $student->first_name,
                'last_name'        => $student->last_name,
                'grade_or_course'  => $student->grade_or_course,
                'section'          => $student->section,
                'education_level'  => $student->education_level,
                'is_enrolled'      => $enrollment ? $enrollment->is_enrolled : false,
                'gender'           => $student->gender,
                'semester'         => $enrollment ? $enrollment->semester : null,
                'school_year'      => $enrollment ? $enrollment->school_year : null,
            ];
        });

        return response()->json($students);
    }
}
