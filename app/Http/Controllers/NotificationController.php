<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Fetch notifications and unread count
    public function index()
    {
        $user = Auth::user();
        
        // Fetch notifications specific to the user or their role
        $notifications = Notification::where(function($query) use ($user) {
            $query->where('user_id', $user->id_number)
                  ->orWhere('role', $user->role);
        })->orderBy('created_at', 'desc')->get();

        // Count unread notifications directly
        $unreadCount = $notifications->where('is_opened', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    // Mark notification as read
    public function markAsOpened($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            // Update the 'is_opened' field
            $notification->is_opened = true;
            $notification->save();

            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }

    // Store a new notification
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|string|exists:users,id_number',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'scheduled_time' => 'nullable|date',
            'role' => 'nullable|string' // Validate the role input
        ]);

        // Create the notification
        Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'scheduled_time' => $request->scheduled_time ?? now(),
            'role' => $request->role,  // Store the role if provided
        ]);

        return response()->json([
            'message' => 'Notification created successfully'
        ]);
    }

    // Delete a notification
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }
}
