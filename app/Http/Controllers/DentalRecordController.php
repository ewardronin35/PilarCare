<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\Appointment;
use App\Models\Information;
use App\Models\Teeth;
use App\Models\DentalExamination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF; // Import PDF facade for domPDF

class DentalRecordController extends Controller
{
    private $allToothNumbers = [
        11, 12, 13, 14, 15, 16, 17, 18,
        21, 22, 23, 24, 25, 26, 27, 28,
        31, 32, 33, 34, 35, 36, 37, 38,
        41, 42, 43, 44, 45, 46, 47, 48
    ];
    public function index()
    {
        $user = auth()->user();
        $role = strtolower($user->role);
    
        // Fetch role-based information
        if ($role === 'student') {
            $personInfo = DB::table('students')
                ->where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'grade_or_course', 'id_number']);
        } elseif ($role === 'teacher') {
            $personInfo = DB::table('teacher')
                ->where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'bed_or_hed', 'id_number']);
        } elseif ($role === 'staff') {
            $personInfo = DB::table('staff')
                ->where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'position', 'id_number']);
        } else {
            $personInfo = null;
            Log::error('No matching role for id_number: ' . $user->id_number);
        }
    
        $personName = $personInfo ? $personInfo->first_name . ' ' . $personInfo->last_name : 'Unknown';
        $additionalInfo = $personInfo ? ($personInfo->grade_or_course ?? $personInfo->bed_or_hed ?? $personInfo->position) : 'Unknown';
        
        $viewName = $role . '.dental-record';
        
        $dentalRecord = DentalRecord::where('id_number', $user->id_number)->first();
        $patientInfo = DB::table('information')
            ->where('id_number', $user->id_number)
            ->first(['birthdate']);
        
        $latestExamination = DB::table('dental_examinations')
            ->where('id_number', $user->id_number)
            ->orderBy('date_of_examination', 'desc')
            ->first();
    
        $teeth = $dentalRecord ? Teeth::where('dental_record_id', $dentalRecord->dental_record_id)->get() : collect();
        if (!$dentalRecord) {
            Log::error('No dental record found for id_number: ' . $user->id_number);
        }
    
        // Fetch the next appointment
        $nextAppointment = DB::table('appointments')
            ->where('id_number', $user->id_number)
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date', 'asc')
            ->first();
    
        // Define teethData array
        $teethData = [
            11 => 'Upper Right Central Incisor',
            12 => 'Upper Right Lateral Incisor',
            13 => 'Upper Right Canine',
            14 => 'Upper Right First Premolar',
            15 => 'Upper Right Second Premolar',
            16 => 'Upper Right First Molar',
            17 => 'Upper Right Second Molar',
            18 => 'Upper Right Third Molar',
            21 => 'Upper Left Central Incisor',
            22 => 'Upper Left Lateral Incisor',
            23 => 'Upper Left Canine',
            24 => 'Upper Left First Premolar',
            25 => 'Upper Left Second Premolar',
            26 => 'Upper Left First Molar',
            27 => 'Upper Left Second Molar',
            28 => 'Upper Left Third Molar',
            31 => 'Lower Left Central Incisor',
            32 => 'Lower Left Lateral Incisor',
            33 => 'Lower Left Canine',
            34 => 'Lower Left First Premolar',
            35 => 'Lower Left Second Premolar',
            36 => 'Lower Left First Molar',
            37 => 'Lower Left Second Molar',
            38 => 'Lower Left Third Molar',
            41 => 'Lower Right Central Incisor',
            42 => 'Lower Right Lateral Incisor',
            43 => 'Lower Right Canine',
            44 => 'Lower Right First Premolar',
            45 => 'Lower Right Second Premolar',
            46 => 'Lower Right First Molar',
            47 => 'Lower Right Second Molar',
            48 => 'Lower Right Third Molar'
        ];
    
        // Check if view exists
        if (!view()->exists($viewName)) {
            abort(404, "View for role '{$role}' not found");
        }
    
        // Pass teethData to the view
        return view($viewName, [
            'personInfo' => $personInfo,
            'dentalRecord' => $dentalRecord,
            'patientInfo' => $patientInfo,
            'personName' => $personName,
            'lastExamination' => $latestExamination,
            'additionalInfo' => $additionalInfo,
            'teeth' => $teeth,
            'user' => $user,  
            'role' => $user->role,
            'nextAppointment' => $nextAppointment,
            'teethData' => $teethData, // Added
        ]);
    }
    
    public function viewAllRecords()
    {
        $role = strtolower(auth()->user()->role); // Ensure role is in lowercase

        $records = DentalRecord::with('user')->get();
        return view("{$role}.dental-record", compact('records'));
    }

    public function create()
    {
        return view('student.dental-record.create');
    }

    public function store(Request $request)
    {
        Log::info('Incoming Request: ', $request->all());
    
        // Validate the data based on role
        $validatedData = $request->validate([
            'patient_name' => 'required|string',
            'id_number' => 'required|string',
            'additional_info' => 'nullable|string', // grade_section/department/position based on role
        ]);
    
        Log::info('Validated Data: ', $validatedData);
    
        // Determine the user type (student, teacher, staff)
        $userType = null;
        if (Student::where('id_number', $validatedData['id_number'])->exists()) {
            $userType = 'student';
        } elseif (Teacher::where('id_number', $validatedData['id_number'])->exists()) {
            $userType = 'teacher';
        } elseif (Staff::where('id_number', $validatedData['id_number'])->exists()) {
            $userType = 'staff';
        }
    
        // If user type not found, return error
        if (!$userType) {
            return response()->json(['error' => 'No user found with the provided ID number.'], 404);
        }
    
        // Generate a unique dental_record_id if not provided
        // You can customize the generation logic as needed
        $dentalRecordId = $request->input('dental_record_id') ?? 'DR' . strtoupper(uniqid());
    
        // Prepare data for creating or updating the dental record
        $dentalRecordData = [
            'dental_record_id' => $dentalRecordId, // Ensure this is unique
            'patient_name' => $validatedData['patient_name'],
            'id_number' => $validatedData['id_number'],
            'user_type' => $userType,
            'grade_section' => $validatedData['additional_info'],
        ];
    
        // Create or update the dental record
        try {
            $dentalRecord = DentalRecord::updateOrCreate(
                ['id_number' => $validatedData['id_number'], 'user_type' => $userType],
                $dentalRecordData
            );
        } catch (\Exception $e) {
            Log::error('Error creating/updating dental record: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save dental record.'], 500);
        }
    
        Log::info('Dental Record: ', $dentalRecord->toArray());
    
        return response()->json([
            'success' => 'Dental record saved successfully!',
            'dental_record_id' => $dentalRecord->dental_record_id // Return the unique string identifier
        ]);
    }
    

    
public function storeTooth(Request $request)
{
    Log::info('Incoming request data: ', $request->all());
    $rules = [
        'dental_record_id' => 'required|exists:dental_records,dental_record_id', // Corrected
        'tooth_number' => 'required|integer|min:11|max:48',
        'status' => 'required|string',
        'notes' => 'nullable|string',
        'svg_path' => 'required|string',
    ];
    // Check if this tooth already exists for the given dental record
    $existingTooth = Teeth::where('dental_record_id', $request->dental_record_id)
        ->where('tooth_number', $request->tooth_number)
        ->first();

    Log::info('Existing tooth: ', $existingTooth ? $existingTooth->toArray() : ['No existing tooth']);

  

    // If the tooth exists (second submission or update), images are required
    if ($existingTooth) {
        Log::info('Update submission detected, requiring images.');
        $rules['update_images.*'] = 'required|image|mimes:jpeg,png,jpg,gif|max:10048';
    } else {
        Log::info('First submission, images optional.');
        // For first submission, images are optional
        $rules['update_images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048';
    }

    // Validate the request
    $validatedData = $request->validate($rules);
    Log::info('Validated data: ', $validatedData);

    // Initialize an array for storing image paths
    $dentalPicturesPaths = [];

    // Handle file uploads if available
    if ($request->hasFile('update_images')) {
        Log::info('Uploading images...');
        foreach ($request->file('update_images') as $image) {
            try {
                $path = $image->store('dental_pictures', 'public');
                $dentalPicturesPaths[] = $path;
                Log::info('Image uploaded to: ' . $path);
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to upload images. Please try again.'], 500);
            }
        }
    }

    // Convert dental pictures paths to JSON if any images are uploaded
    $dentalPicturesJson = !empty($dentalPicturesPaths) ? json_encode($dentalPicturesPaths) : null;

    if ($existingTooth) {
        Log::info('Updating existing tooth record.');

        if (!$existingTooth->is_approved) {
            Log::warning('Tooth record is pending approval. Cannot update.');
            return response()->json([
                'error' => 'This tooth is pending approval. Please wait for approval before updating again.',
            ], 422);
        }

        // Update the existing tooth record
        $existingTooth->update([
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? $existingTooth->notes,
            'svg_path' => $validatedData['svg_path'],
            'dental_pictures' => $dentalPicturesJson ?? $existingTooth->dental_pictures,
            'is_approved' => false, // Mark it as pending approval again
        ]);

        Log::info('Tooth record updated successfully.');

        return response()->json([
            'success' => true,
            'message' => 'Tooth details updated successfully! Awaiting approval.',
            'exists_in_database' => true, // This is an update
            'update' => true
        ]);
    } else {
        Log::info('Creating new tooth record.');

        // First-time save
        Teeth::create([
            'dental_record_id' => $validatedData['dental_record_id'],
            'tooth_number' => $validatedData['tooth_number'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? null,
            'svg_path' => $validatedData['svg_path'],
            'dental_pictures' => $dentalPicturesJson,
            'is_current' => true,
            'is_approved' => true, // First-time save is automatically approved
        ]);

        Log::info('New tooth record created successfully.');

        return response()->json([
            'success' => true,
            'message' => 'Tooth details saved successfully!',
            'exists_in_database' => false, // This is a new record
            'update' => false // Indicate that this is not an update
        ]);
    }
}

public function getToothStatus(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'dental_record_id' => 'required|exists:dental_records,dental_record_id', // Corrected
        'tooth_number' => 'required|string',
    ]);

    // Attempt to find the tooth in the database
    $tooth = Teeth::where('dental_record_id', $request->dental_record_id)
                  ->where('tooth_number', $request->tooth_number)
                  ->first();

    if (!$tooth) {
        // No record found, this is a new tooth entry
        return response()->json([
            'exists_in_database' => false,  // Mark as not existing in the database
            'status' => 'Healthy',          // Default status for a new tooth
            'is_new' => true,               // Indicates this is a new tooth entry
        ]);
    } else {
        // Record found, return the tooth's status and approval state
        return response()->json([
            'exists_in_database' => true,   // Mark as existing in the database
            'status' => $tooth->status,     // Use the status from the database
            'is_approved' => $tooth->is_approved, // Whether the tooth record is approved
            'is_new' => false               // This is not a new entry
        ]);
    }
}
public function searchRecords(Request $request)
{
    $searchQuery = $request->input('search_term');

    if (empty($searchQuery)) {
        return response()->json(['message' => 'Search term cannot be empty.'], 400);
    }

    // Fetch the user by id_number
    $user = User::where('id_number', $searchQuery)->first();

    if (!$user) {
        return response()->json(['message' => 'No user found for the provided ID number.'], 404);
    }

    $role = strtolower($user->role); // Ensure role is in lowercase

    // Initialize variables
    $name = '';
    $grade_section = '';

    // Fetch the additional information based on role
    switch ($role) {
        case 'student':
            $personInfo = Student::where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'grade_or_course']);
            $name = $personInfo ? $personInfo->first_name . ' ' . $personInfo->last_name : '';
            $grade_section = $personInfo ? $personInfo->grade_or_course : '';
            break;
        case 'teacher':
            $personInfo = Teacher::where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'bed_or_hed']);
            $name = $personInfo ? $personInfo->first_name . ' ' . $personInfo->last_name : '';
            $grade_section = $personInfo ? $personInfo->bed_or_hed : '';
            break;
        case 'staff':
            $personInfo = Staff::where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'position']);
            $name = $personInfo ? $personInfo->first_name . ' ' . $personInfo->last_name : '';
            $grade_section = $personInfo ? $personInfo->position : '';
            break;
        default:
            return response()->json(['message' => 'Invalid user role.'], 400);
    }

    if (!$personInfo) {
        return response()->json(['message' => 'No person information found for the provided ID number.'], 404);
    }

    // Fetch patient's birthdate from the Information table
    $information = Information::where('id_number', $user->id_number)->first();

    if (!$information) {
        return response()->json(['message' => 'No information found for the provided ID number.'], 404);
    }

    $birthdate = $information->birthdate;

    // Calculate age
    $age = Carbon::parse($birthdate)->age;

    // Fetch dental record
    $dentalRecord = DentalRecord::with('teeth')->where('id_number', $searchQuery)->first();

    if (!$dentalRecord) {
        return response()->json(['message' => 'No dental record found for the provided ID number.'], 404);
    }

    $teeth = $dentalRecord->teeth;

    // Fetch all previous examinations
    $previousExaminations = DB::table('dental_examinations')
        ->where('id_number', $user->id_number)
        ->orderBy('date_of_examination', 'desc')
        ->get();

    // Fetch tooth history with 'dental_pictures'
    $toothHistory = Teeth::where('dental_record_id', $dentalRecord->dental_record_id)
        ->orderBy('tooth_number')
        ->get(['tooth_number', 'status', 'notes', 'updated_at', 'dental_pictures']); // Include 'dental_pictures'

    // Decode 'dental_pictures' JSON to array if necessary
    $toothHistory->transform(function ($tooth) {
        $tooth->dental_pictures = is_string($tooth->dental_pictures) ? json_decode($tooth->dental_pictures, true) : $tooth->dental_pictures;
        return $tooth;
    });

    // Fetch next scheduled appointment if available for Dr. Sarah Uy-Gan
    $nextAppointment = DB::table('appointments')
        ->where('id_number', $user->id_number) // Use id_number for appointment search
        ->where('appointment_date', '>=', Carbon::now()->toDateString()) // Only future appointments
        ->orderBy('appointment_date', 'asc')
        ->first();

    // Initialize teeth data with default status 'Healthy'
    $completeTeeth = [];

    $allToothNumbers = [
        11, 12, 13, 14, 15, 16, 17, 18,
        21, 22, 23, 24, 25, 26, 27, 28,
        31, 32, 33, 34, 35, 36, 37, 38,
        41, 42, 43, 44, 45, 46, 47, 48
    ];

    foreach ($allToothNumbers as $toothNumber) {
        $tooth = $teeth->firstWhere('tooth_number', $toothNumber);
        if ($tooth) {
            // Ensure status is properly capitalized
            $status = ucfirst(strtolower($tooth->status));
            $completeTeeth[] = [
                'tooth_number' => $toothNumber,
                'status' => $status,
                'notes' => $tooth->notes,
                'updated_at' => $tooth->updated_at,
                'dental_pictures' => $tooth->dental_pictures, // Ensure this field is included
            ];
        } else {
            // If no record exists, assume 'Healthy'
            $completeTeeth[] = [
                'tooth_number' => $toothNumber,
                'status' => 'Healthy',
                'notes' => null,
                'updated_at' => null,
                'dental_pictures' => null, // No pictures
            ];
        }
    }

    // Construct the response data
    $response = [
        'dentalRecord' => $dentalRecord,
        'teeth' => $completeTeeth,
        'name' => $name,
        'birthdate' => $birthdate,
        'age' => $age,
        'grade_section' => $grade_section,
        'previousExaminations' => $previousExaminations, // Send all examinations
        'toothHistory' => $toothHistory,
        'nextAppointment' => $nextAppointment,
        'role' => $role, // Include the role
    ];

    return response()->json($response);
}



    public function generatePdf($id_number)
    {
        $dentalRecord = DentalRecord::where('id_number', $id_number)->firstOrFail();
        $patientName = $dentalRecord->patient_name;

        $teeth = Teeth::where('dental_record_id', $dentalRecord->id)->get();

        $information = Information::where('id_number', $id_number)->firstOrFail();

        $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
        $profilePictureBase64 = file_exists($profilePicturePath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($profilePicturePath)) : null;

        $logoPath = public_path('images/pilarLogo.jpg');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $data = [
            'dentalRecord' => $dentalRecord,
            'teeth' => $teeth,
            'information' => $information,
            'profilePictureBase64' => $profilePictureBase64,
            'logoBase64' => $logoBase64,
        ];

        $pdf = PDF::loadView('pdf.dental-record', $data);

        return $pdf->download('dental_record_' . $id_number . '.pdf');
    }
    public function approveTooth($id)
{
    $tooth = Teeth::findOrFail($id);
    $tooth->is_approved = true;
    $tooth->save();

    return response()->json([
        'success' => true,
        'message' => 'Tooth record approved successfully!'
    ]);
}

public function rejectTooth($id)
{
    $tooth = Teeth::findOrFail($id);
    $tooth->is_approved = false;
    $tooth->delete(); // You can delete or mark the record as rejected based on your requirement

    return response()->json([
        'success' => true,
        'message' => 'Tooth record rejected successfully!'
    ]);
}
public function viewAllDentalRecords()
{
    // Fetch all pending dental records that are not approved
    $pendingDentalRecords = Teeth::where('is_approved', false)
        ->with('dentalRecord')  // Assuming you have set up the relationship with the DentalRecord model
        ->get();
    
    Log::info('Fetched all pending dental records.');
    
    // Get the user's role
    $role = strtolower(auth()->user()->role); // Ensure role is in lowercase
    
    // Return the view with the pending dental records
    return view("{$role}.uploadDentalDocu", compact('pendingDentalRecords'));
}

// In your controller, e.g., DentalController.php
public function showDentalHistory($patientId)
{
    // Fetch patient information
    $patient = User::find($patientId);

    // Fetch previous examinations
    $previousExaminations = DentalExamination::where('patient_id', $patientId)
                            ->orderBy('date', 'desc')
                            ->get();

    // Fetch treatments performed
    $treatmentsPerformed = Treatment::where('patient_id', $patientId)
                            ->orderBy('date', 'desc')
                            ->get();

    // Fetch medications prescribed
    $medicationsPrescribed = Medication::where('patient_id', $patientId)
                            ->orderBy('date', 'desc')
                            ->get();

    // Fetch next scheduled appointment with Dr. Sarah Uy
    $nextAppointment = Appointment::where('patient_id', $patientId)
                        ->where('doctor_name', 'Dr. Sarah Uy-Gan')
                        ->where('date', '>=', now())
                        ->orderBy('date', 'asc')
                        ->first();

    return view('admin.dentalHistory', compact(
        'patient',
        'previousExaminations',
        'treatmentsPerformed',
        'medicationsPrescribed',
        'nextAppointment'
    ));
}
public function getDentalExaminationData(Request $request)
{
    $dentalRecordId = $request->input('dental_record_id');

    // Fetch the latest dental examination for simplicity
    $dentalExamination = DentalExamination::where('dental_record_id', $dentalRecordId)->latest()->first();

    if (!$dentalExamination) {
        return response()->json(null, 404);
    }

    // Return the necessary fields
    return response()->json([
        'carries_free' => $dentalExamination->carries_free,
        'poor_oral_hygiene' => $dentalExamination->poor_oral_hygiene,
        'gum_infection' => $dentalExamination->gum_infection,
        'restorable_caries' => $dentalExamination->restorable_caries,
        'personal_attention' => $dentalExamination->personal_attention,
        'oral_prophylaxis' => $dentalExamination->oral_prophylaxis,
        'fluoride_application' => $dentalExamination->fluoride_application,
        'gum_treatment' => $dentalExamination->gum_treatment,
        'ortho_consultation' => $dentalExamination->ortho_consultation,
        'filling_tooth' => $dentalExamination->filling_tooth,
        'extraction_tooth' => $dentalExamination->extraction_tooth,
        'endodontic_tooth' => $dentalExamination->endodontic_tooth,
        'radiograph_tooth' => $dentalExamination->radiograph_tooth,
        'prosthesis_tooth' => $dentalExamination->prosthesis_tooth,
        'medical_clearance' => $dentalExamination->medical_clearance,
    ]);
}
public function history(Request $request)
{
    $dentalRecordId = $request->input('dental_record_id');
    if (!$dentalRecordId) {
        return response()->json(['error' => 'Dental Record ID is required.'], 400);
    }

    $dentalExaminations = DentalExamination::where('dental_record_id', $dentalRecordId)
        ->orderBy('date_of_examination', 'desc')
        ->get(); // Get all fields

    Log::info('Dental Examinations for Record ID ' . $dentalRecordId . ':', $dentalExaminations->toArray());

    return response()->json($dentalExaminations);
}


// Method to fetch Tooth History
public function toothHistory(Request $request)
{
    $dentalRecordId = $request->input('dental_record_id');
    if (!$dentalRecordId) {
        return response()->json(['error' => 'Dental Record ID is required.'], 400);
    }

    $toothHistory = Teeth::where('dental_record_id', $dentalRecordId)
        ->orderBy('tooth_number')
        ->get(['tooth_number', 'status', 'notes', 'updated_at', 'dental_pictures']); // Include 'dental_pictures'

    Log::info('Tooth History for Record ID ' . $dentalRecordId . ':', $toothHistory->toArray());

    return response()->json($toothHistory);
}

}
