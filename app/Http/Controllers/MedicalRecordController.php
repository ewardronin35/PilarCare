<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Information;
use App\Models\PhysicalExamination;
use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $role = strtolower($user->role);
        $information = Information::where('id_number', $user->id_number)->first();
        $medicalRecord = MedicalRecord::where('name', $user->name)->first();
        $medicalRecords = MedicalRecord::where('user_id', $user->id)->get(); // Fetch records for the authenticated user
        $age = $information ? Carbon::parse($information->birthdate)->age : null;
        $name = $user->first_name . ' ' . $user->last_name;
        $physicalExaminations = PhysicalExamination::all();
        $healthExamination = HealthExamination::where('user_id', $user->id)->first();

        return view("$role.medical-record", compact('information', 'medicalRecord', 'medicalRecords', 'physicalExaminations', 'name', 'age', 'healthExamination'));
    }
    

        public function index()
        {
            $user = Auth::user();
        
            // Fetch information from the Information table
            $information = Information::where('id_number', $user->id_number)->first();
            
            // Fetch the specific medical record for the user
            $medicalRecord = MedicalRecord::where('name', $user->first_name . ' ' . $user->last_name)->first();
            
            // Fetch all medical records
            $medicalRecords = MedicalRecord::all();
        
            // Fetch all physical examination records (assuming you have a model for it)
            $physicalExaminations = PhysicalExamination::all(); // Adjust this to match your actual model and filtering
        
            // Combine first name and last name from the user table
            $name = $user->first_name . ' ' . $user->last_name;
        
            // Calculate the age based on the birthdate
            $age = $information ? Carbon::parse($information->birthdate)->age : null;
        
            // Fetch the Health Examination data
            $healthExamination = HealthExamination::where('user_id', $user->id)->first();
        
            // Check the role of the user and return the corresponding view with all variables
            switch ($user->role) {
                case 'Admin':
                    return view('admin.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations')); // View for admin
                case 'Student':
                    return view('student.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations')); // View for student
                case 'Parent':
                    return view('parent.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations')); // View for parent
                case 'Staff':
                    return view('staff.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations')); // View for staff
                case 'Teacher':
                    return view('teacher.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations')); // View for teacher
                default:
                    return redirect()->route('home')->with('error', 'Unauthorized access'); // Redirect for unauthorized access
            }
        }
        
    
    
        public function store(Request $request)
        {
            // Get the authenticated user
            $user = Auth::user();
        
            // Check if a medical record already exists for this user
            $existingRecord = MedicalRecord::where('user_id', $user->id)->first();
            if ($existingRecord) {
                return redirect()->back()->withErrors(['error' => 'A medical record for this user already exists.']);
            }
        
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'age' => 'required|integer',
                'address' => 'required|string|max:255',
                'personal_contact_number' => 'required|string|max:15',
                'emergency_contact_number' => 'required|string|max:15',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'past_illness' => 'required|string|max:255',
                'chronic_conditions' => 'required|string|max:255',
                'surgical_history' => 'required|string|max:255',
                'family_medical_history' => 'required|string|max:255',
                'allergies' => 'required|string|max:255',
                'medicines' => 'required|array',
                'profile_picture' => 'nullable|image|max:2048',
            ]);
        
            // Handle file upload for profile picture
            $filePath = null;
            if ($request->hasFile('profile_picture')) {
                $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }
        
            // Create or update the medical record
            MedicalRecord::create([
                'user_id' => $user->id,  // Store the user_id
                'name' => $request->name,
                'birthdate' => $request->birthdate,
                'age' => Carbon::parse($request->birthdate)->age, // Calculate age based on birthdate
                'address' => $request->address,
                'personal_contact_number' => $request->personal_contact_number,
                'emergency_contact_number' => $request->emergency_contact_number,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'past_illness' => $request->past_illness,
                'chronic_conditions' => $request->chronic_conditions,
                'surgical_history' => $request->surgical_history,
                'family_medical_history' => $request->family_medical_history,
                'allergies' => $request->allergies,
                'medicines' => json_encode($request->medicines),
                'profile_picture' => $filePath,
            ]);
        
            return redirect()->route(strtolower($user->role) . '.medical-record.create')->with('success', 'Medical record saved successfully!');
        }
        
        public function search(Request $request)
        {
            $query = $request->input('query'); // Assuming 'query' can be name, contact number, or user_id
        
            // Initialize the query builder
            $medicalRecordQuery = MedicalRecord::query();
        
            // Check if the query is numeric (assuming user_id is numeric)
            if (is_numeric($query)) {
                $medicalRecordQuery->where('user_id', $query);
            } else {
                $medicalRecordQuery->where('name', 'LIKE', "%{$query}%")
                                   ->orWhere('personal_contact_number', 'LIKE', "%{$query}%");
            }
        
            // Execute the query
            $record = $medicalRecordQuery->first();
        
            if ($record) {
                // Get the related information (if applicable)
                $information = Information::where('id_number', $record->user_id)->first();
        
                // Get the related physical examination (if applicable)
                $physicalExamination = PhysicalExamination::where('user_id', $record->user_id)->first();
        
                // Get the related health examination (if applicable)
                $healthExamination = HealthExamination::where('user_id', $record->user_id)->first();
        
                // Pass the data to the view
                return view('admin.medical-record', compact('record', 'information', 'physicalExamination', 'healthExamination'));
            } else {
                // Redirect back with an error message if no records are found
                return redirect()->back()->with('error', 'No records found.');
            }
        }
        
        
        
}
