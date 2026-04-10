@extends('layouts.app')

@push('styles')
    <style>
        .admin-dashboard {
            display: grid;
            gap: 24px;
        }

        .admin-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
            gap: 20px;
        }

        .admin-title {
            margin-bottom: 12px;
            font-size: clamp(2rem, 4vw, 3rem);
        }

        .admin-summary,
        .admin-panels {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .admin-stat strong {
            display: block;
            margin-bottom: 6px;
            font-size: 2rem;
            letter-spacing: -0.05em;
        }

        .admin-list {
            margin: 0;
            padding-left: 18px;
        }

        .admin-list li + li {
            margin-top: 10px;
        }

        .admin-table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .admin-table-actions form {
            display: inline;
        }

        .btn.ghost {
            background: rgba(255, 255, 255, 0.84);
            color: var(--violet-900);
            border: 1px dashed var(--line-strong);
            box-shadow: none;
        }

        @media (max-width: 960px) {
            .admin-hero,
            .admin-summary,
            .admin-panels {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="admin-dashboard">
        <div class="admin-hero">
            <div class="card">
                <span class="status">Administration</span>
                <h1 class="admin-title">Monitor student activity and intervene when momentum drops.</h1>
                <p class="muted">
                    Use this workspace to review student engagement, follow up on inactive learners, and keep your own planning tools within reach.
                </p>
                <div class="actions">
                    <a class="btn" href="{{ route('admin.students.index') }}">Manage students</a>
                    <a class="btn secondary" href="{{ route('notifications.index') }}">Open notifications</a>
                    <a class="btn ghost" href="{{ route('dashboard') }}">My dashboard</a>
                </div>
            </div>

            <div class="card">
                <h3>Administration Tools</h3>
                <ul class="admin-list">
                    <li>Review inactive students and send reminder notifications.</li>
                    <li>Resolve or dismiss alerts once a student has been handled.</li>
                    <li>Switch back to your own tasks, calendar, and settings from the same sidebar.</li>
                </ul>
            </div>
        </div>

        <div class="admin-summary">
            <div class="card admin-stat">
                <h3>Total Students</h3>
                <strong>{{ $totalStudents }}</strong>
                <p class="muted">All registered learners in the platform.</p>
            </div>
            <div class="card admin-stat">
                <h3>Active Students</h3>
                <strong>{{ $activeCount }}</strong>
                <p class="muted">Students active in the last 14 days.</p>
            </div>
            <div class="card admin-stat">
                <h3>Inactive Students</h3>
                <strong>{{ $inactiveCount }}</strong>
                <p class="muted">Learners who may need a reminder or follow-up.</p>
            </div>
        </div>

        <div class="admin-panels">
            <div class="card admin-stat">
                <h3>Tasks Completed</h3>
                <strong>{{ $completedTasks }}</strong>
                <p class="muted">Finished student tasks across the platform.</p>
            </div>
            <div class="card admin-stat">
                <h3>Tasks Pending</h3>
                <strong>{{ $pendingTasks }}</strong>
                <p class="muted">Student tasks still waiting for completion.</p>
            </div>
            <div class="card admin-stat">
                <h3>Open Alerts</h3>
                <strong>{{ $openAlerts->count() }}</strong>
                <p class="muted">Outstanding inactivity alerts assigned to you.</p>
            </div>
        </div>

        <div class="card" id="alerts">
            <h3>Alerts to Review</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($openAlerts as $alert)
                        <tr>
                            <td>{{ $alert->subjectUser?->name ?? 'Student' }}</td>
                            <td><span class="pill">{{ ucfirst($alert->status) }}</span></td>
                            <td>{{ optional($alert->created_at)->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    @if ($alert->subjectUser)
                                        <form method="POST" action="{{ route('admin.students.remind', $alert->subjectUser) }}">
                                            @csrf
                                            <button type="submit">Send Reminder</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.alerts.resolve', $alert) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="secondary">Resolve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.alerts.dismiss', $alert) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="secondary">Dismiss</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No open alerts.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card" id="inactive-students">
            <div class="actions" style="justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin-bottom: 0;">Inactive Students (14+ days)</h3>
                <a class="btn secondary" href="{{ route('admin.students.index') }}">Open full directory</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Last Login</th>
                        <th>Last Activity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inactiveStudents as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ optional($student->last_login_at)->format('M d, Y') ?? '-' }}</td>
                            <td>{{ optional($student->last_activity_at)->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <div class="admin-table-actions">
                                    <form method="POST" action="{{ route('admin.students.remind', $student) }}">
                                        @csrf
                                        <button type="submit">Send Reminder</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">No inactive students.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
