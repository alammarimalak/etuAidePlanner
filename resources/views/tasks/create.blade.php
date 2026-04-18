@extends('layouts.app')

@push('styles')
    <style>
        .task-form-row {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: end;
        }

        .task-form-row.title-row {
            grid-template-columns: minmax(0, 1.6fr) minmax(220px, 0.8fr);
        }

        .task-recurrence-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: end;
        }

        .task-description textarea {
            width: 100%;
            min-height: 140px;
            display: block;
        }

        .weekday-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .weekday-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        @media (max-width: 760px) {
            .task-form-row,
            .task-form-row.title-row,
            .task-recurrence-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <h1>Create Task</h1>

    <div class="card">
        <form method="POST" action="{{ route('tasks.store') }}" class="form-grid" id="task-form">
            @csrf
            <div class="task-form-row title-row">
                <div>
                    <label>Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>
                <div>
                    <label>Priority</label>
                    <select name="priority">
                        @foreach (['high', 'medium', 'low'] as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="task-form-row">
                <div>
                    <label>Status</label>
                    <select name="status">
                        @foreach (['pending', 'in_progress', 'review', 'done'] as $status)
                            <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">None</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="task-form-row">
                <div>
                    <label>Start At</label>
                    <input type="datetime-local" name="start_at" value="{{ old('start_at') }}">
                </div>
                <div>
                    <label>Due At</label>
                    <input type="datetime-local" name="due_at" value="{{ old('due_at') }}">
                </div>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1" @checked(old('is_recurring'))>
                    Recurring Task
                </label>
            </div>

            <div class="card recurrence-builder" data-rule="{{ old('recurrence_rule') }}">
                <h3>Recurrence Builder</h3>
                <input type="hidden" name="recurrence_rule" id="recurrence_rule" value="{{ old('recurrence_rule') }}">
                <div class="task-recurrence-grid">
                    <div>
                        <label>Frequency</label>
                        <select data-field="freq">
                            <option value="DAILY">Daily</option>
                            <option value="WEEKLY">Weekly</option>
                            <option value="MONTHLY">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label>Interval</label>
                        <input type="number" min="1" value="1" data-field="interval">
                    </div>
                    <div>
                        <label>Weekly Days</label>
                        <div class="weekday-pills">
                            @foreach (['MO' => 'Mon', 'TU' => 'Tue', 'WE' => 'Wed', 'TH' => 'Thu', 'FR' => 'Fri', 'SA' => 'Sat', 'SU' => 'Sun'] as $value => $label)
                                <label class="weekday-toggle">
                                    <input type="checkbox" value="{{ $value }}" data-field="day"> {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="task-description">
                <label>Description</label>
                <textarea name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="actions">
                <button type="submit">Create Task</button>
                <a class="btn secondary" href="{{ route('tasks.index') }}">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const isRecurring = document.getElementById('is_recurring');
        const builder = document.querySelector('.recurrence-builder');
        const ruleInput = document.getElementById('recurrence_rule');
        const freqSelect = builder.querySelector('[data-field="freq"]');
        const intervalInput = builder.querySelector('[data-field="interval"]');
        const dayInputs = Array.from(builder.querySelectorAll('[data-field="day"]'));

        function parseRule(rule) {
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
        }

        function buildRule() {
            if (!isRecurring.checked) {
                ruleInput.value = '';
                return;
            }
            const freq = freqSelect.value;
            const interval = Math.max(parseInt(intervalInput.value || '1', 10), 1);
            let rule = `FREQ=${freq};INTERVAL=${interval}`;
            if (freq === 'WEEKLY') {
                const days = dayInputs.filter((input) => input.checked).map((input) => input.value);
                if (days.length) {
                    rule += `;BYDAY=${days.join(',')}`;
                }
            }
            ruleInput.value = rule;
        }

        function toggleBuilder() {
            builder.classList.toggle('is-hidden', !isRecurring.checked);
        }

        const initial = parseRule(builder.dataset.rule);
        freqSelect.value = initial.freq || 'DAILY';
        intervalInput.value = initial.interval || 1;
        dayInputs.forEach((input) => {
            input.checked = initial.byday.includes(input.value);
        });
        toggleBuilder();
        buildRule();

        isRecurring.addEventListener('change', () => {
            toggleBuilder();
            buildRule();
        });

        [freqSelect, intervalInput, ...dayInputs].forEach((input) => {
            input.addEventListener('change', buildRule);
        });
    </script>
@endsection
