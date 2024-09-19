<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    // Display the settings form
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
    

   
    public function update(Request $request)
    {
        $user = Auth::user();
    
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);
    
        // Common user info update
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
    
        // Check if the email has changed
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null; // Invalidate email verification
            $user->save();
    
            // Send verification email manually
            $user->sendEmailVerificationNotification();
    
            // Now update the email in the role-specific table as well
            $this->updateRoleSpecificEmail($user, $request->email);
    
            // Return with email verification required message
            return redirect()->back()->with('email_verification_required', true);
        }
    
        // Update password if provided
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save(); // Save user data
    
        // Update first and last name in role-specific table
        $this->updateRoleSpecificName($user, $request->first_name, $request->last_name);
    
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Update email in the role-specific table.
     */
    protected function updateRoleSpecificEmail($user, $email)
    {
        switch ($user->role) {
            case 'Doctor':
                \App\Models\Doctor::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'Nurse':
                \App\Models\Nurse::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'Teacher':
                \App\Models\Teacher::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'Staff':
                \App\Models\Staff::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'Parent':
                \App\Models\ParentModel::where('user_id', $user->id)->update(['email' => $email]);
                break;
            case 'Student':
                \App\Models\Student::where('user_id', $user->id)->update(['email' => $email]);
                break;
            default:
                // No role-specific update required
                break;
        }
    }
    
    /**
     * Update first and last name in the role-specific table.
     */
    protected function updateRoleSpecificName($user, $firstName, $lastName)
    {
        switch ($user->role) {
            case 'Doctor':
                \App\Models\Doctor::where('user_id', $user->id)->update(['first_name' => $firstName, 'last_name' => $lastName]);
                break;
            case 'Nurse':
                \App\Models\Nurse::where('user_id', $user->id)->update(['first_name' => $firstName, 'last_name' => $lastName]);
                break;
            case 'Teacher':
                \App\Models\Teacher::where('user_id', $user->id)->update(['first_name' => $firstName, 'last_name' => $lastName]);
                break;
            case 'Staff':
                \App\Models\Staff::where('user_id', $user->id)->update(['first_name' => $firstName, 'last_name' => $lastName]);
                break;
            case 'Parent':
                \App\Models\ParentModel::where('user_id', $user->id)->update(['first_name' => $firstName, 'last_name' => $lastName]);
                break;
            case 'Student':
                \App\Models\Student::where('user_id', $user->id)->update(['first_name' => $firstName, 'last_name' => $lastName]);
                break;
            default:
                // No role-specific update required
                break;
        }
    }
    

    public function updateImage(Request $request)
    {
        $user = Auth::user();

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }

    public function delete()
    {
        $user = Auth::user();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}

