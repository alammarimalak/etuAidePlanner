<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_page_shows_notification_descriptions(): void
    {
        $user = User::factory()->create();

        Notification::create([
            'user_id' => $user->id,
            'type' => 'task_due',
            'title' => 'Weekly report due',
            'body' => 'Your weekly report is due this evening.',
            'data' => ['task_id' => 12],
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('notifications.index'));

        $response
            ->assertOk()
            ->assertSee('Description')
            ->assertSee('Your weekly report is due this evening.');
    }

    public function test_notification_can_be_marked_read_with_json_request(): void
    {
        $user = User::factory()->create();

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'task_due',
            'title' => 'Weekly report due',
            'body' => 'Your weekly report is due this evening.',
            'data' => ['task_id' => 12],
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson(route('notifications.read', $notification));

        $response
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
            ]);

        $this->assertNotNull($notification->fresh()->read_at);
    }
}
