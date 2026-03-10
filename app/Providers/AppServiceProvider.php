<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Reminder;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskOccurrence;
use App\Policies\CategoryPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\ReminderPolicy;
use App\Policies\SubtaskPolicy;
use App\Policies\TaskOccurrencePolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Subtask::class, SubtaskPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Reminder::class, ReminderPolicy::class);
        Gate::policy(Notification::class, NotificationPolicy::class);
        Gate::policy(TaskOccurrence::class, TaskOccurrencePolicy::class);
    }
}
