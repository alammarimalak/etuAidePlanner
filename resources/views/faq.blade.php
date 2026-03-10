@extends('layouts.app')

@section('content')
    <div class="card">
        <h1 class="section-title">Frequently Asked Questions</h1>
        <p class="muted">Quick answers to the most common questions from students and admins.</p>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Can I change task status?</h3>
            <p class="muted">Yes. Choose from pending, in progress, review, or done for each task and subtask.</p>
        </div>
        <div class="card">
            <h3>How do reminders work?</h3>
            <p class="muted">Set reminders on tasks. You will see in-app notifications and optional email alerts.</p>
        </div>
        <div class="card">
            <h3>What counts as inactive?</h3>
            <p class="muted">No login or task activity for 14 days. Admins see alerts automatically.</p>
        </div>
        <div class="card">
            <h3>Can admins reset passwords?</h3>
            <p class="muted">Yes, admin tooling is prepared for account management workflows.</p>
        </div>
    </div>
@endsection
