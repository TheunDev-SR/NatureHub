<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = auth()->user();
        $notifications = Messages::where('receiver_id', $user->id)
            ->where('read', 0) 
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        Messages::where('receiver_id', $user->id)
            ->where('read', 0)
            ->orderByDesc('created_at')
            ->update(['read' => 1]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
