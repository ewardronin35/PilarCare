<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Ensure Validator is imported
use App\Models\User;
use App\Models\Parents; // Adjust based on your actual model names
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Information;
use App\Models\Nurse;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Staff;
use App\Notifications\CustomVerifyEmail; // Import the custom notification
use App\Mail\EmailChangeNotification; // Import the custom notification
use Illuminate\Support\Facades\Mail; // Ensure Mail is imported


class SettingsController extends Controller
{
    /**
     * Display the settings form based on user role.
     */
    public function edit()
    {
        $user = Auth::user();
    
        // Convert role to lowercase for consistency
        $role = strtolower($user->role);
    
        // Define supported roles and corresponding view paths
        $supportedRoles = ['admin', 'nurse', 'doctor', 'teacher', 'staff', 'student', 'parent'];
    
        if (in_array($role, $supportedRoles)) {
            $viewPath = "{$role}.settings"; // e.g., 'student.settings', 'parent.settings', 'admin.settings'
        } else {
            abort(403, 'Unauthorized action.');
        }
    
        // Check if the view exists, otherwise abort
        if (!view()->exists($viewPath)) {
            abort(404, 'Settings view not found.');
        }
    
        // Initialize roleData, firstName, lastName, and name
        $roleData = null;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $name = null; // Initialize name
    
        // Fetch role-specific data using relationships
        switch ($role) {
            case 'admin':
                $roleData = $user->admin;
                if ($roleData) {
                    $name = $roleData->name ?? $name;
                }
                break;
            case 'parent':
                $roleData = Parents::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name ?? $firstName;
                    $lastName = $roleData->last_name ?? $lastName;
                }
                break;
            case 'student':
                $roleData = Student::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name ?? $firstName;
                    $lastName = $roleData->last_name ?? $lastName;
                }
                break;
            case 'teacher':
                $roleData = Teacher::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name ?? $firstName;
                    $lastName = $roleData->last_name ?? $lastName;
                }
                break;
            case 'nurse':
                $roleData = Nurse::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name ?? $firstName;
                    $lastName = $roleData->last_name ?? $lastName;
                }
                break;
            case 'doctor':
                $roleData = Doctor::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name ?? $firstName;
                    $lastName = $roleData->last_name ?? $lastName;
                }
                break;
            case 'staff':
                $roleData = Staff::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name ?? $firstName;
                    $lastName = $roleData->last_name ?? $lastName;
                }
                break;
            // Add other cases if necessary
            default:
                // For roles like admin, nurse, doctor, staff, use $user->first_name and $user->last_name
                break;
        }
    
        // Enhanced Logging
        if ($role === 'admin') {
            if ($roleData) {
                \Log::info("Admin Data - ID Number: {$roleData->id_number}, Name: {$roleData->name}");
            } else {
                \Log::warning("Admin Record Not Found for User ID: {$user->id}, ID Number: {$user->id_number}");
            }
        }
    
        // Log the firstName, lastName, and name
        \Log::info("Settings Edit - User ID: {$user->id}, Role: {$role}, FirstName: {$firstName}, LastName: {$lastName}, Name: {$name}");
    
        // Pass 'information' only if not admin
        if ($role !== 'admin') {
            $information = Information::where('id_number', $user->id_number)->first();
        } else {
            $information = null; // No Information for admin
        }
    
        return view($viewPath, compact('user', 'roleData', 'information', 'firstName', 'lastName', 'name', 'role'));
    }
    
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        // Store the original email for notification
        $originalEmail = $user->email;
    
        // Define base validation rules
        $rules = [
            'email'            => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'         => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            ],
            'profile_picture'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    
        // Add role-specific validation rules
        if ($role === 'admin') {
            $rules['name'] = 'required|string|max:255';
        } else {
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
        }
    
        // Custom validation messages
        $messages = [
            'password.regex'            => 'Password must be at least 8 characters long and contain both letters and numbers.',
            'profile_picture.image'     => 'The profile picture must be an image.',
            'profile_picture.mimes'     => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
            'profile_picture.max'       => 'The profile picture may not be greater than 2MB.',
        ];
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Retrieve validated data
        $validatedData = $validator->validated();
    
        // Sanitize inputs
        $validatedData['email'] = filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL);
        if ($role !== 'admin') {
            $validatedData['first_name'] = filter_var($validatedData['first_name'], FILTER_SANITIZE_STRING);
            $validatedData['last_name']  = filter_var($validatedData['last_name'], FILTER_SANITIZE_STRING);
        } else {
            $validatedData['name'] = filter_var($validatedData['name'], FILTER_SANITIZE_STRING);
        }
    
        // Update common user info
        $user->email = $validatedData['email'];
    
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }
    
        // Save user data
        $user->save();
    
        // Update role-specific model
        switch ($role) {
            case 'admin':
                $roleData = $user->admin; // Utilize the relationship
                if (!$roleData) {
                    $roleData = new Admin();
                    $roleData->id_number = $user->id_number;
                }
                $roleData->name = $validatedData['name'];
                $roleData->save();
                break;
            case 'parent':
                $roleData = Parents::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $roleData->first_name = $validatedData['first_name'];
                    $roleData->last_name = $validatedData['last_name'];
                    $roleData->save();
                }
                break;
            case 'student':
                $roleData = Student::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $roleData->first_name = $validatedData['first_name'];
                    $roleData->last_name = $validatedData['last_name'];
                    $roleData->save();
                }
                break;
            case 'teacher':
                $roleData = Teacher::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $roleData->first_name = $validatedData['first_name'];
                    $roleData->last_name = $validatedData['last_name'];
                    $roleData->save();
                }
                break;
            case 'nurse':
                $roleData = Nurse::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $roleData->first_name = $validatedData['first_name'];
                    $roleData->last_name = $validatedData['last_name'];
                    $roleData->save();
                }
                break;
            case 'doctor':
                $roleData = Doctor::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $roleData->first_name = $validatedData['first_name'];
                    $roleData->last_name = $validatedData['last_name'];
                    $roleData->save();
                }
                break;
            case 'staff':
                $roleData = Staff::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $roleData->first_name = $validatedData['first_name'];
                    $roleData->last_name = $validatedData['last_name'];
                    $roleData->save();
                }
                break;
            // Add other cases if necessary
            default:
                // For roles like admin, nurse, doctor, staff, first_name and last_name are already updated in User model
                break;
        }
    
        // Update Information model only for non-admin roles
        if ($role !== 'admin') {
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
        }
    
        // Check if email was changed to require verification
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null; // Invalidate email verification
            $user->save();
    
            // Send email verification to the new email address
            $user->sendEmailVerificationNotification();
    
            // Send notification to the old email address about the change
            Mail::to($originalEmail)->send(new EmailChangeNotification($user, $originalEmail));
    
            // Inform the user to verify the new email
            return redirect()->back()->with('email_verification_required', true);
        }
    
        // If no email change, return success
        return redirect()->back()->with('success', 'Profile updated successfully.');
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
