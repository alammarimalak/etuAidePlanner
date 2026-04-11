<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = $this->currentUser();

        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('notifications.index', [
            'currentUser' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, Notification $notification)
    {
        $this->authorize('view', $notification);

        $notification->update([
            'read_at' => $notification->read_at ?? now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'ok',
                'read_at' => optional($notification->fresh()->read_at)?->toIso8601String(),
            ]);
        }

        return redirect()->route('notifications.index')->with('status', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        $user = $this->currentUser();

        Notification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->route('notifications.index')->with('status', 'All notifications marked as read.');
    }
}
