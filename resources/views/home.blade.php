@extends('layouts.app')

@section('body_class', 'home-landing')

@push('styles')
    <style>
        body.home-landing {
            --landing-ink: #050816;
            --landing-slate: #101935;
            --landing-blue: #2156f5;
            --landing-blue-soft: #d8e4ff;
            --landing-violet: #6e4cff;
            --landing-violet-soft: #ebe6ff;
            --landing-line: rgba(16, 25, 53, 0.12);
            --landing-shadow: rgba(5, 8, 22, 0.14);
            font-family: "Segoe UI Variable", "Aptos", "Trebuchet MS", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(33, 86, 245, 0.14), transparent 28%),
                radial-gradient(circle at 85% 10%, rgba(110, 76, 255, 0.14), transparent 24%),
                linear-gradient(180deg, #f7f9ff 0%, #eef2ff 46%, #ffffff 100%);
            color: var(--landing-ink);
        }

        body.home-landing::before {
            background: radial-gradient(circle, rgba(33, 86, 245, 0.18) 0%, transparent 68%);
            top: -150px;
            left: -90px;
        }

        body.home-landing::after {
            background: radial-gradient(circle, rgba(110, 76, 255, 0.16) 0%, transparent 70%);
            bottom: -180px;
            right: -110px;
        }

        .home-landing header {
            background: rgba(255, 255, 255, 0.82);
            border-bottom: 1px solid rgba(16, 25, 53, 0.08);
        }

        .home-landing .nav {
            max-width: 1180px;
            padding-top: 18px;
            padding-bottom: 18px;
        }

        .home-landing .brand {
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .home-landing .brand-bubble {
            background: linear-gradient(135deg, #0b132d 0%, #2156f5 62%, #6e4cff 100%);
            box-shadow: 0 16px 32px rgba(33, 86, 245, 0.2);
        }

        .home-landing .nav-links,
        .home-landing .actions {
            align-items: center;
        }

        .home-landing .nav-links a,
        .home-landing .muted {
            color: rgba(5, 8, 22, 0.74);
        }

        .home-landing .nav-links a:hover {
            background: rgba(33, 86, 245, 0.08);
            color: var(--landing-ink);
        }

        .home-landing .container {
            max-width: 1180px;
            padding-top: 40px;
            padding-bottom: 96px;
        }

        .home-landing .btn,
        .home-landing button {
            background: linear-gradient(135deg, #0b132d 0%, #2156f5 58%, #6e4cff 100%);
            box-shadow: 0 18px 32px rgba(33, 86, 245, 0.22);
        }

        .home-landing .btn.secondary,
        .home-landing button.secondary {
            background: rgba(255, 255, 255, 0.84);
            color: var(--landing-slate);
            border: 1px solid rgba(16, 25, 53, 0.12);
        }

        .landing-shell {
            display: grid;
            gap: 28px;
        }

        .landing-hero {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 28px;
            align-items: stretch;
        }

        .landing-panel,
        .landing-card,
        .landing-metric,
        .landing-feature {
            border: 1px solid var(--landing-line);
            border-radius: 28px;
            box-shadow: 0 22px 60px var(--landing-shadow);
        }

        .landing-panel {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(236, 242, 255, 0.94)),
                linear-gradient(135deg, rgba(33, 86, 245, 0.08), rgba(110, 76, 255, 0.08));
            padding: 44px;
        }

        .landing-panel::after {
            content: "";
            position: absolute;
            inset: auto -40px -60px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(110, 76, 255, 0.14) 0%, transparent 72%);
        }

        .landing-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(16, 25, 53, 0.06);
            color: var(--landing-slate);
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .landing-panel h1 {
            margin: 22px 0 16px;
            font-size: clamp(2.8rem, 5vw, 4.8rem);
            line-height: 0.95;
            letter-spacing: -0.05em;
            max-width: 10ch;
        }

        .landing-panel p {
            max-width: 62ch;
            font-size: 1.08rem;
            line-height: 1.75;
            color: rgba(5, 8, 22, 0.72);
        }

        .landing-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin: 28px 0 34px;
        }

        .landing-proof {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .landing-metric {
            background: rgba(255, 255, 255, 0.88);
            padding: 18px 20px;
        }

        .landing-metric strong {
            display: block;
            font-size: 1.7rem;
            letter-spacing: -0.04em;
            margin-bottom: 6px;
            color: var(--landing-ink);
        }

        .landing-metric span {
            color: rgba(5, 8, 22, 0.64);
            font-size: 0.95rem;
        }

        .landing-card {
            background: linear-gradient(180deg, #09122a 0%, #0e1834 54%, #142451 100%);
            color: #ffffff;
            padding: 28px;
            display: grid;
            gap: 18px;
            position: relative;
            overflow: hidden;
        }

        .landing-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.08), transparent 38%),
                radial-gradient(circle at 100% 0%, rgba(110, 76, 255, 0.42), transparent 30%),
                radial-gradient(circle at 0% 100%, rgba(33, 86, 245, 0.34), transparent 32%);
            pointer-events: none;
        }

        .landing-card > * {
            position: relative;
            z-index: 1;
        }

        .landing-card-top {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
        }

        .landing-card-label {
            font-size: 0.82rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.72);
        }

        .landing-card h2 {
            margin: 8px 0 0;
            font-size: 1.9rem;
            letter-spacing: -0.04em;
        }

        .landing-chip {
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.92);
        }

        .landing-stat-grid {
            display: grid;
            gap: 12px;
        }

        .landing-stat {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            padding: 16px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .landing-stat strong {
            display: block;
            margin-bottom: 4px;
            font-size: 1rem;
        }

        .landing-stat span,
        .landing-stat small,
        .landing-card-footer {
            color: rgba(255, 255, 255, 0.74);
        }

        .landing-stat-value {
            font-weight: 800;
            font-size: 1.2rem;
            white-space: nowrap;
        }

        .landing-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 20px;
        }

        .landing-feature {
            background: rgba(255, 255, 255, 0.9);
            padding: 28px;
        }

        .landing-feature.primary {
            grid-column: span 7;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(235, 230, 255, 0.92));
        }

        .landing-feature.secondary {
            grid-column: span 5;
        }

        .landing-kicker {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--landing-blue);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .landing-feature h3 {
            margin: 0 0 12px;
            font-size: 1.7rem;
            letter-spacing: -0.03em;
        }

        .landing-feature p {
            margin: 0;
            color: rgba(5, 8, 22, 0.72);
            line-height: 1.72;
        }

        .landing-list {
            margin: 22px 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 14px;
        }

        .landing-list li {
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(16, 25, 53, 0.05);
            color: var(--landing-slate);
        }

        .landing-list strong {
            display: block;
            margin-bottom: 4px;
        }

        .landing-columns {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .landing-cta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 32px;
            border-radius: 28px;
            background: linear-gradient(135deg, #071025 0%, #16328a 54%, #5d3ef0 100%);
            color: #ffffff;
            box-shadow: 0 26px 60px rgba(16, 25, 53, 0.25);
        }

        .landing-cta h3 {
            margin: 0 0 10px;
            font-size: 2rem;
            letter-spacing: -0.04em;
        }

        .landing-cta p {
            margin: 0;
            max-width: 60ch;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
        }

        .landing-cta .btn.secondary {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        @media (max-width: 980px) {
            .landing-hero,
            .landing-columns {
                grid-template-columns: 1fr;
            }

            .landing-grid {
                grid-template-columns: 1fr;
            }

            .landing-feature.primary,
            .landing-feature.secondary {
                grid-column: auto;
            }

            .landing-cta {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 720px) {
            .landing-panel,
            .landing-card,
            .landing-feature,
            .landing-cta {
                padding: 24px;
            }

            .landing-proof {
                grid-template-columns: 1fr;
            }

            .landing-panel h1 {
                max-width: none;
                font-size: clamp(2.4rem, 12vw, 3.6rem);
            }
        }
    </style>
@endpush

@section('content')
    <section class="landing-shell">
        <div class="landing-hero">
            <div class="landing-panel">
                <span class="landing-eyebrow">Academic planning, sharpened</span>
                <h1>Plan every semester with clarity and control.</h1>
                <p>
                    EtuAide brings tasks, calendars, reminders, and priorities into one focused workspace so students can
                    operate with the same discipline as a professional team.
                </p>

                <div class="landing-actions">
                    @auth
                        <a class="btn" href="{{ route('dashboard') }}">Open dashboard</a>
                        <a class="btn secondary" href="{{ route('tasks.index') }}">Review tasks</a>
                    @else
                        <a class="btn" href="{{ route('register') }}">Create your account</a>
                        <a class="btn secondary" href="{{ route('login') }}">Sign in</a>
                    @endauth
                </div>

                <div class="landing-proof">
                    <div class="landing-metric">
                        <strong>Daily</strong>
                        <span>Execution rhythms built around the work that matters now.</span>
                    </div>
                    <div class="landing-metric">
                        <strong>Weekly</strong>
                        <span>Calendar views that keep deadlines visible without adding noise.</span>
                    </div>
                    <div class="landing-metric">
                        <strong>Long-term</strong>
                        <span>Structured planning for exams, projects, and recurring commitments.</span>
                    </div>
                </div>
            </div>

            <aside class="landing-card">
                <div class="landing-card-top">
                    <div>
                        <div class="landing-card-label">Focused overview</div>
                        <h2>Today at a glance</h2>
                    </div>
                    <span class="landing-chip">Live workflow</span>
                </div>

                <div class="landing-stat-grid">
                    <div class="landing-stat">
                        <div>
                            <strong>Priority queue</strong>
                            <span>High-value tasks surfaced first</span>
                        </div>
                        <div class="landing-stat-value">03</div>
                    </div>
                    <div class="landing-stat">
                        <div>
                            <strong>Calendar sync</strong>
                            <small>Daily, weekly, and monthly context</small>
                        </div>
                        <div class="landing-stat-value">24h</div>
                    </div>
                    <div class="landing-stat">
                        <div>
                            <strong>Reminder coverage</strong>
                            <small>Email and in-app nudges, aligned to due dates</small>
                        </div>
                        <div class="landing-stat-value">100%</div>
                    </div>
                </div>

                <div class="landing-card-footer">
                    Designed to replace scattered notes, missed deadlines, and last-minute planning with a calmer operating system.
                </div>
            </aside>
        </div>

        <div class="landing-grid">
            <article class="landing-feature primary">
                <span class="landing-kicker">Why EtuAide</span>
                <h3>Professional structure without the overhead.</h3>
                <p>
                    The home page now frames EtuAide as a serious planning product: cleaner hierarchy, tighter copy, and
                    a visual system built entirely from black, white, blue, and purple tones.
                </p>

                <ul class="landing-list">
                    <li>
                        <strong>Task execution</strong>
                        Break large objectives into manageable subtasks, then track completion with clear progress states.
                    </li>
                    <li>
                        <strong>Calendar command</strong>
                        Move from day planning to weekly reviews and monthly forecasting without changing mental models.
                    </li>
                    <li>
                        <strong>Reliable follow-through</strong>
                        Use reminders and notifications to keep momentum steady across classes, deliverables, and exams.
                    </li>
                </ul>
            </article>

            <article class="landing-feature secondary">
                <span class="landing-kicker">Built for momentum</span>
                <h3>A calmer planning experience.</h3>
                <p>
                    High contrast, controlled spacing, and restrained gradients keep the page polished while still feeling modern.
                </p>
            </article>
        </div>

        <div class="landing-columns">
            <article class="landing-feature">
                <span class="landing-kicker">Visibility</span>
                <h3>See the whole workload.</h3>
                <p>Review tasks, deadlines, and categories in one place before they become urgent.</p>
            </article>

            <article class="landing-feature">
                <span class="landing-kicker">Discipline</span>
                <h3>Keep priorities obvious.</h3>
                <p>Highlight what needs action now and reduce the friction of deciding where to start.</p>
            </article>

            <article class="landing-feature">
                <span class="landing-kicker">Consistency</span>
                <h3>Stay on track each week.</h3>
                <p>Use reminders, recurring work, and calendar context to maintain reliable study habits.</p>
            </article>
        </div>

        <section class="landing-cta">
            <div>
                <h3>Move from intention to execution.</h3>
                <p>
                    Whether you are planning the week or mapping an entire semester, EtuAide gives you a cleaner surface to work from.
                </p>
            </div>

            <div class="landing-actions">
                <a class="btn" href="{{ auth()->check() ? route('dashboard') : route('register') }}">
                    {{ auth()->check() ? 'Go to dashboard' : 'Start free' }}
                </a>
                <a class="btn secondary" href="{{ route('about') }}">Explore more</a>
            </div>
        </section>
    </section>
@endsection
