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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintReceived;
use App\Mail\ParentComplaintNotification;
use Carbon\Carbon;

use PDF; // Assuming you're using barryvdh/laravel-dompdf or similar

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $role = strtolower(Auth::user()->role);
        $idNumber = Auth::user()->id_number;
    
        // Handle search query and filter
        $search = $request->input('search');
        $filter = $request->input('filter'); // 'past' or 'present'
    
        // Initialize the query with eager loading
        $query = Complaint::with('user')->where('id_number', $idNumber);
    
        // Apply filter based on 'filter' parameter
        if ($filter) {
            if ($filter === 'past') {
                // Define past complaints as older than 1 day
                $oneDayAgo = Carbon::now()->subDay();
                $query->where('created_at', '<', $oneDayAgo);
            } elseif ($filter === 'present') {
                // Define present complaints as within the last 1 day
                $oneDayAgo = Carbon::now()->subDay();
                $query->where('created_at', '>=', $oneDayAgo);
            }
        }
    
        // Apply search if 'search' parameter is present
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('sickness_description', 'LIKE', "%{$search}%")
                  ->orWhere('medicine_given', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('first_name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }
    
        // Log the query for debugging
        \Log::info('Complaint Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
        ]);
    
        // Fetch complaints with pagination (10 per page)
        $complaints = $query->orderBy('created_at', 'desc')->paginate(10);
    
        // Fetch the most common complaint and most used medicine
        $mostCommonComplaint = Complaint::where('id_number', $idNumber)
            ->select('sickness_description')
            ->groupBy('sickness_description')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->value('sickness_description');
    
        $commonComplaintCount = Complaint::where('id_number', $idNumber)
            ->where('sickness_description', $mostCommonComplaint)
            ->count();
    
        $mostUsedMedicine = Complaint::where('id_number', $idNumber)
            ->select('medicine_given')
            ->groupBy('medicine_given')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->value('medicine_given');
    
        $mostUsedMedicineCount = Complaint::where('id_number', $idNumber)
            ->where('medicine_given', $mostUsedMedicine)
            ->count();
    
        // Pass the data to the appropriate view based on role
        switch ($role) {
            case 'student':
            case 'parent':
            case 'teacher':
            case 'staff':
                return view("$role.complaint", compact(
                    'complaints', 
                    'mostCommonComplaint', 
                    'commonComplaintCount', 
                    'mostUsedMedicine', 
                    'mostUsedMedicineCount'
                ));
    
            case 'admin':
            case 'nurse':
            case 'doctor':
                // Fetch complaints per role with eager loading
                $roles = ['student', 'staff', 'parent', 'teacher'];
                $studentComplaints = Complaint::with('user')->where('role', 'student')->get();
                $staffComplaints = Complaint::with('user')->where('role', 'staff')->get();
                $parentComplaints = Complaint::with('user')->where('role', 'parent')->get();
                $teacherComplaints = Complaint::with('user')->where('role', 'teacher')->get();
    
                // Pass each role's complaints as separate variables
                return view("{$role}.complaint", compact(
                    'studentComplaints', 
                    'staffComplaints', 
                    'parentComplaints', 
                    'teacherComplaints', 
                    'mostCommonComplaint', 
                    'commonComplaintCount', 
                    'mostUsedMedicine', 
                    'mostUsedMedicineCount'
                ));
    
            default:
                abort(403, 'Unauthorized action.');
        }
    }
    
    
public function store(Request $request)
{
    \Log::info('Received request data:', $request->all());

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
        'go_home' => 'required|string|in:yes,no',
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
            'go_home' => $request->go_home,
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

        \Log::info('Complaint and notification successfully saved:', ['complaint' => $complaint->toArray(), 'user_id' => $user->id ?? 'N/A']);

        // Generate PDF if go_home is "yes"
        if ($complaint->go_home == 'yes') {
            $data = [
                'date' => now()->format('Y-m-d'),
                'name' => $complaint->first_name . ' ' . $complaint->last_name,
                'sickness_description' => $complaint->sickness_description,
                'medicine_given' => $complaint->medicine_given,
                'logoBase64' => base64_encode(file_get_contents(public_path('images/pilarLogo.png'))),
                'complaint' => $complaint,
                'role' => $complaint->role,
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
                    $data['position'] = $staff->position;
                }
            } elseif ($complaint->role == 'teacher') {
                $teacher = Teacher::where('id_number', $complaint->id_number)->first();
                if ($teacher) {
                    $data['bed_or_hed'] = $teacher->bed_or_hed;
                }
            }

            // Load the Blade view and pass the data
            $pdf = PDF::loadView('pdf.single_complaint_report', $data);

            // Define the file name using complaint ID for uniqueness
            $fileName = 'complaint_' . $complaint->id . '.pdf';

            // Save the PDF to storage
            $pdf->save(storage_path('app/public/reports/' . $fileName));
            $reportUrl = asset('storage/reports/' . $fileName);

            // Save the report URL to the complaint record
            $complaint->report_url = $reportUrl;
            $complaint->save();

            // Include the report URL in the response
            $response['report_url'] = $reportUrl;
        }

        // **Send email notification to the user regardless of 'go_home' status**
        if ($user) {
            Mail::to($user->email)->send(new ComplaintReceived($complaint));
        }

        // **Notify Parents if the role is 'student'**
        if (strtolower($complaint->role) === 'student') {
            // Fetch the student record
            $student = Student::where('id_number', $complaint->id_number)->first();

            if ($student) {
                // Fetch parents linked to the student
                $parents = Parents::where('student_id', $student->id_number)->get();

                foreach ($parents as $parent) {
                    // Fetch the parent user to get the email
                    $parentUser = User::where('id_number', $parent->id_number)->first();

                    if ($parentUser) {
                        // Create a notification for the parent
                        Notification::create([
                            'user_id' => $parentUser->id,
                            'title' => 'Student Complaint Submitted',
                            'message' => 'Your child has submitted a new complaint.',
                            'status' => 'unread'
                        ]);

                        // Send an email notification to the parent
                        Mail::to($parentUser->email)->send(new ParentComplaintNotification($complaint, $student));
                    }
                }
            }
        }

        // Prepare the response data with all necessary fields
        $response = [
            'success' => true,
            'message' => 'Complaint and notifications successfully saved',
            'complaint_id' => $complaint->id,
            'first_name' => $complaint->first_name,
            'last_name' => $complaint->last_name,
            'sickness_description' => $complaint->sickness_description,
            'pain_assessment' => $complaint->pain_assessment,
            'medicine_given' => $complaint->medicine_given,
            'report_url' => $complaint->report_url ?? null,
            'role' => $complaint->role // Include the role for dynamic table insertion
        ];

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
            'pdf_url' => $complaint->report_url ?? null, // Include the PDF URL
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
            
            $pdf->save(storage_path('app/public/reports/' . $fileName));
            $reportUrl = asset('storage/reports/' . $fileName);
            return response()->json(['success' => true, 'report_url' => $reportUrl]);
            

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
public function getStatistics()
{
    $user = Auth::user();
    $role = strtolower($user->role);

    if ($role === 'admin') {
        // **Admin**: Fetch **global** statistics across all complaints
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
    } else {
        // **Regular Users**: Fetch statistics **specific** to their `id_number`
        $idNumber = $user->id_number;

        $mostCommonComplaint = Complaint::where('id_number', $idNumber)
            ->select('sickness_description')
            ->groupBy('sickness_description')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->value('sickness_description');

        $commonComplaintCount = Complaint::where('id_number', $idNumber)
            ->where('sickness_description', $mostCommonComplaint)
            ->count();

        $mostUsedMedicine = Complaint::where('id_number', $idNumber)
            ->select('medicine_given')
            ->groupBy('medicine_given')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(1)
            ->value('medicine_given');

        $mostUsedMedicineCount = Complaint::where('id_number', $idNumber)
            ->where('medicine_given', $mostUsedMedicine)
            ->count();
    }

    return response()->json([
        'mostCommonComplaint' => $mostCommonComplaint ?? 'N/A',
        'commonComplaintCount' => $commonComplaintCount ?? 0,
        'mostUsedMedicine' => $mostUsedMedicine ?? 'N/A',
        'mostUsedMedicineCount' => $mostUsedMedicineCount ?? 0,
    ]);
}

}
