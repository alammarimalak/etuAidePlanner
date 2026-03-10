<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EtuAide Planner</title>
    <style>
        :root {
            color-scheme: light;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: #f6f7fb;
            color: #1b1d29;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        header {
            background: #ffffff;
            border-bottom: 1px solid #e2e5ef;
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

        .nav-links {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e2e5ef;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            margin-bottom: 16px;
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .status {
            padding: 8px 12px;
            border-radius: 999px;
            background: #f0f4ff;
            color: #2c4b9c;
            display: inline-block;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .form-grid {
            display: grid;
            gap: 12px;
        }

        input, select, textarea, button {
            font: inherit;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #ccd2e3;
        }

        button {
            background: #1f7ae0;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        button.secondary {
            background: #e2e5ef;
            color: #1b1d29;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e2e5ef;
        }

        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.8rem;
            background: #eef2ff;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .muted {
            color: #5c637a;
        }

        .inline-form {
            display: inline;
        }
    </style>
</head>
<body>
<header>
    <div class="nav">
        <div>
            <strong>EtuAide Planner</strong>
        </div>
        <div class="nav-links">
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('tasks.index') }}">Tasks</a>
                <a href="{{ route('calendar.index') }}">Calendar</a>
                <a href="{{ route('categories.index') }}">Categories</a>
                <a href="{{ route('notifications.index') }}">Notifications</a>
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
                <a href="{{ route('login') }}">Login</a>
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
