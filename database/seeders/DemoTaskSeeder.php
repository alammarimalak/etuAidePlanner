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

class DemoTaskSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'malakammarie369@gmail.com')->first()
            ?? User::where('email', 'admin@etuaide.test')->first();
        $student = User::where('email', 'alammarimalak17@gmail.com')->first();
        $amal = User::where('email', 'amal@etuaide.test')->first();
        $inactive = User::where('email', 'inactive@etuaide.test')->first();

        if (!$admin || !$student || !$amal || !$inactive) {
            return;
        }

        $study = Category::whereNull('user_id')->where('name', 'Study')->first();
        $projects = Category::whereNull('user_id')->where('name', 'Projects')->first();
        $personal = Category::whereNull('user_id')->where('name', 'Personal')->first();
        $internship = Category::where('user_id', $student->id)->where('name', 'Internship')->first();
        $club = Category::where('user_id', $student->id)->where('name', 'Campus Club')->first();
        $research = Category::where('user_id', $amal->id)->where('name', 'Research')->first();
        $language = Category::where('user_id', $amal->id)->where('name', 'Language Practice')->first();
        $backlog = Category::where('user_id', $inactive->id)->where('name', 'Backlog')->first();

        $studentNow = CarbonImmutable::now($student->timezone ?? config('app.timezone'));
        $amalNow = CarbonImmutable::now($amal->timezone ?? config('app.timezone'));
        $inactiveNow = CarbonImmutable::now($inactive->timezone ?? config('app.timezone'));

        $calculusTask = $this->upsertTask($student, [
            'category_id' => $study?->id,
            'title' => 'Finish calculus exercises',
            'description' => 'Solve the integration worksheet and verify the final two proofs before class.',
            'priority' => Task::PRIORITY_HIGH,
            'status' => Task::STATUS_PENDING,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($studentNow->addDay()->setTime(14, 0)),
            'due_at' => $this->toUtc($studentNow->addDay()->setTime(18, 0)),
            'completed_at' => null,
        ]);

        $presentationTask = $this->upsertTask($student, [
            'category_id' => $projects?->id,
            'title' => 'Prepare operating systems presentation',
            'description' => 'Finalize the slides, add the scheduler benchmark, and rehearse the live demo.',
            'priority' => Task::PRIORITY_MEDIUM,
            'status' => Task::STATUS_IN_PROGRESS,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($studentNow->setTime(10, 0)),
            'due_at' => $this->toUtc($studentNow->addDays(3)->setTime(11, 0)),
            'completed_at' => null,
        ]);

        $reportTask = $this->upsertTask($student, [
            'category_id' => $internship?->id,
            'title' => 'Submit internship weekly report',
            'description' => 'Summarize the completed tickets and note the blockers for next week.',
            'priority' => Task::PRIORITY_HIGH,
            'status' => Task::STATUS_REVIEW,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($studentNow->subDay()->setTime(16, 0)),
            'due_at' => $this->toUtc($studentNow->setTime(17, 30)),
            'completed_at' => null,
        ]);

        $readingTask = $this->upsertTask($student, [
            'category_id' => $study?->id,
            'title' => 'Read database indexing chapter',
            'description' => 'Review clustered vs non-clustered indexes and capture three revision notes.',
            'priority' => Task::PRIORITY_LOW,
            'status' => Task::STATUS_DONE,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($studentNow->subDays(2)->setTime(19, 0)),
            'due_at' => $this->toUtc($studentNow->subDay()->setTime(21, 0)),
            'completed_at' => $this->toUtc($studentNow->subDay()->setTime(20, 20)),
        ]);

        $revisionTask = $this->upsertTask($student, [
            'category_id' => $club?->id ?? $personal?->id,
            'title' => 'Daily revision sprint',
            'description' => 'A short recurring session to keep lecture notes tidy and ready for exams.',
            'priority' => Task::PRIORITY_MEDIUM,
            'status' => Task::STATUS_PENDING,
            'is_recurring' => true,
            'recurrence_rule' => 'FREQ=DAILY;INTERVAL=1',
            'recurrence_timezone' => $student->timezone,
            'start_at' => $this->toUtc($studentNow->setTime(8, 0)),
            'due_at' => $this->toUtc($studentNow->setTime(8, 45)),
            'completed_at' => null,
        ]);

        $this->upsertSubtask($calculusTask, 'Solve exercises 1 to 5', Subtask::STATUS_DONE, 1, $studentNow->subHours(3));
        $this->upsertSubtask($calculusTask, 'Review proof techniques', Subtask::STATUS_IN_PROGRESS, 2);
        $this->upsertSubtask($presentationTask, 'Record demo screenshots', Subtask::STATUS_DONE, 1, $studentNow->subHours(10));
        $this->upsertSubtask($presentationTask, 'Practice speaking notes', Subtask::STATUS_PENDING, 2);
        $this->upsertSubtask($reportTask, 'List shipped tickets', Subtask::STATUS_DONE, 1, $studentNow->subDay());
        $this->upsertSubtask($reportTask, 'Ask mentor to review draft', Subtask::STATUS_REVIEW, 2);

        $this->upsertOccurrence($revisionTask, $this->toUtc($studentNow->subDay()->setTime(8, 0)), TaskOccurrence::STATUS_DONE, false, null, $studentNow->subDay()->setTime(8, 40));
        $this->upsertOccurrence($revisionTask, $this->toUtc($studentNow->setTime(8, 0)), TaskOccurrence::STATUS_PENDING);
        $this->upsertOccurrence($revisionTask, $this->toUtc($studentNow->addDay()->setTime(8, 0)), TaskOccurrence::STATUS_PENDING);

        $this->upsertReminder($calculusTask, $student, $this->toUtc($studentNow->addDay()->setTime(15, 0)), Reminder::CHANNEL_IN_APP, Reminder::STATUS_PENDING);
        $this->upsertReminder($reportTask, $student, $this->toUtc($studentNow->setTime(15, 30)), Reminder::CHANNEL_EMAIL, Reminder::STATUS_SENT, $studentNow->setTime(15, 31));
        $this->upsertReminder($revisionTask, $student, $this->toUtc($studentNow->addDay()->setTime(7, 30)), Reminder::CHANNEL_IN_APP, Reminder::STATUS_PENDING);

        $this->upsertNotification($student, 'task_due', 'Weekly report due today', 'Your internship report is due this evening. Give it one final check before submission.', [
            'task_id' => $reportTask->id,
            'action' => 'review_task',
        ]);
        $this->upsertNotification($student, 'recurring_task', 'Revision sprint scheduled', 'Tomorrow morning\'s revision sprint has been scheduled on your calendar.', [
            'task_id' => $revisionTask->id,
            'occurrence_time' => $this->toUtc($studentNow->addDay()->setTime(8, 0))?->toIso8601String(),
        ], $this->toUtc($studentNow->subHours(1)));

        $this->upsertActivityLog($student, 'task.created', 'task', $calculusTask->id, [
            'title' => $calculusTask->title,
            'status' => $calculusTask->status,
        ], $studentNow->subDays(2)->setTime(9, 15));
        $this->upsertActivityLog($student, 'task.updated', 'task', $presentationTask->id, [
            'title' => $presentationTask->title,
            'status' => $presentationTask->status,
        ], $studentNow->subHours(6));
        $this->upsertActivityLog($student, 'task.completed', 'task', $readingTask->id, [
            'title' => $readingTask->title,
            'status' => $readingTask->status,
        ], $studentNow->subDay()->setTime(20, 20));

        $amalEssay = $this->upsertTask($amal, [
            'category_id' => $research?->id,
            'title' => 'Write AI ethics summary',
            'description' => 'Finish the two-page summary and add references from the latest lecture.',
            'priority' => Task::PRIORITY_HIGH,
            'status' => Task::STATUS_PENDING,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($amalNow->setTime(13, 30)),
            'due_at' => $this->toUtc($amalNow->addDays(2)->setTime(9, 0)),
            'completed_at' => null,
        ]);

        $amalPractice = $this->upsertTask($amal, [
            'category_id' => $language?->id,
            'title' => 'French listening practice',
            'description' => 'Complete two listening exercises and note unfamiliar expressions.',
            'priority' => Task::PRIORITY_LOW,
            'status' => Task::STATUS_DONE,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($amalNow->subDays(3)->setTime(18, 0)),
            'due_at' => $this->toUtc($amalNow->subDays(2)->setTime(20, 0)),
            'completed_at' => $this->toUtc($amalNow->subDays(2)->setTime(19, 20)),
        ]);

        $this->upsertReminder($amalEssay, $amal, $this->toUtc($amalNow->addDay()->setTime(18, 0)), Reminder::CHANNEL_IN_APP, Reminder::STATUS_PENDING);
        $this->upsertNotification($amal, 'motivation', 'Nice progress this week', 'You completed your listening practice. Keep the streak going with the ethics summary.', [
            'task_id' => $amalPractice->id,
        ], $this->toUtc($amalNow->subHours(5)));
        $this->upsertActivityLog($amal, 'task.completed', 'task', $amalPractice->id, [
            'title' => $amalPractice->title,
            'status' => $amalPractice->status,
        ], $amalNow->subDays(2)->setTime(19, 20));

        $inactiveTask = $this->upsertTask($inactive, [
            'category_id' => $backlog?->id,
            'title' => 'Catch up on physics notes',
            'description' => 'Review the missed lectures and summarize the formulas before the next lab.',
            'priority' => Task::PRIORITY_MEDIUM,
            'status' => Task::STATUS_PENDING,
            'is_recurring' => false,
            'recurrence_rule' => null,
            'recurrence_timezone' => null,
            'start_at' => $this->toUtc($inactiveNow->subDays(25)->setTime(9, 0)),
            'due_at' => $this->toUtc($inactiveNow->subDays(18)->setTime(17, 0)),
            'completed_at' => null,
        ]);

        $this->upsertNotification($inactive, 'inactive_reminder', 'We miss you at EtuAide Planner', 'Log back in to review your open tasks and keep your semester on track.', [
            'sent_by' => 'admin',
            'admin_id' => $admin->id,
        ]);
        $this->upsertActivityLog($inactive, 'task.created', 'task', $inactiveTask->id, [
            'title' => $inactiveTask->title,
            'status' => $inactiveTask->status,
        ], $inactiveNow->subDays(25)->setTime(9, 5));

        $this->upsertAdminAlert($admin, $inactive, AdminAlert::TYPE_INACTIVE_STUDENT, AdminAlert::STATUS_OPEN, $studentNow->subHours(12));
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
