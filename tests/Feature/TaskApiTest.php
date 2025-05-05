<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_guest_cannot_access_tasks()
    {
        $this->getJson('/api/tasks')
            ->assertStatus(401);
    }

    public function test_user_can_list_their_tasks()
    {
        $user = $this->authenticate();
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_create_a_task()
    {
        $this->authenticate();

        $data = ['title' => 'Test Task', 'description' => 'This is a test.'];
        $this->postJson('/api/tasks', $data)
            ->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Task']);
    }

    public function test_user_can_update_their_task()
    {
        $user = $this->authenticate();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title'
        ])
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);
    }

    public function test_user_can_delete_their_task()
    {
        $user = $this->authenticate();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->deleteJson("/api/tasks/{$task->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_access_others_tasks()
    {
        $user = $this->authenticate();
        $otherTask = Task::factory()->create(); // belongs to another user

        $this->getJson("/api/tasks/{$otherTask->id}")
            ->assertStatus(403); // or 404 depending on your policy
    }
}
