<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Inventory;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $appointmentCount = Appointment::count();
        $complaintCount = Complaint::count();
        $inventoryCount = Inventory::count();
        
        // Fetch low stock notifications
        $notifications = Notification::where('user_id', 'admin')->get();

        return view('admin.AdminDashboard', compact('appointmentCount', 'complaintCount', 'inventoryCount', 'notifications'));
    }
}
