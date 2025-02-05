<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class StudentsImport implements 
    ToModel,
    WithValidation,
    WithHeadingRow,
    WithEvents,
    SkipsEmptyRows,
    SkipsOnFailure
{
    use SkipsFailures;

    protected $duplicates                = [];
    protected $importedStudentIdNumbers  = [];
    protected $gradeOrCourse;
    protected $mismatchCount             = 0;
    protected $mismatchErrors           = [];
    protected $seenIdNumbers            = [];
    protected $fileDuplicates           = [];

    /**
     * Constructor to accept grade/course.
     *
     * @param string $gradeOrCourse
     */
    public function __construct($gradeOrCourse)
    {
        $this->gradeOrCourse = strtoupper(trim($gradeOrCourse));
    }

    /**
     * We can map each row if needed, but here we just return as-is.
     */
    public function mapRow(array $row): array
    {
        return $row;
    }

    /**
     * Register events for the import.
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                // Prepare error messages
                $errorMessages = [];

                // Handle file duplicates
                if (!empty($this->fileDuplicates)) {
                    Log::warning('Duplicate ID Numbers found in the import file.', ['duplicates' => $this->fileDuplicates]);
                    $errorMessages[] = 'There are duplicate ID Numbers in the import file.';
                }

                // Handle grade_or_course mismatches
                if ($this->mismatchCount > 0) {
                    $errorMessage = "Import failed due to mismatched Grade/Course entries:\n" 
                                  . implode("\n", $this->mismatchErrors);
                    Log::warning($errorMessage);
                    $errorMessages[] = $errorMessage;
                }

                if (!empty($errorMessages)) {
                    throw ValidationException::withMessages([
                        'duplicates' => $errorMessages,
                    ]);
                }
            },
        ];
    }

    /**
     * Create or update a student record and their enrollment.
     */
    public function model(array $row)
    {
        Log::info('Processing row', ['id_number' => $row['id_number']]);

        // Normalize fields
        $row = array_map('trim', $row);
        $row['id_number']       = strtoupper((string) $row['id_number']);
        $row['email']           = strtolower($row['email']);
        $row['gender']          = ucfirst(strtolower($row['gender']));
        $row['grade_or_course'] = strtoupper($row['grade_or_course']);
        $row['section']         = strtoupper($row['section'] ?? '');

        // Normalize birthdate
        if (isset($row['birthdate'])) {
            try {
                $row['birthdate'] = Carbon::parse($row['birthdate'])->format('Y-m-d');
            } catch (\Exception $e) {
                throw ValidationException::withMessages([
                    'birthdate' => "The birthdate format is invalid for ID Number {$row['id_number']}.",
                ]);
            }
        }

        // Skip row if 'id_number' is empty
        if (empty($row['id_number'])) {
            Log::warning('Skipping row due to empty id_number.', ['row' => $row]);
            return null;
        }

        // Check if normalized grade_or_course matches the one specified at import
        if ($this->normalizeGradeOrCourse($row['grade_or_course']) !== $this->gradeOrCourse) {
            $this->mismatchCount++;
            $this->mismatchErrors[] = "Row with ID Number {$row['id_number']} has mismatched Grade/Course: {$row['grade_or_course']}";

            Log::warning("Grade/Course mismatch for ID Number {$row['id_number']}. "
                ."Expected: {$this->gradeOrCourse}, Found: {$row['grade_or_course']}");
            return null;
        }

        // Check for duplicates within the same file
        $idNumber = $row['id_number'];
        if (in_array($idNumber, $this->seenIdNumbers)) {
            $this->fileDuplicates[] = "Duplicate ID Number '{$idNumber}' found in the import file.";
            Log::warning("Duplicate ID Number '{$idNumber}' found in the import file.");
            return null;
        } else {
            $this->seenIdNumbers[] = $idNumber;
        }

        // Handle profile_picture
        $profilePicture = $row['profile_picture'];
        Log::info('Processed row data', ['row' => $row]);

        // 1) If it's a URL, attempt to download
        if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
            try {
                $imageContents = @file_get_contents($profilePicture);
                if ($imageContents === false) {
                    throw new \Exception("Unable to download image from URL: {$profilePicture}");
                }

                // Determine extension
                $extension = pathinfo(parse_url($profilePicture, PHP_URL_PATH), PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                if (!in_array($extension, $allowedExtensions)) {
                    $extension = 'jpg'; // fallback
                }

                // Unique filename
                $filename = $idNumber . '_' . time() . '.' . $extension;

                // Store in public/profile_pictures
                Storage::disk('public')->put("profile_pictures/{$filename}", $imageContents);

                // Update the row
                $row['profile_picture'] = "storage/profile_pictures/{$filename}";
            } catch (\Exception $e) {
                throw ValidationException::withMessages([
                    'profile_picture' => "Failed to process profile picture for ID Number {$idNumber}: " 
                                       . $e->getMessage(),
                ]);
            }
        }
        // 2) If it's a local file path
        elseif (file_exists($profilePicture)) {
            try {
                $originalName = pathinfo($profilePicture, PATHINFO_BASENAME);
                $extension    = pathinfo($profilePicture, PATHINFO_EXTENSION);
                $extension    = strtolower($extension);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                if (!in_array($extension, $allowedExtensions)) {
                    $extension = 'jpg';
                }

                // Unique filename
                $filename = $idNumber . '_' . time() . '.' . $extension;

                // Copy to public/profile_pictures
                Storage::disk('public')->putFileAs('profile_pictures', new \Illuminate\Http\File($profilePicture), $filename);

                $row['profile_picture'] = "storage/profile_pictures/{$filename}";
            } catch (\Exception $e) {
                throw ValidationException::withMessages([
                    'profile_picture' => "Failed to process profile picture for ID Number {$idNumber}: " 
                                       . $e->getMessage(),
                ]);
            }
        } else {
            // Invalid path or URL
            throw ValidationException::withMessages([
                'profile_picture' => "The profile_picture field for ID Number {$idNumber} is neither "
                                    ."a valid URL nor an existing file path.",
            ]);
        }

        // Create or update the student
        try {
            $existingStudent = Student::where('id_number', $idNumber)->first();

            if ($existingStudent) {
                // Update existing
                $existingStudent->update([
                    'first_name'        => $row['first_name'],
                    'last_name'         => $row['last_name'],
                    'grade_or_course'   => $this->normalizeGradeOrCourse($row['grade_or_course']),
                    'gender'            => $row['gender'],
                    'father_name'       => $row['father_name'],
                    'mother_name'       => $row['mother_name'],
                    'contact_number'    => $row['contact_number'],
                    'address'           => $row['address'],
                    'emergency_contact' => $row['emergency_contact'],
                    'birthdate'         => $row['birthdate'],
                    'profile_picture'   => $row['profile_picture'],
                    'enrollment_status' => 'active',
                    'age'               => $row['age'],
                    'section'           => $row['section'],
                    // Keep existing education_level or re-derive if needed:
                    'education_level'   => $this->determineEducationLevel($row['grade_or_course']),
                ]);

                Log::info('Updated existing student', ['id_number' => $idNumber]);

                // Update or create the enrollment
                Enrollment::updateOrCreate(
                    [
                        'student_id'  => $idNumber,
                        'semester'    => strtoupper(trim($row['semester'])),
                        'school_year' => strtoupper(trim($row['school_year'])),
                    ],
                    [
                        'is_enrolled'     => true,
                        'grade_or_course' => $this->normalizeGradeOrCourse($row['grade_or_course']),
                    ]
                );

                // Mark ID Number as imported
                $this->importedStudentIdNumbers[] = $existingStudent->id_number;

                Log::info('Updated enrollment for existing student', ['id_number' => $idNumber]);

                // Create or update the corresponding user
                $this->createOrUpdateUser($existingStudent, $row);

                return $existingStudent;
            }

            // If new student, determine the correct education level
            $educationLevel = $this->determineEducationLevel($row['grade_or_course']);

            $student = Student::create([
                'id_number'         => $idNumber,
                'first_name'        => $row['first_name'],
                'last_name'         => $row['last_name'],
                'grade_or_course'   => $this->normalizeGradeOrCourse($row['grade_or_course']),
                'gender'            => $row['gender'],
                'father_name'       => $row['father_name'],
                'mother_name'       => $row['mother_name'],
                'contact_number'    => $row['contact_number'],
                'address'           => $row['address'],
                'emergency_contact' => $row['emergency_contact'],
                'birthdate'         => $row['birthdate'],
                'profile_picture'   => $row['profile_picture'],
                'enrollment_status' => 'active',
                'approved'          => 1, // auto-approve
                'education_level'   => $educationLevel,
                'age'               => $row['age'],
                'section'           => $row['section'],
            ]);

            Log::info('Created new student', ['id_number' => $idNumber]);

            // Create enrollment record
            $student->enrollments()->create([
                'semester'         => strtoupper(trim($row['semester'])),
                'school_year'      => strtoupper(trim($row['school_year'])),
                'is_enrolled'      => true,
                'grade_or_course'  => $this->normalizeGradeOrCourse($row['grade_or_course']),
            ]);

            // Mark ID as imported
            $this->importedStudentIdNumbers[] = $student->id_number;

            Log::info('Created enrollment for new student', ['id_number' => $idNumber]);

            // Create or update user
            $this->createOrUpdateUser($student, $row);

            return $student;
        } catch (\Exception $e) {
            Log::error('Exception in model() while processing row', [
                'id_number' => $row['id_number'] ?? 'N/A',
                'error'     => $e->getMessage(),
            ]);
            throw $e; // bubble up
        }
    }

    /**
     * Create or update a user account associated with the student.
     */
    protected function createOrUpdateUser(Student $student, array $row)
    {
        try {
            $user = User::where('id_number', $student->id_number)->first();

            if ($user) {
                // Update existing user
                $user->update([
                    'email'    => $row['email'],
                    'role'     => 'student',
                    'approved' => 1,
                ]);
                Password::sendResetLink(['email' => $user->email]);
                Log::info('Password reset link sent to user', ['email' => $user->email]);

                $user->email_verified_at = now();
                 $user->save();
                Log::info('Updated existing user account', [
                    'user_id'   => $user->id,
                    'id_number' => $user->id_number,
                ]);
            } else {
                // Create a new user
                $user = User::create([
                    'id_number' => $student->id_number,
                    'email'     => $row['email'],
                    'password'  => Hash::make(Str::random(16)), // random temp password
                    'role'      => 'Student',
                    'approved'  => 1,
                ]);
                Log::info('Created new user account', [
                    'user_id'   => $user->id,
                    'id_number' => $user->id_number,
                ]);

              
            Log::info('User email verified automatically after sending password reset link', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::error('Error creating/updating user', [
                'error'     => $e->getMessage(),
                'id_number' => $student->id_number,
                'email'     => $row['email'] ?? 'N/A',
            ]);
            throw $e;
        }
    }

    /**
     * Determine education level:
     * - GRADE 1..12 => BASIC
     * - BS* (BSBA, BSCS, BSIT, etc.) => TERTIARY
     */
    protected function determineEducationLevel($grade_or_course)
    {
        $grade_or_course = strtoupper(trim($grade_or_course));

        // If it matches "GRADE X" where X is 1..12 => BASIC
        if (preg_match('/^GRADE\s?(\d+)$/', $grade_or_course, $matches)) {
            $grade = (int) $matches[1];
            if ($grade >= 1 && $grade <= 12) {
                return 'BASIC';
            }
        }

        // If it matches one of the recognized "BS" courses => TERTIARY
        $tertiaryCourses = ['BSBA','BSCS','BSIT','BSTM','BSHM','BSN','BLIS','BEED'];
        if (in_array($grade_or_course, $tertiaryCourses)) {
            return 'TERTIARY';
        }

        return 'Unknown';
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'id_number' => [
                'required',
                'string',
                'size:7',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'grade_or_course' => 'required|string|max:255',
            'gender'          => ['required', 'string', Rule::in(['Male', 'Female', 'Other'])],
            'father_name'     => 'required|string|max:255',
            'mother_name'     => 'required|string|max:255',
            'contact_number'  => 'required|digits_between:7,20',
            'address'         => 'required|string|max:500',
            'emergency_contact' => 'required|digits_between:7,20',
            'birthdate'       => 'required|date',
            'profile_picture' => 'required|string|max:255', // path or URL
            'semester'        => 'required|string',
            'school_year'     => 'required|string|regex:/^\d{4}-\d{4}$/',
            'age'             => 'required|integer|min:1|max:150',

            // New for section:
            'section'         => 'required|string|max:255',
        ];
    }

    /**
     * Custom error messages.
     */
    public function customValidationMessages()
    {
        return [
            'id_number.required'       => 'The ID Number field is required.',
            'id_number.size'           => 'The ID Number must be exactly 7 characters long.',
            'email.required'           => 'The Email field is required.',
            'email.email'              => 'The Email must be a valid email address.',
            'email.unique'             => 'The Email has already been taken.',
            'first_name.required'      => 'The First Name field is required.',
            'last_name.required'       => 'The Last Name field is required.',
            'grade_or_course.required' => 'The Grade or Course field is required.',
            'gender.required'          => 'The Gender field is required.',
            'gender.in'                => 'The selected Gender is invalid. Allowed values: Male, Female, Other.',
            'father_name.required'     => 'The Father\'s Name field is required.',
            'mother_name.required'     => 'The Mother\'s Name field is required.',
            'contact_number.required'  => 'The Contact Number field is required.',
            'address.required'         => 'The Address field is required.',
            'emergency_contact.required' => 'The Emergency Contact field is required.',
            'birthdate.required'       => 'The Birthdate field is required.',
            'birthdate.date'           => 'The Birthdate must be a valid date.',
            'profile_picture.required' => 'The Profile Picture field is required.',
            'semester.required'        => 'The Semester field is required.',
            'school_year.required'     => 'The School Year field is required.',
            'school_year.regex'        => 'The School Year format is invalid. It should be YYYY-YYYY.',
            'age.required'             => 'The age field is required.',
            'section.required'         => 'The Section field is required.',
        ];
    }

    /**
     * Handle row failures (validation).
     */
    public function onFailure(...$failures)
    {
        foreach ($failures as $failure) {
            $row       = $failure->row();
            $attribute = $failure->attribute();
            $errors    = $failure->errors();

            Log::warning("Row {$row} failed on {$attribute}: " . implode(', ', $errors));

            if ($attribute === 'id_number') {
                $this->duplicates[] = $failure->values()['id_number'] ?? 'Unknown';
            }
        }
    }

    /**
     * Deactivate enrollments not present in the imported list for the given semester and school year.
     */
    public function deactivateMissingEnrollments()
    {
        try {
            // Group imported enrollments by (semester - school_year)
            $enrollments = Enrollment::whereIn('student_id', $this->importedStudentIdNumbers)
                ->get()
                ->groupBy(function ($item) {
                    return $item->semester . '-' . $item->school_year;
                });

            foreach ($enrollments as $key => $group) {
                list($semester, $schoolYear) = explode('-', $key);

                // Find all actively enrolled students for this semester & year
                $currentEnrolledStudents = Enrollment::where('semester', $semester)
                    ->where('school_year', $schoolYear)
                    ->where('is_enrolled', true)
                    ->pluck('student_id')
                    ->toArray();

                // Identify who needs to be deactivated
                $studentsToDeactivate = Enrollment::where('semester', $semester)
                    ->where('school_year', $schoolYear)
                    ->where('is_enrolled', true)
                    ->whereNotIn('student_id', $this->importedStudentIdNumbers)
                    ->pluck('student_id')
                    ->toArray();

                // Deactivate them
                Enrollment::where('semester', $semester)
                    ->where('school_year', $schoolYear)
                    ->whereIn('student_id', $studentsToDeactivate)
                    ->update(['is_enrolled' => false]);

                // Update the student statuses
                Student::whereIn('id_number', $studentsToDeactivate)
                    ->update(['enrollment_status' => 'inactive', 'approved' => 0]);

                Log::info('Deactivated enrollments for semester and school year.', [
                    'semester'             => $semester,
                    'school_year'          => $schoolYear,
                    'students_deactivated' => $studentsToDeactivate,
                ]);
            }

            // Finally update overall approval statuses
            $this->updateStudentApprovalStatuses();

            // Check mismatches
            if ($this->mismatchCount > 0) {
                $errorMessage = "Import failed due to mismatched Grade/Course entries:\n" 
                              . implode("\n", $this->mismatchErrors);
                throw ValidationException::withMessages([
                    'grade_or_course' => $errorMessage,
                ]);
            }

            Log::info('Deactivated missing enrollments based on the import.');
        } catch (\Exception $e) {
            Log::error('Error deactivating missing enrollments: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update the approval status of students based on active enrollments.
     */
    protected function updateStudentApprovalStatuses()
    {
        // Grab all students in the imported grade_or_course
        $students = Student::where('grade_or_course', 'like', $this->gradeOrCourse . '%')->get();

        foreach ($students as $student) {
            // Count how many active enrollments remain
            $activeEnrollments = Enrollment::where('student_id', $student->id_number)
                ->where('is_enrolled', true)
                ->count();

            if ($activeEnrollments > 0) {
                $student->approved          = 1;
                $student->enrollment_status = 'active';
            } else {
                $student->approved          = 0;
                $student->enrollment_status = 'inactive';
            }
            $student->save();

            Log::info('Student approval status updated.', [
                'student_id_number' => $student->id_number,
                'approved'          => $student->approved,
            ]);
        }
    }

    /**
     * Get duplicates from the import.
     */
    public function getDuplicates(): array
    {
        return $this->duplicates;
    }

    /**
     * Get the list of imported student IDs.
     */
    public function getImportedStudentIdNumbers(): array
    {
        return $this->importedStudentIdNumbers;
    }

    /**
     * Normalize the grade_or_course field (e.g., GRADE-10 -> GRADE 10).
     */
    private function normalizeGradeOrCourse($gradeOrCourse)
    {
        $gradeOrCourse = strtoupper(trim($gradeOrCourse));

        // Handle "GRADE-10" or "GRADE10"
        if (preg_match('/^GRADE[-\s]?(\d+)$/', $gradeOrCourse, $matches)) {
            return 'GRADE ' . $matches[1];
        }

        // Handle "GRADE11-HUMMSS" style with suffix
        if (preg_match('/^GRADE[-\s]?(\d+)-([A-Z]+)$/', $gradeOrCourse, $matches)) {
            return 'GRADE ' . $matches[1] . '-' . $matches[2];
        }

        // Handle college courses that might have trailing numbers (e.g., "BSIT-2")
        if (preg_match('/^(BSBA|BSCS|BSIT|BSTM|BSHM|BSN|BLIS|BEED)[-\s]?(\d+)?$/', $gradeOrCourse, $matches)) {
            return $matches[1]; // e.g., "BSIT"
        }

        // Return as-is if no pattern matched
        return $gradeOrCourse;
    }

    /**
     * Get the mismatch errors for the controller.
     */
    public function getMismatchErrors()
    {
        return $this->mismatchErrors;
    }
}
