<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function create()
    {
        $role = strtolower(Auth::user()->role);
        return view("$role.medical-record");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'medical_illness' => 'required|string|max:255',
            'allergies' => 'required|string|max:255',
            'pediatrician' => 'required|string|max:255',
            'medicines' => 'required|array',
            'physical_examination' => 'required|array',
            'consent_signature' => 'required|string|max:255',
            'consent_date' => 'required|date',
            'contact_no' => 'required|string|max:255',
            'picture' => 'required|image|max:2048'
        ]);

        // Handle file upload
        if ($request->hasFile('picture')) {
            $filePath = $request->file('picture')->store('pictures', 'public');
        }

        MedicalRecord::create([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'age' => $request->age,
            'address' => $request->address,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'medical_illness' => $request->medical_illness,
            'allergies' => $request->allergies,
            'pediatrician' => $request->pediatrician,
            'medicines' => json_encode($request->medicines),
            'physical_examination' => json_encode($request->physical_examination),
            'consent_signature' => $request->consent_signature,
            'consent_date' => $request->consent_date,
            'contact_no' => $request->contact_no,
            'picture_path' => $filePath,
        ]);

        $role = strtolower(Auth::user()->role);
        return redirect()->route("$role.medical-record.create")->with('success', 'Medical record created successfully!');
    }
}
