<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Information;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = Appointment::where('id_number', $user->id_number)->get();
        $appointmentCount = $appointments->count();
        
        $complaints = Complaint::where('id_number', $user->id_number)->get();
        $complaintCount = $complaints->count();
        $notifications = Notification::where('user_id', Auth::user()->id_number)->get();

        // Check if the user's profile information is complete
        $information = Information::where('id_number', $user->id_number)->first();
        $showModal = !$information; // If no information exists, show the modal

        return view('student.StudentDashboard', compact('appointments', 'appointmentCount', 'complaints', 'complaintCount', 'showModal', 'notifications'));
    }
    
    public function storeProfile(Request $request)
    {
        try {
            $request->validate([
                'parent_name_father' => ['required', 'regex:/^[A-Za-z\s]+$/'], // Only letters and spaces
                'parent_name_mother' => ['required', 'regex:/^[A-Za-z\s]+$/'], // Only letters and spaces
                'emergency_contact_number' => ['required', 'digits:11'], // Exactly 11 digits
                'personal_contact_number' => ['required', 'digits:11'], // Exactly 11 digits
                'birthdate' => 'required|date',
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'medical_history' => 'required|string',
                'allergies' => 'required|string',
                'medicines' => 'required|array',
                'surgical_history' => 'required|string',
                'chronic_conditions' => 'required|string',
                'agree_terms' => 'required|accepted',
                'id_number' => 'required|string|max:7',
            ]);
        
            // Process the uploaded profile picture
            $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
        
            Information::create([
                'id_number' => $request->id_number,
                'parent_name_father' => $request->parent_name_father,
                'parent_name_mother' => $request->parent_name_mother,
                'emergency_contact_number' => $request->emergency_contact_number,
                'personal_contact_number' => $request->personal_contact_number,
                'birthdate' => $request->birthdate,
                'address' => $request->address,
                'profile_picture' => $profilePicture,
                'medical_history' => $request->medical_history,
                'allergies' => $request->allergies,
                'medicines' => implode(',', $request->medicines),
                'surgical_history' => $request->surgical_history,
                'chronic_conditions' => $request->chronic_conditions,
            ]);
    
            return response()->json(['success' => true]);
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
