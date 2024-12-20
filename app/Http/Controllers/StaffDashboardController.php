<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\MedicalRecord;
use App\Models\DentalRecord;
use App\Models\Information;
use App\Models\Parents;
use App\Models\Notification;
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
            $request->validate([
                'parent_name_father' => ['nullable', 'regex:/^[A-Za-z\s]+$/'],
                'parent_name_mother' => ['nullable', 'regex:/^[A-Za-z\s]+$/'],
                'guardian_first_name' => ['required', 'regex:/^[A-Za-z\s]+$/'],
                'guardian_last_name' => ['required', 'regex:/^[A-Za-z\s]+$/'],
                'guardian_relationship' => ['nullable', 'regex:/^[A-Za-z\s]+$/'],
                'emergency_contact_number' => ['required', 'digits:11'],
                'personal_contact_number' => ['required', 'digits:11'],
                'birthdate' => 'required|date',
                'address' => 'required|string|max:255',
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            // Process the uploaded profile picture
            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
    
            // Save student's information
            Information::create([
                'id_number' => $request->id_number,
                'parent_name_father' => $request->parent_name_father,
                'parent_name_mother' => $request->parent_name_mother,
                'guardian_name' => $request->guardian_first_name . ' ' . $request->guardian_last_name,
                'guardian_relationship' => $request->guardian_relationship,
                'emergency_contact_number' => $request->emergency_contact_number,
                'personal_contact_number' => $request->personal_contact_number,
                'birthdate' => $request->birthdate,
                'address' => $request->address,
                'profile_picture' => $profilePicture,
            ]);
    
            // Create parent account
            $staffIdNumber = $request->id_number;
    
            // Generate parent id_number by replacing the first character with 'P'
            $parentIdNumber = 'P' . substr($staffIdNumber, 1);
    
            // Create the parent account
            $parent = Parents::create([
                'id_number' => $parentIdNumber,
                'first_name' => $request->guardian_first_name,
                'last_name' => $request->guardian_last_name,
                'student_id' => $staffIdNumber,
                'approved' => 1, // Automatically approved
            ]);
    
            // Return response including parent account details
            return response()->json([
                'success' => true,
                'parent_id_number' => $parentIdNumber
            ]);
    
        } catch (\Exception $e) {
            // Log the detailed error message
            \Log::error('Profile Update Error: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
    
}  