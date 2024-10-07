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

        // Fetch all logs for display in the logs table
        $appointmentLogs = Appointment::with('user')->latest()->take(10)->get();
        $inventoryLogs = Inventory::latest()->take(10)->get();
        $complaintLogs = Complaint::with('user')->latest()->take(10)->get();
        $dentalRecordLogs = DentalRecord::with('user')->latest()->take(10)->get();
        $medicalRecordLogs = MedicalRecord::with('user')->latest()->take(10)->get();
        $accountLogs = User::latest()->take(10)->get();
        $loginLogs = LoginLog::with('user')->latest()->take(10)->get(); // New
        $registrationLogs = User::orderBy('created_at', 'desc')->take(10)->get();
        $physicalDentalExamLogs = PhysicalExamination::with('user')->latest()->take(10)->get(); // Add this

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
            'physicalDentalExamLogsCount' => $physicalDentalExamLogsCount, // Add this
            'appointmentLogs' => $appointmentLogs,
            'inventoryLogs' => $inventoryLogs,
            'complaintLogs' => $complaintLogs,
            'dentalRecordLogs' => $dentalRecordLogs,
            'medicalRecordLogs' => $medicalRecordLogs,
            'accountLogs' => $accountLogs,
            'loginLogs' => $loginLogs, // Pass the login logs
            'registrationLogs' => $registrationLogs, // Pass registration logs
            'physicalDentalExamLogs' => $physicalDentalExamLogs, // Add this
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

        // Logic to store the notification (You can create a Notification model for this)
        // Example (if you have a Notification model):
        /*
        Notification::create([
            'school_id' => $request->school_id,
            'name' => $request->name,
            'notification_info' => $request->notification_info,
            'notification_for' => $request->notification_for,
        ]);
        */

        return back()->with('success', 'Notification sent successfully!');
    }
}
