@extends('layouts.app')

@section('content')
    <h1>Tasks</h1>

    <div class="card">
        <form method="GET" action="{{ route('tasks.index') }}" class="form-grid">
            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Priority</label>
                <select name="priority">
                    <option value="">All</option>
                    @foreach (['high', 'medium', 'low'] as $priority)
                        <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Category</label>
                <select name="category_id">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) request('category_id') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="actions">
                <button type="submit">Filter</button>
                <a class="secondary" href="{{ route('tasks.create') }}">New Task</a>
            </div>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td><span class="pill">{{ $task->status }}</span></td>
                        <td>{{ ucfirst($task->priority) }}</td>
                        <td>{{ optional($task->due_at)->format('M d, Y') ?? '—' }}</td>
                        <td class="actions">
                            <a href="{{ route('tasks.show', $task) }}">View</a>
                            <a href="{{ route('tasks.edit', $task) }}">Edit</a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="secondary">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
