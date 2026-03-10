<?php

namespace App\Policies;

use App\Models\TaskOccurrence;
use App\Models\User;

class TaskOccurrencePolicy
{
    public function update(User $user, TaskOccurrence $occurrence): bool
    {
        return $occurrence->task && $occurrence->task->user_id === $user->id;
    }
}
