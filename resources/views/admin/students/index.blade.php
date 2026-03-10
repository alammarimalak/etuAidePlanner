@extends('layouts.app')

@section('content')
    <h1>Students</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Tasks Completed</th>
                    <th>Last Login</th>
                    <th>Last Activity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->tasks_completed_count }}</td>
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
                        <td colspan="6" class="muted">No students yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
