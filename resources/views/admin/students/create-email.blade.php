@extends('layouts.app')

@push('styles')
    <style>
        .admin-compose {
            display: grid;
            gap: 24px;
        }

        .admin-compose-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .compose-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(280px, 0.75fr);
            gap: 20px;
        }

        .compose-panel {
            display: grid;
            gap: 18px;
        }

        .compose-field {
            display: grid;
            gap: 8px;
        }

        .compose-recipient-list {
            display: grid;
            gap: 10px;
            max-height: 520px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .compose-latest-list {
            display: grid;
            gap: 10px;
        }

        .compose-latest-recipient {
            display: grid;
            gap: 4px;
            justify-items: start;
            text-align: left;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.74);
            color: inherit;
            box-shadow: none;
        }

        .compose-recipient {
            display: grid;
            grid-template-columns: 18px minmax(0, 1fr);
            gap: 12px;
            align-items: start;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.74);
        }

        .compose-recipient strong {
            display: block;
            margin-bottom: 2px;
        }

        .compose-latest-recipient strong {
            display: block;
        }

        .compose-latest-recipient small,
        .compose-recipient small {
            color: rgba(5, 8, 22, 0.64);
        }

        .compose-textarea {
            min-height: 320px;
            resize: vertical;
        }

        .compose-meta-card {
            display: grid;
            gap: 8px;
        }

        .compose-meta-row {
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.74);
        }

        .compose-meta-row span {
            display: block;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(5, 8, 22, 0.52);
            margin-bottom: 6px;
        }

        .compose-meta-row strong,
        .compose-meta-row p {
            margin: 0;
        }

        .compose-search-hint,
        .compose-empty-state,
        .compose-error {
            margin: 0;
            font-size: 0.92rem;
            color: rgba(5, 8, 22, 0.64);
        }

        .compose-error {
            color: #b91c1c;
            font-weight: 600;
        }

        .compose-empty-state {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px dashed var(--line);
            background: rgba(255, 255, 255, 0.48);
        }

        .compose-latest-recipient.is-selected,
        .compose-recipient.is-selected {
            border-color: rgba(33, 86, 245, 0.4);
            background: rgba(33, 86, 245, 0.08);
        }

        .is-hidden {
            display: none !important;
        }

        body.theme-dark .compose-latest-recipient,
        body.theme-dark .compose-recipient,
        body.theme-dark .compose-meta-row {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(139, 119, 255, 0.16);
        }

        body.theme-dark .compose-latest-recipient.is-selected,
        body.theme-dark .compose-recipient.is-selected {
            background: rgba(33, 86, 245, 0.18);
            border-color: rgba(139, 119, 255, 0.34);
        }

        body.theme-dark .compose-latest-recipient small,
        body.theme-dark .compose-recipient small,
        body.theme-dark .compose-meta-row span,
        body.theme-dark .compose-search-hint,
        body.theme-dark .compose-empty-state {
            color: rgba(238, 242, 255, 0.68);
        }

        body.theme-dark .compose-empty-state {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(139, 119, 255, 0.2);
        }

        @media (max-width: 960px) {
            .compose-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $selectedStudentIds = collect(old('student_ids', []))->map(fn ($id) => (int) $id);
    @endphp

    <section class="admin-compose">
        <div class="admin-compose-header">
            <div>
                <span class="status">Admin Mail</span>
                <h1 style="margin-bottom: 8px;">Create an email</h1>
                <p class="muted" style="margin: 0;">
                    Send a direct message to one or more students by email and create the same message inside the app.
                </p>
            </div>

            <div class="actions">
                <a class="btn secondary" href="{{ route('admin.students.index') }}">Back to students</a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.students.email.send') }}" class="compose-shell">
            @csrf

            <div class="card compose-panel">
                <div class="compose-field">
                    <label for="compose-from">From</label>
                    <input id="compose-from" type="email" value="{{ $senderEmail }}" readonly>
                </div>

                <div class="compose-field">
                    <label for="compose-subject">Subject <span class="required-marker" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
                    <input id="compose-subject" type="text" name="subject" value="{{ old('subject') }}" required aria-required="true">
                    @error('subject')
                        <p class="compose-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="compose-field">
                    <label for="compose-message">Message <span class="required-marker" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
                    <textarea id="compose-message" name="message" class="compose-textarea" required aria-required="true">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="compose-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="actions">
                    <button type="submit">Send email</button>
                    <a class="btn secondary" href="{{ route('admin.students.index') }}">Cancel</a>
                </div>
            </div>

            <div class="card compose-panel">
                <div class="compose-meta-card">
                    <div class="compose-meta-row">
                        <span>Delivery</span>
                        <strong>Every selected student receives an email and an in-app notification.</strong>
                    </div>
                    <div class="compose-meta-row">
                        <span>Sender</span>
                        <p>{{ $senderEmail }}</p>
                    </div>
                </div>

                <div class="compose-field">
                    <label for="recipient-search">Recipients</label>
                    <input
                        id="recipient-search"
                        type="search"
                        name="student_search"
                        value="{{ old('student_search') }}"
                        placeholder="Search by student name or email"
                        autocomplete="off"
                        data-recipient-search
                    >
                    <p class="compose-search-hint">Use one search bar to find a student by name, email, or both.</p>
                    @error('student_search')
                        <p class="compose-error">{{ $message }}</p>
                    @enderror

                    @if ($latestRecipients->isNotEmpty())
                        <div class="compose-latest-list">
                            @foreach ($latestRecipients as $student)
                                <button
                                    type="button"
                                    class="compose-latest-recipient {{ $selectedStudentIds->contains($student->id) ? 'is-selected' : '' }}"
                                    data-select-student="{{ $student->id }}"
                                    data-recipient-search-value="{{ $student->name }} {{ $student->email }}"
                                >
                                    <strong>{{ $student->name }}</strong>
                                    <small>{{ $student->email }}</small>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="compose-recipient-list">
                        @foreach ($students as $student)
                            <label
                                class="compose-recipient {{ $selectedStudentIds->contains($student->id) ? 'is-selected' : '' }}"
                                data-recipient-item
                                data-recipient-match="{{ mb_strtolower($student->name . ' ' . $student->email) }}"
                            >
                                <input
                                    type="checkbox"
                                    name="student_ids[]"
                                    value="{{ $student->id }}"
                                    @checked($selectedStudentIds->contains($student->id))
                                    data-student-id="{{ $student->id }}"
                                    data-recipient-label="{{ $student->name }} {{ $student->email }}"
                                >
                                <span>
                                    <strong>{{ $student->name }}</strong>
                                    <small>{{ $student->email }}</small>
                                </span>
                            </label>
                        @endforeach

                        <p class="compose-empty-state is-hidden" data-empty-recipient-results>
                            No student matches this search yet.
                        </p>
                    </div>
                    @error('student_ids')
                        <p class="compose-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </section>

    <script>
        (function () {
            const searchInput = document.querySelector('[data-recipient-search]');
            const recipientItems = Array.from(document.querySelectorAll('[data-recipient-item]'));
            const latestRecipientButtons = Array.from(document.querySelectorAll('[data-select-student]'));
            const emptyState = document.querySelector('[data-empty-recipient-results]');

            if (!searchInput || recipientItems.length === 0) {
                return;
            }

            function syncSelectedState() {
                const selectedIds = new Set(
                    recipientItems
                        .map((item) => item.querySelector('[data-student-id]'))
                        .filter((checkbox) => checkbox && checkbox.checked)
                        .map((checkbox) => checkbox.dataset.studentId)
                );

                recipientItems.forEach((item) => {
                    const checkbox = item.querySelector('[data-student-id]');
                    item.classList.toggle('is-selected', Boolean(checkbox && checkbox.checked));
                });

                latestRecipientButtons.forEach((button) => {
                    button.classList.toggle('is-selected', selectedIds.has(button.dataset.selectStudent));
                });
            }

            function filterRecipients() {
                const query = searchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                recipientItems.forEach((item) => {
                    const matches = query === '' || item.dataset.recipientMatch.includes(query);
                    item.classList.toggle('is-hidden', !matches);

                    if (matches) {
                        visibleCount += 1;
                    }
                });

                if (emptyState) {
                    emptyState.classList.toggle('is-hidden', visibleCount !== 0);
                }
            }

            recipientItems.forEach((item) => {
                const checkbox = item.querySelector('[data-student-id]');

                if (!checkbox) {
                    return;
                }

                checkbox.addEventListener('change', function () {
                    if (checkbox.checked && searchInput.value.trim() === '') {
                        searchInput.value = checkbox.dataset.recipientLabel || '';
                        filterRecipients();
                    }

                    syncSelectedState();
                });
            });

            latestRecipientButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    const checkbox = document.querySelector('[data-student-id="' + button.dataset.selectStudent + '"]');

                    if (!checkbox) {
                        return;
                    }

                    checkbox.checked = !checkbox.checked;

                    if (searchInput.value.trim() === '') {
                        searchInput.value = button.dataset.recipientSearchValue || '';
                        filterRecipients();
                    }

                    syncSelectedState();
                });
            });

            searchInput.addEventListener('input', filterRecipients);

            syncSelectedState();
            filterRecipients();
        })();
    </script>
@endsection
