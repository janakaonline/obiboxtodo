<?php

namespace Tests\Unit;

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\TaskStats\TaskCountOverview;
use App\Repositories\TaskStatRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TaskStatRepositoyTest extends TestCase
{
    use RefreshDatabase;

    private TaskStatRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo =  new TaskStatRepository();
    }

    public function test_should_return_total_num_of_tasks_up_to_date()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => Carbon::now(),
            'completed' => false,
            'completed_at' => null,
        ]);
        Task::factory()->create([
            'name' => 'task 2',
            'priority' => TaskPriority::High,
            'description' => 'description',
            'due_date' => Carbon::now()->subDay(),
            'completed' => false,
            'completed_at' => null,
        ]);
        Task::factory()->create([
            'name' => 'task 3',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => Carbon::now()->subDay(),
            'completed' => true,
            'completed_at' => Carbon::now()->subDay(),
        ]);

        $date = Carbon::now()->endOfDay();
        $result = $this->repo->getTotalNumOfTasksUpToDateByCompletedState($date);

        $this->assertInstanceOf(TaskCountOverview::class, $result);
        $this->assertEquals(1, $result->getCountOf("completed"));
        $this->assertEquals(2, $result->getCountOf("todo"));
        $this->assertEquals(3, $result->getCountOf("total"));
    }

    public function test_total_num_of_tasks_up_to_date_by_completed_state_should_exclude_tasks_due_on_future_dates()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => Carbon::now(),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 2',
            'priority' => TaskPriority::High,
            'description' => 'description',
            'due_date' => Carbon::now()->subDay(),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 3',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => Carbon::now()->subDay(),
            'completed' => true,
            'completed_at' => Carbon::now()->subDay(),
        ]);

        Task::factory()->create([
            'name' => 'task 4',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => Carbon::now()->addDay(),
            'completed' => true,
            'completed_at' => Carbon::now()->subDay(),
        ]);

        Task::factory()->create([
            'name' => 'task 5',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => Carbon::now()->addDay(),
            'completed' => true,
            'completed_at' => Carbon::now()->subDay(),
        ]);

        $date = Carbon::now()->endOfDay();
        $result = $this->repo->getTotalNumOfTasksUpToDateByCompletedState($date);

        $this->assertInstanceOf(TaskCountOverview::class, $result);
        $this->assertEquals(1, $result->getCountOf("completed"));
        $this->assertEquals(2, $result->getCountOf("todo"));
        $this->assertEquals(3, $result->getCountOf("total"));
    }

}
