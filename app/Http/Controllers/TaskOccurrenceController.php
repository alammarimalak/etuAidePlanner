<?php

namespace App\Http\Controllers;

use App\Models\TaskOccurrence;
use Illuminate\Http\Request;

class TaskOccurrenceController extends Controller
{
    public function update(Request $request, TaskOccurrence $occurrence)
    {
        $occurrence->loadMissing('task');
        $this->authorize('update', $occurrence);

        $data = $request->validate([
            'scheduled_at' => ['required', 'date'],
            'status' => ['nullable', 'in:pending,in_progress,review,done'],
        ]);

        if (!$occurrence->original_scheduled_at) {
            $data['original_scheduled_at'] = $occurrence->scheduled_at;
        }

        $data['is_override'] = true;

        $occurrence->update($data);

        return redirect()->back()->with('status', 'Occurrence updated.');
    }
}
