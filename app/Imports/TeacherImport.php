<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\File;

// Maatwebsite Excel Concerns
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

/**
 * Class TeacherImport
 *
 * Implements:
 *  - ToModel         : Allows creating/updating Eloquent models from each row.
 *  - WithValidation  : Lets us define validation rules for each row.
 *  - WithHeadingRow  : Indicates the first row is a heading row (column headers).
 *  - WithValidator   : Allows adding custom validation logic (withValidator()).
 *  - WithEvents      : Lets us register event callbacks (e.g., BeforeImport, AfterImport).
 *  - SkipsOnFailure  : Lets us skip rows that fail validation, continuing the import.
 */
class TeacherImport implements 
    ToModel, 
    WithValidation, 
    WithHeadingRow, 
    WithEvents, 
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    protected $importedIdNumbers = [];
    protected $programHeadsImported = [];

    /**
     * Get the list of imported ID numbers.
     */
    public function getImportedIdNumbers()
    {
        return $this->importedIdNumbers;
    }

    /**
     * Implement withValidator to define cross-field or after-row validations.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Map of courses to allowed bed_or_hed
            $courseBedHedMap = [
                'BSIT'       => 'HED',
                'BSBA'       => 'HED',
                'BSN'        => 'HED',
                'BSTM'       => 'HED',
                'BSHM'       => 'HED',
                'BLIS'       => 'HED',
                'BEED'       => 'BED',
                'ELEMENTARY' => 'BED',
                // Add other courses and their allowed bed_or_hed
            ];

            // If you have a helper method getRows() or the library provides row data:
            // Make sure you're retrieving the row data from your import class,
            // or from $validator->getData() if your library version supports it.
            
            // NOTE: If you do NOT have a getRows() method, you could switch to using:
            //   foreach ($validator->getData() as $index => $row) {
            //   ...
            //   }
            //   Make sure your version of Maatwebsite/Excel supports that approach.

            if (method_exists($this, 'getRows') && $this->getRows()) {
                foreach ($this->getRows() as $index => $row) {
                    $rowNumber = $index + 2; // Considering heading row

                    $course = strtoupper(trim($row['course'] ?? ''));
                    $bedOrHed = strtoupper(trim($row['bed_or_hed'] ?? ''));
                    $role = strtolower(trim($row['role'] ?? ''));

                    // Check if course exists in the map
                    if (array_key_exists($course, $courseBedHedMap)) {
                        $allowedBedHed = $courseBedHedMap[$course];
                        if ($bedOrHed !== $allowedBedHed) {
                            $validator->errors()->add(
                                "{$course} Bed/Hed",
                                "Row {$rowNumber}: The course '{$course}' must be under '{$allowedBedHed}'."
                            );
                        }
                    } else {
                        // If course is not defined in the map, you can choose to skip or add a warning
                        $validator->errors()->add(
                            "{$course} Bed/Hed",
                            "Row {$rowNumber}: The course '{$course}' is not recognized. Please ensure it's correctly spelled and mapped."
                        );
                    }

                    // If role is program_head, ensure no duplicate program_head for the course
                    if ($role === 'program_head') {
                        $existingProgramHead = Teacher::where('course', $course)
                            ->where('role', 'program_head')
                            ->exists();

                        if ($existingProgramHead) {
                            $validator->errors()->add(
                                "{$course} Program Head",
                                "Row {$rowNumber}: The course '{$course}' already has a Program Head assigned."
                            );
                        }

                        // Also, check within the current import if multiple program_heads are being assigned to the same course
                        $programHeadCount = array_count_values($this->programHeadsImported);
                        if (isset($programHeadCount[$course]) && $programHeadCount[$course] > 1) {
                            $validator->errors()->add(
                                "{$course} Program Head",
                                "Row {$rowNumber}: Multiple Program Heads found for the course '{$course}' in the uploaded file. Only one Program Head per course is allowed."
                            );
                        }
                    }
                }
            }
        });
    }

    /**
     * Get the list of imported Program Heads' courses.
     */
    public function getProgramHeadsImported()
    {
        return $this->programHeadsImported;
    }

    /**
     * Create/update a teacher from a given row.
     */
    public function model(array $row)
    {
        Log::info('Importing Teacher Row:', $row);

        // Normalize ID Number
        $idNumber = strtoupper(trim($row['id_number'] ?? ''));

        // Check if teacher with the same ID number exists
        $teacher = Teacher::where('id_number', $idNumber)->first();

        // Determine the role, defaulting to 'teacher' if not specified or invalid
        $role = isset($row['role']) && in_array(strtolower($row['role']), ['teacher', 'program_head'])
            ? strtolower($row['role'])
            : 'teacher';

        // Track Program Heads being imported
        if ($role === 'program_head' && isset($row['course'])) {
            $this->programHeadsImported[] = $row['course'];
        }

        // Collect imported ID numbers
        $this->importedIdNumbers[] = $idNumber;

        // Prepare Teacher Data
        $teacherData = [
            'id_number'   => $idNumber,
            'first_name'  => ucfirst(trim($row['first_name'] ?? '')),
            'last_name'   => ucfirst(trim($row['last_name'] ?? '')),
            'bed_or_hed'  => ucfirst(trim($row['bed_or_hed'] ?? '')),
            'course'      => ($role === 'bed') 
                ? 'Elementary' 
                : ucfirst(trim($row['course'] ?? '')),
            'approved'    => true,
            'role'        => $role,
            'father_name' => isset($row['father_name']) 
                ? ucfirst(trim($row['father_name'])) 
                : null,
            'mother_name' => isset($row['mother_name']) 
                ? ucfirst(trim($row['mother_name'])) 
                : null,
            'contact_number' => isset($row['contact_number']) 
                ? trim($row['contact_number']) 
                : null,
            'address' => isset($row['address']) 
                ? trim($row['address']) 
                : null,
            'emergency_contact' => isset($row['emergency_contact']) 
                ? trim($row['emergency_contact']) 
                : null,
            'age' => isset($row['age']) 
                ? (int) $row['age'] 
                : null,
        ];

        // ----------------------------
        // PROFILE PICTURE HANDLING
        // ----------------------------
        $profilePicPath = null;
        if (!empty($row['profile_picture'])) {
            $profilePicture = trim($row['profile_picture']);

            // 1) If it’s a valid URL, attempt to download
            if (filter_var($profilePicture, FILTER_VALIDATE_URL)) {
                try {
                    $imageContents = @file_get_contents($profilePicture);
                    if ($imageContents === false) {
                        throw new \Exception("Unable to download image from URL: $profilePicture");
                    }
                    // Extract extension
                    $extension = pathinfo(
                        parse_url($profilePicture, PHP_URL_PATH),
                        PATHINFO_EXTENSION
                    ) ?: 'jpg';

                    $imageName   = Str::uuid() . '.' . strtolower($extension);
                    $pathToStore = 'profile_pictures/' . $imageName;

                    // Store in public disk
                    Storage::disk('public')->put($pathToStore, $imageContents);

                    $profilePicPath = $pathToStore;
                } catch (\Exception $e) {
                    Log::error('Error downloading profile picture: ' . $e->getMessage());
                    $profilePicPath = null;
                }
            }
            // 2) Else if it’s a local file path
            elseif (file_exists($profilePicture)) {
                try {
                    $extension = pathinfo($profilePicture, PATHINFO_EXTENSION) ?: 'jpg';
                    $imageName = Str::uuid() . '.' . strtolower($extension);

                    $pathToStore = 'profile_pictures/' . $imageName;

                    // Copy local file into storage/app/public/profile_pictures
                    Storage::disk('public')->putFileAs(
                        'profile_pictures',
                        new File($profilePicture),
                        $imageName
                    );

                    $profilePicPath =  $pathToStore;
                } catch (\Exception $e) {
                    Log::error('Error copying local profile picture: ' . $e->getMessage());
                    $profilePicPath = null;
                }
            } else {
                // Not a valid URL, and local path does not exist on server
                Log::warning("Profile picture field is neither a valid URL nor an existing file path: {$profilePicture}");
            }
        }

        // If teacher exists, update
        if ($teacher) {
            $teacher->update($teacherData);

            // If we got a new profile picture path
            if ($profilePicPath) {
                if ($teacher->profile_picture 
                    && Storage::disk('public')->exists(str_replace('storage/', '', $teacher->profile_picture))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $teacher->profile_picture));
                }
                $teacher->profile_picture = $profilePicPath;
                $teacher->save();
            }

            // Sync with User table
            $user = User::where('id_number', $idNumber)->first();
            if ($user) {
                $user->email      = isset($row['email']) 
                    ? trim($row['email']) 
                    : $user->email;
                $user->first_name = $teacherData['first_name'];
                $user->last_name  = $teacherData['last_name'];
                $user->role       = $teacherData['role'];
                $user->approved   = true;
                $user->save();
            } else {
                // Create a new user if not exists & if email is provided
                if (!empty($row['email'])) {
                    $password = Str::random(16);
                    $user = User::create([
                        'id_number' => $idNumber,
                        'email'     => trim($row['email']),
                        'password'  => Hash::make($password),
                        'role'      => $teacherData['role'],
                        'approved'  => true,
                    ]);
                    Password::sendResetLink(['email' => $user->email]);
                    Log::info("Password reset link sent to user: {$user->email}");
                }
            }
            return null; // updated, not created
        }

        // Otherwise create new teacher
        $teacherData['profile_picture'] = $profilePicPath;
        $newTeacher = Teacher::create($teacherData);

        // Create a user if email is provided
        if (!empty($row['email'])) {
            $password = Str::random(16);
            $user = User::create([
                'id_number' => $idNumber,
                'email'     => trim($row['email']),
                'password'  => Hash::make($password),
                'role'      => $teacherData['role'],
                'approved'  => true,
            ]);
            Password::sendResetLink(['email' => $user->email]);
            Log::info("Password reset link sent to new user: {$user->email}");
        } else {
            Log::warning("Email not provided for teacher ID: {$idNumber}. User account not created.");
            $this->programHeadsImported[] = strtoupper($teacherData['course'] ?? '');
        }

        return $newTeacher;
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            '*.id_number'        => 'required|string|max:7|unique:teacher,id_number',
            '*.first_name'       => 'required|string|max:255',
            '*.last_name'        => 'required|string|max:255',
            '*.bed_or_hed'       => 'required|string|in:BED,HED',
            '*.role'             => 'required|string|in:teacher,program_head',
            '*.course'           => 'required|string|max:255',
            '*.email'            => 'nullable|email|unique:users,email',
            '*.profile_picture'  => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            '*.id_number.required'  => 'ID Number is required.',
            '*.id_number.unique'    => 'Teacher with ID Number ":input" already exists.',
            '*.first_name.required' => 'First Name is required.',
            '*.last_name.required'  => 'Last Name is required.',
            '*.bed_or_hed.required' => 'Department (BED or HED) is required.',
            '*.bed_or_hed.in'       => 'Invalid Department selected. Allowed values are BED or HED.',
            '*.role.required'       => 'Role is required.',
            '*.role.in'             => 'Role must be either "teacher" or "program_head".',
            '*.course.required'     => 'Course is required.',
            '*.email.email'         => 'The email ":input" is not a valid email address.',
            '*.email.unique'        => 'The email ":input" has already been taken.',
            '*.profile_picture.string' => 'Profile picture must be a valid URL or server file path.',
            '*.profile_picture.max' => 'Profile picture path is too long.',
        ];
    }

    /**
     * Minimal implemention to satisfy WithEvents interface.
     *
     * If you don't need any specific events, return an empty array.
     */
    public function registerEvents(): array
    {
        return [
            // Example usage:
            // BeforeImport::class => function(BeforeImport $event) { ... },
            // AfterImport::class  => function(AfterImport $event)  { ... },
        ];
    }
}
