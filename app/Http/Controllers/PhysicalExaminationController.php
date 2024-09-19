<?php

namespace App\Http\Controllers;

use App\Models\PhysicalExamination;
use Illuminate\Http\Request;

class PhysicalExaminationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_number' => 'required|string|max:255',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'vision' => 'required|string',
            'medicine_intake' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
    
        // Automatically set md_approved to 1 (approved) when creating the record
        $physicalExamData = $request->all();
        $physicalExamData['md_approved'] = 1; // Automatically approve the MD signature
    
        PhysicalExamination::create($physicalExamData);
    
        return redirect()->route('admin.medical-record.index')->with('success', 'Physical Examination created and MD automatically approved.');
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
