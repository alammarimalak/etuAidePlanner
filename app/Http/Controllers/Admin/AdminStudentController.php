<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function index()
    {
        $admin = $this->currentUser();
        $cutoff = Carbon::now()->subDays(14);

        $students = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->withCount([
                'tasks as tasks_completed_count' => function ($query) {
                    $query->where('status', Task::STATUS_DONE);
                },
            ])
            ->orderBy('name')
            ->get();

        $recentlyActiveCount = $students->filter(function (User $student) use ($cutoff) {
            return $student->last_activity_at?->gte($cutoff) ?? false;
        })->count();

        return view('admin.students.index', [
            'currentUser' => $admin,
            'students' => $students,
            'recentlyActiveCount' => $recentlyActiveCount,
            'needsFollowUpCount' => max($students->count() - $recentlyActiveCount, 0),
            'activityCutoff' => $cutoff,
        ]);
    }

    public function sendReminder(Request $request, User $student)
    {
        $admin = $this->currentUser();

        if ($student->role !== User::ROLE_STUDENT) {
            abort(404);
        }

        if (!$student->notifications_enabled) {
            return redirect()->back()->with('status', 'Notifications are disabled for this student.');
        }

        Notification::create([
            'user_id' => $student->id,
            'type' => 'inactive_reminder',
            'title' => 'We miss you at EtuAide',
            'body' => 'It looks like you have been inactive. Log in to review your tasks and stay on track.',
            'data' => [
                'sent_by' => 'admin',
                'admin_id' => $admin->id,
            ],
        ]);

        return redirect()->back()->with('status', 'Reminder queued.');
    }
}
