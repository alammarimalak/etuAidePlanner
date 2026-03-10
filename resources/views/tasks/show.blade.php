@extends('layouts.app')

@section('content')
    <h1>{{ $task->title }}</h1>

    <div class="card">
        <p class="muted">Status: <span class="pill">{{ $task->status }}</span></p>
        <p>Priority: {{ ucfirst($task->priority) }}</p>
        <p>Due: {{ optional($task->due_at)->format('M d, Y H:i') ?? '—' }}</p>
        <p>Category: {{ $task->category?->name ?? 'None' }}</p>
        <p>Description: {{ $task->description ?? '—' }}</p>

        <div class="actions">
            <a href="{{ route('tasks.edit', $task) }}">Edit Task</a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="secondary">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h3>Subtasks</h3>
        <form method="POST" action="{{ route('tasks.subtasks.store', $task) }}" class="form-grid">
            @csrf
            <input type="text" name="title" placeholder="Subtask title" required>
            <select name="status">
                @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                    <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
            <button type="submit">Add Subtask</button>
        </form>

        <ul>
            @forelse ($task->subtasks as $subtask)
                <li>
                    <form method="POST" action="{{ route('tasks.subtasks.update', [$task, $subtask]) }}" class="actions">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="title" value="{{ $subtask->title }}">
                        <select name="status">
                            @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                                <option value="{{ $status }}" @selected($subtask->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                        <button type="submit">Save</button>
                    </form>
                    <form method="POST" action="{{ route('tasks.subtasks.destroy', [$task, $subtask]) }}" onsubmit="return confirm('Delete this subtask?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="secondary">Delete</button>
                    </form>
                </li>
            @empty
                <li class="muted">No subtasks yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <h3>Reminders</h3>
        <form method="POST" action="{{ route('tasks.reminders.store', $task) }}" class="form-grid">
            @csrf
            <label>Remind At</label>
            <input type="datetime-local" name="remind_at" required>
            <label>Channel</label>
            <select name="channel">
                <option value="email">Email</option>
                <option value="in_app">In App</option>
            </select>
            <button type="submit">Add Reminder</button>
        </form>

        <ul>
            @forelse ($task->reminders as $reminder)
                <li>
                    {{ $reminder->remind_at->format('M d, Y H:i') }} - {{ $reminder->channel }}
                    <span class="pill">{{ $reminder->status }}</span>
                    <form method="POST" action="{{ route('reminders.destroy', $reminder) }}" onsubmit="return confirm('Delete this reminder?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="secondary">Delete</button>
                    </form>
                </li>
            @empty
                <li class="muted">No reminders yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <h3>Occurrences</h3>
        <ul>
            @forelse ($task->occurrences as $occurrence)
                <li>
                    {{ $occurrence->scheduled_at->format('M d, Y H:i') }}
                    <span class="pill">{{ $occurrence->status }}</span>
                </li>
            @empty
                <li class="muted">No occurrences yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
