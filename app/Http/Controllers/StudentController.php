<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'contact_number' => 'required|string|max:12',
            'gender' => 'required|string',
            'id_number' => 'required|string|unique:students',
            'student_type' => 'required|string',
            'program' => 'nullable|string',
            'year_level' => 'nullable|string',
            'bed_type' => 'nullable|string',
            'section' => 'nullable|string',
            'grade' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'id_number' => $request->id_number,
            'student_type' => $request->student_type,
            'program' => $request->program,
            'year_level' => $request->year_level,
            'bed_type' => $request->bed_type,
            'section' => $request->section,
            'grade' => $request->grade,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Student registered successfully.');
    }

    public function storeHealthExaminationPicture(Request $request)
    {
        $request->validate([
            'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = Auth::user();
        $path = $request->file('health_examination_picture')->store('health_examination_pictures', 'public');

        $student->health_examination_picture = $path;
        $student->save();

        return redirect()->route('student.medical-record.create')->with('success', 'Health examination picture uploaded successfully.');
    }
}
