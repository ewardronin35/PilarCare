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
        
        // Fetch notifications specific to the user via id_number or their role
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
    
    public function markAsOpened(Request $request, $id)
    {
        try {
            $user = Auth::user();
    
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $notification = $user->notifications()->where('id', $id)->first();
    
            if ($notification) {
                $notification->is_opened = true; // Use boolean
                $notification->save();
    
                return response()->json(['message' => 'Notification marked as read.'], 200);
            }
    
            return response()->json(['message' => 'Notification not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Error in markAsOpened: ' . $e->getMessage());
    
            return response()->json(['message' => 'Failed to mark notification as read.'], 500);
        }
    }
    
    public function markAllAsRead(Request $request)
    {
        try {
            $user = Auth::user();
    
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $updated = Notification::where('user_id', $user->id_number)
                ->where('is_opened', false)
                ->update(['is_opened' => true]);
    
            return response()->json([
                'message' => 'All notifications marked as read.',
                'updated_count' => $updated
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error in markAllAsRead: ' . $e->getMessage());
    
            return response()->json(['message' => 'Failed to mark notifications as read.'], 500);
        }
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

    public function destroy($id)
    {
        $user = Auth::user();
    
        // Find the notification belonging to the user via id_number
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id_number)
            ->first();
    
        if ($notification) {
            $notification->delete();
    
            return response()->json([
                'message' => 'Notification deleted successfully'
            ]);
        }
    
        return response()->json([
            'message' => 'Notification not found or unauthorized.'
        ], 404);
    }
    
}
