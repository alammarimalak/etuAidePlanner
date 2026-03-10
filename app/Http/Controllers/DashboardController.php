<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->currentUser();
        $timezone = $user->timezone ?? config('app.timezone');

        $tasks = $user->tasks()->with('category')->get();

        $completedCount = $tasks->where('status', Task::STATUS_DONE)->count();
        $notDoneCount = $tasks->where('status', '!=', Task::STATUS_DONE)->count();

        $urgentCutoff = Carbon::now($timezone)->addDays(3);
        $urgentTasks = $tasks->filter(function (Task $task) use ($urgentCutoff) {
            if ($task->priority !== Task::PRIORITY_HIGH) {
                return false;
            }

            if ($task->status === Task::STATUS_DONE) {
                return false;
            }

            if (!$task->due_at) {
                return false;
            }

            return $task->due_at->lte($urgentCutoff);
        });

        $today = Carbon::now($timezone)->startOfDay();
        $tomorrow = (clone $today)->addDay();

        $todayTasks = $tasks->filter(function (Task $task) use ($today) {
            return $task->due_at && $task->due_at->isSameDay($today);
        });

        $tomorrowTasks = $tasks->filter(function (Task $task) use ($tomorrow) {
            return $task->due_at && $task->due_at->isSameDay($tomorrow);
        });

        $prioritySummary = [
            Task::PRIORITY_HIGH => $tasks->where('priority', Task::PRIORITY_HIGH)->count(),
            Task::PRIORITY_MEDIUM => $tasks->where('priority', Task::PRIORITY_MEDIUM)->count(),
            Task::PRIORITY_LOW => $tasks->where('priority', Task::PRIORITY_LOW)->count(),
        ];

        return view('dashboard', [
            'currentUser' => $user,
            'completedCount' => $completedCount,
            'notDoneCount' => $notDoneCount,
            'urgentTasks' => $urgentTasks,
            'todayTasks' => $todayTasks,
            'tomorrowTasks' => $tomorrowTasks,
            'prioritySummary' => $prioritySummary,
        ]);
    }
}
