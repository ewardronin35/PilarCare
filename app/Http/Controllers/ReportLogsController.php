<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use App\Models\DentalRecord;
use App\Models\MedicalRecord;
use App\Models\User;
use App\Models\LoginLog;
use App\Models\PhysicalExamination;

class ReportLogsController extends Controller
{
    public function index()
{
    // Fetch log counts for statistics
    $appointmentLogsCount = Appointment::count();
    $complaintLogsCount = Complaint::count();
    $inventoryLogsCount = Inventory::count();
    $dentalRecordLogsCount = DentalRecord::count();
    $medicalRecordLogsCount = MedicalRecord::count();
    $accountLogsCount = User::count();  // Define the account logs count
    $loginLogsCount = LoginLog::count();
    $registrationLogsCount = User::count(); // Total number of registrations (users)
    $physicalDentalExamLogsCount = PhysicalExamination::count(); // Add this

    // Fetch all logs for display in the logs table, ensuring they have associated users
    $appointmentLogs = Appointment::with('user')->whereHas('user')->latest()->paginate(10, ['*'], 'appointmentPage');
    $complaintLogs = Complaint::with('user')->whereHas('user')->latest()->paginate(10, ['*'], 'complaintPage');
    $dentalRecordLogs = DentalRecord::with('user')->whereHas('user')->latest()->paginate(10, ['*'], 'dentalRecordPage');
    $medicalRecordLogs = MedicalRecord::with('user')->whereHas('user')->latest()->paginate(10, ['*'], 'medicalRecordPage');
    $loginLogs = LoginLog::with('user')->whereHas('user')->latest()->paginate(10, ['*'], 'loginPage');
    $physicalDentalExamLogs = PhysicalExamination::with('user')->whereHas('user')->latest()->paginate(10, ['*'], 'physicalDentalExamPage');

    // Fetch registration logs directly from User model with pagination
    $registrationLogs = User::orderBy('created_at', 'desc')->paginate(10, ['*'], 'registrationPage');

    // Pass all the necessary data to the view
    return view('admin.report-logs', [
        'appointmentLogsCount' => $appointmentLogsCount,
        'complaintLogsCount' => $complaintLogsCount,
        'inventoryLogsCount' => $inventoryLogsCount,
        'dentalRecordLogsCount' => $dentalRecordLogsCount,
        'medicalRecordLogsCount' => $medicalRecordLogsCount,
        'accountLogsCount' => $accountLogsCount,
        'loginLogsCount' => $loginLogsCount,
        'registrationLogsCount' => $registrationLogsCount,
        'physicalDentalExamLogsCount' => $physicalDentalExamLogsCount,
        'appointmentLogs' => $appointmentLogs,
        'complaintLogs' => $complaintLogs,
        'dentalRecordLogs' => $dentalRecordLogs,
        'medicalRecordLogs' => $medicalRecordLogs,
        'loginLogs' => $loginLogs,
        'physicalDentalExamLogs' => $physicalDentalExamLogs,
        'registrationLogs' => $registrationLogs,
    ]);
}


    // Handle notification storage
    public function storeNotification(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'school_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'notification_info' => 'required|string|max:255',
            'notification_for' => 'required|in:student,parent,staff',
        ]);


        Notification::create([
            'school_id' => $request->school_id,
            'name' => $request->name,
            'notification_info' => $request->notification_info,
            'notification_for' => $request->notification_for,
        ]);
    

        return back()->with('success', 'Notification sent successfully!');
    }
}
