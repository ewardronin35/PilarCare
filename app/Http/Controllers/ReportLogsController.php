<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use App\Models\DentalRecord;
use App\Models\MedicalRecord;
use App\Models\User;

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

    // Fetch all logs for display in the logs table
    $appointmentLogs = Appointment::latest()->take(10)->get();
    $inventoryLogs = Inventory::latest()->take(10)->get();
    $complaintLogs = Complaint::latest()->take(10)->get();
    $dentalRecordLogs = DentalRecord::latest()->take(10)->get();
    $medicalRecordLogs = MedicalRecord::latest()->take(10)->get();
    $accountLogs = User::latest()->take(10)->get();  // Fetch latest user logs

    // Pass all the necessary data to the view
    return view('admin.report-logs', [
        'appointmentLogsCount' => $appointmentLogsCount,
        'complaintLogsCount' => $complaintLogsCount,
        'inventoryLogsCount' => $inventoryLogsCount,
        'dentalRecordLogsCount' => $dentalRecordLogsCount,
        'medicalRecordLogsCount' => $medicalRecordLogsCount,
        'accountLogsCount' => $accountLogsCount,  // Pass the account logs count
        'appointmentLogs' => $appointmentLogs,
        'inventoryLogs' => $inventoryLogs,
        'complaintLogs' => $complaintLogs,
        'dentalRecordLogs' => $dentalRecordLogs,
        'medicalRecordLogs' => $medicalRecordLogs,
        'accountLogs' => $accountLogs,  // Pass the account logs
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
