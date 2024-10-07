<?php

namespace App\Http\Controllers;

use App\Models\DentalExamination;
use App\Models\DentalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
        Log::info('Incoming request data:', $request->all());

        // Database Transaction to ensure atomicity
        DB::beginTransaction();

        try {
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

            // Function to map tooth numbers to names
            $mapTeeth = function ($toothNumbers) {
                if (!$toothNumbers) {
                    return null;
                }
                $toothNames = [];
                foreach ($toothNumbers as $number) {
                    if (isset($this->teethData[$number])) {
                        $toothNames[] = $this->teethData[$number];
                    }
                }
                return $toothNames;
            };

            // Map and store tooth selections as JSON (handled by model casting)
            $dentalExamination->sealant_tooth = $mapTeeth($request->sealant_tooth);
            $dentalExamination->filling_tooth = $mapTeeth($request->filling_tooth);
            $dentalExamination->extraction_tooth = $mapTeeth($request->extraction_tooth);
            $dentalExamination->endodontic_tooth = $mapTeeth($request->endodontic_tooth);
            $dentalExamination->radiograph_tooth = $mapTeeth($request->radiograph_tooth);
            $dentalExamination->prosthesis_tooth = $mapTeeth($request->prosthesis_tooth);

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

            // Save the Dental Examination
            $dentalExamination->save();

            Log::info('Saved dental examination:', $dentalExamination->toArray());

            DB::commit(); // Commit the transaction

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dental Examination saved successfully!',
                    'data' => $dentalExamination
                ]);
            }

            return redirect()->back()->with('success', 'Dental Examination saved successfully!');
        } catch (QueryException $e) {
            DB::rollBack(); // Roll back the transaction

            Log::error('Database Query Exception:', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return redirect()->back()->with('error', 'A database error occurred while saving the dental examination.');
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction

            Log::error('General Exception:', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return redirect()->back()->with('error', 'An unexpected error occurred while saving the dental examination.');
        }
    }
}
