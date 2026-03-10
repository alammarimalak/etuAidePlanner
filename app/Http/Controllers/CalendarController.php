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
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
        } else {
            $view = 'weekly';
            $start = $now->copy()->startOfWeek();
            $end = $now->copy()->endOfWeek();
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

        return view('calendar.index', [
            'currentUser' => $user,
            'view' => $view,
            'start' => $start,
            'end' => $end,
            'occurrences' => $occurrences,
            'dueTasks' => $dueTasks,
        ]);
    }
}
