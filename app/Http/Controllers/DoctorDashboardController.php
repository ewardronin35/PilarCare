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
use App\Models\Doctor;
use App\Models\DentalRecord;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this line
use Carbon\Carbon;


class DoctorDashboardController extends Controller
{ 
    protected function getAuthenticatedDoctor()
    {
        $user = Auth::user();
        if (!$user) {
            Log::error("No authenticated user found.");
            return null;
        }

        $doctor = Doctor::where('id_number', $user->id_number)->first();

        if (!$doctor) {
            Log::error("Doctor profile not found for User ID Number: {$user->id_number}");
        }

        return $doctor;
    }
    public function index()
    {
        // Get the currently authenticated doctor
        $doctor = $this->getAuthenticatedDoctor();

        if (!$doctor) {
            // Redirect back with an error message if Doctor profile not found
            return redirect()->back()->withErrors(['error' => 'Doctor profile not found.']);
        }
        // 1. Count of appointments for this doctor
        $doctorAppointmentsCount = Appointment::where('doctor_id', $doctor->id)->count();

        // 2. Other Counts
        $complaintCount = Complaint::count();
        $pendingApprovalCount = HealthExamination::where('is_approved', false)->count();
        $dentalRecordCount = DentalRecord::count();
        $medicalRecordCount = MedicalRecord::count();

        // 3. Pending Approvals
        $pendingHealthExams = HealthExamination::where('is_approved', false)->count();
        $pendingMedicalApprovals = MedicalRecord::where('is_approved', false)->count();

        // 4. Notifications (assuming 'admin' is a user role or specific ID)
        $notifications = Notification::where('user_id', 'admin')->get();

        // 5. Users by Role
        $students = User::where('role', 'Student')->get();
        $staff = User::where('role', 'Staff')->get();
        $parents = User::where('role', 'Parent')->get();
        $teachers = User::where('role', 'Teacher')->get();
        $doctors = User::where('role', 'Doctor')->get();
        $nurses = User::where('role', 'Nurse')->get();

        // 6. Monthly User Data for Charts
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
                $counts[] = $monthlyCounts[$i] ?? 0;
            }
            $monthlyUserData[$role] = $counts;
        }

        // 7. Appointment Statistics (e.g., Monthly Appointments)
        $monthlyAppointments = Appointment::selectRaw('MONTH(appointment_date) as month, COUNT(*) as count')
        ->where('doctor_id', $doctor->id)
        ->whereYear('appointment_date', date('Y'))
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

    $appointmentsPerMonth = [];
    for ($i = 1; $i <= 12; $i++) {
        $appointmentsPerMonth[] = $monthlyAppointments[$i] ?? 0;
    }
        // 8. Recent Complaints (optional for display)

        // Pass all data to the view
        return view('doctor.DoctorDashboard', compact(
            'doctorAppointmentsCount',
            'complaintCount',
            'pendingApprovalCount',
            'students',
            'staff',
            'parents',
            'dentalRecordCount',
            'medicalRecordCount',
            'teachers',
            'doctors',
            'nurses',
            'monthlyUserData',
            'appointmentsPerMonth',
        ));
    }

    public function appointments()
    {
        $doctor = Auth::user();
        $appointments = Appointment::where('doctor_id', $doctor->id)->latest()->paginate(10);
        return view('doctor.appointments', compact('appointments'));
    }
    public function getAppointmentsByMonthDoctor(Request $request)
    {
        $user = Auth::user();
        $monthParam = $request->input('month'); // Expected format: YYYY-MM
    
        // Validate the month parameter
        if (!$monthParam || !preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
            Log::error("Invalid month format received: {$monthParam}");
            return response()->json(['error' => 'Invalid month format. Expected YYYY-MM'], 400);
        }
    
        list($year, $month) = explode('-', $monthParam);
        Log::info("Fetching appointments for Doctor ID {$user->id_number} for {$year}-{$month}");
    
        // Fetch the doctor based on the authenticated user's id_number
        $doctor = Doctor::where('id_number', $user->id_number)->first();
    
        if (!$doctor) {
            Log::error("Doctor profile not found for user ID Number: {$user->id_number}");
            return response()->json(['error' => 'Doctor profile not found.'], 404);
        }
    
        // Fetch appointments for the specified month and doctor
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'id_number' => $appointment->id_number,
                    'patient_name' => $appointment->patient_name,
                    'appointment_date' => Carbon::parse($appointment->appointment_date)->format('Y-m-d'), // Format as 'YYYY-MM-DD'
                    'appointment_time' => $appointment->appointment_time,
                    'role' => $appointment->role,
                    'doctor_id' => $appointment->doctor_id,
                    'appointment_type' => $appointment->appointment_type,
                    'status' => $appointment->status,
                ];
            });
    
        Log::info("Fetched {$appointments->count()} appointments for Doctor ID {$doctor->id}");
    
        return response()->json(['appointments' => $appointments]);
    }
    public function getAppointmentsByDate(Request $request)
    {
        $doctor = Auth::user();
        $date = $request->query('date'); // Expected format: YYYY-MM-DD

        if (!$date) {
            return response()->json(['error' => 'Date parameter is required.'], 400);
        }

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->get();

        return response()->json(['appointments' => $appointments], 200);
    }

    /**
     * Fetch patient name based on ID number.
     */
  
    public function complaints()
    {
        $complaints = Complaint::latest()->paginate(10);
        return view('doctor.complaints', compact('complaints'));
    }
    
    public function medicalRecords()
    {
        $medicalRecords = MedicalRecord::where('doctor_id', Auth::id())->latest()->paginate(10);
        return view('doctor.medicalRecords', compact('medicalRecords'));
    }
    
    public function dentalRecords()
    {
        $dentalRecords = Teeth::where('doctor_id', Auth::id())->latest()->paginate(10);
        return view('doctor.dentalRecords', compact('dentalRecords'));
    }
    
}
