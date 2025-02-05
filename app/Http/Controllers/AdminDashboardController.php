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
use App\Models\Admin;
use App\Models\SchoolYear;
use App\Models\MedicalRecord;
use App\Models\DentalRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\Doctor;
use App\Models\Nurse;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch Admin Information
        $admin = Admin::where('id_number', Auth::user()->id_number)->first();
        $adminName = $admin ? $admin->name : 'Default Admin Name';

        // Count Statistics
        $appointmentCount = Appointment::count();
        $inventoryCount = Inventory::count();
        $complaintCount = Complaint::count();
        $pendingApprovalCount = HealthExamination::where('is_approved', false)->count();
        $dentalRecordCount = DentalRecord::count();
        $medicalRecordCount = MedicalRecord::count();

        // Notifications
        $notifications = Notification::where('user_id', 'admin')->get();

        // School Years
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year');

        // Fetch Role-Specific Details with Eager Loaded User Emails
        // Only fetch records that have a corresponding User
        $students = Student::with('user')
            ->whereHas('user')
            ->select('id_number', 'first_name', 'last_name')
            ->get();

        $staff = Staff::with('user')
            ->whereHas('user')
            ->select('id_number', 'first_name', 'last_name')
            ->get();

       

        $teachers = Teacher::with('user')
            ->whereHas('user')
            ->select('id_number', 'first_name', 'last_name')
            ->get();

        $doctors = Doctor::with('user')
            ->whereHas('user')
            ->select('id_number', 'first_name', 'last_name')
            ->get();

        $nurses = Nurse::with('user')
            ->whereHas('user')
            ->select('id_number', 'first_name', 'last_name')
            ->get();

        // Prepare Data for the Chart
        $roles = ['Student', 'Teacher', 'Staff',  'Doctor', 'Nurse'];
        $monthlyUserData = [];
        foreach ($roles as $role) {
            $monthlyCounts = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->where('role', $role)
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray();

            $counts = [];
            for ($i = 1; $i <= 12; $i++) {
                $counts[] = $monthlyCounts[$i] ?? 0;
            }
            $monthlyUserData[$role] = $counts;
        }

        return view('admin.AdminDashboard', compact(
            'appointmentCount',
            'complaintCount',
            'inventoryCount',
            'pendingApprovalCount',
            'students',
            'staff',
            'dentalRecordCount',
            'medicalRecordCount',
            'teachers',
            'doctors',
            'nurses',
            'monthlyUserData',
            'admin',
            'adminName',
            'schoolYears'
        ));
    }
}
