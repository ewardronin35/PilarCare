<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\Information;
use App\Models\Teeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF; // Import PDF facade for domPDF

class DentalRecordController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch the student/teacher/staff information based on the user's role
        $studentInfo = DB::table('students')
            ->where('id_number', $user->id_number)
            ->first(['first_name', 'last_name', 'grade_or_course', 'id_number']);
        
        // Combine first_name and last_name for the full name
        $studentName = $studentInfo ? $studentInfo->first_name . ' ' . $studentInfo->last_name : 'Unknown';
        $gradeSection = $studentInfo->grade_or_course ?? 'Unknown';
        
        $role = strtolower($user->role); // This assumes user role is defined as 'student', 'teacher', or 'staff'
        $viewName = $role . '.dental-record';
        
        // Fetch the dental record using the user's id_number instead of user_id
        $dentalRecord = DentalRecord::where('id_number', $user->id_number)->first();
        
        if ($dentalRecord) {
            $teeth = Teeth::where('dental_record_id', $dentalRecord->id)->get();
        } else {
            $teeth = collect();  // Return an empty collection if no dental record is found
            Log::error('No dental record found for id_number: ' . $user->id_number);
        }
        
        // Ensure the view exists for the given role
        if (!view()->exists($viewName)) {
            abort(404, "View for role {$user->role} not found.");
        }
        
        // Pass the specific dental record and student info to the view
        return view($viewName, compact('dentalRecord', 'studentName', 'gradeSection', 'studentInfo', 'teeth'));
    }
    
    
    
    
    
    

    public function viewAllRecords()
    {
        // Fetching all dental records with their associated users
        $records = DentalRecord::with('user')->get();
        return view('admin.dental-records', compact('records'));
    }

    public function create()
    {
        // Display the form for creating a new dental record
        return view('student.dental-record.create');
    }
    public function store(Request $request)
    {
        // Log the incoming request to see if all fields are there
        Log::info('Incoming Request: ', $request->all());
    
        $validatedData = $request->validate([
            'patient_name' => 'required|string',
            'grade_section' => 'nullable|string',
            'id_number' => 'required|string',
        ]);
    
        // Log the validated data to check if validation passed correctly
        Log::info('Validated Data: ', $validatedData);
    
        // Check the user type by looking for the id_number in the different tables
        $userType = null;
    
        if (Student::where('id_number', $validatedData['id_number'])->exists()) {
            $userType = 'student';
        } elseif (Teacher::where('id_number', $validatedData['id_number'])->exists()) {
            $userType = 'teacher';
        } elseif (Staff::where('id_number', $validatedData['id_number'])->exists()) {
            $userType = 'staff';
        }
    
        if (!$userType) {
            return response()->json(['error' => 'No user found with the provided ID number.'], 404);
        }
    
        // Create or update the dental record using the detected user_type and id_number
        $dentalRecord = DentalRecord::updateOrCreate(
            ['id_number' => $validatedData['id_number'], 'user_type' => $userType],
            [
                'patient_name' => $validatedData['patient_name'],
                'grade_section' => $userType === 'student' ? $validatedData['grade_section'] : null,
                'id_number' => $validatedData['id_number'],
                'user_type' => $userType,
            ]
        );
    
        // Log the dental record to check if it was created/updated successfully
        Log::info('Dental Record: ', $dentalRecord->toArray());
    
        return response()->json([
            'success' => 'Dental record saved successfully!',
            'dental_record_id' => $dentalRecord->id  // Return the ID here
        ]);
    }
    


    public function storeTooth(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'dental_record_id' => 'required|exists:dental_records,id',
            'tooth_number' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'svg_path' => 'required|string',  // Ensure the svg_path is included
        ]);
    
        // Store or update the tooth data in the teeth table
        Teeth::updateOrCreate(
            [
                'dental_record_id' => $validatedData['dental_record_id'],
                'tooth_number' => $validatedData['tooth_number'],
            ],
            [
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'] ?? null,
                'svg_path' => $validatedData['svg_path'], // Store the svg_path
            ]
        );
    
        return response()->json(['success' => 'Tooth details saved successfully!']);
    }
    
    public function getToothStatus(Request $request)
{
    // Validate the request
    $request->validate([
        'dental_record_id' => 'required|integer',
        'tooth_number' => 'required|integer',
    ]);

    // Fetch the tooth status from the database
    $teeth = Teeth::where('dental_record_id', $request->dental_record_id)
                   ->where('tooth_number', $request->tooth_number)
                   ->first();

    if ($teeth) {
        return response()->json(['status' => $teeth->status]);
    } else {
        return response()->json(['status' => 'Healthy']); // Default status if not found
    }
}
public function searchRecords(Request $request)
{
    $searchQuery = $request->input('search_term');

    // Ensure searchQuery is not empty
    if (empty($searchQuery)) {
        return response()->json(['message' => 'Search term cannot be empty.'], 400);
    }

    // Search only for an exact match by id_number
    $dentalRecord = DentalRecord::where('id_number', $searchQuery)->first();

    // If no exact match by id_number, return an error message
    if (!$dentalRecord) {
        return response()->json(['message' => 'No dental record found for the provided ID number.'], 404);
    }

    // Fetch the associated teeth data
    $teeth = Teeth::where('dental_record_id', $dentalRecord->id)->get();

    // Return the dental record and teeth information
    return response()->json([
        'dentalRecord' => $dentalRecord,
        'teeth' => $teeth,
    ]);
}

public function generatePdf($id_number)
{
    // Fetch the dental record by ID number
    $dentalRecord = DentalRecord::where('id_number', $id_number)->firstOrFail();
    
    // Fetch associated teeth data
    $teeth = Teeth::where('dental_record_id', $dentalRecord->id)->get();

    // Fetch the profile picture from the Information model
    $information = Information::where('id_number', $id_number)->firstOrFail();

    // Check if the profile picture exists and encode it as base64
    $profilePicturePath = storage_path('app/public/profile_pictures/' . $information->profile_picture);
    if (file_exists($profilePicturePath)) {
        $profilePictureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($profilePicturePath));
    } else {
        $profilePictureBase64 = null;  // Set to null if no profile picture exists
    }

    // Load and encode the logo image as base64
    $logoPath = public_path('images/pilarLogo.jpg');
    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

    // Set up view data to pass to the Blade template
    $data = [
        'dentalRecord' => $dentalRecord,
        'teeth' => $teeth,
        'information' => $information,
        'profilePictureBase64' => $profilePictureBase64,
        'logoBase64' => $logoBase64,
    ];

    // Load the PDF view and generate the PDF
    $pdf = PDF::loadView('pdf.dental-record', $data);

    // Stream or download the generated PDF
    return $pdf->download('dental_record_' . $id_number . '.pdf');
}

}

