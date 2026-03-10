@extends('layouts.app')

@section('content')
    <h1>Create Task</h1>

    <div class="card">
        <form method="POST" action="{{ route('tasks.store') }}" class="form-grid">
            @csrf
            <div>
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </div>
            <div>
                <label>Priority</label>
                <select name="priority">
                    @foreach (['high', 'medium', 'low'] as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Status</label>
                <select name="status">
                    @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                        <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Category</label>
                <select name="category_id">
                    <option value="">None</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Start At</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at') }}">
            </div>
            <div>
                <label>Due At</label>
                <input type="datetime-local" name="due_at" value="{{ old('due_at') }}">
            </div>
            <div>
                <label>
                    <input type="checkbox" name="is_recurring" value="1" @checked(old('is_recurring'))>
                    Recurring Task
                </label>
            </div>
            <div>
                <label>Recurrence Rule (RRULE)</label>
                <input type="text" name="recurrence_rule" value="{{ old('recurrence_rule') }}" placeholder="FREQ=WEEKLY;BYDAY=MO">
            </div>
            <div>
                <label>Recurrence Timezone</label>
                <input type="text" name="recurrence_timezone" value="{{ old('recurrence_timezone') }}" placeholder="Africa/Casablanca">
            </div>
            <div class="actions">
                <button type="submit">Create Task</button>
                <a class="secondary" href="{{ route('tasks.index') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
