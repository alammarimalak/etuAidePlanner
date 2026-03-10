<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskOccurrence;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateTaskOccurrences extends Command
{
    protected $signature = 'etuaide:generate-occurrences {--days=30}';
    protected $description = 'Generate task occurrences for recurring tasks.';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $horizonStart = now()->startOfDay();
        $horizonEnd = now()->addDays($days)->endOfDay();

        $tasks = Task::query()
            ->where('is_recurring', true)
            ->whereNotNull('recurrence_rule')
            ->get();

        $created = 0;

        foreach ($tasks as $task) {
            $timezone = $task->recurrence_timezone ?? config('app.timezone');
            $seed = $task->start_at ?? $task->due_at ?? now($timezone);
            $seed = $seed->copy()->setTimezone($timezone);

            $rule = $this->parseRule($task->recurrence_rule ?? '');
            $dates = $this->generateDates($rule, $seed, $horizonStart->copy()->setTimezone($timezone), $horizonEnd->copy()->setTimezone($timezone));

            foreach ($dates as $date) {
                $scheduledAt = $date->copy()->setTimeFrom($seed);

                $exists = TaskOccurrence::query()
                    ->where('task_id', $task->id)
                    ->where('scheduled_at', $scheduledAt->toDateTimeString())
                    ->exists();

                if ($exists) {
                    continue;
                }

                TaskOccurrence::create([
                    'task_id' => $task->id,
                    'scheduled_at' => $scheduledAt,
                    'status' => $task->status,
                ]);

                $created++;
            }
        }

        $this->info("Created {$created} occurrence(s). Generated for {$tasks->count()} task(s).");

        return Command::SUCCESS;
    }

    private function parseRule(string $rule): array
    {
        $parts = array_filter(explode(';', strtoupper(trim($rule))));
        $data = [
            'freq' => 'DAILY',
            'interval' => 1,
            'byday' => [],
        ];

        foreach ($parts as $part) {
            [$key, $value] = array_pad(explode('=', $part, 2), 2, null);
            if (!$key || !$value) {
                continue;
            }

            if ($key === 'FREQ') {
                $data['freq'] = $value;
            }

            if ($key === 'INTERVAL') {
                $data['interval'] = max((int) $value, 1);
            }

            if ($key === 'BYDAY') {
                $data['byday'] = array_filter(explode(',', $value));
            }
        }

        return $data;
    }

    private function generateDates(array $rule, Carbon $seed, Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $dates = [];
        $freq = $rule['freq'] ?? 'DAILY';
        $interval = $rule['interval'] ?? 1;
        $byday = $rule['byday'] ?? [];

        if ($freq === 'MONTHLY') {
            $cursor = $seed->copy()->startOfMonth();
            $targetDay = $seed->day;

            while ($cursor->lte($rangeEnd)) {
                $candidate = $cursor->copy()->day(min($targetDay, $cursor->daysInMonth));
                if ($candidate->betweenIncluded($rangeStart, $rangeEnd)) {
                    $dates[] = $candidate;
                }
                $cursor->addMonths($interval);
            }

            return $dates;
        }

        $cursor = $rangeStart->copy();
        $seedWeekStart = $seed->copy()->startOfWeek(Carbon::MONDAY);

        while ($cursor->lte($rangeEnd)) {
            if ($freq === 'WEEKLY') {
                $weeksDiff = $seedWeekStart->diffInWeeks($cursor->copy()->startOfWeek(Carbon::MONDAY));
                $inCycle = $weeksDiff % $interval === 0;
                $dayCode = strtoupper($cursor->format('D'));
                $mapped = [
                    'MON' => 'MO',
                    'TUE' => 'TU',
                    'WED' => 'WE',
                    'THU' => 'TH',
                    'FRI' => 'FR',
                    'SAT' => 'SA',
                    'SUN' => 'SU',
                ][$dayCode] ?? null;

                $allowed = empty($byday) ? $cursor->isSameDay($seed) : in_array($mapped, $byday, true);

                if ($inCycle && $allowed) {
                    $dates[] = $cursor->copy();
                }
            } else {
                $daysDiff = $seed->copy()->startOfDay()->diffInDays($cursor->copy()->startOfDay());
                if ($daysDiff % $interval === 0) {
                    $dates[] = $cursor->copy();
                }
            }

            $cursor->addDay();
        }

        return $dates;
    }
}
