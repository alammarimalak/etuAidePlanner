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

        .admin-students-table td:last-child {
            width: 1%;
            white-space: nowrap;
        }

        @media (max-width: 960px) {
            .admin-students-summary {
                grid-template-columns: 1fr;
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
                <a class="btn secondary" href="{{ route('admin.dashboard') }}">Back to admin overview</a>
            </div>
        </div>

        <div class="admin-students-summary">
            <div class="card admin-students-stat">
                <h3>Total Students</h3>
                <strong>{{ $students->count() }}</strong>
                <p class="muted">Learners currently registered in EtuAide.</p>
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
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->tasks_completed_count }}</td>
                            <td>{{ optional($student->last_login_at)->format('M d, Y') ?? '-' }}</td>
                            <td>{{ optional($student->last_activity_at)->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <span class="pill">{{ $isRecentlyActive ? 'Active' : 'Needs follow-up' }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.students.remind', $student) }}">
                                    @csrf
                                    <button type="submit">Send Reminder</button>
                                </form>
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
    </section>
@endsection
