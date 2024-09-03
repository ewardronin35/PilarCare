<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
use App\Models\User;
use App\Models\Teeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class DentalRecordController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // Fetch the student information from the students table
        $studentInfo = DB::table('students')
            ->where('id_number', $user->id_number)
            ->first(['first_name', 'last_name', 'grade_or_course', 'id_number']);
    
        // Combine first_name and last_name for the full name
        $studentName = $studentInfo ? $studentInfo->first_name . ' ' . $studentInfo->last_name : 'Unknown';
        $gradeSection = $studentInfo->grade_or_course ?? 'Unknown';
    
        $role = strtolower($user->role);
        $viewName = $role . '.dental-record';
    
        // Fetch the dental record for the currently authenticated user
        $dentalRecord = DentalRecord::where('user_id', $user->id)->first();
        $teeth = Teeth::where('dental_record_id', $dentalRecord->id)->get();


        if (!$dentalRecord) {
            Log::error('No dental record found for user ID: ' . $user->id);
        } else {
            Log::info('Dental record found: ' . $dentalRecord->id);
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
        $user = auth()->user();
    
        // Validate the incoming request
        $validatedData = $request->validate([
            'patient_name' => 'required|string',
            'grade_section' => 'required|string',
            'id_number' => 'required|string',
        ]);
    
        // Create or update the dental record
        $dentalRecord = DentalRecord::updateOrCreate(
            ['user_id' => $user->id],
            [
                'patient_name' => $validatedData['patient_name'],
                'grade_section' => $validatedData['grade_section'],
                'user_id' => $user->id,
            ]
        );
    
        return response()->json(['success' => 'Dental record saved successfully!']);
    }

    public function storeTooth(Request $request)
    {
        $validatedData = $request->validate([
            'dental_record_id' => 'required|exists:dental_records,id',
            'tooth_number' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Store or update the tooth data in the teeth table
        Teeth::updateOrCreate(
            [
                'dental_record_id' => $validatedData['dental_record_id'],
                'tooth_number' => $validatedData['tooth_number'],
            ],
            [
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'],
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

}

