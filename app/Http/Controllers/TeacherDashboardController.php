<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\SchoolYear;
use App\Models\MedicalRecord;
use App\Models\DentalRecord;
use App\Models\Information;
use App\Models\Parents;
use App\Models\Notification;
use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Retrieve the staff’s assigned grade/course and section (adjust if your staff model uses different attribute names)
        $gradeOrCourse = optional($user->teacher)->grade_or_course;
        $section = optional($user->teacher)->section;
        
        // Filter appointments by grade/course and section if available.
        $appointments = Appointment::when($gradeOrCourse, function($query) use ($gradeOrCourse) {
                return $query->where('grade_or_course', $gradeOrCourse);
            })
            ->when($section, function($query) use ($section) {
                return $query->where('section', $section);
            })
            ->get();
        $appointmentCount = $appointments->count();
    
        $complaints = Complaint::where('id_number', $user->id_number)->get();
        $complaintCount = $complaints->count();
        $notifications = Notification::where('user_id', $user->id_number)->get();
    
        // Fetch the current school year
        $currentSchoolYear = SchoolYear::where('is_current', true)->first();
    
        // Fetch health examination for the current school year
        $healthExamination = null;
        $hasHealthExamination = false;
        if ($currentSchoolYear) {
            $healthExamination = HealthExamination::where('id_number', $user->id_number)
                ->where('school_year', $currentSchoolYear->year)
                ->first();
            $hasHealthExamination = $healthExamination !== null;
        }
    
        // Fetch dental records (if needed)
        $dentalRecords = DentalRecord::where('id_number', $user->id_number)->get();
        $hasDentalRecord = $dentalRecords->isNotEmpty();
    
        // Fetch medical records (if needed)
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get();
        $hasMedicalRecord = $medicalRecords->isNotEmpty();
    
        return view('teacher.TeacherDashboard', compact(
            'appointments',
            'appointmentCount',
            'complaints',
            'complaintCount',
            'notifications',
            'hasHealthExamination',
            'hasDentalRecord',
            'hasMedicalRecord',
            'user' // <-- Add this line

        ));
    }
}  