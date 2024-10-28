<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Parents; // Adjust based on your actual model names
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Information;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Staff;
use App\Notifications\CustomVerifyEmail; // Import the custom notification

class SettingsController extends Controller
{
    /**
     * Display the settings form based on user role.
     */
    public function edit()
    {
        $user = Auth::user();

        // Log the user's role for debugging
        \Log::info('User role: ' . $user->role);

        // Convert role to lowercase for consistency
        $role = strtolower($user->role);

        // Define supported roles and corresponding view paths
        $supportedRoles = ['admin', 'nurse', 'doctor', 'teacher', 'staff', 'student', 'parent'];
        $information = Information::where('id_number', $user->id_number)->first();

        if (in_array($role, $supportedRoles)) {
            $viewPath = "{$role}.settings"; // e.g., 'student.settings', 'parent.settings'
        } else {
            abort(403, 'Unauthorized action.');
        }

        // Check if the view exists, otherwise abort
        if (!view()->exists($viewPath)) {
            abort(404, 'Settings view not found.');
        }

        // Fetch role-specific data
        $roleData = null;
        switch ($role) {
            case 'parent':
                $roleData = Parents::where('id_number', $user->id_number)->first();
                break;
            case 'student':
                $roleData = Student::where('id_number', $user->id_number)->first();
                break;
            case 'teacher':
                $roleData = Teacher::where('id_number', $user->id_number)->first();
                break;
            case 'nurse':
                $roleData = Nurse::where('id_number', $user->id_number)->first();
                break;
            case 'doctor':
                $roleData = Doctor::where('id_number', $user->id_number)->first();
                break;
            case 'staff':
                $roleData = Staff::where('id_number', $user->id_number)->first();
                break;
            default:
                // No role-specific data
                break;
        }

        return view($viewPath, compact('user', 'roleData', 'information'));
    }

    /**
     * Update the user's account settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Updated validation rules with password complexity
        $rules = [
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'         => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            ],
            'profile_picture'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Custom validation messages
        $messages = [
            'password.regex'            => 'Password must be at least 8 characters long and contain both letters and numbers.',
            'profile_picture.image'     => 'The profile picture must be an image.',
            'profile_picture.mimes'     => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
            'profile_picture.max'       => 'The profile picture may not be greater than 2MB.',
        ];

        // Validate the request
        $validatedData = $request->validate($rules, $messages);

        // Update common user info
        $user->first_name = $validatedData['first_name'];
        $user->last_name  = $validatedData['last_name'];
        $user->email      = $validatedData['email'];

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Save user data
        $user->save();

        // Update Information model
        $information = Information::firstOrCreate(
            ['id_number' => $user->id_number],
            []
        );

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old image if exists
            if ($information->profile_picture && Storage::disk('public')->exists($information->profile_picture)) {
                Storage::disk('public')->delete($information->profile_picture);
            }
            // Store new image
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $information->profile_picture = $path;
        }

        $information->save();

        // Check if email was changed to require verification
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null; // Invalidate email verification
            $user->save();

            // Send custom verification email
            $user->sendCustomEmailVerificationNotification();

            // Inform the user to verify the new email
            return redirect()->back()->with('email_verification_required', true);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    /**
     * Update the user's additional information.
     */
    public function updateAdditional(Request $request)
    {
        $user = Auth::user();
        $role = strtolower(trim($user->role));

        // Define validation rules based on role
        $rules = [
            'address'   => 'required|string|max:500',
            'birthdate' => 'required|date',
        ];

        if ($role === 'parent') {
            $rules = array_merge($rules, [
                'parent_name_father'       => 'nullable|string|max:255',
                'parent_name_mother'       => 'nullable|string|max:255',
                'guardian_name'            => 'nullable|string|max:255',
                'guardian_relationship'    => 'nullable|string|max:255',
            ]);
        }

        if (in_array($role, ['student', 'teacher', 'nurse', 'doctor', 'staff'])) {
            $rules = array_merge($rules, [
                'emergency_contact_number' => 'nullable|string|regex:/^\d{11}$/',
                'personal_contact_number'  => 'nullable|string|regex:/^\d{11}$/',
            ]);
        }

        // Custom validation messages
        $messages = [
            'emergency_contact_number.regex' => 'The emergency contact number must be exactly 11 digits.',
            'personal_contact_number.regex'  => 'The personal contact number must be exactly 11 digits.',
        ];

        // Validate the request
        $validatedData = $request->validate($rules, $messages);

        // Update Information model
        $information = Information::firstOrCreate(
            ['id_number' => $user->id_number],
            []
        );

        // Update common fields
        $information->address = $validatedData['address'];
        $information->birthdate = $validatedData['birthdate'];

        // Update role-specific fields
        if ($role === 'parent') {
            $information->parent_name_father = $validatedData['parent_name_father'] ?? $information->parent_name_father;
            $information->parent_name_mother = $validatedData['parent_name_mother'] ?? $information->parent_name_mother;
            $information->guardian_name = $validatedData['guardian_name'] ?? $information->guardian_name;
            $information->guardian_relationship = $validatedData['guardian_relationship'] ?? $information->guardian_relationship;
        }

        if (in_array($role, ['student', 'teacher', 'nurse', 'doctor', 'staff'])) {
            $information->emergency_contact_number = $validatedData['emergency_contact_number'] ?? $information->emergency_contact_number;
            $information->personal_contact_number  = $validatedData['personal_contact_number'] ?? $information->personal_contact_number;
        }

        $information->save();

        return redirect()->back()->with('success', 'Additional information updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function delete()
    {
        $user = Auth::user();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
