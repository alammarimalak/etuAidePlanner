@extends('layouts.app')

@section('content')
    <h1>Student Dashboard</h1>

    <div class="grid grid-3">
        <div class="card">
            <h3>Tasks Done</h3>
            <p>{{ $completedCount }}</p>
        </div>
        <div class="card">
            <h3>Tasks Not Done</h3>
            <p>{{ $notDoneCount }}</p>
        </div>
        <div class="card">
            <h3>Urgent Tasks</h3>
            <p>{{ $urgentTasks->count() }}</p>
        </div>
    </div>

    <div class="grid grid-3">
        <div class="card">
            <h3>Today</h3>
            <ul>
                @forelse ($todayTasks as $task)
                    <li>{{ $task->title }}</li>
                @empty
                    <li class="muted">No tasks due today.</li>
                @endforelse
            </ul>
        </div>
        <div class="card">
            <h3>Tomorrow</h3>
            <ul>
                @forelse ($tomorrowTasks as $task)
                    <li>{{ $task->title }}</li>
                @empty
                    <li class="muted">No tasks due tomorrow.</li>
                @endforelse
            </ul>
        </div>
        <div class="card">
            <h3>Priority Summary</h3>
            <ul>
                <li>High: {{ $prioritySummary['high'] }}</li>
                <li>Medium: {{ $prioritySummary['medium'] }}</li>
                <li>Low: {{ $prioritySummary['low'] }}</li>
            </ul>
        </div>
    </div>

    <div class="card">
        <h3>Urgent Tasks (High Priority, due soon)</h3>
        <ul>
            @forelse ($urgentTasks as $task)
                <li>{{ $task->title }} (due {{ optional($task->due_at)->format('M d, Y') }})</li>
            @empty
                <li class="muted">No urgent tasks.</li>
            @endforelse
        </ul>
    </div>
@endsection
