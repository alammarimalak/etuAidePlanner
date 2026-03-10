<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function index()
    {
        $admin = $this->currentUser();

        $students = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->withCount([
                'tasks as tasks_completed_count' => function ($query) {
                    $query->where('status', Task::STATUS_DONE);
                },
            ])
            ->orderBy('name')
            ->get();

        return view('admin.students.index', [
            'currentUser' => $admin,
            'students' => $students,
        ]);
    }

    public function sendReminder(Request $request, User $student)
    {
        $admin = $this->currentUser();

        if ($student->role !== User::ROLE_STUDENT) {
            abort(404);
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

        return redirect()->route('admin.students.index')->with('status', 'Reminder queued.');
    }
}
