<?php
namespace App\Http\Controllers;

use App\Models\HealthExamination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Notification; // Import the Notification model
use App\Models\Student;
use App\Models\Teacher;
use App\Events\NewNotification; // Import the NewNotification event
use App\Models\Staff;
use App\Models\Parents;
use App\Models\Information;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Correct namespace
use Illuminate\Support\Facades\Route;
use App\Models\SchoolYear; // <-- Import the SchoolYear model
use App\Mail\HealthExaminationReminder;
use App\Mail\StudentHealthExaminationReminder;


class HealthExaminationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        // Get the current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
    
        $healthExaminations = HealthExamination::where('id_number', $user->id_number)
            ->orderBy('school_year', 'desc')
            ->get();
                
        return view("$role.upload-pictures", compact('healthExaminations', 'currentSchoolYear'));
    }
    protected function getUserInformation($user)
{
    $role = strtolower($user->role);
    switch ($role) {
        case 'student':
            return \App\Models\Student::where('id_number', $user->id_number)->first();
        case 'teacher':
            return \App\Models\Teacher::where('id_number', $user->id_number)->first();
        case 'staff':
            return \App\Models\Staff::where('id_number', $user->id_number)->first();
        case 'doctor':
            return \App\Models\Doctor::where('id_number', $user->id_number)->first();
        case 'nurse':
            return \App\Models\Nurse::where('id_number', $user->id_number)->first();
        default:
            return null;
    }
}

    public function Admin()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        
        $pendingExaminations = HealthExamination::where('is_approved', false)
        ->with('user') // Assuming a relationship is defined
        ->get();
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year');

       
                
        return view("$role.uploadHealthExamination", compact('pendingExaminations', 'schoolYears'));
    }

    public function create()
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        // Get all school years from the database
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
    
        // Get the current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
    
        if (!$currentSchoolYear) {
            return redirect()->back()->with('error', 'No current school year is set. Please contact the administrator.');
        }
    
        // Pass the school years and current school year to the view
        return view("$role.upload-pictures",  compact('schoolYears', 'currentSchoolYear'));
    }
    

    public function store(Request $request)
{
    try {
        // Log the incoming request data for debugging
        Log::info('Health Examination Store Request:', $request->all());

        $messages = [
            'health_examination_picture.required' => 'Please upload at least one health examination picture.',
            'health_examination_picture.array' => 'Health examination pictures must be an array.',
            'health_examination_picture.*.image' => 'Each health examination file must be an image.',
            'health_examination_picture.*.mimes' => 'Health examination images must be of type: jpeg, png, jpg, gif.',
            'health_examination_picture.*.max' => 'Each health examination image must not exceed 10MB.',
            
            'xray_picture.required' => 'Please upload at least one X-ray picture.',
            'xray_picture.array' => 'X-ray pictures must be an array.',
            'xray_picture.*.image' => 'Each X-ray file must be an image.',
            'xray_picture.*.mimes' => 'X-ray images must be of type: jpeg, png, jpg, gif.',
            'xray_picture.*.max' => 'Each X-ray image must not exceed 1MB.',
            
            'lab_result_picture.required' => 'Please upload at least one lab result picture.',
            'lab_result_picture.array' => 'Lab result pictures must be an array.',
            'lab_result_picture.*.image' => 'Each lab result file must be an image.',
            'lab_result_picture.*.mimes' => 'Lab result images must be of type: jpeg, png, jpg, gif.',
            'lab_result_picture.*.max' => 'Each lab result image must not exceed 1MB.',
            
            'school_year.required' => 'The school year is required.',
            'school_year.exists' => 'The selected school year is invalid.',
        ];
        
        $validated = $request->validate([
            'health_examination_picture' => 'required|array|max:10',
            'health_examination_picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:1148',
        
            'xray_picture' => 'required|array|max:10',
            'xray_picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:1148',
        
            'lab_result_picture' => 'required|array|max:10',
            'lab_result_picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:1148',
        
            'school_year' => 'required|string|exists:school_years,year',
        ], $messages);
        
        $schoolYear = $request->input('school_year');

        // Check if a record for this school year already exists
        $existingExamination = HealthExamination::where('id_number', Auth::user()->id_number)
            ->where('school_year', $schoolYear)
            ->first();

        if ($existingExamination) {
            Log::info('User attempted to submit multiple health examinations for the same school year.', [
                'id_number' => Auth::user()->id_number,
                'school_year' => $schoolYear
            ]);
            return response()->json(['success' => false, 'message' => 'You have already submitted health documents for the selected school year.'], 400);
        }

        // Get the current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();

        if (!$currentSchoolYear || $schoolYear !== $currentSchoolYear->year) {
            return response()->json(['success' => false, 'message' => 'You can only submit health examinations for the current school year.'], 400);
        }

        // Create a new HealthExamination instance
        $healthExamination = new HealthExamination();
        $healthExamination->id_number = Auth::user()->id_number;
        $healthExamination->school_year = $schoolYear;

        // Handle the health examination pictures upload
        if ($request->hasFile('health_examination_picture')) {
            $healthPaths = [];
            foreach ($request->file('health_examination_picture') as $healthPic) {
                $healthPaths[] = $healthPic->store('health_examinations', 'public');
            }
            $healthExamination->health_examination_picture = $healthPaths; // Assign array directly
        } else {
            return response()->json(['success' => false, 'message' => 'Health examination pictures are required.'], 400);
        }

        // Handle the x-ray pictures upload
        if ($request->hasFile('xray_picture')) {
            $xrayPaths = [];
            foreach ($request->file('xray_picture') as $xray) {
                $xrayPaths[] = $xray->store('health_examinations', 'public');
            }
            $healthExamination->xray_picture = $xrayPaths; // Assign array directly
        } else {
            return response()->json(['success' => false, 'message' => 'X-ray pictures are required.'], 400);
        }

        // Handle the lab result pictures upload
        if ($request->hasFile('lab_result_picture')) {
            $labPaths = [];
            foreach ($request->file('lab_result_picture') as $lab) {
                $labPaths[] = $lab->store('health_examinations', 'public');
            }
            $healthExamination->lab_result_picture = $labPaths; // Assign array directly
        } else {
            return response()->json(['success' => false, 'message' => 'Lab result pictures are required.'], 400);
        }

        // Mark the health examination as not approved by default
        $healthExamination->is_approved = false;
        $healthExamination->save();

        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Health Examination submitted successfully and is waiting for approval.']);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Return validation errors
        return response()->json(['success' => false, 'message' => $e->validator->errors()->first()], 422);
    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Error in HealthExaminationController@store: ' . $e->getMessage());

        // Return a JSON response indicating failure
        return response()->json(['success' => false, 'message' => 'An error occurred while processing your request. Please try again later.'], 500);
    }
}


    

    private function getCurrentSchoolYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }

    public function approve($id)
    {
        try {
            // Determine the role based on the current route prefix
            $routePrefix = request()->route()->getPrefix(); // e.g., 'admin', 'nurse', 'doctor'
            $role = strtolower(str_replace('/', '', $routePrefix));

            // Begin a transaction
            DB::beginTransaction();

            // Find the HealthExamination record, throw a 404 error if not found
            $examination = HealthExamination::findOrFail($id);

            // Check if it's already approved to avoid redundant operations
            if ($examination->is_approved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Health Examination is already approved.'
                ], 400);
            }

            // Approve the examination
            $examination->is_approved = true;
            $examination->save();

            // Find the user associated with the health examination
            $user = User::where('id_number', $examination->id_number)->first();

            // Ensure the user exists before creating a notification
            if ($user) {
                Notification::create([
                    'user_id' => $user->id_number, // Use the 'id_number' field as foreign key
                    'title' => 'Health Examination Approved',
                    'message' => 'Your health examination has been approved. You can now proceed with your medical record.',
                    'scheduled_time' => now(),
                ]);
            } else {
                // Log the issue if the user isn't found
                Log::error('User not found for id_number: ' . $examination->id_number);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found. Notification was not sent.'
                ], 404);
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Health Examination approved successfully.'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Error approving health examination: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error approving the health examination.'
            ], 500);
        }
    }
    
    public function reject($id)
    {
        try {
            // Determine the role based on the current route prefix
            $routePrefix = request()->route()->getPrefix(); // e.g., 'admin', 'nurse', 'doctor'
            $role = strtolower(str_replace('/', '', $routePrefix));

            // Begin a transaction
            DB::beginTransaction();

            // Find the HealthExamination record
            $examination = HealthExamination::findOrFail($id);

            // Find the user associated with the health examination
            $user = User::where('id_number', $examination->id_number)->first();

            // Ensure the user exists before creating a notification
            if ($user) {
                Notification::create([
                    'user_id' => $user->id_number, // Use the 'id_number' field as foreign key
                    'title' => 'Health Examination Rejected',
                    'message' => 'Your health examination has been rejected. Please upload proper pictures and try again.',
                    'scheduled_time' => now(),
                ]);
            } else {
                // Log the issue if the user isn't found
                Log::error('User not found for id_number: ' . $examination->id_number);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found. Notification was not sent.'
                ], 404);
            }

            // Delete the HealthExamination record
            $examination->delete();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Health Examination rejected and deleted successfully.'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Error rejecting health examination: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error rejecting the health examination.'
            ], 500);
        }
    }
    public function resetSchoolYear(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'school_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
        ]);
    
        $schoolYear = $request->input('school_year');
    
        try {
            // Begin transaction
            DB::beginTransaction();
    
            // Delete HealthExaminations for the selected school year
            $deletedExaminations = HealthExamination::where('school_year', $schoolYear)->delete();
    
            // Update 'is_current' flag for school years
            // Set all to false
            SchoolYear::query()->update(['is_current' => false]);
            // Set selected school year to true
            SchoolYear::where('year', $schoolYear)->update(['is_current' => true]);
    
            // Define roles to notify
            $roles = ['Admin', 'Student', 'Parent', 'Teacher', 'Staff', 'Nurse', 'Doctor'];
    
            // Iterate through each role and notify users with that role
            foreach ($roles as $role) {
                // Retrieve all users for the current role
                $users = User::where('role', $role)->get();
    
                foreach ($users as $user) {
                    // Create a notification for each user
                    $notification = Notification::create([
                        'user_id' => $user->id_number, // Ensure user_id is correctly mapped
                        'role' => $role,
                        'title' => 'School Year Reset',
                        'message' => "The school year {$schoolYear} has been reset and set as the current school year. Please upload new health examinations.",
                        'scheduled_time' => now(),
                        'is_opened' => false,
                    ]);
    
                    // Broadcast the notification for each user individually
                    event(new NewNotification($notification));
                }
            }
    
            // Commit transaction
            DB::commit();
    
            // Log the reset action
            Log::info("School year data reset for: {$schoolYear}. Total records deleted: {$deletedExaminations}");
    
            return response()->json([
                'success' => true,
                'message' => "School year data for {$schoolYear} has been successfully reset and set as the current school year. Users will need to upload new health examinations."
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
    
            // Log the error
            Log::error("Error resetting school year data for {$schoolYear}: " . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting the school year data. Please try again later.'
            ], 500);
        }
    }
    
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'examination_id' => 'required|integer|exists:health_examinations,id',
            'health_examination_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'xray_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'lab_result_pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'school_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
        ]);
    
        try {
            $examination = HealthExamination::findOrFail($request->input('examination_id'));
    
            // Optional: Ensure the user owns this examination
            if ($examination->id_number !== Auth::user()->id_number) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
    
            // Update school year if changed
            if ($examination->school_year !== $request->input('school_year')) {
                $examination->school_year = $request->input('school_year');
            }
    
            // Handle the health examination picture upload
            if ($request->hasFile('health_examination_picture')) {
                // Optionally, delete the old picture
                if ($examination->health_examination_picture && Storage::disk('public')->exists($examination->health_examination_picture)) {
                    Storage::disk('public')->delete($examination->health_examination_picture);
                }
                $examination->health_examination_picture = $request->file('health_examination_picture')->store('health_examinations', 'public');
            }
    
            // Handle the x-ray pictures upload
            if ($request->hasFile('xray_pictures')) {
                $existingXrays = json_decode($examination->xray_picture, true) ?? [];
                foreach ($request->file('xray_pictures') as $xray) {
                    $existingXrays[] = $xray->store('health_examinations', 'public');
                }
                $examination->xray_picture = $existingXrays;
            }
    
            // Handle the lab result pictures upload
            if ($request->hasFile('lab_result_pictures')) {
                $existingLabs = json_decode($examination->lab_result_picture, true) ?? [];
                foreach ($request->file('lab_result_pictures') as $lab) {
                    $existingLabs[] = $lab->store('health_examinations', 'public');
                }
                $examination->lab_result_picture = $existingLabs;
            }
    
            // Reset approval status if updated
            $examination->is_approved = false;
            $examination->save();
    
            return response()->json(['success' => true, 'message' => 'Health Examination updated successfully and is now pending approval.']);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error in HealthExaminationController@update: ' . $e->getMessage());
    
            // Return a JSON response indicating failure
            return response()->json(['success' => false, 'message' => 'An error occurred while updating your submission. Please try again later.'], 500);
        }
    }
    

    public function viewAllRecords()
    {
        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        Log::info('Fetched all health examinations records.');
        $role = strtolower(auth()->user()->role); // Ensure role is in lowercase
    
    // Return the view with the pending dental records
    return view("{$role}.uploadHealthExamination", compact('pendingExaminations'));
    }

    public function checkApprovalStatus()
    {
        try {
            $user = Auth::user();
            $currentSchoolYear = SchoolYear::where('is_current', true)->first();
    
            if (!$currentSchoolYear) {
                return response()->json(['error' => 'Current school year not set.'], 400);
            }
    
            $healthExamination = HealthExamination::where('id_number', $user->id_number)
                ->where('school_year', $currentSchoolYear->year)
                ->first();
    
            if (!$healthExamination) {
                // No submission exists for current school year
                return response()->json(['exists' => false]);
            }
    
            // Submission exists; check if it's approved
            return response()->json([
                'exists' => true,
                'is_approved' => (bool) $healthExamination->is_approved,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error checking approval status: ' . $e->getMessage());
            return response()->json(['exists' => false, 'is_approved' => false], 500);
        }
    }
    
    public function downloadPdf($id)
    {
        try {
            // Log the start of the process
            Log::info("Initiating PDF download for Health Examination ID: {$id}");
    
            // Fetch the health examination record
            $healthExamination = HealthExamination::findOrFail($id);
            Log::info("Health Examination found: ID {$healthExamination->id}");
    
            // Fetch the user using the id_number from the health examination
            $user = User::where('id_number', $healthExamination->id_number)->first();
            if (!$user) {
                Log::warning("User not found with ID Number: {$healthExamination->id_number}");
                return redirect()->back()->with('error', 'User not found.');
            }
            Log::info("User found: ID {$user->id}");
    
            // Use our helper method to get the additional information from the role-specific model
            $information = $this->getUserInformation($user);
            if (!$information) {
                Log::warning("No additional information found for User ID Number: {$user->id_number}");
            }
    
            // Determine the user's full name.
            // First, try the User model; if that is empty, try the role-specific model.
            $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            if (empty($name) && $information) {
                $name = trim(($information->first_name ?? '') . ' ' . ($information->last_name ?? ''));
            }
            if (empty($name)) {
                $name = 'N/A';
            }
    
            // Get birthdate and address from the role-specific model if available
            $birthdate = ($information && $information->birthdate) ? $information->birthdate : 'N/A';
            $address = ($information && $information->address) ? $information->address : 'N/A';
    
            // Determine role-specific field for course/department
            $role = strtolower($user->role);
            $gradeOrCourse = 'N/A';
            if ($role === 'student' && $information) {
                $gradeOrCourse = $information->grade_or_course ?? 'N/A';
            } elseif ($role === 'teacher' && $information) {
                // Change 'specialization' to whatever field holds the relevant information
                $gradeOrCourse = $information->specialization ?? 'N/A';
            } elseif ($role === 'staff' && $information) {
                $gradeOrCourse = $information->department ?? 'N/A';
            }
    
            Log::info("User Details - Name: {$name}, Birthdate: {$birthdate}, Address: {$address}, Grade/Course: {$gradeOrCourse}");
    
            // Handle profile picture
            $profilePictureBase64 = null;
            if ($information && $information->profile_picture) {
                $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
                Log::info("Looking for profile picture at: {$profilePicturePath}");
                if (file_exists($profilePicturePath)) {
                    $extension = pathinfo($information->profile_picture, PATHINFO_EXTENSION);
                    $profilePictureBase64 = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($profilePicturePath));
                    Log::info("Profile picture encoded for User ID {$user->id}");
                } else {
                    Log::warning("Profile picture file not found at: {$profilePicturePath}");
                }
            }
    
            // Load the Pilar College logo
            $logoPath = public_path('images/pilarLogo.png'); // Adjust filename/extension if needed
            if (!file_exists($logoPath)) {
                Log::warning("Logo not found at path: {$logoPath}");
                return redirect()->back()->with('error', 'Logo not found.');
            }
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
            Log::info("Logo loaded from: {$logoPath}");
    
            // Initialize images array for the PDF
            $images = [
                'Health Examination' => [],
                'X-ray' => [],
                'Lab Exam' => [],
            ];
    
            // Process Health Examination Pictures
            if (!empty($healthExamination->health_examination_picture)) {
                $healthPics = is_array($healthExamination->health_examination_picture)
                    ? $healthExamination->health_examination_picture
                    : [$healthExamination->health_examination_picture];
                foreach ($healthPics as $pic) {
                    $picPath = storage_path('app/public/' . $pic);
                    Log::info("Checking Health Examination image at: {$picPath}");
                    if (file_exists($picPath)) {
                        $images['Health Examination'][] = 'data:image/' . pathinfo($picPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($picPath));
                        Log::info("Added Health Examination image from: {$picPath}");
                    } else {
                        Log::warning("Health Examination image not found at: {$picPath}");
                    }
                }
            }
    
            // Process X-ray Pictures
            $xrayPics = $healthExamination->xray_picture ?? [];
            $xrayPics = is_array($xrayPics) ? $xrayPics : [$xrayPics];
            foreach ($xrayPics as $xray) {
                $xrayPath = storage_path('app/public/' . $xray);
                Log::info("Checking X-ray image at: {$xrayPath}");
                if (file_exists($xrayPath)) {
                    $images['X-ray'][] = 'data:image/' . pathinfo($xrayPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($xrayPath));
                    Log::info("Added X-ray image from: {$xrayPath}");
                } else {
                    Log::warning("X-ray image not found at: {$xrayPath}");
                }
            }
    
            // Process Lab Result Pictures
            $labPics = $healthExamination->lab_result_picture ?? [];
            $labPics = is_array($labPics) ? $labPics : [$labPics];
            foreach ($labPics as $lab) {
                $labPath = storage_path('app/public/' . $lab);
                Log::info("Checking Lab Result image at: {$labPath}");
                if (file_exists($labPath)) {
                    $images['Lab Exam'][] = 'data:image/' . pathinfo($labPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($labPath));
                    Log::info("Added Lab Result image from: {$labPath}");
                } else {
                    Log::warning("Lab Result image not found at: {$labPath}");
                }
            }
    
            // Prepare data for the PDF view
            $pdfData = [
                'logoBase64'             => $logoBase64,
                'name'                   => $name,
                'gradeOrCourse'          => $gradeOrCourse,
                'birthdate'              => $birthdate,
                'address'                => $address,
                'images'                 => $images,
                'profilePictureBase64'   => $profilePictureBase64,
            ];
            Log::info("PDF data prepared.");
    
            // Load the PDF view and set options
            $pdf = PDF::loadView('pdf.health-examination', $pdfData);
            // Enable remote assets if necessary (for external images)
            $pdf->setOptions(['isRemoteEnabled' => true]);
            $pdf->setPaper('A4', 'portrait');
    
            // Generate filename using the user's name (or 'N/A' if missing)
            $filename = "Health_Examination_Report_{$name}.pdf";
            Log::info("PDF generated with filename: {$filename}");
    
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Error downloading Health Examination PDF: " . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to download the Health Examination Report. Please try again later.');
        }
    }
    
    
    
    public function show($id)
    {
        // Fetch the health examination record by ID
        $healthExamination = HealthExamination::findOrFail($id);
    
        // Return the view and pass the health examination data
        return view('student.medical-record', compact('healthExamination'));
    }
    public function getPendingExaminations(Request $request)
{
    try {
        // DataTables parameters
        $draw = intval($request->input('draw'));
        $start = intval($request->input('start'));
        $length = intval($request->input('length'));
        $search = $request->input('search.value', '');

        // Determine the role based on the route prefix (admin, nurse, doctor)
        $routePrefix = $request->route()->getPrefix(); // e.g., '/admin', '/nurse', '/doctor'
        $role = strtolower(str_replace('/', '', $routePrefix)); // Remove '/' if present

        // Get the authenticated user
        $user = Auth::user();

        // Query pending examinations
        $query = HealthExamination::where('is_approved', false)
            ->with(['user', 'user.student', 'user.teacher', 'user.staff']); // Eager load all possible role-specific relationships

        // Apply search filters if any
        if (!empty($search)) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('id_number', 'like', "%{$search}%");
            })
            ->orWhereHas('user.student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('user.teacher', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('user.staff', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Role-based logic (if needed)
   

        // Implement pagination as per DataTables' requirements
        $pendingExaminations = $query->orderBy('created_at', 'desc')->paginate(
            $length,
            ['*'],
            'page',
            ($start / $length) + 1
        );

        // Transform data for DataTables
        $transformed = $pendingExaminations->map(function($exam) {
            // Determine the user's role and fetch the appropriate name
            $user = $exam->user;
            $name = 'N/A';

            if ($user->student) {
                $name = "{$user->student->first_name} {$user->student->last_name}";
            } elseif ($user->teacher) {
                $name = "{$user->teacher->first_name} {$user->teacher->last_name}";
            } elseif ($user->staff) {
                $name = "{$user->staff->first_name} {$user->staff->last_name}";
            } else {
                // If user has no role-specific model, fallback to User model's fields if available
                $name = trim("{$user->first_name} {$user->last_name}") ?: 'N/A';
            }

            return [
                'id' => $exam->id,
                'user_name' => $name,
                'id_number' => $user->id_number,
                'school_year' => $exam->school_year,
                'health_examination_pictures' => array_map(function($pic) {
                    return asset('storage/' . $pic);
                }, $exam->health_examination_picture ?? []),
                'xray_pictures' => array_map(function($xray) {
                    return asset('storage/' . $xray);
                }, $exam->xray_picture ?? []),
                'lab_result_pictures' => array_map(function($lab) {
                    return asset('storage/' . $lab);
                }, $exam->lab_result_picture ?? []),
            ];
        });

        // Prepare the response in DataTables expected format
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $pendingExaminations->total(),
            'recordsFiltered' => $pendingExaminations->total(),
            'data' => $transformed,
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching pending examinations: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json(['error' => 'Failed to fetch pending examinations.'], 500);
    }
}

    
  
    public function getRemindersData(Request $request)
    {
        try {
            // DataTables parameters
            $draw = intval($request->input('draw'));
            $start = intval($request->input('start'));
            $length = intval($request->input('length'));
            $search = $request->input('search.value', '');
    
            // Get the current school year
            $currentSchoolYear = SchoolYear::where('is_current', true)->first();
            if (!$currentSchoolYear) {
                return response()->json(['error' => 'Current school year not set.'], 400);
            }
    
            // Fetch students who have submitted health examinations for the current school year
            $submissions = HealthExamination::where('school_year', $currentSchoolYear->year)->pluck('id_number')->toArray();
    
            // Base query: students not in submissions and registered in users table with role 'Student'
            $query = Student::whereNotIn('id_number', $submissions)
                ->whereHas('user', function($q) {
                    $q->where('role', 'Student');
                })
                ->with('user');
    
            // Apply search filter if provided
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function($subQ) use ($search) {
                        $subQ->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%")
                             ->orWhere('id_number', 'like', "%{$search}%");
                    })
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('grade_or_course', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%");
                });
            }
    
            // Get total records after filtering
            $recordsFiltered = $query->count();
    
            // Get total records without filtering
            $recordsTotal = Student::whereHas('user', function($q) {
                $q->where('role', 'Student');
            })->count();
    
            // Apply pagination
            $students = $query->orderBy('created_at', 'desc')
                ->skip($start)
                ->take($length)
                ->get();
    
            // Transform data for DataTables
            $transformed = $students->map(function($student) {
                $studentName = 'N/A';
                if ($student->first_name && $student->last_name) {
                    // Use the name from the Student model
                    $studentName = "{$student->first_name} {$student->last_name}";
                } elseif ($student->user && $student->user->first_name && $student->user->last_name) {
                    // Fallback to the User model
                    $studentName = "{$student->user->first_name} {$student->user->last_name}";
                }
                return [
                    'id' => $student->id,
                    'id_number' => $student->id_number,
                    'student_name' => $studentName,
                    'grade_or_course' => $student->grade_or_course ?? 'N/A',
                    'section' => $student->section ?? 'N/A',
                    'pending_since' => $student->created_at ? $student->created_at->diffForHumans() : 'N/A',
                ];
            });
    
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $transformed,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reminders data: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to fetch reminders data.'], 500);
        }
    }
    public function sendReminders(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer|exists:students,id',
        ]);
    
        $studentIds = $request->input('student_ids');
    
        // Initialize counters
        $successful = 0;
        $failed = 0;
    
        DB::beginTransaction();
    
        try {
            foreach ($studentIds as $studentId) {
                $student = Student::with('user')->findOrFail($studentId);
                $user = $student->user; // The student user
    
                if (!$user || !$user->email) {
                    $failed++;
                    Log::warning("Student user not found or missing email for student ID: {$student->id_number}");
                    continue;
                }
    
                // Log student info
                Log::info("Processing student ID: {$student->id_number}, Name: {$user->first_name} {$user->last_name}");
    
                // Create notification for the student
                Notification::create([
                    'user_id' => $user->id_number,
                    'title' => 'Reminder: Submit Health Examination',
                    'message' => 'Please submit your health examination documents for the current school year.',
                    'scheduled_time' => now(),
                ]);
    
                // Send email to the student
                \Mail::to($user->email)->queue(new \App\Mail\StudentHealthExaminationReminder($user));
                Log::info("Notification and email sent to student ID: {$user->id_number}");
    
                // Fetch the program head for the student's course
                $studentCourse = trim(strtolower($student->grade_or_course));
                Log::info("Looking for program head for course: {$studentCourse}");
    
                $programHeadTeacher = Teacher::where('role', 'program_head')
                    ->whereRaw('LOWER(course) = ?', [$studentCourse])
                    ->first();
    
                if ($programHeadTeacher) {
                    Log::info("Program head teacher found: {$programHeadTeacher->id_number}");
    
                    $programHeadUser = $programHeadTeacher->user;
    
                    if ($programHeadUser && $programHeadUser->email) {
                        Log::info("Program head user found: {$programHeadUser->id_number}, Email: {$programHeadUser->email}");
    
                        // Create notification for the program head
                        Notification::create([
                            'user_id' => $programHeadUser->id_number,
                            'title' => 'Reminder: Student Pending Health Examination',
                            'message' => "Student {$student->first_name} {$student->last_name} has not submitted their health examination documents.",
                            'scheduled_time' => now(),
                        ]);
    
                        // Send email to the program head
                        \Mail::to($programHeadUser->email)->queue(new \App\Mail\ProgramHeadHealthExaminationReminder($programHeadUser, $student));
                        Log::info("Notification and email sent to program head ID: {$programHeadUser->id_number}");
                    } else {
                        Log::warning("Program head user not found or missing email for teacher ID: {$programHeadTeacher->id_number}");
                    }
                } else {
                    Log::warning("No program head found for course: {$student->grade_or_course}");
                }
    
                $successful++;
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => "Reminders sent successfully to {$successful} student(s) and their program heads.",
                'failed' => $failed,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending reminders: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send reminders.'], 500);
        }
    }
    
    
public function bulkReject(Request $request)
{
    $request->validate([
        'examination_ids' => 'required|array',
        'examination_ids.*' => 'integer|exists:health_examinations,id',
    ], [
        'examination_ids.required' => 'No examinations selected for rejection.',
        'examination_ids.array' => 'Invalid data format for examination IDs.',
        'examination_ids.*.integer' => 'Examination ID must be an integer.',
        'examination_ids.*.exists' => 'Selected examination does not exist.',
    ]);

    $examinationIds = $request->input('examination_ids');
    $rejectedCount = 0;
    $failedRejections = [];

    DB::beginTransaction();

    try {
        foreach ($examinationIds as $id) {
            $examination = HealthExamination::find($id);

            // Only reject examinations that are not already approved
            if ($examination->is_approved) {
                $failedRejections[] = $id;
                continue;
            }

            // Delete the examination record
            $examination->delete();

            // Send notification to the user
            $user = User::where('id_number', $examination->id_number)->first();

            if ($user) {
                Notification::create([
                    'user_id' => $user->id_number,
                    'title' => 'Health Examination Rejected',
                    'message' => 'Your health examination has been rejected. Please upload proper pictures and try again.',
                    'scheduled_time' => now(),
                ]);

                // Optionally, trigger an event for real-time notifications
                event(new NewNotification($user, 'Health Examination Rejected', 'Your health examination has been rejected. Please upload proper pictures and try again.'));
            }

            $rejectedCount++;
        }

        DB::commit();

        $message = "{$rejectedCount} examination(s) rejected successfully.";
        if (count($failedRejections) > 0) {
            $message .= " However, " . count($failedRejections) . " examination(s) were already approved and could not be rejected.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'rejected_count' => $rejectedCount,
            'failed_rejections' => $failedRejections,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in bulkReject: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while rejecting examinations. Please try again later.',
        ], 500);
    }
}
public function generateReport(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'period' => 'required|string|in:daily,weekly,monthly',
    ]);

    $period = $request->input('period');

    // Determine the date range based on the selected period
    $now = now();
    switch ($period) {
        case 'daily':
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
            $reportPeriod = 'Daily';
            break;
        case 'weekly':
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
            $reportPeriod = 'Weekly';
            break;
        case 'monthly':
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $reportPeriod = 'Monthly';
            break;
        default:
            return response()->json(['message' => 'Invalid report period selected.'], 400);
    }

    // Fetch health examinations within the date range
    $healthExaminations = HealthExamination::whereBetween('created_at', [$startDate, $endDate])
        ->where('is_approved', true)
        ->with('user')
        ->get();

    // Prepare summary data
    $totalExaminations = $healthExaminations->count();
    $uniqueStudents = $healthExaminations->unique('id_number')->count();

    // Additional statistics can be added as needed

    // Prepare data for the PDF view
    $pdfData = [
        'logoBase64' => $this->getLogoBase64(),
        'report_period' => $reportPeriod,
        'report_date' => $now->format('F j, Y, g:i a'),
        'totalExaminations' => $totalExaminations,
        'uniqueStudents' => $uniqueStudents,
        'healthExaminations' => $healthExaminations,
    ];

    // Load the PDF view
    $pdf = Pdf::loadView('pdf.health-examination-report', $pdfData);

    // Set paper size and orientation
    $pdf->setPaper('A4', 'portrait');

    // Define the filename
    $filename = "Health_Examination_Report_{$reportPeriod}_{$now->format('YmdHis')}.pdf";

    // Return the PDF as a download
    return $pdf->download($filename);
}

/**
 * Helper function to get the logo in Base64 format.
 */
private function getLogoBase64()
{
    $logoPath = public_path('images/pilarLogo.png'); // Ensure the path and extension are correct
    if (file_exists($logoPath)) {
        return 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
    }
    return '';
}

}