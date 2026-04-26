@extends('layouts.app')

@push('styles')
    <style>
        .admin-students {
            display: grid;
            gap: 24px;
        }

        .admin-students-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .admin-students-summary {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .admin-students-stat strong {
            display: block;
            margin-bottom: 6px;
            font-size: 2rem;
            letter-spacing: -0.05em;
        }

        .admin-students-header .actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-students-table-shell {
            width: 100%;
        }

        .admin-students-table {
            width: 100%;
        }

        .admin-students-table-label {
            display: none;
            margin-bottom: 6px;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(5, 8, 22, 0.48);
        }

        .admin-students-table td:last-child {
            width: 1%;
            white-space: nowrap;
        }

        .admin-students-table-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .admin-students-table-actions form {
            display: inline;
        }

        body.theme-dark .admin-students-table-label {
            color: rgba(238, 242, 255, 0.66);
        }

        @media (max-width: 960px) {
            .admin-students-summary {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 720px) {
            .admin-students {
                gap: 18px;
            }

            .admin-students-header .actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .admin-students-header .actions .btn {
                width: 100%;
            }

            .admin-students-table,
            .admin-students-table tbody,
            .admin-students-table tr,
            .admin-students-table td {
                display: block;
                width: 100%;
            }

            .admin-students-table thead {
                display: none;
            }

            .admin-students-table tbody {
                display: grid;
                gap: 14px;
            }

            .admin-students-table tr {
                padding: 16px;
                border: 1px solid var(--line);
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.62);
            }

            .admin-students-table td {
                padding: 0;
                border-bottom: none;
                white-space: normal;
            }

            .admin-students-table td + td {
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid var(--line);
            }

            .admin-students-table td:last-child {
                width: auto;
            }

            .admin-students-table-label {
                display: block;
            }

            .admin-students-table-actions {
                display: grid;
                grid-template-columns: 1fr;
            }

            .admin-students-table-actions form,
            .admin-students-table-actions button {
                width: 100%;
            }

            body.theme-dark .admin-students-table tr {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(139, 119, 255, 0.16);
            }

            body.theme-dark .admin-students-table td + td {
                border-top-color: rgba(139, 119, 255, 0.14);
            }
        }
    </style>
@endpush

@section('content')
    <section class="admin-students">
        <div class="admin-students-header">
            <div>
                <span class="status">Student Directory</span>
                <h1 style="margin-bottom: 8px;">Students</h1>
                <p class="muted" style="margin: 0;">
                    Review student activity, completed work, and send reminders when engagement slows down.
                </p>
            </div>

            <div class="actions">
                <a class="btn" href="{{ route('admin.students.email.create') }}">Create an email</a>
                <a class="btn secondary" href="{{ route('admin.dashboard') }}">Back to admin overview</a>
            </div>
        </div>

        <div class="admin-students-summary">
            <div class="card admin-students-stat">
                <h3>Total Students</h3>
                <strong>{{ $students->count() }}</strong>
                <p class="muted">Learners currently registered in EtuAide Planner.</p>
            </div>
            <div class="card admin-students-stat">
                <h3>Recently Active</h3>
                <strong>{{ $recentlyActiveCount }}</strong>
                <p class="muted">Students with activity during the last 14 days.</p>
            </div>
            <div class="card admin-students-stat">
                <h3>Needs Follow-up</h3>
                <strong>{{ $needsFollowUpCount }}</strong>
                <p class="muted">Profiles that may need a reminder from the admin team.</p>
            </div>
        </div>

        <div class="card">
            <div class="admin-students-table-shell">
                <table class="admin-students-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Tasks Completed</th>
                            <th>Last Login</th>
                            <th>Last Activity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            @php
                                $isRecentlyActive = $student->last_activity_at?->gte($activityCutoff) ?? false;
                            @endphp
                            <tr>
                                <td>
                                    <span class="admin-students-table-label">Name</span>
                                    {{ $student->name }}
                                </td>
                                <td>
                                    <span class="admin-students-table-label">Email</span>
                                    {{ $student->email }}
                                </td>
                                <td>
                                    <span class="admin-students-table-label">Tasks Completed</span>
                                    {{ $student->tasks_completed_count }}
                                </td>
                                <td>
                                    <span class="admin-students-table-label">Last Login</span>
                                    {{ optional($student->last_login_at)->format('M d, Y') ?? '-' }}
                                </td>
                                <td>
                                    <span class="admin-students-table-label">Last Activity</span>
                                    {{ optional($student->last_activity_at)->format('M d, Y') ?? '-' }}
                                </td>
                                <td>
                                    <span class="admin-students-table-label">Status</span>
                                    <span class="pill">{{ $isRecentlyActive ? 'Active' : 'Needs follow-up' }}</span>
                                </td>
                                <td>
                                    <span class="admin-students-table-label">Action</span>
                                    <div class="admin-students-table-actions">
                                        <form method="POST" action="{{ route('admin.students.remind', $student) }}">
                                            @csrf
                                            <button type="submit">Send Reminder</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="muted">No students yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
