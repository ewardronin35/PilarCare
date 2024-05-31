<?php
// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'school_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'notification_info' => 'required|email|max:255',
            'notification_for' => 'required|string|in:student,parent,staff'
        ]);

        // Handle the notification logic (e.g., save to database, send email, etc.)

        return redirect()->route('notifications.create')->with('success', 'Notification sent successfully!');
    }
}
