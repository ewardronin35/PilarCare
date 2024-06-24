<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();
        $role = Auth::user()->role; // Get the authenticated user's role

        // Define the view path based on the user's role
        $viewPath = "{$role}/appointment";

        // Check if the view exists
        if (!view()->exists($viewPath)) {
            abort(404, "View for role {$role} not found");
        }

        return view($viewPath, compact('appointments'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        Appointment::create($request->all());

        return redirect()->route(Auth::user()->role . '.appointment')->with('success', 'Appointment scheduled successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());

        return redirect()->route(Auth::user()->role . '.appointment')->with('success', 'Appointment updated successfully!');
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route(Auth::user()->role . '.appointment')->with('success', 'Appointment deleted successfully!');
    }
}
