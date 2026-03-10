<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->currentUser();

        $query = Task::query()
            ->where('user_id', $user->id)
            ->with('category');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority')->value());
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        $tasks = $query->orderByDesc('created_at')->get();

        $categories = Category::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('is_system', true);
            })
            ->orderBy('name')
            ->get();

        return view('tasks.index', [
            'currentUser' => $user,
            'tasks' => $tasks,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $user = $this->currentUser();

        $categories = Category::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('is_system', true);
            })
            ->orderBy('name')
            ->get();

        return view('tasks.create', [
            'currentUser' => $user,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);
        $user = $this->currentUser();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:high,medium,low'],
            'status' => ['required', 'in:pending,in_progress,review,done'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_recurring' => ['nullable', 'boolean'],
            'recurrence_rule' => ['nullable', 'string', 'max:255'],
            'recurrence_timezone' => ['nullable', 'string', 'max:100'],
            'start_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
        ]);

        $data['user_id'] = $user->id;
        $data['is_recurring'] = $request->boolean('is_recurring');

        if ($data['status'] === Task::STATUS_DONE) {
            $data['completed_at'] = now();
        }

        $task = Task::create($data);

        return redirect()->route('tasks.show', $task)->with('status', 'Task created.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $user = $this->currentUser();

        $task->load(['category', 'subtasks', 'reminders', 'occurrences']);

        return view('tasks.show', [
            'currentUser' => $user,
            'task' => $task,
        ]);
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $user = $this->currentUser();

        $categories = Category::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('is_system', true);
            })
            ->orderBy('name')
            ->get();

        return view('tasks.edit', [
            'currentUser' => $user,
            'task' => $task,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $user = $this->currentUser();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:high,medium,low'],
            'status' => ['required', 'in:pending,in_progress,review,done'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_recurring' => ['nullable', 'boolean'],
            'recurrence_rule' => ['nullable', 'string', 'max:255'],
            'recurrence_timezone' => ['nullable', 'string', 'max:100'],
            'start_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
        ]);

        $data['is_recurring'] = $request->boolean('is_recurring');

        if ($data['status'] === Task::STATUS_DONE) {
            $data['completed_at'] = $task->completed_at ?? now();
        } else {
            $data['completed_at'] = null;
        }

        $task->update($data);

        return redirect()->route('tasks.show', $task)->with('status', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Task deleted.');
    }
}
