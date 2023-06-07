<?php

namespace Tests\Unit;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\TaskStats\TaskCountOverview;
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
        $dueDate =  new Carbon('2023-12-10');
        $this->repo->create('test task', TaskPriority::High, $dueDate, "some description");

        $result = Task::all();
        $this->assertCount(1, $result->toArray());
        $this->assertEquals('test task', $result[0]->name);
        $this->assertEquals(TaskPriority::High, $result[0]->priority);
        $this->assertTrue($dueDate->equalTo($result[0]->due_date));
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
            'due_date' => new Carbon('2023-10-20'),
        ]);

        $dueDate =  new Carbon('2023-12-10');
        $task = Task::first();

        $result = $this->repo->update($task, 'new task name', TaskPriority::High, $dueDate, 'new description');
        $this->assertTrue($result);

        $updatedTask = Task::first();
        $this->assertEquals('new task name', $updatedTask->name);
        $this->assertEquals(TaskPriority::High, $updatedTask->priority);
        $this->assertEquals('new description', $updatedTask->description);
        $this->assertTrue($dueDate->equalTo($updatedTask->due_date));
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


    public function test_should_return_incomplete_tasks_of_the_given_date()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 10:10:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 2',
            'priority' => TaskPriority::High,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 23:10:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 3',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 08:10:00'),
            'completed' => true,
            'completed_at' => new Carbon('2023-01-23 13:15:00'),
        ]);

        Task::factory()->create([
            'name' => 'task 4',
            'priority' => TaskPriority::Low,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-24 13:15:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        $date = new Carbon('2023-01-23 01:00:00');
        $result = $this->repo->getTasksToDoOn($date);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals("task 1", $result->first()->name);
        $this->assertEquals("task 2", $result->last()->name);
    }

    public function test_getTasksToDoBy_should_return_incomplete_tasks_up_to_the_given_date_by_priority()
    {
        Task::factory()->create([
            'name' => 'task 1',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 10:10:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 2',
            'priority' => TaskPriority::High,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 23:10:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 3',
            'priority' => TaskPriority::Medium,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 08:10:00'),
            'completed' => true,
            'completed_at' => new Carbon('2023-01-23 13:15:00'),
        ]);

        Task::factory()->create([
            'name' => 'task 4',
            'priority' => TaskPriority::Low,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-24 13:15:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        Task::factory()->create([
            'name' => 'task 6',
            'priority' => TaskPriority::Low,
            'description' => 'description',
            'due_date' => new Carbon('2023-01-23 22:10:00'),
            'completed' => false,
            'completed_at' => null,
        ]);

        $date = new Carbon('2023-01-23 01:00:00');
        $result = $this->repo->getTasksToDoOn($date, SortOrder::Desending);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        $this->assertEquals("task 2", $result->first()->name);
        $this->assertEquals(TaskPriority::High, $result->first()->priority);
        $this->assertEquals("task 6", $result->last()->name);
        $this->assertEquals(TaskPriority::Low, $result->last()->priority);
    }
}
