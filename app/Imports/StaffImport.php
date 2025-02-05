<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class StaffImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    protected $duplicates = [];

    /**
     * Retrieve any duplicates found.
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }

    /**
     * Create or update staff from each row in the Excel file.
     */
    public function model(array $row)
    {
        // Log the incoming row for debugging
        Log::info('Importing Staff Row:', $row);

        // Validate required fields
        if (!isset($row['id_number']) || !isset($row['first_name']) ||
            !isset($row['last_name']) || !isset($row['position'])) {
            Log::warning('Missing required fields in row:', $row);
            $this->duplicates[] = (object) ['id_number' => $row['id_number'] ?? 'Unknown'];
            return null;
        }

        // Normalize ID Number
        $idNumber = strtoupper(trim($row['id_number']));

        // Check if staff with the same ID number exists
        $staff = Staff::where('id_number', $idNumber)->first();

        // Common staff data to update or create
        $staffData = [
            'first_name'       => ucfirst(trim($row['first_name'])),
            'last_name'        => ucfirst(trim($row['last_name'])),
            'position'         => ucfirst(trim($row['position'])),
            'father_name'      => isset($row['father_name']) ? ucfirst(trim($row['father_name'])) : null,
            'mother_name'      => isset($row['mother_name']) ? ucfirst(trim($row['mother_name'])) : null,
            'contact_number'   => isset($row['contact_number']) ? trim($row['contact_number']) : null,
            'address'          => isset($row['address']) ? trim($row['address']) : null,
            'birthdate'        => isset($row['birthdate']) ? $row['birthdate'] : null,
            'emergency_contact'=> isset($row['emergency_contact']) ? trim($row['emergency_contact']) : null,
            'age'              => isset($row['age']) ? (int) $row['age'] : null,
            'approved'         => true, // automatically approve
        ];

        // ==========================
        // PROFILE PICTURE HANDLING
        // ==========================
        $profilePicPath = null;

        if (isset($row['profile_picture']) && $row['profile_picture']) {
            $profilePicture = trim($row['profile_picture']);

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
                        $profilePicture, // or new \Illuminate\Http\File($profilePicture)
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
        }
        // Done profile picture handling

        // If staff exists, update
        if ($staff) {
            // If we got a new profile picture path
            if ($profilePicPath) {
                // Delete old profile picture if exists
                if ($staff->profile_picture &&
                    Storage::disk('public')->exists(str_replace('storage/', '', $staff->profile_picture))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $staff->profile_picture));
                }
                $staffData['profile_picture'] = $profilePicPath;
            }
            $staff->update($staffData);

            // Sync with user (if any)
            $user = User::where('id_number', $idNumber)->first();
            if ($user) {
                $user->update([
                    'email'            => isset($row['email']) ? trim($row['email']) : $user->email,
                    'first_name'       => $staffData['first_name'],
                    'last_name'        => $staffData['last_name'],
                    'position'         => $staffData['position'],
                    'approved'         => true,
                ]);
            } else {
                // Create a new user if not exists & if email is provided
                if (isset($row['email']) && $row['email']) {
                    $password = Str::random(16);
                    $user = User::create([
                        'id_number' => $idNumber,
                        'email'     => trim($row['email']),
                        'password'  => Hash::make($password),
                        'role'      => 'staff',
                        'approved'  => true,
                    ]);
                    Password::sendResetLink(['email' => $user->email]);
                    Log::info("Password reset link sent to user: {$user->email}");
                }
            }

            // Return null since we updated, not created
            return null;
        }

        // Otherwise create new staff
        if ($profilePicPath) {
            $staffData['profile_picture'] = $profilePicPath;
        }
        $staffData['id_number'] = $idNumber;

        $newStaff = Staff::create($staffData);

        // Create a user if email is provided
        if (isset($row['email']) && $row['email']) {
            $user = User::create([
                'id_number' => $idNumber,
                'email'     => trim($row['email']),
                'password'  => Hash::make(Str::random(16)), // Temporary password
                'role'      => 'staff',
                'approved'  => true,
            ]);
            Password::sendResetLink(['email' => $user->email]);
            Log::info("Password reset link sent to new user: {$user->email}");
        } else {
            // No email -> can't create user
            Log::warning("Email not provided for staff ID: {$idNumber}. User account not created.");
            $this->duplicates[] = (object) ['id_number' => $idNumber];
        }

        return $newStaff;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'id_number'        => 'required|string|max:255',
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'position'         => 'required|string|max:255',
            'father_name'      => 'nullable|string|max:255',
            'mother_name'      => 'nullable|string|max:255',
            'contact_number'   => 'nullable|string|max:200',
            'address'          => 'nullable|string|max:255',
            'birthdate'        => 'nullable|date',
            'emergency_contact'=> 'nullable|string|max:200',
            'age'              => 'nullable|integer|min:0|max:150',
            'email'            => 'nullable|email|unique:users,email',
            // Not validating 'profile_picture' because it can be local or URL
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages()
    {
        return [
            'id_number.required'        => 'ID Number is required.',
            'first_name.required'       => 'First Name is required.',
            'last_name.required'        => 'Last Name is required.',
            'position.required'         => 'Position is required.',
            'email.email'               => 'The email must be a valid email address.',
            'email.unique'              => 'The email has already been taken.',
            // Additional messages...
        ];
    }
}
