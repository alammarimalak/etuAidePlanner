@extends('layouts.app')

@section('content')
    <h1>Calendar</h1>

    <div class="card">
        <div class="actions">
            <a class="btn secondary" href="{{ route('calendar.index', ['view' => 'daily']) }}">Daily</a>
            <a class="btn secondary" href="{{ route('calendar.index', ['view' => 'weekly']) }}">Weekly</a>
            <a class="btn secondary" href="{{ route('calendar.index', ['view' => 'monthly']) }}">Monthly</a>
        </div>
        <p class="muted">Showing {{ ucfirst($view) }} view from {{ $start->toFormattedDateString() }} to {{ $end->toFormattedDateString() }}.</p>
    </div>

    @if ($view !== 'daily')
        <div class="calendar-weekdays">
            @foreach ($weekdays as $weekday)
                <div class="weekday-pill">{{ $weekday->format('D') }}</div>
            @endforeach
        </div>
    @endif

    <div class="calendar-grid calendar-{{ $view }}">
        @foreach ($days as $day)
            @php
                $dateKey = $day->toDateString();
                $occurrences = $occurrencesByDate->get($dateKey, collect());
                $tasks = $tasksByDate->get($dateKey, collect());
                $isToday = $day->isSameDay($today);
                $isOutside = $view === 'monthly' && $day->month !== $currentMonth;
            @endphp
            <div class="card calendar-day {{ $isToday ? 'is-today' : '' }} {{ $isOutside ? 'is-outside' : '' }}" data-date="{{ $dateKey }}">
                <div class="day-header">
                    <strong>{{ $day->format('D') }}</strong>
                    <span class="muted">{{ $day->format('M d') }}</span>
                </div>
                <form class="quick-add" method="POST" action="{{ route('tasks.store') }}" data-date="{{ $dateKey }}">
                    @csrf
                    <input type="text" name="title" placeholder="Quick add a task" required>
                    <div class="quick-add-row">
                        <input class="quick-add-time" type="time" value="09:00">
                        <input type="hidden" name="due_at" value="">
                        <input type="hidden" name="priority" value="medium">
                        <input type="hidden" name="status" value="pending">
                        <button type="submit">Add</button>
                    </div>
                </form>
                <div class="drop-zone">
                    @foreach ($occurrences as $occurrence)
                        <div class="calendar-item" draggable="true" data-type="occurrence" data-id="{{ $occurrence->id }}" data-time="{{ $occurrence->scheduled_at->format('H:i') }}">
                            <div><strong>{{ $occurrence->task->title }}</strong></div>
                            <div class="muted">{{ $occurrence->scheduled_at->format('H:i') }} · {{ $occurrence->status }}</div>
                        </div>
                    @endforeach
                    @foreach ($tasks as $task)
                        <div class="calendar-item" draggable="true" data-type="task" data-id="{{ $task->id }}" data-time="{{ $task->due_at->format('H:i') }}">
                            <div><strong>{{ $task->title }}</strong></div>
                            <div class="muted">Due {{ $task->due_at->format('H:i') }} · {{ $task->status }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';
        const taskRouteTemplate = '{{ route('tasks.move', ':id') }}';
        const occurrenceRouteTemplate = '{{ route('occurrences.update', ':id') }}';

        document.querySelectorAll('.calendar-item').forEach((item) => {
            item.addEventListener('dragstart', (event) => {
                item.classList.add('dragging');
                event.dataTransfer.setData('text/plain', JSON.stringify({
                    type: item.dataset.type,
                    id: item.dataset.id,
                    time: item.dataset.time || '09:00'
                }));
            });

            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
            });
        });

        document.querySelectorAll('.calendar-day').forEach((day) => {
            day.addEventListener('dragover', (event) => {
                event.preventDefault();
                day.classList.add('drag-over');
            });

            day.addEventListener('dragleave', () => {
                day.classList.remove('drag-over');
            });

            day.addEventListener('drop', async (event) => {
                event.preventDefault();
                day.classList.remove('drag-over');

                const payload = JSON.parse(event.dataTransfer.getData('text/plain'));
                const date = day.dataset.date;
                const datetime = `${date} ${payload.time}:00`;

                if (payload.type === 'task') {
                    const url = taskRouteTemplate.replace(':id', payload.id);
                    await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ due_at: datetime })
                    });
                }

                if (payload.type === 'occurrence') {
                    const url = occurrenceRouteTemplate.replace(':id', payload.id);
                    await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ scheduled_at: datetime })
                    });
                }

                window.location.reload();
            });
        });

        document.querySelectorAll('.quick-add').forEach((form) => {
            form.addEventListener('submit', () => {
                const date = form.dataset.date;
                const timeInput = form.querySelector('.quick-add-time');
                const time = timeInput && timeInput.value ? timeInput.value : '09:00';
                const dueAtInput = form.querySelector('input[name="due_at"]');
                dueAtInput.value = `${date} ${time}:00`;
            });
        });
    </script>
@endsection
