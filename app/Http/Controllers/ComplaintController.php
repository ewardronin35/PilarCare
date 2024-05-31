<?php
namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintDetail;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('details')->get();
        return view('complaint', compact('complaints'));
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

        return redirect()->route('complaint')->with('success', 'Complaint added successfully!');
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

        return redirect()->route('complaint')->with('success', 'Complaint updated successfully!');
    }

    public function delete($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route('complaint')->with('success', 'Complaint deleted successfully!');
    }
}
