<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;  // Add this line
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Notification;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
    
        // Determine the role of the logged-in user
        $role = strtolower($user->role);
    
        // Fetch appointments based on user role
        if ($role == 'admin') {
            // Fetch all appointments
            $appointments = Appointment::all();
        } else {
            // Fetch appointments for the logged-in user based on their id_number
            $appointments = Appointment::where('id_number', $user->id_number)->get();
        }
    
        // Count total appointments
        $totalAppointments = $appointments->count();
    
        // Filter and count upcoming appointments
        $upcomingAppointments = $appointments->filter(function ($appointment) {
            return Carbon::parse($appointment->appointment_date)->isFuture();
        })->count();
    
        // Filter and count completed appointments
        $completedAppointments = $appointments->filter(function ($appointment) {
            return Carbon::parse($appointment->appointment_date)->isPast();
        })->count();
    
        // Count appointments for specific doctors
        $drIsnaniAppointments = $appointments->where('appointment_type', 'Dr. Nurmina Isnani')->count();
        $drGanAppointments = $appointments->where('appointment_type', 'Dr. Sarah Uy Gan')->count();
    
        $complaintCount = 0; // Placeholder for complaint count logic
    
        $viewPath = "{$role}.appointment";
    
        // Check if the view exists for the given role, otherwise return 404
        if (!view()->exists($viewPath)) {
            abort(404, "View for role '{$role}' not found");
        }
    
        // Fetch Doctors who are approved and have an active user
          // Fetch Doctors who are approved and have an associated user
          $doctorsQuery = Doctor::where('approved', true)
          ->whereHas('user') // Ensures only doctors with a user are fetched
          ->with('user'); // Eager load the user relationship

        // Handle Search
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $doctorsQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $doctors = $doctorsQuery->get();
    
        // Pass the calculated values and doctors to the view
        return view($viewPath, compact(
            'appointments',
            'totalAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'complaintCount',
            'drIsnaniAppointments',
            'drGanAppointments',
            'doctors' // Added doctors to the compact function
        ));
    }
    public function indexs(Request $request)
    {
        $user = Auth::user();
    
        $date = $request->input('date');
    
        // Fetch all appointments for the logged-in user based on their id_number
        $appointments = Appointment::with('doctor')
            ->where('id_number', $user->id_number)
            ->whereDate('appointment_date', $date)
            ->get();
    
        // Filter upcoming appointments (appointments in the future)
        $upcomingAppointments = Appointment::with('doctor')
            ->where('id_number', $user->id_number)
            ->where('appointment_date', '>=', now())
            ->get();
    
        // Filter completed appointments (appointments in the past)
        $completedAppointments = Appointment::with('doctor')
            ->where('id_number', $user->id_number)
            ->where('appointment_date', '<', now())
            ->get();
    
        // Determine the role of the logged-in user and make sure it's lowercase for matching the view
        $role = strtolower($user->role);
    
        // Define the view path based on the role
        $viewPath = "{$role}.appointment";
    
        // Check if the view exists for the given role, otherwise return 404
        if (!view()->exists($viewPath)) {
            abort(404, "View for role '{$role}' not found");
        }
    
        // Pass the calculated values to the view
        return view($viewPath, compact(
            'appointments',
            'upcomingAppointments',
            'completedAppointments'
        ));
    }
    
    public function add(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'id_number' => 'required|string|max:7',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
        ]);
    
        $user = Auth::user();
    
        $data = [
            'id_number' => $request->id_number,
            'patient_name' => $request->patient_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'role' => $user->role,
            'doctor_id' => $request->doctor_id,
            'appointment_type' => $request->appointment_type,
            'status' => 'pending',
        ];
    
        Log::info('Appointment Data: ', $data);
    
        try {
            // Create the appointment
            $appointment = Appointment::create($data);
            Log::info('Appointment Created: ', $appointment->toArray());
    
            // Fetch the doctor with the associated user
            $doctor = Doctor::with('user')->find($request->doctor_id);
            if (!$doctor || !$doctor->user) {
                Log::error('Doctor or associated user not found.');
                return response()->json(['error' => 'Doctor information is incomplete.'], 500);
            }
    
            // Fetch the patient user
            $patient = User::where('id_number', $request->id_number)->first();
            if (!$patient) {
                Log::error('Patient not found.');
                return response()->json(['error' => 'Patient not found.'], 404);
            }
    
            // Create a notification for the doctor
     // Notification for the doctor
Notification::create([
    'user_id' => $doctor->user->id_number, // Correct: Reference 'id'
    'title' => 'New Appointment Scheduled',
    'message' => "You have a new appointment scheduled by {$user->first_name} {$user->last_name} on {$appointment->appointment_date} at {$appointment->appointment_time}.",
    'scheduled_time' => now(),
    'role' => $doctor->user->role,
]);

// Notification for the patient
Notification::create([
    'user_id' => $patient->id_number, // Correct: Reference 'id'
    'title' => 'Appointment Pending Approval',
    'message' => "Your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} with Dr. {$doctor->user->first_name} {$doctor->user->last_name} is pending approval.",
    'scheduled_time' => now(),
    'role' => $patient->role,
]);

    
            return response()->json(['success' => 'Appointment scheduled successfully!']);
        } catch (\Exception $e) {
            Log::error('Error scheduling appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_number' => 'required|string|max:7',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
        ]);

        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->update($request->only(['appointment_date', 'appointment_time', 'appointment_type']));

            return response()->json(['success' => 'Appointment updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Error updating appointment: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->delete();

            return response()->json(['success' => 'Appointment deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting appointment: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
    public function fetchPatientName($id)
    {
        $user = User::where('id_number', $id)->first();

        if ($user) {
            return response()->json([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);
        } else {
            return response()->json(['error' => 'Patient not found'], 404);
        }
    }
    // Add this method in your AppointmentController
    public function getAppointmentsByMonth(Request $request)
    {
        $user = Auth::user();
        $monthParam = $request->input('month'); // Expected format: YYYY-MM
    
        // Validate and extract year and month
        if (!$monthParam || !preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
            return response()->json(['error' => 'Invalid month format. Expected YYYY-MM'], 400);
        }
    
        list($year, $month) = explode('-', $monthParam);
    
        // Fetch appointments for the specified month
        $appointments = Appointment::where('appointment_date', 'like', "{$year}-{$month}-%")->get();
    
        return response()->json(['appointments' => $appointments]);
    }
    
    
    public function getAppointmentsByDate(Request $request)
{
    $user = Auth::user();

    // Validate date format to ensure it is YYYY-MM-DD
    $validated = $request->validate([
        'date' => 'required|date_format:Y-m-d'
    ]);

    $date = $validated['date'];

    Log::info('Fetching appointments for date: ' . $date . ' and user: ' . $user->id_number);

    $role = strtolower($user->role);

    if ($role == 'admin') {
        // For admin, fetch all appointments on that date
        $appointments = Appointment::with('doctor')
            ->whereDate('appointment_date', $date)
            ->get();
    } else {
        // For other users, fetch only their appointments
        $appointments = Appointment::with('doctor')
            ->where('id_number', $user->id_number)
            ->whereDate('appointment_date', $date)
            ->get();
    }

    if ($appointments->isEmpty()) {
        Log::info('No appointments found for this user on this date.');
    } else {
        Log::info('Appointments Fetched:', $appointments->toArray());
    }

    // Map appointments to include doctor_name
    $appointments = $appointments->map(function($appointment) {
        $doctorName = 'N/A';
        if ($appointment->doctor) {
            $doctorFirstName = $appointment->doctor->first_name ?? '';
            $doctorLastName = $appointment->doctor->last_name ?? '';
            $doctorName = trim($doctorFirstName . ' ' . $doctorLastName);
        }

        return [
            'appointment_time' => $appointment->appointment_time,
            'appointment_type' => $appointment->appointment_type,
            'status' => $appointment->status,
            'doctor_name' => $doctorName,
            'appointment_date' => $appointment->appointment_date,
            // Include other fields as needed
        ];
    });

    return response()->json([
        'appointments' => $appointments
    ]);
}

    
    public function confirm($id)
    {
        $appointment = Appointment::find($id);
    
        if ($appointment) {
            $appointment->status = 'confirmed';
            $appointment->save();
    
            // Fetch the doctor and patient
            $doctor = $appointment->doctor()->with('user')->first();
            $patient = User::where('id_number', $appointment->id_number)->first();
    
            if ($doctor && $doctor->user && $patient) {
                // Notify the patient that the appointment has been confirmed
                Notification::create([
                    'user_id' => $patient->id_number, // Use 'id' instead of 'id_number'
                    'title' => 'Appointment Confirmed',
                    'message' => "Your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} has been confirmed by Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                    'scheduled_time' => now(),
                    'role' => $patient->role,
                ]);
    
                // Optionally, notify the admin or nurse about the confirmation
                // Assuming you have roles like 'admin' or 'nurse'
                // Fetch users with 'admin' or 'nurse' roles and notify them
                $admins = User::whereIn('role', ['admin', 'nurse'])->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id_number,
                        'title' => 'Appointment Confirmed',
                        'message' => "Appointment ID {$appointment->id} has been confirmed by Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                        'scheduled_time' => now(),
                        'role' => $admin->role,
                    ]);
                }
            }
    
            return response()->json(['success' => true, 'message' => 'Appointment confirmed successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Appointment not found']);
        }
    }
    
public function getApprovedDoctors()
{
    $doctors = Doctor::where('approved', true)
        ->whereHas('user')
        ->with('user')
        ->get();

    return response()->json(['doctors' => $doctors]);
}
public function generateStatisticsReport(Request $request)
{
    // Validate the request parameters
    $request->validate([
        'report_period' => 'required|in:week,month',
        'report_date' => 'required|date',
    ]);

    // Parse the request data
    $period = $request->report_period;
    $date = Carbon::parse($request->report_date);

    // Determine the start and end date based on the period (week or month)
    if ($period === 'week') {
        $startDate = $date->copy()->startOfWeek();
        $endDate = $date->copy()->endOfWeek();
    } elseif ($period === 'month') {
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();
    }

    // Fetch appointments within the date range
    $appointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->get();

    // Calculate statistics
    $totalAppointments = $appointments->count();
    $completedAppointments = $appointments->filter(function ($appointment) {
        return Carbon::parse($appointment->appointment_date)->isPast();
    })->count();
    $upcomingAppointments = $totalAppointments - $completedAppointments;

    // Check if the logo file exists and convert it to base64
    $logoPath = public_path('images/logo.png'); // Adjust path as needed
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    } else {
        $logoBase64 = null; // Handle missing logo gracefully
    }

    // Pass the data to the PDF view
    $data = [
        'totalAppointments' => $totalAppointments,
        'completedAppointments' => $completedAppointments,
        'upcomingAppointments' => $upcomingAppointments,
        'appointments' => $appointments,
        'period' => ucfirst($period),
        'startDate' => $startDate->toFormattedDateString(),
        'endDate' => $endDate->toFormattedDateString(),
        'logoBase64' => $logoBase64, // Include the logo base64 string
    ];

    // Generate the PDF
    $pdf = PDF::loadView('pdf.statistics-report', $data);
    $pdfFileName = "statistics_report_{$period}_{$startDate->format('Y_m_d')}_to_{$endDate->format('Y_m_d')}.pdf";
    $pdfPath = storage_path("app/public/{$pdfFileName}");

    // Save the PDF file
    $pdf->save($pdfPath);

    // Return the PDF URL in the response
    return response()->json([
        'success' => true,
        'pdf_url' => asset("storage/{$pdfFileName}"),
    ]);
}



    
}    