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
use App\Mail\ParentGoHomeNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;


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
        if ($role === 'parent') {
            // Fetch the parent record(s)
            $parentRecords = Parents::where('id_number', $idNumber)->get();
    
            // Collect the child id_numbers
            $childIdNumbers = $parentRecords->pluck('student_id');
    
            // Initialize the query with eager loading
            $query = Complaint::with('user')->whereIn('id_number', $childIdNumbers);
        } else {
            // For other roles, use the user's own id_number
            $query = Complaint::with('user')->where('id_number', $idNumber);
        }
    
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
    
        // Fetch complaints with pagination (10 per page)
        $complaints = $query->orderBy('created_at', 'desc')->paginate(10);
    
        // Fetch the top 3 most common complaints and top 3 most used medicines
        if ($role === 'parent') {
            // For parents, consider children's complaints
            $topComplaints = Complaint::whereIn('id_number', $childIdNumbers)
                ->select('sickness_description as complaint', \DB::raw('count(*) as count'))
                ->groupBy('sickness_description')
                ->orderByDesc('count')
                ->limit(3)
                ->get();
    
            $topMedicines = Complaint::whereIn('id_number', $childIdNumbers)
                ->select('medicine_given as medicine', \DB::raw('count(*) as count'))
                ->whereNotNull('medicine_given')
                ->groupBy('medicine_given')
                ->orderByDesc('count')
                ->limit(3)
                ->get();
        } else {
            // For other roles, consider their own complaints
            $topComplaints = Complaint::where('id_number', $idNumber)
                ->select('sickness_description as complaint', \DB::raw('count(*) as count'))
                ->groupBy('sickness_description')
                ->orderByDesc('count')
                ->limit(3)
                ->get();
    
            $topMedicines = Complaint::where('id_number', $idNumber)
                ->select('medicine_given as medicine', \DB::raw('count(*) as count'))
                ->whereNotNull('medicine_given')
                ->groupBy('medicine_given')
                ->orderByDesc('count')
                ->limit(3)
                ->get();
        }
    
        // Pass the data to the appropriate view based on role
        switch ($role) {
            case 'student':
            case 'parent':
            case 'teacher':
            case 'staff':
                return view("$role.complaint", compact(
                    'complaints', 
                    'topComplaints', 
                    'topMedicines'
                ));
    
            case 'admin':
            case 'nurse':
            case 'doctor':
                // Fetch complaints per role with eager loading
                $studentComplaints = Complaint::with('user')->where('role', 'student')->get();
                $staffComplaints = Complaint::with('user')->where('role', 'staff')->get();
                $parentComplaints = Complaint::with('user')->where('role', 'parent')->get();
                $teacherComplaints = Complaint::with('user')->where('role', 'teacher')->get();
    
                // Fetch top 3 across all roles
                $topComplaints = Complaint::select('sickness_description as complaint', \DB::raw('count(*) as count'))
                    ->groupBy('sickness_description')
                    ->orderByDesc('count')
                    ->limit(3)
                    ->get();
    
                $topMedicines = Complaint::select('medicine_given as medicine', \DB::raw('count(*) as count'))
                    ->whereNotNull('medicine_given')
                    ->groupBy('medicine_given')
                    ->orderByDesc('count')
                    ->limit(3)
                    ->get();
    
                // Pass each role's complaints as separate variables along with top 3 statistics
                return view("{$role}.complaint", compact(
                    'studentComplaints', 
                    'staffComplaints', 
                    'parentComplaints', 
                    'teacherComplaints', 
                    'topComplaints', 
                    'topMedicines'
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
            'pain_assessment' => 'required|integer|min:1|max:10',
            'sickness_description' => 'required|string|max:1000',
            'role' => 'required|string|max:255',
            'medicine_given' => 'required|string|max:255',
            'go_home' => 'required|string|in:yes,no',
            'grade_course' => 'nullable|string|max:255', // Optional, based on role
            'section' => 'nullable|string|max:255',       // Optional, based on role
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
                'pain_assessment' => $request->pain_assessment,
                'sickness_description' => $request->sickness_description,
                'role' => $request->role,
                'medicine_given' => $request->medicine_given,
                'go_home' => $request->go_home,
                'grade_course' => $request->grade_course, // New field
                'section' => $request->section,           // New field
            ]);
    
            // Reduce inventory quantity
            $inventory = Inventory::where('item_name', $request->medicine_given)->first();
            if ($inventory) {
                if ($inventory->quantity < 1) {
                    return response()->json(['success' => false, 'message' => 'Medicine inventory is insufficient'], 400);
                }
                $inventory->quantity -= 1;
                $inventory->save();
            } else {
                return response()->json(['success' => false, 'message' => 'Medicine not found in inventory'], 404);
            }
    
            // Send notification to the user
            $user = User::where('id_number', $request->id_number)->first();
            if ($user) {
                Notification::create([
                    'user_id' => $user->id_number, // Assuming 'user_id' references 'id' not 'id_number'
                    'title' => 'Complaint Received',
                    'message' => 'You have a new complaint added',
                    'status' => 'unread'
                ]);
            }
    
            \Log::info('Complaint and notification successfully saved:', [
                'complaint' => $complaint->toArray(),
                'user_id' => $user->id ?? 'N/A'
            ]);
    
            // Generate PDF if go_home is "yes"
            if ($complaint->go_home == 'yes') {
                $student = Student::where('id_number', $complaint->id_number)->first();
                if ($student) {
                    $data = [
                        'date' => now()->format('Y-m-d'),
                        'name' => $complaint->first_name . ' ' . $complaint->last_name,
                        'sickness_description' => $complaint->sickness_description,
                        'pain_assessment' => $complaint->pain_assessment,
                        'medicine_given' => $complaint->medicine_given,
                        'logoBase64' => base64_encode(file_get_contents(public_path('images/pilarLogo.png'))),
                        'complaint' => $complaint,
                        'role' => $complaint->role,
                        'grade_course' => $complaint->grade_or_course,
                        'section' => $complaint->section,
                    ];
            
                    // Fetch additional data based on role
                    if ($complaint->role == 'Student') {
                        $data['grade'] = $student->grade_or_course;
                        $data['section'] = $student->section;
                    } elseif ($complaint->role == 'Staff') {
                        $staff = Staff::where('id_number', $complaint->id_number)->first();
                        if ($staff) {
                            $data['position'] = $staff->position;
                        }
                    } elseif ($complaint->role == 'Teacher') {
                        $teacher = Teacher::where('id_number', $complaint->id_number)->first();
                        if ($teacher) {
                            $data['bed_or_hed'] = $teacher->course;
                        }
                    }
            
                    // Load the Blade view and pass the data
                    $pdf = PDF::loadView('pdf.single_complaint_report', $data);
            
                    // Define the file name using complaint ID for uniqueness
                    $fileName = 'complaint_' . $complaint->id . '.pdf';
            
                    // Save the PDF to storage
                    $pdf->save(storage_path('app/public/reports/' . $fileName));
                    $reportUrl = route('reports.download', ['filename' => $fileName]);
            
                    // Save the report URL to the complaint record
                    $complaint->report_url = $reportUrl;
                    $complaint->save();
            
                    // Include the report URL in the response
                    $response['report_url'] = $reportUrl;
                }
            }
            // **Send email notification to the user regardless of 'go_home' status**
            if ($user) {
                Mail::to($user->email)->send(new ComplaintReceived($complaint));
            }
    
            // **Notify Parents if the role is 'student'**
           
    
            // **Additional Notification and Email for 'go_home' == 'yes'**
            if ($complaint->go_home == 'yes' && strtolower($complaint->role) === 'student') {
                // Fetch the student record
                $student = Student::where('id_number', $complaint->id_number)->first();
    
              
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
                'role' => $complaint->role, // Include the role for dynamic table insertion
                'go_home' => $complaint->go_home, // Include the go_home field for dynamic table insertion
                'grade_course' => $complaint->grade_course, // Include the grade_course field for dynamic table insertion
                'section' => $complaint->section, // Include the section field for dynamic table insertion
                'created_at' => $complaint->created_at->toIso8601String(), // **Add this line**

            ];
    
            return response()->json($response);
    
        } catch (\Exception $e) {
            \Log::error('Error while saving complaint:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the complaint. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

      
public function show($id)
{
    $user = Auth::user();
    $role = strtolower($user->role);

    $complaint = Complaint::with('doctor.user')->findOrFail($id);

    if ($role === 'parent') {
        // Fetch the parent's children
        $childIdNumbers = Parents::where('id_number', $user->id_number)->pluck('student_id');

        // Check if the complaint belongs to one of the children
        if (!$childIdNumbers->contains($complaint->id_number)) {
            abort(403, 'Unauthorized action.');
        }
    } else {
        // Implement additional role-based access controls if necessary
        // For example, doctors might access all complaints, etc.
    }

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
            // Fetch the user from the users table to get the role
            $user = User::where('id_number', $id)->first();
    
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
    
            $role = strtolower($user->role);
    
            // Initialize response data
            $responseData = [
                'first_name' => null,
                'last_name' => null,
                'role' => ucfirst($role),
                'id_number' => $user->id_number,
                'grade_course' => null,
                'section' => null,
            ];
    
            // Fetch data from role-specific tables
            if ($role === 'student') {
                $student = Student::where('id_number', $id)->first();
                if ($student) {
                    $responseData['first_name'] = $student->first_name;
                    $responseData['last_name'] = $student->last_name;
                    $responseData['grade_course'] = $student->grade_or_course ?? null;
                    $responseData['section'] = $student->section ?? null;
                }
            } elseif ($role === 'teacher') {
                $teacher = Teacher::where('id_number', $id)->first();
                if ($teacher) {
                    $responseData['first_name'] = $teacher->first_name;
                    $responseData['last_name'] = $teacher->last_name;
                    $responseData['grade_course'] = $teacher->course ?? null;
                    $responseData['section'] = $teacher->section ?? null;
                }
            } elseif ($role === 'staff') {
                $staff = Staff::where('id_number', $id)->first();
                if ($staff) {
                    $responseData['first_name'] = $staff->first_name;
                    $responseData['last_name'] = $staff->last_name;
                    $responseData['grade_course'] = $staff->department ?? null;
                    $responseData['section'] = $staff->section ?? null;
                }
            } else {
                // If role is not student, teacher, or staff, use the data from the users table
                $responseData['first_name'] = $user->first_name;
                $responseData['last_name'] = $user->last_name;
            }
    
            return response()->json($responseData, 200);
    
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
    
        // Validate the request inputs for report_period and report_date
        $request->validate([
            'report_period' => 'required|in:daily,weekly,monthly',
            'report_date' => 'required|date',
        ]);
    
        $report_period = ucfirst($request->input('report_period')); // Capitalize first letter
        $report_date = $request->input('report_date');
    
        try {
            // Fetch complaints based on role
            $complaints = Complaint::where('role', strtolower($role))->get();
    
            if ($complaints->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No complaints found for this role.'], 404);
            }
    
            // Fetch the most common complaint and its count
            $mostCommonComplaint = Complaint::select('sickness_description')
                ->groupBy('sickness_description')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->value('sickness_description');
    
            $commonComplaintCount = Complaint::where('sickness_description', $mostCommonComplaint)
                ->count();
    
            // Fetch the most used medicine and its count
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
                'report_period' => $report_period,
                'report_date' => $report_date,
            ];
    
            // Load the correct Blade view and pass the data
            $pdf = PDF::loadView('pdf.complaint_statistics_report', $data);
    
            // Define the file name
            $fileName = 'complaint_statistics_report_' . strtolower($role) . '_' . now()->timestamp . '.pdf';
    
            // Store the PDF and provide a link
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

    if (in_array($role, ['admin', 'nurse', 'doctor'])) {
        // **Admin**: Fetch **global** top 3 statistics across all complaints
        $topComplaints = Complaint::select('sickness_description as complaint', \DB::raw('count(*) as count'))
            ->groupBy('sickness_description')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        $topMedicines = Complaint::select('medicine_given as medicine', \DB::raw('count(*) as count'))
            ->whereNotNull('medicine_given')
            ->groupBy('medicine_given')
            ->orderByDesc('count')
            ->limit(3)
            ->get();
    } else {
        // **Regular Users**: Fetch statistics **specific** to their `id_number`
        $idNumber = $user->id_number;

        $topComplaints = Complaint::where('id_number', $idNumber)
            ->select('sickness_description as complaint', \DB::raw('count(*) as count'))
            ->groupBy('sickness_description')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        $topMedicines = Complaint::where('id_number', $idNumber)
            ->select('medicine_given as medicine', \DB::raw('count(*) as count'))
            ->whereNotNull('medicine_given')
            ->groupBy('medicine_given')
            ->orderByDesc('count')
            ->limit(3)
            ->get();
    }

    return response()->json([
        'topComplaints' => $topComplaints,
        'topMedicines' => $topMedicines,
    ]);
}

public function downloadPdf($id)
{
    $user = Auth::user();
    $role = strtolower($user->role);

    $complaint = Complaint::findOrFail($id);

    if ($role === 'parent') {
        // Fetch the parent's children
        $childIdNumbers = Parents::where('id_number', $user->id_number)->pluck('student_id');

        // Check if the complaint belongs to one of the children
        if (!$childIdNumbers->contains($complaint->id_number)) {
            abort(403, 'Unauthorized action.');
        }
    } else {
        // Implement additional role-based access controls if necessary
    }

    if (!$complaint->report_url) {
        return response()->json(['error' => 'PDF not available for this complaint.'], 404);
    }

    // Extract the file path from the report_url
    $filePath = str_replace(asset(''), '', $complaint->report_url);
    $fullPath = public_path($filePath);

    if (!File::exists($fullPath)) {
        return response()->json(['error' => 'PDF file not found.'], 404);
    }

    return response()->download($fullPath, basename($fullPath));
}
public function generateComplaintStatisticsReport(Request $request)
{
    // Validate the incoming request parameters
    $validator = Validator::make($request->all(), [
        'report_period' => 'required|in:daily,weekly,monthly',
        'report_date' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid input data.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $reportPeriod = $request->input('report_period');
    $reportDate = Carbon::parse($request->input('report_date'));

    // Determine the date range based on the selected period
    switch ($reportPeriod) {
        case 'daily':
            $startDate = $reportDate->copy()->startOfDay();
            $endDate = $reportDate->copy()->endOfDay();
            $periodLabel = 'Daily';
            break;

        case 'weekly':
            // Assuming week starts on Monday
            $startDate = $reportDate->copy()->startOfWeek();
            $endDate = $reportDate->copy()->endOfWeek();
            $periodLabel = 'Weekly';
            break;

        case 'monthly':
            $startDate = $reportDate->copy()->startOfMonth();
            $endDate = $reportDate->copy()->endOfMonth();
            $periodLabel = 'Monthly';
            break;

        default:
            return response()->json([
                'success' => false,
                'message' => 'Invalid report period selected.',
            ], 400);
    }

    try {
        // Fetch complaints within the date range based on 'created_at'
        $complaints = Complaint::whereBetween('created_at', [$startDate, $endDate])->get();

        if ($complaints->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No complaints found for the selected period.',
            ], 404);
        }

        // Determine the top 3 used medicines in the selected period
        $topMedicines = $complaints->groupBy('medicine_given')
            ->map(function ($group) {
                return count($group);
            })
            ->sortDesc()
            ->slice(0, 3)
            ->all();

        // Determine the top 3 complaints
        $topComplaints = $complaints->groupBy('sickness_description')
            ->map(function ($group) {
                return count($group);
            })
            ->sortDesc()
            ->slice(0, 3)
            ->all();

        // Prepare data for the PDF
        $data = [
            'report_period' => $periodLabel,
            'report_date' => $reportDate->format('Y-m-d'),
            'complaints' => $complaints,
            'topMedicines' => $topMedicines,
            'topComplaints' => $topComplaints,
            'logoBase64' => file_exists(public_path('images/pilarLogo.png')) ? base64_encode(file_get_contents(public_path('images/pilarLogo.png'))) : null,
        ];

        // Log the report generation details
        \Log::info('Generating Complaint Statistics Report', [
            'report_period' => $reportPeriod,
            'report_date' => $reportDate->toDateString(),
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_complaints' => $complaints->count(),
            'top_complaints' => $topComplaints,
            'top_medicines' => $topMedicines,
        ]);

        // Generate PDF using Blade view
        $pdf = PDF::loadView('pdf.complaint_statistics_report', $data);

        // Create a unique filename using timestamp and a unique identifier
        $fileName = 'complaints_report_' . strtolower($periodLabel) . '_' . $reportDate->format('Ymd') . '_' . uniqid() . '.pdf';
        $pdfDirectory = 'reports'; // Define a directory within the public disk
        $pdfPath = "{$pdfDirectory}/{$fileName}"; // Relative path within storage/app/public

        // Ensure the directory exists
        Storage::disk('public')->makeDirectory($pdfDirectory);

        // Save the PDF file
        Storage::disk('public')->put($pdfPath, $pdf->output());
        \Log::info("Complaint Statistics Report generated and saved to {$pdfPath}");

        // Generate the report URL
        $reportUrl = route('reports.download', ['filename' => $fileName]);

        // Return the report URL in the response
        return response()->json([
            'success' => true,
            'report_url' => $reportUrl,
            'message' => 'Report generated successfully.',
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error generating PDF report:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'An error occurred while generating the report.'], 500);
    }
}
public function getPredictions()
{
    $user = Auth::user();
    $role = strtolower($user->role);
    $idNumber = $user->id_number;

    // Initialize query based on role
    if ($role === 'parent') {
        // Fetch the parent's children
        $childIdNumbers = Parents::where('id_number', $idNumber)->pluck('student_id');

        // Initialize the query for children's complaints
        $complaintsQuery = Complaint::whereIn('id_number', $childIdNumbers);
    } elseif (in_array($role, ['admin', 'nurse', 'doctor'])) {
        // For admin, nurse, and doctor roles, fetch all complaints
        $complaintsQuery = Complaint::query();
    } else {
        // For other roles, fetch their own complaints
        $complaintsQuery = Complaint::where('id_number', $idNumber);
    }

    // Fetch all relevant complaints
    $complaints = $complaintsQuery->get();

    // Next Likely Complaint Type Prediction
    $nextComplaint = $this->predictNextComplaintType($complaints);

    // Most Likely Medicine Prediction
    $likelyMedicine = $this->predictMostLikelyMedicine($complaints);

    return response()->json([
        'success' => true,
        'next_complaint' => $nextComplaint,
        'likely_medicine' => $likelyMedicine,
    ], 200);
}


/**
 * Predict the next likely complaint type based on frequency.
 *
 * @param \Illuminate\Support\Collection $complaints
 * @return string
 */
private function predictNextComplaintType($complaints)
{
    // Group complaints by type and count
    $complaintCounts = $complaints->groupBy('sickness_description')->map->count();

    // Sort in descending order
    $sortedComplaints = $complaintCounts->sortDesc();

    // Get the highest count
    $maxCount = $sortedComplaints->first();

    // Get all complaints with the highest count
    $topComplaints = $sortedComplaints->filter(function ($count) use ($maxCount) {
        return $count === $maxCount;
    })->keys();

    return $topComplaints->implode(', ') ?: 'N/A';
}


/**
 * Predict the most likely medicine to be used based on frequency.
 *
 * @param \Illuminate\Support\Collection $complaints
 * @return string
 */
private function predictMostLikelyMedicine($complaints)
{
    // Group medicines by name and count
    $medicineCounts = $complaints->groupBy('medicine_given')->map->count();

    // Sort in descending order
    $sortedMedicines = $medicineCounts->sortDesc();

    // Get the highest count
    $maxCount = $sortedMedicines->first();

    // Get all medicines with the highest count
    $topMedicines = $sortedMedicines->filter(function ($count) use ($maxCount) {
        return $count === $maxCount;
    })->keys();

    return $topMedicines->implode(', ') ?: 'N/A';
}
public function downloadReport($filename)
{
    // Sanitize the filename to prevent directory traversal
    $filename = basename($filename);

    $filePath = storage_path('app/public/reports/' . $filename);

    if (!File::exists($filePath)) {
        abort(404, 'Report not found.');
    }

    // Optionally, add authorization checks here
    return Response::download($filePath, $filename, [
        'Content-Type' => 'application/pdf',
    ]);
}

}
