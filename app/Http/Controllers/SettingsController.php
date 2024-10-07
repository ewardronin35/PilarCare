<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ParentModel; // Adjust based on your actual model names
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Information;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Staff;

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
                $roleData = ParentModel::where('id_number', $user->id_number)->first();
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

        return view($viewPath, compact('user', 'roleData' ,'information'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
    
        // Base validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6',
            'address'    => 'required|string|max:500',
            'birthdate'  => 'required|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for image
        ];
    
        // Role-specific validation
        $role = strtolower(trim($user->role));
        if ($role === 'parent') {
            $rules = array_merge($rules, [
                'parent_name_father'       => 'nullable|string|max:255',
                'parent_name_mother'       => 'nullable|string|max:255',
                'guardian_name'            => 'nullable|string|max:255',
                'guardian_relationship'    => 'nullable|string|max:255',
                'emergency_contact_number' => 'nullable|string|regex:/^\d{11}$/',
                'personal_contact_number'  => 'nullable|string|regex:/^\d{11}$/',
            ]);
        } elseif (in_array($role, ['student', 'teacher', 'nurse', 'doctor', 'staff'])) {
            $rules = array_merge($rules, [
                'emergency_contact_number' => 'nullable|string|regex:/^\d{11}$/',
                'personal_contact_number'  => 'nullable|string|regex:/^\d{11}$/',
            ]);
        }
    
        // Custom validation messages
        $messages = [
            'emergency_contact_number.regex' => 'The emergency contact number must be exactly 11 digits.',
            'personal_contact_number.regex'  => 'The personal contact number must be exactly 11 digits.',
            'profile_image.image'            => 'The profile picture must be an image.',
            'profile_image.mimes'            => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
            'profile_image.max'              => 'The profile picture may not be greater than 2MB.',
        ];
    
        // Validate the request
        $request->validate($rules, $messages);
    
        // Update common user info
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
    
        // Update password if provided
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        // Update additional common fields
        $user->address   = $request->address;
        $user->birthdate = $request->birthdate;
    
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // Store new image
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }
    
        // Save user data
        $user->save();
    
        // Update Information model
        $information = Information::firstOrCreate(
            ['id_number' => $user->id_number],
            []
        );
    
        $information->birthdate = $user->birthdate;
        $information->address = $user->address;
    
        if ($role === 'parent') {
            $information->parent_name_father = $request->parent_name_father;
            $information->parent_name_mother = $request->parent_name_mother;
            $information->guardian_name = $request->guardian_name;
            $information->guardian_relationship = $request->guardian_relationship;
        }
    
        if (in_array($role, ['student', 'teacher', 'nurse', 'doctor', 'staff'])) {
            $information->emergency_contact_number = $request->emergency_contact_number;
            $information->personal_contact_number  = $request->personal_contact_number;
        }
    
        $information->save();
    
        // Check if email was changed to require verification
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null; // Invalidate email verification
            $user->save();
    
            // Send verification email manually
            $user->sendEmailVerificationNotification();
    
            // Redirect back with email verification required
            return redirect()->back()->with('email_verification_required', true);
        }
    
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Update role-specific fields.
     */
    protected function updateRoleSpecificFields($user, $request)
    {
        switch (strtolower($user->role)) {
            case 'parent':
                $parent = ParentModel::where('id_number', $user->id_number)->first();
                if ($parent) {
                    $parent->parent_name_father     = $request->parent_name_father;
                    $parent->parent_name_mother     = $request->parent_name_mother;
                    $parent->guardian_name          = $request->guardian_name;
                    $parent->guardian_relationship  = $request->guardian_relationship;
                    $parent->emergency_contact_number = $request->emergency_contact_number;
                    $parent->personal_contact_number  = $request->personal_contact_number;
                    $parent->save();
                } else {
                    // Create if not exists
                    ParentModel::create([
                        'user_id'                   => $user->id,
                        'id_number'                 => $user->id_number,
                        'parent_name_father'        => $request->parent_name_father,
                        'parent_name_mother'        => $request->parent_name_mother,
                        'guardian_name'             => $request->guardian_name,
                        'guardian_relationship'     => $request->guardian_relationship,
                        'emergency_contact_number'  => $request->emergency_contact_number,
                        'personal_contact_number'   => $request->personal_contact_number,
                        'address'                   => $user->address,
                        'birthdate'                 => $user->birthdate,
                        'profile_image'             => $user->profile_image,
                    ]);
                }
                break;
            case 'student':
                $student = Student::where('id_number', $user->id_number)->first();
                if ($student) {
                    $student->emergency_contact_number = $request->emergency_contact_number;
                    $student->personal_contact_number  = $request->personal_contact_number;
                    $student->save();
                } else {
                    Student::create([
                        'user_id'                   => $user->id,
                        'id_number'                 => $user->id_number,
                        'emergency_contact_number'  => $request->emergency_contact_number,
                        'personal_contact_number'   => $request->personal_contact_number,
                        'address'                   => $user->address,
                        'birthdate'                 => $user->birthdate,
                        'profile_image'             => $user->profile_image,
                    ]);
                }
                break;
            case 'teacher':
                $teacher = Teacher::where('id_number', $user->id_number)->first();
                if ($teacher) {
                    $teacher->emergency_contact_number = $request->emergency_contact_number;
                    $teacher->personal_contact_number  = $request->personal_contact_number;
                    $teacher->save();
                } else {
                    Teacher::create([
                        'user_id'                   => $user->id,
                        'id_number'                 => $user->id_number,
                        'emergency_contact_number'  => $request->emergency_contact_number,
                        'personal_contact_number'   => $request->personal_contact_number,
                        'address'                   => $user->address,
                        'birthdate'                 => $user->birthdate,
                        'profile_image'             => $user->profile_image,
                    ]);
                }
                break;
            case 'nurse':
                $nurse = Nurse::where('id_number', $user->id_number)->first();
                if ($nurse) {
                    $nurse->emergency_contact_number = $request->emergency_contact_number;
                    $nurse->personal_contact_number  = $request->personal_contact_number;
                    $nurse->save();
                } else {
                    Nurse::create([
                        'user_id'                   => $user->id,
                        'id_number'                 => $user->id_number,
                        'emergency_contact_number'  => $request->emergency_contact_number,
                        'personal_contact_number'   => $request->personal_contact_number,
                        'address'                   => $user->address,
                        'birthdate'                 => $user->birthdate,
                        'profile_image'             => $user->profile_image,
                    ]);
                }
                break;
            case 'doctor':
                $doctor = Doctor::where('id_number', $user->id_number)->first();
                if ($doctor) {
                    $doctor->emergency_contact_number = $request->emergency_contact_number;
                    $doctor->personal_contact_number  = $request->personal_contact_number;
                    $doctor->save();
                } else {
                    Doctor::create([
                        'user_id'                   => $user->id,
                        'id_number'                 => $user->id_number,
                        'emergency_contact_number'  => $request->emergency_contact_number,
                        'personal_contact_number'   => $request->personal_contact_number,
                        'address'                   => $user->address,
                        'birthdate'                 => $user->birthdate,
                        'profile_image'             => $user->profile_image,
                    ]);
                }
                break;
            case 'staff':
                $staff = Staff::where('id_number', $user->id_number)->first();
                if ($staff) {
                    $staff->emergency_contact_number = $request->emergency_contact_number;
                    $staff->personal_contact_number  = $request->personal_contact_number;
                    $staff->save();
                } else {
                    Staff::create([
                        'user_id'                   => $user->id,
                        'id_number'                 => $user->id_number,
                        'emergency_contact_number'  => $request->emergency_contact_number,
                        'personal_contact_number'   => $request->personal_contact_number,
                        'address'                   => $user->address,
                        'birthdate'                 => $user->birthdate,
                        'profile_image'             => $user->profile_image,
                    ]);
                }
                break;
            // Add more cases if necessary
            default:
                // No role-specific fields to update
                break;
        }
    }

    /**
     * Update email in the role-specific table.
     */
    protected function updateRoleSpecificEmail($user, $email)
    {
        switch (strtolower($user->role)) {
            case 'doctor':
                Doctor::where('id_number', $user->id_number)->update(['email' => $email]);
                break;
            case 'nurse':
                Nurse::where('id_number', $user->id_number)->update(['email' => $email]);
                break;
            case 'teacher':
                Teacher::where('id_number', $user->id_number)->update(['email' => $email]);
                break;
            case 'staff':
                Staff::where('id_number', $user->id_number)->update(['email' => $email]);
                break;
            case 'parent':
                ParentModel::where('id_number', $user->id_number)->update(['email' => $email]);
                break;
            case 'student':
                Student::where('id_number', $user->id_number)->update(['email' => $email]);
                break;
            default:
                // No role-specific update required
                break;
        }
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
