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
use App\Models\Notification;

use App\Models\DentalExamination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\ToothApprovedUser;
use App\Mail\ToothApprovedParent;
use App\Mail\ToothRejectedUser;
use App\Mail\ToothRejectedParent;
use Illuminate\Support\Facades\Mail;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


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
    
        // Fetch the next dental examination that hasn't been downloaded
        $nextExamination = DentalExamination::where('id_number', $user->id_number)
            ->where('is_downloaded', 0)
            ->orderBy('date_of_examination', 'asc')
            ->first();
    
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
            'nextExamination' => $nextExamination,
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
            'dental_record_id' => 'required|exists:dental_records,dental_record_id',
            'tooth_number' => 'required|integer|min:11|max:48',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'svg_path' => 'required|string',
            'update_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
        ];
    
        // Validate the request
        $validatedData = $request->validate($rules);
        Log::info('Validated data: ', $validatedData);
    
        // Find existing tooth record
        $existingTooth = Teeth::where('dental_record_id', $validatedData['dental_record_id'])
            ->where('tooth_number', $validatedData['tooth_number'])
            ->first();
    
        Log::info('Existing tooth: ', $existingTooth ? $existingTooth->toArray() : ['No existing tooth']);
    
        // Handle file uploads if available
        $dentalPicturesPaths = [];
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
    
        if ($existingTooth) {
            Log::info('Updating existing tooth record.');
    
            if (!$existingTooth->is_approved) {
                Log::warning('Tooth record is pending approval. Cannot update.');
                return response()->json([
                    'error' => 'This tooth is pending approval. Please wait for approval before updating again.',
                ], 422);
            }
    
            // Merge existing pictures with new ones
            $existingPictures = $existingTooth->dental_pictures ?? [];
            $updatedPictures = array_merge($existingPictures, $dentalPicturesPaths);
    
            // Update the existing tooth record
            $updateData = [
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'] ?? $existingTooth->notes,
                'svg_path' => $validatedData['svg_path'],
                'dental_pictures' => $updatedPictures, // Pass array directly
                'is_approved' => false, // Mark as pending approval
                'is_new' => false, // Ensure 'is_new' is false after update
            ];
    
            Log::info('Updating tooth with data: ', $updateData);
    
            $existingTooth->update($updateData);
    
            // Reload the model to ensure changes are reflected
            $existingTooth->refresh();
            Log::info('Tooth record updated successfully.', ['tooth' => $existingTooth->toArray()]);
    
            return response()->json([
                'success' => true,
                'message' => 'Tooth details updated successfully! Awaiting approval.',
                'exists_in_database' => true, // This is an update
                'update' => true
            ]);
        } else {
            Log::info('Creating new tooth record.');
    
            // Create a new tooth record
            Teeth::create([
                'dental_record_id' => $validatedData['dental_record_id'],
                'tooth_number' => $validatedData['tooth_number'],
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'] ?? null,
                'svg_path' => $validatedData['svg_path'],
                'dental_pictures' => $dentalPicturesPaths, // Pass array directly
                'is_current' => true,
                'is_approved' => true, // First-time save is automatically approved
                'is_new' => false, // Mark as false second submission
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
        'dental_record_id' => 'required|exists:dental_records,dental_record_id',
        'tooth_number' => 'required|integer',
    ]);

    // Log the incoming parameters for debugging
    Log::info('getToothStatus called with', [
        'dental_record_id' => $request->dental_record_id,
        'tooth_number' => $request->tooth_number,
    ]);

    // Attempt to find the tooth in the database
    $tooth = Teeth::where('dental_record_id', $request->dental_record_id)
                 ->where('tooth_number', $request->tooth_number)
                 ->first();

    if (!$tooth) {
        Log::info('No tooth found in database for given dental_record_id and tooth_number.');

        // No record found, this is a new tooth entry
        return response()->json([
            'exists_in_database' => false,
            'status' => 'Healthy',
            'is_new' => true,
            'notes' => '',
        ]);
    } else {
        Log::info('Tooth found in database.', ['tooth' => $tooth]);

        // Record found, return the tooth's status and approval state
        return response()->json([
            'exists_in_database' => true,
            'status' => $tooth->status,
            'is_approved' => $tooth->is_approved,
            'is_new' => $tooth->is_new, // Reflect the actual value from the database
            'notes' => $tooth->notes,
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
    $previousExaminations = DentalExamination::where('id_number', $user->id_number)
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
    $dentalRecord->previousExaminations = $previousExaminations;

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
    try {
        // Retrieve the dental record along with associated teeth
        $dentalRecord = DentalRecord::with('teeth')->where('id_number', $id_number)->firstOrFail();

        // Access teeth via relationship
        $teeth = $dentalRecord->teeth;

        // Process the teeth to include Base64-encoded images
        $teethData = []; // Initialize an array to hold processed teeth data

        foreach ($teeth as $tooth) {
            $toothData = $tooth->toArray(); // Convert the tooth model to an array

            // Decode dental_pictures if it's stored as JSON in the database
            if (is_string($toothData['dental_pictures'])) {
                $dental_pictures = json_decode($toothData['dental_pictures'], true);
            } else {
                $dental_pictures = $toothData['dental_pictures'];
            }

            $base64_pictures = [];
            if (!empty($dental_pictures) && is_array($dental_pictures)) {
                foreach ($dental_pictures as $picture) {
                    // Get the storage path to the image
                    $image_path = storage_path('app/public/' . $picture);
                    if (file_exists($image_path)) {
                        // Read the image file
                        $image_data = file_get_contents($image_path);
                        // Get the image mime type
                        $image_mime = mime_content_type($image_path);
                        // Encode the image data in Base64
                        $base64_image = 'data:' . $image_mime . ';base64,' . base64_encode($image_data);
                        $base64_pictures[] = $base64_image;
                    } else {
                        $base64_pictures[] = null; // Handle missing files as needed
                    }
                }
            }

            $toothData['dental_pictures'] = $dental_pictures; // Ensure dental_pictures is an array
            $toothData['base64_pictures'] = $base64_pictures; // Add the Base64 images

            $teethData[] = $toothData; // Add the processed tooth data to the array
        }

        // Retrieve additional patient information
        $information = Information::where('id_number', $id_number)->firstOrFail();

        // Prepare profile picture
        $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
        $profilePictureBase64 = file_exists($profilePicturePath)
            ? 'data:image/' . pathinfo($profilePicturePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($profilePicturePath))
            : null;

        // Prepare logo
        $logoPath = public_path('images/pilarLogo.jpg');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        // Define all possible tooth numbers based on your dental numbering system
        $allToothNumbers = [
            11,12,13,14,15,16,17,18,
            21,22,23,24,25,26,27,28,
            31,32,33,34,35,36,37,38,
            41,42,43,44,45,46,47,48
        ];

        // Initialize teethStatus with all teeth set to 'healthy' by default
        $teethStatus = [];
        foreach ($allToothNumbers as $toothNumber) {
            $toothClass = 'tooth-' . $toothNumber;
            $tooth = $teeth->firstWhere('tooth_number', $toothNumber);
            if ($tooth) {
                // Map tooth status to colors
                switch (strtolower($tooth->status)) {
                    case 'healthy':
                        $color = 'green';
                        break;
                    case 'missing':
                        $color = 'gray';
                        break;
                    case 'aching':
                        $color = 'red';
                        break;
                    default:
                        $color = 'green'; // Default color if status is undefined
                }
            } else {
                $color = 'green'; // Default color for healthy teeth if not in DB
            }
            $teethStatus[$toothClass] = $color;
        }

        // Prepare data for the view, including teeth pictures
        $data = [
            'dentalRecord' => $dentalRecord,
            'teeth' => $teethData, // Use the processed teeth data
            'teethStatus' => $teethStatus, // Pass the color mapping
            'information' => $information,
            'profilePictureBase64' => $profilePictureBase64,
            'logoBase64' => $logoBase64,
        ];

        // Generate PDF from the Blade view
        $pdf = PDF::loadView('pdf.dental-record', $data);

        // Download the generated PDF
        return $pdf->download('dental_record_' . $id_number . '.pdf');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Handle not found exceptions
        Log::error("Dental record or information not found for ID {$id_number}: " . $e->getMessage());

        return redirect()->back()->with('error', 'Dental record not found for the provided ID number.');
    } catch (\Exception $e) {
        // Handle other exceptions
        Log::error("Error generating PDF for ID {$id_number}: " . $e->getMessage());

        return redirect()->back()->with('error', 'Unable to generate PDF. Please try again later.');
    }
}


public function approveTooth($id)
{
    // Find the Teeth record
    $tooth = Teeth::findOrFail($id);
    $tooth->is_approved = true;
    $tooth->save();

    // Fetch the associated DentalRecord
    $dentalRecord = $tooth->dentalRecord;

    if (!$dentalRecord) {
        Log::error("DentalRecord not found for Teeth ID: {$id}");
        return response()->json([
            'success' => false,
            'message' => 'Associated dental record not found.'
        ], 404);
    }

    // Fetch the user associated with the DentalRecord (student)
    $user = $dentalRecord->user;

    if (!$user) {
        Log::error("User not found for DentalRecord ID: {$dentalRecord->dental_record_id}");
        return response()->json([
            'success' => false,
            'message' => 'Associated user not found.'
        ], 404);
    }
    Log::info("User {$user->id_number} email: {$user->email}");

    // Validate email format and verification
    if (!filter_var($user->email, FILTER_VALIDATE_EMAIL) || !$user->email_verified_at) {
        Log::error("User with ID {$user->id_number} has invalid or unverified email: {$user->email}");
        return response()->json([
            'success' => false,
            'message' => 'The user\'s email is invalid or not verified.'
        ], 400);
    }

    // Prepare email data for user (student)
    $emailDataUser = [
        'userName' => $user->first_name . ' ' . $user->last_name,
        'patientName' => $dentalRecord->patient_name,
        'toothNumber' => $tooth->tooth_number,
        'status' => ucfirst($tooth->status),
        'notes' => $tooth->notes,
    ];

    // Send email to user (student)
    try {
        Mail::to($user->email)->send(new ToothApprovedUser($emailDataUser));
        Log::info("Approval email sent to user: {$user->email}");
    } catch (\Exception $e) {
        Log::error("Failed to send approval email to user: {$user->email}. Error: {$e->getMessage()}");
    }

    // Create notification for user (student)
    try {
        Notification::create([
            'user_id' => $user->id_number,
            'title' => 'Tooth Record Approved',
            'message' => "Hello {$user->first_name}, your tooth record for tooth number {$tooth->tooth_number} has been approved.",
            'is_read' => false,
        ]);
        Log::info("Approval notification created for user: {$user->email}");
    } catch (\Exception $e) {
        Log::error("Failed to create approval notification for user: {$user->email}. Error: {$e->getMessage()}");
    }

    $parents = $user->parents; // Assuming this fetches parent User models correctly

    foreach ($parents as $parent) {
        // Validate each parent's email format and verification
        if (!filter_var($parent->email, FILTER_VALIDATE_EMAIL) || !$parent->email_verified_at) {
            Log::warning("Parent with ID {$parent->id_number} has invalid or unverified email: {$parent->email}");
            continue; // Skip sending email if invalid or unverified
        }

        // Prepare email data for parent
        $emailDataParent = [
            'parentName' => $parent->first_name . ' ' . $parent->last_name,
            'userName' => $user->first_name . ' ' . $user->last_name,
            'patientName' => $dentalRecord->patient_name,
            'toothNumber' => $tooth->tooth_number,
            'status' => ucfirst($tooth->status),
            'notes' => $tooth->notes,
        ];

        // Send email to parent
        try {
            Mail::to($parent->email)->send(new ToothApprovedParent($emailDataParent));
            Log::info("Approval email sent to parent: {$parent->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send approval email to parent: {$parent->email}. Error: {$e->getMessage()}");
        }

        // Create notification for parent
        try {
            Notification::create([
                'user_id' => $parent->id_number,
                'title' => 'Tooth Record Approved',
                'message' => "Hello {$parent->first_name}, the tooth record for {$user->first_name} has been approved.",
                'is_read' => false,
            ]);
            Log::info("Approval notification created for parent: {$parent->email}");
        } catch (\Exception $e) {
            Log::error("Failed to create approval notification for parent: {$parent->email}. Error: {$e->getMessage()}");
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Tooth record approved successfully!'
    ]);
}

public function rejectTooth($id)
{
    // Find the Teeth record
    $tooth = Teeth::findOrFail($id);
    $tooth->is_approved = false;
    $tooth->save(); // Consider updating status instead of deleting

    // Fetch the associated DentalRecord
    $dentalRecord = $tooth->dentalRecord;

    if (!$dentalRecord) {
        Log::error("DentalRecord not found for Teeth ID: {$id}");
        return response()->json([
            'success' => false,
            'message' => 'Associated dental record not found.'
        ], 404);
    }

    // Fetch the user associated with the DentalRecord (student)
    $user = $dentalRecord->user;

    if (!$user) {
        Log::error("User not found for DentalRecord ID: {$dentalRecord->dental_record_id}");
        return response()->json([
            'success' => false,
            'message' => 'Associated user not found.'
        ], 404);
    }

    // Fetch the parents associated with the user (student)
    $parents = $user->parents; // Retrieves a Collection of User models (parents)

    // Prepare email data for user (student)
    $emailDataUser = [
        'userName' => $user->first_name . ' ' . $user->last_name,
        'patientName' => $dentalRecord->patient_name,
        'toothNumber' => $tooth->tooth_number,
        'status' => ucfirst($tooth->status),
        'notes' => $tooth->notes,
    ];

    // Send email to user (student) if email exists
    if ($user->email) {
        try {
            Mail::to($user->email)->send(new ToothRejectedUser($emailDataUser));
            Log::info("Rejection email sent to user: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send rejection email to user: {$user->email}. Error: {$e->getMessage()}");
        }

        // Create notification for user (student)
        try {
            Notification::create([
                'user_id' => $user->id_number,
                'title' => 'Tooth Record Rejected',
                'message' => "Hello {$user->first_name}, your tooth record for tooth number {$tooth->tooth_number} has been rejected.",
                'is_read' => false,
            ]);
            Log::info("Rejection notification created for user: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to create rejection notification for user: {$user->email}. Error: {$e->getMessage()}");
        }
    } else {
        Log::warning("User with ID {$user->id_number} does not have an email address.");
    }

    // Prepare and send emails to all parents
    foreach ($parents as $parent) {
        // Prepare email data for parent
        $emailDataParent = [
            'parentName' => $parent->first_name . ' ' . $parent->last_name,
            'userName' => $user->first_name . ' ' . $user->last_name,
            'patientName' => $dentalRecord->patient_name,
            'toothNumber' => $tooth->tooth_number,
            'status' => ucfirst($tooth->status),
            'notes' => $tooth->notes,
        ];

        // Send email to parent if email exists
        if ($parent->email) {
            try {
                Mail::to($parent->email)->send(new ToothRejectedParent($emailDataParent));
                Log::info("Rejection email sent to parent: {$parent->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send rejection email to parent: {$parent->email}. Error: {$e->getMessage()}");
            }

            // Create notification for parent
            try {
                Notification::create([
                    'user_id' => $parent->id_number,
                    'title' => 'Tooth Record Rejected',
                    'message' => "Hello {$parent->first_name}, the tooth record for {$user->first_name} has been rejected.",
                    'is_read' => false,
                ]);
                Log::info("Rejection notification created for parent: {$parent->email}");
            } catch (\Exception $e) {
                Log::error("Failed to create rejection notification for parent: {$parent->email}. Error: {$e->getMessage()}");
            }
        } else {
            Log::warning("Parent with ID {$parent->id_number} does not have an email address.");
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Tooth record rejected successfully!'
    ]);
}
    
public function viewAllDentalRecords()
{
    // Fetch all pending teeth records that are not approved
    $pendingTeethRecords = Teeth::where('is_approved', 0)
        ->with('dentalRecord.user')  // Eager load relationships
        ->get();
    
    Log::info('Fetched all pending teeth records.', ['records' => $pendingTeethRecords->toArray()]);
    
    // Get the user's role
    $role = strtolower(auth()->user()->role); // Ensure role is in lowercase
    
    // Prepare $teethData
    $teethData = $pendingTeethRecords->map(function($tooth) {
        // Ensure dental_pictures is an array
        $dentalPictures = is_array($tooth->dental_pictures) ? $tooth->dental_pictures : json_decode($tooth->dental_pictures, true) ?? [];
        
        // Convert paths to full URLs
        $dentalPictures = collect($dentalPictures)->map(function($path) {
            return asset('storage/' . $path);
        })->toArray();
        
        return [
            'id' => $tooth->id,
            'patient_name' => $tooth->dentalRecord->patient_name ?? 'N/A',
            'user_type' => ucfirst($tooth->dentalRecord->user_type ?? 'N/A'),
            'tooth_number' => $tooth->tooth_number,
            'notes' => $tooth->notes ?? 'N/A',
            'status' => ucfirst($tooth->status),
            'dental_pictures' => $dentalPictures,
        ];
    });
    
    // Return the view with both variables
    return view("{$role}.uploadDentalDocu", compact('pendingTeethRecords', 'teethData'));
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
public function fetchDentalRecords(Request $request)
{
    $role = $request->input('role', 'student'); // Default to 'student'
    $search = $request->input('search', '');

    // Validate role
    $validRoles = ['student', 'teacher', 'staff', 'nurse', 'doctor', 'parent'];
    if (!in_array($role, $validRoles)) {
        return response()->json(['error' => 'Invalid role specified.'], 400);
    }

    // Fetch teeth records based on role and search query
    $teethData = Teeth::where('is_approved', 0)
        ->whereHas('dentalRecord', function ($query) use ($role, $search) {
            $query->where('user_type', $role);
            if ($search) {
                $query->where('patient_name', 'like', '%' . $search . '%');
            }
        })
        ->with('dentalRecord')
        ->get()
        ->map(function($tooth) {
            // Ensure dental_pictures is an array
            $dentalPictures = is_array($tooth->dental_pictures) ? $tooth->dental_pictures : json_decode($tooth->dental_pictures, true) ?? [];
            
            // Convert paths to full URLs
            $dentalPictures = collect($dentalPictures)->map(function($path) {
                return asset('storage/' . $path);
            })->toArray();

            return [
                'id' => $tooth->id,
                'patient_name' => $tooth->dentalRecord->patient_name ?? 'N/A',
                'user_type' => ucfirst($tooth->dentalRecord->user_type ?? 'N/A'),
                'tooth_number' => $tooth->tooth_number,
                'notes' => $tooth->notes ?? 'N/A',
                'status' => ucfirst($tooth->status),
                'dental_pictures' => $dentalPictures,
            ];
        });

    return response()->json($teethData);
}
public function downloadDentalExamPdfByIdNumber($id_number)
{
    // Log the method call with the provided ID number
    Log::info("downloadDentalExamPdfByIdNumber called with ID number: {$id_number}");

    try {
        // Fetch the next dental examination record that hasn't been downloaded
        $dentalExamination = DentalExamination::where('id_number', $id_number)
            ->where('is_downloaded', 0)
            ->orderBy('date_of_examination', 'asc')
            ->firstOrFail();

        // Log the fetched examination
        Log::info("Fetched Dental Examination: ", $dentalExamination->toArray());

        // Fetch the user (patient)
        $user = User::where('id_number', $id_number)->first();

        if (!$user) {
            Log::error("User not found for ID number: {$id_number}");
            return redirect()->back()->with('error', 'User not found.');
        }

        // Teeth data mapping (ensure consistency with your controller)
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

        // Fetch the clinic logo and encode it in base64
        $logoPath = public_path('images/pilarLogo.png'); // Ensure the logo exists at this path
        if (file_exists($logoPath)) {
            $logoBase64 = base64_encode(file_get_contents($logoPath));
        } else {
            Log::warning("Logo file not found at path: {$logoPath}");
            $logoBase64 = ''; // Optionally, use a placeholder or leave it empty
        }

        // Generate the PDF using SnappyPDF
        $pdf = PDF::loadView('pdf.dental_examination_report', [
                'dentalExamination' => $dentalExamination,
                'user' => $user,
                'teethData' => $teethData,
                'logoBase64' => $logoBase64
            ])
            ->setOption('enable-javascript', true)
            ->setOption('no-stop-slow-scripts', true);

        // Update the is_downloaded flag
        $dentalExamination->is_downloaded = 1;
        $dentalExamination->save();

        // Log the update
        Log::info("Set is_downloaded to 1 for Dental Examination ID: {$dentalExamination->id}");

        // Return the PDF as a download
        return $pdf->download('Dental_Examination_' . $id_number . '.pdf');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Log the error for debugging
        Log::error("No downloadable dental examination found for ID {$id_number}: " . $e->getMessage());

        // Redirect back with an error message
        return redirect()->back()->with('error', 'No downloadable dental examination found for the provided ID number.');
    } catch (\Exception $e) {
        // Log the general error for debugging
        Log::error("Error downloading PDF for ID {$id_number}: " . $e->getMessage());

        // Redirect back with an error message
        return redirect()->back()->with('error', 'Unable to generate PDF. Please try again later.');
    }
}
public function generateParentPdf($id_number)
{
    try {
        // Log the call for debugging
        Log::info("generateParentPdf called with ID number: {$id_number}");

        // Retrieve the dental record and associated teeth
        $dentalRecord = DentalRecord::with('teeth')->where('id_number', $id_number)->firstOrFail();

        // Access teeth via relationship
        $teeth = $dentalRecord->teeth;

        // Process the teeth to include Base64-encoded images
        $teethData = []; // Initialize an array to hold processed teeth data

        foreach ($teeth as $tooth) {
            $toothData = $tooth->toArray(); // Convert the tooth model to an array

            // Decode dental_pictures if it's stored as JSON in the database
            if (is_string($toothData['dental_pictures'])) {
                $dental_pictures = json_decode($toothData['dental_pictures'], true);
            } else {
                $dental_pictures = $toothData['dental_pictures'];
            }

            $base64_pictures = [];
            if (!empty($dental_pictures) && is_array($dental_pictures)) {
                foreach ($dental_pictures as $picture) {
                    // Get the storage path to the image
                    $image_path = storage_path('app/public/' . $picture);
                    if (file_exists($image_path)) {
                        // Read the image file
                        $image_data = file_get_contents($image_path);
                        // Get the image mime type
                        $image_mime = mime_content_type($image_path);
                        // Encode the image data in Base64
                        $base64_image = 'data:' . $image_mime . ';base64,' . base64_encode($image_data);
                        $base64_pictures[] = $base64_image;
                    } else {
                        $base64_pictures[] = null; // Handle missing files as needed
                    }
                }
            }

            $toothData['dental_pictures'] = $dental_pictures; // Ensure dental_pictures is an array
            $toothData['base64_pictures'] = $base64_pictures; // Add the Base64 images

            $teethData[] = $toothData; // Add the processed tooth data to the array
        }

        // Retrieve additional patient information
        $information = Information::where('id_number', $id_number)->firstOrFail();

        // Prepare profile picture
        $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
        $profilePictureBase64 = file_exists($profilePicturePath)
            ? 'data:image/' . pathinfo($profilePicturePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($profilePicturePath))
            : null;

        // Prepare logo
        $logoPath = public_path('images/pilarLogo.jpg');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        // Define all possible tooth numbers based on the dental numbering system
        $allToothNumbers = [
            11,12,13,14,15,16,17,18,
            21,22,23,24,25,26,27,28,
            31,32,33,34,35,36,37,38,
            41,42,43,44,45,46,47,48
        ];

        // Initialize teethStatus with all teeth set to 'healthy' by default
        $teethStatus = [];
        foreach ($allToothNumbers as $toothNumber) {
            $toothClass = 'tooth-' . $toothNumber;
            $tooth = $teeth->firstWhere('tooth_number', $toothNumber);
            if ($tooth) {
                // Map tooth status to colors
                switch (strtolower($tooth->status)) {
                    case 'healthy':
                        $color = 'green';
                        break;
                    case 'missing':
                        $color = 'gray';
                        break;
                    case 'aching':
                        $color = 'red';
                        break;
                    default:
                        $color = 'green'; // Default color if status is undefined
                }
            } else {
                $color = 'green'; // Default color for healthy teeth if not in DB
            }
            $teethStatus[$toothClass] = $color;
        }

        // Prepare data for the view
        $data = [
            'dentalRecord' => $dentalRecord,
            'teeth' => $teethData, // Use the processed teeth data
            'teethStatus' => $teethStatus, // Pass the color mapping
            'information' => $information,
            'profilePictureBase64' => $profilePictureBase64,
            'logoBase64' => $logoBase64,
        ];

        // Generate PDF from the Blade view tailored for the parent's side
        $pdf = PDF::loadView('pdf.dental-record', $data);

        // Log the completion of PDF generation
        Log::info("PDF generated for parent download: Dental Record for ID {$id_number}");

        // Return the PDF as a download
        return $pdf->download('dental-record-' . $id_number . '.pdf');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Log the error if the dental record or information is not found
        Log::error("Dental record or information not found for ID {$id_number}: " . $e->getMessage());

        return redirect()->back()->with('error', 'Dental record not found for the provided ID number.');
    } catch (\Exception $e) {
        // Log any other error
        Log::error("Error generating PDF for parent download with ID {$id_number}: " . $e->getMessage());

        return redirect()->back()->with('error', 'Unable to generate PDF. Please try again later.');
    }
}

public function downloadParentDentalExamPdfByIdNumber($id_number)
{
    // Log the method call with the provided ID number
    Log::info("downloadParentDentalExamPdfByIdNumber called with ID number: {$id_number}");

    try {
        // Fetch the dental examination record for parents to download
        $dentalExamination = DentalExamination::where('id_number', $id_number)
            ->orderBy('date_of_examination', 'desc')
            ->firstOrFail();

        // Log the fetched examination
        Log::info("Fetched Dental Examination for Parent: ", $dentalExamination->toArray());

        // Fetch the student information
        $user = User::where('id_number', $id_number)->first();
        if (!$user) {
            Log::error("User not found for ID number: {$id_number}");
            return redirect()->back()->with('error', 'User not found.');
        }

        // Teeth data mapping for the examination report
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


        // Fetch and encode the clinic logo
        $logoPath = public_path('images/pilarLogo.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

        // Generate the PDF using the parent's specific view template
        $pdf = PDF::loadView('pdf.dental_examination_report', [
            'dentalExamination' => $dentalExamination,
            'user' => $user,
            'teethData' => $teethData,
            'logoBase64' => $logoBase64,
        ]);

        // Return the PDF as a download for parents
        return $pdf->download('Parent_Dental_Examination_' . $id_number . '.pdf');
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Log the error if no examination is found
        Log::error("No dental examination found for ID {$id_number}: " . $e->getMessage());
        return redirect()->back()->with('error', 'No dental examination found for the provided ID number.');
    } catch (\Exception $e) {
        // Log any other error
        Log::error("Error downloading PDF for ID {$id_number}: " . $e->getMessage());
        return redirect()->back()->with('error', 'Unable to generate PDF. Please try again later.');
    }
}

}