<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineIntake;
use App\Models\Notification;

class MedicineIntakeController extends Controller
{public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'intake_time' => 'required',
            'notes' => 'nullable|string|max:500',
            'id_number' => 'required|string',  // Assuming id_number is varchar
        ]);
    
        // Store the medicine intake
        $medicineIntake = new MedicineIntake();
        $medicineIntake->id_number = $request->id_number;
        $medicineIntake->medicine_name = $request->medicine_name;
        $medicineIntake->dosage = $request->dosage;
        $medicineIntake->intake_time = $request->intake_time;
        $medicineIntake->notes = $request->notes;
        $medicineIntake->save();
    
        // Create a new notification
        $notification = new Notification();
        $notification->user_id = $medicineIntake->id_number;
        $notification->title = 'Medicine Intake Recorded';
        $notification->message = "Medicine {$medicineIntake->medicine_name} has been recorded with a dosage of {$medicineIntake->dosage}.";
        $notification->role = 'Student';  // Adjust role if necessary
        $notification->is_opened = 0;  // Initially set to not opened
        $notification->scheduled_time = now(); // Optional: adjust if needed
        $notification->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Medicine intake recorded successfully!',
            'medicineIntake' => $medicineIntake
        ]);
    }
    
    
    
    // Show all medicine intake records for a specific user (id_number)
    public function showMedicalRecord($id_number)
    {
        // Fetch the medicine intake records for the given id_number
        $medicineIntakes = MedicineIntake::where('id_number', $id_number)->get();
    
        // Fetch the current authenticated user
        $user = auth()->user();
    
        // Return the medical record view with the relevant data, passing the user's role
        return view('medical-record', compact('medicineIntakes', 'user'));
    }
    

    // Optional: update an existing medicine intake record
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'intake_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Find the medicine intake record and update it
        $medicineIntake = MedicineIntake::findOrFail($id);
        $medicineIntake->update([
            'medicine_name' => $request->medicine_name,
            'dosage' => $request->dosage,
            'intake_time' => $request->intake_time,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Medicine intake record updated successfully!');
    }
}
