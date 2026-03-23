@extends('layouts.app')

@push('styles')
    <style>
        .calendar-shell {
            display: grid;
            gap: 24px;
        }

        .calendar-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
        }

        .calendar-toolbar h1 {
            margin-bottom: 8px;
            font-size: clamp(2rem, 4vw, 2.8rem);
        }

        .calendar-toolbar p {
            margin: 0;
        }

        .calendar-toolbar-actions,
        .calendar-nav,
        .calendar-view-switch {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .calendar-nav a,
        .calendar-view-switch a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
        }

        .calendar-view-switch .is-active {
            background: linear-gradient(135deg, #0b132d 0%, var(--violet-500) 58%, var(--violet-700) 100%);
            color: #ffffff;
            box-shadow: 0 16px 28px rgba(33, 86, 245, 0.22);
        }

        .calendar-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .calendar-summary .card {
            margin-bottom: 0;
        }

        .calendar-summary strong {
            display: block;
            margin-top: 8px;
            font-size: 1.9rem;
            letter-spacing: -0.05em;
        }

        .calendar-board {
            display: grid;
            gap: 16px;
        }

        .calendar-weekday-row {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 12px;
        }

        .calendar-weekday-label {
            padding: 12px 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid var(--line);
            text-align: center;
            font-weight: 700;
            color: var(--violet-900);
        }

        .month-grid,
        .week-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 12px;
        }

        .calendar-cell {
            min-width: 0;
            min-height: 220px;
            padding: 16px;
            border-radius: 24px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.78);
            box-shadow: 0 18px 34px rgba(5, 8, 22, 0.08);
            display: grid;
            gap: 14px;
            align-content: start;
        }

        .calendar-cell.is-outside {
            opacity: 0.55;
        }

        .calendar-cell.is-today {
            border-color: rgba(33, 86, 245, 0.34);
            box-shadow: 0 22px 40px rgba(33, 86, 245, 0.16);
        }

        .calendar-cell.drag-over,
        .calendar-slot.drag-over {
            outline: 2px dashed var(--violet-500);
            outline-offset: 2px;
        }

        .calendar-cell-header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: baseline;
        }

        .calendar-cell-title {
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .calendar-cell-date {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.05em;
        }

        .calendar-inline-form {
            display: grid;
            gap: 8px;
        }

        .calendar-inline-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 92px;
            gap: 8px;
        }

        .calendar-inline-form input[type="text"] {
            min-width: 0;
        }

        .calendar-events {
            display: grid;
            gap: 8px;
            align-content: start;
        }

        .calendar-event {
            display: grid;
            gap: 4px;
            padding: 12px 14px;
            border-radius: 18px;
            border: 1px solid rgba(16, 25, 53, 0.08);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 20px rgba(5, 8, 22, 0.06);
            cursor: grab;
        }

        .calendar-event.dragging {
            opacity: 0.6;
            cursor: grabbing;
        }

        .calendar-event-time {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--violet-500);
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .calendar-event-title {
            font-weight: 700;
            line-height: 1.35;
        }

        .calendar-event-meta {
            color: rgba(5, 8, 22, 0.6);
            font-size: 0.88rem;
        }

        .calendar-event.task {
            border-left: 4px solid #2156f5;
        }

        .calendar-event.occurrence {
            border-left: 4px solid #6e4cff;
        }

        .calendar-timeline {
            display: grid;
            gap: 10px;
        }

        .calendar-slot {
            display: grid;
            grid-template-columns: 88px minmax(0, 1fr);
            gap: 14px;
            align-items: start;
            padding: 14px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.76);
        }

        .calendar-slot-time {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--violet-900);
            padding-top: 8px;
        }

        .calendar-slot-content {
            display: grid;
            gap: 10px;
            min-height: 64px;
        }

        body.theme-dark .calendar-weekday-label,
        body.theme-dark .calendar-cell,
        body.theme-dark .calendar-slot,
        body.theme-dark .calendar-event {
            background: rgba(9, 18, 42, 0.88);
            border-color: rgba(139, 119, 255, 0.14);
        }

        body.theme-dark .calendar-event-meta {
            color: rgba(238, 242, 255, 0.66);
        }

        body.theme-dark .calendar-cell.is-outside {
            opacity: 0.45;
        }

        @media (max-width: 960px) {
            .calendar-summary {
                grid-template-columns: 1fr;
            }

            .calendar-weekday-row {
                display: none;
            }

            .month-grid,
            .week-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 780px) {
            .calendar-toolbar,
            .calendar-summary,
            .calendar-slot {
                grid-template-columns: 1fr;
            }

            .calendar-toolbar {
                display: grid;
            }

            .calendar-slot {
                gap: 8px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $periodEventCount = $eventsByDate->flatten(1)->count();
        $taskCount = $eventsByDate->flatten(1)->where('type', 'task')->count();
        $occurrenceCount = $eventsByDate->flatten(1)->where('type', 'occurrence')->count();
        $dailyEvents = $eventsByDate->get($focusDate->toDateString(), collect());
    @endphp

    <section class="calendar-shell">
        <div class="card calendar-toolbar">
            <div>
                <span class="status">Structured Calendar</span>
                <h1>{{ $rangeLabel }}</h1>
                <p class="muted">Navigate by month, week, or day and place tasks into a calendar that reads like an actual schedule.</p>
            </div>

            <div class="calendar-toolbar-actions">
                <div class="calendar-nav">
                    <a class="btn secondary" href="{{ route('calendar.index', ['view' => $view, 'date' => $previousDate]) }}">Prev</a>
                    <a class="btn secondary" href="{{ route('calendar.index', ['view' => $view, 'date' => $todayDate]) }}">Today</a>
                    <a class="btn secondary" href="{{ route('calendar.index', ['view' => $view, 'date' => $nextDate]) }}">Next</a>
                </div>

                <div class="calendar-view-switch">
                    <a class="btn secondary {{ $view === 'daily' ? 'is-active' : '' }}" href="{{ route('calendar.index', ['view' => 'daily', 'date' => $focusDate->toDateString()]) }}">Day</a>
                    <a class="btn secondary {{ $view === 'weekly' ? 'is-active' : '' }}" href="{{ route('calendar.index', ['view' => 'weekly', 'date' => $focusDate->toDateString()]) }}">Week</a>
                    <a class="btn secondary {{ $view === 'monthly' ? 'is-active' : '' }}" href="{{ route('calendar.index', ['view' => 'monthly', 'date' => $focusDate->toDateString()]) }}">Month</a>
                </div>
            </div>
        </div>

        <div class="calendar-summary">
            <div class="card">
                <p class="muted">Items in view</p>
                <strong>{{ $periodEventCount }}</strong>
            </div>
            <div class="card">
                <p class="muted">Due tasks</p>
                <strong>{{ $taskCount }}</strong>
            </div>
            <div class="card">
                <p class="muted">Scheduled occurrences</p>
                <strong>{{ $occurrenceCount }}</strong>
            </div>
        </div>

        @if ($view !== 'daily')
            <div class="calendar-weekday-row">
                @foreach ($weekdays as $weekday)
                    <div class="calendar-weekday-label">{{ $weekday->format('D') }}</div>
                @endforeach
            </div>
        @endif

        @if ($view === 'monthly')
            <div class="month-grid">
                @foreach ($days as $day)
                    @php
                        $dateKey = $day->toDateString();
                        $events = $eventsByDate->get($dateKey, collect());
                        $isToday = $day->isSameDay($today);
                        $isOutside = $day->month !== $currentMonth;
                    @endphp
                    <div class="calendar-cell {{ $isToday ? 'is-today' : '' }} {{ $isOutside ? 'is-outside' : '' }}" data-date="{{ $dateKey }}" data-time="09:00">
                        <div class="calendar-cell-header">
                            <div>
                                <div class="calendar-cell-title">{{ $day->format('l') }}</div>
                                <div class="muted">{{ $day->format('M Y') }}</div>
                            </div>
                            <div class="calendar-cell-date">{{ $day->format('d') }}</div>
                        </div>

                        <form class="calendar-inline-form quick-add" method="POST" action="{{ route('tasks.store') }}" data-date="{{ $dateKey }}">
                            @csrf
                            <input type="text" name="title" placeholder="Add a task" required>
                            <div class="calendar-inline-row">
                                <input class="quick-add-time" type="time" value="09:00">
                                <button type="submit">Add</button>
                            </div>
                            <input type="hidden" name="due_at" value="">
                            <input type="hidden" name="priority" value="medium">
                            <input type="hidden" name="status" value="pending">
                        </form>

                        <div class="calendar-events">
                            @forelse ($events as $event)
                                <div class="calendar-event {{ $event['type'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
                                    <span class="calendar-event-time">{{ $event['time'] }}</span>
                                    <span class="calendar-event-title">{{ $event['title'] }}</span>
                                    <span class="calendar-event-meta">{{ $event['meta'] }} - {{ str_replace('_', ' ', $event['status']) }}</span>
                                </div>
                            @empty
                                <span class="muted">No scheduled items.</span>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif ($view === 'weekly')
            <div class="week-grid">
                @foreach ($days as $day)
                    @php
                        $dateKey = $day->toDateString();
                        $events = $eventsByDate->get($dateKey, collect());
                        $isToday = $day->isSameDay($today);
                    @endphp
                    <div class="calendar-cell {{ $isToday ? 'is-today' : '' }}" data-date="{{ $dateKey }}" data-time="09:00">
                        <div class="calendar-cell-header">
                            <div>
                                <div class="calendar-cell-title">{{ $day->format('l') }}</div>
                                <div class="muted">{{ $day->format('M d, Y') }}</div>
                            </div>
                            <div class="calendar-cell-date">{{ $day->format('d') }}</div>
                        </div>

                        <form class="calendar-inline-form quick-add" method="POST" action="{{ route('tasks.store') }}" data-date="{{ $dateKey }}">
                            @csrf
                            <input type="text" name="title" placeholder="Quick add" required>
                            <div class="calendar-inline-row">
                                <input class="quick-add-time" type="time" value="09:00">
                                <button type="submit">Add</button>
                            </div>
                            <input type="hidden" name="due_at" value="">
                            <input type="hidden" name="priority" value="medium">
                            <input type="hidden" name="status" value="pending">
                        </form>

                        <div class="calendar-events">
                            @forelse ($events as $event)
                                <div class="calendar-event {{ $event['type'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
                                    <span class="calendar-event-time">{{ $event['time'] }}</span>
                                    <span class="calendar-event-title">{{ $event['title'] }}</span>
                                    <span class="calendar-event-meta">{{ $event['meta'] }} - {{ str_replace('_', ' ', $event['status']) }}</span>
                                </div>
                            @empty
                                <span class="muted">Nothing planned for this day.</span>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card">
                <div class="calendar-cell-header" style="margin-bottom: 16px;">
                    <div>
                        <div class="calendar-cell-title">{{ $focusDate->format('l') }}</div>
                        <div class="muted">{{ $focusDate->format('F d, Y') }}</div>
                    </div>
                    <a class="btn secondary" href="{{ route('tasks.create') }}">New task</a>
                </div>

                <div class="calendar-timeline">
                    @foreach (range(7, 21) as $hour)
                        @php
                            $slotTime = str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00';
                            $slotEvents = $dailyEvents->filter(fn ($event) => (int) $event['datetime']->format('G') === $hour);
                        @endphp
                        <div class="calendar-slot" data-date="{{ $focusDate->toDateString() }}" data-time="{{ $slotTime }}">
                            <div class="calendar-slot-time">{{ \Carbon\Carbon::createFromTime($hour)->format('g:i A') }}</div>
                            <div class="calendar-slot-content">
                                <form class="calendar-inline-form quick-add" method="POST" action="{{ route('tasks.store') }}" data-date="{{ $focusDate->toDateString() }}">
                                    @csrf
                                    <div class="calendar-inline-row">
                                        <input type="text" name="title" placeholder="Add a task at {{ $slotTime }}" required>
                                        <button type="submit">Add</button>
                                    </div>
                                    <input class="quick-add-time" type="hidden" value="{{ $slotTime }}">
                                    <input type="hidden" name="due_at" value="">
                                    <input type="hidden" name="priority" value="medium">
                                    <input type="hidden" name="status" value="pending">
                                </form>

                                @forelse ($slotEvents as $event)
                                    <div class="calendar-event {{ $event['type'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
                                        <span class="calendar-event-time">{{ $event['time'] }}</span>
                                        <span class="calendar-event-title">{{ $event['title'] }}</span>
                                        <span class="calendar-event-meta">{{ $event['meta'] }} - {{ str_replace('_', ' ', $event['status']) }}</span>
                                    </div>
                                @empty
                                    <span class="muted">Open slot.</span>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <script>
        const csrfToken = '{{ csrf_token() }}';
        const taskRouteTemplate = '{{ route('tasks.move', ':id') }}';
        const occurrenceRouteTemplate = '{{ route('occurrences.update', ':id') }}';

        document.querySelectorAll('.calendar-event').forEach((item) => {
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

        document.querySelectorAll('.calendar-cell[data-date], .calendar-slot[data-date]').forEach((dropTarget) => {
            dropTarget.addEventListener('dragover', (event) => {
                event.preventDefault();
                dropTarget.classList.add('drag-over');
            });

            dropTarget.addEventListener('dragleave', () => {
                dropTarget.classList.remove('drag-over');
            });

            dropTarget.addEventListener('drop', async (event) => {
                event.preventDefault();
                dropTarget.classList.remove('drag-over');

                const payload = JSON.parse(event.dataTransfer.getData('text/plain'));
                const date = dropTarget.dataset.date;
                const time = dropTarget.dataset.time || payload.time || '09:00';
                const datetime = `${date} ${time}:00`;

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
