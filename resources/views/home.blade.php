@extends('layouts.app')

@section('body_class', 'home-bootstrap jira-inspired')

@push('styles')
    <style>
        body.home-bootstrap.jira-inspired {
            --jira-surface: #ffffff;
            --jira-surface-muted: #fafbfc;
            --jira-border: #dfe1e6;
            --jira-border-strong: #c1c7d0;
            --jira-text: #172b4d;
            --jira-text-muted: #5e6c84;
            --jira-blue: #0c66e4;
            --jira-blue-hover: #0055cc;
            --jira-blue-soft: #e9f2ff;
            --jira-shadow: 0 1px 2px rgba(9, 30, 66, 0.08), 0 0 0 1px rgba(9, 30, 66, 0.04);
            --jira-radius: 16px;
            scroll-behavior: smooth;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #f7f8f9 0%, #f4f5f7 100%);
            color: var(--jira-text);
        }

        .home-shell,
        .home-content,
        .home-bootstrap.jira-inspired main,
        .home-bootstrap.jira-inspired .container {
            width: 100%;
        }

        .home-bootstrap.jira-inspired .public-main > .container {
            max-width: 1440px;
            padding-left: 24px;
            padding-right: 24px;
        }

        .home-shell,
        .home-content {
            flex: 1 0 auto;
        }

        .home-bootstrap.jira-inspired section[id] {
            scroll-margin-top: 110px;
        }

        .home-bootstrap.jira-inspired .hero-card,
        .home-bootstrap.jira-inspired .metric-card,
        .home-bootstrap.jira-inspired .feature-card,
        .home-bootstrap.jira-inspired .cta-card,
        .home-bootstrap.jira-inspired .insight-card,
        .home-bootstrap.jira-inspired .faq-item,
        .home-bootstrap.jira-inspired .contact-panel,
        .home-bootstrap.jira-inspired .event-row {
            border: 1px solid var(--jira-border);
            border-radius: var(--jira-radius);
            box-shadow: var(--jira-shadow);
        }

        .home-bootstrap.jira-inspired .hero-card,
        .home-bootstrap.jira-inspired .feature-card,
        .home-bootstrap.jira-inspired .faq-item,
        .home-bootstrap.jira-inspired .contact-panel,
        .home-bootstrap.jira-inspired .event-row {
            background: var(--jira-surface);
        }

        .home-bootstrap.jira-inspired .hero-card {
            background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
        }

        .home-bootstrap.jira-inspired .insight-card,
        .home-bootstrap.jira-inspired .contact-panel.soft {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .home-bootstrap.jira-inspired .feature-card.primary {
            background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
            border-color: #c7dbff;
        }

        .home-bootstrap.jira-inspired .metric-card {
            background: var(--jira-surface-muted);
        }

        .home-bootstrap.jira-inspired .cta-card {
            background: linear-gradient(135deg, #0c66e4 0%, #1d7afc 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 12px 28px rgba(12, 102, 228, 0.22);
        }

        .home-bootstrap.jira-inspired .eyebrow,
        .home-bootstrap.jira-inspired .section-tag,
        .home-bootstrap.jira-inspired .metric-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--jira-blue);
        }

        .home-bootstrap.jira-inspired .eyebrow {
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: var(--jira-blue-soft);
            margin-bottom: 1.5rem;
        }

        .home-bootstrap.jira-inspired .display-title {
            font-size: clamp(2.4rem, 5vw, 4.35rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
            color: var(--jira-text);
            max-width: 11ch;
        }

        .home-bootstrap.jira-inspired .text-secondary,
        .home-bootstrap.jira-inspired .form-note {
            color: var(--jira-text-muted) !important;
        }

        .home-bootstrap.jira-inspired .btn {
            border-radius: 10px;
            font-weight: 600;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .home-bootstrap.jira-inspired .btn-primary {
            background: var(--jira-blue);
            border-color: var(--jira-blue);
            box-shadow: none;
        }

        .home-bootstrap.jira-inspired .btn-primary:hover,
        .home-bootstrap.jira-inspired .btn-primary:focus {
            background: var(--jira-blue-hover);
            border-color: var(--jira-blue-hover);
        }

        .home-bootstrap.jira-inspired .btn-outline-dark {
            border-color: var(--jira-border-strong);
            color: var(--jira-text);
            background: #fff;
        }

        .home-bootstrap.jira-inspired .btn-outline-dark:hover {
            background: #f4f5f7;
            border-color: #a5adba;
            color: var(--jira-text);
        }

        .home-bootstrap.jira-inspired .btn-light {
            background: #ffffff;
            border-color: #ffffff;
            color: var(--jira-blue);
            font-weight: 700;
        }

        .home-bootstrap.jira-inspired .btn-light:hover {
            background: #f7f8f9;
            color: var(--jira-blue-hover);
        }

        .home-bootstrap.jira-inspired .btn-outline-light {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.55);
        }

        .home-bootstrap.jira-inspired .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.75);
            color: #ffffff;
        }

        .home-bootstrap.jira-inspired .stat-pill {
            background: var(--jira-blue-soft);
            border: 1px solid #c7dbff;
            color: var(--jira-blue);
            font-weight: 700;
        }

        .home-bootstrap.jira-inspired .event-row,
        .home-bootstrap.jira-inspired .faq-item {
            padding: 1.25rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .home-bootstrap.jira-inspired .event-row:hover,
        .home-bootstrap.jira-inspired .faq-item:hover {
            border-color: #b3d4ff;
            box-shadow: 0 4px 14px rgba(9, 30, 66, 0.08);
            transform: translateY(-1px);
        }

        .home-bootstrap.jira-inspired .event-kpi {
            min-width: 72px;
            text-align: right;
            color: var(--jira-blue);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .home-bootstrap.jira-inspired .feature-list li + li {
            margin-top: 1rem;
        }

        .home-bootstrap.jira-inspired .feature-list li {
            padding: 1rem 1rem 1rem 1.1rem;
            border: 1px solid var(--jira-border);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.72);
        }

        .home-bootstrap.jira-inspired .mini-note {
            border-left: 4px solid var(--jira-blue);
            padding-left: 1rem;
        }

        .home-bootstrap.jira-inspired label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.45rem;
            color: var(--jira-text);
        }

        .home-bootstrap.jira-inspired .form-grid > div {
            min-width: 0;
        }

        .home-bootstrap.jira-inspired .contact-panel input,
        .home-bootstrap.jira-inspired .contact-panel textarea {
            display: block;
            width: 100%;
            max-width: 100%;
        }

        @media (max-width: 991.98px) {
            .home-bootstrap.jira-inspired .display-title {
                max-width: none;
            }
        }

        @media (max-width: 575.98px) {
            .home-bootstrap.jira-inspired .event-kpi {
                min-width: auto;
                text-align: left;
            }
        }
    </style>
@endpush

@section('content')
    <section class="home-shell">
        <div class="home-content">
            <div class="container-xl px-4 px-lg-5 py-5">
                <section id="home" class="mb-4">
                    <div class="row g-4 align-items-stretch">
                        <div class="col-lg-7">
                            <div class="hero-card h-100 p-4 p-lg-5">
                                <span class="eyebrow">Academic planning, sharpened</span>
                                <h1 class="display-title fw-bold mb-4">Plan every semester with clarity and control.</h1>
                                <p class="lead text-secondary mb-4">
                                    EtuAide brings tasks, calendars, reminders, and priorities into one focused workspace so students can plan with structure, stay aligned on deadlines, and keep momentum week after week.
                                </p>

                                <div class="d-flex flex-wrap gap-3 mb-4">
                                    @auth
                                        <a class="btn btn-primary btn-lg px-4" href="{{ route('dashboard') }}">Open dashboard</a>
                                        <a class="btn btn-outline-dark btn-lg px-4" href="{{ route('tasks.index') }}">Review tasks</a>
                                    @else
                                        <a class="btn btn-primary btn-lg px-4" href="{{ route('register') }}">Create your account</a>
                                        <a class="btn btn-outline-dark btn-lg px-4" href="{{ route('login') }}">Sign in</a>
                                    @endauth
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="metric-card h-100 p-3 p-lg-4">
                                            <div class="metric-label mb-2">Daily planning</div>
                                            <div class="h4 fw-bold mb-2">Stay focused</div>
                                            <p class="mb-0 text-secondary">Keep the next tasks visible and reduce context switching.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="metric-card h-100 p-3 p-lg-4">
                                            <div class="metric-label mb-2">Weekly reviews</div>
                                            <div class="h4 fw-bold mb-2">See deadlines</div>
                                            <p class="mb-0 text-secondary">Track upcoming work with a clearer calendar view.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="metric-card h-100 p-3 p-lg-4">
                                            <div class="metric-label mb-2">Semester view</div>
                                            <div class="h4 fw-bold mb-2">Plan ahead</div>
                                            <p class="mb-0 text-secondary">Map exams, projects, and recurring commitments early.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="insight-card h-100 p-4 p-lg-5">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-4 flex-wrap">
                                    <div>
                                        <div class="section-tag mb-2">Focused overview</div>
                                        <h2 class="h1 mb-0">Today at a glance</h2>
                                    </div>
                                    <span class="badge rounded-pill stat-pill px-3 py-2">Live workflow</span>
                                </div>

                                <div class="d-grid gap-3">
                                    <div class="event-row">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-column flex-sm-row">
                                            <div>
                                                <h3 class="h5 mb-1">Priority queue</h3>
                                                <p class="mb-0 text-secondary">High-value tasks surfaced first for immediate attention.</p>
                                            </div>
                                            <div class="event-kpi">03 items</div>
                                        </div>
                                    </div>
                                    <div class="event-row">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-column flex-sm-row">
                                            <div>
                                                <h3 class="h5 mb-1">Calendar sync</h3>
                                                <p class="mb-0 text-secondary">Daily, weekly, and monthly planning context in one place.</p>
                                            </div>
                                            <div class="event-kpi">24h view</div>
                                        </div>
                                    </div>
                                    <div class="event-row">
                                        <div class="d-flex justify-content-between align-items-start gap-3 flex-column flex-sm-row">
                                            <div>
                                                <h3 class="h5 mb-1">Reminder coverage</h3>
                                                <p class="mb-0 text-secondary">Email and in-app nudges aligned with due dates.</p>
                                            </div>
                                            <div class="event-kpi">100%</div>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-secondary mt-4 mb-0 mini-note">
                                    Replace scattered notes, missed deadlines, and last-minute planning with a calmer academic workflow.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row g-4 mb-4">
                    <div class="col-lg-7">
                        <div class="feature-card primary h-100 p-4 p-lg-5">
                            <div class="section-tag mb-3">Why EtuAide</div>
                            <h2 class="display-6 fw-bold mb-3">Professional structure without the overhead.</h2>
                            <p class="text-secondary mb-4">
                                Inspired by modern product workspaces, this landing page uses flatter surfaces, stronger hierarchy, and clearer segmentation to make the experience feel focused and operational.
                            </p>

                            <ul class="feature-list list-unstyled mb-0">
                                <li>
                                    <strong class="d-block mb-1">Task execution</strong>
                                    <span class="text-secondary">Break large objectives into manageable subtasks and track progress with clear completion states.</span>
                                </li>
                                <li>
                                    <strong class="d-block mb-1">Calendar command</strong>
                                    <span class="text-secondary">Move from daily planning to weekly reviews and monthly forecasting without changing mental models.</span>
                                </li>
                                <li>
                                    <strong class="d-block mb-1">Reliable follow-through</strong>
                                    <span class="text-secondary">Use reminders and notifications to maintain momentum across classes, deliverables, and exams.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="feature-card h-100 p-4 p-lg-5">
                            <div class="section-tag mb-3">Built for momentum</div>
                            <h2 class="h2 fw-bold mb-3">A calmer planning experience.</h2>
                            <p class="text-secondary mb-0">
                                Cleaner cards, clearer calls to action, quieter colors, and more structured spacing give the interface a product-first feel.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <div class="section-tag mb-3">Visibility</div>
                            <h3 class="h3 fw-bold mb-3">See the whole workload.</h3>
                            <p class="mb-0 text-secondary">Review tasks, deadlines, and categories in one place before they become urgent.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <div class="section-tag mb-3">Discipline</div>
                            <h3 class="h3 fw-bold mb-3">Keep priorities obvious.</h3>
                            <p class="mb-0 text-secondary">Highlight what needs action now and reduce the friction of deciding where to start.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 p-4">
                            <div class="section-tag mb-3">Consistency</div>
                            <h3 class="h3 fw-bold mb-3">Stay on track each week.</h3>
                            <p class="mb-0 text-secondary">Use reminders, recurring work, and calendar context to maintain reliable study habits.</p>
                        </div>
                    </div>
                </div>

                <div class="cta-card p-4 p-lg-5 mb-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-8">
                            <h2 class="display-6 fw-bold mb-3">Move from intention to execution.</h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.86);">
                                Whether you are planning the week or mapping an entire semester, EtuAide gives you a cleaner, more dependable workspace to operate from.
                            </p>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex flex-column flex-sm-row flex-lg-column gap-3 align-items-stretch">
                                <a class="btn btn-light btn-lg" href="{{ auth()->check() ? route('dashboard') : route('register') }}">
                                    {{ auth()->check() ? 'Go to dashboard' : 'Start free' }}
                                </a>
                                <a class="btn btn-outline-light btn-lg" href="#about">Explore more</a>
                            </div>
                        </div>
                    </div>
                </div>

                <section id="about" class="mb-4">
                    <div class="feature-card primary p-4 p-lg-5">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-6">
                                <div class="section-tag mb-3">About EtuAide</div>
                                <h2 class="display-6 fw-bold mb-3">One planning space for academic clarity.</h2>
                                <p class="text-secondary mb-0">
                                    EtuAide is a student-first planner built to reduce noise. We bring tasks, calendar planning, reminders, and accountability into one focused workspace so students can act with more confidence and less friction.
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="metric-card h-100 p-4">
                                            <div class="metric-label mb-2">Our mission</div>
                                            <p class="mb-0 text-secondary">Help students focus on what matters today while building steady momentum for long-term goals.</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="metric-card h-100 p-4">
                                            <div class="metric-label mb-2">How we work</div>
                                            <p class="mb-0 text-secondary">Clear task states, structured calendars, and reminders that support follow-through without overwhelming the user.</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="metric-card h-100 p-4">
                                            <div class="metric-label mb-2">Who it is for</div>
                                            <p class="mb-0 text-secondary">Students who want a cleaner system for deadlines, study routines, projects, and semester planning.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="faq" class="mb-4">
                    <div class="feature-card p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-4 flex-column flex-lg-row">
                            <div>
                                <div class="section-tag mb-2">FAQ</div>
                                <h2 class="display-6 fw-bold mb-2">Common questions, answered clearly.</h2>
                                <p class="text-secondary mb-0">Everything students and admins usually ask before getting started.</p>
                            </div>
                            <span class="badge rounded-pill stat-pill px-3 py-2">4 quick answers</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="faq-item h-100">
                                    <h3 class="h5 fw-bold">Can I change task status?</h3>
                                    <p class="mb-0 text-secondary">Yes. Tasks and subtasks can move through pending, in progress, review, and done states.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="faq-item h-100">
                                    <h3 class="h5 fw-bold">How do reminders work?</h3>
                                    <p class="mb-0 text-secondary">Set reminders on tasks to receive in-app notifications and optional email nudges before deadlines.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="faq-item h-100">
                                    <h3 class="h5 fw-bold">What counts as inactive?</h3>
                                    <p class="mb-0 text-secondary">An account with no login or task activity for 14 days is treated as inactive for admin follow-up.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="faq-item h-100">
                                    <h3 class="h5 fw-bold">Can admins reset passwords?</h3>
                                    <p class="mb-0 text-secondary">Yes. The admin side is designed to support account management and recovery workflows.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="contact">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="contact-panel soft h-100 p-4 p-lg-5">
                                <div class="section-tag mb-3">Contact</div>
                                <h2 class="display-6 fw-bold mb-3">Reach the EtuAide team.</h2>
                                <p class="text-secondary mb-4">
                                    We love hearing from students, teachers, and parents. Send a message for support, onboarding, or partnership questions.
                                </p>

                                <div class="d-grid gap-3">
                                    <div class="event-row">
                                        <h3 class="h6 fw-bold mb-1">Email</h3>
                                        <p class="mb-0 text-secondary">support@etuaide.com</p>
                                    </div>
                                    <div class="event-row">
                                        <h3 class="h6 fw-bold mb-1">Campus hours</h3>
                                        <p class="mb-0 text-secondary">Mon to Fri - 9:00 to 18:00</p>
                                    </div>
                                    <div class="event-row">
                                        <h3 class="h6 fw-bold mb-1">Community</h3>
                                        <p class="mb-0 text-secondary">Ask us about partnerships, onboarding, or student support workflows.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="contact-panel h-100 p-4 p-lg-5">
                                <div class="section-tag mb-3">Send a message</div>
                                <h2 class="h2 fw-bold mb-3">We'll get back to you soon.</h2>
                                <p class="form-note mb-4">Use the form below and the team will follow up as quickly as possible.</p>

                                <form method="POST" action="{{ route('contact.submit') }}" class="form-grid">
                                    @csrf
                                    <div>
                                        <label for="contact-name">Name</label>
                                        <input id="contact-name" type="text" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="muted mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="contact-email">Email</label>
                                        <input id="contact-email" type="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="muted mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="contact-message">Message</label>
                                        <textarea id="contact-message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="muted mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
