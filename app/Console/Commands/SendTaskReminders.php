<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Reminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendTaskReminders extends Command
{
    protected $signature = 'etuaide:send-reminders';
    protected $description = 'Send pending task reminders.';

    public function handle(): int
    {
        $reminders = Reminder::query()
            ->where('status', Reminder::STATUS_PENDING)
            ->where('remind_at', '<=', now())
            ->with(['user', 'task'])
            ->get();

        if ($reminders->isEmpty()) {
            $this->info('No reminders to send.');
            return Command::SUCCESS;
        }

        $sent = 0;
        $failed = 0;

        foreach ($reminders as $reminder) {
            $user = $reminder->user;
            $task = $reminder->task;

            if (!$user || !$task || !$user->notifications_enabled) {
                $reminder->update([
                    'status' => Reminder::STATUS_FAILED,
                ]);
                $failed++;
                continue;
            }

            if ($reminder->channel === Reminder::CHANNEL_IN_APP) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'task_reminder',
                    'title' => 'Reminder: ' . $task->title,
                    'body' => 'You scheduled a reminder for this task. Stay on track!',
                    'data' => [
                        'task_id' => $task->id,
                        'reminder_id' => $reminder->id,
                    ],
                ]);

                $reminder->update([
                    'status' => Reminder::STATUS_SENT,
                    'sent_at' => now(),
                ]);
                $sent++;
                continue;
            }

            try {
                Mail::raw(
                    "Reminder: {$task->title}\n\nYou asked to be reminded about this task. Log in to review your progress.",
                    function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('EtuAide Planner Task Reminder');
                    }
                );

                $reminder->update([
                    'status' => Reminder::STATUS_SENT,
                    'sent_at' => now(),
                ]);
                $sent++;
            } catch (Throwable $exception) {
                $reminder->update([
                    'status' => Reminder::STATUS_FAILED,
                ]);
                $failed++;
            }
        }

        $this->info("Sent {$sent} reminder(s), failed {$failed}.");

        return Command::SUCCESS;
    }
}
