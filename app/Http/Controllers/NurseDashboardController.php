<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Nurse;
use App\Models\Notification;
use App\Models\HealthExamination;
use App\Models\Teeth;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;

class NurseDashboardController extends Controller
{
    public function index()
    {
        $appointmentCount = Appointment::count();
        $inventoryCount = Inventory::count();
        $complaintCount = Complaint::count();
        $pendingApprovalCount = HealthExamination::where('is_approved', false)->count();
        $dentalRecordCount = Teeth::count();
        $medicalRecordCount = MedicalRecord::count();
        
        // Fetch the Nurse's information
        $nurse = Nurse::where('id_number', Auth::user()->id_number)->first();
        
        // Handle the case where the nurse might not be found
        if ($nurse) {
            $nurseName = $nurse->name; // Adjust this if your Nurse model has first_name and last_name
        } else {
            $nurseName = 'Default Nurse Name'; // Or any default value you prefer
        }
    
        // Statistics for submissions by role (HealthExaminations, DentalRecords, MedicalRecords)
        $roles = ['Student', 'Teacher', 'Staff', 'Parent', 'Doctor', 'Nurse'];
        $submissionsPerRole = [];
    
        foreach ($roles as $role) {
            $healthExamCount = HealthExamination::whereHas('user', function($query) use ($role) {
                $query->where('role', $role);
            })->count();
    
            $dentalRecordCountRole = Teeth::whereHas('dentalRecord.user', function($query) use ($role) {
                $query->where('role', $role);
            })->count();
    
            $medicalRecordCountRole = MedicalRecord::whereHas('user', function($query) use ($role) {
                $query->where('role', $role);
            })->count();
    
            $submissionsPerRole[$role] = [
                'health_examinations' => $healthExamCount,
                'dental_records' => $dentalRecordCountRole,
                'medical_records' => $medicalRecordCountRole,
            ];
        }
    
        // Fetch low stock notifications
        $notifications = Notification::where('user_id', 'admin')->get();
    
        // Fetch all users by role
        $students = User::where('role', 'Student')->get();
        $staff = User::where('role', 'Staff')->get();
        $parents = User::where('role', 'Parent')->get();
        $teachers = User::where('role', 'Teacher')->get();
        $doctors = User::where('role', 'Doctor')->get();
        $nurses = User::where('role', 'Nurse')->get();
    
        return view('nurse.NurseDashboard', compact(
            'appointmentCount',
            'complaintCount',
            'inventoryCount',
            'pendingApprovalCount',
            'dentalRecordCount',
            'medicalRecordCount',
            'students',
            'staff',
            'parents',
            'teachers',
            // Pass the nurse's name to the view
            'nurseName'
        ));
    }
    
    
    public function pendingApprovals()
    {
        $pendingApprovals = HealthExamination::where('is_approved', false)->get();
        return view('admin.uploadHealthExamination', compact('pendingApprovals'));
    }
}
