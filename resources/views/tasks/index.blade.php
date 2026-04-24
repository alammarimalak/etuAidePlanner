@extends('layouts.app')

@push('styles')
    <style>
        .tasks-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: end;
        }

        .tasks-filter-field {
            flex: 1 1 180px;
            min-width: 0;
        }

        .tasks-filter-field label {
            display: block;
            margin-bottom: 6px;
        }

        .tasks-filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .tasks-filter-actions .secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.84);
            color: var(--violet-900);
        }

        .task-table-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .task-icon-action {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.84);
            color: var(--ink);
            box-shadow: none;
            padding: 0;
        }

        .task-icon-action svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .task-icon-action:hover {
            transform: translateY(-1px);
        }

        .task-icon-action.delete {
            color: #b91c1c;
        }

        body.theme-dark .tasks-filter-actions .secondary,
        body.theme-dark .task-icon-action {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 119, 255, 0.18);
            color: #eef2ff;
        }

        body.theme-dark .task-icon-action.delete {
            color: #fca5a5;
        }

        @media (max-width: 720px) {
            .tasks-filter-form {
                align-items: stretch;
            }

            .tasks-filter-actions {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <h1>Tasks</h1>

    <div class="card">
        <form method="GET" action="{{ route('tasks.index') }}" class="tasks-filter-form" data-auto-filter-form>
            <div class="tasks-filter-field">
                <label>Status</label>
                <select name="status">
                    <option value="">All</option>
                    @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="tasks-filter-field">
                <label>Priority</label>
                <select name="priority">
                    <option value="">All</option>
                    @foreach (['high', 'medium', 'low'] as $priority)
                        <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="tasks-filter-field">
                <label>Category</label>
                <select name="category_id">
                    <option value="">All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) request('category_id') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="actions tasks-filter-actions">
                <a class="secondary" href="{{ route('tasks.index') }}">Reset</a>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>
                            <span class="pill status-{{ str_replace('_', '-', $task->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="pill priority-{{ str_replace('_', '-', $task->priority) }}">
                                {{ ucfirst(str_replace('_', ' ', $task->priority)) }}
                            </span>
                        </td>
                        <td>{{ optional($task->due_at)->format('M d, Y') ?? '-' }}</td>
                        <td>
                            <div class="task-table-actions">
                                <a href="{{ route('tasks.show', $task) }}" class="task-icon-action" aria-label="View {{ $task->title }}" title="View">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 5c5.23 0 9.27 4.62 10.57 6.3a1.1 1.1 0 0 1 0 1.4C21.27 14.38 17.23 19 12 19S2.73 14.38 1.43 12.7a1.1 1.1 0 0 1 0-1.4C2.73 9.62 6.77 5 12 5Zm0 2c-3.77 0-7 3.19-8.47 5C5 13.81 8.23 17 12 17s7-3.19 8.47-5C19 10.19 15.77 7 12 7Zm0 1.75A3.25 3.25 0 1 1 8.75 12 3.25 3.25 0 0 1 12 8.75Zm0 2A1.25 1.25 0 1 0 13.25 12 1.25 1.25 0 0 0 12 10.75Z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('tasks.edit', $task) }}" class="task-icon-action" aria-label="Edit {{ $task->title }}" title="Edit">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="m15.14 3.53 5.33 5.33a1.75 1.75 0 0 1 0 2.48l-9.9 9.9a2.5 2.5 0 0 1-1.18.65l-4.53 1.13a.9.9 0 0 1-1.1-1.1l1.13-4.53a2.5 2.5 0 0 1 .65-1.18l9.9-9.9a1.75 1.75 0 0 1 2.48 0Zm-8.31 13.9-.7 2.81 2.81-.7a.5.5 0 0 0 .24-.13l8.83-8.83-2.22-2.22-8.83 8.83a.5.5 0 0 0-.13.24Zm10.36-10.48 2.22 2.22 1.06-1.06-2.22-2.22-1.06 1.06Z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <input type="hidden" name="priority" value="{{ request('priority') }}">
                                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                    <input type="hidden" name="page" value="{{ $tasks->currentPage() }}">
                                    <button type="submit" class="task-icon-action delete" aria-label="Delete {{ $task->title }}" title="Delete">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h1l.8 11.14A3 3 0 0 0 9.79 21h4.42a3 3 0 0 0 2.99-2.86L18 7h1a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9Zm5 2h-4v0h4v0Zm-5.2 2h6.4l-.78 10.99a1 1 0 0 1-1 .95H9.58a1 1 0 0 1-1-.95L7.8 7Zm1.95 2.25a1 1 0 0 1 1 1v5.5a1 1 0 1 1-2 0v-5.5a1 1 0 0 1 1-1Zm4.5 0a1 1 0 0 1 1 1v5.5a1 1 0 1 1-2 0v-5.5a1 1 0 0 1 1-1Z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $tasks->onEachSide(1)->links('partials.pagination') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterForm = document.querySelector('[data-auto-filter-form]');

            if (!filterForm) {
                return;
            }

            filterForm.querySelectorAll('select').forEach((input) => {
                input.addEventListener('change', () => filterForm.requestSubmit());
            });
        });
    </script>
@endsection
