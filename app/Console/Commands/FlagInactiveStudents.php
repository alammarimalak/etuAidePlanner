<?php

namespace App\Console\Commands;

use App\Models\AdminAlert;
use App\Models\User;
use Illuminate\Console\Command;

class FlagInactiveStudents extends Command
{
    protected $signature = 'etuaide:flag-inactive';
    protected $description = 'Flag inactive students and create admin alerts.';

    public function handle(): int
    {
        $cutoff = now()->subDays(14);

        $inactiveIds = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->where(function ($query) use ($cutoff) {
                $query->whereNull('last_login_at')
                    ->orWhere('last_login_at', '<', $cutoff)
                    ->orWhereNull('last_activity_at')
                    ->orWhere('last_activity_at', '<', $cutoff);
            })
            ->pluck('id');

        $activeIds = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->whereNotIn('id', $inactiveIds)
            ->pluck('id');

        $adminIds = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->pluck('id');

        if ($adminIds->isEmpty()) {
            $this->info('No admins found.');
            return Command::SUCCESS;
        }

        $created = 0;

        foreach ($adminIds as $adminId) {
            foreach ($inactiveIds as $studentId) {
                $alert = AdminAlert::firstOrCreate([
                    'admin_id' => $adminId,
                    'subject_user_id' => $studentId,
                    'type' => AdminAlert::TYPE_INACTIVE_STUDENT,
                    'status' => AdminAlert::STATUS_OPEN,
                ], [
                    'created_at' => now(),
                ]);

                if ($alert->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        if ($activeIds->isNotEmpty()) {
            AdminAlert::query()
                ->where('status', AdminAlert::STATUS_OPEN)
                ->whereIn('subject_user_id', $activeIds)
                ->update([
                    'status' => AdminAlert::STATUS_RESOLVED,
                    'resolved_at' => now(),
                ]);
        }

        $this->info("Created {$created} alert(s). Inactive students: {$inactiveIds->count()}.");

        return Command::SUCCESS;
    }
}
