<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function index()
    {
        $role = strtolower(Auth::user()->role);
        $complaints = Complaint::where('role', $role)->get();

        switch ($role) {
            case 'student':
                return view('student.complaint', compact('complaints', 'role'));
            case 'parent':
                return view('parent.complaint', compact('complaints', 'role'));
            case 'teacher':
                return view('teacher.complaint', compact('complaints', 'role'));
            case 'staff':
                return view('staff.complaint', compact('complaints', 'role'));
            case 'admin':
                $allComplaints = Complaint::all();
                $studentComplaints = $allComplaints->where('role', 'student');
                $staffComplaints = $allComplaints->where('role', 'staff');
                $parentComplaints = $allComplaints->where('role', 'parent');
                $teacherComplaints = $allComplaints->where('role', 'teacher');
                return view('admin.complaint', compact('studentComplaints', 'staffComplaints', 'parentComplaints', 'teacherComplaints'));
            default:
                abort(403, 'Unauthorized action.');
        }
    }

    public function addComplaint()
    {
        return view('admin.addcomplaint');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'birthdate' => 'required|date',
            'contact_number' => 'required|string|max:255',
            'health_history' => 'required|string',
            'pain_assessment' => 'required|integer|min:1|max:10',
            'sickness_description' => 'required|string',
            'teacher_type' => 'required_if:role,teacher|string',
            'student_type' => 'required_if:role,student|string',
            'year' => 'nullable|string',
            'section' => 'nullable|string',
            'program' => 'nullable|string',
            'grade' => 'nullable|string',
            'strand' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $complaintData = $request->all();
        $complaintData['role'] = strtolower(Auth::user()->role);

        if ($request->role === 'student') {
            if ($request->student_type === 'TED') {
                $complaintData['grade'] = null;
                $complaintData['strand'] = null;
            } elseif ($request->student_type === 'BED') {
                $complaintData['program'] = null;
                $complaintData['year'] = null;
                $complaintData['strand'] = null;
            } elseif ($request->student_type === 'SHS') {
                $complaintData['program'] = null;
                $complaintData['year'] = null;
            }
        }

        $complaint = Complaint::create($complaintData);

        return response()->json([
            'success' => true,
            'message' => 'Complaint added successfully!',
            'complaint' => $complaint
        ], 200);
        
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Completed,Not yet done',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $complaint = Complaint::findOrFail($id);
        $complaint->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $complaint->status
        ], 200);
    }

    public function show($id)
    {
        $complaint = Complaint::find($id);

        if ($complaint) {
            return response()->json($complaint);
        } else {
            return response()->json(['error' => 'Complaint not found'], 404);
        }
    }
}
