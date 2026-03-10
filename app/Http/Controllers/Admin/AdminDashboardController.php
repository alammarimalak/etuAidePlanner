<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAlert;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = $this->currentUser();

        $studentsQuery = User::query()->where('role', User::ROLE_STUDENT);
        $totalStudents = $studentsQuery->count();

        $cutoff = Carbon::now()->subDays(14);

        $inactiveStudents = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->where(function ($query) use ($cutoff) {
                $query->whereNull('last_login_at')
                    ->orWhere('last_login_at', '<', $cutoff)
                    ->orWhereNull('last_activity_at')
                    ->orWhere('last_activity_at', '<', $cutoff);
            })
            ->orderBy('last_activity_at')
            ->get();

        $inactiveCount = $inactiveStudents->count();
        $activeCount = max($totalStudents - $inactiveCount, 0);

        $completedTasks = Task::query()
            ->where('status', Task::STATUS_DONE)
            ->count();

        $pendingTasks = Task::query()
            ->where('status', Task::STATUS_PENDING)
            ->count();

        $openAlerts = AdminAlert::query()
            ->where('admin_id', $admin->id)
            ->where('status', AdminAlert::STATUS_OPEN)
            ->with('subjectUser')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.dashboard', [
            'currentUser' => $admin,
            'totalStudents' => $totalStudents,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'inactiveStudents' => $inactiveStudents,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'openAlerts' => $openAlerts,
        ]);
    }
}
