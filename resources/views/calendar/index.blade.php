@extends('layouts.app')

@push('styles')
    <style>
        .calendar-shell {
            --calendar-shell-bg: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, var(--lavender) 100%);
            --calendar-shell-border: var(--line);
            --calendar-surface: rgba(255, 255, 255, 0.92);
            --calendar-surface-strong: #ffffff;
            --calendar-surface-soft: rgba(255, 255, 255, 0.72);
            --calendar-text: var(--ink);
            --calendar-text-soft: var(--ink-soft);
            --calendar-accent: var(--violet-500);
            --calendar-accent-strong: var(--violet-700);
            --calendar-accent-soft: var(--lavender);
            --calendar-accent-border: rgba(33, 86, 245, 0.2);
            --calendar-task: var(--violet-500);
            --calendar-occurrence: var(--violet-700);
            display: grid;
            gap: 24px;
            background: var(--calendar-shell-bg);
            border: 1px solid var(--calendar-shell-border);
            border-radius: 18px;
            padding: 1.5rem;
        }

        body.theme-dark .calendar-shell {
            --calendar-shell-bg: linear-gradient(180deg, rgba(9, 18, 42, 0.96) 0%, rgba(13, 23, 48, 0.96) 100%);
            --calendar-shell-border: rgba(139, 119, 255, 0.16);
            --calendar-surface: rgba(9, 18, 42, 0.92);
            --calendar-surface-strong: rgba(13, 23, 48, 0.96);
            --calendar-surface-soft: rgba(255, 255, 255, 0.04);
            --calendar-text: #eef2ff;
            --calendar-text-soft: rgba(238, 242, 255, 0.8);
            --calendar-accent: var(--violet-400);
            --calendar-accent-strong: var(--violet-300);
            --calendar-accent-soft: rgba(139, 119, 255, 0.12);
            --calendar-accent-border: rgba(139, 119, 255, 0.28);
            --calendar-task: var(--violet-400);
            --calendar-occurrence: var(--violet-300);
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
            background: linear-gradient(135deg, #0b132d 0%, var(--calendar-accent) 58%, var(--calendar-accent-strong) 100%);
            color: #ffffff;
            box-shadow: 0 16px 28px rgba(5, 8, 22, 0.18);
        }

        .calendar-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .calendar-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
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

        .calendar-canvas {
            display: grid;
            gap: 16px;
            padding: 18px;
            border-radius: 18px;
            border: 1px solid var(--calendar-shell-border);
            background: var(--calendar-surface);
            box-shadow: none;
            min-width: 0;
            overflow: hidden;
        }

        .calendar-canvas-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .calendar-canvas-header h2 {
            margin: 6px 0 8px;
            font-size: clamp(1.1rem, 2.5vw, 1.45rem);
        }

        .calendar-canvas-header p {
            margin: 0;
            max-width: 540px;
        }

        .calendar-legend {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .calendar-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid var(--calendar-shell-border);
            background: var(--calendar-surface-strong);
            font-weight: 600;
            color: var(--calendar-text-soft);
            font-size: 0.9rem;
        }

        .calendar-legend-swatch {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .calendar-legend-swatch.task {
            background: var(--calendar-task);
        }

        .calendar-legend-swatch.occurrence {
            background: var(--calendar-occurrence);
        }

        .calendar-legend-swatch.add {
            background: var(--calendar-text);
        }

        .calendar-board {
            display: grid;
            gap: 16px;
            min-width: 0;
        }

        .calendar-weekday-row {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 8px;
        }

        .calendar-weekday-label {
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--calendar-surface-strong);
            border: 1px solid var(--calendar-shell-border);
            text-align: center;
            font-weight: 700;
            color: var(--calendar-text);
        }

        .month-grid,
        .week-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 8px;
        }

        .calendar-cell {
            min-width: 0;
            min-height: 120px;
            aspect-ratio: 1 / 1;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid var(--calendar-shell-border);
            background: var(--calendar-surface-strong);
            box-shadow: none;
            display: grid;
            gap: 8px;
            align-content: start;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            overflow: hidden;
        }

        .calendar-cell.monthly,
        .calendar-cell.weekly {
            align-content: stretch;
        }

        .calendar-cell.is-outside {
            opacity: 0.55;
        }

        .calendar-cell.is-today {
            border-color: var(--calendar-accent-border);
            background: var(--calendar-accent-soft);
        }

        .calendar-cell.is-selected,
        .calendar-slot.is-selected {
            border-color: var(--calendar-accent);
            background: var(--calendar-accent-soft);
        }

        .calendar-cell.drag-over,
        .calendar-slot.drag-over {
            outline: 2px dashed var(--calendar-accent);
            outline-offset: 2px;
        }

        .calendar-cell:hover,
        .calendar-slot:hover,
        .calendar-cell:focus-visible,
        .calendar-slot:focus-visible {
            transform: none;
            border-color: var(--calendar-accent-border);
            box-shadow: none;
        }

        .calendar-events {
            display: grid;
            gap: 5px;
            align-content: start;
        }

        .calendar-event {
            display: block;
            width: 100%;
            min-height: 6px;
            height: 6px;
            padding: 0;
            border-radius: 999px;
            border: 0;
            background: rgba(16, 25, 53, 0.14);
            box-shadow: none;
            cursor: grab;
        }

        .calendar-event > span {
            display: none;
        }

        .calendar-event.dragging {
            opacity: 0.6;
            cursor: grabbing;
        }

        .calendar-event.task {
            background: var(--calendar-task);
        }

        .calendar-event.occurrence {
            background: var(--calendar-occurrence);
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
            border-radius: 10px;
            border: 1px solid var(--calendar-shell-border);
            background: var(--calendar-surface-strong);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .calendar-slot-time {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--calendar-text);
            padding-top: 8px;
        }

        .calendar-slot-content {
            display: grid;
            gap: 10px;
            min-height: 64px;
        }

        .calendar-modal-shell[hidden] {
            display: none;
        }

        .calendar-modal-shell {
            position: fixed;
            inset: 0;
            z-index: 40;
        }

        .calendar-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(5, 8, 22, 0.52);
            backdrop-filter: blur(8px);
        }

        .calendar-modal {
            position: relative;
            z-index: 1;
            width: min(520px, calc(100vw - 32px));
            margin: min(10vh, 72px) auto 0;
            padding: 24px;
            border-radius: 28px;
            border: 1px solid var(--calendar-accent-border);
            background:
                linear-gradient(180deg, var(--calendar-surface-strong), var(--calendar-surface)),
                radial-gradient(circle at top right, var(--calendar-accent-soft), transparent 28%);
            box-shadow: 0 28px 58px rgba(5, 8, 22, 0.24);
        }

        .calendar-modal-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .calendar-modal-header h3 {
            margin: 6px 0 8px;
            font-size: 1.45rem;
        }

        .calendar-modal-header p {
            margin: 0;
        }

        .calendar-modal-close {
            border: 0;
            background: var(--calendar-surface-soft);
            color: var(--calendar-text);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .calendar-modal-form {
            display: grid;
            gap: 16px;
        }

        .calendar-modal-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 140px;
            gap: 12px;
        }

        .calendar-modal-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .calendar-modal-meta-card {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--calendar-shell-border);
            background: var(--calendar-surface-soft);
        }

        .calendar-modal-meta-card strong {
            display: block;
            margin-top: 6px;
            font-size: 1rem;
        }

        .calendar-modal label {
            display: grid;
            gap: 8px;
            font-weight: 700;
            color: var(--calendar-text-soft);
        }

        .calendar-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .calendar-modal-list {
            display: grid;
            gap: 10px;
        }

        .calendar-modal-item {
            border: 1px solid var(--calendar-shell-border);
            border-radius: 12px;
            padding: 0.85rem;
            background: var(--calendar-surface-strong);
        }

        .calendar-modal-item-title {
            font-weight: 700;
            color: var(--calendar-text);
        }

        .calendar-modal-item-meta {
            font-size: 0.88rem;
            color: var(--calendar-text-soft);
        }

        .calendar-modal-item-actions {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .calendar-hover-card[hidden] {
            display: none;
        }

        .calendar-hover-card {
            position: fixed;
            z-index: 35;
            width: min(320px, calc(100vw - 24px));
            padding: 14px;
            border-radius: 14px;
            border: 1px solid var(--calendar-accent-border);
            background: var(--calendar-surface);
            box-shadow: 0 18px 36px rgba(5, 8, 22, 0.18);
            pointer-events: none;
        }

        .calendar-hover-title {
            font-weight: 700;
            color: var(--calendar-text);
            margin-bottom: 8px;
        }

        .calendar-hover-list {
            display: grid;
            gap: 8px;
        }

        .calendar-hover-item {
            display: grid;
            grid-template-columns: 8px minmax(0, 1fr);
            gap: 10px;
            align-items: start;
        }

        .calendar-hover-accent {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 5px;
        }

        .calendar-hover-accent.task {
            background: var(--calendar-task);
        }

        .calendar-hover-accent.occurrence {
            background: var(--calendar-occurrence);
        }

        .calendar-hover-item-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--calendar-text);
            line-height: 1.3;
        }

        .calendar-hover-item-meta {
            font-size: 0.82rem;
            color: var(--calendar-text-soft);
        }

        body.theme-dark .calendar-modal label,
        body.theme-dark .calendar-modal-close,
        body.theme-dark .calendar-modal-item-meta,
        body.theme-dark .calendar-hover-item-meta {
            color: rgba(238, 242, 255, 0.8);
        }

        body.theme-dark .calendar-modal-close {
            background: rgba(255, 255, 255, 0.08);
        }

        body.theme-dark .calendar-modal-item-title,
        body.theme-dark .calendar-hover-title,
        body.theme-dark .calendar-hover-item-title {
            color: #eef2ff;
        }

        body.theme-dark .calendar-cell.is-outside {
            opacity: 0.45;
        }

        @media (max-width: 960px) {
            .calendar-cell {
                aspect-ratio: auto;
                min-height: 88px;
            }

            .calendar-canvas-header,
            .calendar-modal-meta {
                display: grid;
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
            .calendar-slot,
            .calendar-modal-grid,
            .calendar-modal-actions {
                grid-template-columns: 1fr;
            }

            .calendar-toolbar,
            .calendar-modal-actions {
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
        $calendarDetailEvents = $eventsByDate
            ->map(fn ($events) => $events
                ->map(fn ($event) => [
                    'title' => $event['title'],
                    'type' => $event['type'],
                    'time' => $event['time'],
                    'meta' => $event['meta'],
                    'status' => str_replace('_', ' ', $event['status']),
                    'edit_url' => $event['edit_url'] ?? null,
                ])
                ->values())
            ->toArray();
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

        <div class="calendar-layout">
            <div class="calendar-canvas">
                <div class="calendar-canvas-header">
                    <div>
                        <span class="status">Calendar Canvas</span>
                        <h2>{{ $periodEventCount }} item{{ $periodEventCount === 1 ? '' : 's' }} in view</h2>
                        <p class="muted">Simple squares, task lines, and a selected-day panel inspired by your sample calendar.</p>
                    </div>

                    <div class="calendar-legend">
                        <span class="calendar-legend-item"><span class="calendar-legend-swatch task"></span> Due task</span>
                        <span class="calendar-legend-item"><span class="calendar-legend-swatch occurrence"></span> Scheduled occurrence</span>
                    </div>
                </div>

                <div class="calendar-board">
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
                                $displayDate = $day->isoFormat('dddd, MMMM D, YYYY');
                            @endphp
                            <div class="calendar-cell calendar-target monthly {{ $isToday ? 'is-today' : '' }} {{ $isOutside ? 'is-outside' : '' }}" data-date="{{ $dateKey }}" data-time="09:00" data-display-date="{{ $displayDate }}" tabindex="0" role="button" aria-label="Add a task on {{ $displayDate }}">
                                <div class="calendar-events">
                                    @forelse ($events as $event)
                                        <div class="calendar-event {{ $event['type'] }}" title="{{ $event['time'] }} - {{ $event['title'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
                                            <span class="calendar-event-time">{{ $event['time'] }}</span>
                                            <span class="calendar-event-title">{{ $event['title'] }}</span>
                                            <span class="calendar-event-meta">{{ $event['meta'] }} - {{ str_replace('_', ' ', $event['status']) }}</span>
                                        </div>
                                    @empty
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
                                $displayDate = $day->isoFormat('dddd, MMMM D, YYYY');
                            @endphp
                            <div class="calendar-cell calendar-target weekly {{ $isToday ? 'is-today' : '' }}" data-date="{{ $dateKey }}" data-time="09:00" data-display-date="{{ $displayDate }}" tabindex="0" role="button" aria-label="Add a task on {{ $displayDate }}">
                                <div class="calendar-events">
                                    @forelse ($events as $event)
                                        <div class="calendar-event {{ $event['type'] }}" title="{{ $event['time'] }} - {{ $event['title'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
                                            <span class="calendar-event-time">{{ $event['time'] }}</span>
                                            <span class="calendar-event-title">{{ $event['title'] }}</span>
                                            <span class="calendar-event-meta">{{ $event['meta'] }} - {{ str_replace('_', ' ', $event['status']) }}</span>
                                        </div>
                                    @empty
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
                            <a class="btn secondary" href="{{ route('tasks.create') }}">Full task form</a>
                        </div>

                        <div class="calendar-timeline">
                            @foreach (range(7, 21) as $hour)
                                @php
                                    $slotTime = str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00';
                                    $slotEvents = $dailyEvents->filter(fn ($event) => (int) $event['datetime']->format('G') === $hour);
                                    $displayDate = $focusDate->isoFormat('dddd, MMMM D, YYYY');
                                @endphp
                                <div class="calendar-slot calendar-target" data-date="{{ $focusDate->toDateString() }}" data-time="{{ $slotTime }}" data-display-date="{{ $displayDate }}" tabindex="0" role="button" aria-label="Add a task at {{ $slotTime }} on {{ $displayDate }}">
                                    <div class="calendar-slot-time">{{ \Carbon\Carbon::createFromTime($hour)->format('g:i A') }}</div>
                                    <div class="calendar-slot-content">
                                        @forelse ($slotEvents as $event)
                                            <div class="calendar-event {{ $event['type'] }}" title="{{ $event['time'] }} - {{ $event['title'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
                                                <span class="calendar-event-time">{{ $event['time'] }}</span>
                                                <span class="calendar-event-title">{{ $event['title'] }}</span>
                                                <span class="calendar-event-meta">{{ $event['meta'] }} - {{ str_replace('_', ' ', $event['status']) }}</span>
                                            </div>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                </div>
            </div>

        </div>
    </section>

    <div id="calendar-hover-card" class="calendar-hover-card" hidden></div>

    <div class="calendar-modal-shell" hidden>
        <div class="calendar-modal-backdrop" data-modal-close></div>
        <div class="calendar-modal" role="dialog" aria-modal="true" aria-labelledby="calendar-modal-title">
            <div class="calendar-modal-header">
                <div>
                    <span class="status">Quick Add</span>
                    <h3 id="calendar-modal-title">Add a task from the calendar</h3>
                    <p class="muted">Choose the time, name the task, and it will be added straight into this schedule.</p>
                </div>
                <button type="button" class="calendar-modal-close" data-modal-close aria-label="Close quick add">x</button>
            </div>

            <form id="calendar-popup-form" class="calendar-modal-form" method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <input type="hidden" name="priority" value="medium">
                <input type="hidden" name="status" value="pending">
                <input type="hidden" name="from_calendar" value="1">
                <input type="hidden" name="due_at" value="">
                <input type="hidden" id="calendar-modal-date" value="">

                <div class="calendar-modal-meta">
                    <div class="calendar-modal-meta-card">
                        <span class="muted">Selected day</span>
                        <strong data-selected-date-label>Choose a day</strong>
                    </div>
                    <div class="calendar-modal-meta-card">
                        <span class="muted">Selected time</span>
                        <strong data-selected-time-label>09:00</strong>
                    </div>
                </div>

                <div>
                    <div class="fw-semibold mb-2">Tasks on this day</div>
                    <div id="calendar-modal-events" class="calendar-modal-list">
                        <div class="muted">No tasks yet.</div>
                    </div>
                </div>

                <label for="calendar-modal-title-input">
                    Task
                    <input id="calendar-modal-title-input" type="text" name="title" placeholder="Example: Review chapter notes" required>
                </label>

                <div class="calendar-modal-grid">
                    <label for="calendar-modal-time">
                        Time
                        <input id="calendar-modal-time" type="time" value="09:00" required>
                    </label>
                    <label>
                        Action
                        <button type="submit">Add</button>
                    </label>
                </div>

                <div class="calendar-modal-actions">
                    <button type="button" class="secondary" data-modal-close>Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';
        const taskRouteTemplate = '{{ route('tasks.move', ':id') }}';
        const occurrenceRouteTemplate = '{{ route('occurrences.update', ':id') }}';
        const calendarEventsByDate = @json($calendarDetailEvents);
        const modalShell = document.querySelector('.calendar-modal-shell');
        const modalForm = document.getElementById('calendar-popup-form');
        const modalDateInput = document.getElementById('calendar-modal-date');
        const modalDueAtInput = modalForm ? modalForm.querySelector('input[name="due_at"]') : null;
        const modalTimeInput = document.getElementById('calendar-modal-time');
        const modalTitleInput = document.getElementById('calendar-modal-title-input');
        const modalDateLabel = document.querySelector('[data-selected-date-label]');
        const modalTimeLabel = document.querySelector('[data-selected-time-label]');
        const modalEvents = document.getElementById('calendar-modal-events');
        const hoverCard = document.getElementById('calendar-hover-card');
        let selectedDate = '{{ $focusDate->toDateString() }}';
        let selectedTime = '09:00';

        const escapeHtml = (value) => String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const renderEventItems = (entries, allowEdit = false) => {
            if (!entries.length) {
                return '<div class="muted">No tasks scheduled for this day.</div>';
            }

            return entries.map((entry) => `
                <div class="${allowEdit ? 'calendar-modal-item' : 'calendar-hover-item'}">
                    ${allowEdit ? '' : `<span class="calendar-hover-accent ${escapeHtml(entry.type)}"></span>`}
                    <div>
                        <div class="${allowEdit ? 'calendar-modal-item-title' : 'calendar-hover-item-title'}">${escapeHtml(entry.title)}</div>
                        <div class="${allowEdit ? 'calendar-modal-item-meta' : 'calendar-hover-item-meta'}">${escapeHtml(entry.time)} - ${escapeHtml(entry.meta)}</div>
                        ${allowEdit ? `<div class="calendar-modal-item-meta text-capitalize">${escapeHtml(entry.status)}</div>` : ''}
                        ${allowEdit && entry.edit_url ? `<div class="calendar-modal-item-actions"><a class="btn secondary" href="${escapeHtml(entry.edit_url)}">Edit</a></div>` : ''}
                    </div>
                </div>
            `).join('');
        };

        const renderHoverCard = (date, displayDate, target) => {
            if (!hoverCard) {
                return;
            }

            const entries = calendarEventsByDate[date] || [];
            if (!entries.length) {
                hoverCard.hidden = true;
                return;
            }

            hoverCard.innerHTML = `
                <div class="calendar-hover-title">${escapeHtml(displayDate || date)}</div>
                <div class="calendar-hover-list">${renderEventItems(entries, false)}</div>
            `;

            hoverCard.hidden = false;
            const rect = target.getBoundingClientRect();
            const top = Math.min(window.innerHeight - hoverCard.offsetHeight - 12, rect.top + 12);
            const left = Math.min(window.innerWidth - hoverCard.offsetWidth - 12, rect.right + 10);
            hoverCard.style.top = `${Math.max(12, top)}px`;
            hoverCard.style.left = `${Math.max(12, left)}px`;
        };

        const hideHoverCard = () => {
            if (hoverCard) {
                hoverCard.hidden = true;
            }
        };

        const renderModalEvents = (date) => {
            if (!modalEvents) {
                return;
            }

            const entries = calendarEventsByDate[date] || [];
            modalEvents.innerHTML = renderEventItems(entries, true);
        };

        const updateSelectedTarget = (date, time = '') => {
            document.querySelectorAll('.calendar-target').forEach((target) => {
                const isSameDate = target.dataset.date === date;
                const isSlot = target.classList.contains('calendar-slot');
                const matches = isSameDate && (!isSlot || !time || target.dataset.time === time);
                target.classList.toggle('is-selected', matches);
            });
        };

        const openCalendarModal = (date, time, displayDate) => {
            if (!modalShell || !modalForm) {
                return;
            }

            hideHoverCard();
            modalShell.hidden = false;
            modalDateInput.value = date;
            modalTimeInput.value = time || '09:00';
            modalDateLabel.textContent = displayDate || date;
            modalTimeLabel.textContent = time || '09:00';
            modalDueAtInput.value = `${date} ${modalTimeInput.value}:00`;
            renderModalEvents(date);

            window.requestAnimationFrame(() => {
                modalTitleInput.focus();
            });
        };

        const closeCalendarModal = () => {
            if (!modalShell || !modalForm) {
                return;
            }

            modalShell.hidden = true;
            modalForm.reset();
            modalTimeInput.value = '09:00';
            modalDateInput.value = '';
            modalDueAtInput.value = '';
            modalDateLabel.textContent = 'Choose a day';
            modalTimeLabel.textContent = '09:00';
            updateSelectedTarget('', '');
        };

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

        document.querySelectorAll('.calendar-target').forEach((target) => {
            target.addEventListener('mouseenter', () => {
                renderHoverCard(target.dataset.date, target.dataset.displayDate || target.dataset.date, target);
            });

            target.addEventListener('mouseleave', hideHoverCard);

            target.addEventListener('focus', () => {
                renderHoverCard(target.dataset.date, target.dataset.displayDate || target.dataset.date, target);
            });

            target.addEventListener('blur', hideHoverCard);

            target.addEventListener('click', (event) => {
                if (event.target.closest('a, button, input, select, textarea, label, form')) {
                    return;
                }

                selectedDate = target.dataset.date;
                selectedTime = target.dataset.time || '09:00';
                updateSelectedTarget(selectedDate, target.classList.contains('calendar-slot') ? selectedTime : '');
                openCalendarModal(selectedDate, selectedTime, target.dataset.displayDate || selectedDate);
            });

            target.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }

                event.preventDefault();
                selectedDate = target.dataset.date;
                selectedTime = target.dataset.time || '09:00';
                updateSelectedTarget(selectedDate, target.classList.contains('calendar-slot') ? selectedTime : '');
                openCalendarModal(selectedDate, selectedTime, target.dataset.displayDate || selectedDate);
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

        document.querySelectorAll('[data-modal-close]').forEach((closeTrigger) => {
            closeTrigger.addEventListener('click', closeCalendarModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modalShell && !modalShell.hidden) {
                closeCalendarModal();
            }
        });

        if (modalForm) {
            modalTimeInput.addEventListener('input', () => {
                modalTimeLabel.textContent = modalTimeInput.value || '09:00';
            });

            modalForm.addEventListener('submit', () => {
                const time = modalTimeInput.value || '09:00';
                modalDueAtInput.value = `${modalDateInput.value} ${time}:00`;
            });
        }

        hideHoverCard();
    </script>
@endsection
