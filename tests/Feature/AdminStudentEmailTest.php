<?php

namespace Tests\Feature;

use App\Mail\AdminStudentEmail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminStudentEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_compose_email_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.students.email.create'));

        $response
            ->assertOk()
            ->assertSee('Create an email')
            ->assertSee('alammarimalak17@gmail.com');
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
                'student_ids' => $students->pluck('id')->all(),
                'subject' => 'Weekly coaching update',
                'message' => 'Please review your open tasks before Friday.',
            ]);

        $response->assertRedirect(route('admin.students.index'));

        Mail::assertSent(AdminStudentEmail::class, 2);
        Mail::assertSent(AdminStudentEmail::class, function (AdminStudentEmail $mail) use ($students) {
            return $mail->senderEmail === 'alammarimalak17@gmail.com'
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
        }
    }
}
