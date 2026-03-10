<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EtuAide Planner</title>
    <style>
        :root {
            --ink: #0a0a0f;
            --paper: #ffffff;
            --lavender: #efe7ff;
            --violet-300: #c4b5fd;
            --violet-400: #a855f7;
            --violet-500: #8b5cf6;
            --violet-700: #6d28d9;
            --violet-900: #3b0764;
            --shadow: rgba(20, 6, 35, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Fredoka", "Baloo 2", "Comic Neue", sans-serif;
            background: radial-gradient(circle at top, #f7f2ff 0%, #f0e8ff 35%, #e9e0ff 100%);
            color: var(--ink);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body.theme-dark {
            color: #f3f1ff;
            background: radial-gradient(circle at top, #1a0f2e 0%, #140a28 35%, #0b0618 100%);
        }

        body.theme-dark .card {
            background: #150b2b;
            border-color: rgba(196, 181, 253, 0.15);
            box-shadow: 0 14px 30px rgba(6, 2, 16, 0.4);
        }

        body.theme-dark header {
            background: rgba(15, 10, 25, 0.92);
            border-bottom-color: rgba(196, 181, 253, 0.1);
        }

        body.theme-dark input,
        body.theme-dark select,
        body.theme-dark textarea {
            background: rgba(255, 255, 255, 0.08);
            color: #f3f1ff;
        }

        body.theme-dark .muted {
            color: rgba(243, 241, 255, 0.7);
        }

        body::before,
        body::after {
            content: "";
            position: absolute;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            filter: blur(0px);
            opacity: 0.4;
            z-index: 0;
        }

        body::before {
            background: radial-gradient(circle, var(--violet-300) 0%, transparent 70%);
            top: -120px;
            left: -140px;
        }

        body::after {
            background: radial-gradient(circle, var(--violet-400) 0%, transparent 70%);
            bottom: -140px;
            right: -100px;
        }

        body.palette-grape {
            --violet-300: #ddd6fe;
            --violet-400: #c084fc;
            --violet-500: #9333ea;
            --violet-700: #7e22ce;
            --violet-900: #4c1d95;
            --lavender: #f3e8ff;
        }

        body.palette-midnight {
            --violet-300: #c7d2fe;
            --violet-400: #a5b4fc;
            --violet-500: #6366f1;
            --violet-700: #4f46e5;
            --violet-900: #312e81;
            --lavender: #eef2ff;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(93, 33, 188, 0.12);
            backdrop-filter: blur(10px);
        }

        .nav {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            gap: 16px;
            padding: 16px 24px;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .brand-bubble {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--violet-500), var(--violet-700));
            display: grid;
            place-items: center;
            color: white;
            font-weight: 700;
            box-shadow: 0 10px 20px var(--shadow);
        }

        .nav-links {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            font-weight: 600;
        }

        .nav-links a {
            padding: 6px 10px;
            border-radius: 999px;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav-links a:hover {
            background: var(--lavender);
            color: var(--violet-900);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 24px 64px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--paper);
            border: 1px solid rgba(93, 33, 188, 0.1);
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 14px 30px var(--shadow);
            margin-bottom: 18px;
        }

        .grid {
            display: grid;
            gap: 18px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .status {
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--lavender);
            color: var(--violet-900);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .form-grid {
            display: grid;
            gap: 12px;
        }

        input, select, textarea, button {
            font: inherit;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid rgba(93, 33, 188, 0.2);
            background: #fbfaff;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid rgba(139, 92, 246, 0.5);
            border-color: var(--violet-500);
        }

        button, .btn {
            background: linear-gradient(135deg, var(--violet-500), var(--violet-700));
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 999px;
            box-shadow: 0 12px 20px rgba(109, 40, 217, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover, .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(109, 40, 217, 0.3);
        }

        .btn.secondary, button.secondary {
            background: #ffffff;
            color: var(--violet-700);
            border: 1px solid rgba(109, 40, 217, 0.3);
            box-shadow: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid rgba(93, 33, 188, 0.1);
        }

        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            background: var(--lavender);
            color: var(--violet-900);
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .muted {
            color: rgba(10, 10, 15, 0.6);
        }

        body.theme-dark .muted {
            color: rgba(243, 241, 255, 0.7);
        }

        .inline-form {
            display: inline;
        }

        .hero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            align-items: center;
            margin-top: 24px;
        }

        .hero-title {
            font-size: clamp(2.2rem, 2.5vw, 3.1rem);
            font-weight: 700;
            margin-bottom: 12px;
        }

        .hero-card {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.95), rgba(239, 231, 255, 0.9));
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 24px 50px rgba(109, 40, 217, 0.2);
        }

        body.theme-dark .hero-card {
            background: linear-gradient(160deg, rgba(27, 14, 51, 0.95), rgba(43, 22, 82, 0.9));
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(109, 40, 217, 0.12);
            color: var(--violet-900);
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        body.theme-dark .badge {
            background: rgba(196, 181, 253, 0.2);
            color: #f3f1ff;
        }

        .section-title {
            font-size: 1.6rem;
            margin-bottom: 12px;
        }

        .cards {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }

        .weekday-pill {
            background: rgba(109, 40, 217, 0.1);
            color: var(--violet-900);
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 600;
            text-align: center;
        }

        .calendar-grid {
            display: grid;
            gap: 16px;
        }

        .calendar-grid.calendar-weekly,
        .calendar-grid.calendar-monthly {
            grid-template-columns: repeat(7, minmax(140px, 1fr));
        }

        .calendar-grid.calendar-daily {
            grid-template-columns: 1fr;
        }

        .calendar-day {
            min-height: 220px;
        }

        .calendar-day.drag-over {
            outline: 2px dashed var(--violet-500);
        }

        .calendar-day.is-today {
            border-color: var(--violet-500);
            box-shadow: 0 16px 32px rgba(109, 40, 217, 0.25);
        }

        .calendar-day.is-outside {
            opacity: 0.55;
        }

        .day-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .quick-add {
            display: grid;
            gap: 8px;
            margin-bottom: 10px;
        }

        .quick-add input[type="text"] {
            background: #ffffff;
        }

        .quick-add-row {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .quick-add-time {
            width: 100px;
        }

        .calendar-item {
            background: var(--lavender);
            border-radius: 14px;
            padding: 10px 12px;
            margin-bottom: 8px;
            box-shadow: 0 6px 14px rgba(109, 40, 217, 0.2);
            cursor: grab;
        }

        .calendar-item.dragging {
            opacity: 0.6;
            cursor: grabbing;
        }

        body.theme-dark .calendar-item {
            background: rgba(196, 181, 253, 0.15);
        }

        .recurrence-builder {
            background: rgba(109, 40, 217, 0.08);
        }

        body.theme-dark .recurrence-builder {
            background: rgba(196, 181, 253, 0.1);
        }

        .recurrence-builder.is-hidden {
            display: none;
        }

        .weekday-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .weekday-toggle {
            background: var(--lavender);
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 600;
        }

        body.theme-dark .weekday-toggle {
            background: rgba(196, 181, 253, 0.2);
        }

        .weekday-toggle input {
            margin-right: 6px;
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @media (max-width: 900px) {
            .calendar-weekdays,
            .calendar-grid.calendar-weekly,
            .calendar-grid.calendar-monthly {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }} {{ auth()->check() && auth()->user()->palette ? 'palette-' . auth()->user()->palette : '' }}">
<header>
    <div class="nav">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-bubble">E</span>
            <span>EtuAide Planner</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('faq') }}">FAQ</a>
            <a href="{{ route('contact') }}">Contact</a>
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('tasks.index') }}">Tasks</a>
                <a href="{{ route('calendar.index') }}">Calendar</a>
                <a href="{{ route('categories.index') }}">Categories</a>
                <a href="{{ route('notifications.index') }}">Notifications</a>
                <a href="{{ route('settings.edit') }}">Settings</a>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
            @endauth
        </div>
        <div class="actions">
            @auth
                <span class="muted">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="secondary">Logout</button>
                </form>
            @else
                <a class="btn secondary" href="{{ route('login') }}">Login</a>
                <a class="btn" href="{{ route('register') }}">Sign Up</a>
            @endauth
        </div>
    </div>
</header>

<div class="container">
    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
