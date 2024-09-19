<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    // Store medical history
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|exists:medical_records,id_number',
            'medical_condition' => 'required|string',
            'surgical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',
        ]);

        MedicalHistory::create($validated);

        return redirect()->back()->with('success', 'Medical history added successfully.');
    }
   

    // Show all medical history for a user
    public function show($id_number)
    {
        $medicalRecords = MedicalRecord::where('id_number', $id_number)->with('medicalHistories')->firstOrFail();
        return view('medical_history.show', compact('medicalRecords'));
    }
}
