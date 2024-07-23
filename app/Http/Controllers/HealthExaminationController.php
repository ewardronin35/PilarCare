<?php
namespace App\Http\Controllers;

use App\Models\HealthExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HealthExaminationController extends Controller
{
    public function create()
    {
        return view('student.upload-pictures');
    }

    public function medicalRecord()
    {
        $pendingExaminations = HealthExamination::where('is_approved', false)->with('user')->get();
        Log::info('Fetched Pending Examinations for Medical Record:', ['pendingExaminations' => $pendingExaminations]);
        return view('admin.medical-record', compact('pendingExaminations'));
    }

    public function index()
    {
        $role = auth()->user()->role;
        $viewPath = "{$role}.medical-record";

        if (!view()->exists($viewPath)) {
            abort(404, "View for role {$role} not found");
        }

        $healthExaminations = HealthExamination::where('user_id', auth()->id())->get();

        return view($viewPath, compact('healthExaminations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pictures' => 'required|array|size:3',
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $paths = [];
        foreach ($request->file('pictures') as $index => $file) {
            $path = $file->store('health_examinations', 'public');
            $paths[] = $path;
            Log::info('Uploaded picture ' . $index . ' stored at: ' . $path);
        }

        $healthExamination = new HealthExamination([
            'user_id' => auth()->id(),
            'health_examination_picture' => $paths[0],
            'xray_picture' => $paths[1],
            'lab_result_picture' => $paths[2],
            'is_approved' => false,
        ]);

        $healthExamination->save();

        Log::info('Health Examination created: ', $healthExamination->toArray());

        return response()->json(['success' => true, 'message' => 'Health Examination created successfully.']);
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
