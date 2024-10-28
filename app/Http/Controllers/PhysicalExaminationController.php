<?php

namespace App\Http\Controllers;

use App\Models\PhysicalExamination;
use App\Models\Information;
use App\Models\User;
use App\Mail\NewPhysicalExaminationNotification;
use App\Models\Notification; // Import the Notification model
use App\Models\Parents; // Import the Parents model
use App\Models\Student; // Import the Parents model


use App\Mail\NewPhysicalExaminationParentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PhysicalExaminationController extends Controller
{
    public function store(Request $request)
    {
        // Log incoming request data for debugging
        Log::info('Physical Examination Store Request:', $request->all());
    
        // Validate the incoming request data
        $validatedData = $request->validate([
            'id_number' => 'required|string|max:255',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'vision' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
    
        // Automatically set md_approved to 1 (approved)
        $validatedData['md_approved'] = 1;
    
        try {
            // Check if the corresponding information record exists
            $information = Information::where('id_number', $validatedData['id_number'])->first();
            if (!$information) {
                Log::error('Information record not found for id_number: ' . $validatedData['id_number']);
                return response()->json([
                    'success' => false,
                    'message' => 'Corresponding information record not found.',
                ], 422); // Unprocessable Entity
            }
    
            // Create the Physical Examination record
            $physicalExamination = PhysicalExamination::create($validatedData);
    
            // Log success
            Log::info('Physical Examination created successfully for id_number: ' . $validatedData['id_number']);
    
            // Fetch the user (patient)
            $user = User::where('id_number', $validatedData['id_number'])->first();
            if (!$user) {
                Log::error('User not found for id_number: ' . $validatedData['id_number']);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404); // Not Found
            }
    
            // Fetch the student record with parents and their user accounts
            $student = Student::where('id_number', $validatedData['id_number'])->with('parents.user')->first();
            if (!$student) {
                Log::error('Student not found for id_number: ' . $validatedData['id_number']);
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found.',
                ], 404); // Not Found
            }
    
            // Fetch the parents of the student
            $parents = $student->parents;
    
            // Send email to the user (patient)
            Mail::to($user->email)->send(new NewPhysicalExaminationNotification($user, $physicalExamination));
    
            Log::info('Sent NewPhysicalExaminationNotification email to user: ' . $user->email);
    
            // Send emails to each parent and create notifications
            foreach ($parents as $parent) {
                // Check if the parent has an associated User and email
                if ($parent->user && $parent->user->email) {
                    // Pass the User instance to the Mailable
                    Mail::to($parent->user->email)->send(new NewPhysicalExaminationParentNotification($parent->user, $user, $physicalExamination));
                    Log::info('Sent NewPhysicalExaminationParentNotification email to parent: ' . $parent->user->email);
    
                    // Create Notification entry for parent
                    Notification::create([
                        'user_id' => $parent->id_number, // Assuming 'user_id' references 'id_number'
                        'title' => 'Child\'s Physical Examination Recorded',
                        'message' => "A new physical examination has been recorded for your child, {$user->name}. Height: {$physicalExamination->height} cm, Weight: {$physicalExamination->weight} kg, Vision: {$physicalExamination->vision}.",
                        'scheduled_time' => now(),
                        'role' => $parent->user->role, // Fetch role from associated User
                    ]);
    
                    Log::info("Notification created for parent ID Number {$parent->id_number}");
                } else {
                    // Detailed warning if User or email is missing
                    if (!$parent->user) {
                        Log::warning('No associated User found for parent ID Number: ' . $parent->id_number);
                    } elseif (!$parent->user->email) {
                        Log::warning('Parent User does not have an email address: ' . $parent->id_number);
                    }
                }
            }
    
            // Create Notification entry for user (patient)
            Notification::create([
                'user_id' => $user->id_number, // Ensure this references the correct field
                'title' => 'New Physical Examination Recorded',
                'message' => "A new physical examination has been recorded for you. Height: {$physicalExamination->height} cm, Weight: {$physicalExamination->weight} kg, Vision: {$physicalExamination->vision}.",
                'scheduled_time' => now(),
                'role' => $user->role, // Adjust if necessary
            ]);
    
            Log::info("Notification created for user ID Number {$user->id_number}");
    
            // Return JSON success response
            return response()->json([
                'success' => true,
                'message' => 'Physical Examination data saved and notifications sent successfully.',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error storing Physical Examination: ' . $e->getMessage());
    
            // Return JSON error response
            return response()->json([
                'success' => false,
                'message' => 'There was an error saving the Physical Examination. Please try again.',
            ], 500); // Internal Server Error
        }
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'vision' => 'required|string',
            'remarks' => 'nullable|string',
            'physical_exam_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        // Find the record by id
        $physicalExam = PhysicalExamination::findOrFail($id);
    
        // Update the fields
        $physicalExam->height = $request->input('height');
        $physicalExam->weight = $request->input('weight');
        $physicalExam->vision = $request->input('vision');
        $physicalExam->remarks = $request->input('remarks');
    
        // Handle picture upload
        if ($request->hasFile('physical_exam_image')) {
            $images = [];
            foreach ($request->file('physical_exam_image') as $image) {
                $path = $image->store('public/physical_examinations');
                $images[] = $path;
            }
            $physicalExam->picture = $images;
        }
    
        $physicalExam->save();
    
        return redirect()->back()->with('success', 'Physical examination updated successfully.');
    }
    
    public function edit($id)
    {
        // Find the physical examination record by ID
        $physicalExam = PhysicalExamination::findOrFail($id);
    
        // Pass the record to the view
        return view('student.medical-record', compact('physicalExam'));
    }
    
    public function approve(PhysicalExamination $physicalExamination)
    {
        $physicalExamination->md_approved = true;
        $physicalExamination->save();

        return redirect()->route('admin.medical-record.index')->with('success', 'MD\'s signature approved successfully.');
    }
    

}
