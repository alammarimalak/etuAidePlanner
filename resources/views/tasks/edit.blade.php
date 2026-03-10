@extends('layouts.app')

@section('content')
    <h1>Edit Task</h1>

    <div class="card">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="form-grid">
            @csrf
            @method('PATCH')
            <div>
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required>
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" rows="4">{{ old('description', $task->description) }}</textarea>
            </div>
            <div>
                <label>Priority</label>
                <select name="priority">
                    @foreach (['high', 'medium', 'low'] as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', $task->priority) === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Status</label>
                <select name="status">
                    @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $task->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Category</label>
                <select name="category_id">
                    <option value="">None</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id', $task->category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Start At</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at', optional($task->start_at)->format('Y-m-d\TH:i')) }}">
            </div>
            <div>
                <label>Due At</label>
                <input type="datetime-local" name="due_at" value="{{ old('due_at', optional($task->due_at)->format('Y-m-d\TH:i')) }}">
            </div>
            <div>
                <label>
                    <input type="checkbox" name="is_recurring" value="1" @checked(old('is_recurring', $task->is_recurring))>
                    Recurring Task
                </label>
            </div>
            <div>
                <label>Recurrence Rule (RRULE)</label>
                <input type="text" name="recurrence_rule" value="{{ old('recurrence_rule', $task->recurrence_rule) }}" placeholder="FREQ=WEEKLY;BYDAY=MO">
            </div>
            <div>
                <label>Recurrence Timezone</label>
                <input type="text" name="recurrence_timezone" value="{{ old('recurrence_timezone', $task->recurrence_timezone) }}" placeholder="Africa/Casablanca">
            </div>
            <div class="actions">
                <button type="submit">Save Changes</button>
                <a class="secondary" href="{{ route('tasks.show', $task) }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
