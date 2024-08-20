<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        // Fetch information from the Information table
        $information = Information::where('id_number', $user->id_number)->first();

        // Calculate the age
        $age = $information ? Carbon::parse($information->birthdate)->age : null;

        // Combine first name and last name from the user table
        $name = $user->first_name . ' ' . $user->last_name;

        return view("$role.medical-record", compact('information', 'age', 'name')); // Pass the information, age, and name to the view
    }

    public function index()
    {
        // Get the current authenticated user
        $user = Auth::user();

        // Check the role of the user and return the corresponding view
        switch ($user->role) {
            case 'Admin':
                return view('admin.medical-record'); // View for admin
            case 'Student':
                return view('student.medical-record'); // View for student
            case 'Parent':
                return view('parent.medical-record'); // View for parent
            case 'Staff':
                return view('staff.medical-record'); // View for staff
            case 'Teacher':
                return view('teacher.medical-record'); // View for teacher
            default:
                return redirect()->route('home')->with('error', 'Unauthorized access'); // Redirect for unauthorized access
        }
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
            'past_illness' => 'required|string|max:255',
            'allergies' => 'required|string|max:255',
            'chronic_conditions' => 'required|string|max:255',
            'surgical_history' => 'required|string|max:255',
            'family_medical_history' => 'required|string|max:255',
            'medicines' => 'required|array',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('profile_picture')) {
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        MedicalRecord::create([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'age' => Carbon::parse($request->birthdate)->age, // Calculate age based on birthdate
            'address' => $request->address,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'past_illness' => $request->past_illness,
            'chronic_conditions' => $request->chronic_conditions,
            'surgical_history' => $request->surgical_history,
            'family_medical_history' => $request->family_medical_history,
            'allergies' => $request->allergies,
            'medicines' => json_encode($request->medicines),
            'profile_picture' => $filePath,
        ]);

        $role = strtolower(Auth::user()->role);
        return redirect()->route("$role.medical-record.create")->with('success', 'Medical record created successfully!');
    }
}
