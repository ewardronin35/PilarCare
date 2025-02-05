<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Parents;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\AppointmentCreated;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentRescheduled;
use App\Mail\AppointmentParentRescheduledNotification;
use App\Mail\AppointmentParentNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // <-- Add this line

use App\Mail\AppointmentPending; // Ensure you have this Mailable

use PDF;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
        
        // Initialize variables
        $appointments = collect();
        $totalAppointments = 0;
        $upcomingAppointments = 0;
        $completedAppointments = 0;
        $drIsnaniAppointments = 0;
        $drGanAppointments = 0;
        $doctors = collect(); // Initialize as empty collection
        $complaintCount = 0; // Placeholder for complaint count logic
    
        if (in_array($role, ['admin', 'nurse'])) {
            // **Admin & Nurse**: Fetch all appointments
            $appointments = Appointment::with('doctor.user')->get();
            Log::info("{$role} fetched {$appointments->count()} appointments.");
    
            // Handle search for doctors
            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $request->input('search');
                $doctors = Doctor::whereHas('user', function($q) use ($search){
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('specialization', 'like', "%{$search}%")
                ->get();
                Log::info("Admin/Nurse searched doctors with term '{$search}'. Found {$doctors->count()} doctors.");
            } else {
                // Fetch all approved doctors with active users
                $doctors = Doctor::where('approved', true)
                    ->whereHas('user')
                    ->with('user')
                    ->get();
                Log::info("Admin/Nurse fetched {$doctors->count()} approved doctors.");
            }
    
            // Fetch specific doctors for statistics
            $drIsnani = Doctor::whereHas('user', function($q){
                $q->where('first_name', 'Nurmina')
                  ->where('last_name', 'Isnani');
            })->first();
        
            $drGan = Doctor::whereHas('user', function($q){
                $q->where('first_name', 'Sarah')
                  ->where('last_name', 'Uy Gan');
            })->first();
        
            // Count appointments for specific doctors using doctor_id
            $drIsnaniAppointments = $drIsnani ? $appointments->where('doctor_id', $drIsnani->id)->count() : 0;
            $drGanAppointments = $drGan ? $appointments->where('doctor_id', $drGan->id)->count() : 0;
    
            // Determine the most appointed doctor
            $doctorAppointmentCounts = $appointments->groupBy('doctor_id')->map->count();
            $mostAppointedDoctorId = $doctorAppointmentCounts->sortDesc()->keys()->first();
            $mostAppointedDoctorCount = $doctorAppointmentCounts->sortDesc()->first();
            $mostAppointedDoctorName = 'N/A';
    
            if ($mostAppointedDoctorId) {
                $mostAppointedDoctor = Doctor::with('user')->find($mostAppointedDoctorId);
                if ($mostAppointedDoctor && $mostAppointedDoctor->user) {
                    $mostAppointedDoctorName = $mostAppointedDoctor->full_name;
                }
            }
    
            // Get appointment type counts
            $appointmentTypeCounts = $appointments->groupBy('appointment_type')->map->count();
    
        } elseif ($role == 'doctor') {
            // **Doctor**: Fetch appointments assigned to this doctor
            $doctor = Doctor::where('id_number', $user->id_number)->first();
            
            if ($doctor) {
                $appointments = Appointment::where('doctor_id', $doctor->id)
                    ->with('doctor.user')
                    ->get();
                Log::info("Doctor ID {$doctor->id} fetched {$appointments->count()} appointments.");
    
                // Handle search within doctor's appointments (e.g., patient name or type)
                if ($request->has('search') && !empty($request->input('search'))) {
                    $search = $request->input('search');
                    $appointments = $appointments->filter(function ($appointment) use ($search) {
                        return stripos($appointment->patient_name, $search) !== false ||
                               stripos($appointment->appointment_type, $search) !== false;
                    });
                    Log::info("Doctor searched appointments with term '{$search}'. Found {$appointments->count()} appointments.");
                }
            } else {
                $appointments = collect();
                Log::warning("Doctor profile not found for user ID Number: {$user->id_number}");
            }
    
        } elseif ($role == 'parent') {
            // **Parent**: Fetch appointments for all their children
            $childrenIds = Parents::where('id_number', $user->id_number)->pluck('student_id');
            
            if ($childrenIds->isEmpty()) {
                $appointments = collect();
                Log::info("Parent {$user->id_number} has no associated children.");
            } else {
                $appointments = Appointment::whereIn('id_number', $childrenIds)
                    ->with('doctor.user')
                    ->get();
                Log::info("Parent {$user->id_number} fetched {$appointments->count()} appointments for their children.");
    
                // Handle search within children's appointments (e.g., appointment type)
                if ($request->has('search') && !empty($request->input('search'))) {
                    $search = $request->input('search');
                    $appointments = $appointments->filter(function ($appointment) use ($search) {
                        return stripos($appointment->appointment_type, $search) !== false ||
                               stripos($appointment->patient_name, $search) !== false;
                    });
                    Log::info("Parent searched appointments with term '{$search}'. Found {$appointments->count()} appointments.");
                }
            }
    
        } else {
            // **Regular Users (e.g., Student)**: Fetch appointments specific to their id_number
            $appointments = Appointment::where('id_number', $user->id_number)
                ->with('doctor.user')
                ->get();
            Log::info("Patient ID Number {$user->id_number} fetched {$appointments->count()} appointments.");
    
            // Handle search within user's appointments (e.g., appointment type)
            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $request->input('search');
                $appointments = $appointments->filter(function ($appointment) use ($search) {
                    return stripos($appointment->appointment_type, $search) !== false ||
                           stripos($appointment->doctor->user->first_name, $search) !== false ||
                           stripos($appointment->doctor->user->last_name, $search) !== false;
                });
                Log::info("Patient searched appointments with term '{$search}'. Found {$appointments->count()} appointments.");
            }
        }
    
        // Calculate counts
        $totalAppointments = $appointments->count();
        $upcomingAppointments = $appointments->filter(function ($appointment) {
            return Carbon::parse($appointment->appointment_date)->isFuture();
        })->count();
        $completedAppointments = $appointments->filter(function ($appointment) {
            return Carbon::parse($appointment->appointment_date)->isPast();
        })->count();
    
        // For admin and nurse roles, calculate most appointed doctor and appointment type counts
        if (in_array($role, ['admin', 'nurse'])) {
            // Determine the most appointed doctor
            $doctorAppointmentCounts = $appointments->groupBy('doctor_id')->map->count();
            $mostAppointedDoctorId = $doctorAppointmentCounts->sortDesc()->keys()->first();
            $mostAppointedDoctorCount = $doctorAppointmentCounts->sortDesc()->first();
            $mostAppointedDoctorName = 'N/A';
          
            if ($mostAppointedDoctorId) {
                $mostAppointedDoctor = Doctor::with('user')->find($mostAppointedDoctorId);
                if ($mostAppointedDoctor && $mostAppointedDoctor->user) {
                    $mostAppointedDoctorName = $mostAppointedDoctor->full_name;
                }
            }
    
            // Get appointment type counts
            $appointmentTypeCounts = $appointments->groupBy('appointment_type')->map->count();
             // Prediction Logic
    // a. Overall appointment type frequency
    $overallAppointmentTypeCounts = Appointment::select('appointment_type', DB::raw('count(*) as count'))
    ->groupBy('appointment_type')
    ->orderByDesc('count')
    ->get();


// b. Appointment type frequency per user
$sectionAppointmentCounts = Appointment::select(DB::raw("CONCAT(grade_or_course, ' ', section) as section"), DB::raw('count(*) as count'))
    ->groupBy('grade_or_course', 'section')
    ->orderByDesc('count')
    ->get();

// c. Recent trends (e.g., last 3 months)
$recentAppointmentTypeCounts = Appointment::where('appointment_date', '>=', Carbon::now()->subMonths(3))
    ->select('appointment_type', DB::raw('count(*) as count'))
    ->groupBy('appointment_type')
    ->orderByDesc('count')
    ->get();

// Simulated Prediction: Next appointment type based on overall frequency
$predictedAppointmentTypes = $overallAppointmentTypeCounts->take(3)->map(function ($item) {
    return [
        'type'  => $item->appointment_type,
        'count' => $item->count
    ];
});

// Simulated Prediction: Next recipient based on user's past appointments
$predictedAppointmentRecipients = $sectionAppointmentCounts->take(3)->map(function ($item) {
    return [
        'recipient' => $item->section, // This will be something like "GRADE 11 Section A" or "BSBA Section B"
        'count'     => $item->count
    ];
});
        }
    
        // Define the view path based on the role
        $viewPath = "{$role}.appointment";
    
        // Check if the view exists for the given role, otherwise return 404
        if (!view()->exists($viewPath)) {
            abort(404, "View for role '{$role}' not found");
        }
    
        // Pass the calculated values and doctors to the view
        // For roles that need 'doctors' variable (admin/nurse)
        if (in_array($role, ['admin', 'nurse'])) {
            return view($viewPath, compact(
                'appointments',
                'totalAppointments',
                'upcomingAppointments',
                'completedAppointments',
                'complaintCount',
                'drIsnaniAppointments',
                'drGanAppointments',
                'doctors', // Only admin/nurse have 'doctors'
                'mostAppointedDoctorName',
                'mostAppointedDoctorCount',
                'appointmentTypeCounts',
                 'predictedAppointmentTypes',
            'predictedAppointmentRecipients'
            ));
        } else {
            // For other roles, 'doctors' is not necessary
            return view($viewPath, compact(
                'appointments',
                'totalAppointments',
                'upcomingAppointments',
                'completedAppointments',
                'complaintCount',
                'drIsnaniAppointments',
                'drGanAppointments'
            ));
        }
    }
    
    public function indexs(Request $request)
{
    $user = Auth::user();
    $role = strtolower($user->role);

    $date = $request->input('date');

    if ($role === 'parent') {
        // Fetch the parent's children by querying the students table where parent_id matches the parent's id_number
        $childrenIds = Student::where('parent_id', $user->id_number)->pluck('id_number');

        if ($childrenIds->isEmpty()) {
            $appointments = collect();
            $upcomingAppointments = collect();
            $completedAppointments = collect();
            Log::info("Parent {$user->id_number} has no associated children.");
        } else {
            // Fetch all appointments for the parent's children
            $appointments = Appointment::with('doctor')
                ->whereIn('id_number', $childrenIds)
                ->when($date, function ($query) use ($date) {
                    return $query->whereDate('appointment_date', $date);
                })
                ->get();

            // Filter upcoming appointments (appointments in the future)
            $upcomingAppointments = Appointment::with('doctor')
                ->whereIn('id_number', $childrenIds)
                ->where('appointment_date', '>=', now())
                ->get();

            // Filter completed appointments (appointments in the past)
            $completedAppointments = Appointment::with('doctor')
                ->whereIn('id_number', $childrenIds)
                ->where('appointment_date', '<', now())
                ->get();
        }
    } else {
        // Fetch all appointments for the logged-in user based on their id_number
        $appointments = Appointment::with('doctor')
            ->where('id_number', $user->id_number)
            ->when($date, function ($query) use ($date) {
                return $query->whereDate('appointment_date', $date);
            })
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
    }

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
    // Define custom validation messages.
    $messages = [
        'appointment_date.required'        => 'The appointment date is required.',
        'appointment_date.date_format'       => 'The appointment date must be in the format YYYY-MM-DD.',
        'appointment_date.after_or_equal'    => 'The appointment date cannot be in the past.',
        'appointment_time.required'          => 'The appointment time is required.',
        'appointment_time.in'                => 'The selected appointment time is invalid.',
        'appointment_type.required'          => 'The appointment type is required.',
        'doctor_id.required'                 => 'Please select a doctor.',
        'doctor_id.exists'                   => 'The selected doctor does not exist.',
        'grade_or_course.required'           => 'Please select a grade or course.',
        'section.required'                   => 'Please select a section.',
    ];

    // Validate the request.
    $validator = Validator::make($request->all(), [
        'appointment_date'  => 'required|date_format:Y-m-d|after_or_equal:today',
        'appointment_time'  => 'required|date_format:H:i',
        'appointment_type'  => 'required|string|max:255',
        'doctor_id'         => 'required|exists:doctors,id',
        'grade_or_course'   => 'required|string',
        'section'           => 'required|string',
    ], $messages);

    if ($validator->fails()) {
        Log::warning('Appointment creation failed due to validation errors.', $validator->errors()->toArray());
        return response()->json(['error' => $validator->errors()->first()], 422);
    }

    $user   = Auth::user();
    $role   = strtolower($user->role);
    $status = $role === 'doctor' ? 'confirmed' : 'pending'; // Auto-confirm if doctor

    // Prepare the appointment time (append seconds if needed).
    $appointment_time = $request->appointment_time; // e.g., "10:00"

    // Prepare the appointment data.
    // Notice that we no longer include 'patient_name' here.
    $data = [
        'appointment_date'  => $request->appointment_date,
        'appointment_time'  => $appointment_time,
        'role'              => $user->role,
        'doctor_id'         => $request->doctor_id,
        'appointment_type'  => $request->appointment_type,
        'status'            => $status,
        'grade_or_course'   => $request->grade_or_course,
        'section'           => $request->section,
    ];

    // Determine the extra info string based on the user’s role.
    $extraInfo = '';
    if ($role === 'teacher') {
        // Assuming the teacher model has a bed_or_hed attribute.
        if ($user->teacher) {
            $extraInfo = ' (' . $user->teacher->bed_or_hed . ')';
        }
    } elseif ($role === 'staff') {
        // For staff, use the position attribute.
        if ($user->staff) {
            $extraInfo = ' (' . $user->staff->position . ')';
        }
    } elseif ($role === 'student') {
        // For students, combine grade and section.
        $extraInfo = " for {$request->grade_or_course} {$request->section}";
    }
    // (For other roles, you can add more cases as needed.)

    try {
        DB::beginTransaction();

        // Lock the doctor record to avoid race conditions.
        $doctor = Doctor::where('id', $data['doctor_id'])->lockForUpdate()->first();
        if (!$doctor) {
            DB::rollBack();
            Log::error('Doctor not found.');
            return response()->json(['error' => 'Doctor not found.'], 404);
        }

        // Check if the doctor already has an appointment at the same date and time.
        $existingAppointment = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->first();
        if ($existingAppointment) {
            Log::warning("Doctor ID {$data['doctor_id']} already has an appointment on {$data['appointment_date']} at {$data['appointment_time']}.");
            return response()->json([
                'success' => false,
                'message' => 'The selected doctor already has an appointment on this date and time.',
            ], 409);
        }

        // Create the appointment.
        $appointment = Appointment::create($data);
        Log::info('Appointment Created: ', $appointment->toArray());

        // Fetch the doctor with the associated user data.
        $doctor = Doctor::with('user')->find($request->doctor_id);
        if (!$doctor || !$doctor->user) {
            DB::rollBack();
            Log::error('Doctor or associated user not found.');
            return response()->json(['error' => 'Doctor information is incomplete.'], 500);
        }

        // Build the notification message using the extra info.
        $notificationMessage = "An appointment has been scheduled" . $extraInfo .
            " on {$appointment->appointment_date} at {$appointment->appointment_time}.";

        // Create a notification.
        // For section-wide notifications (e.g. for students) we set user_id to null.
        // For teacher or staff appointments, you might want to notify the individual user.
        $notificationUserId = in_array($role, ['teacher', 'staff']) ? $user->id_number : null;

        Notification::create([
            'user_id'         => $notificationUserId,
            'grade_or_course' => $request->grade_or_course,
            'section'         => $request->section,
            'title'           => 'New Appointment Scheduled',
            'message'         => $notificationMessage,
            'scheduled_time'  => now(),
            'role'            => ($role === 'student') ? 'section' : $role,
        ]);
        Log::info("Notification created: " . $notificationMessage);

        DB::commit();

        return response()->json([
            'success' => 'Appointment scheduled successfully!'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error scheduling appointment: ' . $e->getMessage());
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
    
 // app/Http/Controllers/AppointmentController.php

public function getAppointmentsByMonth(Request $request)
{
    $user = Auth::user();
    $role = strtolower($user->role);
    $monthParam = $request->input('month'); // Expected format: YYYY-MM

    // Validate and extract year and month
    if (!$monthParam || !preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
        return response()->json(['error' => 'Invalid month format. Expected YYYY-MM'], 400);
    }

    list($year, $month) = explode('-', $monthParam);

    // Fetch appointments based on role
    if (in_array($role, ['admin', 'nurse'])) {
        // Admin and Nurse: Fetch all appointments for the specified month
        $appointments = Appointment::with('doctor.user')
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->get();
    } elseif ($role === 'doctor') {
        // Doctor: Fetch appointments assigned to this doctor for the specified month
        $doctor = Doctor::where('id_number', $user->id_number)->first();
        if ($doctor) {
            $appointments = Appointment::with('doctor.user')
                ->where('doctor_id', $doctor->id)
                ->whereYear('appointment_date', $year)
                ->whereMonth('appointment_date', $month)
                ->get();
        } else {
            $appointments = collect();
            Log::warning("Doctor profile not found for user ID Number: {$user->id_number}");
        }
    } elseif (in_array($role, ['parent', 'student'])) {
        // Parent and Student: Fetch appointments based on id_number
        if ($role === 'parent') {
            $childrenIds = Parents::where('id_number', $user->id_number)->pluck('student_id');
            $idNumbers = User::whereIn('id_number', $childrenIds)->pluck('id_number');
        } else {
            $idNumbers = collect([$user->id_number]);
        }

        $appointments = Appointment::with('doctor.user')
            ->whereIn('id_number', $idNumbers)
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->get();
    } else {
        // Other Roles (e.g., Student/Past User): Fetch appointments specific to their id_number
        $appointments = Appointment::with('doctor.user')
            ->where('id_number', $user->id_number)
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month)
            ->get();
    }

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

    if (in_array($role, ['admin', 'nurse'])) {
        // Admin & Nurse: Fetch all appointments on that date
        $appointments = Appointment::with('doctor') // Removed 'doctor.user'
            ->whereDate('appointment_date', $date)
            ->get();
        Log::info("{$role} fetched {$appointments->count()} appointments on {$date}.");
    } elseif ($role == 'doctor') {
        // Doctor: Fetch appointments assigned to this doctor on the date
        $doctor = Doctor::where('id_number', $user->id_number)->first();

        if ($doctor) {
            $appointments = Appointment::with('doctor') // Removed 'doctor.user'
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $date)
                ->get();
            Log::info("Doctor {$doctor->id_number} fetched {$appointments->count()} appointments on {$date}.");
        } else {
            $appointments = collect();
            Log::warning("Doctor profile not found for user ID Number: {$user->id_number}");
        }
    } elseif ($role === 'parent') {
        // Parent: Fetch appointments for all their children on the specified date
        $parent = Parents::where('id_number', $user->id_number)->first();

        if ($parent) {
            // Fetch all children associated with this parent
            $childrenIds = $parent->students()->pluck('id_number');

            if ($childrenIds->isEmpty()) {
                $appointments = collect();
                Log::info("Parent {$user->id_number} has no associated children.");
            } else {
                $appointments = Appointment::with('doctor') // Removed 'doctor.user'
                    ->whereIn('id_number', $childrenIds)
                    ->whereDate('appointment_date', $date)
                    ->get();
                Log::info("Parent {$user->id_number} fetched {$appointments->count()} appointments on {$date}.");
            }
        } else {
            $appointments = collect();
            Log::warning("Parent profile not found for user ID Number: {$user->id_number}");
        }
    } else {
        // For other users (e.g., student), fetch their own appointments
        $appointments = Appointment::with('doctor') // Removed 'doctor.user'
            ->where('id_number', $user->id_number)
            ->whereDate('appointment_date', $date)
            ->get();
        Log::info("Patient {$user->id_number} fetched {$appointments->count()} appointments on {$date}.");
    }

    if ($appointments->isEmpty()) {
        Log::info('No appointments found for this user on this date.');
    } else {
        Log::info('Appointments Fetched:', $appointments->toArray());
    }

    // Map appointments to include necessary fields
    $appointments = $appointments->map(function($appointment) {
        $doctorName = 'N/A';
        if ($appointment->doctor) { // Accessing doctor directly
            $doctorFirstName = $appointment->doctor->first_name ?? '';
            $doctorLastName = $appointment->doctor->last_name ?? '';
            $doctorName = $appointment->doctor ? $appointment->doctor->full_name : 'N/A';
        }

        return [
            'patient_name' => $appointment->patient_name,
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
        $user = Auth::user(); // Define $user
        
        $appointment = Appointment::find($id);
    
        if ($appointment) {
            // Check if already confirmed
            if ($appointment->status === 'confirmed') {
                Log::info("Appointment ID {$id} is already confirmed.");
                return response()->json(['success' => false, 'message' => 'Appointment is already confirmed.'], 400);
            }
    
            $appointment->status = 'confirmed';
            $appointment->save();
    
            // Fetch the doctor and patient
            $doctor = $appointment->doctor()->with('user')->first();
            $patient = User::where('id_number', $appointment->id_number)->first();
    
            if ($doctor && $doctor->user && $patient) {
                // Fetch the patient's parents
                $parents = $patient->parents()->with('user')->get();
    
                // 1. Create and store database notifications
    
                // Notify the patient
                Notification::create([
                    'user_id' => $patient->id_number, // Correct reference
                    'title' => 'Appointment Confirmed',
                    'message' => "Your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} has been confirmed by Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                    'scheduled_time' => now(),
                    'role' => $patient->role,
                ]);
                Log::info("Notification created for patient ID Number {$patient->id_number}");
    
                // Notify the parents
                foreach ($parents as $parent) {
                    Notification::create([
                        'user_id' => $parent->user->id_number, // Correct reference
                        'title' => 'Child\'s Appointment Confirmed',
                        'message' => "{$patient->first_name} {$patient->last_name}'s appointment on {$appointment->appointment_date} at {$appointment->appointment_time} with Dr. {$doctor->user->first_name} {$doctor->user->last_name} has been confirmed.",
                        'scheduled_time' => now(),
                        'role' => $parent->user->role, // Correct role reference
                    ]);
                    Log::info("Notification created for parent ID Number {$parent->user->id_number}");
    
                    // 2. Send confirmation email to parents
                    if ($parent->user->email) { // Ensure parent has an email
                        Mail::to($parent->user->email)->send(new AppointmentConfirmed($appointment, $doctor, $user));
                        Log::info("Sent AppointmentConfirmed email to parent: {$parent->user->email}");
                    } else {
                        Log::warning("Parent ID Number {$parent->user->id_number} does not have an email address.");
                    }
                }
    
                // Notify admins and nurses
                $admins = User::whereIn('role', ['admin', 'nurse'])->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id_number, // Correct reference
                        'title' => 'Appointment Confirmed',
                        'message' => "Appointment ID {$appointment->id} has been confirmed by Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                        'scheduled_time' => now(),
                        'role' => $admin->role,
                    ]);
                    Log::info("Notification created for admin/nurse ID Number {$admin->id_number}");
    
                    // Send confirmation email to admins/nurses
                    if ($admin->email) {
                        Mail::to($admin->email)->send(new AppointmentConfirmed($appointment, $doctor, $user));
                        Log::info("Sent AppointmentConfirmed email to admin/nurse: {$admin->email}");
                    } else {
                        Log::warning("Admin/Nurse ID Number {$admin->id_number} does not have an email address.");
                    }
                }
    
                // 3. Send confirmation email to patient
                if ($patient->email) {
                    Mail::to($patient->email)->send(new AppointmentConfirmed($appointment, $doctor, $user));
                    Log::info("Sent AppointmentConfirmed email to patient: {$patient->email}");
                } else {
                    Log::warning("Patient ID Number {$patient->id_number} does not have an email address.");
                }
    
                return response()->json(['success' => true, 'message' => 'Appointment confirmed successfully']);
            }
    
            return response()->json(['success' => false, 'message' => 'Doctor or patient information is incomplete.'], 500);
        } else {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
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

        // Prepare data for the PDF view
        $data = [
            'totalAppointments' => $totalAppointments,
            'completedAppointments' => $completedAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'appointments' => $appointments,
            'period' => ucfirst($period),
            'startDate' => $startDate->toFormattedDateString(),
            'endDate' => $endDate->toFormattedDateString(),
            'logoBase64' => file_exists(public_path('images/pilarLogo.png')) ? base64_encode(file_get_contents(public_path('images/pilarLogo.png'))) : null,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('pdf.statistics-report', $data);
        $pdfFileName = "statistics_report_{$period}_{$startDate->format('Y_m_d')}_to_{$endDate->format('Y_m_d')}.pdf";
        $pdfDirectory = "statistics_reports"; // Define a directory within the public disk
        $pdfPath = "{$pdfDirectory}/{$pdfFileName}"; // Relative path within storage/app/public

        // Ensure the directory exists
        Storage::disk('public')->makeDirectory($pdfDirectory);

        // Check and delete existing file if it exists
        if (Storage::disk('public')->exists($pdfPath)) {
            Storage::disk('public')->delete($pdfPath);
            Log::info("Deleted existing report: {$pdfPath}");
        }

        try {
            // Save the PDF file
            $pdf->save(storage_path("app/public/{$pdfPath}"));
            Log::info("Generated new statistics report: {$pdfPath}");
        } catch (\Knp\Snappy\Exception\FileAlreadyExistsException $e) {
            Log::error("Failed to save PDF because it already exists: {$pdfPath}");
            return response()->json(['error' => 'Report already exists and cannot be overwritten.'], 409);
        } catch (\Exception $e) {
            Log::error("Error generating PDF: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred while generating the report.'], 500);
        }

        // Return the PDF URL in the response
        return response()->json([
            'success' => true,
            'pdf_url' => asset("storage/{$pdfPath}"),
        ]);
    }

    public function update(Request $request, $id)
    {
        // Define custom validation messages
        $messages = [
            'appointment_date.required'       => 'The appointment date is required.',
            'appointment_date.date_format'      => 'The appointment date must be in the format YYYY-MM-DD.',
            'appointment_date.after_or_equal'   => 'The appointment date cannot be in the past.',
            'appointment_time.required'         => 'The appointment time is required.',
            'appointment_time.in'               => 'The selected appointment time is invalid.',
            'appointment_type.required'         => 'The appointment type is required.',
            'doctor_id.required'                => 'Please select a doctor.',
            'doctor_id.exists'                  => 'The selected doctor does not exist.',
            'patient_name.required'             => 'The patient name is required.',
        ];
    
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'id_number'         => 'required|string|max:7',
            'appointment_date'  => 'required|date_format:Y-m-d|after_or_equal:today',
            'appointment_time'  => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:16:00',
            'appointment_type'  => 'required|string|max:255',
            'doctor_id'         => 'required|exists:doctors,id',
            'patient_name'      => 'required|string|max:255',
        ], $messages);
    
        if ($validator->fails()) {
            Log::warning('Appointment update failed due to validation errors.', $validator->errors()->toArray());
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
    
        $user = Auth::user();
        $role = strtolower($user->role);
    
        // If the user is a doctor, ensure they can only assign themselves.
        if ($role === 'doctor') {
            $doctor = Doctor::where('id_number', $user->id_number)->first();
            if (!$doctor || $doctor->id != $request->doctor_id) {
                Log::warning("Doctor user ID Number {$user->id_number} attempted to assign a different doctor ID {$request->doctor_id}");
                return response()->json(['error' => 'You can only assign yourself as the doctor.'], 403);
            }
        }
    
        try {
            DB::beginTransaction();
    
            // Find the appointment by ID.
            $appointment = Appointment::findOrFail($id);
            Log::info("Found appointment: ", $appointment->toArray());
    
            // Determine if the appointment details have changed.
            $isRescheduled = false;
            if (
                $appointment->appointment_date !== $request->appointment_date ||
                $appointment->appointment_time !== $request->appointment_time ||
                $appointment->doctor_id !== $request->doctor_id
            ) {
                $isRescheduled = true;
            }
    
            // Check if the doctor already has an appointment on the selected date (excluding current appointment)
            $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('id', '!=', $id)
                ->first();
    
            if ($existingAppointment) {
                Log::warning("Doctor ID {$request->doctor_id} already has an appointment on {$request->appointment_date}.");
                return response()->json([
                    'success' => false,
                    'message' => 'The selected doctor already has an appointment on this date.',
                ], 409);
            }
    
            // Update appointment details.
            $appointment->update([
                'id_number'         => $request->id_number,
                'patient_name'      => $request->patient_name,
                'appointment_date'  => $request->appointment_date,
                'appointment_time'  => $request->appointment_time,
                'appointment_type'  => $request->appointment_type,
                'doctor_id'         => $request->doctor_id,
            ]);
            Log::info("Updated appointment details for appointment ID: {$id}");
    
            // Fetch the updated doctor with the associated user.
            $doctor = Doctor::with('user')->find($request->doctor_id);
            if (!$doctor || !$doctor->user) {
                Log::error("Doctor or associated user not found for doctor ID: {$request->doctor_id}");
                return response()->json(['error' => 'Doctor information is incomplete.'], 500);
            }
            Log::info("Fetched doctor: ", $doctor->toArray());
    
            // Fetch the patient user.
            $patient = User::where('id_number', $request->id_number)->first();
            if (!$patient) {
                Log::error("Patient not found for id_number: {$request->id_number}");
                return response()->json(['error' => 'Patient not found.'], 404);
            }
            Log::info("Fetched patient: ", $patient->toArray());
    
            // Determine the extra information based on the user's role.
            $extraInfo = '';
            if ($role === 'teacher') {
                if ($user->teacher) {
                    $extraInfo = ' (' . $user->teacher->bed_or_hed . ')';
                }
            } elseif ($role === 'staff') {
                if ($user->staff) {
                    $extraInfo = ' (' . $user->staff->position . ')';
                }
            } elseif ($role === 'student') {
                // For students, use the appointment data for grade/course and section.
                $extraInfo = " for {$appointment->grade_or_course} {$appointment->section}";
            }
    
            if ($isRescheduled) {
                // Build the notification messages using the extra info.
                $doctorNotificationMsg = "An appointment for **{$patient->patient_name}**" . $extraInfo .
                    " has been rescheduled to **{$appointment->appointment_date}** at **{$appointment->appointment_time}**.";
                $patientNotificationMsg = "Your appointment has been rescheduled to **{$appointment->appointment_date}** at **{$appointment->appointment_time}** with Dr. {$doctor->user->first_name} {$doctor->user->last_name}" . $extraInfo . ".";
    
                // Notify the doctor.
                Notification::create([
                    'user_id'        => $doctor->user->id_number,
                    'title'          => 'Appointment Rescheduled',
                    'message'        => $doctorNotificationMsg,
                    'scheduled_time' => now(),
                    'role'           => $doctor->user->role,
                ]);
                Log::info("Notification created for doctor ID Number {$doctor->user->id_number}");
    
                // Notify the patient.
                Notification::create([
                    'user_id'        => $patient->id_number,
                    'title'          => 'Appointment Rescheduled',
                    'message'        => $patientNotificationMsg,
                    'scheduled_time' => now(),
                    'role'           => $patient->role,
                ]);
                Log::info("Notification created for patient ID Number {$patient->id_number}");
    
                // Send rescheduled email to the patient.
                if ($patient->email) {
                    Mail::to($patient->email)->send(new AppointmentRescheduled($appointment, $doctor, $user));
                    Log::info("Sent AppointmentRescheduled email to patient: {$patient->email}");
                } else {
                    Log::warning("Patient ID Number {$patient->id_number} does not have an email address.");
                }
    
                Log::info("Appointment ID {$id} rescheduled successfully.");
            } else {
                Log::info("No changes detected. Appointment ID {$id} was not rescheduled.");
            }
    
            DB::commit(); // Commit the transaction
    
            return response()->json(['success' => 'Appointment updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
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
    public function getChildAppointments(Request $request)
{
    $user = Auth::user();

    // Assuming the parent has a 'students' relationship to their children
    $students = $user->students;

    if ($students->isEmpty()) {
        return response()->json(['appointments' => []]); // No children found
    }

    // Fetch the child's appointments for each student
    $appointments = Appointment::whereIn('id_number', $students->pluck('id_number'))
        ->with('doctor.user')
        ->get();

    // Map appointments to include necessary fields
    $appointments = $appointments->map(function($appointment) {
        $doctorName = 'N/A';
        if ($appointment->doctor && $appointment->doctor->user) {
            $doctorFirstName = $appointment->doctor->user->first_name ?? '';
            $doctorLastName = $appointment->doctor->user->last_name ?? '';
            $doctorName = $appointment->doctor ? $appointment->doctor->full_name : 'N/A';
        }

        return [
            'child_name' => $appointment->patient_name,
            'appointment_time' => $appointment->appointment_time,
            'appointment_type' => $appointment->appointment_type,
            'status' => $appointment->status,
            'doctor_name' => $doctorName,
            'appointment_date' => $appointment->appointment_date,
        ];
    });

    return response()->json([
        'appointments' => $appointments
    ]);
}
public function getChildAppointmentsByDate(Request $request)
{
    $user = Auth::user();

    // Validate the incoming request
    $validated = $request->validate([
        'date' => 'required|date_format:Y-m-d'
    ]);

    $date = $validated['date'];

    Log::info('Fetching child appointments for date: ' . $date . ' and parent: ' . $user->id_number);

    // Check if the user is a parent
    if (strtolower($user->role) === 'parent') {
        // Fetch the parent record
        $parent = Parents::where('id_number', $user->id_number)->first();

        if (!$parent) {
            Log::info("Parent {$user->id_number} not found.");
            return response()->json(['appointments' => []]);
        }

        // Fetch all children associated with this parent
        $children = $parent->students()->pluck('id_number');

        if ($children->isEmpty()) {
            Log::info("Parent {$user->id_number} has no associated children.");
            return response()->json(['appointments' => []]);
        }

        // Fetch appointments for all children on the specified date
        $appointments = Appointment::with('doctor.user')
            ->whereIn('id_number', $children)
            ->whereDate('appointment_date', $date)
            ->get();

        // Map appointments to include necessary fields
        $formattedAppointments = $appointments->map(function($appointment) {
            $doctorName = 'N/A';
            if ($appointment->doctor) {
                $doctorName = $appointment->doctor->full_name ?? 'N/A';
            }
           

            return [
                'child_name' => $appointment->patient_name,
                'appointment_time' => $appointment->appointment_time,
                'appointment_type' => $appointment->appointment_type,
                'status' => $appointment->status,
                'doctor_name' => $doctorName,
                'appointment_date' => $appointment->appointment_date,
            ];
        });

        return response()->json([
            'appointments' => $formattedAppointments
        ]);
    } else {
        return response()->json(['appointments' => []]);
    }
}

public function availableDoctors(Request $request)
{
    // Validate the date
    $request->validate([
        'date' => 'required|date_format:Y-m-d',
    ]);

    $date = $request->input('date');

    // Optional: Exclude a specific appointment ID (useful for edit functionality)
    $excludeAppointmentId = $request->input('exclude_appointment_id');

    // Fetch doctors who are approved and have the necessary attributes
    $query = Doctor::where('approved', true)
        ->whereNotNull('first_name')
        ->whereNotNull('last_name');

    if ($excludeAppointmentId) {
        $query->whereDoesntHave('appointments', function ($q) use ($date, $excludeAppointmentId) {
            $q->whereDate('appointment_date', $date)
              ->where('id', '!=', $excludeAppointmentId);
        });
    } else {
        $query->whereDoesntHave('appointments', function ($q) use ($date) {
            $q->whereDate('appointment_date', $date);
        });
    }

    // Include the 'user' relationship
    $availableDoctors = $query->with('user')->get(['id', 'first_name', 'last_name', 'specialization']);

    return response()->json([
        'available_doctors' => $availableDoctors
    ]);
} public function getNextAppointment()
{
    $user = Auth::user();
    $userRole = strtolower($user->role);

    if ($userRole !== 'doctor') {
        return response()->json([
            'success' => false,
            'error' => 'Unauthorized access.'
        ], 403);
    }

    $doctor = $user->doctor;

    if (!$doctor) {
        return response()->json([
            'success' => false,
            'error' => 'Doctor profile not found.'
        ], 404);
    }

    $currentDateTime = now();

    // Fetch the next appointment that is confirmed and scheduled after the current time
    $nextAppointment = Appointment::where('doctor_id', $doctor->id)
        ->where('status', 'confirmed')
        ->where(function ($query) use ($currentDateTime) {
            $query->where('appointment_date', '>', $currentDateTime->toDateString())
                  ->orWhere(function ($q) use ($currentDateTime) {
                      $q->where('appointment_date', $currentDateTime->toDateString())
                        ->where('appointment_time', '>', $currentDateTime->format('H:i:s'));
                  });
        })
        ->orderBy('appointment_date', 'asc')
        ->orderBy('appointment_time', 'asc')
        ->first();

    if ($nextAppointment) {
        return response()->json([
            'success' => true,
            'nextAppointment' => $nextAppointment
        ]);
    } else {
        return response()->json([
            'success' => true,
            'nextAppointment' => null
        ]);
    }
}

}