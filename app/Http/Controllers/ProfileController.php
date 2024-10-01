<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Nurse;      // Ensure these models exist
use App\Models\Doctor;  
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'parent_contact' => 'required|string|max:15',
            'birthdate' => 'required|date',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $user->parent_name = $request->parent_name;
        $user->parent_contact = $request->parent_contact;
        $user->birthdate = $request->birthdate;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $filePath;
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Store the user's profile picture.
     */
    public function storeProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $filePath;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-picture-updated');
    }
    public function index(Request $request): View
    {
        $role = $request->input('role', 'students'); // Default to students
        $search = $request->input('search', null);

        // Determine the model based on role
        switch ($role) {
            case 'students':
                $query = Student::query();
                break;
            case 'teachers':
                $query = Teacher::query();
                break;
            case 'nurses':
                $query = Nurse::query();
                break;
            case 'doctors':
                $query = Doctor::query();
                break;
            case 'staff':
                $query = Staff::query();
                break;
            case 'parents':
                $query = Parents::query();
                break;
            default:
                $query = Student::query(); // Default to students
                break;
        }

        // Apply search filters if any
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('id_number', 'LIKE', "%{$search}%");
            });
        }

        // Handle AJAX requests for dynamic fetching
        if ($request->ajax()) {
            $profiles = $query->get(['first_name', 'last_name', 'id_number']);
            return response()->json($profiles);
        }

        // For standard page load
        $profiles = $query->get(['first_name', 'last_name', 'id_number']);

        return view('admin.profiles', compact('profiles', 'role'));
    }
    
}
