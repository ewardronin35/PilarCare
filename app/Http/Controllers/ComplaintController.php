<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Student;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Parents;
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
        $complaints = Complaint::where('role', $role)->get();

    foreach ($complaints as $complaint) {
        $user = User::where('id_number', $complaint->id_number)->first();
        if ($user) {
            $complaint->first_name = $user->first_name;
            $complaint->last_name = $user->last_name;
        }
    }
    
        switch ($role) {
            case 'student':
                return view('student.complaint', compact('complaints', 'role'));
            case 'parent':
                return view('parent.complaint', compact('complaints', 'role'));
            case 'teacher':
                return view('teacher.complaint', compact('complaints', 'role'));
            case 'staff':
                return view('staff.complaint', compact('complaints', 'role'));
            case 'admin':
                $studentComplaints = Complaint::where('role', 'student')->get();
                $staffComplaints = Complaint::where('role', 'staff')->get();
                $parentComplaints = Complaint::where('role', 'parent')->get();
                $teacherComplaints = Complaint::where('role', 'teacher')->get();
                return view('admin.complaint', compact('studentComplaints', 'staffComplaints', 'parentComplaints', 'teacherComplaints'));
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
            'medicine_given' => 'required|string|max:255'
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
                'medicine_given' => $request->medicine_given
            ]);    
    
            // Check if the medicine exists in the inventory and reduce quantity
            $inventory = Inventory::where('item_name', $request->medicine_given)->first();
            if ($inventory) {
                $inventory->quantity -= 1;
                $inventory->save();
            } else {
                return response()->json(['success' => false, 'message' => 'Medicine not found in inventory'], 404);
            }
    
            return response()->json(['success' => true, 'message' => 'Complaint successfully saved']);
            \Log::info('Complaint successfully saved:', $complaint->toArray());  
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while saving the complaint. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }
    
    

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Completed,Not yet done',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $complaint->status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $complaint = Complaint::find($id);

            if ($complaint) {
                return response()->json($complaint);
            } else {
                return response()->json(['error' => 'Complaint not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
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
