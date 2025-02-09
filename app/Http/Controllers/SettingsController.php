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
        $role = strtolower($user->role);
    
        // Define supported roles and view paths
        $supportedRoles = ['admin', 'nurse', 'doctor', 'teacher', 'staff', 'student', 'parent'];
        if (!in_array($role, $supportedRoles)) {
            abort(403, 'Unauthorized action.');
        }
        $viewPath = "{$role}.settings";
        if (!view()->exists($viewPath)) {
            abort(404, 'Settings view not found.');
        }
    
        // Load role‑specific data (using your existing switch)
        $roleData = null;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $name = null;
    
        switch ($role) {
            case 'admin':
                $roleData = $user->admin;
                if ($roleData) {
                    $name = $roleData->name;
                }
                break;
            case 'parent':
                $roleData = \App\Models\Parents::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name;
                    $lastName = $roleData->last_name;
                }
                break;
            case 'student':
                $roleData = Student::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name;
                    $lastName = $roleData->last_name;
                }
                break;
            case 'teacher':
                $roleData = Teacher::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name;
                    $lastName = $roleData->last_name;
                }
                break;
            case 'nurse':
                $roleData = Nurse::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name;
                    $lastName = $roleData->last_name;
                }
                break;
            case 'doctor':
                $roleData = Doctor::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name;
                    $lastName = $roleData->last_name;
                }
                break;
            case 'staff':
                $roleData = Staff::where('id_number', $user->id_number)->first();
                if ($roleData) {
                    $firstName = $roleData->first_name;
                    $lastName = $roleData->last_name;
                }
                break;
        }
    
        \Log::info("Settings Edit - User ID: {$user->id}, Role: {$role}, FirstName: {$firstName}, LastName: {$lastName}, Name: {$name}");
    
        // Since the Information model is deleted, we no longer load it.
        $information = null;
    
        return view($viewPath, compact('user', 'roleData', 'information', 'firstName', 'lastName', 'name', 'role'));
    }
    
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        // Store the original email for later comparison
        $originalEmail = $user->email;
    
        // Base validation rules for email, password, and profile picture
        $rules = [
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
            ],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    
        if ($role === 'admin') {
            $rules['name'] = 'required|string|max:255';
        } else {
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
        }
    
        $messages = [
            'password.regex' => 'Password must be at least 8 characters long and contain both letters and numbers.',
            'profile_picture.image' => 'The profile picture must be an image.',
            'profile_picture.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
            'profile_picture.max' => 'The profile picture may not be greater than 2MB.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $validatedData = $validator->validated();
    
        // Sanitize inputs
        $validatedData['email'] = filter_var($validatedData['email'], FILTER_SANITIZE_EMAIL);
        if ($role !== 'admin') {
            $validatedData['first_name'] = filter_var($validatedData['first_name'], FILTER_SANITIZE_STRING);
            $validatedData['last_name'] = filter_var($validatedData['last_name'], FILTER_SANITIZE_STRING);
        } else {
            $validatedData['name'] = filter_var($validatedData['name'], FILTER_SANITIZE_STRING);
        }
    
        // Update the email on the user record
        $user->email = $validatedData['email'];
    
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }
    
        $user->save();
    
        // Update the role-specific model (example for teacher; do similarly for other roles)
        switch ($role) {
            case 'admin':
                $roleData = $user->admin;
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
            // ... repeat for student, teacher, nurse, doctor, and staff ...
        }
    
        // If you previously handled profile picture uploads in a role-specific model,
        // do that here as well. For example, if teachers store their profile picture in the teacher record:
        if ($role !== 'admin') {
            // (Example for teacher; adjust similarly for other roles)
            if ($role === 'teacher') {
                $roleData = Teacher::where('id_number', $user->id_number)->first();
                if ($roleData && $request->hasFile('profile_picture')) {
                    if ($roleData->profile_picture && Storage::disk('public')->exists($roleData->profile_picture)) {
                        Storage::disk('public')->delete($roleData->profile_picture);
                    }
                    $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $roleData->profile_picture = $path;
                    $roleData->save();
                }
            }
        }
    
        // If the email was changed, reset the verification timestamp and send a new verification email
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
    
            // Send a verification email using Laravel's built-in method
            $user->sendEmailVerificationNotification();
    
            // Optionally, send a notification to the original email address about the change
            Mail::to($originalEmail)->send(new EmailChangeNotification($user, $originalEmail));
    
            return redirect()->back()->with('email_verification_required', true);
        }
    
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
