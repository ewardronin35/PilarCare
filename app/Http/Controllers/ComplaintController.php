<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Student;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Parents;
use App\Models\Notification;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use PDF; // Assuming you're using barryvdh/laravel-dompdf or similar

class ComplaintController extends Controller
{
    public function index()
{
    $role = strtolower(Auth::user()->role);
    $idNumber = Auth::user()->id_number;
    $complaints = Complaint::where('id_number', $idNumber)->get();
    // Fetch complaints based on role

    // Fetch the most common complaint and most used medicine
    $mostCommonComplaint = Complaint::select('sickness_description')
        ->groupBy('sickness_description')
        ->orderByRaw('COUNT(*) DESC')
        ->limit(1)
        ->value('sickness_description');

        $commonComplaintCount = Complaint::where('sickness_description', $mostCommonComplaint)
        ->count();

    $mostUsedMedicine = Complaint::select('medicine_given')
        ->groupBy('medicine_given')
        ->orderByRaw('COUNT(*) DESC')
        ->limit(1)
        ->value('medicine_given');

        $mostUsedMedicineCount = Complaint::where('medicine_given', $mostUsedMedicine)
        ->count();


    foreach ($complaints as $complaint) {
        $user = User::where('id_number', $complaint->id_number)->first();
        if ($user) {
            $complaint->first_name = $user->first_name;
            $complaint->last_name = $user->last_name;
        }
    }
    

    // Pass the data to the appropriate view based on role
    switch ($role) {
        case 'student':
        case 'parent':
        case 'teacher':
        case 'staff':
            // Ensure you have corresponding views like complaint/student.blade.php, complaint/parent.blade.php, etc.
            return view("$role.complaint", compact('complaints'));


            case 'admin':
                // Fetch complaints per role
                $studentComplaints = Complaint::where('role', 'student')->get();
                $staffComplaints = Complaint::where('role', 'staff')->get();
                $parentComplaints = Complaint::where('role', 'parent')->get();
                $teacherComplaints = Complaint::where('role', 'teacher')->get();
            
                // Pass each role's complaints as separate variables
                return view('admin.complaint', compact('studentComplaints', 'staffComplaints', 'parentComplaints', 'teacherComplaints', 'mostCommonComplaint', 'commonComplaintCount', 'mostUsedMedicine', 'mostUsedMedicineCount'));
            
                case 'nurse':
                    // Fetch complaints per role
                    $studentComplaints = Complaint::where('role', 'student')->get();
                    $staffComplaints = Complaint::where('role', 'staff')->get();
                    $parentComplaints = Complaint::where('role', 'parent')->get();
                    $teacherComplaints = Complaint::where('role', 'teacher')->get();
                
                    // Pass each role's complaints as separate variables
                    return view('nurse.complaint', compact('studentComplaints', 'staffComplaints', 'parentComplaints', 'teacherComplaints', 'mostCommonComplaint', 'commonComplaintCount', 'mostUsedMedicine', 'mostUsedMedicineCount'));

                    case 'doctor':
                        // Fetch complaints per role
                        $studentComplaints = Complaint::where('role', 'student')->get();
                        $staffComplaints = Complaint::where('role', 'staff')->get();
                        $parentComplaints = Complaint::where('role', 'parent')->get();
                        $teacherComplaints = Complaint::where('role', 'teacher')->get();
                    
                        // Pass each role's complaints as separate variables
                        return view('doctor.complaint', compact('studentComplaints', 'staffComplaints', 'parentComplaints', 'teacherComplaints', 'mostCommonComplaint', 'commonComplaintCount', 'mostUsedMedicine', 'mostUsedMedicineCount'));
        default:
            abort(403, 'Unauthorized action.');
    }
}

    
    
    // public function addComplaint()
    // {
    //     $role = strtolower(Auth::user()->role);
    //     return view('admin.addcomplaint', compact('role'));
    // }

    public function store(Request $request)
    {
        \Log::info('Received request data:', $request->all());  // Debugging statement
    
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer',
            'birthdate' => 'required|date',
            'year' => 'required|string|max:255',
            'personal_contact_number' => 'required|string|max:255',
            'pain_assessment' => 'required|integer|min:1|max:10',
            'sickness_description' => 'required|string|max:1000',
            'role' => 'required|string|max:255',
            'medicine_given' => 'required|string|max:255',
            'confine_status' => 'required|string|in:confined,not_confined',
            'go_home' => 'required|string|in:yes,no', // Added this line
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
    
        try {
            // Create the complaint
            $complaint = Complaint::create([
                'id_number' => $request->id_number,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'age' => $request->age,
                'birthdate' => $request->birthdate,
                'year' => $request->year,
                'personal_contact_number' => $request->personal_contact_number,
                'pain_assessment' => $request->pain_assessment,
                'sickness_description' => $request->sickness_description,
                'role' => $request->role,
                'medicine_given' => $request->medicine_given,
                'confine_status' => $request->confine_status,
                'go_home' => $request->go_home, // Added this line
            ]);
    
            // Reduce inventory quantity
            $inventory = Inventory::where('item_name', $request->medicine_given)->first();
            if ($inventory) {
                $inventory->quantity -= 1;
                $inventory->save();
            } else {
                return response()->json(['success' => false, 'message' => 'Medicine not found in inventory'], 404);
            }
    
            // Send notification to the user
            $user = User::where('id_number', $request->id_number)->first();
            if ($user) {
                Notification::create([
                    'user_id' => $user->id_number,
                    'title' => 'Complaint Received',
                    'message' => 'You have a new complaint added',
                    'status' => 'unread'
                ]);
            }
    
            \Log::info('Complaint and notification successfully saved:', ['complaint' => $complaint->toArray(), 'user_id' => $user->id]);
    
            $response = ['success' => true, 'message' => 'Complaint and notification successfully saved'];
    
            // Generate PDF if go_home is "yes"
            if ($complaint->go_home == 'yes') {
                $data = [
                    'date' => now()->format('Y-m-d'),
                    'name' => $complaint->first_name . ' ' . $complaint->last_name,
                    'sickness_description' => $complaint->sickness_description,
                    'medicine_given' => $complaint->medicine_given,
                    'logoBase64' => base64_encode(file_get_contents(public_path('images/pilarLogo.png'))),
                    'complaint' => $complaint,
                    'role' => $complaint->role, // Include the role in data
                ];
            
                // Fetch additional data based on role
                if ($complaint->role == 'student') {
                    $student = Student::where('id_number', $complaint->id_number)->first();
                    if ($student) {
                        $data['grade'] = $student->grade;
                        $data['section'] = $student->section;
                    }
                } elseif ($complaint->role == 'staff') {
                    $staff = Staff::where('id_number', $complaint->id_number)->first();
                    if ($staff) {
                        $data['position'] = $staff->position; // Corrected 'postion' to 'position'
                    }
                } elseif ($complaint->role == 'teacher') {
                    $teacher = Teacher::where('id_number', $complaint->id_number)->first();
                    if ($teacher) {
                        $data['bed_or_hed'] = $teacher->bed_or_hed;
                    }
                }
            
                // Load the Blade view and pass the data
                $pdf = PDF::loadView('pdf.single_complaint_report', $data);
            
                // Define the file name
                $fileName = 'go_home_' . $complaint->id_number . '_' . now()->format('Ymd') . '.pdf';
            
                // Save the PDF to storage
                $pdf->save(storage_path('app/public/reports/' . $fileName));
                $reportUrl = asset('storage/reports/' . $fileName);
            
                // Include the report URL in the response
                $response['report_url'] = $reportUrl;
            }
    
            return response()->json($response);
    
        } catch (\Exception $e) {
            \Log::error('Error while saving complaint:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while saving the complaint. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    
    
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        $formattedConfineStatus = ucwords(str_replace('_', ' ', $complaint->confine_status));

        return response()->json([
            'first_name' => $complaint->first_name,
            'last_name' => $complaint->last_name,
            'sickness_description' => $complaint->sickness_description,
            'pain_assessment' => $complaint->pain_assessment,
            'confine_status' => $formattedConfineStatus, // Use formatted value
            'medicine_given' => $complaint->medicine_given,
            'status' => $complaint->status,
        ]);
    }
    
    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);
        
        // Return the complaint data for editing
        return response()->json($complaint);
    }
    
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->notes = $request->input('notes');
        $complaint->status = $request->input('status');
        $complaint->save();
    
        return redirect()->back()->with('success', 'Complaint updated successfully.');
    }

    public function fetchStudentData($id)
    {
        try {
            \DB::enableQueryLog();
            \Log::info('Fetching data for ID: ' . $id);
            
            // Fetch the user from the users table
            $user = User::where('id_number', $id)->first();
            \Log::info('User: ' . json_encode($user));
    
        
            // Check if the user exists, since we need the first_name and last_name from this table
            if ($user) {
                return response()->json([
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'role' => $user->role,
                    'id_number' => $user->id_number
                ]);
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function fetchPersonData($id)
    {
        try {
            // Fetch from 'User' table first (for all roles)
            $user = User::where('id_number', $id)->first();
        
            if (!$user) {
                // Check 'students', 'teachers', and 'staff' tables
                $student = Student::where('id_number', $id)->first();
                $teacher = Teacher::where('id_number', $id)->first();
                $staff = Staff::where('id_number', $id)->first();
        
                if ($student) {
                    $user = $student;
                    $user->role = 'student';
                } elseif ($teacher) {
                    $user = $teacher;
                    $user->role = 'teacher';
                } elseif ($staff) {
                    $user = $staff;
                    $user->role = 'staff';
                } else {
                    return response()->json(['error' => 'Person not found'], 404);
                }
            }
        
            // Fetch 'information' table for birthdate and personal_contact_number
            $information = \DB::table('information')
                ->where('id_number', $id)
                ->first(['birthdate', 'personal_contact_number']);
        
            // Log the information fetched
            \Log::info('Information fetched: ', (array) $information);
        
            if (!$information) {
                // Handle the case where no matching record is found in 'information' table
                return response()->json(['error' => 'Information not found for this ID number'], 404);
            }
        
            // Overwrite the user birthdate and contact number
            $user->birthdate = $information->birthdate;
            $user->personal_contact_number = $information->personal_contact_number;
        
            // Calculate the age based on the birthdate
            $age = $this->calculateAge($user->birthdate);
        
            return response()->json([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'role' => $user->role,
                'id_number' => $user->id_number,
                'birthdate' => $user->birthdate,
                'age' => $age,
                'personal_contact_number' => $user->personal_contact_number
            ], 200);
        
        } catch (\Exception $e) {
            \Log::error('Error fetching person data: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    private function calculateAge($birthdate)
    {
        $birthDate = new \DateTime($birthdate);
        $currentDate = new \DateTime();
        $age = $currentDate->diff($birthDate)->y;
        return $age;
    }

    public function getAvailableMedicines()
    {
        try {
            $medicines = Inventory::where('quantity', '>', 0)->where('type', 'medicine')->pluck('item_name');
            return response()->json($medicines);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function generatePdfReport(Request $request, $role)
    {
        // Validate the role
        $validRoles = ['student', 'staff', 'parent', 'teacher'];
        if (!in_array(strtolower($role), $validRoles)) {
            return response()->json(['success' => false, 'message' => 'Invalid role specified.'], 400);
        }

        try {
            // Fetch complaints based on role
            $complaints = Complaint::where('role', strtolower($role))->get();

            if ($complaints->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No complaints found for this role.'], 404);
            }

            // Fetch the most common complaint and most used medicine
            $mostCommonComplaint = Complaint::select('sickness_description')
                ->groupBy('sickness_description')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->value('sickness_description');

            $commonComplaintCount = Complaint::where('sickness_description', $mostCommonComplaint)
                ->count();

            $mostUsedMedicine = Complaint::select('medicine_given')
                ->groupBy('medicine_given')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->value('medicine_given');

            $mostUsedMedicineCount = Complaint::where('medicine_given', $mostUsedMedicine)
                ->count();

            // Prepare data for the PDF
            $data = [
                'role' => ucfirst($role),
                'complaints' => $complaints,
                'mostCommonComplaint' => $mostCommonComplaint,
                'commonComplaintCount' => $commonComplaintCount,
                'mostUsedMedicine' => $mostUsedMedicine,
                'mostUsedMedicineCount' => $mostUsedMedicineCount,
                'logoBase64' => base64_encode(file_get_contents(public_path('images/logo.png'))), // Adjust the path to your logo
            ];

            // Load the Blade view and pass the data
            $pdf = PDF::loadView('pdf.complaint_report', $data);

            // Define the file name
            $fileName = 'complaint_report_' . strtolower($role) . '_' . now()->timestamp . '.pdf';

            // Return the generated PDF as a download
            return $pdf->download($fileName);

            // Alternatively, to store the PDF and provide a link:
            /*
            $pdf->save(storage_path('app/public/reports/' . $fileName));
            $reportUrl = asset('storage/reports/' . $fileName);
            return response()->json(['success' => true, 'report_url' => $reportUrl]);
            */

        } catch (\Exception $e) {
            \Log::error('Error generating PDF report:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while generating the report.'], 500);
        }
    }
    public function generateComplaintReport($complaintId)
{
    try {
        $complaint = Complaint::findOrFail($complaintId);

        // Get the logo and encode it
        $logoPath = public_path('images/logo.png');
        if (!File::exists($logoPath)) {
            throw new \Exception('Logo file not found.');
        }
        $logoData = base64_encode(File::get($logoPath));
        $logoBase64 = 'data:image/png;base64,' . $logoData;

        // Pass data to the view
        $pdf = PDF::loadView('pdf.complaint_report', [
            'complaint' => $complaint,
            'logoBase64' => $logoBase64,
            // Add other necessary data
        ]);

        // Save or return the PDF as needed
        return $pdf->download('complaint_report.pdf');

    } catch (\Exception $e) {
        \Log::error('Error while saving complaint: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
