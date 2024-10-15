<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Information;
use App\Models\PhysicalExamination;
use App\Models\HealthExamination;
use App\Models\User;
use App\Models\Nutse;
use App\Models\Doctor;


use App\Models\MedicineIntake;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get(); // Fetch records for the authenticated user
        $age = $information ? Carbon::parse($information->birthdate)->age : null;
        $name = $user->first_name . ' ' . $user->last_name;
        $physicalExaminations = PhysicalExamination::where('id_number', $user->id_number)->get();
        $healthExamination = HealthExamination::where('id_number', $user->id_number)->first();

        return view("$role.medical-record", compact('information', 'medicalRecord', 'medicalRecords', 'physicalExaminations', 'name', 'age', 'healthExamination'));
    }
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role);
        
        // Fetch latest medical record
        $latestMedicalRecord = MedicalRecord::where('id_number', $user->id_number)
                                            ->where('is_current', true)
                                            ->first();
        
        // Fetch user information
        $information = Information::where('id_number', $user->id_number)->first();
        
        // Fetch medical record with related medicine intake records
        $medicalRecord = MedicalRecord::with('medicineIntakes')->where('id_number', $user->id_number)->first();
        
        // Fetch all medical records
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get();
        
        // Fetch all physical examinations
        $physicalExaminations = PhysicalExamination::where('id_number', $user->id_number)->get();
        
        // Fetch health examination record
        $healthExamination = HealthExamination::where('id_number', $user->id_number)->first();
        
        // Fetch health examination pictures
        $healthExaminationPictures = HealthExamination::where('id_number', $user->id_number)
            ->select('school_year', 'health_examination_picture', 'xray_picture', 'lab_result_picture')
            ->get();
        
        // Fetch profile picture from Information table
        $profilePicture = $information ? $information->profile_picture : null;
        
        // Concatenate first name and last name
        $name = $user->first_name . ' ' . $user->last_name;
        
        // Calculate age based on birthdate
        $age = $information ? Carbon::parse($information->birthdate)->age : null;
    
        // Return the view based on the user's role
        return view("$role.medical-record", compact('user', 'information', 'name', 'age', 'healthExamination', 'medicalRecord', 'medicalRecords', 'physicalExaminations', 'healthExaminationPictures','latestMedicalRecord', 'profilePicture'));
    }
    
    
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
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
                'medical_condition' => 'required|string|max:255',
                'medicines' => 'required|array',
                'medicines.*' => 'string|max:255',
                'health_documents' => 'nullable|array',
                'health_documents.*' => 'file|mimes:jpg,png,jpeg,pdf|max:10008',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'record_date' => 'required|date',
                'is_approved' => 'nullable|boolean',
                'is_current' => 'nullable|boolean',
            ]);
    
            // Check if the last medical record is not approved
            $lastMedicalRecord = MedicalRecord::where('id_number', Auth::user()->id_number)
                                              ->where('is_current', true)
                                              ->first();
                                              
            if ($lastMedicalRecord && !$lastMedicalRecord->is_approved) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot create a new medical record until the previous one is approved.'
                ], 403);
            }
    
            // Handle health documents upload
            $healthDocumentsPaths = [];
            if ($request->hasFile('health_documents')) {
                foreach ($request->file('health_documents') as $file) {
                    $path = $file->store('health_documents', 'public');
                    $healthDocumentsPaths[] = $path;
                }
            }
    
            // Handle profile picture upload and update in Information table
            $profilePicture = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
                $information = Information::where('id_number', Auth::user()->id_number)->first();
                if ($information) {
                    $information->update(['profile_picture' => $profilePicture]);
                }
            }
    
            // Mark the previous medical records as not current
            MedicalRecord::where('id_number', Auth::user()->id_number)
                ->update(['is_current' => false]);
    
            // Create the new medical record
            $medicalRecord = MedicalRecord::create([
                'id_number' => Auth::user()->id_number,
                'name' => $request->name,
                'birthdate' => $request->birthdate,
                'age' => $request->age,
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
                'medical_condition' => $request->medical_condition,
                'medicines' => json_encode($request->medicines),
                'health_documents' => !empty($healthDocumentsPaths) ? json_encode($healthDocumentsPaths) : null,
                'profile_picture' => $profilePicture,
                'record_date' => $request->record_date,
                'is_approved' => $request->is_approved ?? false,
                'is_current' => $request->is_current ?? true,
            ]);
    
            Log::info('Medical Record Created: ', ['medical_record' => $medicalRecord]);
    
            return response()->json([
                'success' => true,
                'message' => 'Medical record created successfully.',
                'medical_record' => $medicalRecord,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in storing medical record: ', ['error' => $e->getMessage()]);
    
            return response()->json([
                'success' => false,
                'message' => 'Error in creating medical record.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
    public function search(Request $request)
    {
        $query = trim($request->input('query'));
    
        // Log the search query for debugging
        Log::info('Search Query:', ['query' => $query]);
    
        // Fetch medical records matching the query
        $medicalRecords = MedicalRecord::where('id_number', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('personal_contact_number', 'LIKE', "%{$query}%")
            ->get();
    
        // Log found medical records
        Log::info('Medical Records Found:', ['records' => $medicalRecords]);
    
        $medicalRecord = $medicalRecords->first();
    
        if ($medicalRecord) {
            $idNumber = $medicalRecord->id_number;
    
            // Fetch related data
            $information = Information::where('id_number', $idNumber)->first();
            $physicalExaminations = PhysicalExamination::where('id_number', $idNumber)->get();
            $healthExaminations = HealthExamination::where('id_number', $idNumber)->get(); // Fetch all records
            $medicineIntakes = MedicineIntake::where('id_number', $idNumber)->get();
    
            return response()->json([
                'success' => true,
                'medicalRecord' => $medicalRecord,
                'medicalRecords' => $medicalRecords,
                'information' => $information,
                'physicalExaminations' => $physicalExaminations,
                'healthExaminations' => $healthExaminations, // Updated key
                'medicineIntakes' => $medicineIntakes,
                'debug_query' => $idNumber,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No records found.',
                'debug_query' => $query,
            ]);
        }
    }
    
    
    public function history($id_number)
{
    // Fetch all related records based on the provided id_number
    $medicalRecords = MedicalRecord::where('id_number', $id_number)->get();
    $physicalExaminations = PhysicalExamination::where('id_number', $id_number)->get();
    $healthExaminations = HealthExamination::where('id_number', $id_number)->get(); // Fetch all records
    $medicineIntakes = MedicineIntake::where('id_number', $id_number)->get();
    $information = Information::where('id_number', $id_number)->first(); // Fetch information

    if ($medicalRecords->isEmpty() && $physicalExaminations->isEmpty() && $healthExaminations->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No records found.'
        ]);
    }

    return response()->json([
        'success' => true,
        'medicalRecords' => $medicalRecords,
        'physicalExaminations' => $physicalExaminations,
        'healthExaminations' => $healthExaminations, // Updated key
        'medicineIntakes' => $medicineIntakes,
        'information' => $information, // Include information if needed
    ]);
}

    
    public function downloadPdf($id)
    {
        $medicalRecord = MedicalRecord::findOrFail($id);

        if (empty($medicalRecord->id_number)) {
            \Log::error('The id_number field in the MedicalRecord is empty.');
            return response()->json(['error' => 'No id_number found in the medical record'], 404);
        }

        $information = Information::where('id_number', $medicalRecord->id_number)->first();

        if (!$information) {
            \Log::error('No Information record found for ID number: ' . $medicalRecord->id_number);
            return response()->json(['error' => 'No information record found'], 404);
        }

        $profilePictureBase64 = null;
        if ($information->profile_picture) {
            $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
            if (file_exists($profilePicturePath)) {
                $profilePictureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($profilePicturePath));
            } else {
                \Log::error('Profile picture not found at path: ' . $profilePicturePath);
            }
        }

        $logoPath = public_path('images/pilarLogo.jpg');
        $logoBase64 = 'data:image/jpg;base64,' . base64_encode(file_get_contents($logoPath));

        $pdf = PDF::loadView('pdf.medical-record', [
            'medicalRecord' => $medicalRecord,
            'information' => $information,
            'physicalExamination' => PhysicalExamination::where('id_number', $medicalRecord->id_number)->first(),
            'profilePictureBase64' => $profilePictureBase64,
            'logoBase64' => $logoBase64
        ]);

        return $pdf->download('medical_record_' . $medicalRecord->name . '.pdf');
    }
    public function storePhysicalExamination(Request $request)
{
    // Validate incoming data
    $validatedData = $request->validate([
        'height' => 'required|numeric|min:1', // Height in cm
        'weight' => 'required|numeric|min:1', // Weight in kg
        'vision' => 'required|string',
        'remarks' => 'nullable|string',
        'md_approved' => 'required|boolean',
    ]);

    // Calculate BMI: BMI = weight (kg) / height (m)^2
    $heightInMeters = $request->height / 100; // Convert height to meters
    $bmi = $request->weight / ($heightInMeters * $heightInMeters); // BMI formula

    // Store physical examination data
    $physicalExamination = PhysicalExamination::create([
        'id_number' => Auth::user()->id_number,
        'height' => $request->height,
        'weight' => $request->weight,
        'vision' => $request->vision,
        'remarks' => $request->remarks,
        'md_approved' => $request->md_approved,
        'bmi' => $bmi, // Store the calculated BMI
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Physical examination saved successfully.',
        'physicalExamination' => $physicalExamination, // Return the physical examination data
    ]);
}
public function getBMIData($id_number)
{
    // Fetch physical examinations for the user
    $physicalExaminations = PhysicalExamination::where('id_number', $id_number)->get();

    // Prepare data for the BMI chart
    $bmiData = [
        'dates' => [],
        'bmis' => []
    ];

    foreach ($physicalExaminations as $examination) {
        $date = $examination->created_at->format('Y-m-d');
        $heightInMeters = $examination->height / 100;
        $bmi = $examination->weight / ($heightInMeters * $heightInMeters);

        if ($bmi) {
            $bmiData['dates'][] = $date;
            $bmiData['bmis'][] = round($bmi, 2);
        }
    }

    return response()->json(['bmiData' => $bmiData]);
}


private function calculateBMI($height, $weight)
{
    if ($height == 0) {
        return 0; // Avoid division by zero
    }

    // Convert height from cm to meters and calculate BMI
    $heightInMeters = $height / 100;
    $bmi = $weight / ($heightInMeters * $heightInMeters);
    return round($bmi, 2);  // Return rounded BMI value
}
// In your MedicalRecordController or relevant controller

public function approve($id)
{
    try {
        DB::beginTransaction();

        $medicalRecord = MedicalRecord::findOrFail($id);

        if ($medicalRecord->is_approved) {
            return response()->json(['error' => 'Medical Record is already approved.'], 400);
        }

        $medicalRecord->is_approved = true;
        $medicalRecord->save();

        // Fetch the user by id_number
        $user = User::where('id_number', $medicalRecord->id_number)->first();

        if ($user) {
            // Create a notification using 'id_number'
            Notification::create([
                'user_id' => $user->id_number, // Use 'id_number' here
                'title' => 'Medical Record Approved',
                'message' => 'Your medical record has been approved.',
                'scheduled_time' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => 'Medical Record approved successfully.']);
        } else {
            Log::error('User not found for id_number: ' . $medicalRecord->id_number);
            return response()->json(['error' => 'User not found.'], 404);
        }
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error approving medical record: ' . $e->getMessage());
        return response()->json(['error' => 'Error approving the medical record.'], 500);
    }
}

public function reject($id)
{
    try {
        DB::beginTransaction();

        $medicalRecord = MedicalRecord::findOrFail($id);

        // Only allow rejection if not already approved
        if ($medicalRecord->is_approved) {
            return response()->json(['error' => 'Cannot reject an approved medical record.'], 400);
        }

        $medicalRecord->delete();

        // Fetch the user by id_number
        $user = User::where('id_number', $medicalRecord->id_number)->first();

        if ($user) {
            // Create a notification using 'id' instead of 'id_number'
            Notification::create([
                'user_id' => $user->id, // Use 'id' here
                'title' => 'Medical Record Rejected',
                'message' => 'Your medical record has been rejected and deleted.',
                'scheduled_time' => now(),
            ]);
        } else {
            Log::error('User not found for id_number: ' . $medicalRecord->id_number);
        }

        DB::commit();
        return response()->json(['success' => 'Medical Record rejected and deleted successfully.'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error rejecting medical record: ' . $e->getMessage());
        return response()->json(['error' => 'Error rejecting the medical record.'], 400);
    }
}

public function viewAllRecords()
{
    $user = Auth::user();
    $role = strtolower($user->role); // Ensure the role is in lowercase

    // Define allowed roles and their corresponding views
    $allowedRoles = ['admin', 'nurse', 'doctor'];

    if (!in_array($role, $allowedRoles)) {
        Log::warning("Unauthorized role attempted to access viewAllRecords: {$role}");
        abort(403, 'Unauthorized action.');
    }

    // Fetch pending medical records based on role
    switch ($role) {
        case 'admin':
            // Admin can see all pending medical records
            $pendingMedicalRecords = MedicalRecord::where('is_approved', false)
                ->with(['user', 'nurse', 'doctor'])
                ->paginate(10); // Adjust as needed
            break;

        case 'nurse':
            // Nurse can see pending medical records assigned to them
            $pendingMedicalRecords = MedicalRecord::where('is_approved', false)
                ->where('nurse_id', $user->id)
                ->with(['user', 'nurse', 'doctor'])
                ->paginate(10);
            break;

        case 'doctor':
            // Doctor can see pending medical records assigned to them
            $pendingMedicalRecords = MedicalRecord::where('is_approved', false)
                ->where('doctor_id', $user->id)
                ->with(['user', 'nurse', 'doctor'])
                ->paginate(10);
            break;

        default:
            // This case should be unreachable due to the earlier check
            abort(403, 'Unauthorized action.');
    }

    Log::info("Fetched pending medical records for role: {$role}");

    // Construct the view path dynamically
    $viewPath = "{$role}.uploadMedicalDocu";

    // Check if the view exists
    if (!view()->exists($viewPath)) {
        Log::error("View not found: {$viewPath}");
        abort(404, 'View not found.');
    }

    // Return the dynamically determined view with the data
    return view($viewPath, compact('pendingMedicalRecords'));
}
    public function checkApprovalStatus(Request $request)
    {
        // Retrieve the medical record by id_number
        $record = MedicalRecord::where('id_number', $request->id_number)->first();
        
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }
    
        // Return the approval status
        return response()->json([
            'success' => true,
            'is_approved' => $record->is_approved // Boolean indicating if the record is approved
        ]);
    }
    
}

