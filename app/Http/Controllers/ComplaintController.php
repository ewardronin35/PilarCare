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
            

        default:
            abort(403, 'Unauthorized action.');
    }
}

    
    
    public function addComplaint()
    {
        $role = strtolower(Auth::user()->role);
        return view('admin.addcomplaint', compact('role'));
    }

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
            'confine_status' => 'required|string|in:confined,not_confined',  // New validation rule for confine_status
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
                'confine_status' => $request->confine_status // New field for confine status
            ]);    
    
            // Check if the medicine exists in the inventory and reduce quantity
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
                // Assuming you have a Notification model
                Notification::create([
                    'user_id' => $user->id_number, // Use the 'id_number' field if that's the foreign key
                    'title' => 'Complaint Received',
                    'message' => 'You have a new complaint added',
                    'status' => 'unread'
                ]);
            }
    
            \Log::info('Complaint and notification successfully saved:', ['complaint' => $complaint->toArray(), 'user_id' => $user->id]);
    
            return response()->json(['success' => true, 'message' => 'Complaint and notification successfully saved']);
    
        } catch (\Exception $e) {
            \Log::error('Error while saving complaint:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while saving the complaint. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    
    public function show($id)
    {
        // Fetch the complaint based only on the ID
        $complaint = Complaint::findOrFail($id);
    
        // Return the complaint data as JSON
        return response()->json($complaint);
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
}
