<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Parents;

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

use App\Mail\AppointmentPending; // Ensure you have this Mailable

use PDF;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
        
        if (in_array($role, ['admin', 'nurse'])) {
            // **Admin & Nurse**: Fetch **all** appointments
            $appointments = Appointment::with('doctor.user')->get();
            Log::info("{$role} fetched {$appointments->count()} appointments.");
        } elseif ($role == 'doctor') {
            // Fetch the doctor's ID based on the logged-in user
            $doctor = Doctor::where('id_number', $user->id_number)->first();
            
            if ($doctor) {
                // Fetch appointments assigned to this doctor
                $appointments = Appointment::where('doctor_id', $doctor->id)->with('doctor.user')->get();
                Log::info("Doctor ID {$doctor->id} fetched {$appointments->count()} appointments.");
            } else {
                $appointments = collect(); // Empty collection if doctor not found
                Log::warning("Doctor profile not found for user ID Number: {$user->id_number}");
            }
        } else {
            // **Regular Users**: Fetch appointments **specific** to their `id_number`
            $appointments = Appointment::where('id_number', $user->id_number)->with('doctor.user')->get();
            Log::info("Patient ID Number {$user->id_number} fetched {$appointments->count()} appointments.");
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
    
        // Fetch specific doctors for statistics (only for admin and nurse)
        if (in_array($role, ['admin', 'nurse'])) {
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
        } else {
            $drIsnaniAppointments = 0;
            $drGanAppointments = 0;
        }
    
        $complaintCount = 0; // Placeholder for complaint count logic
    
        // Define the view path based on the role
        $viewPath = "{$role}.appointment";
    
        // Check if the view exists for the given role, otherwise return 404
        if (!view()->exists($viewPath)) {
            abort(404, "View for role '{$role}' not found");
        }
    
        // Fetch Doctors who are approved and have an active user
        $doctorsQuery = Doctor::where('approved', true)
            ->whereHas('user') // Ensures only doctors with a user are fetched
            ->with('user'); // Eager load the user relationship
    
        // Handle Search (only for admin and nurse)
        if (in_array($role, ['admin', 'nurse']) && $request->has('search') && !empty($request->input('search'))) {
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
        Log::info("Fetched {$doctors->count()} approved doctors.");
    
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
        $role = strtolower($user->role);
    
        $date = $request->input('date');
    
        if ($role === 'parent') {
            // Fetch the parent's children
            $childrenIds = Parents::where('id_number', $user->id_number)->pluck('student_id');
    
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
        // Define custom validation messages
        $messages = [
            'appointment_date.required' => 'The appointment date is required.',
            'appointment_date.date_format' => 'The appointment date must be in the format YYYY-MM-DD.',
            'appointment_date.after_or_equal' => 'The appointment date cannot be in the past.',
            'appointment_time.required' => 'The appointment time is required.',
            'appointment_time.in' => 'The selected appointment time is invalid.',
            'appointment_type.required' => 'The appointment type is required.',
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'patient_name.required' => 'The patient name is required.',
        ];
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|string|max:7',
            'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:16:00',
            'appointment_type' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
        ], $messages);
    
        // Check if validation fails
        if ($validator->fails()) {
            Log::warning('Appointment creation failed due to validation errors.', $validator->errors()->toArray());
            return response()->json(['error' => $validator->errors()->first()], 422); // 422 Unprocessable Entity
        }
    
        $user = Auth::user();
        $role = strtolower($user->role);
        $status = $role === 'doctor' ? 'confirmed' : 'pending'; // Auto-confirm if doctor
    
        $appointment_time = $request->appointment_time . ':00'; // Append seconds if necessary

        // Prepare data after validation
        $data = [
            'id_number' => $request->id_number,
            'patient_name' => $request->patient_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'role' => $user->role,
            'doctor_id' => $request->doctor_id,
            'appointment_type' => $request->appointment_type,
            'status' => $status, // Set status based on role
        ];
    
        Log::info('Appointment Data: ', $data);
    
        try {
            DB::beginTransaction();
            $doctor = Doctor::where('id', $data['doctor_id'])->lockForUpdate()->first();
            if (!$doctor) {
                DB::rollBack();
                Log::error('Doctor not found.');
                return response()->json(['error' => 'Doctor not found.'], 404);
            }
            // Check if the doctor already has an appointment on the selected date
            $existingAppointment = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->first();
    
            if ($existingAppointment) {
                Log::warning("Doctor ID {$data['doctor_id']} already has an appointment on {$data['appointment_date']}.");
                return response()->json([
                    'success' => false,
                    'message' => 'The selected doctor already has an appointment on this date.',
                ], 409); // 409 Conflict
            }
    
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
    
            // Fetch the patient's parents
            $parents = $patient->parents()->with('user')->get();
    
            if ($parents->isNotEmpty()) {
                foreach ($parents as $parent) {
                    if ($parent->user && $parent->user->email) {
                        // Send email to parent
                        Mail::to($parent->user->email)->send(new AppointmentParentNotification($appointment, $doctor, $parent->user));
                        Log::info("Sent AppointmentParentNotification email to parent: {$parent->user->email}");
                    } else {
                        Log::warning("Parent ID Number {$parent->id_number} does not have an email address.");
                    }
                }
            } else {
                Log::info("No parents found for patient ID Number: {$patient->id_number}");
            }
    
            // 1. Create and store database notifications
    
            // Notify the doctor about the new appointment
            Notification::create([
                'user_id' => $doctor->user->id_number, // Correct reference
                'title' => 'New Appointment Scheduled',
                'message' => "You have a new appointment scheduled by **{$user->first_name} {$user->last_name}** on {$appointment->appointment_date} at {$appointment->appointment_time}.",
                'scheduled_time' => now(),
                'role' => $doctor->user->role,
            ]);
            Log::info("Notification created for doctor ID Number {$doctor->user->id_number}");
    
            // Notify the patient about the appointment
            Notification::create([
                'user_id' => $patient->id_number, // Correct reference
                'title' => 'Appointment ' . ucfirst($status),
                'message' => $status === 'confirmed' 
                    ? "Your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} with Dr. {$doctor->user->first_name} {$doctor->user->last_name} has been confirmed."
                    : "Your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} with Dr. {$doctor->user->first_name} {$doctor->user->last_name} is pending approval.",
                'scheduled_time' => now(),
                'role' => $patient->role,
            ]);
            Log::info("Notification created for patient ID Number {$patient->id_number}");
    
            // Notify the patient's parents about the appointment
            foreach ($parents as $parent) {
                Notification::create([
                    'user_id' => $parent->user->id_number, // Correct reference
                    'title' => 'Child\'s Appointment Scheduled',
                    'message' => "{$patient->patient_name} has an appointment scheduled on {$appointment->appointment_date} at {$appointment->appointment_time} with Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                    'scheduled_time' => now(),
                    'role' => $parent->user->role,
                ]);
                Log::info("Notification created for parent ID Number {$parent->user->id_number}");
            }
    
            // 2. Send email notifications to doctor and patient based on status
    
            if ($status === 'confirmed') {
                // Send email to the doctor about the new appointment
                if ($doctor->user->email) {
                    Mail::to($doctor->user->email)->send(new AppointmentCreated($appointment, $doctor, $user));
                    Log::info("Sent AppointmentCreated email to doctor: {$doctor->user->email}");
                } else {
                    Log::warning("Doctor ID Number {$doctor->user->id_number} does not have an email address.");
                }
    
                // Send email to the patient about the confirmed appointment
                if ($patient->email) {
                    Mail::to($patient->email)->send(new AppointmentConfirmed($appointment, $doctor, $user));
                    Log::info("Sent AppointmentConfirmed email to patient: {$patient->email}");
                } else {
                    Log::warning("Patient ID Number {$patient->id_number} does not have an email address.");
                }
            } else {
                // Send email to the patient about the pending appointment
                if ($patient->email) {
                    Mail::to($patient->email)->send(new AppointmentPending($appointment, $doctor, $user));
                    Log::info("Sent AppointmentPending email to patient: {$patient->email}");
                } else {
                    Log::warning("Patient ID Number {$patient->id_number} does not have an email address.");
                }
            }
            DB::commit(); // Commit the transaction

            return response()->json(['success' => 'Appointment scheduled successfully!']);
        } catch (\Exception $e) {
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
    
        if (in_array($role, ['admin', 'nurse', 'doctor'])) {
            // For admin, fetch all appointments on that date
            $appointments = Appointment::with('doctor.user')
                ->whereDate('appointment_date', $date)
                ->get();
            Log::info("Admin fetched {$appointments->count()} appointments on {$date}.");
        } elseif ($role == 'doctor') {
            // Fetch the doctor's ID based on the logged-in user
            $doctor = Doctor::where('id_number', $user->id_number)->first();
    
            if ($doctor) {
                // Fetch appointments for the specified date and doctor
                $appointments = Appointment::with('doctor.user')
                    ->where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', $date)
                    ->get();
                Log::info("Doctor {$doctor->id_number} fetched {$appointments->count()} appointments on {$date}.");
            } else {
                $appointments = collect();
                Log::warning("Doctor profile not found for user ID Number: {$user->id_number}");
            }
        } else {
            // For other users, fetch only their appointments
            $appointments = Appointment::with('doctor.user')
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
            if ($appointment->doctor && $appointment->doctor->user) {
                $doctorFirstName = $appointment->doctor->user->first_name ?? '';
                $doctorLastName = $appointment->doctor->user->last_name ?? '';
                $doctorName = trim($doctorFirstName . ' ' . $doctorLastName);
            }
    
            return [
                'patient_name' => $appointment->patient_name, // Added this line
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

        // Check if the logo file exists and convert it to base64


        // Pass the data to the PDF view
        $data = [
            'totalAppointments' => $totalAppointments,
            'completedAppointments' => $completedAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            'appointments' => $appointments,
            'period' => ucfirst($period),
            'startDate' => $startDate->toFormattedDateString(),
            'endDate' => $endDate->toFormattedDateString(),
            'logoBase64' => base64_encode(file_get_contents(public_path('images/pilarLogo.png'))),
             // Include the logo base64 string
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

    public function update(Request $request, $id)
    {
        // Define custom validation messages
        $messages = [
            'appointment_date.required' => 'The appointment date is required.',
            'appointment_date.date_format' => 'The appointment date must be in the format YYYY-MM-DD.',
            'appointment_date.after_or_equal' => 'The appointment date cannot be in the past.',
            'appointment_time.required' => 'The appointment time is required.',
            'appointment_time.in' => 'The selected appointment time is invalid.',
            'appointment_type.required' => 'The appointment type is required.',
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'patient_name.required' => 'The patient name is required.',
        ];
    
    
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|string|max:7',
            'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:16:00',
            'appointment_type' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_name' => 'required|string|max:255',
        ], $messages);
    
        // Check if validation fails
        if ($validator->fails()) {
            Log::warning('Appointment update failed due to validation errors.', $validator->errors()->toArray());
            return response()->json(['error' => $validator->errors()->first()], 422); // 422 Unprocessable Entity
        }
    
        $user = Auth::user(); // Define $user
        $role = strtolower($user->role);
    
        // If user is doctor, ensure they can only assign themselves
        if ($role === 'doctor') {
            $doctor = Doctor::where('id_number', $user->id_number)->first();
            if (!$doctor || $doctor->id != $request->doctor_id) {
                Log::warning("Doctor user ID Number {$user->id_number} attempted to assign a different doctor ID {$request->doctor_id}");
                return response()->json(['error' => 'You can only assign yourself as the doctor.'], 403);
            }
        }
    
        try {
            DB::beginTransaction();

            // Find the appointment by ID
            $appointment = Appointment::findOrFail($id);
            Log::info("Found appointment: ", $appointment->toArray());
    
            // Check if the new appointment details are different from the existing ones
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
                ], 409); // 409 Conflict
            }
    
            // Update appointment details
            $appointment->update([
                'id_number' => $request->id_number,
                'patient_name' => $request->patient_name,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'appointment_type' => $request->appointment_type,
                'doctor_id' => $request->doctor_id,
            ]);
            Log::info("Updated appointment details for appointment ID: {$id}");
    
            // Fetch the updated doctor with the associated user
            $doctor = Doctor::with('user')->find($request->doctor_id);
            if (!$doctor || !$doctor->user) {
                Log::error("Doctor or associated user not found for doctor ID: {$request->doctor_id}");
                return response()->json(['error' => 'Doctor information is incomplete.'], 500);
            }
            Log::info("Fetched doctor: ", $doctor->toArray());
    
            // Fetch the patient user
            $patient = User::where('id_number', $request->id_number)->first();
            if (!$patient) {
                Log::error("Patient not found for id_number: {$request->id_number}");
                return response()->json(['error' => 'Patient not found.'], 404);
            }
            Log::info("Fetched patient: ", $patient->toArray());
    
            // Fetch the patient's parents
            $parents = $patient->parents()->with('user')->get();
    
            Log::info("Number of parents to notify: " . count($parents));
    
            // Only proceed if the appointment is rescheduled
            if ($isRescheduled) {
                // 1. Create and store database notifications
    
                // Notify the doctor about the rescheduled appointment
                Notification::create([
                    'user_id' => $doctor->user->id_number, // Correct reference
                    'title' => 'Appointment Rescheduled',
                    'message' => "An appointment for **{$patient->patient_name}** has been rescheduled to **{$appointment->appointment_date}** at **{$appointment->appointment_time}**.",
                    'scheduled_time' => now(),
                    'role' => $doctor->user->role,
                ]);
                Log::info("Notification created for doctor ID Number {$doctor->user->id_number}");
    
                // Notify the patient about the rescheduling
                Notification::create([
                    'user_id' => $patient->id_number, // Correct reference
                    'title' => 'Appointment Rescheduled',
                    'message' => "Your appointment has been rescheduled to **{$appointment->appointment_date}** at **{$appointment->appointment_time}** with Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                    'scheduled_time' => now(),
                    'role' => $patient->role,
                ]);
                Log::info("Notification created for patient ID Number {$patient->id_number}");
    
                // Notify the patient's parents about the rescheduling
                foreach ($parents as $parent) {
                    Notification::create([
                        'user_id' => $parent->user->id_number, // Correct reference
                        'title' => 'Child\'s Appointment Rescheduled',
                        'message' => "**{$patient->patient_name}**'s appointment has been rescheduled to **{$appointment->appointment_date}** at **{$appointment->appointment_time}** with Dr. {$doctor->user->first_name} {$doctor->user->last_name}.",
                        'scheduled_time' => now(),
                        'role' => $parent->user->role, // Correct role reference
                    ]);
                    Log::info("Notification created for parent ID Number {$parent->user->id_number}");
    
                    // 2. Send rescheduled email to parents
                    if ($parent->user->email) { // Ensure parent has an email
                        Mail::to($parent->user->email)->send(new AppointmentParentRescheduledNotification($appointment, $doctor, $user));
                        Log::info("Sent AppointmentParentRescheduledNotification email to parent: {$parent->user->email}");
                    } else {
                        Log::warning("Parent ID Number {$parent->user->id_number} does not have an email address.");
                    }
    
                    // 3. Send rescheduled email to the user (patient)
                    if ($patient->email) {
                        Mail::to($patient->email)->send(new AppointmentRescheduled($appointment, $doctor, $user));
                        Log::info("Sent AppointmentRescheduled email to patient: {$patient->email}");
                    } else {
                        Log::warning("Patient ID Number {$patient->id_number} does not have an email address.");
                    }
                }
    
                // Optionally, notify admins/nurses about the rescheduling if required
                // [Add code here if needed]
    
                Log::info("Appointment ID {$id} rescheduled successfully.");
            } else {
                Log::info("No changes detected. Appointment ID {$id} was not rescheduled.");
            }
            DB::commit(); // Commit the transaction

            return response()->json(['success' => 'Appointment updated successfully!']);
        } catch (\Exception $e) {
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
            $doctorName = trim($doctorFirstName . ' ' . $doctorLastName);
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
        // Fetch the parent's children
        $children = Parents::where('id_number', $user->id_number)->pluck('student_id');

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
            if ($appointment->doctor && $appointment->doctor->user) {
                $doctorFirstName = $appointment->doctor->user->first_name ?? '';
                $doctorLastName = $appointment->doctor->user->last_name ?? '';
                $doctorName = trim($doctorFirstName . ' ' . $doctorLastName);
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

    // Fetch doctors who are approved, have an active user, and are not booked on the selected date
    $query = Doctor::where('approved', true)
        ->whereHas('user');

    if ($excludeAppointmentId) {
        $query->whereDoesntHave('appointments', function($q) use ($date, $excludeAppointmentId) {
            $q->whereDate('appointment_date', $date)
              ->where('id', '!=', $excludeAppointmentId);
        });
    } else {
        $query->whereDoesntHave('appointments', function($q) use ($date) {
            $q->whereDate('appointment_date', $date);
        });
    }

    $availableDoctors = $query->with('user')->get();

    return response()->json([
        'available_doctors' => $availableDoctors
    ]);
}
}