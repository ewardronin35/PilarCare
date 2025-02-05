<?php

namespace App\Http\Controllers;

use App\Models\PhysicalExamination;
use App\Models\User;
use App\Models\Notification;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Teacher;
use App\Mail\NewPhysicalExaminationNotification;
use App\Mail\NewPhysicalExaminationParentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PhysicalExaminationController extends Controller
{
    /**
     * Store a new physical examination.
     */
    public function store(Request $request)
    {
        // Log incoming request data for debugging
        Log::info('Physical Examination Store Request:', $request->all());

        // Validate the incoming request data
        $validatedData = $request->validate([
            'id_number' => 'required|string|max:255',
            'height'    => 'required|numeric',
            'weight'    => 'required|numeric',
            'vision'    => 'required|string',
            'remarks'   => 'nullable|string',
        ]);

        // Automatically set md_approved to 1 (approved)
        $validatedData['md_approved'] = 1;

        try {
            // Instead of checking a separate Information record,
            // we assume the id_number belongs to a user in our system.
            $user = User::where('id_number', $validatedData['id_number'])->first();
            if (!$user) {
                Log::error('User not found for id_number: ' . $validatedData['id_number']);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            // Create the Physical Examination record
            $physicalExamination = PhysicalExamination::create($validatedData);
            Log::info('Physical Examination created successfully for id_number: ' . $validatedData['id_number']);

            // Determine the role and load the role-specific model if needed.
            // For a student, we want to load the Student model (which in turn contains parents, etc.)
            if (strtolower($user->role) === 'student') {
                $student = Student::where('id_number', $validatedData['id_number'])
                    ->with('parents.user')
                    ->first();
                if (!$student) {
                    Log::error('Student not found for id_number: ' . $validatedData['id_number']);
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found.',
                    ], 404);
                }

                $parents = $student->parents;

                // Send email to the user (patient)
                Mail::to($user->email)->send(new NewPhysicalExaminationNotification($user, $physicalExamination));
                Log::info('Sent NewPhysicalExaminationNotification email to user: ' . $user->email);

                // Send emails to each parent and create notifications
               
            } elseif (in_array(strtolower($user->role), ['teacher', 'staff'])) {
                // For teachers and staff, send an email and create a notification
                Mail::to($user->email)->send(new NewPhysicalExaminationNotification($user, $physicalExamination));
                Log::info('Sent NewPhysicalExaminationNotification email to user: ' . $user->email);

                Notification::create([
                    'user_id'        => $user->id_number,
                    'title'          => 'New Physical Examination Recorded',
                    'message'        => "A new physical examination has been recorded for you. Height: {$physicalExamination->height} cm, Weight: {$physicalExamination->weight} kg, Vision: {$physicalExamination->vision}.",
                    'scheduled_time' => now(),
                    'role'           => $user->role,
                ]);
                Log::info("Notification created for user ID Number {$user->id_number}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Physical Examination data saved and notifications sent successfully.',
                'physicalExamination' => $physicalExamination,
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing Physical Examination: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'There was an error saving the Physical Examination. Please try again.',
            ], 500);
        }
    }

    // ... (Other methods remain unchanged or can be similarly updated to remove Information references)
}
