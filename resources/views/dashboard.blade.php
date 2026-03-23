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
                <div class="actions">
                    <a class="btn" href="{{ route('tasks.create') }}">Create task</a>
                    <a class="btn secondary" href="{{ route('calendar.index') }}">Open calendar</a>
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
