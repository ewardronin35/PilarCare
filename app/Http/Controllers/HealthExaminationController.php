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
        return view("$role.uploadHealthExamination", compact('schoolYears', 'currentSchoolYear'));
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
            'xray_picture.*.max' => 'Each X-ray image must not exceed 10MB.',
            
            'lab_result_picture.required' => 'Please upload at least one lab result picture.',
            'lab_result_picture.array' => 'Lab result pictures must be an array.',
            'lab_result_picture.*.image' => 'Each lab result file must be an image.',
            'lab_result_picture.*.mimes' => 'Lab result images must be of type: jpeg, png, jpg, gif.',
            'lab_result_picture.*.max' => 'Each lab result image must not exceed 10MB.',
            
            'school_year.required' => 'The school year is required.',
            'school_year.exists' => 'The selected school year is invalid.',
        ];
        
        $validated = $request->validate([
            'health_examination_picture' => 'required|array|max:10',
            'health_examination_picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:10048',
        
            'xray_picture' => 'required|array|max:10',
            'xray_picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:10048',
        
            'lab_result_picture' => 'required|array|max:10',
            'lab_result_picture.*' => 'image|mimes:jpeg,png,jpg,gif|max:10048',
        
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
        \Log::error('Error in HealthExaminationController@store: ' . $e->getMessage());

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
            // Log the initiation of the PDF download process
            Log::info("Initiating PDF download for Health Examination ID: {$id}");
    
            // Fetch the health examination record
            $healthExamination = HealthExamination::findOrFail($id);
            Log::info("Health Examination found: ID {$healthExamination->id}");
    
            // Fetch the user information using the 'id_number' field from the health examination
            $user = User::where('id_number', $healthExamination->id_number)->first();
            if (!$user) {
                Log::warning("User not found with ID Number: {$healthExamination->id_number}");
                return redirect()->back()->with('error', 'User not found.');
            }
            Log::info("User found: ID {$user->id}, Name: {$user->first_name} {$user->last_name}");
    
            // Fetch the user's additional information like birthdate, address
            $information = Information::where('id_number', $user->id_number)->first();
            if (!$information) {
                Log::warning("Information not found for User ID Number: {$user->id_number}");
                // Continue with 'N/A' if Information is optional
            } else {
                Log::info("Information found for User ID Number: {$user->id_number}");
            }
    
            // Determine role-specific data
            $role = strtolower($user->role); // Ensure role is in lowercase
            $gradeOrCourse = 'N/A'; // Default to N/A if no role-specific data is found
    
            // Fetch grade_or_course or department based on the user's role
            switch ($role) {
                case 'student':
                    $student = Student::where('id_number', $user->id_number)->first();
                    $gradeOrCourse = $student ? $student->grade_or_course : 'N/A';
                    if ($student) {
                        Log::info("Student found: ID {$student->id}, Grade/Course: {$student->grade_or_course}");
                    } else {
                        Log::warning("Student record not found for User ID Number: {$user->id_number}");
                    }
                    break;
                case 'teacher':
                    $teacher = Teacher::where('id_number', $user->id_number)->first();
                    $gradeOrCourse = $teacher ? $teacher->specialization : 'N/A'; // Replace 'specialization' with the relevant field
                    if ($teacher) {
                        Log::info("Teacher found: ID {$teacher->id}, Specialization: {$teacher->specialization}");
                    } else {
                        Log::warning("Teacher record not found for User ID Number: {$user->id_number}");
                    }
                    break;
                case 'staff':
                    $staff = Staff::where('id_number', $user->id_number)->first();
                    $gradeOrCourse = $staff ? $staff->department : 'N/A'; // Replace 'department' with the relevant field
                    if ($staff) {
                        Log::info("Staff found: ID {$staff->id}, Department: {$staff->department}");
                    } else {
                        Log::warning("Staff record not found for User ID Number: {$user->id_number}");
                    }
                    break;
                // Add other roles as needed
                default:
                    Log::warning("Unrecognized role '{$role}' for User ID Number: {$user->id_number}");
                    $gradeOrCourse = 'N/A';
            }
    
            // If no data is found for user or information, default to 'N/A'
            $name = $user ? "{$user->first_name} {$user->last_name}" : 'N/A';
            $birthdate = $information ? $information->birthdate : 'N/A';
            $address = $information ? $information->address : 'N/A';
    
            Log::info("User Details - Name: {$name}, Birthdate: {$birthdate}, Address: {$address}");
    
            // Handle profile picture
            $profilePictureBase64 = null;
            if ($information && $information->profile_picture) {
                $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
                if (file_exists($profilePicturePath)) {
                    $profilePictureBase64 = 'data:image/' . pathinfo($information->profile_picture, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($profilePicturePath));
                    Log::info("Profile picture found and encoded for User ID {$user->id}");
                } else {
                    Log::warning("Profile picture file does not exist at path: {$profilePicturePath}");
                }
            }
    
            // Fetch the Pilar College logo for the PDF
            $logoPath = public_path('images/pilarLogo.png'); // Ensure the path and extension are correct
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
                Log::info("Logo found and encoded from path: {$logoPath}");
            } else {
                Log::warning("Logo file does not exist at path: {$logoPath}");
            }
    
            // Initialize images array with consistent structure (arrays for each category)
            $images = [
                'Health Examination' => [],
                'X-ray' => [],
                'Lab Exam' => [],
            ];
    
            // Handle Health Examination Pictures
            if (!empty($healthExamination->health_examination_picture)) {
                // Ensure it's an array
                $healthPictures = is_array($healthExamination->health_examination_picture) ? $healthExamination->health_examination_picture : [$healthExamination->health_examination_picture];
    
                foreach ($healthPictures as $pic) {
                    $picPath = storage_path('app/public/' . $pic);
                    if (file_exists($picPath)) {
                        $images['Health Examination'][] = 'data:image/' . pathinfo($picPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($picPath));
                        Log::info("Health Examination image added from path: {$picPath}");
                    } else {
                        Log::warning("Health Examination image does not exist at path: {$picPath}");
                    }
                }
            }
    
            // Handle X-ray images
            $xrayImages = $healthExamination->xray_picture ?? [];
            if (!empty($xrayImages)) {
                // Ensure it's an array
                $xrayPictures = is_array($xrayImages) ? $xrayImages : [$xrayImages];
    
                foreach ($xrayPictures as $xray) {
                    $xrayPath = storage_path('app/public/' . $xray);
                    if (file_exists($xrayPath)) {
                        $images['X-ray'][] = 'data:image/' . pathinfo($xrayPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($xrayPath));
                        Log::info("X-ray image added from path: {$xrayPath}");
                    } else {
                        Log::warning("X-ray image does not exist at path: {$xrayPath}");
                    }
                }
            }
    
            // Handle Lab Result images
            $labImages = $healthExamination->lab_result_picture ?? [];
            if (!empty($labImages)) {
                // Ensure it's an array
                $labPictures = is_array($labImages) ? $labImages : [$labImages];
    
                foreach ($labPictures as $lab) {
                    $labPath = storage_path('app/public/' . $lab);
                    if (file_exists($labPath)) {
                        $images['Lab Exam'][] = 'data:image/' . pathinfo($labPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($labPath));
                        Log::info("Lab Result image added from path: {$labPath}");
                    } else {
                        Log::warning("Lab Result image does not exist at path: {$labPath}");
                    }
                }
            }
    
            // Prepare data for the PDF view
            $pdfData = [
                'logoBase64' => $logoBase64,
                'name' => $name,
                'gradeOrCourse' => $gradeOrCourse,
                'birthdate' => $birthdate,
                'address' => $address,
                'images' => $images,
                'profilePictureBase64' => $profilePictureBase64,
            ];
    
            Log::info("Prepared data for PDF generation.");
    
            // Load the PDF view with the data
            $pdf = PDF::loadView('pdf.health-examination', $pdfData);
            Log::info("PDF view loaded successfully.");
    
            // Optionally, set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
    
            // Generate a meaningful filename
            $filename = "Health_Examination_Report_{$user->first_name}_{$user->last_name}.pdf";
            Log::info("PDF generated with filename: {$filename}");
    
            // Download the PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Log the exception details
            Log::error("Error downloading Health Examination PDF: " . $e->getMessage());
    
            // Redirect back with an error message
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
        // Determine the role based on the route prefix (admin, nurse, doctor)
        $routePrefix = $request->route()->getPrefix(); // e.g., '/admin', '/nurse', '/doctor'
        $role = strtolower(str_replace('/', '', $routePrefix)); // Remove '/' if present
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Fetch search query correctly
        $search = $request->input('search.value', '');
    
        // Query pending examinations
        $query = HealthExamination::where('is_approved', false)
            ->with('user');
    
        // Apply search filters if any
        if (!empty($search)) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }
    
        // Role-based logic (if needed)
        if (in_array($role, ['nurse', 'doctor'])) {
            $query->where('id_number', $user->id_number);
        }
    
        // Implement pagination as per DataTables' requirements
        $pendingExaminations = $query->orderBy('created_at', 'desc')->paginate($request->input('length', 10), ['*'], 'start', $request->input('start', 0) / $request->input('length', 10));
    
        // Transform data for JSON response
        $transformed = $pendingExaminations->map(function($exam) {
            return [
                'id' => $exam->id,
                'user_name' => $exam->user->first_name . ' ' . $exam->user->last_name,
                'id_number' => $exam->user->id_number,
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
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $pendingExaminations->total(),
            'recordsFiltered' => $pendingExaminations->total(),
            'data' => $transformed,
        ]);
    }
    public function parentDownloadPdf($studentIdNumber)
    {
        try {
            $user = Auth::user();
            Log::info("Authenticated User ID: {$user->id}, Role: {$user->role}");
    
            // Check if the authenticated user is a parent
            if (strtolower($user->role) !== 'parent') {
                Log::warning("User {$user->id} attempted to download PDF without parent role.");
                abort(403, 'Unauthorized action.');
            }
    
            // Find the parent record
            $parent = Parents::where('id_number', $user->id_number)->first();
            if (!$parent) {
                Log::warning("Parent record not found for user ID: {$user->id}");
                abort(403, 'Parent record not found.');
            }
            Log::info("Parent found: ID {$parent->id}, Student ID Number: {$parent->student_id}");
    
            // Fetch the student using id_number
            $student = Student::where('id_number', $studentIdNumber)->first();
            if (!$student) {
                Log::warning("Student not found with ID Number: {$studentIdNumber}");
                abort(404, 'Student not found.');
            }
            Log::info("Student found: ID {$student->id}, ID Number: {$student->id_number}");
    
            // Ensure the parent is linked to the student
            if ($parent->student_id !== $student->id_number) {
                Log::warning("Parent ID {$parent->id} is not linked to Student ID Number {$student->id_number}");
                abort(403, 'You are not authorized to access this student\'s records.');
            }
    
            // Fetch all approved health examinations for the student
            $healthExaminations = HealthExamination::where('id_number', $student->id_number)
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->get();
    
            if ($healthExaminations->isEmpty()) {
                Log::info("No approved health examinations found for Student ID {$student->id}");
                return redirect()->back()->with('error', 'No approved health examinations found for this student.');
            }
    
            // Fetch the student's personal information
            $information = Information::where('id_number', $student->id_number)->first();
            if (!$information) {
                Log::warning("Information not found for Student ID {$student->id}");
                abort(404, 'Student information not found.');
            }
            Log::info("Information found for Student ID {$student->id}");
    
            // Handle profile picture
            $profilePictureBase64 = null;
            if ($information->profile_picture) {
                $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
                if (file_exists($profilePicturePath)) {
                    $profilePictureBase64 = 'data:image/' . pathinfo($information->profile_picture, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($profilePicturePath));
                    Log::info("Profile picture found and encoded for Student ID {$student->id}");
                } else {
                    Log::warning("Profile picture file does not exist at path: {$profilePicturePath}");
                }
            }
    
            // Fetch the Pilar College logo for the PDF
            $logoPath = public_path('images/pilarLogo.jpg'); // Ensure the path and extension are correct
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
                Log::info("Logo found and encoded.");
            } else {
                Log::warning("Logo file does not exist at path: {$logoPath}");
            }
    
            // Prepare images array for the PDF
            $images = [
                'Health Examination' => [],
                'X-ray' => [],
                'Lab Exam' => [],
            ];
    
            foreach ($healthExaminations as $exam) {
                // Handle Health Examination Pictures
                if (!empty($exam->health_examination_picture)) {
                    // Assuming model casting converts JSON to array
                    foreach ($exam->health_examination_picture as $pic) {
                        $picPath = storage_path('app/public/' . $pic);
                        if (file_exists($picPath)) {
                            $images['Health Examination'][] = 'data:image/' . pathinfo($pic, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($picPath));
                            Log::info("Added Health Examination picture: {$pic}");
                        } else {
                            Log::warning("Health Examination image does not exist at path: {$picPath}");
                        }
                    }
                }
    
                // Handle X-ray Pictures
                if (!empty($exam->xray_picture)) {
                    foreach ($exam->xray_picture as $xray) {
                        $xrayPath = storage_path('app/public/' . $xray);
                        if (file_exists($xrayPath)) {
                            $images['X-ray'][] = 'data:image/' . pathinfo($xray, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($xrayPath));
                            Log::info("Added X-ray picture: {$xray}");
                        } else {
                            Log::warning("X-ray image does not exist at path: {$xrayPath}");
                        }
                    }
                }
    
                // Handle Lab Result Pictures
                if (!empty($exam->lab_result_picture)) {
                    foreach ($exam->lab_result_picture as $lab) {
                        $labPath = storage_path('app/public/' . $lab);
                        if (file_exists($labPath)) {
                            $images['Lab Exam'][] = 'data:image/' . pathinfo($lab, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($labPath));
                            Log::info("Added Lab Result picture: {$lab}");
                        } else {
                            Log::warning("Lab Result image does not exist at path: {$labPath}");
                        }
                    }
                }
            }
    
            // Pass the fetched data to the PDF view
            $pdf = Pdf::loadView('pdf.health-examination', [
                'logoBase64' => $logoBase64,
                'name' => "{$student->first_name} {$student->last_name}",
                'gradeOrCourse' => is_array($student->grade_or_course) ? implode(', ', $student->grade_or_course) : ($student->grade_or_course ?? 'N/A'),
                'birthdate' => $information->birthdate ?? 'N/A',
                'address' => $information->address ?? 'N/A',
                'images' => $images,
                'profilePictureBase64' => $profilePictureBase64,
            ]);
    
            // Set paper size and orientation if needed
            $pdf->setPaper('A4', 'portrait');
    
            // Generate a meaningful filename
            $filename = "Clinic_Health_Examination_Report_{$student->first_name}_{$student->last_name}.pdf";
    
            // Return the PDF as a download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error downloading Health Examination PDF for parent: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Unable to download the Health Examination Report. Please try again later.');
        }
    }
    
}