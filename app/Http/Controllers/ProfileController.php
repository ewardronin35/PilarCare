<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Parents; // Ensure this model exists and is correctly named
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Add this import
use Illuminate\Http\JsonResponse; // Add this import

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

    /**
     * Display the profiles view.
     */
    public function index(Request $request): View
    {
        // Fetch initial profiles for the default role (students)
        $role = 'students';
        $query = Student::query();

        $profiles = $query->get(['first_name', 'last_name', 'id_number']);

        return view('admin.profiles', compact('profiles', 'role'));
    }

    /**
     * Fetch profiles based on role and search query.
     */
    public function fetchProfiles(Request $request): JsonResponse
    {
        $role = strtolower($request->input('role', 'students'));
        $search = $request->input('search');

        // Define a mapping between roles and their corresponding models
        $modelMapping = [
            'students' => Student::class,
            'teachers' => Teacher::class,
            'nurses' => Nurse::class,
            'doctors' => Doctor::class,
            'staff' => Staff::class,
            'parents' => Parents::class, // Ensure this matches your actual model
        ];

        if (!array_key_exists($role, $modelMapping)) {
            Log::error("Invalid role received: {$role}");
            return response()->json(['error' => 'Invalid role provided.'], 400);
        }

        $model = $modelMapping[$role];

        // Initialize the query
        $query = $model::query();

        // Apply search filters if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('id_number', 'LIKE', "%{$search}%");
            });
        }

        try {
            $profiles = $query->get();

            // Map profiles to include full URLs for profile pictures and PDFs
            $profiles = $profiles->map(function($profile) use ($role) {
                return [
                    'first_name' => $profile->first_name,
                    'last_name' => $profile->last_name,
                    'role' => $role, // Use the role from the request
                    'birthdate' => $profile->birthdate,
                    'description' => $profile->description ?? '',
                    'id_number' => $profile->id_number,
                    'profile_picture_url' => $profile->profile_picture 
                        ? asset('storage/profiles/' . $profile->profile_picture) 
                        : asset('images/pilarLogo.png'),
                    'pdf_url' => $profile->pdf_file 
                        ? asset('storage/pdfs/' . $profile->pdf_file) 
                        : null,
                ];
            });

            return response()->json($profiles, 200);

        } catch (\Exception $e) {
            Log::error("Error fetching profiles: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching profiles.'], 500);
        }
    }
}
