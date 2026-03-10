@extends('layouts.app')

@section('content')
    <h1>Notifications</h1>

    <div class="card">
        <form method="POST" action="{{ route('notifications.read_all') }}">
            @csrf
            @method('PATCH')
            <button type="submit">Mark All Read</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr>
                        <td>{{ $notification->title }}</td>
                        <td>{{ $notification->body }}</td>
                        <td>{{ $notification->read_at ? 'Read' : 'Unread' }}</td>
                        <td>
                            @if (!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Mark Read</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No notifications.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
