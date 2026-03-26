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
        $requestedDate = $request->string('date')->value();

        $now = Carbon::now($timezone);
        $focusDate = $now->copy();

        if ($requestedDate) {
            try {
                $focusDate = Carbon::createFromFormat('Y-m-d', $requestedDate, $timezone);
            } catch (\Throwable $exception) {
                $focusDate = $now->copy();
            }
        }

        if ($view === 'daily') {
            $start = $focusDate->copy()->startOfDay();
            $end = $focusDate->copy()->endOfDay();
        } elseif ($view === 'monthly') {
            $start = $focusDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
            $end = $focusDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        } else {
            $view = 'weekly';
            $start = $focusDate->copy()->startOfWeek(Carbon::MONDAY);
            $end = $focusDate->copy()->endOfWeek(Carbon::SUNDAY);
        }

        $occurrences = TaskOccurrence::query()
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereHas('task', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('task.category')
            ->orderBy('scheduled_at')
            ->get();

        $dueTasks = Task::query()
            ->where('user_id', $user->id)
            ->whereBetween('due_at', [$start, $end])
            ->with('category')
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

        $eventsByDate = collect($days)
            ->mapWithKeys(function (Carbon $day) use ($occurrencesByDate, $tasksByDate) {
                $dateKey = $day->toDateString();

                $occurrenceEvents = $occurrencesByDate->get($dateKey, collect())
                    ->map(function (TaskOccurrence $occurrence) {
                        return [
                            'id' => $occurrence->id,
                            'type' => 'occurrence',
                            'title' => $occurrence->task->title,
                            'status' => $occurrence->status,
                            'priority' => $occurrence->task->priority,
                            'category' => $occurrence->task->category?->name,
                            'category_color' => $occurrence->task->category?->color,
                            'datetime' => $occurrence->scheduled_at,
                            'time' => $occurrence->scheduled_at->format('H:i'),
                            'meta' => 'Scheduled task',
                            'edit_url' => route('tasks.edit', $occurrence->task),
                        ];
                    });

                $taskEvents = $tasksByDate->get($dateKey, collect())
                    ->map(function (Task $task) {
                        return [
                            'id' => $task->id,
                            'type' => 'task',
                            'title' => $task->title,
                            'status' => $task->status,
                            'priority' => $task->priority,
                            'category' => $task->category?->name,
                            'category_color' => $task->category?->color,
                            'datetime' => $task->due_at,
                            'time' => $task->due_at->format('H:i'),
                            'meta' => 'Due task',
                            'edit_url' => route('tasks.edit', $task),
                        ];
                    });

                return [$dateKey => $occurrenceEvents
                    ->concat($taskEvents)
                    ->sortBy('datetime')
                    ->values()];
            });

        $rangeLabel = match ($view) {
            'daily' => $focusDate->isoFormat('dddd, MMMM D'),
            'monthly' => $focusDate->isoFormat('MMMM YYYY'),
            default => sprintf('%s - %s', $start->isoFormat('MMM D'), $end->isoFormat('MMM D, YYYY')),
        };

        $previousDate = match ($view) {
            'daily' => $focusDate->copy()->subDay(),
            'monthly' => $focusDate->copy()->subMonthNoOverflow(),
            default => $focusDate->copy()->subWeek(),
        };

        $nextDate = match ($view) {
            'daily' => $focusDate->copy()->addDay(),
            'monthly' => $focusDate->copy()->addMonthNoOverflow(),
            default => $focusDate->copy()->addWeek(),
        };

        return view('calendar.index', [
            'currentUser' => $user,
            'view' => $view,
            'focusDate' => $focusDate,
            'start' => $start,
            'end' => $end,
            'days' => $days,
            'weekdays' => $weekdays,
            'eventsByDate' => $eventsByDate,
            'today' => $now->copy()->startOfDay(),
            'currentMonth' => $focusDate->month,
            'rangeLabel' => $rangeLabel,
            'previousDate' => $previousDate->toDateString(),
            'nextDate' => $nextDate->toDateString(),
            'todayDate' => $now->toDateString(),
        ]);
    }
}
