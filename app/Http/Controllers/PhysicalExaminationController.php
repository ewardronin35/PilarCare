<?php

namespace App\Http\Controllers;

use App\Models\PhysicalExamination;
use App\Models\Information;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            PhysicalExamination::create($validatedData);

            // Log success
            Log::info('Physical Examination created successfully for id_number: ' . $validatedData['id_number']);

            // Return JSON success response
            return response()->json([
                'success' => true,
                'message' => 'Physical Examination data saved successfully.',
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
