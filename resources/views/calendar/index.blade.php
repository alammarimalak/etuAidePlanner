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

        .calendar-toolbar-note {
            max-width: 360px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--calendar-shell-border);
            background: var(--calendar-surface-soft);
            color: var(--calendar-text-soft);
        }

        .calendar-toolbar-actions,
        .calendar-nav,
        .calendar-view-switch {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .calendar-toolbar-actions {
            justify-content: flex-end;
        }

        .calendar-nav a,
        .calendar-view-switch a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
        }

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .calendar-nav {
            justify-content: flex-end;
        }

        .calendar-nav-icon {
            width: 44px;
            height: 44px;
            padding: 0;
            border-radius: 999px;
            line-height: 1;
            font-size: 0;
            color: transparent;
            position: relative;
        }

        .calendar-nav-icon::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            border-top: 2px solid currentColor;
            border-right: 2px solid currentColor;
            transform: translate(-50%, -50%) rotate(45deg);
            color: var(--calendar-text);
        }

        .calendar-nav .calendar-nav-icon:first-child::before {
            transform: translate(-50%, -50%) rotate(-135deg);
        }

        .calendar-view-switch .is-active {
            background: var(--violet-500);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 16px 28px rgba(33, 86, 245, 0.22);
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

        .calendar-canvas-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
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
            --event-dot-accent: var(--calendar-task);
            --event-line-accent: var(--calendar-task);
            display: grid;
            grid-template-columns: 8px minmax(0, 1fr);
            align-items: center;
            gap: 6px;
            width: 100%;
            min-height: 10px;
            padding: 0;
            border-radius: 999px;
            border: 0;
            background: transparent;
            box-shadow: none;
            cursor: grab;
        }

        .calendar-event::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--event-dot-accent);
            flex-shrink: 0;
        }

        .calendar-event::after {
            content: "";
            display: block;
            width: 100%;
            height: 4px;
            border-radius: 999px;
            background: var(--event-line-accent);
        }

        .calendar-event > span {
            display: none;
        }

        .calendar-event.dragging {
            opacity: 0.6;
            cursor: grabbing;
        }

        .calendar-event.task {
            --event-dot-accent: var(--calendar-task);
            --event-line-accent: var(--calendar-task);
        }

        .calendar-event.occurrence {
            --event-dot-accent: var(--calendar-occurrence);
            --event-line-accent: var(--calendar-occurrence);
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
            width: min(980px, calc(100vw - 32px));
            max-height: calc(100vh - 40px);
            margin: 20px auto 0;
            padding: 28px;
            border-radius: 28px;
            border: 1px solid rgba(16, 25, 53, 0.12);
            background: #ffffff;
            box-shadow: 0 28px 58px rgba(5, 8, 22, 0.24);
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-gutter: stable both-edges;
            color: var(--ink);
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            background: rgba(16, 25, 53, 0.06);
            color: var(--ink);
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
        }

        .calendar-modal-form {
            display: grid;
            gap: 18px;
        }

        .calendar-modal-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            align-items: start;
        }

        .calendar-modal-row.title-row {
            grid-template-columns: minmax(0, 1.6fr) minmax(220px, 0.8fr);
        }

        .calendar-modal-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .calendar-modal-meta-card {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(16, 25, 53, 0.1);
            background: #ffffff;
        }

        .calendar-modal-meta-card strong {
            display: block;
            margin-top: 6px;
            font-size: 1rem;
        }

        .calendar-modal-row > div,
        .calendar-recurrence-grid > div,
        .calendar-modal-description,
        .calendar-subtasks {
            display: grid;
            gap: 8px;
            align-content: start;
        }

        .calendar-modal label {
            font-weight: 700;
            color: var(--ink-soft);
        }

        .calendar-modal input,
        .calendar-modal select,
        .calendar-modal textarea {
            width: 100%;
            background: #ffffff !important;
            color: var(--ink) !important;
            border: 1px solid rgba(16, 25, 53, 0.18);
        }

        .calendar-modal textarea {
            min-height: 140px;
            resize: vertical;
        }

        .calendar-modal .muted {
            color: rgba(16, 25, 53, 0.64);
        }

        .calendar-inline-check label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--ink);
        }

        .calendar-inline-check input {
            width: auto;
        }

        .calendar-recurrence-builder {
            display: grid;
            gap: 16px;
            padding: 18px;
            border-radius: 20px;
            border: 1px solid rgba(16, 25, 53, 0.1);
            background: #ffffff;
        }

        .calendar-recurrence-builder.is-hidden {
            display: none;
        }

        .calendar-recurrence-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            align-items: start;
        }

        .calendar-weekday-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .calendar-weekday-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .calendar-weekday-toggle input {
            width: auto;
        }

        .calendar-subtasks {
            gap: 14px;
        }

        .calendar-subtasks-header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .calendar-subtasks-header p {
            margin: 0;
        }

        .calendar-subtasks-list {
            display: grid;
            gap: 12px;
        }

        .calendar-subtask-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 180px auto;
            gap: 12px;
            align-items: end;
            padding: 14px;
            border-radius: 18px;
            border: 1px solid rgba(16, 25, 53, 0.1);
            background: #ffffff;
        }

        .calendar-subtask-empty {
            padding: 14px;
            border-radius: 18px;
            border: 1px dashed rgba(16, 25, 53, 0.18);
            color: rgba(16, 25, 53, 0.62);
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

        .calendar-modal-item-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
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
            display: flex;
            justify-content: flex-end;
            margin-top: 0;
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
            --event-accent: var(--calendar-task);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 5px;
            background: var(--event-accent);
        }

        .calendar-hover-accent.task {
            --event-accent: var(--calendar-task);
        }

        .calendar-hover-accent.occurrence {
            --event-accent: var(--calendar-occurrence);
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

        body.theme-dark .calendar-modal,
        body.theme-dark .calendar-modal-meta-card,
        body.theme-dark .calendar-recurrence-builder,
        body.theme-dark .calendar-subtask-row,
        body.theme-dark .calendar-modal-item {
            background: rgba(9, 18, 42, 0.96);
            border-color: rgba(33, 86, 245, 0.18);
            color: #eef2ff;
        }

        body.theme-dark .calendar-modal label,
        body.theme-dark .calendar-modal-close,
        body.theme-dark .calendar-modal-item-meta {
            color: rgba(238, 242, 255, 0.82);
        }

        body.theme-dark .calendar-modal .muted {
            color: rgba(238, 242, 255, 0.7);
        }

        body.theme-dark .calendar-modal-item-title {
            color: #f8fbff;
        }

        body.theme-dark .calendar-modal {
            background:
                linear-gradient(180deg, rgba(7, 14, 31, 0.98), rgba(10, 21, 45, 0.98)),
                radial-gradient(circle at top right, rgba(33, 86, 245, 0.14), transparent 34%);
            box-shadow: 0 28px 58px rgba(0, 0, 0, 0.44);
        }

        body.theme-dark .calendar-modal-backdrop {
            background: rgba(2, 8, 23, 0.78);
        }

        body.theme-dark .calendar-modal input,
        body.theme-dark .calendar-modal select,
        body.theme-dark .calendar-modal textarea {
            background: rgba(255, 255, 255, 0.06) !important;
            color: #eef2ff !important;
            border-color: rgba(33, 86, 245, 0.2);
        }

        body.theme-dark .calendar-inline-check label,
        body.theme-dark .calendar-modal-header p,
        body.theme-dark .calendar-modal-meta-card strong {
            color: #eef2ff;
        }

        body.theme-dark .calendar-modal-close {
            background: rgba(255, 255, 255, 0.08);
        }

        body.theme-dark .calendar-subtask-empty {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(33, 86, 245, 0.18);
            color: rgba(238, 242, 255, 0.7);
        }

        body.theme-dark .calendar-view-switch .is-active {
            background: rgba(17, 28, 56, 0.96);
            color: #ffffff;
            border-color: rgba(139, 119, 255, 0.34);
            box-shadow: none;
        }

        body.theme-dark .calendar-shell .btn.secondary,
        body.theme-dark .calendar-shell button.secondary {
            background: rgba(7, 14, 31, 0.94);
            color: #eef2ff;
            border: 1px solid rgba(139, 119, 255, 0.2);
            box-shadow: none;
        }

        body.theme-dark .calendar-shell .btn.secondary:hover,
        body.theme-dark .calendar-shell button.secondary:hover {
            background: rgba(11, 20, 42, 0.98);
            border-color: rgba(139, 119, 255, 0.32);
        }

        body.theme-dark .calendar-canvas .btn:not(.secondary),
        body.theme-dark .calendar-modal .btn:not(.secondary),
        body.theme-dark .calendar-modal button:not(.secondary):not(.calendar-modal-close) {
            background: var(--violet-500);
            color: #ffffff;
            box-shadow: 0 16px 28px rgba(33, 86, 245, 0.22);
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
            .calendar-canvas-topbar,
            .calendar-modal-meta,
            .calendar-modal-row,
            .calendar-modal-row.title-row,
            .calendar-recurrence-grid,
            .calendar-subtask-row {
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
                    'task_id' => $event['task_id'] ?? $event['id'],
                    'title' => $event['title'],
                    'type' => $event['type'],
                    'time' => $event['time'],
                    'meta' => $event['meta'],
                    'status' => str_replace('_', ' ', $event['status']),
                    'category_color' => $event['category_color'] ?? null,
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
                <p class="calendar-toolbar-note">Choose a view, use the arrows to move through time, then click any day or hour slot to add a task directly into the calendar.</p>
            </div>
        </div>

        <div class="calendar-layout">
            <div class="calendar-canvas">
                <div class="calendar-canvas-topbar">
                    <div class="calendar-view-switch">
                        <a class="btn secondary {{ $view === 'daily' ? 'is-active' : '' }}" href="{{ route('calendar.index', ['view' => 'daily', 'date' => $focusDate->toDateString()]) }}">Day</a>
                        <a class="btn secondary {{ $view === 'weekly' ? 'is-active' : '' }}" href="{{ route('calendar.index', ['view' => 'weekly', 'date' => $focusDate->toDateString()]) }}">Week</a>
                        <a class="btn secondary {{ $view === 'monthly' ? 'is-active' : '' }}" href="{{ route('calendar.index', ['view' => 'monthly', 'date' => $focusDate->toDateString()]) }}">Month</a>
                    </div>

                    <div class="calendar-nav">
                        <a class="btn secondary calendar-nav-icon" href="{{ route('calendar.index', ['view' => $view, 'date' => $previousDate]) }}" aria-label="Previous period">‹</a>
                        <a class="btn secondary" href="{{ route('calendar.index', ['view' => $view, 'date' => $todayDate]) }}">Today</a>
                        <a class="btn secondary calendar-nav-icon" href="{{ route('calendar.index', ['view' => $view, 'date' => $nextDate]) }}" aria-label="Next period">›</a>
                    </div>

                    <div class="calendar-legend">
                        <span class="calendar-legend-item"><span class="calendar-legend-swatch task"></span> Due task</span>
                        <span class="calendar-legend-item"><span class="calendar-legend-swatch occurrence"></span> Scheduled occurrence</span>
                    </div>
                </div>

                <div class="calendar-canvas-header">
                    <div>
                        <span class="status">Calendar Canvas</span>
                        <h2>{{ $periodEventCount }} item{{ $periodEventCount === 1 ? '' : 's' }} in view</h2>
                        <p class="muted">Simple squares, task lines, and a selected-day panel inspired by your sample calendar.</p>
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
                                        <div class="calendar-event {{ $event['type'] }}" style="--event-line-accent: {{ $event['category_color'] ?: ($event['type'] === 'task' ? 'var(--calendar-task)' : 'var(--calendar-occurrence)') }};" title="{{ $event['time'] }} - {{ $event['title'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
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
                                        <div class="calendar-event {{ $event['type'] }}" style="--event-line-accent: {{ $event['category_color'] ?: ($event['type'] === 'task' ? 'var(--calendar-task)' : 'var(--calendar-occurrence)') }};" title="{{ $event['time'] }} - {{ $event['title'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
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
                            <p class="muted" style="margin: 0; max-width: 320px;">Select an hour to add a task quickly, or drag existing items to reschedule them inside the calendar.</p>
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
                                            <div class="calendar-event {{ $event['type'] }}" style="--event-line-accent: {{ $event['category_color'] ?: ($event['type'] === 'task' ? 'var(--calendar-task)' : 'var(--calendar-occurrence)') }};" title="{{ $event['time'] }} - {{ $event['title'] }}" draggable="true" data-type="{{ $event['type'] }}" data-id="{{ $event['id'] }}" data-time="{{ $event['time'] }}">
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
                    <span class="status" data-modal-status-badge>Quick Add</span>
                    <h3 id="calendar-modal-title" data-modal-heading>Add a task from the calendar</h3>
                    <p class="muted" data-modal-copy>Fill in the task details, add subtasks if needed, and save it directly into this calendar slot.</p>
                </div>
                <button type="button" class="calendar-modal-close" data-modal-close aria-label="Close quick add">x</button>
            </div>

            <form id="calendar-popup-form" class="calendar-modal-form" method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <input type="hidden" name="_method" value="POST" data-modal-method>
                <input type="hidden" name="from_calendar" value="1">
                <input type="hidden" name="subtasks_present" value="1">
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

                <div class="calendar-modal-row title-row">
                    <div>
                        <label for="calendar-modal-title-input">Title <span class="required-marker" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
                        <input id="calendar-modal-title-input" type="text" name="title" placeholder="Example: Review chapter notes" required aria-required="true">
                    </div>
                    <div>
                        <label for="calendar-modal-priority">Priority</label>
                        <select id="calendar-modal-priority" name="priority">
                            @foreach (['high', 'medium', 'low'] as $priority)
                                <option value="{{ $priority }}" @selected($priority === 'medium')>{{ ucfirst($priority) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="calendar-modal-row">
                    <div>
                        <label for="calendar-modal-status">Status</label>
                        <select id="calendar-modal-status" name="status">
                            @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                                <option value="{{ $status }}" @selected($status === 'pending')>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="calendar-modal-category">Category</label>
                        <select id="calendar-modal-category" name="category_id">
                            <option value="">None</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="calendar-modal-row">
                    <div>
                        <label for="calendar-modal-start-at">Start At</label>
                        <input id="calendar-modal-start-at" type="datetime-local" name="start_at">
                    </div>
                    <div>
                        <label for="calendar-modal-due-at">Due At</label>
                        <input id="calendar-modal-due-at" type="datetime-local" name="due_at">
                    </div>
                </div>

                <div class="calendar-inline-check">
                    <label>
                        <input type="checkbox" name="is_recurring" id="calendar-is-recurring" value="1">
                        Recurring Task
                    </label>
                </div>

                <div class="calendar-recurrence-builder" data-rule="">
                    <input type="hidden" name="recurrence_rule" id="calendar-recurrence-rule" value="">
                    <div class="calendar-recurrence-grid">
                        <div>
                            <label for="calendar-recurrence-frequency">Frequency</label>
                            <select id="calendar-recurrence-frequency" data-calendar-field="freq">
                                <option value="DAILY">Daily</option>
                                <option value="WEEKLY">Weekly</option>
                                <option value="MONTHLY">Monthly</option>
                            </select>
                        </div>
                        <div>
                            <label for="calendar-recurrence-interval">Interval</label>
                            <input id="calendar-recurrence-interval" type="number" min="1" value="1" data-calendar-field="interval">
                        </div>
                        <div>
                            <label>Weekly Days</label>
                            <div class="calendar-weekday-pills">
                                @foreach (['MO' => 'Mon', 'TU' => 'Tue', 'WE' => 'Wed', 'TH' => 'Thu', 'FR' => 'Fri', 'SA' => 'Sat', 'SU' => 'Sun'] as $value => $label)
                                    <label class="calendar-weekday-toggle">
                                        <input type="checkbox" value="{{ $value }}" data-calendar-field="day"> {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="calendar-modal-description">
                    <label for="calendar-modal-description">Description</label>
                    <textarea id="calendar-modal-description" name="description" rows="4" placeholder="Add notes, context, or a checklist summary"></textarea>
                </div>

                <div class="calendar-subtasks">
                    <div class="calendar-subtasks-header">
                        <div>
                            <strong>Subtasks</strong>
                            <p class="muted">Add smaller steps before creating the task.</p>
                        </div>
                        <button type="button" class="secondary" data-add-subtask>Add Subtask</button>
                    </div>
                    <div id="calendar-subtasks-list" class="calendar-subtasks-list">
                        <div class="calendar-subtask-empty">No subtasks yet. Use "Add Subtask" to create one.</div>
                    </div>
                </div>

                <div class="calendar-modal-actions">
                    <button type="submit" data-modal-submit-label>Create Task</button>
                    <button type="button" class="secondary" data-modal-close>Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';
        const taskStoreRoute = '{{ route('tasks.store') }}';
        const taskRouteTemplate = '{{ route('tasks.move', ':id') }}';
        const taskUpdateRouteTemplate = '{{ route('tasks.update', ':id') }}';
        const occurrenceRouteTemplate = '{{ route('occurrences.update', ':id') }}';
        const calendarEventsByDate = @json($calendarDetailEvents);
        const calendarTaskDetails = @json($taskDetails);
        const modalShell = document.querySelector('.calendar-modal-shell');
        const modalForm = document.getElementById('calendar-popup-form');
        const modalMethodInput = modalForm ? modalForm.querySelector('[data-modal-method]') : null;
        const modalDateInput = document.getElementById('calendar-modal-date');
        const modalTitleInput = document.getElementById('calendar-modal-title-input');
        const modalStartAtInput = document.getElementById('calendar-modal-start-at');
        const modalDueAtInput = document.getElementById('calendar-modal-due-at');
        const modalDateLabel = document.querySelector('[data-selected-date-label]');
        const modalTimeLabel = document.querySelector('[data-selected-time-label]');
        const modalStatusBadge = document.querySelector('[data-modal-status-badge]');
        const modalHeading = document.querySelector('[data-modal-heading]');
        const modalCopy = document.querySelector('[data-modal-copy]');
        const modalSubmitLabel = document.querySelector('[data-modal-submit-label]');
        const modalEvents = document.getElementById('calendar-modal-events');
        const modalSubtasksList = document.getElementById('calendar-subtasks-list');
        const addSubtaskButton = document.querySelector('[data-add-subtask]');
        const modalRecurringCheckbox = document.getElementById('calendar-is-recurring');
        const modalRecurrenceBuilder = document.querySelector('.calendar-recurrence-builder');
        const modalRecurrenceRuleInput = document.getElementById('calendar-recurrence-rule');
        const modalRecurrenceFreq = document.querySelector('[data-calendar-field="freq"]');
        const modalRecurrenceInterval = document.querySelector('[data-calendar-field="interval"]');
        const modalRecurrenceDays = Array.from(document.querySelectorAll('[data-calendar-field="day"]'));
        const hoverCard = document.getElementById('calendar-hover-card');
        let selectedDate = '{{ $focusDate->toDateString() }}';
        let selectedTime = '09:00';
        let subtaskIndex = 0;
        let editingTaskId = null;

        const escapeHtml = (value) => String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const toDateTimeLocalValue = (date, time = '09:00') => `${date}T${time}`;
        const extractTimeFromDateTime = (value) => value && value.includes('T') ? value.split('T')[1].slice(0, 5) : '09:00';

        const parseRecurrenceRule = (rule) => {
            const data = { freq: 'DAILY', interval: 1, byday: [] };

            if (!rule) {
                return data;
            }

            rule.split(';').forEach((part) => {
                const [key, value] = part.split('=');

                if (key === 'FREQ') {
                    data.freq = value;
                }

                if (key === 'INTERVAL') {
                    data.interval = parseInt(value || '1', 10);
                }

                if (key === 'BYDAY') {
                    data.byday = value ? value.split(',') : [];
                }
            });

            return data;
        };

        const buildRecurrenceRule = () => {
            if (!modalRecurringCheckbox || !modalRecurringCheckbox.checked || !modalRecurrenceRuleInput) {
                if (modalRecurrenceRuleInput) {
                    modalRecurrenceRuleInput.value = '';
                }

                return;
            }

            const freq = modalRecurrenceFreq.value;
            const interval = Math.max(parseInt(modalRecurrenceInterval.value || '1', 10), 1);
            let rule = `FREQ=${freq};INTERVAL=${interval}`;

            if (freq === 'WEEKLY') {
                const days = modalRecurrenceDays.filter((input) => input.checked).map((input) => input.value);

                if (days.length) {
                    rule += `;BYDAY=${days.join(',')}`;
                }
            }

            modalRecurrenceRuleInput.value = rule;
        };

        const toggleRecurrenceBuilder = () => {
            if (!modalRecurrenceBuilder || !modalRecurringCheckbox) {
                return;
            }

            modalRecurrenceBuilder.classList.toggle('is-hidden', !modalRecurringCheckbox.checked);
        };

        const renderSubtaskEmptyState = () => {
            if (!modalSubtasksList) {
                return;
            }

            if (modalSubtasksList.children.length === 0) {
                modalSubtasksList.innerHTML = '<div class="calendar-subtask-empty">No subtasks yet. Use "Add Subtask" to create one.</div>';
            }
        };

        const createSubtaskRow = (values = {}) => {
            if (!modalSubtasksList) {
                return;
            }

            const emptyState = modalSubtasksList.querySelector('.calendar-subtask-empty');

            if (emptyState) {
                emptyState.remove();
            }

            const row = document.createElement('div');
            row.className = 'calendar-subtask-row';
            row.innerHTML = `
                <div>
                    <input type="hidden" name="subtasks[${subtaskIndex}][id]" value="${values.id || ''}">
                    <label>Subtask Title</label>
                    <input type="text" name="subtasks[${subtaskIndex}][title]" value="${escapeHtml(values.title || '')}" placeholder="Example: Draft the outline">
                </div>
                <div>
                    <label>Status</label>
                    <select name="subtasks[${subtaskIndex}][status]">
                        <option value="pending"${(values.status || 'pending') === 'pending' ? ' selected' : ''}>Pending</option>
                        <option value="in_progress"${values.status === 'in_progress' ? ' selected' : ''}>In Progress</option>
                        <option value="review"${values.status === 'review' ? ' selected' : ''}>Review</option>
                        <option value="done"${values.status === 'done' ? ' selected' : ''}>Done</option>
                    </select>
                </div>
                <button type="button" class="secondary" data-remove-subtask>Remove</button>
            `;

            row.querySelector('[data-remove-subtask]').addEventListener('click', () => {
                row.remove();
                renderSubtaskEmptyState();
            });

            modalSubtasksList.appendChild(row);
            subtaskIndex += 1;
        };

        const resetRecurrenceBuilder = (rule = '') => {
            const parsedRule = parseRecurrenceRule(rule);

            if (modalRecurrenceFreq) {
                modalRecurrenceFreq.value = parsedRule.freq || 'DAILY';
            }

            if (modalRecurrenceInterval) {
                modalRecurrenceInterval.value = parsedRule.interval || 1;
            }

            modalRecurrenceDays.forEach((input) => {
                input.checked = parsedRule.byday.includes(input.value);
            });
        };

        const setModalMode = (mode, taskId = null) => {
            editingTaskId = mode === 'edit' ? taskId : null;

            if (modalForm) {
                modalForm.action = mode === 'edit' && taskId
                    ? taskUpdateRouteTemplate.replace(':id', taskId)
                    : taskStoreRoute;
            }

            if (modalMethodInput) {
                modalMethodInput.value = mode === 'edit' ? 'PATCH' : 'POST';
            }

            if (modalStatusBadge) {
                modalStatusBadge.textContent = mode === 'edit' ? 'Edit Task' : 'Quick Add';
            }

            if (modalHeading) {
                modalHeading.textContent = mode === 'edit' ? 'Edit a task from the calendar' : 'Add a task from the calendar';
            }

            if (modalCopy) {
                modalCopy.textContent = mode === 'edit'
                    ? 'Update the task details below and save the changes directly from your calendar.'
                    : 'Fill in the task details, add subtasks if needed, and save it directly into this calendar slot.';
            }

            if (modalSubmitLabel) {
                modalSubmitLabel.textContent = mode === 'edit' ? 'Save Changes' : 'Create Task';
            }
        };

        const fillModalFromTask = (task) => {
            if (!task || !modalForm) {
                return;
            }

            modalForm.querySelector('input[name="title"]').value = task.title || '';
            modalForm.querySelector('textarea[name="description"]').value = task.description || '';
            modalForm.querySelector('select[name="priority"]').value = task.priority || 'medium';
            modalForm.querySelector('select[name="status"]').value = task.status || 'pending';
            modalForm.querySelector('select[name="category_id"]').value = task.category_id ?? '';
            modalStartAtInput.value = task.start_at || '';
            modalDueAtInput.value = task.due_at || toDateTimeLocalValue(selectedDate, selectedTime);
            modalRecurringCheckbox.checked = Boolean(task.is_recurring);
            resetRecurrenceBuilder(task.recurrence_rule || '');
            toggleRecurrenceBuilder();
            buildRecurrenceRule();

            if (modalSubtasksList) {
                modalSubtasksList.innerHTML = '';
            }

            subtaskIndex = 0;
            (task.subtasks || []).forEach((subtask) => createSubtaskRow(subtask));
            renderSubtaskEmptyState();
            updateModalSelectionSummary();
        };

        const updateModalSelectionSummary = () => {
            const primaryDateTime = modalDueAtInput?.value || modalStartAtInput?.value || '';
            const derivedDate = primaryDateTime.includes('T') ? primaryDateTime.split('T')[0] : modalDateInput.value;

            if (derivedDate) {
                modalDateInput.value = derivedDate;
            }

            if (modalDateLabel) {
                modalDateLabel.textContent = derivedDate || 'Choose a day';
            }

            if (modalTimeLabel) {
                modalTimeLabel.textContent = extractTimeFromDateTime(primaryDateTime);
            }
        };

        const renderEventItems = (entries, allowEdit = false) => {
            if (!entries.length) {
                return '<div class="muted">No tasks scheduled for this day.</div>';
            }

            return entries.map((entry) => `
                <div class="${allowEdit ? 'calendar-modal-item' : 'calendar-hover-item'}">
                    ${allowEdit ? '' : `<span class="calendar-hover-accent ${escapeHtml(entry.type)}"></span>`}
                    <div>
                        ${allowEdit ? `
                            <div class="calendar-modal-item-top">
                                <div class="calendar-modal-item-title">${escapeHtml(entry.title)}</div>
                                <div class="calendar-modal-item-actions">
                                    <button type="button" class="btn secondary" data-edit-task="${escapeHtml(entry.task_id)}">Edit</button>
                                </div>
                            </div>
                        ` : `<div class="calendar-hover-item-title">${escapeHtml(entry.title)}</div>`}
                        <div class="${allowEdit ? 'calendar-modal-item-meta' : 'calendar-hover-item-meta'}">${escapeHtml(entry.time)} - ${escapeHtml(entry.meta)}</div>
                        ${allowEdit ? `<div class="calendar-modal-item-meta text-capitalize">${escapeHtml(entry.status)}</div>` : ''}
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
            modalForm.reset();
            setModalMode('create');
            subtaskIndex = 0;
            if (modalSubtasksList) {
                modalSubtasksList.innerHTML = '';
            }
            modalShell.hidden = false;
            modalDateInput.value = date;
            modalStartAtInput.value = toDateTimeLocalValue(date, time || '09:00');
            modalDueAtInput.value = toDateTimeLocalValue(date, time || '09:00');
            modalDateLabel.textContent = displayDate || date;
            modalTimeLabel.textContent = time || '09:00';
            resetRecurrenceBuilder('');
            toggleRecurrenceBuilder();
            buildRecurrenceRule();
            renderSubtaskEmptyState();
            renderModalEvents(date);
            updateModalSelectionSummary();

            window.requestAnimationFrame(() => {
                modalTitleInput.focus();
            });
        };

        const openEditTaskModal = (taskId) => {
            const task = calendarTaskDetails[taskId];

            if (!task || !modalShell || !modalForm) {
                return;
            }

            hideHoverCard();
            modalForm.reset();
            setModalMode('edit', taskId);
            modalShell.hidden = false;

            const primaryDateTime = task.due_at || task.start_at || toDateTimeLocalValue(selectedDate, selectedTime);
            const [datePart, timePart] = primaryDateTime.split('T');
            selectedDate = datePart || selectedDate;
            selectedTime = (timePart || selectedTime).slice(0, 5);
            modalDateInput.value = selectedDate;

            fillModalFromTask(task);
            renderModalEvents(selectedDate);
            updateSelectedTarget(selectedDate, selectedTime);

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
            setModalMode('create');
            if (modalSubtasksList) {
                modalSubtasksList.innerHTML = '';
            }
            subtaskIndex = 0;
            modalDateInput.value = '';
            modalDueAtInput.value = '';
            modalStartAtInput.value = '';
            resetRecurrenceBuilder('');
            toggleRecurrenceBuilder();
            buildRecurrenceRule();
            renderSubtaskEmptyState();
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

        if (modalEvents) {
            modalEvents.addEventListener('click', (event) => {
                const editButton = event.target.closest('[data-edit-task]');

                if (!editButton) {
                    return;
                }

                event.preventDefault();
                openEditTaskModal(editButton.getAttribute('data-edit-task'));
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modalShell && !modalShell.hidden) {
                closeCalendarModal();
            }
        });

        if (modalForm) {
            if (addSubtaskButton) {
                addSubtaskButton.addEventListener('click', () => createSubtaskRow());
            }

            if (modalRecurringCheckbox) {
                modalRecurringCheckbox.addEventListener('change', () => {
                    toggleRecurrenceBuilder();
                    buildRecurrenceRule();
                });
            }

            [modalRecurrenceFreq, modalRecurrenceInterval, ...modalRecurrenceDays]
                .filter(Boolean)
                .forEach((input) => {
                    input.addEventListener('change', buildRecurrenceRule);
                });

            [modalStartAtInput, modalDueAtInput]
                .filter(Boolean)
                .forEach((input) => {
                    input.addEventListener('input', updateModalSelectionSummary);
                });

            modalForm.addEventListener('submit', () => {
                buildRecurrenceRule();
            });

            toggleRecurrenceBuilder();
            buildRecurrenceRule();
            renderSubtaskEmptyState();
        }

        hideHoverCard();
    </script>
@endsection
