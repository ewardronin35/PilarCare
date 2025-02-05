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
        // Eager load 'user', 'students', and 'information' relationships
        $parents = Parents::with(['user', 'students', 'information'])->get();
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

            // Fetch all parents with their relationships
            $parents = Parents::with(['students', 'information'])->get();
            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Parents imported successfully.',
                'parents' => $parents
            ]);
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
    
        // Reload relationships to include updated data
        $parent->load(['user', 'students', 'information']);
    
        return response()->json([
            'success' => true,
            'message' => 'Parent status updated successfully.',
            'parent' => $parent
        ]);
    }


    public function enrolledParents()
    {
        // Eager load 'students' and 'information' relationships
        $parents = Parents::with(['students', 'information'])->get();
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
        $students = $parentRecords->pluck('student')->flatten();  // Flatten the collection to avoid nested collections
    
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

    public function viewChildDentalRecord(Request $request)
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
    
        // Fetch the parent record associated with the user, including students and their information
        $parentRecord = Parents::where('id_number', $user->id_number)
                                ->with(['students', 'information'])
                                ->first();
    
        if (!$parentRecord || $parentRecord->students->isEmpty()) {
            return view('parent.no-child')->with('message', 'No child associated with your account.');
        }
    
        // Collect all dental records for each student linked to this parent in a single query
        $studentIds = $parentRecord->students->pluck('id_number');
        $records = DentalRecord::whereIn('id_number', $studentIds)->get();
    
        // Determine the child to view (specific child by ID or default to the first)
        $childId = $request->input('child_id');
        $child = $childId ? $parentRecord->students->firstWhere('id', $childId) : $parentRecord->students->first();
    
        if (!$child) {
            return redirect()->back()->with('error', 'Selected child not found.');
        }
    
        // Fetch the dental record and related data for the selected child
        $dentalRecord = DentalRecord::where('id_number', $child->id_number)->first();
        $patientInfo = Information::where('id_number', $child->id_number)->first(['birthdate']);
        $latestExamination = DentalExamination::where('id_number', $child->id_number)->latest('date_of_examination')->first();
        $previousExaminations = DentalExamination::where('id_number', $child->id_number)->orderBy('date_of_examination', 'desc')->get();
        $toothHistory = $dentalRecord ? Teeth::where('dental_record_id', $dentalRecord->dental_record_id)->orderBy('tooth_number')->get() : collect();
        $teeth = $dentalRecord ? Teeth::where('dental_record_id', $dentalRecord->dental_record_id)->get() : collect();
        $nextAppointment = Appointment::where('id_number', $child->id_number)
                                        ->where('appointment_date', '>=', now())
                                        ->orderBy('appointment_date', 'asc')
                                        ->first();
        $nextExamination = DentalExamination::where('id_number', $child->id_number)->orderBy('date_of_examination', 'asc')->first();
    
        // Prepare teethData if needed (this could be populated with any additional static mapping logic)
        $teethData = [
            // ... (populate if needed)
        ];
        $dentalRecordData = [
            'personInfo' => $child,
            'patientInfo' => $patientInfo,
            'lastExamination' => $latestExamination,
            'previousExaminations' => $previousExaminations,
            'toothHistory' => $toothHistory,
            // Add any other data you need in dentalRecordData
        ];
        // Return the view with all the data needed for the dental record display
        return view('parent.dental-record', [
            'dentalRecordData' => $dentalRecordData, // Pass dentalRecordData to the view
            'personInfo' => $child,
            'dentalRecord' => $dentalRecord,
            'patientInfo' => $patientInfo,
            'personName' => "{$child->first_name} {$child->last_name}",
            'lastExamination' => $latestExamination,
            'previousExaminations' => $previousExaminations,
            'toothHistory' => $toothHistory,
            'additionalInfo' => $child->grade_or_course ?? '',
            'teeth' => $teeth,
            'user' => $user,
            'records' => $records,
            'role' => $user->role,
            'nextAppointment' => $nextAppointment,
            'teethData' => $teethData,
            'nextExamination' => $nextExamination,
        ]);
    }

    /**
     * Handle AJAX request to get dental record preview.
     */
    public function getDentalRecordPreview(Request $request)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 401);
            }
    
            $user = Auth::user();
    
            // Ensure the user is a parent
            if (strtolower($user->role) !== 'parent') {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
    
            // Validate the incoming request
            $validated = $request->validate([
                'id_number' => 'required|string|exists:dental_records,id_number'
            ]);
    
            $idNumber = $validated['id_number'];
    
            // Fetch the parent record associated with the user, including students
            $parentRecord = Parents::where('id_number', $user->id_number)
                ->with(['students'])
                ->first();
    
            if (!$parentRecord || $parentRecord->students->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No child associated with your account.'], 404);
            }
    
            // Verify that the provided id_number belongs to one of the parent's children
            $child = $parentRecord->students->where('id_number', $idNumber)->first();
    
            if (!$child) {
                return response()->json(['success' => false, 'message' => 'You are not authorized to view this dental record.'], 403);
            }
    
            // Fetch the dental record and related data for the selected child
            $dentalRecord = DentalRecord::where('id_number', $idNumber)
                ->with(['teeth', 'dentalExaminations'])
                ->first();
    
            if (!$dentalRecord) {
                return response()->json(['success' => false, 'message' => 'Dental record not found.'], 404);
            }
    
            // Fetch the next appointment separately
            $nextAppointment = Appointment::where('id_number', $idNumber)
                ->where('appointment_date', '>=', now())
                ->orderBy('appointment_date', 'asc')
                ->first();
    
            // Prepare teeth data
            $teeth = $dentalRecord->teeth;
    
            // Fetch additional data if needed
            $patientInfo = Information::where('id_number', $child->id_number)->first(['birthdate']);
            $latestExamination = DentalExamination::where('id_number', $child->id_number)
                ->latest('date_of_examination')
                ->first();
    
            // Fetch the grade_or_course directly from the child (student)
            $gradeSection = $child->grade_or_course ?? 'N/A';
    
            // Prepare the response data
            $responseData = [
                'success' => true,
                'id_number' => $dentalRecord->id_number,
                'patient_name' => $dentalRecord->patient_name,
                'grade_section' => $gradeSection,
                'birthdate' => $patientInfo->birthdate ?? 'N/A',
                'lastExamination' => $latestExamination ? [
                    'date_of_examination' => $latestExamination->date_of_examination,
                    'dentist_name' => $latestExamination->dentist_name,
                    'findings' => $latestExamination->findings
                ] : 'N/A',
                'previousExaminations' => $dentalRecord->dentalExaminations->map(function ($exam) {
                    return [
                        'date_of_examination' => $exam->date_of_examination,
                        'dentist_name' => $exam->dentist_name,
                        'findings' => $exam->findings
                    ];
                }),
                'teeth' => $teeth->map(function ($tooth) {
                    return [
                        'tooth_number' => $tooth->tooth_number,
                        'status' => $tooth->status,
                        'notes' => $tooth->notes,
                        'dental_pictures' => $tooth->dental_pictures, // Ensure this is stored as JSON or an array
                        'updated_at' => $tooth->updated_at
                    ];
                }),
                'nextAppointment' => $nextAppointment ? [
                    'appointment_date' => $nextAppointment->appointment_date,
                    'purpose' => $nextAppointment->purpose
                ] : 'N/A',
            ];
    
            return response()->json($responseData);
        } catch (\Exception $e) {
            Log::error('Error in getDentalRecordPreview: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }
    
    
    }

