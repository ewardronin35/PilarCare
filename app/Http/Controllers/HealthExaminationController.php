<?php
namespace App\Http\Controllers;

use App\Models\HealthExamination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Notification; // Import the Notification model
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
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
                $examination->xray_picture = json_encode($existingXrays);
            }
    
            // Handle the lab result pictures upload
            if ($request->hasFile('lab_result_pictures')) {
                $existingLabs = json_decode($examination->lab_result_picture, true) ?? [];
                foreach ($request->file('lab_result_pictures') as $lab) {
                    $existingLabs[] = $lab->store('health_examinations', 'public');
                }
                $examination->lab_result_picture = json_encode($existingLabs);
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
        // Fetch the health examination record
        $healthExamination = HealthExamination::findOrFail($id);
    
        // Fetch the user information using the 'id_number' field from the health examination
        $user = User::where('id_number', $healthExamination->id_number)->first();
    
        // Fetch the user's additional information like birthdate, address
        $information = Information::where('id_number', $user->id_number)->first();
    
        // Determine role-specific data
        $role = strtolower($user->role); // Assuming role is stored in lowercase in the database
        $gradeOrCourse = 'N/A'; // Default to N/A if no role-specific data is found
        
        // Fetch grade_or_course or department based on the user's role
        if ($role === 'student') {
            $student = Student::where('id_number', $user->id_number)->first();
            $gradeOrCourse = $student ? $student->grade_or_course : 'N/A';
        } elseif ($role === 'teacher') {
            $teacher = Teacher::where('id_number', $user->id_number)->first();
            $gradeOrCourse = $teacher ? $teacher->specialization : 'N/A'; // Replace 'specialization' with the relevant field
        } elseif ($role === 'staff') {
            $staff = Staff::where('id_number', $user->id_number)->first();
            $gradeOrCourse = $staff ? $staff->department : 'N/A'; // Replace 'department' with the relevant field
        }
        
    
        // If no data is found for user or information, default to 'N/A'
        $name = $user ? $user->first_name . ' ' . $user->last_name : 'N/A';
        $birthdate = $information ? $information->birthdate : 'N/A';
        $address = $information ? $information->address : 'N/A';
    
        // Fetch the Pilar College logo for the PDF
        $logoPath = public_path('images/pilarLogo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    
        // Fetch the image paths from storage
        $healthImage = $healthExamination->health_examination_picture ? storage_path('app/public/' . $healthExamination->health_examination_picture) : null;
        $xrayImages = $healthExamination->xray_picture ? json_decode($healthExamination->xray_picture, true) : [];
        $labImages = $healthExamination->lab_result_picture ? json_decode($healthExamination->lab_result_picture, true) : [];
    
        // Collect all images for the PDF
        $images = [];
        if ($healthImage) {
            $images['Health Examination'] = $healthImage;
        }
        if ($xrayImages) {
            foreach ($xrayImages as $xray) {
                $images['X-ray'][] = storage_path('app/public/' . $xray);
            }
        }
        if ($labImages) {
            foreach ($labImages as $lab) {
                $images['Lab Exam'][] = storage_path('app/public/' . $lab);
            }
        }
    
        // Pass the fetched data to the PDF view
        $pdf = PDF::loadView('pdf.health-examination', compact('healthExamination', 'name', 'gradeOrCourse', 'birthdate', 'address', 'images', 'logoBase64'));
    
        // Download the PDF
        return $pdf->download('health-examination.pdf');
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
    
        // Fetch search query
        $search = $request->input('search', '');
    
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
    
        // Fetch the examinations
        $pendingExaminations = $query->orderBy('created_at', 'desc')->get();
    
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
    
        return response()->json($transformed);
    }
    
}