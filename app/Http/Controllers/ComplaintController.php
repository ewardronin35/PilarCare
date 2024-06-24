<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('details')->get();
        $role = strtolower(Auth::user()->role); // Get the authenticated user's role and convert to lowercase

        // Filter complaints based on role
        $studentComplaints = $complaints->where('role', 'student');
        $staffComplaints = $complaints->where('role', 'staff');
        $parentComplaints = $complaints->where('role', 'parent');
        $teacherComplaints = $complaints->where('role', 'teacher');

        // Define the view path based on the user's role
        $viewPath = "{$role}/complaint";

        // Check if the view exists
        if (!view()->exists($viewPath)) {
            abort(404, "View for role {$role} not found");
        }

        return view($viewPath, compact('studentComplaints', 'staffComplaints', 'parentComplaints', 'teacherComplaints', 'role'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year_and_section' => 'required|string|max:255',
            'age' => 'required|integer',
            'birthdate' => 'required|date',
            'contact_number' => 'required|string|max:255',
            'health_history' => 'required|string',
        ]);

        $complaint = Complaint::create($request->all());

        return redirect()->route('complaint.index')->with('success', 'Complaint added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year_and_section' => 'required|string|max:255',
            'age' => 'required|integer',
            'birthdate' => 'required|date',
            'contact_number' => 'required|string|max:255',
            'health_history' => 'required|string',
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->update($request->all());

        return redirect()->route('complaint.index')->with('success', 'Complaint updated successfully!');
    }

    public function delete($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route('complaint.index')->with('success', 'Complaint deleted successfully!');
    }
}
