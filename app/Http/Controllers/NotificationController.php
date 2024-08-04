<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::user()->id_number)->get();
        \Log::info('Fetched notifications: ', $notifications->toArray());

        return response()->json(['notifications' => $notifications]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string|exists:users,id_number',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'scheduled_time' => 'nullable|date'
        ]);

        Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'scheduled_time' => $request->scheduled_time ?? now()
        ]);

        return response()->json([
            'message' => 'Notification created successfully'
        ]);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }
}
