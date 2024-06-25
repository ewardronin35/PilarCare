<?php

namespace App\Http\Controllers;

use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HealthExaminationController extends Controller
{
    public function index()
    {
        // Fetch all pending examinations
        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        Log::info('Pending Examinations: ', ['count' => count($pendingExaminations)]);
        return view('admin.medical-record', compact('pendingExaminations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('health_examination_picture')) {
            $path = $request->file('health_examination_picture')->store('health_examinations', 'public');
            Log::info('Image stored at: ' . $path); // Debugging line
            $validated['health_examination_picture'] = $path;
        } else {
            Log::error('No file found'); // Debugging line
        }
    
        $validated['user_id'] = auth()->id();
        $validated['is_approved'] = false;
    
        HealthExamination::create($validated);
    
        Log::info('Health Examination created: ' . json_encode($validated)); // Debugging line
    
        return redirect()->route('student.medical-record.create')->with('success', 'Health Examination created successfully.');
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

        return response()->json(['success' => true]);
    }

    public function approve($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->is_approved = true;
        $examination->save();

        return redirect()->route('admin.medical-record.index')->with('success', 'Health Examination approved successfully.');
    }

    public function reject($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->delete();

        return redirect()->route('admin.medical-record.index')->with('success', 'Health Examination rejected and deleted successfully.');
    }
}
