<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\PhysicalExamination;
use App\Models\HealthExamination;
use App\Models\MedicalHistory;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\MedicineIntake;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PDF;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPhysicalExaminationNotification;

class MedicalRecordController extends Controller
{
    /**
     * Helper method to fetch user information based on role.
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\Student|\App\Models\Teacher|\App\Models\Staff|null
     */
    private function getUserInformation($user)
    {
        $role = strtolower($user->role);
        switch ($role) {
            case 'student':
                return Student::where('id_number', $user->id_number)->first();
            case 'teacher':
                return Teacher::where('id_number', $user->id_number)->first();
            case 'staff':
                return Staff::where('id_number', $user->id_number)->first();
            default:
                return null;
        }
    }

    /**
     * Display the medical record creation form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $role = strtolower($user->role);
        $information = $this->getUserInformation($user);
        $medicalRecord = MedicalRecord::where('name', $user->name)->first();
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get();
        $age = $information ? Carbon::parse($information->birthdate)->age : null;
        $name = trim("{$user->first_name} {$user->last_name}");
        $physicalExaminations = PhysicalExamination::where('id_number', $user->id_number)->get();
        $healthExamination = HealthExamination::where('id_number', $user->id_number)->first();
        $record = $medicalRecord ?? new MedicalRecord();

        return view("$role.medical-record", compact(
            'information',
            'medicalRecord',
            'medicalRecords',
            'physicalExaminations',
            'name',
            'age',
            'healthExamination',
            'record'
        ));
    }

    /**
     * Store a newly created medical record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'age' => 'required|integer',
                'address' => 'required|string|max:255',
                'personal_contact_number' => 'required|string|max:15',
                'emergency_contact_number' => 'required|string|max:15',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'past_illness' => 'nullable|string|max:255',
                'chronic_conditions' => 'nullable|string|max:255',
                'surgical_history' => 'nullable|string|max:255',
                'family_medical_history' => 'nullable|string|max:255',
                'allergies' => 'nullable|string|max:255',
                'medical_condition' => 'nullable|string|max:255',
                'medicines' => 'nullable|array',
                'medicines.*' => 'nullable|string|max:255',
                'health_documents' => 'nullable|array',
                'health_documents.*' => 'file|mimes:jpg,png,jpeg,pdf|max:10008',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'record_date' => 'required|date',
                'is_approved' => 'nullable|boolean',
                'is_current' => 'nullable|boolean',
            ]);

            // Check if the last medical record is not approved
            $lastMedicalRecord = MedicalRecord::where('id_number', Auth::user()->id_number)
                                              ->where('is_current', true)
                                              ->first();
            if ($lastMedicalRecord && !$lastMedicalRecord->is_approved) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot create a new medical record until the previous one is approved.'
                ], 403);
            }

            // Handle health documents upload
            $healthDocumentsPaths = [];
            if ($request->hasFile('health_documents')) {
                foreach ($request->file('health_documents') as $file) {
                    $path = $file->store('health_documents', 'public');
                    $healthDocumentsPaths[] = $path;
                }
            }

            // Handle profile picture upload and update in the respective model
            $profilePicture = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicture = $request->file('profile_picture')->store('profile_pictures', 'public');
                $information = $this->getUserInformation(Auth::user());
                if ($information) {
                    // Delete old profile picture if exists
                    if ($information->profile_picture && Storage::disk('public')->exists($information->profile_picture)) {
                        Storage::disk('public')->delete($information->profile_picture);
                    }
                    $information->update(['profile_picture' => $profilePicture]);
                }
            }

            // Mark the previous medical records as not current
            MedicalRecord::where('id_number', Auth::user()->id_number)
                ->update(['is_current' => false]);

            // Create the new medical record
            $medicalRecord = MedicalRecord::create([
                'id_number' => Auth::user()->id_number,
                'name' => $validatedData['name'],
                'birthdate' => $validatedData['birthdate'],
                'age' => $validatedData['age'],
                'address' => $validatedData['address'],
                'personal_contact_number' => $validatedData['personal_contact_number'],
                'emergency_contact_number' => $validatedData['emergency_contact_number'],
                'father_name' => $validatedData['father_name'],
                'mother_name' => $validatedData['mother_name'],
                'past_illness' => $validatedData['past_illness'] ?? null,
                'chronic_conditions' => $validatedData['chronic_conditions'] ?? null,
                'surgical_history' => $validatedData['surgical_history'] ?? null,
                'family_medical_history' => $validatedData['family_medical_history'] ?? null,
                'allergies' => $validatedData['allergies'] ?? null,
                'medical_condition' => $validatedData['medical_condition'] ?? null,
                'medicines' => $validatedData['medicines'] ?? [],
                'health_documents' => $healthDocumentsPaths,
                'profile_picture' => $profilePicture,
                'record_date' => $validatedData['record_date'],
                'is_approved' => $validatedData['is_approved'] ?? false,
                'is_current' => $validatedData['is_current'] ?? true,
                'version' => 1, // Initialize version
            ]);

            // Removed logging for production

            return response()->json([
                'success' => true,
                'message' => 'Medical record created successfully.',
                'medical_record' => $medicalRecord,
            ]);
        } catch (\Exception $e) {
            // Removed logging for production
            return response()->json([
                'success' => false,
                'message' => 'Error in creating medical record.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display a listing of the medical records based on user role.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        // Initialize variables for first and last name
        $firstName = 'N/A';
        $lastName = 'N/A';
    
        switch ($role) {
            case 'teacher':
                $teacher = $user->teacher;
                if ($teacher) {
                    $firstName = $teacher->first_name;
                    $lastName = $teacher->last_name;
                }
                break;
            case 'staff':
                $staff = $user->staff;
                if ($staff) {
                    $firstName = $staff->first_name;
                    $lastName = $staff->last_name;
                }
                break;
            case 'student':
                $student = $user->student;
                if ($student) {
                    $firstName = $student->first_name;
                    $lastName = $student->last_name;
                }
                break;
            default:
                $firstName = $user->first_name ?? 'N/A';
                $lastName = $user->last_name ?? 'N/A';
                break;
        }
    
        $name = trim("{$firstName} {$lastName}");
    
        $latestMedicalRecord = MedicalRecord::where('id_number', $user->id_number)
                                            ->where('is_current', true)
                                            ->first();
        $medicalRecord = MedicalRecord::with('medicineIntakes')
                                       ->where('id_number', $user->id_number)
                                       ->where('is_current', true)
                                       ->first();
        $medicalRecords = MedicalRecord::where('id_number', $user->id_number)->get();
        $physicalExaminations = PhysicalExamination::where('id_number', $user->id_number)->get();
        $healthExamination = HealthExamination::where('id_number', $user->id_number)->first();
        $healthExaminationPictures = HealthExamination::where('id_number', $user->id_number)
                                                ->select('school_year', 'health_examination_picture', 'xray_picture', 'lab_result_picture')
                                                ->get();
        $information = $this->getUserInformation($user);
        $record = $medicalRecord ?? new MedicalRecord();
    
        // Get historical records from the dedicated history table via the relationship
        $previousRecords = $latestMedicalRecord 
        ? $latestMedicalRecord->histories()->orderBy('created_at', 'desc')->get()
        : collect();
    
    
        return view("$role.medical-record", compact(
            'user',
            'name',
            'healthExamination',
            'medicalRecord',
            'medicalRecords',
            'physicalExaminations',
            'healthExaminationPictures',
            'latestMedicalRecord',
            'record',
            'previousRecords',
            'information'
        ));
    }
    

    /**
     * Update the specified medical record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  Medical Record ID
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $existingRecord = MedicalRecord::findOrFail($id);
    
            // Authorization: Ensure only the owner or admin can update
            if (Auth::user()->id_number !== $existingRecord->id_number && strtolower(Auth::user()->role) !== 'admin') {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
    
            // Validate incoming request data
            $validatedData = $request->validate([
                'name'                     => 'required|string|max:255',
                'birthdate'                => 'required|date',
                'age'                      => 'required|integer',
                'address'                  => 'required|string|max:255',
                'personal_contact_number'  => 'required|string|max:15',
                'emergency_contact_number' => 'required|string|max:15',
                'father_name'              => 'required|string|max:255',
                'mother_name'              => 'required|string|max:255',
                'past_illness'             => 'required|string|max:255',
                'chronic_conditions'       => 'required|string|max:255',
                'surgical_history'         => 'required|string|max:255',
                'family_medical_history'   => 'required|string|max:255',
                'medical_condition'        => 'required|string|max:255',
                'allergies'                => 'required|string|max:255',
                'medicines'                => 'required|array',
                'medicines.*'              => 'string|max:255',
                'health_documents'         => 'nullable|array',
                'health_documents.*'       => 'file|mimes:jpg,png,jpeg,pdf|max:10008',
                'profile_picture'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            // Handle profile picture upload
            $profilePicturePath = $existingRecord->profile_picture;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                $information = $this->getUserInformation($existingRecord->user);
                if ($information) {
                    if ($information->profile_picture && Storage::disk('public')->exists($information->profile_picture)) {
                        Storage::disk('public')->delete($information->profile_picture);
                    }
                    $information->update(['profile_picture' => $profilePicturePath]);
                }
            }
    
            // Handle health documents upload
            $healthDocumentsPaths = $existingRecord->health_documents ?? [];
            if ($request->hasFile('health_documents')) {
                foreach ($request->file('health_documents') as $file) {
                    $path = $file->store('health_documents', 'public');
                    $healthDocumentsPaths[] = $path;
                }
            }
    
            // Archive the existing record into the history table.
            // This assumes you have set up a MedicalHistory model and the histories() relationship.
            $existingRecord->histories()->create([
                'medical_record_id'        => $existingRecord->id_number,
                'name'                     => $existingRecord->name,
                'birthdate'                => $existingRecord->birthdate,
                'age'                      => $existingRecord->age,
                'address'                  => $existingRecord->address,
                'personal_contact_number'  => $existingRecord->personal_contact_number,
                'emergency_contact_number' => $existingRecord->emergency_contact_number,
                'father_name'              => $existingRecord->father_name,
                'mother_name'              => $existingRecord->mother_name,
                'past_illness'             => $existingRecord->past_illness,
                'chronic_conditions'       => $existingRecord->chronic_conditions,
                'surgical_history'         => $existingRecord->surgical_history,
                'family_medical_history'   => $existingRecord->family_medical_history,
                'allergies'                => $existingRecord->allergies,
                'medical_condition'        => $existingRecord->medical_condition,
                'medicines'                => $existingRecord->medicines,
                'health_documents'         => $existingRecord->health_documents,
                'profile_picture'          => $existingRecord->profile_picture,
                'is_approved'              => $existingRecord->is_approved,
                'record_date'              => $existingRecord->record_date,
            ]);
    
            // Now update the current record with the new validated data.
            $existingRecord->update([
                'name'                     => $validatedData['name'],
                'birthdate'                => $validatedData['birthdate'],
                'age'                      => $validatedData['age'],
                'address'                  => $validatedData['address'],
                'personal_contact_number'  => $validatedData['personal_contact_number'],
                'emergency_contact_number' => $validatedData['emergency_contact_number'],
                'father_name'              => $validatedData['father_name'],
                'mother_name'              => $validatedData['mother_name'],
                'past_illness'             => $validatedData['past_illness'],
                'chronic_conditions'       => $validatedData['chronic_conditions'],
                'surgical_history'         => $validatedData['surgical_history'],
                'family_medical_history'   => $validatedData['family_medical_history'],
                'allergies'                => $validatedData['allergies'],
                'medical_condition'        => $validatedData['medical_condition'],
                'medicines'                => $validatedData['medicines'],
                'health_documents'         => $healthDocumentsPaths,
                'profile_picture'          => $profilePicturePath,
            ]);
            $existingRecord->refresh(); // Refresh to get the latest data from DB if needed

    
            return response()->json([
                'success' => true,
                'message' => 'Medical record updated successfully.',
                'medical_record' => $existingRecord,
            ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the medical record.'], 500);
        }
    }
    

    /**
     * Search for medical records based on a query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        // Removed logging for production

        $medicalRecords = MedicalRecord::where('id_number', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('personal_contact_number', 'LIKE', "%{$query}%")
            ->orWhere('emergency_contact_number', 'LIKE', "%{$query}%")
            ->get();

        // Removed logging for production

        $medicalRecord = $medicalRecords->first();

        if ($medicalRecord) {
            $idNumber = $medicalRecord->id_number;
            $user = User::where('id_number', $idNumber)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                    'debug_query' => $idNumber,
                ], 404);
            }

            $information = $this->getUserInformation($user);
            $physicalExaminations = PhysicalExamination::where('id_number', $idNumber)->get();
            $healthExaminations = HealthExamination::where('id_number', $idNumber)->get();
            $medicineIntakes = MedicineIntake::where('id_number', $idNumber)->get();

            return response()->json([
                'success' => true,
                'medicalRecord' => $medicalRecord,
                'medicalRecords' => $medicalRecords,
                'information' => $information,
                'physicalExaminations' => $physicalExaminations,
                'healthExaminations' => $healthExaminations,
                'medicineIntakes' => $medicineIntakes,
                'debug_query' => $idNumber,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No records found.',
                'debug_query' => $query,
            ]);
        }
    }

    /**
     * Display medical history data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function history()
    {
        $user = Auth::user();
        $id_number = $user->id_number;
        
        // Get archived medical histories from the MedicalHistory model.
        // This assumes your MedicalRecord model has a histories() relationship
        // and that each MedicalHistory record has a 'medical_record_id' field.
        $medicalHistories = \App\Models\MedicalHistory::whereHas('medicalRecord', function ($q) use ($id_number) {
            $q->where('id_number', $id_number);
        })->orderBy('created_at', 'desc')->get();
        
        
        // Also fetch other records as needed.
        $physicalExaminations = PhysicalExamination::where('id_number', $id_number)->get();
        $healthExaminations = HealthExamination::where('id_number', $id_number)->get();
        $medicineIntakes = MedicineIntake::where('id_number', $id_number)->get();
        $information = $this->getUserInformation($user);
        
        // If you want to check for no data at all:
        if ($medicalHistories->isEmpty() && $physicalExaminations->isEmpty() && $healthExaminations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No records found.'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'medicalHistories' => $medicalHistories,  // Note the key change here.
            'physicalExaminations' => $physicalExaminations,
            'healthExaminations' => $healthExaminations,
            'medicineIntakes' => $medicineIntakes,
            'information' => $information,
        ]);
    }

    /**
     * Download a specific medical record as PDF.
     *
     * @param  int  $id  Medical Record ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadPdf($id)
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);
            $user = User::where('id_number', $medicalRecord->id_number)->first();

            if (!$user) {
                return response()->json(['error' => 'User information not found.'], 404);
            }

            $information = $this->getUserInformation($user);

            $profilePictureBase64 = null;
            if ($information && $information->profile_picture) {
                $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
                if (file_exists($profilePicturePath)) {
                    $profilePictureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($profilePicturePath));
                }
            }

            $logoPath = public_path('images/pilarLogo.jpg');
            if (!file_exists($logoPath)) {
                return response()->json(['error' => 'Logo not found.'], 404);
            }
            $logoBase64 = 'data:image/jpg;base64,' . base64_encode(file_get_contents($logoPath));

            $physicalExamination = PhysicalExamination::where('id_number', $medicalRecord->id_number)->first();
            $medicineIntakes = MedicineIntake::where('id_number', $medicalRecord->id_number)->get();

            $pdf = PDF::loadView('pdf.medical-record', [
                'medicalRecord' => $medicalRecord,
                'information' => $information,
                'physicalExamination' => $physicalExamination,
                'medicineIntakes' => $medicineIntakes,
                'profilePictureBase64' => $profilePictureBase64,
                'logoBase64' => $logoBase64
            ]);

            return $pdf->download('medical_record_' . $medicalRecord->name . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to download PDF.'], 500);
        }
    }

    /**
     * Display a specific medical record's details.
     *
     * @param  int  $id  Medical Record ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewMedicalRecord($id)
    {
        if (!in_array(strtolower(Auth::user()->role), ['admin', 'nurse', 'doctor', 'parent'])) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $record = MedicalRecord::find($id);

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Medical Record not found.'], 404);
        }

        $user = User::where('id_number', $record->id_number)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $information = $this->getUserInformation($user);
        $medicalRecords = MedicalRecord::where('id_number', $record->id_number)->get();
        $physicalExaminations = PhysicalExamination::where('id_number', $record->id_number)->get();
        $healthExaminations = HealthExamination::where('id_number', $record->id_number)->get();
        $medicineIntakes = MedicineIntake::where('id_number', $record->id_number)->get();

        $bmiData = ['dates' => [], 'bmis' => []];
        foreach ($physicalExaminations as $exam) {
            if ($exam->height > 0) {
                $bmi = $this->calculateBMI($exam->height, $exam->weight);
                $bmiData['dates'][] = $exam->created_at->format('Y-m-d');
                $bmiData['bmis'][] = $bmi;
            }
        }

        $previousRecords = MedicalHistory::where('medical_record_id', $record->id_number)
        ->orderBy('created_at', 'desc')
        ->get();
    
        $data = [
            'success' => true,
            'medicalRecord' => [
                'id_number' => $record->id_number,
                'name' => $record->name ?? 'N/A',
                'record_date' => $record->record_date ?? $record->created_at,
                'patient_name' => $record->name ?? 'N/A',
                'birthdate' => $record->birthdate ? $record->birthdate->format('Y-m-d') : 'N/A',
                'age' => $record->age ?? 'N/A',
                'address' => $record->address ?? 'N/A',
                'personal_contact_number' => $record->personal_contact_number ?? 'N/A',
                'emergency_contact_number' => $record->emergency_contact_number ?? 'N/A',
                'father_name' => $record->father_name ?? 'N/A',
                'mother_name' => $record->mother_name ?? 'N/A',
                'past_illness' => $record->past_illness ?? 'N/A',
                'chronic_conditions' => $record->chronic_conditions ?? 'N/A',
                'surgical_history' => $record->surgical_history ?? 'N/A',
                'family_medical_history' => $record->family_medical_history ?? 'N/A',
                'allergies' => $record->allergies ?? 'N/A',
                'medicines' => is_array($record->medicines) ? $record->medicines : json_decode($record->medicines, true) ?? [],
                'medical_condition' => $record->medical_condition ?? 'N/A',
                'health_documents' => $record->health_documents,
                'is_approved' => $record->is_approved ? 'Approved' : 'Pending',
            ],
            'medicalRecords' => $medicalRecords,
            'physicalExaminations' => $physicalExaminations,
            'healthExaminations' => $healthExaminations,
            'medicineIntakes' => $medicineIntakes,
            'bmiData' => $bmiData,
            'medicalHistories' => $previousRecords,
            'information' => $information,
        ];
        Log::debug('viewMedicalRecord data', [
            'record' => $record->toArray(),
            'user' => $user->toArray(),
            'information' => $information ? $information->toArray() : null,
            'medicalRecords' => $medicalRecords->toArray(),
            'physicalExaminations' => $physicalExaminations->toArray(),
            'healthExaminations' => $healthExaminations->toArray(),
            'medicineIntakes' => $medicineIntakes->toArray(),
            'bmiData' => $bmiData,
            'previousRecords' => $previousRecords->toArray(),
        ]);
        
        return response()->json($data);
    }

    /**
     * Retrieve all medical records for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllMedicalRecords(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        $allowedRoles = ['admin', 'nurse', 'doctor'];
        if (!in_array($role, $allowedRoles)) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
    
        $columns = [
            0 => 'name',
            1 => 'birthdate',
            2 => 'age',
            3 => 'address',
            4 => 'personal_contact_number',
            5 => 'emergency_contact_number',
            6 => 'father_name',
            7 => 'mother_name',
            8 => 'past_illness',
            9 => 'chronic_conditions',
            10 => 'surgical_history',
            11 => 'family_medical_history',
            12 => 'allergies',
            13 => 'medicines',
            14 => 'actions',
        ];
    
        $query = MedicalRecord::query();
    
        if ($role !== 'admin') {
            $query->where('is_current', 1);
        }
    
        $filterGradeOrCourse = $request->input('grade_or_course');
        $filterRole = strtolower($request->input('role'));
    
        if (!empty($filterGradeOrCourse)) {
            $query->whereHas('student', function($q) use ($filterGradeOrCourse) {
                $q->where('grade_or_course', $filterGradeOrCourse);
            });
        }
    
        if (!empty($filterRole)) {
            $query->whereHas('user', function($q) use ($filterRole) {
                $q->where('role', ucfirst($filterRole));
            });
        }
    
        $totalData = $query->count();
        $totalFiltered = $totalData;
    
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $order = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'name';
        $dir = $request->input('order.0.dir', 'asc');
    
        if (empty($request->input('search.value'))) {
            $medicalRecords = $query->select('medical_records.*')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
    
            $medicalRecords = $query->where(function($subQuery) use ($search) {
                    $subQuery->where('id_number', 'LIKE', "%{$search}%")
                             ->orWhere('name', 'LIKE', "%{$search}%")
                             ->orWhere('personal_contact_number', 'LIKE', "%{$search}%")
                             ->orWhere('emergency_contact_number', 'LIKE', "%{$search}%");
                })
                ->with(['student.parent'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
    
            $totalFiltered = $query->where(function($subQuery) use ($search) {
                    $subQuery->where('id_number', 'LIKE', "%{$search}%")
                             ->orWhere('name', 'LIKE', "%{$search}%")
                             ->orWhere('personal_contact_number', 'LIKE', "%{$search}%")
                             ->orWhere('emergency_contact_number', 'LIKE', "%{$search}%");
                })
                ->count();
        }
    
        $data = [];
        if (!empty($medicalRecords)) {
            foreach ($medicalRecords as $record) {
                $nestedData = [];
                $nestedData['id_number'] = $record->id_number ?? 'N/A';
                $nestedData['name'] = $record->name ?? 'N/A';
                $nestedData['birthdate'] = $record->birthdate ? $record->birthdate->format('Y-m-d') : 'N/A';
                $nestedData['age'] = $record->age ?? 'N/A';
                $nestedData['address'] = $record->address ?? 'N/A';
                $nestedData['personal_contact_number'] = $record->personal_contact_number ?? 'N/A';
                $nestedData['emergency_contact_number'] = $record->emergency_contact_number ?? 'N/A';
                $nestedData['father_name'] = $record->father_name ?? 'N/A';
                $nestedData['mother_name'] = $record->mother_name ?? 'N/A';
                $nestedData['past_illness'] = $record->past_illness ?? 'N/A';
                $nestedData['chronic_conditions'] = $record->chronic_conditions ?? 'N/A';
                $nestedData['surgical_history'] = $record->surgical_history ?? 'N/A';
                $nestedData['family_medical_history'] = $record->family_medical_history ?? 'N/A';
                $nestedData['allergies'] = $record->allergies ?? 'N/A';
                $medicines = is_array($record->medicines) ? $record->medicines : json_decode($record->medicines, true) ?? [];
                $nestedData['medicines'] = !empty($medicines) ? implode(', ', $medicines) : 'N/A';
                $nestedData['actions'] = $record->id;
                $nestedData['medical_condition'] = $record->medical_condition ?? 'N/A';
                
                $data[] = $nestedData;
            }
        }
    
        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
        ];
    
        return response()->json($json_data);
    }
    

    /**
     * Download all medical records as PDFs (if needed).
     *
     * @param  int  $id  Medical Record ID
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadPdfs($id)
    {
        if (!in_array(strtolower(Auth::user()->role), ['admin', 'nurse', 'doctor'])) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
    
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);
            $user = User::where('id_number', $medicalRecord->id_number)->first();
    
            if (!$user) {
                return response()->json(['error' => 'User information not found.'], 404);
            }
    
            $information = $this->getUserInformation($user);
    
            $profilePictureBase64 = null;
            if ($information && $information->profile_picture) {
                $profilePicturePath = storage_path('app/public/' . $information->profile_picture);
                if (file_exists($profilePicturePath)) {
                    $profilePictureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($profilePicturePath));
                }
            }
    
            $logoPath = public_path('images/pilarLogo.jpg');
            if (!file_exists($logoPath)) {
                return response()->json(['error' => 'Logo not found.'], 404);
            }
            $logoBase64 = 'data:image/jpg;base64,' . base64_encode(file_get_contents($logoPath));
    
            $physicalExamination = PhysicalExamination::where('id_number', $medicalRecord->id_number)->first();
            $medicineIntakes = MedicineIntake::where('id_number', $medicalRecord->id_number)->get();
    
            $pdf = PDF::loadView('pdf.medical-record', [
                'medicalRecord' => $medicalRecord,
                'information' => $information,
                'physicalExamination' => $physicalExamination,
                'medicineIntakes' => $medicineIntakes,
                'profilePictureBase64' => $profilePictureBase64,
                'logoBase64' => $logoBase64
            ]);
    
            return $pdf->download('medical_record_' . $medicalRecord->name . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to download PDF.'], 500);
        }
    }
    
    /**
     * Store a new physical examination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePhysicalExamination(Request $request)
    {
        $validatedData = $request->validate([
            'id_number'   => 'required|string',
            'height'      => 'required|numeric|min:1',
            'weight'      => 'required|numeric|min:1',
            'vision'      => 'required|string',
            'remarks'     => 'nullable|string',
            'md_approved' => 'required|boolean',
        ]);
    
        // Calculate BMI and add it to the data array
        $heightInMeters = $validatedData['height'] / 100;
        $bmi = $validatedData['weight'] / ($heightInMeters * $heightInMeters);
        $validatedData['bmi'] = round($bmi, 2);
    
        try {
            // Log the incoming request data
            Log::info('Physical Examination Store Request:', $request->all());
    
            // Retrieve the user by id_number
            $user = User::where('id_number', $validatedData['id_number'])->first();
            if (!$user) {
                Log::error('User not found for id_number: ' . $validatedData['id_number']);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }
    
            // Create the Physical Examination record
            $physicalExamination = PhysicalExamination::create([
                'id_number'   => $validatedData['id_number'],
                'height'      => $validatedData['height'],
                'weight'      => $validatedData['weight'],
                'vision'      => $validatedData['vision'],
                'remarks'     => $validatedData['remarks'] ?? null,
                'md_approved' => $validatedData['md_approved'],
                'bmi'         => $validatedData['bmi'],
            ]);
            Log::info('Physical Examination created successfully for id_number: ' . $validatedData['id_number']);
    
            // Determine the role and send notifications accordingly
            if (strtolower($user->role) === 'student') {
                // For a student, send an email notification directly
                Mail::to($user->email)->send(new NewPhysicalExaminationNotification($user, $physicalExamination));
                Log::info('Sent NewPhysicalExaminationNotification email to user: ' . $user->email);
            } elseif (in_array(strtolower($user->role), ['teacher', 'staff'])) {
                // For teachers and staff, send an email and create a notification
                Mail::to($user->email)->send(new NewPhysicalExaminationNotification($user, $physicalExamination));
                Log::info('Sent NewPhysicalExaminationNotification email to user: ' . $user->email);
    
                Notification::create([
                    'user_id'        => $user->id_number,
                    'title'          => 'New Physical Examination Recorded',
                    'message'        => "A new physical examination has been recorded for you. Height: {$physicalExamination->height} cm, Weight: {$physicalExamination->weight} kg, Vision: {$physicalExamination->vision}.",
                    'scheduled_time' => now(),
                    'role'           => $user->role,
                ]);
                Log::info("Notification created for user with ID Number {$user->id_number}");
            }
    
            // Refresh the model instance (if needed for updated fields like timestamps)
            $physicalExamination->refresh();
    
            return response()->json([
                'success'             => true,
                'message'             => 'Physical examination saved successfully and notifications sent.',
                'physicalExamination' => $physicalExamination,
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing Physical Examination: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'There was an error saving the Physical Examination. Please try again.',
            ], 500);
        }
    }
    
    
    /**
     * Retrieve BMI data for chart rendering.
     *
     * @param  string  $id_number
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBMIData($id_number)
    {
        $physicalExaminations = PhysicalExamination::where('id_number', $id_number)->get();
    
        $bmiData = [
            'dates' => [],
            'bmis' => []
        ];
    
        foreach ($physicalExaminations as $examination) {
            $date = $examination->created_at->format('Y-m-d');
            $heightInMeters = $examination->height / 100;
            $bmi = $examination->weight / ($heightInMeters * $heightInMeters);
    
            if ($bmi) {
                $bmiData['dates'][] = $date;
                $bmiData['bmis'][] = round($bmi, 2);
            }
        }
    
        return response()->json(['bmiData' => $bmiData]);
    }
    
    /**
     * Calculate BMI based on height and weight.
     *
     * @param  float  $height  Height in cm
     * @param  float  $weight  Weight in kg
     * @return float
     */
    private function calculateBMI($height, $weight)
    {
        if ($height == 0) {
            return 0;
        }
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        return round($bmi, 2);
    }
    
    /**
     * Display all pending medical records based on user role.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function viewAllRecords() 
    {
        $user = Auth::user();
        $role = strtolower($user->role);
    
        $allowedRoles = ['admin', 'nurse', 'doctor'];
        if (!in_array($role, $allowedRoles)) {
            abort(403, 'Unauthorized action.');
        }
    
        switch ($role) {
            case 'admin':
                $pendingMedicalRecords = MedicalRecord::where('is_approved', false)
                    ->with(['user', 'nurse', 'doctor'])
                    ->paginate(10);
                break;
            case 'nurse':
                $pendingMedicalRecords = MedicalRecord::where('is_approved', false)
                    ->where('id_number', $user->id_number)
                    ->with(['user', 'nurse', 'doctor'])
                    ->paginate(10);
                break;
            case 'doctor':
                $pendingMedicalRecords = MedicalRecord::where('is_approved', false)
                    ->where('id_number', $user->id_number)
                    ->with(['user', 'nurse', 'doctor'])
                    ->paginate(10);
                break;
            default:
                abort(403, 'Unauthorized action.');
        }
    
        return view("{$role}.uploadMedicalDocu", compact('pendingMedicalRecords'));
    }
    
    /**
     * Check the approval status of a medical record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkApprovalStatus(Request $request)
    {
        $validated = $request->validate([
            'id_number' => 'required|string|max:255',
        ]);
    
        $record = MedicalRecord::where('id_number', $validated['id_number'])->first();
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }
    
        return response()->json([
            'success' => true,
            'is_approved' => $record->is_approved
        ]);
    }
}
