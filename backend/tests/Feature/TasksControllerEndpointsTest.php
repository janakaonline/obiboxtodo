<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksControllerEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_should_return_a_list_of_tasks(): void
    {
        
        Task::factory(20)->create();

        $response = $this->json('get', '/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'name',
                        'description',
                        'priority',
                    ]
                ]
            ]);
    }

    public function test_tasks_todo_should_return_a_list_of_tasks_for_today(): void
    {
        
        Task::factory(20)->create();

        $response = $this->json('get', '/api/tasks/due-today');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'name',
                        'description',
                        'priority',
                    ]
                ]
            ]);
    }

    public function test_tasks_todo_should_return_a_list_of_overdue_tasks(): void
    {
        
        Task::factory(20)->create();

        $response = $this->json('get', '/api/tasks/overdue');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'name',
                        'description',
                        'priority',
                    ]
                ]
            ]);
    }
}
