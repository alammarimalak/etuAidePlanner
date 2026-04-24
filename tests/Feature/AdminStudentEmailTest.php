<?php

namespace Tests\Feature;

use App\Mail\AdminStudentEmail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class AdminStudentEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_compose_email_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email' => 'admin.one@example.com',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.students.email.create'));

        $response
            ->assertOk()
            ->assertSee('Create an email')
            ->assertSee('admin.one@example.com')
            ->assertSee('Search by student name or email');
    }

    public function test_compose_email_page_shows_the_three_latest_recipients(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email' => 'sender.admin@example.com',
        ]);

        $students = User::factory()->count(4)->create([
            'role' => User::ROLE_STUDENT,
        ]);

        foreach ($students as $index => $student) {
            $notification = Notification::create([
                'user_id' => $student->id,
                'type' => 'admin_email',
                'title' => 'Check-in',
                'body' => 'A recent admin email.',
                'data' => [
                    'admin_id' => $admin->id,
                    'recipient_email' => $student->email,
                ],
            ]);

            $notification->timestamps = false;
            $notification->forceFill([
                'created_at' => now()->subMinutes(4 - $index),
                'updated_at' => now()->subMinutes(4 - $index),
            ])->save();
        }

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.students.email.create'));

        $response
            ->assertOk()
            ->assertSee('data-select-student="' . $students[3]->id . '"', false)
            ->assertSee('data-select-student="' . $students[2]->id . '"', false)
            ->assertSee('data-select-student="' . $students[1]->id . '"', false)
            ->assertDontSee('data-select-student="' . $students[0]->id . '"', false);
    }

    public function test_admin_email_sends_mail_and_creates_in_app_notifications(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $students = User::factory()->count(2)->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.students.email.send'), [
                'student_search' => $students->pluck('email')->implode(' '),
                'student_ids' => $students->pluck('id')->all(),
                'subject' => 'Weekly coaching update',
                'message' => 'Please review your open tasks before Friday.',
            ]);

        $response->assertRedirect(route('admin.students.index'));

        Mail::assertSent(AdminStudentEmail::class, 2);
        Mail::assertSent(AdminStudentEmail::class, function (AdminStudentEmail $mail) use ($students, $admin) {
            return $mail->senderEmail === $admin->email
                && $students->contains(fn (User $student) => $mail->hasTo($student->email));
        });

        $this->assertEquals(2, Notification::query()->count());

        foreach ($students as $student) {
            $this->assertDatabaseHas('notifications', [
                'user_id' => $student->id,
                'type' => 'admin_email',
                'title' => 'Weekly coaching update',
                'body' => 'Please review your open tasks before Friday.',
            ]);

            $this->assertDatabaseHas('notifications', [
                'user_id' => $student->id,
                'type' => 'admin_email',
                'data->sender_email' => $admin->email,
            ]);
        }
    }

    public function test_admin_email_allows_an_empty_recipient_search_bar(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.students.email.send'), [
                'student_ids' => [$student->id],
                'subject' => 'Reminder',
                'message' => 'Please update your task list.',
            ]);

        $response
            ->assertRedirect(route('admin.students.index'));

        Mail::assertSent(AdminStudentEmail::class, 1);
    }

    public function test_admin_email_uses_student_label_in_recipient_validation_message(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.students.email.create'))
            ->post(route('admin.students.email.send'), [
                'subject' => 'Reminder',
                'message' => 'Please update your task list.',
            ]);

        $response
            ->assertRedirect(route('admin.students.email.create'))
            ->assertSessionHasErrors([
                'student_ids' => 'The student field is required.',
            ]);
    }

    public function test_admin_email_failures_render_the_error_page(): void
    {
        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new RuntimeException('Mail transport failed.'));

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.students.email.send'), [
                'student_search' => $student->email,
                'student_ids' => [$student->id],
                'subject' => 'Reminder',
                'message' => 'Please update your task list.',
            ]);

        $response
            ->assertStatus(500)
            ->assertSee('The server ran into a problem.');
    }
}
