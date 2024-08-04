<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $appointments = Appointment::where('id_number', $user->id_number)->get();
        $appointmentCount = $appointments->count();
        
        $complaints = Complaint::where('id_number', $user->id_number)->get();
        $complaintCount = $complaints->count();

        return view('student.StudentDashboard', compact('appointments', 'appointmentCount', 'complaints', 'complaintCount'));
    }
}
