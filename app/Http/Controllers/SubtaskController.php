<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:pending,in_progress,review,done'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['task_id'] = $task->id;

        Subtask::create($data);

        return redirect()->route('tasks.show', $task)->with('status', 'Subtask added.');
    }

    public function update(Request $request, Task $task, Subtask $subtask)
    {
        if ($subtask->task_id !== $task->id) {
            abort(404);
        }

        $this->authorize('update', $subtask);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:pending,in_progress,review,done'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $subtask->update($data);

        return redirect()->route('tasks.show', $task)->with('status', 'Subtask updated.');
    }

    public function destroy(Task $task, Subtask $subtask)
    {
        if ($subtask->task_id !== $task->id) {
            abort(404);
        }

        $this->authorize('delete', $subtask);

        $subtask->delete();

        return redirect()->route('tasks.show', $task)->with('status', 'Subtask deleted.');
    }
}
