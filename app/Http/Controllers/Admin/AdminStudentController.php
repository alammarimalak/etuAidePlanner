<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminStudentEmail;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdminStudentController extends Controller
{
    private const ADMIN_SENDER_EMAIL = 'alammarimalak17@gmail.com';

    public function index()
    {
        $admin = $this->currentUser();
        $cutoff = Carbon::now()->subDays(14);

        $students = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->withCount([
                'tasks as tasks_completed_count' => function ($query) {
                    $query->where('status', Task::STATUS_DONE);
                },
            ])
            ->orderBy('name')
            ->get();

        $recentlyActiveCount = $students->filter(function (User $student) use ($cutoff) {
            return $student->last_activity_at?->gte($cutoff) ?? false;
        })->count();

        return view('admin.students.index', [
            'currentUser' => $admin,
            'students' => $students,
            'recentlyActiveCount' => $recentlyActiveCount,
            'needsFollowUpCount' => max($students->count() - $recentlyActiveCount, 0),
            'activityCutoff' => $cutoff,
        ]);
    }

    public function createEmail()
    {
        $admin = $this->currentUser();

        try {
            return view('admin.students.create-email', [
                'currentUser' => $admin,
                'students' => $this->studentsForEmailForm(),
                'latestRecipients' => $this->latestRecipientsForEmailForm($admin),
                'senderEmail' => self::ADMIN_SENDER_EMAIL,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            abort(500);
        }
    }

    public function sendEmail(Request $request)
    {
        $admin = $this->currentUser();

        $validator = Validator::make(
            $request->all(),
            [
                'student_search' => ['nullable', 'string', 'max:255'],
                'student_ids' => ['required', 'array', 'min:1'],
                'student_ids.*' => ['integer', 'exists:users,id'],
                'subject' => ['required', 'string', 'max:255'],
                'message' => ['required', 'string'],
            ],
            [
                'student_ids.required' => 'The student field is required.',
                'student_ids.min' => 'The student field is required.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $data = $validator->validated();

        try {
            $studentIds = collect($data['student_ids'])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $students = User::query()
                ->where('role', User::ROLE_STUDENT)
                ->whereIn('id', $studentIds)
                ->orderBy('name')
                ->get();

            if ($students->count() !== $studentIds->count()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['student_ids' => 'Select valid student recipients only.']);
            }

            $sentCount = 0;

            foreach ($students as $student) {
                Mail::to($student->email)->send(new AdminStudentEmail(
                    senderEmail: self::ADMIN_SENDER_EMAIL,
                    recipientName: $student->name,
                    subjectLine: $data['subject'],
                    messageBody: $data['message'],
                ));

                Notification::create([
                    'user_id' => $student->id,
                    'type' => 'admin_email',
                    'title' => $data['subject'],
                    'body' => $data['message'],
                    'data' => [
                        'sender_email' => self::ADMIN_SENDER_EMAIL,
                        'admin_id' => $admin->id,
                        'recipient_email' => $student->email,
                    ],
                ]);

                $sentCount++;
            }

            if ($sentCount === 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        'message' => 'The email could not be sent to the selected students.',
                    ]);
            }

            $status = "Email sent to {$sentCount} student" . ($sentCount === 1 ? '' : 's') . '.';

            return redirect()
                ->route('admin.students.index')
                ->with('status', $status);
        } catch (Throwable $exception) {
            report($exception);

            abort(500);
        }
    }

    public function sendReminder(Request $request, User $student)
    {
        $admin = $this->currentUser();

        if ($student->role !== User::ROLE_STUDENT) {
            abort(404);
        }

        if (!$student->notifications_enabled) {
            return redirect()->back()->with('status', 'Notifications are disabled for this student.');
        }

        Notification::create([
            'user_id' => $student->id,
            'type' => 'inactive_reminder',
            'title' => 'We miss you at EtuAide Planner',
            'body' => 'It looks like you have been inactive. Log in to review your tasks and stay on track.',
            'data' => [
                'sent_by' => 'admin',
                'admin_id' => $admin->id,
            ],
        ]);

        return redirect()->back()->with('status', 'Reminder queued.');
    }

    protected function studentsForEmailForm()
    {
        return User::query()
            ->where('role', User::ROLE_STUDENT)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    protected function latestRecipientsForEmailForm(User $admin)
    {
        return Notification::query()
            ->where('type', 'admin_email')
            ->where('data->admin_id', $admin->id)
            ->with('user:id,name,email,role')
            ->latest()
            ->get()
            ->unique('user_id')
            ->map(fn (Notification $notification) => $notification->user)
            ->filter(fn ($user) => $user instanceof User && $user->role === User::ROLE_STUDENT)
            ->take(3)
            ->values();
    }
}
