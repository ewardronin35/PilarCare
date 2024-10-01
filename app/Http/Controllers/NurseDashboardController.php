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
            // Fetch pending approvals for health examinations, dental records, and medical records
    $pendingHealthExams = HealthExamination::where('is_approved', false)->count();
    $pendingDentalApprovals = Teeth::where('is_approved', false)->count();
    $pendingMedicalApprovals = MedicalRecord::where('is_approved', false)->count();
        // Fetch low stock notifications
        $notifications = Notification::where('user_id', 'admin')->get();
    
        // Fetch all users by role
        $students = User::where('role', 'Student')->get();
        $staff = User::where('role', 'Staff')->get();
        $parents = User::where('role', 'Parent')->get();
        $teachers = User::where('role', 'Teacher')->get();
        $doctors = User::where('role', 'Doctor')->get();
        $nurses = User::where('role', 'Nurse')->get();
    
        // Prepare data for the chart
        $roles = ['Student', 'Teacher', 'Staff', 'Parent', 'Doctor', 'Nurse'];
        $monthlyUserData = [];
    
        foreach ($roles as $role) {
            $monthlyCounts = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->where('role', $role)
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')->toArray();
    
            // Initialize counts for all months
            $counts = [];
            for ($i = 1; $i <= 12; $i++) {
                $counts[] = isset($monthlyCounts[$i]) ? $monthlyCounts[$i] : 0;
            }
            $monthlyUserData[$role] = $counts;
        }
    
        return view('nurse.NurseDashboard', compact(
            'appointmentCount',
            'complaintCount',
            'inventoryCount',
            'pendingApprovalCount',
            'students',
            'staff',
            'parents',
            'dentalRecordCount',
            'medicalRecordCount',
            'teachers',
            'doctors',
            'nurses',
            'monthlyUserData'
        ));
    }
    
    public function pendingApprovals()
    {
        $pendingApprovals = HealthExamination::where('is_approved', false)->get();
        return view('admin.uploadHealthExamination', compact('pendingApprovals'));
    }
}
