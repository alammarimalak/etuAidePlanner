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

        body.theme-dark .compose-recipient,
        body.theme-dark .compose-meta-row {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(139, 119, 255, 0.16);
        }

        body.theme-dark .compose-recipient small,
        body.theme-dark .compose-meta-row span {
            color: rgba(238, 242, 255, 0.68);
        }

        @media (max-width: 960px) {
            .compose-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
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
                    <label for="compose-subject">Subject</label>
                    <input id="compose-subject" type="text" name="subject" value="{{ old('subject') }}" required>
                </div>

                <div class="compose-field">
                    <label for="compose-message">Message</label>
                    <textarea id="compose-message" name="message" class="compose-textarea" required>{{ old('message') }}</textarea>
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
                    <label>Recipients</label>
                    <div class="compose-recipient-list">
                        @foreach ($students as $student)
                            <label class="compose-recipient">
                                <input
                                    type="checkbox"
                                    name="student_ids[]"
                                    value="{{ $student->id }}"
                                    @checked(collect(old('student_ids', []))->contains($student->id))
                                >
                                <span>
                                    <strong>{{ $student->name }}</strong>
                                    <small>{{ $student->email }}</small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
