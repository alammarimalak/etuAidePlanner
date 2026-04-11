@extends('layouts.app')

@push('styles')
    <style>
        .notification-toolbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .notification-toolbar p {
            margin: 8px 0 0;
        }

        .notification-list {
            display: grid;
            gap: 14px;
        }

        .notification-trigger {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 18px 20px;
            background: rgba(255, 255, 255, 0.82);
            box-shadow: none;
            text-align: left;
            color: inherit;
        }

        .notification-trigger:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 30px rgba(33, 86, 245, 0.12);
        }

        .notification-trigger.is-unread {
            border-color: rgba(33, 86, 245, 0.24);
            background: linear-gradient(135deg, rgba(33, 86, 245, 0.14), rgba(255, 255, 255, 0.96));
        }

        .notification-trigger.is-read {
            opacity: 0.88;
        }

        .notification-trigger-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .notification-trigger-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .notification-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .notification-pill.is-unread {
            background: rgba(33, 86, 245, 0.14);
            color: var(--violet-900);
        }

        .notification-pill.is-read {
            background: rgba(16, 25, 53, 0.08);
            color: rgba(5, 8, 22, 0.66);
        }

        .notification-description-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(5, 8, 22, 0.48);
        }

        .notification-description {
            margin: 0;
            font-size: 1rem;
            color: var(--ink);
        }

        .notification-time {
            font-size: 0.88rem;
            color: rgba(5, 8, 22, 0.62);
        }

        .notification-modal-shell[hidden] {
            display: none;
        }

        .notification-modal-shell {
            position: fixed;
            inset: 0;
            z-index: 40;
        }

        .notification-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(5, 8, 22, 0.54);
            backdrop-filter: blur(8px);
        }

        .notification-modal {
            position: relative;
            z-index: 1;
            width: min(680px, calc(100vw - 32px));
            margin: min(8vh, 72px) auto 0;
            padding: 24px;
            border-radius: 28px;
            border: 1px solid rgba(33, 86, 245, 0.18);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(247, 249, 255, 0.96)),
                radial-gradient(circle at top right, rgba(33, 86, 245, 0.12), transparent 30%);
            box-shadow: 0 28px 58px rgba(5, 8, 22, 0.24);
        }

        .notification-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .notification-modal-header h3 {
            margin: 8px 0 0;
            font-size: 1.5rem;
        }

        .notification-modal-close {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.86);
            color: var(--ink);
            box-shadow: none;
        }

        .notification-modal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .notification-modal-card {
            padding: 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.8);
        }

        .notification-modal-card span {
            display: block;
            margin-bottom: 6px;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(5, 8, 22, 0.48);
        }

        .notification-modal-card strong,
        .notification-modal-card p {
            margin: 0;
            color: var(--ink);
        }

        .notification-modal-card p {
            line-height: 1.6;
        }

        .notification-details-list {
            display: grid;
            gap: 10px;
            margin-top: 12px;
        }

        .notification-detail-row {
            display: grid;
            grid-template-columns: minmax(140px, 180px) minmax(0, 1fr);
            gap: 12px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.72);
        }

        .notification-detail-row strong {
            color: rgba(5, 8, 22, 0.7);
        }

        .notification-detail-row span {
            color: var(--ink);
            overflow-wrap: anywhere;
        }

        .notification-modal-empty {
            padding: 16px;
            border-radius: 16px;
            border: 1px dashed var(--line);
            color: rgba(5, 8, 22, 0.62);
            background: rgba(255, 255, 255, 0.48);
        }

        body.theme-dark .notification-trigger {
            background: rgba(9, 18, 42, 0.84);
            border-color: rgba(139, 119, 255, 0.14);
        }

        body.theme-dark .notification-trigger.is-unread {
            background: linear-gradient(135deg, rgba(139, 119, 255, 0.2), rgba(9, 18, 42, 0.94));
            border-color: rgba(139, 119, 255, 0.28);
        }

        body.theme-dark .notification-pill.is-unread {
            background: rgba(139, 119, 255, 0.2);
            color: #eef2ff;
        }

        body.theme-dark .notification-pill.is-read {
            background: rgba(255, 255, 255, 0.08);
            color: rgba(238, 242, 255, 0.7);
        }

        body.theme-dark .notification-description,
        body.theme-dark .notification-modal-card strong,
        body.theme-dark .notification-modal-card p,
        body.theme-dark .notification-detail-row span {
            color: #eef2ff;
        }

        body.theme-dark .notification-description-label,
        body.theme-dark .notification-time,
        body.theme-dark .notification-modal-card span,
        body.theme-dark .notification-detail-row strong,
        body.theme-dark .notification-modal-empty {
            color: rgba(238, 242, 255, 0.7);
        }

        body.theme-dark .notification-modal {
            background:
                linear-gradient(180deg, rgba(9, 18, 42, 0.98), rgba(13, 23, 48, 0.96)),
                radial-gradient(circle at top right, rgba(139, 119, 255, 0.16), transparent 30%);
            border-color: rgba(139, 119, 255, 0.22);
        }

        body.theme-dark .notification-modal-close,
        body.theme-dark .notification-modal-card,
        body.theme-dark .notification-detail-row,
        body.theme-dark .notification-modal-empty {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(139, 119, 255, 0.14);
            color: #eef2ff;
        }

        @media (max-width: 720px) {
            .notification-modal-grid,
            .notification-detail-row,
            .notification-trigger-head {
                grid-template-columns: 1fr;
            }

            .notification-trigger-head {
                display: grid;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $unreadCount = $notifications->whereNull('read_at')->count();
        $notificationPayload = $notifications->map(function ($notification) {
            $details = collect($notification->data ?? [])
                ->mapWithKeys(function ($value, $key) {
                    if (is_bool($value)) {
                        $formattedValue = $value ? 'Yes' : 'No';
                    } elseif (is_array($value)) {
                        $formattedValue = json_encode($value, JSON_UNESCAPED_SLASHES);
                    } elseif ($value === null) {
                        $formattedValue = 'Not provided';
                    } else {
                        $formattedValue = (string) $value;
                    }

                    return [\Illuminate\Support\Str::headline((string) $key) => $formattedValue];
                })
                ->all();

            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'description' => $notification->body,
                'type' => \Illuminate\Support\Str::headline(str_replace('_', ' ', $notification->type)),
                'status' => $notification->read_at ? 'Read' : 'Unread',
                'created_at' => optional($notification->created_at)->format('M d, Y g:i A'),
                'read_at' => optional($notification->read_at)->format('M d, Y g:i A'),
                'is_unread' => $notification->read_at === null,
                'details' => $details,
                'mark_read_url' => route('notifications.read', $notification),
            ];
        })->values();
    @endphp

    <h1>Notifications</h1>

    <div class="card notification-toolbar">
        <div>
            <span class="status">Notification Center</span>
            <p class="muted">
                <strong data-notification-unread-count>{{ $unreadCount }}</strong>
                unread notification{{ $unreadCount === 1 ? '' : 's' }}. Click any notification to open its full details.
            </p>
        </div>

        <form method="POST" action="{{ route('notifications.read_all') }}">
            @csrf
            @method('PATCH')
            <button type="submit" data-mark-all-read-button @disabled($unreadCount === 0)>Mark All Read</button>
        </form>
    </div>

    <div class="notification-list">
        @forelse ($notifications as $notification)
            <button
                type="button"
                class="notification-trigger {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                data-notification-id="{{ $notification->id }}"
            >
                <div class="notification-trigger-head">
                    <div class="notification-trigger-meta">
                        <span class="notification-pill {{ $notification->read_at ? 'is-read' : 'is-unread' }}" data-notification-state>
                            {{ $notification->read_at ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                    <span class="notification-time">{{ $notification->created_at?->diffForHumans() }}</span>
                </div>

                <span class="notification-description-label">Description</span>
                <p class="notification-description">{{ $notification->body }}</p>
            </button>
        @empty
            <div class="card muted">No notifications yet.</div>
        @endforelse
    </div>

    <div class="notification-modal-shell" id="notification-modal-shell" hidden>
        <div class="notification-modal-backdrop" data-notification-close></div>
        <div class="notification-modal" role="dialog" aria-modal="true" aria-labelledby="notification-modal-title">
            <div class="notification-modal-header">
                <div>
                    <span class="status" id="notification-modal-status">Unread</span>
                    <h3 id="notification-modal-title">Notification details</h3>
                </div>
                <button type="button" class="notification-modal-close secondary" data-notification-close aria-label="Close notification details">x</button>
            </div>

            <div class="notification-modal-grid">
                <div class="notification-modal-card">
                    <span>Description</span>
                    <p id="notification-modal-description">-</p>
                </div>
                <div class="notification-modal-card">
                    <span>Title</span>
                    <strong id="notification-modal-title-text">-</strong>
                </div>
                <div class="notification-modal-card">
                    <span>Type</span>
                    <strong id="notification-modal-type">-</strong>
                </div>
                <div class="notification-modal-card">
                    <span>Created At</span>
                    <strong id="notification-modal-created-at">-</strong>
                </div>
                <div class="notification-modal-card">
                    <span>Read At</span>
                    <strong id="notification-modal-read-at">Not read yet</strong>
                </div>
            </div>

            <div>
                <h3>Extra Details</h3>
                <div id="notification-modal-details"></div>
            </div>
        </div>
    </div>

    <script>
        const notificationItems = @json($notificationPayload);
        const notificationMap = new Map(notificationItems.map((item) => [String(item.id), item]));
        const notificationModalShell = document.getElementById('notification-modal-shell');
        const notificationModalStatus = document.getElementById('notification-modal-status');
        const notificationModalTitle = document.getElementById('notification-modal-title');
        const notificationModalTitleText = document.getElementById('notification-modal-title-text');
        const notificationModalDescription = document.getElementById('notification-modal-description');
        const notificationModalType = document.getElementById('notification-modal-type');
        const notificationModalCreatedAt = document.getElementById('notification-modal-created-at');
        const notificationModalReadAt = document.getElementById('notification-modal-read-at');
        const notificationModalDetails = document.getElementById('notification-modal-details');
        const unreadCountLabel = document.querySelector('[data-notification-unread-count]');
        const markAllReadButton = document.querySelector('[data-mark-all-read-button]');
        const csrfToken = '{{ csrf_token() }}';

        const escapeHtml = (value) => String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');

        const renderNotificationDetails = (details) => {
            const entries = Object.entries(details || {});

            if (!entries.length) {
                notificationModalDetails.innerHTML = '<div class="notification-modal-empty">No extra details for this notification.</div>';
                return;
            }

            notificationModalDetails.innerHTML = `
                <div class="notification-details-list">
                    ${entries.map(([label, value]) => `
                        <div class="notification-detail-row">
                            <strong>${escapeHtml(label)}</strong>
                            <span>${escapeHtml(value)}</span>
                        </div>
                    `).join('')}
                </div>
            `;
        };

        const formatDateTime = (value) => {
            if (!value) {
                return 'Just now';
            }

            const date = new Date(value);
            if (Number.isNaN(date.getTime())) {
                return value;
            }

            return new Intl.DateTimeFormat(undefined, {
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
            }).format(date);
        };

        const updateUnreadCount = () => {
            const unreadCount = Array.from(notificationMap.values()).filter((item) => item.is_unread).length;

            if (unreadCountLabel) {
                unreadCountLabel.textContent = unreadCount;
            }

            if (markAllReadButton) {
                markAllReadButton.disabled = unreadCount === 0;
            }

            const sidebarLink = document.querySelector('[data-notifications-nav]');
            if (!sidebarLink) {
                return;
            }

            let badge = sidebarLink.querySelector('.student-sidebar-badge');

            if (unreadCount > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'student-sidebar-badge';
                    sidebarLink.appendChild(badge);
                }

                badge.textContent = unreadCount;
            } else if (badge) {
                badge.remove();
            }
        };

        const markNotificationRead = async (item, trigger) => {
            if (!item.is_unread) {
                return;
            }

            try {
                const response = await fetch(item.mark_read_url, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({}),
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                item.is_unread = false;
                item.status = 'Read';
                item.read_at = formatDateTime(payload.read_at);

                if (trigger) {
                    trigger.classList.remove('is-unread');
                    trigger.classList.add('is-read');

                    const state = trigger.querySelector('[data-notification-state]');
                    if (state) {
                        state.textContent = 'Read';
                        state.classList.remove('is-unread');
                        state.classList.add('is-read');
                    }
                }

                if (notificationModalStatus) {
                    notificationModalStatus.textContent = 'Read';
                }

                if (notificationModalReadAt) {
                    notificationModalReadAt.textContent = item.read_at;
                }

                updateUnreadCount();
            } catch (error) {
                console.error(error);
            }
        };

        const openNotificationModal = async (trigger) => {
            const item = notificationMap.get(String(trigger.dataset.notificationId));

            if (!item || !notificationModalShell) {
                return;
            }

            notificationModalStatus.textContent = item.status;
            notificationModalTitle.textContent = item.title;
            notificationModalTitleText.textContent = item.title;
            notificationModalDescription.textContent = item.description;
            notificationModalType.textContent = item.type;
            notificationModalCreatedAt.textContent = item.created_at || '-';
            notificationModalReadAt.textContent = item.read_at || 'Not read yet';
            renderNotificationDetails(item.details);
            notificationModalShell.hidden = false;
            document.body.style.overflow = 'hidden';

            if (item.is_unread) {
                await markNotificationRead(item, trigger);
            }
        };

        const closeNotificationModal = () => {
            if (!notificationModalShell) {
                return;
            }

            notificationModalShell.hidden = true;
            document.body.style.overflow = '';
        };

        document.querySelectorAll('.notification-trigger').forEach((trigger) => {
            trigger.addEventListener('click', () => openNotificationModal(trigger));
        });

        document.querySelectorAll('[data-notification-close]').forEach((element) => {
            element.addEventListener('click', closeNotificationModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && notificationModalShell && !notificationModalShell.hidden) {
                closeNotificationModal();
            }
        });
    </script>
@endsection
