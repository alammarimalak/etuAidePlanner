<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\AdminAlert;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Reminder;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskOccurrence;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class TestWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'malakammarie369@gmail.com')->first();
        $student = User::where('email', 'alammarimalak17@gmail.com')->first();
        $amal = User::where('email', 'amal@etuaide.test')->first();

        if (!$admin || !$student || !$amal) {
            return;
        }

        $studentNow = CarbonImmutable::now($student->timezone ?? config('app.timezone'));
        $adminNow = CarbonImmutable::now($admin->timezone ?? config('app.timezone'));

        $studentCategories = [
            'Deep Work' => $this->userCategory($student, 'Deep Work'),
            'Exam Prep' => $this->userCategory($student, 'Exam Prep'),
            'Freelance' => $this->userCategory($student, 'Freelance'),
            'Wellness' => $this->userCategory($student, 'Wellness'),
        ];

        $adminCategories = [
            'Student Follow-up' => $this->userCategory($admin, 'Student Follow-up'),
            'Email Campaigns' => $this->userCategory($admin, 'Email Campaigns'),
            'Operations' => $this->userCategory($admin, 'Operations'),
            'Reports' => $this->userCategory($admin, 'Reports'),
        ];

        $studentTasks = [
            $this->upsertTask($student, [
                'category_id' => $studentCategories['Deep Work']?->id,
                'title' => 'Build Laravel study sprint',
                'description' => 'Review routing, middleware, policies, and pagination before the next practical session.',
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_IN_PROGRESS,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($studentNow->setTime(9, 0)),
                'due_at' => $this->toUtc($studentNow->addDay()->setTime(17, 30)),
                'completed_at' => null,
            ]),
            $this->upsertTask($student, [
                'category_id' => $studentCategories['Exam Prep']?->id,
                'title' => 'Prepare database systems exam notes',
                'description' => 'Compress joins, indexing, and normalization into a clean revision sheet.',
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_PENDING,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($studentNow->addDay()->setTime(14, 0)),
                'due_at' => $this->toUtc($studentNow->addDays(2)->setTime(20, 0)),
                'completed_at' => null,
            ]),
            $this->upsertTask($student, [
                'category_id' => $studentCategories['Freelance']?->id,
                'title' => 'Finish client landing page revision',
                'description' => 'Polish the responsive hero, improve spacing, and prepare the review handoff.',
                'priority' => Task::PRIORITY_MEDIUM,
                'status' => Task::STATUS_REVIEW,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($studentNow->subHours(7)),
                'due_at' => $this->toUtc($studentNow->setTime(19, 0)),
                'completed_at' => null,
            ]),
            $this->upsertTask($student, [
                'category_id' => $studentCategories['Wellness']?->id,
                'title' => 'Plan weekly reset routine',
                'description' => 'Organize meals, study blocks, and downtime so the week starts clear and realistic.',
                'priority' => Task::PRIORITY_LOW,
                'status' => Task::STATUS_DONE,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($studentNow->subDays(2)->setTime(18, 0)),
                'due_at' => $this->toUtc($studentNow->subDay()->setTime(21, 0)),
                'completed_at' => $this->toUtc($studentNow->subDay()->setTime(20, 15)),
            ]),
            $this->upsertTask($student, [
                'category_id' => $studentCategories['Deep Work']?->id,
                'title' => 'Morning focus block',
                'description' => 'Protect one recurring hour every morning for concentrated academic work.',
                'priority' => Task::PRIORITY_MEDIUM,
                'status' => Task::STATUS_PENDING,
                'is_recurring' => true,
                'recurrence_rule' => 'FREQ=DAILY;INTERVAL=1',
                'recurrence_timezone' => $student->timezone,
                'start_at' => $this->toUtc($studentNow->setTime(8, 0)),
                'due_at' => $this->toUtc($studentNow->setTime(9, 0)),
                'completed_at' => null,
            ]),
        ];

        $this->upsertSubtask($studentTasks[0], 'Review Laravel route groups', Subtask::STATUS_DONE, 1, $studentNow->subHours(4));
        $this->upsertSubtask($studentTasks[0], 'Practice middleware flow', Subtask::STATUS_IN_PROGRESS, 2);
        $this->upsertSubtask($studentTasks[0], 'Write pagination cheat sheet', Subtask::STATUS_PENDING, 3);
        $this->upsertSubtask($studentTasks[1], 'Summarize B-tree indexing', Subtask::STATUS_PENDING, 1);
        $this->upsertSubtask($studentTasks[1], 'Rewrite normalization examples', Subtask::STATUS_PENDING, 2);
        $this->upsertSubtask($studentTasks[2], 'QA mobile breakpoints', Subtask::STATUS_DONE, 1, $studentNow->subHours(6));
        $this->upsertSubtask($studentTasks[2], 'Send design handoff notes', Subtask::STATUS_REVIEW, 2);

        $this->upsertOccurrence($studentTasks[4], $this->toUtc($studentNow->subDay()->setTime(8, 0)), TaskOccurrence::STATUS_DONE, false, null, $studentNow->subDay()->setTime(8, 58));
        $this->upsertOccurrence($studentTasks[4], $this->toUtc($studentNow->setTime(8, 0)), TaskOccurrence::STATUS_PENDING);
        $this->upsertOccurrence($studentTasks[4], $this->toUtc($studentNow->addDay()->setTime(8, 0)), TaskOccurrence::STATUS_PENDING);

        $this->upsertReminder($studentTasks[0], $student, $this->toUtc($studentNow->setTime(16, 0)), Reminder::CHANNEL_IN_APP, Reminder::STATUS_PENDING);
        $this->upsertReminder($studentTasks[1], $student, $this->toUtc($studentNow->addDay()->setTime(18, 30)), Reminder::CHANNEL_EMAIL, Reminder::STATUS_PENDING);
        $this->upsertReminder($studentTasks[2], $student, $this->toUtc($studentNow->setTime(18, 0)), Reminder::CHANNEL_EMAIL, Reminder::STATUS_SENT, $studentNow->setTime(18, 1));

        $adminTasks = [
            $this->upsertTask($admin, [
                'category_id' => $adminCategories['Student Follow-up']?->id,
                'title' => 'Check inactive student follow-ups',
                'description' => 'Review the open inactivity alerts and decide who needs a reminder today.',
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_IN_PROGRESS,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($adminNow->setTime(9, 30)),
                'due_at' => $this->toUtc($adminNow->setTime(16, 30)),
                'completed_at' => null,
            ]),
            $this->upsertTask($admin, [
                'category_id' => $adminCategories['Email Campaigns']?->id,
                'title' => 'Send motivation email batch',
                'description' => 'Prepare a supportive email for students with unfinished high-priority work this week.',
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_REVIEW,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($adminNow->subHours(2)),
                'due_at' => $this->toUtc($adminNow->addHours(3)),
                'completed_at' => null,
            ]),
            $this->upsertTask($admin, [
                'category_id' => $adminCategories['Reports']?->id,
                'title' => 'Update weekly admin snapshot',
                'description' => 'Refresh student activity totals and export a concise update for the coordination meeting.',
                'priority' => Task::PRIORITY_MEDIUM,
                'status' => Task::STATUS_PENDING,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($adminNow->addDay()->setTime(10, 0)),
                'due_at' => $this->toUtc($adminNow->addDay()->setTime(14, 0)),
                'completed_at' => null,
            ]),
            $this->upsertTask($admin, [
                'category_id' => $adminCategories['Operations']?->id,
                'title' => 'Review notification center QA',
                'description' => 'Check the latest dashboard and notification UI fixes across student and admin views.',
                'priority' => Task::PRIORITY_LOW,
                'status' => Task::STATUS_DONE,
                'is_recurring' => false,
                'recurrence_rule' => null,
                'recurrence_timezone' => null,
                'start_at' => $this->toUtc($adminNow->subDays(1)->setTime(11, 0)),
                'due_at' => $this->toUtc($adminNow->subDays(1)->setTime(15, 0)),
                'completed_at' => $this->toUtc($adminNow->subDays(1)->setTime(14, 20)),
            ]),
        ];

        $this->upsertSubtask($adminTasks[0], 'Review open alert list', Subtask::STATUS_DONE, 1, $adminNow->subHours(3));
        $this->upsertSubtask($adminTasks[0], 'Tag students needing a reminder', Subtask::STATUS_IN_PROGRESS, 2);
        $this->upsertSubtask($adminTasks[1], 'Write motivational subject line', Subtask::STATUS_DONE, 1, $adminNow->subHours(1));
        $this->upsertSubtask($adminTasks[1], 'Prepare recipient selection', Subtask::STATUS_REVIEW, 2);
        $this->upsertSubtask($adminTasks[2], 'Export counts from dashboard', Subtask::STATUS_PENDING, 1);

        $this->upsertReminder($adminTasks[2], $admin, $this->toUtc($adminNow->addDay()->setTime(9, 15)), Reminder::CHANNEL_IN_APP, Reminder::STATUS_PENDING);
        $this->upsertReminder($adminTasks[1], $admin, $this->toUtc($adminNow->setTime(15, 45)), Reminder::CHANNEL_EMAIL, Reminder::STATUS_SENT, $adminNow->setTime(15, 46));

        $this->upsertNotification($student, 'admin_email', 'Quick check-in from Malak Ammarie', 'Hi Malak, I noticed your exam prep is active. Keep going and let me know if you need support before the deadline.', [
            'sender_email' => $admin->email,
            'admin_id' => $admin->id,
            'recipient_email' => $student->email,
        ], $this->toUtc($studentNow->subHours(2)));

        $this->upsertNotification($amal, 'admin_email', 'Project follow-up from Malak Ammarie', 'Your latest progress looks promising. Please keep your notes updated before the weekly review.', [
            'sender_email' => $admin->email,
            'admin_id' => $admin->id,
            'recipient_email' => $amal->email,
        ]);

        $this->upsertNotification($student, 'reminder', 'Upcoming focus block reminder', 'Your recurring morning focus block is scheduled for tomorrow at 8:00 AM.', [
            'task_id' => $studentTasks[4]->id,
            'source' => 'recurring_task',
        ]);

        $this->upsertNotification($admin, 'admin_summary', 'Demo workspace ready', 'Your admin test workspace now includes seeded students, alerts, mail history, and dashboard activity.', [
            'student_email' => $student->email,
        ], $this->toUtc($adminNow->subMinutes(10)));

        $this->upsertActivityLog($student, 'task.updated', 'task', $studentTasks[0]->id, [
            'title' => $studentTasks[0]->title,
            'status' => $studentTasks[0]->status,
        ], $studentNow->subHours(3));

        $this->upsertActivityLog($student, 'task.completed', 'task', $studentTasks[3]->id, [
            'title' => $studentTasks[3]->title,
            'status' => $studentTasks[3]->status,
        ], $studentNow->subDay()->setTime(20, 15));

        $this->upsertActivityLog($admin, 'notification.sent', 'notification', null, [
            'title' => 'Quick check-in from Malak Ammarie',
            'recipient' => $student->email,
        ], $adminNow->subHours(2));

        $this->upsertActivityLog($admin, 'task.updated', 'task', $adminTasks[1]->id, [
            'title' => $adminTasks[1]->title,
            'status' => $adminTasks[1]->status,
        ], $adminNow->subHours(1));

        $this->upsertAdminAlert($admin, $student, AdminAlert::TYPE_INACTIVE_STUDENT, AdminAlert::STATUS_OPEN, $adminNow->subHours(5));
        $this->upsertAdminAlert($admin, $amal, AdminAlert::TYPE_INACTIVE_STUDENT, AdminAlert::STATUS_OPEN, $adminNow->subDay()->setTime(12, 0));
    }

    protected function userCategory(User $user, string $name): ?Category
    {
        return Category::where('user_id', $user->id)
            ->where('name', $name)
            ->first();
    }

    protected function upsertTask(User $user, array $attributes): Task
    {
        return Task::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => $attributes['title'],
            ],
            $attributes + ['user_id' => $user->id],
        );
    }

    protected function upsertSubtask(Task $task, string $title, string $status, int $sortOrder, ?CarbonImmutable $completedAt = null): Subtask
    {
        return Subtask::updateOrCreate(
            [
                'task_id' => $task->id,
                'title' => $title,
            ],
            [
                'status' => $status,
                'sort_order' => $sortOrder,
                'completed_at' => $status === Subtask::STATUS_DONE && $completedAt
                    ? $this->toUtc($completedAt)
                    : null,
            ],
        );
    }

    protected function upsertOccurrence(
        Task $task,
        CarbonImmutable $scheduledAt,
        string $status,
        bool $isOverride = false,
        ?CarbonImmutable $originalScheduledAt = null,
        ?CarbonImmutable $completedAt = null,
    ): TaskOccurrence {
        return TaskOccurrence::updateOrCreate(
            [
                'task_id' => $task->id,
                'scheduled_at' => $scheduledAt,
            ],
            [
                'status' => $status,
                'completed_at' => $status === TaskOccurrence::STATUS_DONE && $completedAt
                    ? $this->toUtc($completedAt)
                    : null,
                'is_override' => $isOverride,
                'original_scheduled_at' => $originalScheduledAt ? $this->toUtc($originalScheduledAt) : null,
            ],
        );
    }

    protected function upsertReminder(
        Task $task,
        User $user,
        CarbonImmutable $remindAt,
        string $channel,
        string $status,
        ?CarbonImmutable $sentAt = null,
    ): Reminder {
        return Reminder::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => $user->id,
                'channel' => $channel,
                'remind_at' => $remindAt,
            ],
            [
                'status' => $status,
                'sent_at' => $status === Reminder::STATUS_SENT && $sentAt
                    ? $this->toUtc($sentAt)
                    : null,
            ],
        );
    }

    protected function upsertNotification(
        User $user,
        string $type,
        string $title,
        string $body,
        array $data = [],
        ?CarbonImmutable $readAt = null,
    ): Notification {
        return Notification::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
            ],
            [
                'body' => $body,
                'data' => $data,
                'read_at' => $readAt,
            ],
        );
    }

    protected function upsertActivityLog(
        User $user,
        string $action,
        ?string $entityType,
        ?int $entityId,
        array $meta,
        CarbonImmutable $createdAt,
    ): ActivityLog {
        return ActivityLog::updateOrCreate(
            [
                'user_id' => $user->id,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'created_at' => $this->toUtc($createdAt),
            ],
            [
                'meta' => $meta,
            ],
        );
    }

    protected function upsertAdminAlert(
        User $admin,
        User $subject,
        string $type,
        string $status,
        CarbonImmutable $createdAt,
    ): AdminAlert {
        return AdminAlert::updateOrCreate(
            [
                'admin_id' => $admin->id,
                'subject_user_id' => $subject->id,
                'type' => $type,
            ],
            [
                'status' => $status,
                'created_at' => $this->toUtc($createdAt),
                'resolved_at' => null,
            ],
        );
    }

    protected function toUtc(CarbonImmutable $dateTime): CarbonImmutable
    {
        return $dateTime->setTimezone(config('app.timezone'));
    }
}
