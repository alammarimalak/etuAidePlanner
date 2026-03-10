@extends('layouts.app')

@section('content')
    <h1>Calendar</h1>

    <div class="card">
        <div class="actions">
            <a href="{{ route('calendar.index', ['view' => 'daily']) }}">Daily</a>
            <a href="{{ route('calendar.index', ['view' => 'weekly']) }}">Weekly</a>
            <a href="{{ route('calendar.index', ['view' => 'monthly']) }}">Monthly</a>
        </div>
        <p class="muted">Showing {{ ucfirst($view) }} view from {{ $start->toFormattedDateString() }} to {{ $end->toFormattedDateString() }}.</p>
    </div>

    <div class="card">
        <h3>Scheduled Occurrences</h3>
        <ul>
            @forelse ($occurrences as $occurrence)
                <li>
                    {{ $occurrence->scheduled_at->format('M d, Y H:i') }} - {{ $occurrence->task->title }}
                    <span class="pill">{{ $occurrence->status }}</span>
                </li>
            @empty
                <li class="muted">No occurrences in this range.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <h3>Tasks with Due Dates</h3>
        <ul>
            @forelse ($dueTasks as $task)
                <li>
                    {{ $task->due_at->format('M d, Y') }} - {{ $task->title }}
                    <span class="pill">{{ $task->status }}</span>
                </li>
            @empty
                <li class="muted">No tasks due in this range.</li>
            @endforelse
        </ul>
    </div>
@endsection
