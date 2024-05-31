<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::all();
        return view('appointment', compact('appointments'));
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

        return redirect()->route('appointment')->with('success', 'Appointment scheduled successfully!');
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

        return redirect()->route('appointment')->with('success', 'Appointment updated successfully!');
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointment')->with('success', 'Appointment deleted successfully!');
    }
}
