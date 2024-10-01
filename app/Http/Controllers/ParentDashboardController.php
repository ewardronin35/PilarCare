<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\MedicalRecord;
use App\Models\DentalRecord;
use App\Models\Information;
use App\Models\Parents;
use App\Models\Notification;
use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
    // Fetch all the students associated with this parent
    $studentIds = $user->students->pluck('id_number')->toArray();

    // Fetch appointments, complaints, and records for the associated students
    $appointments = Appointment::whereIn('id_number', $studentIds)->get();
    $appointmentCount = $appointments->count();

    $complaints = Complaint::whereIn('id_number', $studentIds)->get();
    $complaintCount = $complaints->count();

    $notifications = Notification::whereIn('user_id', $studentIds)->get();

    // Check if any student's profile information is complete
    $information = Information::whereIn('id_number', $studentIds)->first();
    $showModal = !$information; // Show modal if no information exists

    // Check if any student's health, dental, and medical records exist
    $healthExaminations = HealthExamination::whereIn('id_number', $studentIds)->get();
    $hasHealthExamination = $healthExaminations->isNotEmpty(); // True if health records exist

    $dentalRecords = DentalRecord::whereIn('id_number', $studentIds)->get();
    $hasDentalRecord = $dentalRecords->isNotEmpty(); // True if dental records exist

    $medicalRecords = MedicalRecord::whereIn('id_number', $studentIds)->get();
    $hasMedicalRecord = $medicalRecords->isNotEmpty(); // True if medical records exist

    // Pass all data to the view
    return view('parent.ParentDashboard', compact(
        'appointments',
        'appointmentCount',
        'complaints',
        'complaintCount',
        'showModal',
        'notifications',
        'hasHealthExamination',
        'hasDentalRecord',
        'hasMedicalRecord'
    ));
}
}