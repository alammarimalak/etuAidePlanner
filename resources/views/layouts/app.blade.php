@php
    $lightLogoUrl = asset('storage/EtuAide_lightmode.png');
    $darkLogoUrl = asset('storage/EtuAide_darkmode.png');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EtuAide Planner</title>
    <link rel="icon" type="image/png" href="{{ $lightLogoUrl }}">
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
            background: #f7f9ff;
            color: var(--ink);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            line-height: 1.6;
        }

        body.theme-dark {
            color: #eef2ff;
            background: #08101f;
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

        body.theme-dark select {
            color-scheme: dark;
        }

        body.theme-dark select option,
        body.theme-dark select optgroup {
            background: #000000;
            color: #ffffff;
        }

        body.theme-dark .muted {
            color: rgba(238, 242, 255, 0.68);
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            filter: blur(0px);
            opacity: 0.4;
            z-index: 0;
            pointer-events: none;
            background: transparent;
        }

        body::before {
            top: -120px;
            left: -140px;
        }

        body::after {
            bottom: -140px;
            right: -100px;
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

        .public-nav {
            position: relative;
        }

        .public-nav-panel {
            display: flex;
            flex: 1 1 auto;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-width: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .brand-bubble {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            width: 65px;
            max-width: 100%;
        }

        .brand-logo {
            width: 100%;
            height: auto;
            display: block;
        }

        .brand-logo--dark {
            display: none;
        }

        body.theme-dark .brand-logo--light {
            display: none;
        }

        body.theme-dark .brand-logo--dark {
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            font-weight: 600;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            transition: background 0.2s ease, color 0.2s ease;
            color: rgba(5, 8, 22, 0.74);
        }

        .nav-link-icon,
        .nav-action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .nav-link-icon svg,
        .nav-action-icon svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .nav-links a:hover {
            background: rgba(33, 86, 245, 0.08);
            color: var(--ink);
        }

        body.theme-dark .nav-links a {
            color: rgba(255, 255, 255, 0.9);
        }

        body.theme-dark .nav-links a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .nav-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border-radius: 14px;
            border: 1px solid rgba(16, 25, 53, 0.14);
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            box-shadow: none;
            flex-shrink: 0;
        }

        .nav-menu-toggle:hover {
            transform: none;
            box-shadow: none;
            background: rgba(33, 86, 245, 0.08);
        }

        .nav-menu-toggle:focus-visible {
            outline: 2px solid rgba(33, 86, 245, 0.35);
            outline-offset: 2px;
        }

        .nav-menu-toggle-box {
            display: grid;
            gap: 4px;
        }

        .nav-menu-toggle-line {
            display: block;
            width: 18px;
            height: 2px;
            border-radius: 999px;
            background: currentColor;
            transition: transform 0.2s ease, opacity 0.2s ease;
            transform-origin: center;
        }

        .public-nav.is-open .nav-menu-toggle-line:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }

        .public-nav.is-open .nav-menu-toggle-line:nth-child(2) {
            opacity: 0;
        }

        .public-nav.is-open .nav-menu-toggle-line:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border-radius: 999px;
            border: 1px solid rgba(16, 25, 53, 0.14);
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            box-shadow: 0 12px 24px rgba(33, 86, 245, 0.14);
            line-height: 1;
        }

        .theme-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(33, 86, 245, 0.18);
        }

        .theme-toggle:focus-visible {
            outline: 2px solid rgba(33, 86, 245, 0.35);
            outline-offset: 2px;
        }

        .theme-toggle-icon {
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #f59e0b;
        }

        .theme-toggle-icon svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
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
            background: #ffffff;
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            gap: 24px;
            overflow-y: auto;
            scrollbar-gutter: stable;
        }

        .student-sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .student-sidebar::-webkit-scrollbar-track {
            background: rgba(16, 25, 53, 0.08);
            border-radius: 999px;
        }

        .student-sidebar::-webkit-scrollbar-thumb {
            background: rgba(33, 86, 245, 0.28);
            border-radius: 999px;
        }

        .student-sidebar-brand-shell {
            display: grid;
            gap: 10px;
        }

        .student-sidebar-brand-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .student-sidebar-brand-mark {
            display: inline-flex;
            align-items: center;
        }

        .student-sidebar-brand-mark .brand-bubble {
            width: 61px;
        }

        .student-sidebar-brand {
            display: grid;
            gap: 6px;
            color: inherit;
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

        .student-sidebar-theme-toggle.theme-toggle {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            margin-top: 4px;
            box-shadow: none;
        }

        .student-sidebar-nav {
            display: grid;
            gap: 8px;
        }

        .student-sidebar-section-label {
            margin: 8px 0 2px;
            padding: 0 14px;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(5, 8, 22, 0.45);
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
            background: var(--violet-500);
            box-shadow: 0 18px 30px rgba(33, 86, 245, 0.2);
        }

        .student-sidebar-link small {
            color: inherit;
            opacity: 0.8;
        }

        .student-sidebar-badge {
            min-width: 28px;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(33, 86, 245, 0.12);
            color: var(--violet-900);
            font-size: 0.78rem;
            font-weight: 800;
            line-height: 1;
            text-align: center;
        }

        .student-sidebar-link.is-active .student-sidebar-badge {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
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

        .public-main.is-marketing > .container {
            padding-bottom: 0;
        }

        .main-footer-container {
            padding-top: 0;
            padding-bottom: 56px;
        }

        .main-pages-footer {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.82);
            border-top: 1px solid var(--line);
            backdrop-filter: blur(14px);
        }

        .main-pages-footer .footer-nav {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            gap: 16px;
            padding: 16px 24px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .main-pages-footer .footer-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .main-pages-footer .footer-copy {
            color: rgba(5, 8, 22, 0.64);
            font-size: 0.92rem;
            font-weight: 600;
        }

        .main-pages-footer .social-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .main-pages-footer .social-link {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #ffffff;
            color: var(--ink);
            box-shadow: 0 10px 22px rgba(33, 86, 245, 0.12);
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .main-pages-footer .social-link:hover {
            transform: translateY(-1px);
            border-color: rgba(33, 86, 245, 0.28);
            background: rgba(33, 86, 245, 0.08);
            color: var(--violet-500);
        }

        .main-pages-footer .social-link svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
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

        body.theme-dark .main-pages-footer {
            background: rgba(5, 8, 22, 0.84);
            border-top-color: rgba(139, 119, 255, 0.12);
        }

        body.theme-dark .main-pages-footer .footer-copy {
            color: rgba(238, 242, 255, 0.72);
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

        body.theme-dark .status {
            color: #ffffff;
            background: rgba(139, 119, 255, 0.16);
            border-color: rgba(139, 119, 255, 0.24);
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
            background: var(--violet-500);
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

        button.theme-toggle {
            border: 1px solid rgba(16, 25, 53, 0.14);
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            box-shadow: 0 12px 24px rgba(33, 86, 245, 0.14);
        }

        button.theme-toggle:hover {
            box-shadow: 0 16px 28px rgba(33, 86, 245, 0.18);
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
            border: 1px solid transparent;
        }

        .pill.status-done,
        .pill.priority-low {
            background: rgba(34, 197, 94, 0.16);
            border-color: rgba(34, 197, 94, 0.24);
            color: #166534;
        }

        .pill.status-pending {
            background: rgba(249, 115, 22, 0.16);
            border-color: rgba(249, 115, 22, 0.24);
            color: #9a3412;
        }

        .pill.status-in-progress,
        .pill.priority-medium {
            background: rgba(250, 204, 21, 0.18);
            border-color: rgba(250, 204, 21, 0.3);
            color: #854d0e;
        }

        .pill.status-review {
            background: rgba(148, 163, 184, 0.18);
            border-color: rgba(148, 163, 184, 0.28);
            color: #475569;
        }

        .pill.priority-high {
            background: rgba(239, 68, 68, 0.16);
            border-color: rgba(239, 68, 68, 0.24);
            color: #b91c1c;
        }

        body.theme-dark .pill {
            color: #ffffff;
            background: rgba(139, 119, 255, 0.18);
        }

        body.theme-dark .pill.status-done,
        body.theme-dark .pill.priority-low {
            background: rgba(34, 197, 94, 0.22);
            border-color: rgba(74, 222, 128, 0.24);
            color: #dcfce7;
        }

        body.theme-dark .pill.status-pending {
            background: rgba(249, 115, 22, 0.22);
            border-color: rgba(251, 146, 60, 0.24);
            color: #ffedd5;
        }

        body.theme-dark .pill.status-in-progress,
        body.theme-dark .pill.priority-medium {
            background: rgba(234, 179, 8, 0.22);
            border-color: rgba(250, 204, 21, 0.24);
            color: #fef3c7;
        }

        body.theme-dark .pill.status-review {
            background: rgba(100, 116, 139, 0.28);
            border-color: rgba(148, 163, 184, 0.26);
            color: #e2e8f0;
        }

        body.theme-dark .pill.priority-high {
            background: rgba(239, 68, 68, 0.22);
            border-color: rgba(248, 113, 113, 0.24);
            color: #fee2e2;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
        }

        .nav-user-name {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 6px;
            font-weight: 600;
        }

        .nav-action-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            background: rgba(9, 18, 42, 0.96);
            border-right-color: rgba(139, 119, 255, 0.14);
            scrollbar-color: #18357a rgba(8, 16, 31, 0.92);
        }

        body.theme-dark .student-sidebar::-webkit-scrollbar-track {
            background: rgba(8, 16, 31, 0.92);
        }

        body.theme-dark .student-sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #1f4fb8 0%, #102a63 100%);
        }

        body.theme-dark .student-sidebar-copy span,
        body.theme-dark .student-sidebar-user span {
            color: rgba(238, 242, 255, 0.68);
        }

        body.theme-dark .student-sidebar-section-label {
            color: rgba(238, 242, 255, 0.46);
        }

        body.theme-dark .student-sidebar-link {
            color: rgba(238, 242, 255, 0.76);
        }

        body.theme-dark .student-sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 119, 255, 0.16);
        }

        body.theme-dark .student-sidebar-badge {
            background: rgba(255, 255, 255, 0.12);
            color: #eef2ff;
        }

        body.theme-dark .student-sidebar-user {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(139, 119, 255, 0.14);
        }

        body.theme-dark .theme-toggle {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 119, 255, 0.2);
            color: #eef2ff;
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.24);
        }

        body.theme-dark .nav-menu-toggle {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 119, 255, 0.2);
            color: #eef2ff;
        }

        body.theme-dark .nav-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        body.theme-dark .theme-toggle-icon {
            color: #c4b5fd;
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 24px 50px rgba(109, 40, 217, 0.2);
        }

        body.theme-dark .hero-card {
            background: rgba(27, 14, 51, 0.95);
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
                padding: 14px 16px;
            }

            .brand-bubble {
                width: 58px;
            }

            .public-nav {
                flex-wrap: wrap;
                align-items: center;
            }

            .nav-menu-toggle {
                display: inline-flex;
                margin-left: auto;
            }

            .public-nav-panel {
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                padding-top: 16px;
                margin-top: 8px;
                border-top: 1px solid var(--line);
            }

            .public-nav.is-open .public-nav-panel {
                display: flex;
            }

            .nav-links,
            .actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .nav-links a,
            .nav-action-button,
            .nav-user-name,
            .public-nav .actions .theme-toggle {
                width: 100%;
                justify-content: center;
            }

            .nav-links a {
                padding: 12px 14px;
                border-radius: 16px;
            }

            .inline-form {
                display: block;
                width: 100%;
            }

            .inline-form button {
                width: 100%;
            }

            .nav-user-name {
                padding: 10px 14px;
                border: 1px solid var(--line);
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.76);
            }

            body.theme-dark .nav-user-name {
                background: rgba(255, 255, 255, 0.04);
                border-color: rgba(139, 119, 255, 0.18);
            }

            .main-pages-footer .footer-nav,
            .main-pages-footer .footer-meta {
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
<body class="@yield('body_class') {{ auth()->check() && auth()->user()->theme === 'dark' ? 'theme-dark' : '' }}">
<script>
    (function () {
        const storageKey = 'etuaide-theme';
        const body = document.body;
        const savedTheme = window.localStorage.getItem(storageKey);

        if (savedTheme === 'dark') {
            body.classList.add('theme-dark');
        } else if (savedTheme === 'light') {
            body.classList.remove('theme-dark');
        }
    })();
</script>
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser && $currentUser->role === \App\Models\User::ROLE_ADMIN;
    $unreadNotificationCount = $currentUser?->notifications()->whereNull('read_at')->count() ?? 0;
    $isAppShell = $currentUser
        && request()->routeIs('dashboard', 'tasks.*', 'calendar.*', 'categories.*', 'notifications.*', 'settings.*', 'admin.*');
    $isMainMarketingPage = request()->routeIs('home', 'about', 'faq', 'contact');
@endphp

@if ($isAppShell)
    <div class="student-shell">
        <aside class="student-sidebar">
            <div class="student-sidebar-brand-shell">
                <div class="student-sidebar-brand-top">
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="student-sidebar-brand-mark" aria-label="Go to workspace home">
                        <span class="brand-bubble">
                            <img class="brand-logo brand-logo--light" src="{{ $lightLogoUrl }}" alt="EtuAide logo">
                            <img class="brand-logo brand-logo--dark" src="{{ $darkLogoUrl }}" alt="EtuAide logo">
                        </span>
                    </a>
                    <button
                        type="button"
                        class="theme-toggle student-sidebar-theme-toggle"
                        data-theme-toggle
                        aria-label="Switch color theme"
                        @if ($currentUser)
                            data-theme-endpoint="{{ route('settings.theme') }}"
                            data-theme-token="{{ csrf_token() }}"
                        @endif
                    >
                        <span class="theme-toggle-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" data-theme-icon="sun">
                                <path d="M12 4.75a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0V5.5a.75.75 0 0 1 .75-.75Zm0 12.25a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 12 17Zm7.25-5.75a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1 0-1.5h1.5Zm-13 0a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1 0-1.5h1.5Zm9.016-4.766a.75.75 0 0 1 1.06 0l1.061 1.061a.75.75 0 0 1-1.06 1.061l-1.061-1.06a.75.75 0 0 1 0-1.062Zm-7.603 7.603a.75.75 0 0 1 1.06 0l1.062 1.061a.75.75 0 1 1-1.06 1.06l-1.062-1.06a.75.75 0 0 1 0-1.061Zm8.663 1.061a.75.75 0 0 1 1.06-1.06l1.061 1.06a.75.75 0 1 1-1.06 1.061l-1.061-1.061ZM8.724 7.545a.75.75 0 0 1 0 1.06l-1.06 1.061a.75.75 0 0 1-1.062-1.06l1.061-1.061a.75.75 0 0 1 1.061 0ZM12 8.25a3.75 3.75 0 1 1 0 7.5 3.75 3.75 0 0 1 0-7.5Z"/>
                            </svg>
                            <svg viewBox="0 0 24 24" data-theme-icon="moon" hidden>
                                <path d="M14.5 3.32a.75.75 0 0 1 .83.98 7.75 7.75 0 1 0 9.37 9.37.75.75 0 0 1 .98.83A9.25 9.25 0 1 1 14.5 3.32Z"/>
                            </svg>
                        </span>
                        <span class="sr-only" data-theme-label>Dark mode</span>
                    </button>
                </div>
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="student-sidebar-brand">
                    <span class="student-sidebar-copy">
                        <strong>Planner</strong>
                        <span>{{ $isAdmin ? 'Admin workspace' : 'Student workspace' }}</span>
                    </span>
                </a>
            </div>

            <nav class="student-sidebar-nav" aria-label="{{ $isAdmin ? 'Admin navigation' : 'Student navigation' }}">
                <div class="student-sidebar-section-label">Workspace</div>
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
                <a href="{{ route('notifications.index') }}" class="student-sidebar-link {{ request()->routeIs('notifications.*') ? 'is-active' : '' }}" data-notifications-nav>
                    <span>Notifications</span>
                    @if ($unreadNotificationCount > 0)
                        <span class="student-sidebar-badge">{{ $unreadNotificationCount }}</span>
                    @endif
                </a>
                <a href="{{ route('settings.edit') }}" class="student-sidebar-link {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">
                    <span>Settings</span>
                </a>

                @if ($isAdmin)
                    <div class="student-sidebar-section-label">Administration</div>
                    <a href="{{ route('admin.dashboard') }}" class="student-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <span>Admin Overview</span>
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="student-sidebar-link {{ request()->routeIs('admin.students.*') ? 'is-active' : '' }}">
                        <span>Students</span>
                    </a>
                @endif

                <a href="{{ route('home') }}" class="student-sidebar-link">
                    <span>Home</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="student-sidebar-logout">
                    @csrf
                    <button type="submit" class="secondary">Log out</button>
                </form>
            </nav>
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
            <div class="nav public-nav" data-public-nav>
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-bubble">
                        <img class="brand-logo brand-logo--light" src="{{ $lightLogoUrl }}" alt="EtuAide logo">
                        <img class="brand-logo brand-logo--dark" src="{{ $darkLogoUrl }}" alt="EtuAide logo">
                    </span>
                    <span>Planner</span>
                </a>
                <button
                    type="button"
                    class="nav-menu-toggle"
                    data-nav-toggle
                    aria-expanded="false"
                    aria-controls="public-nav-panel"
                    aria-label="Toggle navigation menu"
                >
                    <span class="nav-menu-toggle-box" aria-hidden="true">
                        <span class="nav-menu-toggle-line"></span>
                        <span class="nav-menu-toggle-line"></span>
                        <span class="nav-menu-toggle-line"></span>
                    </span>
                    <span class="sr-only">Menu</span>
                </button>
                <div class="public-nav-panel" id="public-nav-panel" data-nav-panel>
                    <div class="nav-links">
                        <a href="{{ route('home') }}#home">
                            <span class="nav-link-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 3.3 3.8 10a1 1 0 0 0-.3.74V20a1 1 0 0 0 1 1H9v-5.25a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1V21h4.5a1 1 0 0 0 1-1v-9.26a1 1 0 0 0-.36-.77L12 3.3Z"/>
                                </svg>
                            </span>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('home') }}#about">
                            <span class="nav-link-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 2.75a9.25 9.25 0 1 0 9.25 9.25A9.26 9.26 0 0 0 12 2.75Zm0 4a1.2 1.2 0 1 1-1.2 1.2A1.2 1.2 0 0 1 12 6.75Zm1.25 10.5h-2.5a.75.75 0 0 1 0-1.5h.5v-3h-.5a.75.75 0 0 1 0-1.5H12a.75.75 0 0 1 .75.75v3.75h.5a.75.75 0 0 1 0 1.5Z"/>
                                </svg>
                            </span>
                            <span>About</span>
                        </a>
                        <a href="{{ route('home') }}#faq">
                            <span class="nav-link-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 2.75a9.25 9.25 0 1 0 9.25 9.25A9.26 9.26 0 0 0 12 2.75Zm0 14.5a1.05 1.05 0 1 1 1.05-1.05A1.05 1.05 0 0 1 12 17.25Zm1.14-4.78-.63.37a.98.98 0 0 0-.51.84.75.75 0 0 1-1.5 0 2.47 2.47 0 0 1 1.26-2.14l.62-.36a1.67 1.67 0 1 0-2.5-1.45.75.75 0 0 1-1.5 0 3.17 3.17 0 1 1 4.76 2.74Z"/>
                                </svg>
                            </span>
                            <span>FAQ</span>
                        </a>
                        <a href="{{ route('home') }}#contact">
                            <span class="nav-link-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M4.75 5.5A2.75 2.75 0 0 1 7.5 2.75h9a2.75 2.75 0 0 1 2.75 2.75v13A2.75 2.75 0 0 1 16.5 21.25h-9A2.75 2.75 0 0 1 4.75 18.5v-13Zm2.4.5 4.35 4.02a.75.75 0 0 0 1 0L16.85 6h-9.7Zm10.6 1-4.23 3.9a2.25 2.25 0 0 1-3.04 0L6.25 7v11.5c0 .69.56 1.25 1.25 1.25h9c.69 0 1.25-.56 1.25-1.25V7Z"/>
                                </svg>
                            </span>
                            <span>Contact</span>
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}">
                                <span class="nav-link-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M4.75 4.75h6.5v6.5h-6.5Zm8 0h6.5v9h-6.5Zm-8 8h6.5v6.5h-6.5Zm8 10v-4.5h6.5v4.5Zm1.5-16.5v6h3.5v-6Zm-8 8v3.5h3.5v-3.5Zm8 5.5v1.5h3.5v-1.5Z"/>
                                    </svg>
                                </span>
                                <span>Dashboard</span>
                            </a>
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}">
                                    <span class="nav-link-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="m12 2.75 7.25 2.9v5.83c0 4.25-2.55 8.14-6.47 9.88a1.93 1.93 0 0 1-1.56 0C7.3 19.62 4.75 15.73 4.75 11.48V5.65L12 2.75Zm0 1.62L6.25 6.67v4.81c0 3.64 2.17 6.97 5.51 8.46.15.07.33.07.48 0 3.34-1.49 5.51-4.82 5.51-8.46V6.67L12 4.37Zm-.75 4.38h1.5v3.5h3a.75.75 0 0 1 0 1.5h-3.75a.75.75 0 0 1-.75-.75v-4.25Z"/>
                                        </svg>
                                    </span>
                                    <span>Admin</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                    <div class="actions">
                        <button
                            type="button"
                            class="theme-toggle"
                            data-theme-toggle
                            aria-label="Switch color theme"
                            @if ($currentUser)
                                data-theme-endpoint="{{ route('settings.theme') }}"
                                data-theme-token="{{ csrf_token() }}"
                            @endif
                        >
                            <span class="theme-toggle-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" data-theme-icon="sun">
                                    <path d="M12 4.75a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0V5.5a.75.75 0 0 1 .75-.75Zm0 12.25a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 12 17Zm7.25-5.75a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1 0-1.5h1.5Zm-13 0a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1 0-1.5h1.5Zm9.016-4.766a.75.75 0 0 1 1.06 0l1.061 1.061a.75.75 0 0 1-1.06 1.061l-1.061-1.06a.75.75 0 0 1 0-1.062Zm-7.603 7.603a.75.75 0 0 1 1.06 0l1.062 1.061a.75.75 0 1 1-1.06 1.06l-1.062-1.06a.75.75 0 0 1 0-1.061Zm8.663 1.061a.75.75 0 0 1 1.06-1.06l1.061 1.06a.75.75 0 1 1-1.06 1.061l-1.061-1.061ZM8.724 7.545a.75.75 0 0 1 0 1.06l-1.06 1.061a.75.75 0 0 1-1.062-1.06l1.061-1.061a.75.75 0 0 1 1.061 0ZM12 8.25a3.75 3.75 0 1 1 0 7.5 3.75 3.75 0 0 1 0-7.5Z"/>
                                </svg>
                                <svg viewBox="0 0 24 24" data-theme-icon="moon" hidden>
                                    <path d="M14.5 3.32a.75.75 0 0 1 .83.98 7.75 7.75 0 1 0 9.37 9.37.75.75 0 0 1 .98.83A9.25 9.25 0 1 1 14.5 3.32Z"/>
                                </svg>
                            </span>
                            <span class="sr-only" data-theme-label>Dark mode</span>
                        </button>
                        @auth
                            <span class="muted nav-user-name">
                                <span class="nav-action-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 12.25a4.75 4.75 0 1 0-4.75-4.75A4.76 4.76 0 0 0 12 12.25Zm0 1.5c-4.03 0-7.25 2.15-7.25 4.75a.75.75 0 0 0 .75.75h13a.75.75 0 0 0 .75-.75c0-2.6-3.22-4.75-7.25-4.75Z"/>
                                    </svg>
                                </span>
                                <span>{{ auth()->user()->name }}</span>
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline-form">
                                @csrf
                                <button type="submit" class="secondary nav-action-button">
                                    <span class="nav-action-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24">
                                            <path d="M10.75 4.75a.75.75 0 0 1 0 1.5h-3.5v11.5h3.5a.75.75 0 0 1 0 1.5H6.5a.75.75 0 0 1-.75-.75V5.5a.75.75 0 0 1 .75-.75Zm5.72 3.97a.75.75 0 0 1 1.06 0l2.75 2.75a.75.75 0 0 1 0 1.06l-2.75 2.75a.75.75 0 1 1-1.06-1.06l1.47-1.47h-7.19a.75.75 0 0 1 0-1.5h7.19l-1.47-1.47a.75.75 0 0 1 0-1.06Z"/>
                                        </svg>
                                    </span>
                                    <span>Logout</span>
                                </button>
                            </form>
                        @else
                            <a class="btn secondary nav-action-button" href="{{ route('login') }}">
                                <span class="nav-action-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M10.75 4.75a.75.75 0 0 1 0 1.5h-3.5v11.5h3.5a.75.75 0 0 1 0 1.5H6.5a.75.75 0 0 1-.75-.75V5.5a.75.75 0 0 1 .75-.75Zm5.72 3.97a.75.75 0 0 1 1.06 0l2.75 2.75a.75.75 0 0 1 0 1.06l-2.75 2.75a.75.75 0 1 1-1.06-1.06l1.47-1.47h-7.19a.75.75 0 0 1 0-1.5h7.19l-1.47-1.47a.75.75 0 0 1 0-1.06Z"/>
                                    </svg>
                                </span>
                                <span>Login</span>
                            </a>
                            <a class="btn nav-action-button" href="{{ route('register') }}">
                                <span class="nav-action-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 12.25a4.75 4.75 0 1 0-4.75-4.75A4.76 4.76 0 0 0 12 12.25Zm0 1.5c-4.03 0-7.25 2.15-7.25 4.75a.75.75 0 0 0 .75.75h13a.75.75 0 0 0 .75-.75c0-2.6-3.22-4.75-7.25-4.75Zm6.25-6.5h1.5v1.5h1.5a.75.75 0 0 1 0 1.5h-1.5v1.5a.75.75 0 0 1-1.5 0v-1.5h-1.5a.75.75 0 0 1 0-1.5h1.5Z"/>
                                    </svg>
                                </span>
                                <span>Sign Up</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="public-main {{ $isMainMarketingPage ? 'is-marketing' : '' }}">
            <div class="container">
                @if (session('status'))
                    <div class="status">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>
        </main>

        @if ($isMainMarketingPage)
            <footer class="main-pages-footer">
                <div class="footer-nav">
                    <a href="{{ route('home') }}" class="brand">
                        <span class="brand-bubble">
                            <img class="brand-logo brand-logo--light" src="{{ $lightLogoUrl }}" alt="EtuAide logo">
                            <img class="brand-logo brand-logo--dark" src="{{ $darkLogoUrl }}" alt="EtuAide logo">
                        </span>
                        <span>Planner</span>
                    </a>

                    <div class="footer-meta">
                        <div class="social-links" aria-label="Social media links">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.5 22v-8h2.7l.4-3.1h-3.1V9c0-.9.2-1.5 1.6-1.5H17V4.7c-.4-.1-1.6-.2-3-.2-3 0-5 1.8-5 5.2v1.2H6V14h3v8h4.5Z"/>
                                </svg>
                                <span class="visually-hidden">Facebook</span>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M7.5 3h9A4.5 4.5 0 0 1 21 7.5v9a4.5 4.5 0 0 1-4.5 4.5h-9A4.5 4.5 0 0 1 3 16.5v-9A4.5 4.5 0 0 1 7.5 3Zm0 1.8A2.7 2.7 0 0 0 4.8 7.5v9a2.7 2.7 0 0 0 2.7 2.7h9a2.7 2.7 0 0 0 2.7-2.7v-9a2.7 2.7 0 0 0-2.7-2.7h-9Zm9.75 1.35a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1ZM12 7.8A4.2 4.2 0 1 1 7.8 12 4.2 4.2 0 0 1 12 7.8Zm0 1.8A2.4 2.4 0 1 0 14.4 12 2.4 2.4 0 0 0 12 9.6Z"/>
                                </svg>
                                <span class="visually-hidden">Instagram</span>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M6.5 8.8H3.3V20h3.2V8.8ZM4.9 3A1.9 1.9 0 1 0 5 6.8 1.9 1.9 0 0 0 4.9 3Zm15.8 9.9c0-3.4-1.8-5-4.3-5a3.8 3.8 0 0 0-3.4 1.9V8.8H9.9c0 .7 0 11.2 0 11.2H13v-6.2c0-.3 0-.7.1-.9a2.1 2.1 0 0 1 2-1.4c1.4 0 2 1.1 2 2.7V20h3.2v-7.1Z"/>
                                </svg>
                                <span class="visually-hidden">LinkedIn</span>
                            </a>
                            <a href="#" class="social-link" aria-label="X">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.9 4H21l-4.6 5.3L22 20h-4.6l-3.6-5.7L8.8 20H6.7l4.9-5.7L2 4h4.7l3.2 5.1L14.3 4h2.1Zm-1.6 14.4h1.3L6 5.5H4.7l12.6 12.9Z"/>
                                </svg>
                                <span class="visually-hidden">X</span>
                            </a>
                        </div>
                        <span class="footer-copy">&copy; {{ date('Y') }} EtuAide</span>
                    </div>
                </div>
            </footer>
        @endif
    </div>
@endif
<script>
    (function () {
        const toggle = document.querySelector('[data-theme-toggle]');
        const sunIcon = document.querySelector('[data-theme-icon="sun"]');
        const moonIcon = document.querySelector('[data-theme-icon="moon"]');
        const label = document.querySelector('[data-theme-label]');
        const storageKey = 'etuaide-theme';
        const themeEndpoint = toggle?.dataset.themeEndpoint || '';
        const themeToken = toggle?.dataset.themeToken || '';

        if (!toggle || !sunIcon || !moonIcon) {
            return;
        }

        async function persistTheme(theme) {
            if (!themeEndpoint || !themeToken) {
                return;
            }

            try {
                const response = await fetch(themeEndpoint, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': themeToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ theme }),
                });

                if (!response.ok) {
                    throw new Error('Unable to save theme preference.');
                }
            } catch (error) {
                console.error(error);
            }
        }

        function syncThemeToggle() {
            const isDark = document.body.classList.contains('theme-dark');
            const nextLabel = isDark ? 'Light mode' : 'Dark mode';
            toggle.setAttribute('aria-label', nextLabel);
            if (label) {
                label.textContent = nextLabel;
            }
            toggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            sunIcon.hidden = isDark;
            moonIcon.hidden = !isDark;
        }

        toggle.addEventListener('click', function () {
            const willBeDark = !document.body.classList.contains('theme-dark');
            const nextTheme = willBeDark ? 'dark' : 'light';
            document.body.classList.toggle('theme-dark', willBeDark);
            window.localStorage.setItem(storageKey, nextTheme);
            syncThemeToggle();
            void persistTheme(nextTheme);
        });

        syncThemeToggle();
    })();
</script>
<script>
    (function () {
        const nav = document.querySelector('[data-public-nav]');
        const toggle = nav?.querySelector('[data-nav-toggle]');
        const panel = nav?.querySelector('[data-nav-panel]');

        if (!nav || !toggle || !panel) {
            return;
        }

        function closeMenu() {
            nav.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }

        toggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        panel.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    closeMenu();
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                closeMenu();
                toggle.focus();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                closeMenu();
            }
        });
    })();
</script>
</body>
</html>
