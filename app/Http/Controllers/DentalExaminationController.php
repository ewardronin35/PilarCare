<?php

namespace App\Http\Controllers;

use App\Models\DentalExamination;
use App\Models\User;
use App\Models\DentalRecord;
use App\Models\Notitfication;
use App\Models\Teeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DentalExaminationController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Incoming request data:', $request->all());
    
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
            'sealant_tooth' => 'nullable|string',
            'filling_tooth' => 'nullable|string',
            'extraction_tooth' => 'nullable|string',
            'endodontic_tooth' => 'nullable|string',
            'radiograph_tooth' => 'nullable|string',
            'prosthesis_tooth' => 'nullable|string',
            'medical_clearance' => 'nullable|boolean',
            'other_recommendation' => 'nullable|string',
        ]);
    
        // Split the full name into first and last name
        $nameParts = explode(' ', $request->name);
        $firstname = $nameParts[0] ?? '';
        $lastname = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
    
        // If either firstname or lastname is missing, add a default value or handle it accordingly
        if (empty($firstname)) {
            $firstname = 'Unknown'; // Or handle it differently
        }
        if (empty($lastname)) {
            $lastname = 'Unknown'; // Or handle it differently
        }
        $dentalRecord = DentalRecord::where('id_number', $request->id_number)->first();
        if (!$dentalRecord) {
            Log::error('No dental record found for id_number: ' . $request->id_number);
            return redirect()->back()->with('error', 'No dental record found for the provided ID number.');
        }
    
        $dentalExamination = new DentalExamination([
            'id_number' => $request->id_number, // Set id_number
            'user_id' => auth()->id(),
            'dental_record_id' => $dentalRecord->id, // Link to dental record
            'date_of_examination' => $request->date_of_examination,
            'grade_section' => $request->grade_section,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'birthdate' => $request->birthdate,
            'age' => $request->age,
    
            'carries_free' => $request->carries_free ?? false,
            'poor_oral_hygiene' => $request->poor_oral_hygiene ?? false,
            'gum_infection' => $request->gum_infection ?? false,
            'restorable_caries' => $request->restorable_caries ?? false,
            'other_condition' => $request->other_condition,
    
            'personal_attention' => $request->personal_attention ?? false,
            'oral_prophylaxis' => $request->oral_prophylaxis ?? false,
            'fluoride_application' => $request->fluoride_application ?? false,
            'gum_treatment' => $request->gum_treatment ?? false,
            'ortho_consultation' => $request->ortho_consultation ?? false,
            'sealant_tooth' => $request->sealant_tooth,
            'filling_tooth' => $request->filling_tooth,
            'extraction_tooth' => $request->extraction_tooth,
            'endodontic_tooth' => $request->endodontic_tooth,
            'radiograph_tooth' => $request->radiograph_tooth,
            'prosthesis_tooth' => $request->prosthesis_tooth,
            'medical_clearance' => $request->medical_clearance ?? false,
            'other_recommendation' => $request->other_recommendation,
        ]);
    
        $dentalExamination->save();
    
        Log::info('Saved dental examination:', $dentalExamination->toArray());
    
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Dental Examination saved successfully!',
                'data' => $dentalExamination
            ]);
        }

        return redirect()->back()->with('success', 'Dental Examination saved successfully!');
    }
}
