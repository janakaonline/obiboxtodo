<?php

namespace Tests\Unit;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TaskRepositoyTest extends TestCase
{
    use RefreshDatabase;

    private TaskRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo =  new TaskRepository();
    }

    public function test_should_return_tasks_as_collection()
    {
        Task::factory()->count(10)->create();

        $result = $this->repo->getTasks();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(10, $result->toArray());
    }

    public function test_should_filter_by_name()
    {
        Task::factory()->create([
            'name' => 'test 1'
        ]);
        Task::factory()->create([
            'name' => 'test 2'
        ]);
        Task::factory()->create([
            'name' => 'some task'
        ]);

        $result = $this->repo->getTasks("test 1");
        $this->assertCount(1, $result->toArray());

        $result = $this->repo->getTasks("test");
        $this->assertCount(2, $result->toArray());

        $result = $this->repo->getTasks("invalid task");
        $this->assertCount(0, $result->toArray());
    }

    public function test_should_filter_by_priority()
    {
        Task::factory()->create([
            'priority' => TaskPriority::Low
        ]);
        Task::factory()->create([
            'priority' => TaskPriority::Low
        ]);
        Task::factory()->create([
            'priority' => TaskPriority::High
        ]);

        $result = $this->repo->getTasks(null, TaskPriority::Low);
        $this->assertCount(2, $result->toArray());

        $result = $this->repo->getTasks(null, TaskPriority::High);
        $this->assertCount(1, $result->toArray());
    }

    public function test_should_filter_by_completed_status()
    {
        Task::factory()->create([
            'completed' => true
        ]);
        Task::factory()->create([
            'completed' => true
        ]);
        Task::factory()->create([
            'completed' => false
        ]);

        $result = $this->repo->getTasks(null, null, true);
        $this->assertCount(2, $result->toArray());

        $result = $this->repo->getTasks(null, null, false);
        $this->assertCount(1, $result->toArray());
    }

    public function test_should_sort_by_created_at()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'created_at' => '2023-01-01 00:00:00'
        ]);
        Task::factory()->create([
            'name' => 'task 2',
            'created_at' => '2023-10-01 00:00:00'
        ]);
        Task::factory()->create([
            'name' => 'task 3',
            'created_at' => '2023-02-20 00:00:00'
        ]);

        $result = $this->repo->getTasks(null, null, null, SortOrder::Assending);
        $this->assertEquals('task 1', $result[0]->name);
        $this->assertEquals('task 3', $result[1]->name);
        $this->assertEquals('task 2', $result[2]->name);

        $result = $this->repo->getTasks(null, null, null, SortOrder::Desending);
        $this->assertEquals('task 2', $result[0]->name);
        $this->assertEquals('task 3', $result[1]->name);
        $this->assertEquals('task 1', $result[2]->name);
    }

    public function test_should_create_a_task_with_the_given_data_successfully()
    {
        $this->repo->create('test task', TaskPriority::High, "some description");

        $result = Task::all();
        $this->assertCount(1, $result->toArray());
        $this->assertEquals('test task', $result[0]->name);
        $this->assertEquals(TaskPriority::High, $result[0]->priority);
        $this->assertEquals('some description', $result[0]->description);
        $this->assertFalse($result[0]->completed);
        $this->assertNotEmpty($result[0]->created_at);
    }

    public function test_should_update_a_task_with_the_new_data_successfully()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'wrong description',
        ]);

        $task = Task::first();

        $result = $this->repo->update($task, 'new task name', TaskPriority::High, 'new description');
        $this->assertTrue($result);

        $updatedTask = Task::first();
        $this->assertEquals('new task name', $updatedTask->name);
        $this->assertEquals(TaskPriority::High, $updatedTask->priority);
        $this->assertEquals('new description', $updatedTask->description);
    }

    public function test_should_mark_a_task_as_completed_successfully()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'completed' => false,
            'completed_at' => null,
        ]);

        $task = Task::first();

        $result = $this->repo->markAsComplete($task);
        $this->assertTrue($result);

        $updatedTask = Task::first();
        $this->assertEquals('task 1', $updatedTask->name);
        $this->assertTrue($updatedTask->completed);
        $this->assertNotEmpty($updatedTask->completed_at);
        $this->assertTrue($updatedTask->completed_at->isAfter(Carbon::now()->subSeconds(2)));
        $this->assertTrue($updatedTask->updated_at->isAfter(Carbon::now()->subSeconds(2)));
    }

    public function test_already_updated_task_should_not_be_updated_again()
    {
        $pastDateTime = new Carbon("2023-01-01 00:00:00");
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'completed' => true,
            'completed_at' => $pastDateTime,
            'created_at' => $pastDateTime,
            'updated_at' => $pastDateTime,
        ]);

        $task = Task::first();

        $result = $this->repo->markAsComplete($task);
        $this->assertTrue($result);

        $updatedTask = Task::first();
        $this->assertEquals('task 1', $updatedTask->name);
        $this->assertTrue($updatedTask->completed);
        $this->assertNotEmpty($updatedTask->completed_at);
        $this->assertTrue($updatedTask->completed_at->eq($pastDateTime));
        $this->assertTrue($updatedTask->updated_at->eq($pastDateTime));
    }

    public function test_should_mark_a_completed_task_as_an_incompleted_task_successfully()
    {
        $pastDateTime = new Carbon("2023-01-01 00:00:00");
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'completed' => true,
            'completed_at' => $pastDateTime,
            'created_at' => $pastDateTime,
            'updated_at' => $pastDateTime,
        ]);

        $task = Task::first();

        $result = $this->repo->markAsInComplete($task);
        $this->assertTrue($result);

        $updatedTask = Task::first();
        $this->assertEquals('task 1', $updatedTask->name);
        $this->assertFalse($updatedTask->completed);
        $this->assertEmpty($updatedTask->completed_at);
        $this->assertTrue($updatedTask->updated_at->isAfter(Carbon::now()->subSeconds(2)));
    }
}