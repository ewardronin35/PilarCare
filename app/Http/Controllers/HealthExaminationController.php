<?php
namespace App\Http\Controllers;

use App\Models\HealthExamination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HealthExaminationController extends Controller
{
    public function index()
    {
        $healthExaminations = HealthExamination::where('user_id', auth()->id())->get();
        return view('student.upload-pictures', compact('healthExaminations'));
    }
    public function create()
    {
        $healthExamination = HealthExamination::where('user_id', auth()->id())->first();
    
        if ($healthExamination) {
            if ($healthExamination->is_approved) {
                return redirect()->route('student.medical-record.create');
            } else {
                return view('student.upload-pictures')->with('pending', true);
            }
        }
    
        return view('student.upload-pictures')->with('pending', false);
    }

    public function medicalRecord()
    {
        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        
        return view('admin.medical-record', compact('pendingExaminations'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'xray_pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lab_result_pictures.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $healthExamination = new HealthExamination();
        $healthExamination->user_id = auth()->id();
    
        if ($request->hasFile('health_examination_picture')) {
            $healthExamination->health_examination_picture = $request->file('health_examination_picture')->store('health_examinations', 'public');
        }
    
        if ($request->hasFile('xray_pictures')) {
            $xrayPaths = [];
            foreach ($request->file('xray_pictures') as $xray) {
                $xrayPaths[] = $xray->store('health_examinations', 'public');
            }
            $healthExamination->xray_picture = json_encode($xrayPaths);
        }
    
        if ($request->hasFile('lab_result_pictures')) {
            $labPaths = [];
            foreach ($request->file('lab_result_pictures') as $lab) {
                $labPaths[] = $lab->store('health_examinations', 'public');
            }
            $healthExamination->lab_result_picture = json_encode($labPaths);
        }
    
        $healthExamination->is_approved = false;
        $healthExamination->save();
    
        session(['health_examination_pending' => true]);
    
        return response()->json(['success' => true, 'message' => 'Health Examination submitted successfully and is waiting for approval.']);
    }
    
    
    
    
    public function approve($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->is_approved = true;
        $examination->save();


        return redirect()->route('admin.uploadHealthExamination')->with('success', 'Health Examination approved successfully.');
    }

    public function reject($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->delete();

        return redirect()->route('admin.uploadHealthExamination')->with('success', 'Health Examination rejected and deleted successfully.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
          'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'xray_pictures' => 'required|array|min:2|max:2', // Ensuring exactly 2 X-ray pictures
            'xray_pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
         'lab_result_pictures' => 'required|array|min:4|max:4', // Ensuring exactly 4 Lab result pictures
            'lab_result_pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $examination = HealthExamination::findOrFail($request->examination_id);

        if ($request->hasFile('health_examination_picture')) {
            $path = $request->file('health_examination_picture')->store('health_examinations', 'public');
            $examination->health_examination_picture = $path;
        }

        if ($request->hasFile('xray_picture')) {
            $path = $request->file('xray_picture')->store('health_examinations', 'public');
            $examination->xray_picture = $path;
        }

        if ($request->hasFile('lab_result_picture')) {
            $path = $request->file('lab_result_picture')->store('health_examinations', 'public');
            $examination->lab_result_picture = $path;
        }

        $examination->save();

        return response()->json(['success' => true, 'message' => 'Health Examination updated successfully.']);
    }

    public function viewAllRecords()
    {

        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        Log::info('Fetched all health examinations records.');
        return view('admin.uploadHealthExamination', compact('pendingExaminations'));
    }
    public function checkApprovalStatus()
    {
        try {
            $user = Auth::user();
            $healthExamination = HealthExamination::where('user_id', $user->id)->where('is_approved', true)->first();
    
            return response()->json([
                'is_approved' => $healthExamination ? true : false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while checking approval status.'
            ], 500);
        }
    }
    

}
