@extends('layouts.app')

@section('content')
    <div class="hero">
        <div>
            <div class="badge">Stay focused. Stay playful.</div>
            <h1 class="hero-title">Your study planner that feels like a game.</h1>
            <p class="muted">EtuAide helps students map tasks, track progress, and celebrate wins with a clean, purple-powered experience.</p>
            <div class="actions">
                <a class="btn" href="{{ route('register') }}">Create your free account</a>
                <a class="btn secondary" href="{{ route('login') }}">I already have an account</a>
            </div>
        </div>
        <div class="hero-card floating">
            <h3>Today at a glance</h3>
            <ul>
                <li>2 tasks in progress</li>
                <li>1 review checkpoint</li>
                <li>3 quick wins to finish</li>
            </ul>
            <div class="status">Powered by priorities + focus time</div>
        </div>
    </div>

    <div class="card">
        <h2 class="section-title">Why students love EtuAide</h2>
        <div class="cards">
            <div class="card">
                <h3>Task storytelling</h3>
                <p class="muted">Break big goals into mini victories with subtasks and progress states.</p>
            </div>
            <div class="card">
                <h3>Calendar flow</h3>
                <p class="muted">Drag and drop tasks into daily, weekly, or monthly views that actually make sense.</p>
            </div>
            <div class="card">
                <h3>Reminders that help</h3>
                <p class="muted">Email + in-app nudges keep you steady without the stress.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="section-title">Built for focus + balance</h2>
        <p class="muted">Dark/light modes, priority heatmaps, and gentle motivation so you can plan smarter and relax sooner.</p>
        <div class="actions">
            <a class="btn" href="{{ route('about') }}">Learn more</a>
            <a class="btn secondary" href="{{ route('contact') }}">Talk to us</a>
        </div>
    </div>
@endsection
