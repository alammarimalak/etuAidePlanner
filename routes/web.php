<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskOccurrenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    Route::resource('tasks', TaskController::class);

    Route::post('tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('tasks.subtasks.store');
    Route::patch('tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('tasks.subtasks.update');
    Route::delete('tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('tasks.subtasks.destroy');

    Route::patch('occurrences/{occurrence}', [TaskOccurrenceController::class, 'update'])->name('occurrences.update');

    Route::post('tasks/{task}/reminders', [ReminderController::class, 'store'])->name('tasks.reminders.store');
    Route::patch('reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read_all');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::post('students/{student}/remind', [AdminStudentController::class, 'sendReminder'])->name('students.remind');
    });
});
