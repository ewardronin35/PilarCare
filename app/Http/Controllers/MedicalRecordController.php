<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Information;
use App\Models\PhysicalExamination;
use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

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
        $medicalRecord = MedicalRecord::where('user_id', $user->id)->first();
        
        // Fetch all medical records for the user
        $medicalRecords = MedicalRecord::where('user_id', $user->id)->get();
        
        // Fetch all physical examination records
        $physicalExaminations = PhysicalExamination::where('user_id', $user->id)->get();
    
        // Fetch health examination pictures for the user
        $healthExamination = HealthExamination::where('user_id', $user->id)->first(); // Ensure this is fetched
    
        $healthExaminationPictures = HealthExamination::where('user_id', $user->id)
            ->select('health_examination_picture', 'xray_picture', 'lab_result_picture')
            ->get();
    
        // Combine first name and last name
        $name = $user->first_name . ' ' . $user->last_name;
    
        // Calculate age based on the birthdate
        $age = $information ? Carbon::parse($information->birthdate)->age : null;
    
        // Check the role of the user and return the corresponding view with all variables
        switch ($user->role) {
            case 'Admin':
                return view('admin.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations', 'healthExaminationPictures'));
            case 'Student':
                return view('student.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations' , 'healthExaminationPictures'));
            case 'Parent':
                return view('parent.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations' , 'healthExaminationPictures'));
            case 'Staff':
                return view('staff.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations' , 'healthExaminationPictures'));
            case 'Teacher':
                return view('teacher.medical-record', compact('information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations' , 'healthExaminationPictures'));
            default:
                return redirect()->route('home')->with('error', 'Unauthorized access');
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
        public function downloadPdf($id)
        {
            // Fetch the medical record by ID
            $medicalRecord = MedicalRecord::findOrFail($id);
            
            // Fetch the related information by 'id_number'
            $information = Information::where('id_number', $medicalRecord->id_number)->first();
        
            // Fetch the physical examination record
            $physicalExamination = PhysicalExamination::where('user_id', $medicalRecord->user_id)->first();
            
            // Initialize the profile picture base64 variable
            $profilePictureBase64 = null;
        
            // Check if the information has a profile picture and convert it to base64
            if ($information && $information->profile_picture) {
                // Profile picture path stored in the information table
                $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
                
                // Check if the profile picture file exists before converting it to base64
                if (file_exists($profilePicturePath)) {
                    $profilePictureBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($profilePicturePath));
                } else {
                    // Log the error if file doesn't exist
                    \Log::error('Profile picture not found at: ' . $profilePicturePath);
                }
            }
        
            // Use a default profile picture if no custom picture is available
            if (!$profilePictureBase64) {
                $defaultProfilePicturePath = public_path('images/default-profile.jpg');
                if (file_exists($defaultProfilePicturePath)) {
                    $profilePictureBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($defaultProfilePicturePath));
                }
            }
        
            // Fetch the clinic logo and encode it in base64
            $logoPath = public_path('images/pilarLogo.jpg');
            $logoBase64 = "data:image/jpg;base64," . base64_encode(file_get_contents($logoPath));
        
            // Pass the data to the PDF view
            $pdf = PDF::loadView('pdf.medical-record', [
                'medicalRecord' => $medicalRecord,
                'physicalExamination' => $physicalExamination,
                'profilePicture' => $profilePictureBase64, // This is now in base64 format
                'logoBase64' => $logoBase64
            ]);
        
            // Return the PDF for download with a dynamic file name
            return $pdf->download('medical_record_' . $medicalRecord->name . '.pdf');
        }
        
}
