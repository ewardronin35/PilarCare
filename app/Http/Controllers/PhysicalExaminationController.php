<?php

namespace App\Http\Controllers;

use App\Models\PhysicalExamination;
use Illuminate\Http\Request;

class PhysicalExaminationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'vision' => 'required|string',
            'medicine_intake' => 'nullable|string',
            'remarks' => 'nullable|string',
            'md_approved' => 'sometimes|boolean', // Validate MD approval status
        ]);

        PhysicalExamination::create($request->all());

        return redirect()->route('admin.medical-record.index')->with('success', 'Physical Examination created successfully.');
    }

    public function update(Request $request, PhysicalExamination $physicalExamination)
    {
        $request->validate([
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'vision' => 'required|string',
            'medicine_intake' => 'nullable|string',
            'remarks' => 'nullable|string',
            'md_approved' => 'sometimes|boolean', // Validate MD approval status
        ]);

        $physicalExamination->update($request->all());

        return redirect()->route('admin.medical-record.index')->with('success', 'Physical Examination updated successfully.');
    }

    public function approve(PhysicalExamination $physicalExamination)
    {
        $physicalExamination->md_approved = true;
        $physicalExamination->save();

        return redirect()->route('admin.medical-record.index')->with('success', 'MD\'s signature approved successfully.');
    }
}
