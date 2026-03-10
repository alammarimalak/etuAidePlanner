<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Task;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $user = $this->currentUser();

        $data = $request->validate([
            'remind_at' => ['required', 'date'],
            'channel' => ['required', 'in:email,in_app'],
        ]);

        $data['task_id'] = $task->id;
        $data['user_id'] = $user->id;
        $data['status'] = Reminder::STATUS_PENDING;

        Reminder::create($data);

        return redirect()->route('tasks.show', $task)->with('status', 'Reminder added.');
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $data = $request->validate([
            'remind_at' => ['required', 'date'],
            'channel' => ['required', 'in:email,in_app'],
            'status' => ['required', 'in:pending,sent,failed'],
        ]);

        $reminder->update($data);

        return redirect()->route('tasks.show', $reminder->task_id)->with('status', 'Reminder updated.');
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);

        $taskId = $reminder->task_id;
        $reminder->delete();

        return redirect()->route('tasks.show', $taskId)->with('status', 'Reminder deleted.');
    }
}
