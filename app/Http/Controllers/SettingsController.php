<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
    
        switch ($user->role) {
            case 'Admin': // Match the exact role name as it appears in the database
                return view('admin.settings', compact('user'));
            case 'Nurse':
                return view('nurse.settings', compact('user'));
            case 'Doctor':
                return view('doctor.settings', compact('user'));
            case 'Teacher':
                return view('teacher.settings', compact('user'));
            case 'Staff':
                return view('staff.settings', compact('user'));
            case 'Student':
                return view('student.settings', compact('user'));
            case 'Parent':
                return view('parent.settings', compact('user'));
            default:
                return view('settings.default', compact('user')); // Fallback view
        }
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
        ];
    
        // Role-specific validation
        switch (strtolower($user->role)) {
            case 'parent':
                $rules = array_merge($rules, [
                    'parent_name_father'       => 'nullable|string|max:255',
                    'parent_name_mother'       => 'nullable|string|max:255',
                    'guardian_name'            => 'nullable|string|max:255',
                    'guardian_relationship'    => 'nullable|string|max:255',
                ]);
                break;
            case 'student':
            case 'teacher':
            case 'nurse':
            case 'doctor':
            case 'staff':
                $rules = array_merge($rules, [
                    'emergency_contact_number' => 'nullable|string|max:15',
                    'personal_contact_number'  => 'nullable|string|max:15',
                ]);
                break;
            // Add more cases if necessary
        }
    
        // Validate the request
        $request->validate($rules);
    
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
    
        // Update role-specific information
        $this->updateRoleSpecificFields($user, $request);
    
        // Check if email was changed to require verification
        if ($user->wasChanged('email')) {
            $user->email_verified_at = null; // Invalidate email verification
            $user->save();
    
            // Send verification email manually
            $user->sendEmailVerificationNotification();
    
            // Update role-specific email if applicable
            $this->updateRoleSpecificEmail($user, $request->email);
    
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
                $parent = \App\Models\ParentModel::where('user_id', $user->id)->first();
                if ($parent) {
                    $parent->parent_name_father     = $request->parent_name_father;
                    $parent->parent_name_mother     = $request->parent_name_mother;
                    $parent->guardian_name          = $request->guardian_name;
                    $parent->guardian_relationship  = $request->guardian_relationship;
                    $parent->save();
                }
                break;
            case 'student':
                $student = \App\Models\Student::where('user_id', $user->id)->first();
                if ($student) {
                    $student->emergency_contact_number = $request->emergency_contact_number;
                    $student->personal_contact_number  = $request->personal_contact_number;
                    $student->save();
                }
                break;
            case 'teacher':
            case 'nurse':
            case 'doctor':
            case 'staff':
                $roleModel = $this->getRoleModel($user->role);
                if ($roleModel) {
                    $roleInstance = $roleModel::where('user_id', $user->id)->first();
                    if ($roleInstance) {
                        $roleInstance->emergency_contact_number = $request->emergency_contact_number;
                        $roleInstance->personal_contact_number  = $request->personal_contact_number;
                        $roleInstance->save();
                    }
                }
                break;
            // Add more cases if necessary
            default:
                // No role-specific fields to update
                break;
        }
    }

    /**
     * Get the corresponding model based on role.
     */
    protected function getRoleModel($role)
    {
        switch (strtolower($role)) {
            case 'teacher':
                return \App\Models\Teacher::class;
            case 'nurse':
                return \App\Models\Nurse::class;
            case 'doctor':
                return \App\Models\Doctor::class;
            case 'staff':
                return \App\Models\Staff::class;
            // Add more roles if necessary
            default:
                return null;
        }
    }

    /**
     * Update email in the role-specific table.
     */
    protected function updateRoleSpecificEmail($user, $email)
    {
        switch (strtolower($user->role)) {
            case 'doctor':
                \App\Models\Doctor::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'nurse':
                \App\Models\Nurse::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'teacher':
                \App\Models\Teacher::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'staff':
                \App\Models\Staff::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'parent':
                \App\Models\ParentModel::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'student':
                \App\Models\Student::where('user_id', $user->id)->update(['email' => $email]);
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
