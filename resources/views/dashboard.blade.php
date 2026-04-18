@extends('layouts.app')

@push('styles')
    <style>
        .dashboard-shell {
            display: grid;
            gap: 24px;
        }

        .dashboard-intro {
            width: 100%;
        }

        .dashboard-title {
            margin-bottom: 12px;
            font-size: clamp(2rem, 4vw, 3rem);
        }

        .dashboard-summary {
            display: grid;
            gap: 16px;
        }

        .dashboard-summary-row {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-stat {
            display: grid;
            gap: 10px;
            min-height: 150px;
            align-content: start;
        }

        .dashboard-stat-label {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(33, 86, 245, 0.08);
            color: var(--violet-900);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .dashboard-stat p {
            margin: 0;
        }

        .dashboard-stat.priority-card {
            display: grid;
            gap: 12px;
        }

        .dashboard-stat.priority-card .dashboard-priority-stats {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-stat strong {
            display: block;
            font-size: 2.2rem;
            letter-spacing: -0.05em;
        }

        .dashboard-stat h4 {
            margin: 0;
            font-size: 0.95rem;
        }

        .dashboard-actions-card {
            display: grid;
            gap: 16px;
        }

        .dashboard-actions-copy p {
            margin: 10px 0 0;
        }

        .dashboard-actions-card h3,
        .dashboard-stat h3 {
            margin-bottom: 0;
        }

        .dashboard-action-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-action-tile {
            display: grid;
            gap: 12px;
            padding: 18px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.62);
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-action-tile:hover {
            transform: translateY(-2px);
            border-color: rgba(33, 86, 245, 0.22);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 16px 26px rgba(33, 86, 245, 0.12);
        }

        .dashboard-action-tile strong,
        .dashboard-action-tile span {
            display: block;
        }

        .dashboard-action-tile span:last-child {
            color: rgba(5, 8, 22, 0.66);
            font-size: 0.94rem;
        }

        .dashboard-action-icon-shell {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: rgba(33, 86, 245, 0.1);
            color: var(--violet-500);
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

        body.theme-dark .dashboard-stat-label {
            background: rgba(139, 119, 255, 0.18);
            color: #eef2ff;
        }

        body.theme-dark .dashboard-action-tile {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(139, 119, 255, 0.14);
        }

        body.theme-dark .dashboard-action-tile:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(139, 119, 255, 0.24);
            box-shadow: 0 18px 30px rgba(0, 0, 0, 0.22);
        }

        body.theme-dark .dashboard-action-tile span:last-child {
            color: rgba(238, 242, 255, 0.7);
        }

        body.theme-dark .dashboard-action-icon-shell {
            background: rgba(139, 119, 255, 0.16);
            color: #eef2ff;
        }

        @media (max-width: 960px) {
            .dashboard-summary,
            .dashboard-stack {
                grid-template-columns: 1fr;
            }

            .dashboard-summary-row,
            .dashboard-action-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-stat.priority-card .dashboard-priority-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="dashboard-shell">
        <div class="card dashboard-intro">
            <span class="status">Student Dashboard</span>
            <h1 class="dashboard-title">Keep your semester visible and under control.</h1>
            <p class="muted">
                Track execution, review upcoming work, and stay ahead of deadlines from one focused workspace.
            </p>
        </div>

        <div class="dashboard-summary">
            <div class="dashboard-summary-row">
                <div class="card dashboard-stat">
                    <span class="dashboard-stat-label">Overview</span>
                    <h3>Tasks Done</h3>
                    <p class="muted">Finished work already behind you.</p>
                    <strong>{{ $completedCount }}</strong>
                </div>
                <div class="card dashboard-stat">
                    <span class="dashboard-stat-label">Overview</span>
                    <h3>Tasks Not Done</h3>
                    <p class="muted">Open tasks that still need attention.</p>
                    <strong>{{ $notDoneCount }}</strong>
                </div>
                <div class="card dashboard-stat">
                    <span class="dashboard-stat-label">Focus</span>
                    <h3>Urgent Tasks</h3>
                    <p class="muted">High-priority work due very soon.</p>
                    <strong>{{ $urgentTasks->count() }}</strong>
                </div>
            </div>

            <div class="dashboard-summary-row">
                <div class="card dashboard-stat">
                    <span class="dashboard-stat-label">Schedule</span>
                    <h3>Today</h3>
                    <p class="muted">Tasks that land on today.</p>
                    <strong>{{ $todayTasks->count() }}</strong>
                </div>
                <div class="card dashboard-stat">
                    <span class="dashboard-stat-label">Schedule</span>
                    <h3>Tomorrow</h3>
                    <p class="muted">What is already waiting next.</p>
                    <strong>{{ $tomorrowTasks->count() }}</strong>
                </div>
                <div class="card dashboard-stat priority-card">
                    <span class="dashboard-stat-label">Priority</span>
                    <h3>Priority Summary</h3>
                    <p class="muted">See where your workload is concentrated.</p>
                    <div class="dashboard-priority-stats">
                        <div>
                            <h4>High</h4>
                            <strong>{{ $prioritySummary['high'] }}</strong>
                        </div>
                        <div>
                            <h4>Medium</h4>
                            <strong>{{ $prioritySummary['medium'] }}</strong>
                        </div>
                        <div>
                            <h4>Low</h4>
                            <strong>{{ $prioritySummary['low'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card dashboard-actions-card">
            <div class="dashboard-actions-copy">
                <h3>Quick Actions</h3>
                <p class="muted">Jump straight into the page you need most.</p>
            </div>

            <div class="dashboard-action-grid">
                <a class="dashboard-action-tile" href="{{ route('tasks.create') }}">
                    <span class="dashboard-action-icon-shell">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M12 5a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H6a1 1 0 1 1 0-2h5V6a1 1 0 0 1 1-1Z"/>
                        </svg>
                    </span>
                    <strong>Create Task</strong>
                    <span>Add a new assignment, deadline, or study item.</span>
                </a>

                <a class="dashboard-action-tile" href="{{ route('tasks.index') }}">
                    <span class="dashboard-action-icon-shell">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M6.75 5.5A1.75 1.75 0 0 1 8.5 3.75h9A1.75 1.75 0 0 1 19.25 5.5v13A1.75 1.75 0 0 1 17.5 20.25h-9A1.75 1.75 0 0 1 6.75 18.5v-13Zm1.5 0v13a.25.25 0 0 0 .25.25h9a.25.25 0 0 0 .25-.25v-13a.25.25 0 0 0-.25-.25h-9a.25.25 0 0 0-.25.25Zm2 2.75a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.75-.75Zm0 4a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.75-.75Zm0 4a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75Z"/>
                        </svg>
                    </span>
                    <strong>View Tasks</strong>
                    <span>Open the full task list and manage progress.</span>
                </a>

                <a class="dashboard-action-tile" href="{{ route('calendar.index') }}">
                    <span class="dashboard-action-icon-shell">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v12a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1Zm12 8H5v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8ZM6 6a1 1 0 0 0-1 1v1h14V7a1 1 0 0 0-1-1H6Z"/>
                        </svg>
                    </span>
                    <strong>Open Calendar</strong>
                    <span>See deadlines and plan the week visually.</span>
                </a>

                <a class="dashboard-action-tile" href="{{ route('categories.index') }}">
                    <span class="dashboard-action-icon-shell">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M4.75 7.5A2.75 2.75 0 0 1 7.5 4.75h3.06c.73 0 1.43.29 1.94.8l.7.7c.23.24.54.37.87.37h2.43a2.75 2.75 0 0 1 2.75 2.75v7.18a2.75 2.75 0 0 1-2.75 2.75h-9A2.75 2.75 0 0 1 4.75 17.5v-10Zm2.75-1.25c-.69 0-1.25.56-1.25 1.25v10c0 .69.56 1.25 1.25 1.25h9c.69 0 1.25-.56 1.25-1.25V9.32c0-.69-.56-1.25-1.25-1.25h-2.43a3.92 3.92 0 0 1-2.12-.62l-.7-.7a1.24 1.24 0 0 0-.88-.37H7.5Z"/>
                        </svg>
                    </span>
                    <strong>Create Category</strong>
                    <span>Add or organize categories for your tasks.</span>
                </a>

                <a class="dashboard-action-tile" href="{{ route('notifications.index') }}">
                    <span class="dashboard-action-icon-shell">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M12 3.75a4.25 4.25 0 0 0-4.25 4.25v1.07c0 .67-.22 1.33-.62 1.88L5.98 12.5a1.75 1.75 0 0 0 1.42 2.75h9.2a1.75 1.75 0 0 0 1.42-2.75l-1.15-1.55a3.24 3.24 0 0 1-.62-1.88V8A4.25 4.25 0 0 0 12 3.75Zm0 17.5a2.74 2.74 0 0 1-2.58-1.84.75.75 0 1 1 1.41-.5 1.25 1.25 0 0 0 2.34 0 .75.75 0 1 1 1.41.5A2.74 2.74 0 0 1 12 21.25Z"/>
                        </svg>
                    </span>
                    <strong>View Notifications</strong>
                    <span>Check alerts, reminders, and recent updates.</span>
                </a>

                <a class="dashboard-action-tile" href="{{ route('settings.edit') }}">
                    <span class="dashboard-action-icon-shell">
                        <svg class="dashboard-action-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor" d="M10.17 4.14a1.75 1.75 0 0 1 3.66 0l.2 1.22c.1.57.48 1.04 1.01 1.24l1.14.43a1.8 1.8 0 0 0 1.56-.14l1.04-.61a1.75 1.75 0 0 1 2.59 2.59l-.61 1.04c-.3.5-.35 1.13-.14 1.66l.43 1.04c.2.53.67.92 1.24 1.01l1.22.2a1.75 1.75 0 0 1 0 3.66l-1.22.2a1.75 1.75 0 0 0-1.24 1.01l-.43 1.04c-.2.53-.15 1.16.14 1.66l.61 1.04a1.75 1.75 0 1 1-2.59 2.59l-1.04-.61a1.8 1.8 0 0 0-1.56-.14l-1.14.43a1.75 1.75 0 0 0-1.01 1.24l-.2 1.22a1.75 1.75 0 0 1-3.66 0l-.2-1.22a1.75 1.75 0 0 0-1.01-1.24l-1.14-.43a1.8 1.8 0 0 0-1.56.14l-1.04.61a1.75 1.75 0 1 1-2.59-2.59l.61-1.04c.3-.5.35-1.13.14-1.66l-.43-1.04a1.75 1.75 0 0 0-1.24-1.01l-1.22-.2a1.75 1.75 0 0 1 0-3.66l1.22-.2c.57-.1 1.04-.48 1.24-1.01l.43-1.04c.2-.53.15-1.16-.14-1.66l-.61-1.04a1.75 1.75 0 1 1 2.59-2.59l1.04.61c.5.29 1.12.35 1.66.14l1.04-.43c.53-.2.92-.67 1.01-1.24l.2-1.22ZM12 9.25A2.75 2.75 0 1 0 12 14.75 2.75 2.75 0 0 0 12 9.25Z"/>
                        </svg>
                    </span>
                    <strong>Open Settings</strong>
                    <span>Update your profile, timezone, and preferences.</span>
                </a>
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
