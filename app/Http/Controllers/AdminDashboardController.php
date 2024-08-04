<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Notification;
use App\Models\HealthExamination;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $appointmentCount = Appointment::count();
        $inventoryCount = Inventory::count();
        $complaintCount = Complaint::count();
        $pendingApprovalCount = HealthExamination::where('is_approved', false)->count();
        
        // Fetch low stock notifications
        $notifications = Notification::where('user_id', 'admin')->get();
        
        // Fetch all users
        $students = User::where('role', 'student')->get();
        $staff = User::where('role', 'staff')->get();
        $parents = User::where('role', 'parent')->get();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.AdminDashboard', compact('appointmentCount', 'complaintCount', 'inventoryCount', 'pendingApprovalCount', 'students', 'staff', 'parents', 'teachers'));
    }

    public function pendingApprovals()
    {
        $pendingApprovals = HealthExamination::where('is_approved', false)->get();
        return view('admin.pending-approvals', compact('pendingApprovals'));
    }
}
