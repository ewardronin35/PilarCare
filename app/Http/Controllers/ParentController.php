<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parents;
use App\Models\User;
use App\Models\Information;
use App\Models\MedicalRecord;

use App\Models\PhysicalExamination;
use App\Models\HealthExamination;
use App\Models\DentalRecord;
use App\Models\DentalExamination;
use App\Models\Teeth;
use App\Models\Appointment;


use App\Imports\ParentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ParentController extends Controller
{
    public function showUploadForm()
    {
        // Eager load 'user', 'student', and 'information' relationships
        $parents = Parents::with(['user', 'student', 'information'])->get();
        Log::info('Parents:', $parents->toArray());
        return view('admin.enrolledparents', compact('parents'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new ParentImport;
            Excel::import($import, $request->file('file'));

            $parents = Parents::with(['user', 'student', 'information'])->get();
            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Parents imported successfully.', 'parents' => $parents]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing parents: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the parents.']]);
        }
    }

    public function toggleApproval(Request $request, $id)
    {
        $parent = Parents::findOrFail($id);
        $parent->approved = $request->input('approved');
        $parent->save();

        // Update the related user's approval status
        $user = User::where('id_number', $parent->id_number)->first();
        if ($user) {
            $user->approved = $parent->approved;
            $user->save();
        }

        // Reload relationships
        $parent->load(['user', 'student', 'information']);

        return response()->json(['success' => true, 'message' => 'Parent status updated successfully.', 'parent' => $parent]);
    }

    public function enrolledParents()
    {
        // Eager load 'student' and 'information' relationships
        $parents = Parents::with(['student', 'information'])->get();
        return response()->json($parents);
    }

    public function downloadParents()
    {
        $filePath = 'templates/parents_template.xlsx';

        if (Storage::exists($filePath)) {
            try {
                $fileSize = Storage::size($filePath);
                Log::info('File size: ' . $fileSize);
            } catch (\Exception $e) {
                Log::error('Error retrieving file size: ' . $e->getMessage());
            }
        } else {
            Log::error('File not found: ' . $filePath);
            return response()->json(['success' => false, 'message' => 'Template file not found.'], 404);
        }
        
        // If file exists, proceed with download
        return Storage::download($filePath, 'parents_template.xlsx');
    }
    
    public function getDuplicates()
    {
        return $this->duplicates;
    }
    public function viewMedicalRecords()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view medical records.');
        }
    
        $user = Auth::user();
    
        // Fetch the parent record(s) associated with the user
        $parentRecords = Parents::where('id_number', $user->id_number)
                                ->with(['student', 'information'])
                                ->get();
    
        if ($parentRecords->isEmpty()) {
            return view('parent.no-children')->with('message', 'No children associated with your account.');
        }
    
        // Collect all students linked to the parent
        $students = $parentRecords->pluck('student');
    
        // Fetch medical records for each student
        $medicalData = [];
        foreach ($students as $student) {
            if ($student) {
                $latestMedicalRecord = MedicalRecord::where('id_number', $student->id_number)
                    ->latest('created_at') // Get the latest medical record
                    ->first();
    
                $medicalData[] = [
                    'student' => $student,
                    'information' => Information::where('id_number', $student->id_number)->first(),
                    'latestMedicalRecord' => $latestMedicalRecord, // Pass the latest medical record
                    'medicalRecords' => MedicalRecord::where('id_number', $student->id_number)->with(['medicineIntakes'])->get(),
                    'physicalExaminations' => PhysicalExamination::where('id_number', $student->id_number)->get(),
                    'healthExaminations' => HealthExamination::where('id_number', $student->id_number)->get(),
                ];
            }
        }
    
        // Pass the collected data to a Blade view
        return view('parent.medical-record', compact('medicalData'));
    }
    public function viewChildDentalRecord()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view the dental record.');
        }
    
        $user = Auth::user();
    
        // Ensure the user is a parent
        if (strtolower($user->role) !== 'parent') {
            abort(403, 'Unauthorized action.');
        }
    
        // Fetch the parent record associated with the user
        $parentRecord = Parents::where('id_number', $user->id_number)
                                ->with(['student', 'information'])
                                ->first();
    
        if (!$parentRecord || !$parentRecord->student) {
            return view('parent.no-child')->with('message', 'No child associated with your account.');
        }
    
        $child = $parentRecord->student;
    
        // Fetch the dental record for the child
        $dentalRecord = DentalRecord::where('id_number', $child->id_number)->first();
    
        if (!$dentalRecord) {
            // Return a view indicating no dental record found
            return view('parent.no-dental-record', ['child' => $child]);
        }
    
        // Fetch personInfo
        $personInfo = $child;
    
        // Fetch patientInfo
        $patientInfo = Information::where('id_number', $child->id_number)->first(['birthdate']);
    
        // Fetch last examination
        $latestExamination = DentalExamination::where('id_number', $child->id_number)
            ->orderBy('date_of_examination', 'desc')
            ->first();
    
        // Fetch previous examinations
        $previousExaminations = DentalExamination::where('id_number', $child->id_number)
            ->orderBy('date_of_examination', 'desc')
            ->get();
    
        // Fetch tooth history
        $toothHistory = Teeth::where('dental_record_id', $dentalRecord->dental_record_id)
            ->orderBy('tooth_number')
            ->get();
    
        // Fetch teeth
        $teeth = Teeth::where('dental_record_id', $dentalRecord->dental_record_id)->get();
    
        // Fetch next appointment
        $nextAppointment = Appointment::where('id_number', $child->id_number)
            ->where('appointment_date', '>=', now())
            ->orderBy('appointment_date', 'asc')
            ->first();
    
        // Fetch next examination
        $nextExamination = DentalExamination::where('id_number', $child->id_number)
            ->orderBy('date_of_examination', 'asc')
            ->first();
    
        // Prepare teethData
        $teethData = [
            // ... (teeth data)
        ];
    
        return view('parent.dental-record', [
            'personInfo' => $personInfo,
            'dentalRecord' => $dentalRecord,
            'patientInfo' => $patientInfo,
            'personName' => $personInfo->first_name . ' ' . $personInfo->last_name,
            'lastExamination' => $latestExamination,
            'previousExaminations' => $previousExaminations,
            'toothHistory' => $toothHistory,
            'additionalInfo' => $personInfo->grade_or_course ?? '',
            'teeth' => $teeth,
            'user' => $user,
            'role' => $user->role,
            'nextAppointment' => $nextAppointment,
            'teethData' => $teethData,
            'nextExamination' => $nextExamination,
        ]);
    }
}