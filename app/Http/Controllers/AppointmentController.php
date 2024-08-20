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
        $appointments = Appointment::where('id_number', $user->id_number)->get();
        $appointmentCount = $appointments->count(); // Count the appointments for the logged-in student
        $complaintCount = 0; // Replace with actual logic to count complaints if needed

        $role = $user->role;
        $viewPath = "{$role}.appointment";

        if (!view()->exists($viewPath)) {
            abort(404, "View for role {$role} not found");
        }

        return view($viewPath, compact('appointments', 'appointmentCount', 'complaintCount'));
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
}
