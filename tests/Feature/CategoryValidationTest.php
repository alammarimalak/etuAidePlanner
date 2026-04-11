<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_creation_requires_name_and_color(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('categories.index'))
            ->post(route('categories.store'), [
                'name' => '',
                'color' => '',
            ]);

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHasErrors(['name', 'color']);
    }

    public function test_category_update_requires_name_and_color(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Study',
            'color' => '#3B82F6',
            'is_system' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('categories.index'))
            ->patch(route('categories.update', $category), [
                'name' => '',
                'color' => '',
            ]);

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHasErrors(['name', 'color']);
    }
}
