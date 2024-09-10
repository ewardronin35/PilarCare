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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Correct namespace



class HealthExaminationController extends Controller
{
    public function index()
    {
        $healthExaminations = HealthExamination::where('user_id', auth()->id())->get();
        return view('student.upload-pictures', compact('healthExaminations'));
    }
    public function create()
    {
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $healthExamination = HealthExamination::where('user_id', auth()->id())
            ->where('school_year', $currentSchoolYear)
            ->first();

        if ($healthExamination) {
            if ($healthExamination->is_approved) {
                return redirect()->route('student.medical-record.create');
            } else {
                return view('student.upload-pictures')->with('pending', true);
            }
        }

        return view('student.upload-pictures')->with('pending', false);
    }

    public function medicalRecord()
    {
        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        
        return view('admin.medical-record', compact('pendingExaminations'));
    }
    
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'xray_pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'lab_result_pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $currentSchoolYear = $this->getCurrentSchoolYear();

            // Check if the student has already uploaded health documents for this school year
            $existingExamination = HealthExamination::where('user_id', auth()->id())
                ->where('school_year', $currentSchoolYear)
                ->first();

            if ($existingExamination) {
                return response()->json(['success' => false, 'message' => 'You have already submitted health documents for the current school year.'], 400);
            }

            // Create a new HealthExamination instance
            $healthExamination = new HealthExamination();
            $healthExamination->user_id = auth()->id();
            $healthExamination->school_year = $currentSchoolYear;

            // Handle the health examination picture upload
            if ($request->hasFile('health_examination_picture')) {
                $healthExamination->health_examination_picture = $request->file('health_examination_picture')->store('health_examinations', 'public');
            }

            // Handle the x-ray pictures upload
            if ($request->hasFile('xray_pictures')) {
                $xrayPaths = [];
                foreach ($request->file('xray_pictures') as $xray) {
                    $xrayPaths[] = $xray->store('health_examinations', 'public');
                }
                $healthExamination->xray_picture = json_encode($xrayPaths);
            }

            // Handle the lab result pictures upload
            if ($request->hasFile('lab_result_pictures')) {
                $labPaths = [];
                foreach ($request->file('lab_result_pictures') as $lab) {
                    $labPaths[] = $lab->store('health_examinations', 'public');
                }
                $healthExamination->lab_result_picture = json_encode($labPaths);
            }

            // Mark the health examination as not approved by default
            $healthExamination->is_approved = false;
            $healthExamination->save();

            // Set a session variable to indicate that the health examination is pending approval
            session(['health_examination_pending' => true]);

            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Health Examination submitted successfully and is waiting for approval.']);
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
        // Find the HealthExamination record, throw a 404 error if not found
        $examination = HealthExamination::findOrFail($id);
    
        // Approve the examination
        $examination->is_approved = true;
        $examination->save();
    
        // Find the user associated with the health examination
        $user = User::find($examination->user_id);
    
        // Ensure the user exists before creating a notification
        if ($user) {
            Notification::create([
                'user_id' => $user->id_number, // Use the correct identifier from the users table
                'title' => 'Health Examination Approved',
                'message' => 'Your health examination has been approved. You can now proceed with your medical record.',
                'scheduled_time' => now(), // You can set a specific time if needed
            ]);
    
            return redirect()->route('admin.uploadHealthExamination')->with('success', 'Health Examination approved successfully.');
        } else {
            // Handle the case where the user is not found
            return redirect()->route('admin.uploadHealthExamination')->with('error', 'User not found. Notification was not sent.');
        }
    }
    
    public function reject($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->delete();

        return redirect()->route('admin.uploadHealthExamination')->with('success', 'Health Examination rejected and deleted successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
          'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'xray_pictures' => 'required|array|min:2|max:2', // Ensuring exactly 2 X-ray pictures
            'xray_pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
         'lab_result_pictures' => 'required|array|min:4|max:4', // Ensuring exactly 4 Lab result pictures
            'lab_result_pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $examination = HealthExamination::findOrFail($request->examination_id);

        if ($request->hasFile('health_examination_picture')) {
            $path = $request->file('health_examination_picture')->store('health_examinations', 'public');
            $examination->health_examination_picture = $path;
        }

        if ($request->hasFile('xray_picture')) {
            $path = $request->file('xray_picture')->store('health_examinations', 'public');
            $examination->xray_picture = $path;
        }

        if ($request->hasFile('lab_result_picture')) {
            $path = $request->file('lab_result_picture')->store('health_examinations', 'public');
            $examination->lab_result_picture = $path;
        }

        $examination->save();

        return response()->json(['success' => true, 'message' => 'Health Examination updated successfully.']);
    }

    public function viewAllRecords()
    {

        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        Log::info('Fetched all health examinations records.');
        return view('admin.uploadHealthExamination', compact('pendingExaminations'));
    }
    public function checkApprovalStatus()
    {
        try {
            $user = Auth::user();
            $healthExamination = HealthExamination::where('user_id', $user->id)->where('is_approved', true)->first();
    
            return response()->json([
                'is_approved' => $healthExamination ? true : false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while checking approval status.'
            ], 500);
        }
    }
    public function downloadPdf($id)
    {
        // Fetch the health examination record
        $healthExamination = HealthExamination::findOrFail($id);
    
        // Fetch the user information using the 'user_id' field from the health examination
        $user = User::find($healthExamination->user_id);
    
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
    
}
