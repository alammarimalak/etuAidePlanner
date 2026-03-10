<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskOccurrence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->currentUser();
        $timezone = $user->timezone ?? config('app.timezone');
        $view = $request->string('view', 'weekly')->lower()->value();

        $now = Carbon::now($timezone);

        if ($view === 'daily') {
            $start = $now->copy()->startOfDay();
            $end = $now->copy()->endOfDay();
        } elseif ($view === 'monthly') {
            $start = $now->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
            $end = $now->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        } else {
            $view = 'weekly';
            $start = $now->copy()->startOfWeek(Carbon::MONDAY);
            $end = $now->copy()->endOfWeek(Carbon::SUNDAY);
        }

        $occurrences = TaskOccurrence::query()
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereHas('task', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('task')
            ->orderBy('scheduled_at')
            ->get();

        $dueTasks = Task::query()
            ->where('user_id', $user->id)
            ->whereBetween('due_at', [$start, $end])
            ->orderBy('due_at')
            ->get();

        $days = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $days[] = $cursor->copy();
            $cursor->addDay();
        }

        $weekdays = [];
        $weekdayCursor = $start->copy()->startOfWeek(Carbon::MONDAY);
        for ($i = 0; $i < 7; $i++) {
            $weekdays[] = $weekdayCursor->copy();
            $weekdayCursor->addDay();
        }

        $occurrencesByDate = $occurrences->groupBy(function (TaskOccurrence $occurrence) {
            return $occurrence->scheduled_at->toDateString();
        });

        $tasksByDate = $dueTasks->groupBy(function (Task $task) {
            return $task->due_at->toDateString();
        });

        return view('calendar.index', [
            'currentUser' => $user,
            'view' => $view,
            'start' => $start,
            'end' => $end,
            'days' => $days,
            'weekdays' => $weekdays,
            'occurrencesByDate' => $occurrencesByDate,
            'tasksByDate' => $tasksByDate,
            'today' => $now->copy()->startOfDay(),
            'currentMonth' => $now->month,
        ]);
    }
}
