<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineIntake;
use App\Models\Notification;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;

use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Parents;
use App\Mail\MedicineIntakeReminderMail;
use App\Jobs\SendMedicineIntakeReminder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;
use Carbon\Carbon;


class MedicineIntakeController extends Controller{
    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|integer|min:1|max:10', // Adjust min and max as needed
            'intake_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'id_number' => 'required|string',
        ]);
    
        // Find the user
        $user = User::where('id_number', $request->id_number)->first();
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }
    
        // Parse dosage to determine frequency
        $dosageFrequency = $this->parseDosageFrequency($request->dosage);
    
        if (!$dosageFrequency) {
            $dosageFrequency = 1; // Default to once a day if parsing fails
        }
    
        // Set initial intake_time
        if ($request->intake_time) {
            $initialIntakeTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', now()->format('Y-m-d') . ' ' . $request->intake_time);
        } else {
            $initialIntakeTime = now();
        }
        
        $medicineIntakes = []; // To collect all medicine intake records
    
        for ($i = 0; $i < $dosageFrequency; $i++) {
            $intakeTime = \Carbon\Carbon::parse($initialIntakeTime)->addHours($i * (24 / $dosageFrequency));
    
            // Store the medicine intake
            $medicineIntake = new MedicineIntake();
            $medicineIntake->id_number = $request->id_number;
            $medicineIntake->medicine_name = $request->medicine_name;
            $medicineIntake->dosage = $request->dosage;
            $medicineIntake->intake_time = $intakeTime;
            $medicineIntake->notes = $request->notes;
            $medicineIntake->save();
    
            $medicineIntakes[] = $medicineIntake;
    
            // Schedule Notification
            $scheduledTime = $intakeTime;
    
            // Create a new notification
            $notification = new Notification();
            $notification->user_id = $user->id_number; // Use $user->id
            $notification->title = 'Medicine Intake Reminder';
            $notification->message = "It's time to take your medicine: {$medicineIntake->medicine_name}, Dosage: {$medicineIntake->dosage}.";
            $notification->role = $user->role;
            $notification->is_opened = false;
            $notification->scheduled_time = $scheduledTime;
            $notification->save();
    
            // Dispatch a job to send the notification at the scheduled time
            \App\Jobs\SendMedicineIntakeReminder::dispatch($notification, $user)->delay($scheduledTime);
    
            // Schedule Email Reminder
            \Mail::to($user->email)
            ->send(new \App\Mail\MedicineIntakeReminderMail($medicineIntake, $user));
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Medicine intake recorded successfully! Reminders have been scheduled.',
            'medicineIntakes' => $medicineIntakes
        ]);
    }
    
    /**
     * Parse the dosage string to determine the frequency.
     * @param string $dosage
     * @return int Frequency of dosage per day
     */
    private function parseDosageFrequency($dosage)
    {
        // Simple parsing logic based on common dosage phrases
        $dosage = strtolower($dosage);
    
        if (strpos($dosage, 'once a day') !== false || strpos($dosage, 'once daily') !== false || strpos($dosage, '1 time a day') !== false) {
            return 1;
        } elseif (strpos($dosage, 'twice a day') !== false || strpos($dosage, 'twice daily') !== false || strpos($dosage, '2 times a day') !== false) {
            return 2;
        } elseif (strpos($dosage, 'three times a day') !== false || strpos($dosage, '3 times a day') !== false) {
            return 3;
        } elseif (strpos($dosage, 'four times a day') !== false || strpos($dosage, '4 times a day') !== false) {
            return 4;
        } else {
            // Default frequency
            return $dosage;
        }
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
