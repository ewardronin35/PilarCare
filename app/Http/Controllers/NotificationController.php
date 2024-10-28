<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use App\Events\NewNotification; // Ensure this event exists

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch notifications where the role matches or user_id matches
        $notifications = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id_number)
                      ->orWhere('role', $user->role);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Count unread notifications
        $unreadCount = $notifications->where('is_opened', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Store a newly created notification in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'nullable|string|exists:users,id_number',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'scheduled_time' => 'nullable|date',
            'role' => 'nullable|string|in:Admin,Student,Parent,Teacher,Staff,Nurse,Doctor', // Adjust roles as needed
        ]);

        // Create the notification
        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'scheduled_time' => $request->scheduled_time ?? now(),
            'role' => $request->role,
            'is_opened' => false,
        ]);

        // Optionally, broadcast the notification event
        if ($notification->role || $notification->user_id) {
            event(new NewNotification($notification));
        }

        // Log the creation
        Log::info("Notification created: ID {$notification->id}");

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully.',
            'notification' => $notification,
        ], 201);
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Find the notification
        $notification = Notification::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id_number)
                      ->orWhere('role', $user->role);
            })
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        // Delete the notification
        $notification->delete();

        // Log the deletion
        Log::info("Notification deleted: ID {$id}");

        return response()->json(['message' => 'Notification deleted successfully.'], 200);
    }

    /**
     * Mark the specified notification as opened.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsOpened($id)
    {
        $user = Auth::user();

        // Find the notification for the user based on user_id or role
        $notification = Notification::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id_number)
                      ->orWhere('role', $user->role);
            })
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        // Mark as opened
        $notification->is_opened = true;
        $notification->save();

        // Log the update
        Log::info("Notification marked as opened: ID {$id}");

        return response()->json(['message' => 'Notification marked as read.'], 200);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        // Update all notifications to mark them as opened
        $updated = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id_number)
                      ->orWhere('role', $user->role);
            })
            ->where('is_opened', false)
            ->update(['is_opened' => true]);

        // Log the update
        Log::info("All notifications marked as read for user ID {$user->id_number}");

        return response()->json([
            'message' => 'All notifications marked as read.',
            'updated_count' => $updated
        ], 200);
    }

    /**
     * Get the count of unread notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        $user = Auth::user();

        // Count unread notifications
        $unreadCount = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id_number)
                      ->orWhere('role', $user->role);
            })
            ->where('is_opened', false)
            ->count();

        return response()->json([
            'unreadCount' => $unreadCount,
        ], 200);
    }
}
