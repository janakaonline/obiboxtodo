<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskOverviewControllerEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_completion_overview_should_return_count(): void
    {

        Task::factory(20)->create();

        $response = $this->json('get', '/api/overview/task-completion');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'completed',
                    'todo',
                    'total'
                ]
            ]);
    }

    public function test_tasks_by_priority_should_return_count(): void
    {

        Task::factory(20)->create();

        $response = $this->json('get', '/api/overview/tasks-by-priority');
    
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'high',
                    'medium',
                    'low',
                ]
            ]);
    }
}
