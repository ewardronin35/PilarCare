<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use App\Models\User;
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

        // Fetch Complaints by Status
        $complaintsByConfineStatus = Complaint::select('confine_status', \DB::raw('count(*) as total'))
        ->groupBy('confine_status')
        ->get()
        ->pluck('total', 'confine_status')
        ->toArray();

// 3. Complaints by Go Home
$complaintsByGoHome = Complaint::select('go_home', \DB::raw('count(*) as total'))
  ->groupBy('go_home')
  ->get()
  ->pluck('total', 'go_home')
  ->toArray();

        // Fetch Inventory by Category
        // Assuming your Inventory model has a 'category' field
        $inventoryByCategory = Inventory::select('type', \DB::raw('count(*) as total'))
                                        ->groupBy('type')
                                        ->get()
                                        ->pluck('total', 'type')
                                        ->toArray();
    
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
            'doctors',
            'nurses',
            'submissionsPerRole',
            'complaintsByConfineStatus',
            'complaintsByGoHome',
            'inventoryByCategory' // Added variables
        ));
    }

    
    public function pendingApprovals()
    {
        $pendingApprovals = HealthExamination::where('is_approved', false)->get();
        return view('admin.uploadHealthExamination', compact('pendingApprovals'));
    }
}
