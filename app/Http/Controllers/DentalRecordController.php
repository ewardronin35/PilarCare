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
        $role = strtolower($user->role);

        // Fetch role-based information
        if ($role === 'student') {
            $personInfo = DB::table('students')
                ->where('id_number', $user->id_number)
                ->first(['first_name', 'last_name', 'grade_or_course', 'id_number']);
        } elseif ($role === 'teacher') {
            $personInfo = DB::table('teachers')
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
        $additionalInfo = $personInfo ? ($personInfo->grade_or_course ?? $personInfo->department ?? $personInfo->position) : 'Unknown';
        
        $viewName = $role . '.dental-record';
        
        $dentalRecord = DentalRecord::where('id_number', $user->id_number)->first();
        $patientInfo = DB::table('information')
            ->where('id_number', $user->id_number)
            ->first(['birthdate']);
        
        $latestExamination = DB::table('dental_examinations')
            ->where('id_number', $user->id_number)
            ->orderBy('date_of_examination', 'desc')
            ->first();

        $teeth = $dentalRecord ? Teeth::where('dental_record_id', $dentalRecord->id)->get() : collect();
        if (!$dentalRecord) {
            Log::error('No dental record found for id_number: ' . $user->id_number);
        }

        if (!view()->exists($viewName)) {
            abort(404, "View for role {$user->role} not found.");
        }

        Log::info('Person Info:', (array) $personInfo);
        Log::info('Additional Info:', [$additionalInfo]);

        return view($viewName, [
            'personInfo' => $personInfo,
            'dentalRecord' => $dentalRecord,
            'patientInfo' => $patientInfo,
            'personName' => $personName,
            'lastExamination' => $latestExamination,
            'additionalInfo' => $additionalInfo,
            'teeth' => $teeth,
            'user' => $user,  
            'role' => $user->role
        ]);
    }

    public function viewAllRecords()
    {
        $records = DentalRecord::with('user')->get();
        return view('admin.dental-records', compact('records'));
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
            'additional_info' => 'nullable|string', // This will handle grade_section/department/position based on role
        ]);
    
        Log::info('Validated Data: ', $validatedData);
    
        // Determine the user type (student, teacher, staff) by checking the id_number
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
    
        // Prepare data for creating or updating the dental record
        $dentalRecordData = [
            'patient_name' => $validatedData['patient_name'],
            'id_number' => $validatedData['id_number'],
            'user_type' => $userType,
            // Use grade_section for all roles, but store different information based on role
            'grade_section' => $validatedData['additional_info'], 
        ];
    
        // Create or update the dental record
        $dentalRecord = DentalRecord::updateOrCreate(
            ['id_number' => $validatedData['id_number'], 'user_type' => $userType],
            $dentalRecordData
        );
    
        Log::info('Dental Record: ', $dentalRecord->toArray());
    
        return response()->json([
            'success' => 'Dental record saved successfully!',
            'dental_record_id' => $dentalRecord->id
        ]);
    }
    
    public function storeTooth(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'dental_record_id' => 'required|exists:dental_records,id',
            'tooth_number' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'svg_path' => 'required|string',
            'update_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);
    
        // Check if the files are being uploaded correctly
        if ($request->hasFile('update_images')) {
            foreach ($request->file('update_images') as $image) {
                \Log::info('Image detected: ' . $image->getClientOriginalName());
    
                // Attempt to store the image
                try {
                    $path = $image->store('dental_pictures', 'public');
                    \Log::info('Image stored at: ' . $path); // Log the path
                    $dentalPicturesPaths[] = $path; // Save the paths to array
                } catch (\Exception $e) {
                    \Log::error('File storage error: ' . $e->getMessage()); // Log any errors
                }
            }
        } else {
            \Log::info('No files uploaded');
        }
    
        // Convert the array of paths to JSON
        $dentalPicturesJson = !empty($dentalPicturesPaths) ? json_encode($dentalPicturesPaths) : null;
    
        // Mark existing record for this tooth as not current
        Teeth::where('dental_record_id', $validatedData['dental_record_id'])
            ->where('tooth_number', $validatedData['tooth_number'])
            ->update(['is_current' => false]);
    
        // Create the new current record
        Teeth::create([
            'dental_record_id' => $validatedData['dental_record_id'],
            'tooth_number' => $validatedData['tooth_number'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'] ?? null,
            'svg_path' => $validatedData['svg_path'],
            'dental_pictures' => $dentalPicturesJson, // Store dental picture paths as JSON
            'is_current' => true,
        ]);
    
        return response()->json(['success' => 'Tooth details saved successfully!']);
    }
    

    public function getToothStatus(Request $request)
    {
        $request->validate([
            'dental_record_id' => 'required|integer',
            'tooth_number' => 'required|integer',
        ]);

        $teeth = Teeth::where('dental_record_id', $request->dental_record_id)
                      ->where('tooth_number', $request->tooth_number)
                      ->first();

        return $teeth ? response()->json(['status' => $teeth->status]) : response()->json(['status' => 'Healthy']);
    }

    public function searchRecords(Request $request)
    {
        $searchQuery = $request->input('search_term');

        if (empty($searchQuery)) {
            return response()->json(['message' => 'Search term cannot be empty.'], 400);
        }

        $dentalRecord = DentalRecord::where('id_number', $searchQuery)->first();

        if (!$dentalRecord) {
            return response()->json(['message' => 'No dental record found for the provided ID number.'], 404);
        }

        $teeth = Teeth::where('dental_record_id', $dentalRecord->id)->get();

        return response()->json([
            'dentalRecord' => $dentalRecord,
            'teeth' => $teeth,
        ]);
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
}
