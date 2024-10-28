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

        // Fetch the parent record linked to the authenticated user
        $parent = Parents::where('id_number', $user->id_number)->first();

        if (!$parent) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all the students associated with this parent
        $students = $parent->student()->with(['information', 'medicalRecords', 'dentalRecords', 'healthExaminations'])->get();

        // Extract student IDs
        $studentIds = $students->pluck('id_number')->toArray();

        // Fetch appointments, complaints, and notifications for the associated students
        $appointments = Appointment::whereIn('id_number', $studentIds)->get();
        $appointmentCount = $appointments->count();

        $complaints = Complaint::whereIn('id_number', $studentIds)->get();
        $complaintCount = $complaints->count();

        $notifications = Notification::whereIn('user_id', $studentIds)->get();

        // Fetch health, dental, and medical records grouped by student_id
        $healthExaminations = HealthExamination::whereIn('id_number', $studentIds)->get()->groupBy('id_number');
        $dentalRecords = DentalRecord::whereIn('id_number', $studentIds)->get()->groupBy('id_number');
        $medicalRecords = MedicalRecord::whereIn('id_number', $studentIds)->get()->groupBy('id_number');

        // Fetch information records
        $informations = Information::whereIn('id_number', $studentIds)->get()->keyBy('id_number');

        // Initialize an array to hold status data
        $studentsStatus = [];

        foreach ($students as $student) {
            $id_number = $student->id_number;
            $studentsStatus[] = [
                'id_number' => $id_number,
                'name' => $student->first_name . ' ' . $student->last_name,
                'information_submitted' => $informations->has($id_number),
                'medical_record_submitted' => $medicalRecords->has($id_number),
                'medical_record_approved' => $medicalRecords->has($id_number) ? $medicalRecords[$id_number]->where('is_approved', true)->count() > 0 : false,
                'dental_record_submitted' => $dentalRecords->has($id_number),
                'health_examination_submitted' => $healthExaminations->has($id_number),
            ];
        }

        // Determine if any records exist
        $hasHealthExamination = $healthExaminations->isNotEmpty();
        $hasDentalRecord = $dentalRecords->isNotEmpty();
        $hasMedicalRecord = $medicalRecords->isNotEmpty();

        // Check if any student's profile information is complete
        $showModal = $informations->count() < $students->count(); // Show modal if any student lacks information

        // Pass all data to the view, including studentsStatus
        return view('parent.ParentDashboard', compact(
            'appointments',
            'appointmentCount',
            'complaints',
            'complaintCount',
            'showModal',
            'notifications',
            'hasHealthExamination',
            'hasDentalRecord',
            'hasMedicalRecord',
            'studentsStatus' // New data passed to the view
        ));
    }
}
