<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\MedicalRecord;
use App\Models\DentalRecord;
use App\Models\Information;
use App\Models\Parents;
use App\Models\Notification;
use App\Models\SchoolYear;
use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = Appointment::where('id_number', $user->id_number)->get();
        $appointmentCount = $appointments->count();
    
        $complaints = Complaint::where('id_number', $user->id_number)->get();
        $complaintCount = $complaints->count();
        $notifications = Notification::where('user_id', $user->id_number)->get();
    
        // Check if the user's profile information is complete
        $information = Information::where('id_number', $user->id_number)->first();
        $showModal = !$information; // If no information exists, show the modal
    
        // Fetch the current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
    
        // Fetch health examination for the current school year
        $healthExamination = null;
        $hasHealthExamination = false;
    
        if ($currentSchoolYear) {
            $healthExamination = HealthExamination::where('id_number', $user->id_number)
                ->where('school_year', $currentSchoolYear->year)
                ->first();
            $hasHealthExamination = $healthExamination !== null;
        }
    
        // Fetch dental records (if needed)
        $dentalRecords = DentalRecord::where('id_number', $user->id_number)->get();
        $hasDentalRecord = $dentalRecords->isNotEmpty();
    
        // Fetch medical records (if needed)
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get();
        $hasMedicalRecord = $medicalRecords->isNotEmpty();
    
        return view('staff.StaffDashboard', compact(
            'appointments',
            'appointmentCount',
            'complaints',
            'complaintCount',
            'showModal',
            'notifications',
            'hasHealthExamination',
            'hasDentalRecord',
            'hasMedicalRecord'
        ));
    }
    
    public function storeProfile(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'parent_name_father' => ['nullable', 'regex:/^[A-Za-z\s]+$/'],
                'parent_name_mother' => ['nullable', 'regex:/^[A-Za-z\s]+$/'],
                'guardian_first_name' => ['nullable', 'string'],
                'guardian_last_name' => ['nullable', 'string'],
                'guardian_relationship' => ['nullable', 'string'],
                'emergency_contact_number' => ['required', 'digits:11'],
                'personal_contact_number' => ['required', 'digits:11'],
                'birthdate' => 'required|date',
                'address' => 'required|string|max:255',
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
    
            // Process the uploaded profile picture
            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
    
            // Conditionally set guardian_name if both first and last names are provided
            $guardianName = null;
            if ($request->filled('guardian_first_name') && $request->filled('guardian_last_name')) {
                $guardianName = $request->guardian_first_name . ' ' . $request->guardian_last_name;
            }
    
            // Conditionally set guardian_relationship if provided
            $guardianRelationship = $request->filled('guardian_relationship') ? $request->guardian_relationship : null;
    
            // Save student's information
            Information::create([
                'id_number' => $request->id_number,
                'parent_name_father' => $request->parent_name_father,
                'parent_name_mother' => $request->parent_name_mother,
                'guardian_name' => $guardianName,
                'guardian_relationship' => $guardianRelationship,
                'emergency_contact_number' => $request->emergency_contact_number,
                'personal_contact_number' => $request->personal_contact_number,
                'birthdate' => $request->birthdate,
                'address' => $request->address,
                'profile_picture' => $profilePicture,
            ]);
    
            // Return response indicating success
            return response()->json(['success' => true]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::debug('Validation errors:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Log the detailed error message
            \Log::error('Profile Update Error: ' . $e->getMessage());
    
            // Return a generic error response
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
    
    
}  