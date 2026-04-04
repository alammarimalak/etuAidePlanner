<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EtuAide Planner</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @if (request()->routeIs('home', 'about', 'faq', 'contact'))
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @endif
    <style>
        :root {
            --ink: #050816;
            --ink-soft: #243152;
            --paper: #ffffff;
            --paper-soft: rgba(255, 255, 255, 0.84);
            --lavender: #eef2ff;
            --violet-300: #d9d3ff;
            --violet-400: #8b77ff;
            --violet-500: #2156f5;
            --violet-700: #5d3ef0;
            --violet-900: #101935;
            --line: rgba(16, 25, 53, 0.12);
            --line-strong: rgba(16, 25, 53, 0.18);
            --shadow: rgba(5, 8, 22, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI Variable", "Aptos", "Trebuchet MS", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(33, 86, 245, 0.12), transparent 28%),
                radial-gradient(circle at 85% 8%, rgba(93, 62, 240, 0.12), transparent 26%),
                linear-gradient(180deg, #f7f9ff 0%, #eef2ff 42%, #ffffff 100%);
            color: var(--ink);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            line-height: 1.6;
        }

        body.theme-dark {
            color: #eef2ff;
            background:
                radial-gradient(circle at top left, rgba(33, 86, 245, 0.22), transparent 28%),
                radial-gradient(circle at 85% 10%, rgba(93, 62, 240, 0.18), transparent 24%),
                linear-gradient(180deg, #08101f 0%, #0d1730 46%, #050816 100%);
        }

        body.theme-dark .card {
            background: rgba(9, 18, 42, 0.92);
            border-color: rgba(139, 119, 255, 0.18);
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.3);
        }

        body.theme-dark header {
            background: rgba(5, 8, 22, 0.84);
            border-bottom-color: rgba(139, 119, 255, 0.12);
        }

        body.theme-dark input,
        body.theme-dark select,
        body.theme-dark textarea {
            background: rgba(255, 255, 255, 0.06);
            color: #eef2ff;
            border-color: rgba(139, 119, 255, 0.18);
        }

        body.theme-dark .muted {
            color: rgba(238, 242, 255, 0.68);
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
            background: radial-gradient(circle, rgba(33, 86, 245, 0.2) 0%, transparent 70%);
            top: -120px;
            left: -140px;
        }

        body::after {
            background: radial-gradient(circle, rgba(93, 62, 240, 0.18) 0%, transparent 70%);
            bottom: -140px;
            right: -100px;
        }

        body.palette-grape {
            --violet-300: #e1d8ff;
            --violet-400: #9f87ff;
            --violet-500: #4d63ff;
            --violet-700: #6b45f5;
            --violet-900: #161d45;
            --lavender: #f2efff;
        }

        body.palette-midnight {
            --violet-300: #cdd7ff;
            --violet-400: #7d98ff;
            --violet-500: #295dff;
            --violet-700: #4e4ff1;
            --violet-900: #0f1733;
            --lavender: #eef3ff;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(255, 255, 255, 0.82);
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(14px);
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
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .brand-bubble {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(33, 86, 245, 0.12);
            flex-shrink: 0;
        }

        .brand-bubble img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
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
            color: rgba(5, 8, 22, 0.74);
        }

        .nav-links a:hover {
            background: rgba(33, 86, 245, 0.08);
            color: var(--ink);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px 72px;
            position: relative;
            z-index: 1;
        }

        .student-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            position: relative;
            z-index: 1;
        }

        .student-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            padding: 28px 20px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(238, 242, 255, 0.82)),
                linear-gradient(180deg, rgba(33, 86, 245, 0.08), rgba(93, 62, 240, 0.08));
            border-right: 1px solid var(--line);
            backdrop-filter: blur(18px);
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .student-sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-sidebar-copy strong {
            display: block;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
        }

        .student-sidebar-copy span {
            color: rgba(5, 8, 22, 0.6);
            font-size: 0.92rem;
        }

        .student-sidebar-nav {
            display: grid;
            gap: 8px;
        }

        .student-sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 18px;
            font-weight: 700;
            color: rgba(5, 8, 22, 0.72);
            border: 1px solid transparent;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .student-sidebar-link:hover {
            transform: translateX(2px);
            color: var(--ink);
            background: rgba(33, 86, 245, 0.08);
            border-color: rgba(33, 86, 245, 0.12);
        }

        .student-sidebar-link.is-active {
            color: #ffffff;
            background: linear-gradient(135deg, #0b132d 0%, var(--violet-500) 58%, var(--violet-700) 100%);
            box-shadow: 0 18px 30px rgba(33, 86, 245, 0.2);
        }

        .student-sidebar-link small {
            color: inherit;
            opacity: 0.8;
        }

        .student-sidebar-footer {
            margin-top: auto;
            display: grid;
            gap: 14px;
        }

        .student-sidebar-user {
            padding: 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid var(--line);
        }

        .student-sidebar-user strong {
            display: block;
            margin-bottom: 4px;
        }

        .student-sidebar-user span {
            color: rgba(5, 8, 22, 0.6);
            font-size: 0.92rem;
        }

        .student-sidebar-logout {
            width: 100%;
        }

        .student-sidebar-logout button {
            display: flex;
            width: 100%;
            justify-content: center;
        }

        .student-main {
            min-width: 0;
            padding: 34px 32px 60px;
        }

        .student-main .container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .public-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .public-main {
            flex: 1 0 auto;
        }

        .main-footer-container {
            padding-top: 0;
            padding-bottom: 56px;
        }

        .main-pages-footer {
            margin-top: auto;
        }

        .main-pages-footer .footer-card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 18px 40px var(--shadow);
        }

        .main-pages-footer .project-logo-wrap {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #f4f5f7;
            border: 1px dashed rgba(16, 25, 53, 0.24);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .main-pages-footer .project-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .main-pages-footer .social-links {
            display: flex;
        }

        .main-pages-footer .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1rem;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            color: var(--ink);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .main-pages-footer .social-link:hover {
            border-color: rgba(33, 86, 245, 0.28);
            background: rgba(33, 86, 245, 0.08);
            color: var(--violet-500);
        }

        .main-pages-footer .footer-link {
            text-decoration: none;
            color: rgba(5, 8, 22, 0.64);
            font-weight: 600;
        }

        .main-pages-footer .footer-link:hover {
            color: var(--violet-500);
        }

        body.theme-dark .main-pages-footer .footer-card {
            background: rgba(9, 18, 42, 0.92);
            border-color: rgba(139, 119, 255, 0.18);
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.3);
        }

        body.theme-dark .main-pages-footer .project-logo-wrap {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(139, 119, 255, 0.24);
        }

        body.theme-dark .main-pages-footer .social-link {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(139, 119, 255, 0.18);
            color: #eef2ff;
        }

        body.theme-dark .main-pages-footer .social-link:hover {
            background: rgba(33, 86, 245, 0.14);
            color: #ffffff;
        }

        body.theme-dark .main-pages-footer .text-dark {
            color: #f7f9ff !important;
        }

        body.theme-dark .main-pages-footer .text-secondary {
            color: rgba(238, 242, 255, 0.68) !important;
        }

        body.theme-dark .main-pages-footer .footer-link {
            color: rgba(238, 242, 255, 0.72);
        }

        body.theme-dark .main-pages-footer .footer-link:hover {
            color: #ffffff;
        }

        .card {
            background: var(--paper-soft);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 18px 40px var(--shadow);
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
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
            background: rgba(33, 86, 245, 0.1);
            color: var(--violet-900);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 12px;
            border: 1px solid rgba(33, 86, 245, 0.12);
        }

        .form-grid {
            display: grid;
            gap: 12px;
        }

        input, select, textarea, button {
            font: inherit;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid var(--line-strong);
            background: rgba(255, 255, 255, 0.88);
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid rgba(33, 86, 245, 0.28);
            border-color: var(--violet-500);
        }

        button, .btn {
            background: linear-gradient(135deg, #0b132d 0%, var(--violet-500) 58%, var(--violet-700) 100%);
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-weight: 700;
            padding: 12px 18px;
            border-radius: 999px;
            box-shadow: 0 16px 28px rgba(33, 86, 245, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover, .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 34px rgba(33, 86, 245, 0.26);
        }

        .btn.secondary, button.secondary {
            background: rgba(255, 255, 255, 0.84);
            color: var(--violet-900);
            border: 1px solid var(--line);
            box-shadow: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid var(--line);
        }

        .pill {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            background: rgba(93, 62, 240, 0.1);
            color: var(--violet-900);
            font-weight: 700;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .muted {
            color: rgba(5, 8, 22, 0.64);
        }

        body.theme-dark .muted {
            color: rgba(238, 242, 255, 0.68);
        }

        h1, h2, h3, h4 {
            color: var(--ink);
            letter-spacing: -0.03em;
            line-height: 1.12;
            margin-top: 0;
        }

        body.theme-dark h1,
        body.theme-dark h2,
        body.theme-dark h3,
        body.theme-dark h4 {
            color: #f7f9ff;
        }

        body.theme-dark .student-sidebar {
            background:
                linear-gradient(180deg, rgba(7, 16, 37, 0.92), rgba(9, 18, 42, 0.86)),
                linear-gradient(180deg, rgba(33, 86, 245, 0.12), rgba(93, 62, 240, 0.1));
            border-right-color: rgba(139, 119, 255, 0.14);
        }

        body.theme-dark .student-sidebar-copy span,
        body.theme-dark .student-sidebar-user span {
            color: rgba(238, 242, 255, 0.68);
        }

        body.theme-dark .student-sidebar-link {
            color: rgba(238, 242, 255, 0.76);
        }

        body.theme-dark .student-sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 119, 255, 0.16);
        }

        body.theme-dark .student-sidebar-user {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(139, 119, 255, 0.14);
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

            .student-shell {
                grid-template-columns: 1fr;
            }

            .student-sidebar {
                position: static;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--line);
            }

            .student-main {
                padding: 24px 20px 48px;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body_class') {{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }} {{ auth()->check() && auth()->user()->palette ? 'palette-' . auth()->user()->palette : '' }}">
@php
    $currentUser = auth()->user();
    $isStudentShell = $currentUser
        && $currentUser->role === \App\Models\User::ROLE_STUDENT
        && request()->routeIs('dashboard', 'tasks.*', 'calendar.*', 'categories.*', 'notifications.*', 'settings.*');
    $isMainMarketingPage = request()->routeIs('home', 'about', 'faq', 'contact');
@endphp

@if ($isStudentShell)
    <div class="student-shell">
        <aside class="student-sidebar">
            <a href="{{ route('dashboard') }}" class="student-sidebar-brand">
                <span class="brand-bubble">
                    <img src="{{ asset('images/logo.png') }}" alt="EtuAide logo">
                </span>
                <span class="student-sidebar-copy">
                    <strong>EtuAide Planner</strong>
                    <span>Student workspace</span>
                </span>
            </a>

            <nav class="student-sidebar-nav" aria-label="Student navigation">
                <a href="{{ route('dashboard') }}" class="student-sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('tasks.index') }}" class="student-sidebar-link {{ request()->routeIs('tasks.*') ? 'is-active' : '' }}">
                    <span>Tasks</span>
                </a>
                <a href="{{ route('calendar.index') }}" class="student-sidebar-link {{ request()->routeIs('calendar.*') ? 'is-active' : '' }}">
                    <span>Calendar</span>
                </a>
                <a href="{{ route('categories.index') }}" class="student-sidebar-link {{ request()->routeIs('categories.*') ? 'is-active' : '' }}">
                    <span>Categories</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="student-sidebar-link {{ request()->routeIs('notifications.*') ? 'is-active' : '' }}">
                    <span>Notifications</span>
                </a>
                <a href="{{ route('settings.edit') }}" class="student-sidebar-link {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">
                    <span>Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="student-sidebar-logout">
                    @csrf
                    <button type="submit" class="secondary">Log out</button>
                </form>
            </nav>

            <div class="student-sidebar-footer">
                <div class="student-sidebar-user">
                    <strong>{{ $currentUser->name }}</strong>
                    <span>{{ ucfirst($currentUser->role) }}</span>
                </div>                
            </div>
        </aside>

        <main class="student-main">
            <div class="container">
                @if (session('status'))
                    <div class="status">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
@else
    <div class="public-shell">
        <header>
            <div class="nav">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-bubble">
                        <img src="{{ asset('images/logo.png') }}" alt="EtuAide logo">
                    </span>
                    <span>EtuAide Planner</span>
                </a>
                <div class="nav-links">
                    <a href="{{ route('home') }}#home">Home</a>
                    <a href="{{ route('home') }}#about">About</a>
                    <a href="{{ route('home') }}#faq">FAQ</a>
                    <a href="{{ route('home') }}#contact">Contact</a>
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
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

        <main class="public-main">
            <div class="container">
                @if (session('status'))
                    <div class="status">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>
        </main>

        @if ($isMainMarketingPage)
            <footer class="main-pages-footer">
                <div class="container main-footer-container">
                    <div class="footer-card p-4 p-lg-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="project-logo-wrap"> 
                                        <img src="{{ asset('images/logo.png') }}" alt="EtuAide logo" class="project-logo">
                                    </div>

                                    <div>
                                        <div class="fw-bold text-dark mb-1">EtuAide</div>
                                        <p class="mb-0 text-secondary small">
                                            Academic planning for students who want more structure, clarity, and consistency.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="text-lg-center">
                                    <div class="fw-semibold text-dark mb-2">Follow us</div>
                                    <div class="d-flex justify-content-lg-center flex-wrap gap-2 social-links">
                                        <a href="#" class="social-link" aria-label="Facebook">Facebook</a>
                                        <a href="#" class="social-link" aria-label="Instagram">Instagram</a>
                                        <a href="#" class="social-link" aria-label="LinkedIn">LinkedIn</a>
                                        <a href="#" class="social-link" aria-label="X">X</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="text-lg-end">
                                    <div class="d-flex justify-content-lg-end flex-wrap gap-3">
                                        <a class="footer-link" href="{{ route('home') }}#about">About</a>
                                        <a class="footer-link" href="{{ route('home') }}#faq">FAQ</a>
                                        <a class="footer-link" href="{{ route('home') }}#contact">Contact</a>
                                        @guest
                                            <a class="footer-link" href="{{ route('login') }}">Sign in</a>
                                            <a class="footer-link" href="{{ route('register') }}">Register</a>
                                        @endguest
                                    </div>
                                    <p class="mb-0 text-secondary small mt-3">
                                        &copy; {{ date('Y') }} EtuAide. All rights reserved.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        @endif
    </div>
@endif
</body>
</html>
