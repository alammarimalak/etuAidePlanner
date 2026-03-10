@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>

    <div class="grid grid-3">
        <div class="card">
            <h3>Total Students</h3>
            <p>{{ $totalStudents }}</p>
        </div>
        <div class="card">
            <h3>Active Students</h3>
            <p>{{ $activeCount }}</p>
        </div>
        <div class="card">
            <h3>Inactive Students</h3>
            <p>{{ $inactiveCount }}</p>
        </div>
    </div>

    <div class="grid grid-3">
        <div class="card">
            <h3>Tasks Completed</h3>
            <p>{{ $completedTasks }}</p>
        </div>
        <div class="card">
            <h3>Tasks Pending</h3>
            <p>{{ $pendingTasks }}</p>
        </div>
        <div class="card">
            <h3>Alerts</h3>
            <p>{{ $inactiveCount }} inactive students</p>
        </div>
    </div>

    <div class="card">
        <h3>Inactive Students (14+ days)</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Last Login</th>
                    <th>Last Activity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inactiveStudents as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ optional($student->last_login_at)->format('M d, Y') ?? '—' }}</td>
                        <td>{{ optional($student->last_activity_at)->format('M d, Y') ?? '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.students.remind', $student) }}">
                                @csrf
                                <button type="submit">Send Reminder</button>
                            </form>
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
@endsection
