<?php
namespace App\Http\Controllers;

use App\Models\DentalRecord;
use Illuminate\Http\Request;

class DentalRecordController extends Controller
{
    public function create()
    {
        return view('student.dental-record.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'treatment' => 'required|string',
            'dentist' => 'required|string|max:255',
        ]);

        DentalRecord::create($request->all());

        return redirect()->route('student.dental-record.create')->with('success', 'Dental record created successfully!');
    }
}
