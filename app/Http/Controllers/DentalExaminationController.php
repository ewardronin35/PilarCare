<?php

namespace App\Http\Controllers;

use App\Models\DentalExamination;
use App\Models\DentalRecord;
use App\Models\User;
use App\Models\Notification;
use App\Models\Parents;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewDentalExaminationNotification;
use App\Mail\NewDentalExaminationParentNotification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF; // Import SnappyPDF Facade


class DentalExaminationController extends Controller
{
    // Define the teeth mapping as a private property
    private $teethData = [
        11 => 'Upper Right Central Incisor',
        12 => 'Upper Right Lateral Incisor',
        13 => 'Upper Right Canine',
        14 => 'Upper Right First Premolar',
        15 => 'Upper Right Second Premolar',
        16 => 'Upper Right First Molar',
        17 => 'Upper Right Second Molar',
        18 => 'Upper Right Third Molar',
        21 => 'Upper Left Central Incisor',
        22 => 'Upper Left Lateral Incisor',
        23 => 'Upper Left Canine',
        24 => 'Upper Left First Premolar',
        25 => 'Upper Left Second Premolar',
        26 => 'Upper Left First Molar',
        27 => 'Upper Left Second Molar',
        28 => 'Upper Left Third Molar',
        31 => 'Lower Left Central Incisor',
        32 => 'Lower Left Lateral Incisor',
        33 => 'Lower Left Canine',
        34 => 'Lower Left First Premolar',
        35 => 'Lower Left Second Premolar',
        36 => 'Lower Left First Molar',
        37 => 'Lower Left Second Molar',
        38 => 'Lower Left Third Molar',
        41 => 'Lower Right Central Incisor',
        42 => 'Lower Right Lateral Incisor',
        43 => 'Lower Right Canine',
        44 => 'Lower Right First Premolar',
        45 => 'Lower Right Second Premolar',
        46 => 'Lower Right First Molar',
        47 => 'Lower Right Second Molar',
        48 => 'Lower Right Third Molar'
    ];

    // Constructor with middleware (optional)

    // Show the form to create a new Dental Examination
    public function create()
    {
        return view('dental_examinations.create', [
            'teethData' => $this->teethData
        ]);
    }

    // Store the Dental Examination data
    public function store(Request $request)
    {
        Log::info('Incoming Dental Examination request data:', $request->all());

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Preprocess the request data to remove nulls from arrays
            $input = $request->all();

            $arrayFields = [
                'sealant_tooth',
                'filling_tooth',
                'extraction_tooth',
                'endodontic_tooth',
                'radiograph_tooth',
                'prosthesis_tooth'
            ];

            foreach ($arrayFields as $field) {
                if (isset($input[$field]) && is_array($input[$field])) {
                    // Remove nulls and non-integer values
                    $input[$field] = array_filter($input[$field], function ($value) {
                        return is_numeric($value);
                    });

                    // Reindex the array to prevent gaps
                    $input[$field] = array_values($input[$field]);
                }
            }

            // Update the request with the sanitized input
            $request->merge($input);

            // Validate the data
            $validatedData = $request->validate([
                'id_number' => 'required|string',
                'date_of_examination' => 'required|date',
                'grade_section' => 'required|string',
                'name' => 'required|string',
                'birthdate' => 'required|date',
                'age' => 'required|integer',

                'carries_free' => 'nullable|boolean',
                'poor_oral_hygiene' => 'nullable|boolean',
                'gum_infection' => 'nullable|boolean',
                'restorable_caries' => 'nullable|boolean',
                'other_condition' => 'nullable|string',

                'personal_attention' => 'nullable|boolean',
                'oral_prophylaxis' => 'nullable|boolean',
                'fluoride_application' => 'nullable|boolean',
                'gum_treatment' => 'nullable|boolean',
                'ortho_consultation' => 'nullable|boolean',

                // Validation rules to accept arrays for tooth selections
                'sealant_tooth' => 'nullable|array',
                'sealant_tooth.*' => 'integer|in:' . implode(',', array_keys($this->teethData)),

                'filling_tooth' => 'nullable|array',
                'filling_tooth.*' => 'integer|in:' . implode(',', array_keys($this->teethData)),

                'extraction_tooth' => 'nullable|array',
                'extraction_tooth.*' => 'integer|in:' . implode(',', array_keys($this->teethData)),

                'endodontic_tooth' => 'nullable|array',
                'endodontic_tooth.*' => 'integer|in:' . implode(',', array_keys($this->teethData)),

                'radiograph_tooth' => 'nullable|array',
                'radiograph_tooth.*' => 'integer|in:' . implode(',', array_keys($this->teethData)),

                'prosthesis_tooth' => 'nullable|array',
                'prosthesis_tooth.*' => 'integer|in:' . implode(',', array_keys($this->teethData)),

                'medical_clearance' => 'nullable|boolean',
                'other_recommendation' => 'nullable|string',
            ]);

            // Split the full name into first and last name (Patient's Name)
            $nameParts = explode(' ', $request->name);
            $firstname = $nameParts[0] ?? 'Unknown';
            $lastname = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : 'Unknown';

            // Fetch the dental record
            $dentalRecord = DentalRecord::where('id_number', $request->id_number)->first();
            if (!$dentalRecord) {
                Log::error('No dental record found for id_number: ' . $request->id_number);
                return redirect()->back()->with('error', 'No dental record found for the provided ID number.');
            }

            // Create a new DentalExamination instance
            $dentalExamination = new DentalExamination();
            $dentalExamination->id_number = $request->id_number;
            $dentalExamination->dental_record_id = $dentalRecord->dental_record_id; // Ensure correct field
            $dentalExamination->date_of_examination = $request->date_of_examination;
            $dentalExamination->grade_section = $request->grade_section;
            $dentalExamination->lastname = $lastname;
            $dentalExamination->firstname = $firstname;
            $dentalExamination->birthdate = $request->birthdate;
            $dentalExamination->age = $request->age;

            $dentalExamination->carries_free = $request->carries_free ?? false;
            $dentalExamination->poor_oral_hygiene = $request->poor_oral_hygiene ?? false;
            $dentalExamination->gum_infection = $request->gum_infection ?? false;
            $dentalExamination->restorable_caries = $request->restorable_caries ?? false;
            $dentalExamination->other_condition = $request->other_condition;

            $dentalExamination->personal_attention = $request->personal_attention ?? false;
            $dentalExamination->oral_prophylaxis = $request->oral_prophylaxis ?? false;
            $dentalExamination->fluoride_application = $request->fluoride_application ?? false;
            $dentalExamination->gum_treatment = $request->gum_treatment ?? false;
            $dentalExamination->ortho_consultation = $request->ortho_consultation ?? false;

            // Map and store tooth selections as arrays (handled by model casting)
            $dentalExamination->sealant_tooth = $request->sealant_tooth;
            $dentalExamination->filling_tooth = $request->filling_tooth;
            $dentalExamination->extraction_tooth = $request->extraction_tooth;
            $dentalExamination->endodontic_tooth = $request->endodontic_tooth;
            $dentalExamination->radiograph_tooth = $request->radiograph_tooth;
            $dentalExamination->prosthesis_tooth = $request->prosthesis_tooth;

            $dentalExamination->medical_clearance = $request->medical_clearance ?? false;
            $dentalExamination->other_recommendation = $request->other_recommendation;

            // **Set the dentist_name based on authenticated user's name**
            $dentist = auth()->user();

            // Log the authenticated user's details for debugging
            Log::info('Authenticated Dentist:', [
                'first_name' => $dentist->first_name ?? 'N/A',
                'last_name' => $dentist->last_name ?? 'N/A',
                'name' => $dentist->name ?? 'N/A',
            ]);

            // Assign the dentist_name based on available attributes
            if (!empty($dentist->first_name) && !empty($dentist->last_name)) {
                $dentalExamination->dentist_name = $dentist->first_name . ' ' . $dentist->last_name;
            } elseif (!empty($dentist->name)) {
                $dentalExamination->dentist_name = $dentist->name;
            } else {
                $dentalExamination->dentist_name = 'Unknown Dentist';
                Log::warning('Authenticated user does not have first_name, last_name, or name. Assigned "Unknown Dentist".');
            }
            $dentalExamination->is_downloaded = 0;

            // Save the Dental Examination
            $dentalExamination->save();

            Log::info('Saved dental examination:', $dentalExamination->toArray());

            // **Send Emails and Create Notifications**

            // Fetch the user (patient)
            $user = User::where('id_number', $request->id_number)->first();
            if (!$user) {
                Log::error('User not found for id_number: ' . $request->id_number);
                DB::rollBack();
                return redirect()->back()->with('error', 'User not found.');
            }

            // Fetch the parents of the user
            $parents = $user->parents; // Ensure this relationship is correctly defined

            // **Check for User Role**
            // Assuming you have a roles table and a relationship defined
            if (!$user->role) {
                Log::error('No matching role for id_number: ' . $user->id_number);
                DB::rollBack();
                return redirect()->back()->with('error', 'User role not defined.');
            }

            // **Send email to the user (patient)**
            Mail::to($user->email)->send(new NewDentalExaminationNotification($user, $dentalExamination, $this->teethData));

            Log::info('Sent NewDentalExaminationNotification email to user: ' . $user->email);

            // **Create Notification entry for user (patient)**
            Notification::create([
                'user_id' => $user->id_number, // Ensure this references the correct field
                'title' => 'New Dental Examination Recorded',
                'message' => "A new dental examination has been recorded for you.",
                'scheduled_time' => now(),
                'role' => $user->role, // Fetch role from User
            ]);

            Log::info("Notification created for user ID Number {$user->id_number}");

            // **Send emails to each parent and create notifications**
            foreach ($parents as $parent) {
                // Check if the parent has an associated User and email
                if ($parent->user && $parent->user->email) {
                    // Pass the User instance to the Mailable
                    Mail::to($parent->user->email)->send(new NewDentalExaminationParentNotification($parent->user, $user, $dentalExamination, $this->teethData));
                    Log::info('Sent NewDentalExaminationParentNotification email to parent: ' . $parent->user->email);

                    // Create Notification entry for parent
                    Notification::create([
                        'user_id' => $parent->id_number, // Assuming 'user_id' references 'id_number'
                        'title' => 'Child\'s Dental Examination Recorded',
                        'message' => "A new dental examination has been recorded for your child, {$user->first_name} {$user->last_name}.",
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

            // Commit the transaction
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dental Examination saved successfully!',
                    'data' => $dentalExamination
                ]);
            }

            return redirect()->back()->with('success', 'Dental Examination saved successfully!');
        }

        catch (ValidationException $e) {
            // Roll back the transaction
            DB::rollBack();

            // Log the validation errors
            Log::error('Validation Exception:', [
                'errors' => $e->errors(),
            ]);

            // Return with validation errors
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        catch (QueryException $e) {
            DB::rollBack(); // Roll back the transaction

            Log::error('Database Query Exception:', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return redirect()->back()->with('error', 'A database error occurred while saving the dental examination.');
        }
        catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction

            Log::error('General Exception:', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return redirect()->back()->with('error', 'An unexpected error occurred while saving the dental examination.');
        }
    }

}
