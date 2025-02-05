<?php

namespace App\Imports;

use App\Models\Nurse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Password;
use Maatwebsite\Excel\Validators\ValidationException;

class NurseImport implements ToModel, WithValidation, WithHeadingRow
{
    protected $duplicates = [];

    /**
     * Insert the nurse into the database.
     */
    public function model(array $row)
    {
        $idNumber = strtoupper(trim($row['id_number']));
        $email = isset($row['email']) ? strtolower(trim($row['email'])) : null;

        // Check if the nurse already exists
        $existingNurse = Nurse::where('id_number', $idNumber)->first();

        if ($existingNurse) {
            Log::info("Duplicate entry found for ID: {$idNumber}");
            $this->duplicates[] = $idNumber;
            throw ValidationException::withMessages(['id_number' => "Nurse with ID Number {$idNumber} already exists."]);
        }

        Log::info("Importing Nurse: ", $row);

        // Create the Nurse record
        $nurse = Nurse::create([
            'id_number'  => $idNumber,
            'first_name' => ucfirst(trim($row['first_name'])),
            'last_name'  => ucfirst(trim($row['last_name'])),
            'department' => ucfirst(trim($row['department'])),  // Ensure department is formatted correctly
            'approved'   => true, // Automatically approve the nurse
        ]);

        // Create a corresponding User account if email is provided
        if ($email) {
            $existingUser = User::where('email', $email)->first();

            if (!$existingUser) {
                $password = Str::random(16); // Generate a random password
                $user = User::create([
                    'id_number'  => $idNumber,
                    'email'      => $email,
                    'password'   => Hash::make($password),
                    'role'       => 'nurse',
                    'approved'   => true, // Automatically approve the user
                ]);

                // Send password reset link for login setup
                Password::sendResetLink(['email' => $user->email]);
                Log::info("Password reset link sent to user: {$user->email}");
            } else {
                Log::warning("Email {$email} already exists in the users table. Skipping user creation.");
            }
        } else {
            Log::warning("No email provided for nurse ID: {$idNumber}. User account not created.");
        }

        return $nurse;
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            '*.id_number'  => 'required|string|max:7|unique:nurses,id_number',
            '*.first_name' => 'required|string|max:255',
            '*.last_name'  => 'required|string|max:255',
            '*.department' => 'required|string|max:255',
            '*.email'      => 'nullable|email|unique:users,email',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            '*.id_number.required'  => 'ID Number is required.',
            '*.id_number.unique'    => 'Nurse with ID Number ":input" already exists.',
            '*.first_name.required' => 'First Name is required.',
            '*.last_name.required'  => 'Last Name is required.',
            '*.department.required' => 'Department is required.',
            '*.email.email'         => 'The email ":input" is not a valid email address.',
            '*.email.unique'        => 'The email ":input" has already been taken.',
        ];
    }

    /**
     * Get duplicate entries encountered during import.
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
