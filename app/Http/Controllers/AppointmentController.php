<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;  // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        // Fetch all appointments for the logged-in user based on their id_number
        $appointments = Appointment::where('id_number', $user->id_number)->get();
    
        // Count total appointments
        $totalAppointments = $appointments->count();
    
        // Filter and count upcoming appointments (appointments in the future)
        $upcomingAppointments = $appointments->filter(function ($appointment) {
            return $appointment->appointment_date >= now();
        })->count();
    
        // Filter and count completed appointments (appointments in the past)
        $completedAppointments = $appointments->filter(function ($appointment) {
            return $appointment->appointment_date < now();
        })->count();
    
        // Count appointments for specific doctors
        $drIsnaniAppointments = $appointments->where('appointment_type', 'Dr. Nurmina Isnani')->count();
        $drGanAppointments = $appointments->where('appointment_type', 'Dr. Sarah Uy Gan')->count();
    
        // Placeholder for complaint count logic (if you plan to add complaints later)
        $complaintCount = 0;
    
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
            'totalAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'complaintCount',
            'drIsnaniAppointments',
            'drGanAppointments'
        ));
    }
    

    
    
    
    public function add(Request $request)
    {
    
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',  // Add validation for patient_name
        ]);
        $user = Auth::user();

        $data = [
            'id_number' => Auth::user()->id_number,
            'patient_name' => $request->patient_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'role' => Auth::user()->role,
            'appointment_type' => $request->appointment_type,
        ];
    
        Log::info('Appointment Data: ', $data);
    
        try {
            $appointment = Appointment::create($data);
            Log::info('Appointment Created: ', $appointment->toArray());
    
            return response()->json(['success' => 'Appointment scheduled successfully!']);
        } catch (\Exception $e) {
            Log::error('Error scheduling appointment: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'appointment_type' => 'required|string|max:255',
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
    $year = $request->input('year');
    $month = $request->input('month');

    $appointments = Appointment::whereYear('appointment_date', $year)
        ->whereMonth('appointment_date', $month)
        ->get();

    return response()->json($appointments);
}
public function getAppointmentsByDate(Request $request)
{
    $date = $request->input('date');

    // Fetch appointments for the logged-in user
    $appointments = Appointment::whereDate('appointment_date', $date)
        ->where('id_number', Auth::user()->id_number)
        ->get();

    // Log the appointments data to ensure it's correct
    \Log::info($appointments);

    return response()->json([
        'appointments' => $appointments
    ]);
}

}
