@extends('layouts.app')

@push('styles')
    <style>
        .dashboard-shell {
            display: grid;
            gap: 24px;
        }

        .dashboard-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
            gap: 20px;
        }

        .dashboard-title {
            margin-bottom: 12px;
            font-size: clamp(2rem, 4vw, 3rem);
        }

        .dashboard-summary {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-stat strong {
            display: block;
            margin-bottom: 6px;
            font-size: 2rem;
            letter-spacing: -0.05em;
        }

        .dashboard-stack {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-list {
            margin: 0;
            padding-left: 18px;
        }

        .dashboard-list li + li {
            margin-top: 10px;
        }

        .dashboard-hero-actions .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .dashboard-action-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        @media (max-width: 960px) {
            .dashboard-hero,
            .dashboard-summary,
            .dashboard-stack {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="dashboard-shell">
        <div class="dashboard-hero">
            <div class="card">
                <span class="status">Student Dashboard</span>
                <h1 class="dashboard-title">Keep your semester visible and under control.</h1>
                <p class="muted">
                    Track execution, review upcoming work, and stay ahead of deadlines from one focused workspace.
                </p>
                <div class="actions dashboard-hero-actions">
                    <a class="btn" href="{{ route('tasks.create') }}">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M12 5a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H6a1 1 0 1 1 0-2h5V6a1 1 0 0 1 1-1Z"/>
                        </svg>
                        <span>Create task</span>
                    </a>
                    <a class="btn secondary" href="{{ route('calendar.index') }}">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1Zm12 8H5v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8ZM6 6a1 1 0 0 0-1 1v1h14V7a1 1 0 0 0-1-1H6Z"/>
                        </svg>
                        <span>Open calendar</span>
                    </a>
                </div>
            </div>

            <div class="card">
                <h3>Priority Summary</h3>
                <ul class="dashboard-list">
                    <li>High: {{ $prioritySummary['high'] }}</li>
                    <li>Medium: {{ $prioritySummary['medium'] }}</li>
                    <li>Low: {{ $prioritySummary['low'] }}</li>
                </ul>
            </div>
        </div>

        <div class="dashboard-summary">
            <div class="card dashboard-stat">
                <h3>Tasks Done</h3>
                <strong>{{ $completedCount }}</strong>
                <p class="muted">Completed work across your active plan.</p>
            </div>
            <div class="card dashboard-stat">
                <h3>Tasks Not Done</h3>
                <strong>{{ $notDoneCount }}</strong>
                <p class="muted">Items still waiting for progress or review.</p>
            </div>
            <div class="card dashboard-stat">
                <h3>Urgent Tasks</h3>
                <strong>{{ $urgentTasks->count() }}</strong>
                <p class="muted">High-priority work due soon.</p>
            </div>
        </div>

        <div class="dashboard-stack">
            <div class="card">
                <h3>Today</h3>
                <ul class="dashboard-list">
                    @forelse ($todayTasks as $task)
                        <li>{{ $task->title }}</li>
                    @empty
                        <li class="muted">No tasks due today.</li>
                    @endforelse
                </ul>
            </div>

            <div class="card">
                <h3>Tomorrow</h3>
                <ul class="dashboard-list">
                    @forelse ($tomorrowTasks as $task)
                        <li>{{ $task->title }}</li>
                    @empty
                        <li class="muted">No tasks due tomorrow.</li>
                    @endforelse
                </ul>
            </div>

            <div class="card">
                <h3>Urgent Tasks</h3>
                <ul class="dashboard-list">
                    @forelse ($urgentTasks as $task)
                        <li>{{ $task->title }} (due {{ optional($task->due_at)->format('M d, Y') }})</li>
                    @empty
                        <li class="muted">No urgent tasks.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>
@endsection
