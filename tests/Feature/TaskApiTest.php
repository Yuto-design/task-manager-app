<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_task_api(): void
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_list_own_tasks(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Test Task',
            'description' => 'Description',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $task->id)
            ->assertJsonPath('0.title', 'Test Task')
            ->assertJsonPath('0.status', 'todo');
    }

    public function test_authenticated_user_does_not_see_other_users_tasks(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUser->tasks()->create([
            'title' => 'Other Task',
            'description' => null,
            'status' => 'doing',
        ]);

        $response = $this->actingAs($user)->getJson('/api/tasks');

        $response->assertStatus(200)->assertJsonCount(0);
    }

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => 'todo',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('title', 'New Task')
            ->assertJsonPath('description', 'Task description')
            ->assertJsonPath('status', 'todo')
            ->assertJsonPath('user_id', $user->id);

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'New Task',
            'status' => 'todo',
        ]);
    }

    public function test_create_task_requires_title_and_status(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'description' => 'No title',
            'status' => 'todo',
        ]);
        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => 'No status',
            'description' => null,
        ]);
        $response->assertStatus(422);
    }

    public function test_create_task_accepts_only_valid_status(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => 'Task',
            'description' => null,
            'status' => 'invalid',
        ]);
        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Original',
            'description' => 'Desc',
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
            'status' => 'done',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('title', 'Updated Title')
            ->assertJsonPath('status', 'done');

        $task->refresh();
        $this->assertSame('Updated Title', $task->title);
        $this->assertSame('done', $task->status);
    }

    public function test_authenticated_user_cannot_update_other_users_task(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = $otherUser->tasks()->create([
            'title' => 'Other Task',
            'description' => null,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->putJson("/api/tasks/{$task->id}", [
            'title' => 'Hacked',
            'description' => null,
            'status' => 'done',
        ]);

        $response->assertStatus(403);
        $task->refresh();
        $this->assertSame('Other Task', $task->title);
    }

    public function test_authenticated_user_can_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'To Delete',
            'description' => null,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_authenticated_user_cannot_delete_other_users_task(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = $otherUser->tasks()->create([
            'title' => 'Other Task',
            'description' => null,
            'status' => 'todo',
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
