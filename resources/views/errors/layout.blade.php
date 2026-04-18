@php
    $statusCode = $statusCode ?? ($exception->getStatusCode() ?? 500);
    $errorType = $errorType ?? ('HTTP ' . $statusCode);
    $title = $title ?? 'Something went wrong';
    $description = $description ?? 'Please try again later.';
    $lightLogoUrl = asset('images/logo.png');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $statusCode }} | EtuAide</title>
    <link rel="icon" type="image/png" href="{{ $lightLogoUrl }}">
    <style>
        :root {
            --error-ink: #0f172a;
            --error-ink-soft: #475569;
            --error-line: rgba(37, 99, 235, 0.18);
            --error-panel: rgba(255, 255, 255, 0.9);
            --error-panel-strong: #ffffff;
            --error-blue: #2563eb;
            --error-blue-strong: #1d4ed8;
            --error-blue-soft: rgba(37, 99, 235, 0.1);
            --error-shadow: 0 28px 70px rgba(15, 23, 42, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI Variable", "Aptos", "Trebuchet MS", sans-serif;
            color: var(--error-ink);
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 24%),
                radial-gradient(circle at 84% 12%, rgba(59, 130, 246, 0.16), transparent 22%),
                linear-gradient(180deg, #f8fbff 0%, #eef4ff 52%, #e5eefc 100%);
            display: flex;
            flex-direction: column;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .error-header,
        .error-footer {
            padding: 20px 24px;
        }

        .error-nav,
        .error-footer-inner {
            width: min(1120px, calc(100vw - 32px));
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .error-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .error-brand img {
            width: 58px;
            height: auto;
            display: block;
        }

        .error-brand-copy {
            display: grid;
            gap: 2px;
        }

        .error-brand-copy span:last-child,
        .error-footer-inner,
        .error-home-link {
            color: var(--error-ink-soft);
        }

        .error-home-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(37, 99, 235, 0.12);
            font-weight: 700;
        }

        .error-main {
            flex: 1 0 auto;
            display: grid;
            place-items: center;
            padding: 32px 24px 48px;
        }

        .error-card {
            width: min(1120px, 100%);
            border-radius: 32px;
            border: 1px solid var(--error-line);
            background: linear-gradient(180deg, var(--error-panel-strong) 0%, var(--error-panel) 100%);
            box-shadow: var(--error-shadow);
            overflow: hidden;
        }

        .error-card-inner {
            display: grid;
            grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
            gap: 0;
        }

        .error-code-panel {
            padding: 36px 32px;
            background: linear-gradient(180deg, #2563eb 0%, #1e40af 100%);
            color: #ffffff;
            display: grid;
            align-content: space-between;
            gap: 24px;
            min-height: 420px;
        }

        .error-code-panel small {
            display: inline-flex;
            width: fit-content;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .error-code {
            margin: 0;
            font-size: clamp(4.8rem, 10vw, 8rem);
            line-height: 0.9;
            letter-spacing: -0.08em;
        }

        .error-code-copy {
            margin: 0;
            max-width: 18rem;
            color: rgba(255, 255, 255, 0.82);
        }

        .error-content {
            padding: 40px;
            display: grid;
            gap: 28px;
            align-content: center;
        }

        .error-status-row {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .error-status-logo {
            width: 78px;
            height: 78px;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(37, 99, 235, 0.12), rgba(37, 99, 235, 0.04));
            border: 1px solid rgba(37, 99, 235, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .error-status-logo img {
            width: 54px;
            height: auto;
            display: block;
        }

        .error-status-copy {
            display: grid;
            gap: 8px;
        }

        .error-status-copy span {
            display: inline-flex;
            width: fit-content;
            padding: 7px 12px;
            border-radius: 999px;
            background: var(--error-blue-soft);
            color: var(--error-blue-strong);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .error-title {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3.25rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
        }

        .error-description {
            margin: 0;
            max-width: 42rem;
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--error-ink-soft);
        }

        .error-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .error-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 20px;
            border-radius: 999px;
            border: 1px solid transparent;
            background: var(--error-blue);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 16px 28px rgba(37, 99, 235, 0.22);
        }

        .error-button.secondary {
            background: rgba(255, 255, 255, 0.84);
            color: var(--error-blue-strong);
            border-color: rgba(37, 99, 235, 0.16);
            box-shadow: none;
        }

        .error-footer {
            color: var(--error-ink-soft);
        }

        @media (max-width: 860px) {
            .error-card-inner {
                grid-template-columns: 1fr;
            }

            .error-code-panel {
                min-height: 0;
            }

            .error-content {
                padding: 28px 22px;
            }

            .error-status-row {
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <header class="error-header">
        <div class="error-nav">
            <a href="{{ route('home') }}" class="error-brand">
                <img src="{{ $lightLogoUrl }}" alt="EtuAide logo">
                <span class="error-brand-copy">
                    <span>EtuAide</span>
                    <span>Planner platform</span>
                </span>
            </a>

            <a href="{{ route('home') }}" class="error-home-link">Back to Home</a>
        </div>
    </header>

    <main class="error-main">
        <section class="error-card" aria-labelledby="error-title">
            <div class="error-card-inner">
                <div class="error-code-panel">
                    <div>
                        <small>HTTP Response</small>
                    </div>

                    <div>
                        <h1 class="error-code">{{ $statusCode }}</h1>
                        <p class="error-code-copy">{{ $errorType }}</p>
                    </div>
                </div>

                <div class="error-content">
                    <div class="error-status-row">
                        <div class="error-status-logo">
                            <img src="{{ $lightLogoUrl }}" alt="EtuAide logo">
                        </div>

                        <div class="error-status-copy">
                            <span>{{ $errorType }}</span>
                            <h2 id="error-title" class="error-title">{{ $title }}</h2>
                        </div>
                    </div>

                    <p class="error-description">{{ $description }}</p>

                    <div class="error-actions">
                        <a href="{{ route('home') }}" class="error-button">Go Home</a>
                        <a href="javascript:history.back()" class="error-button secondary">Try Again</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="error-footer">
        <div class="error-footer-inner">
            <span>&copy; {{ date('Y') }} EtuAide</span>
            <span>If the problem continues, please try again later.</span>
        </div>
    </footer>
</body>
</html>
