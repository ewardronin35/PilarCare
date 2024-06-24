<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $appointmentCount = Appointment::count();
        $complaintCount = Complaint::count();
        $inventoryCount = Inventory::count();

        return view('admin.AdminDashboard', compact('appointmentCount', 'complaintCount', 'inventoryCount'));
        
    }
}
