<?php
namespace App\Http\Controllers;

use App\Models\HealthExamination;
use Illuminate\Http\Request;

class HealthExaminationController extends Controller
{
    public function index()
    {
        // Fetch all pending examinations
        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        return view('admin.medical-record', compact('pendingExaminations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'health_examination_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('health_examination_picture')) {
            $path = $request->file('health_examination_picture')->store('health_examinations', 'public');
            $validated['health_examination_picture'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $validated['is_approved'] = false;

        HealthExamination::create($validated);

        return redirect()->route('admin.health-examinations.index')->with('success', 'Health Examination created successfully.');
    }

    public function approve($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->is_approved = true;
        $examination->save();

        return redirect()->route('admin.health-examinations.index')->with('success', 'Health Examination approved successfully.');
    }

    public function reject($id)
    {
        $examination = HealthExamination::findOrFail($id);
        $examination->delete();

        return redirect()->route('admin.health-examinations.index')->with('success', 'Health Examination rejected and deleted successfully.');
    }
}
